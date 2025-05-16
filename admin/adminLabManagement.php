<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <style>
        .seat-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            margin-top: 15px;
        }
        .seat-btn {
            width: 100%;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .seat-btn:hover:not(.unavailable) {
            transform: scale(1.05);
        }
        .seat-btn.available {
            background-color: #28a745;
            color: white;
        }
        .seat-btn.unavailable {
            background-color: #dc3545;
            color: white;
            cursor: not-allowed;
        }
        .status-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        .status-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .status-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <?php include 'adminHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    <main class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Laboratory Seat Management</h5>
            </div>
            <div class="card-body">
                <div class="status-legend">
                    <div class="status-item">
                        <div class="status-color" style="background-color: #28a745;"></div>
                        <span>Available</span>
                    </div>
                    <div class="status-item">
                        <div class="status-color" style="background-color: #dc3545;"></div>
                        <span>Unavailable</span>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="managementLab" class="form-label">Laboratory</label>
                        <select class="form-select" id="managementLab" required>
                            <option value="" disabled selected>Select Lab</option>
                            <option value="517">517</option>
                            <option value="524">524</option>
                            <option value="526">526</option>
                            <option value="528">528</option>
                            <option value="530">530</option>
                            <option value="542">542</option>
                            <option value="544">544</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Seat Status Management</label>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Click on a seat to toggle between Available and Unavailable
                    </div>
                    <div class="seat-grid" id="managementSeatGrid">
                        <!-- Seats will be populated by JavaScript -->
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button id="saveChangesBtn" class="btn btn-primary btn-lg">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const seatGrid = document.getElementById("managementSeatGrid");
            const labSelect = document.getElementById("managementLab");
            const saveBtn = document.getElementById("saveChangesBtn");
            
            // Track seat status changes
            const seatStatus = {};
            
            // Generate 48 seats
            function generateSeats() {
                seatGrid.innerHTML = '';
                for (let i = 1; i <= 48; i++) {
                    const btn = document.createElement("button");
                    btn.className = "btn seat-btn available";
                    btn.innerText = `Seat ${i}`;
                    btn.dataset.seat = i;
                    
                    // Initialize status
                    seatStatus[i] = 'available';
                    
                    btn.addEventListener("click", function () {
                        // Toggle between available and unavailable
                        const currentStatus = seatStatus[i];
                        const newStatus = currentStatus === 'available' ? 'unavailable' : 'available';
                        
                        // Update UI and status tracking
                        btn.className = "btn seat-btn " + newStatus;
                        seatStatus[i] = newStatus;
                    });
                    
                    seatGrid.appendChild(btn);
                }
            }
            
            // Load existing seat statuses when lab changes
            function loadSeatStatuses() {
                const lab = labSelect.value;
                
                if (!lab) return;
                
                fetch("../db/fetch_seat_status.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        laboratory: lab
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then(data => {
                    if (data.status === "success") {
                        // Reset all seats first
                        document.querySelectorAll(".seat-btn").forEach(btn => {
                            btn.classList.remove("unavailable");
                            btn.classList.add("available");
                        });
                        
                        // Mark the unavailable seats
                        Object.keys(data.seatStatus).forEach(seat => {
                            if (data.seatStatus[seat] === 'unavailable') {
                                const btn = document.querySelector(`.seat-btn[data-seat="${seat}"]`);
                                if (btn) {
                                    btn.classList.remove("available");
                                    btn.classList.add("unavailable");
                                    seatStatus[seat] = 'unavailable';
                                }
                            }
                        });
                    }
                })
                .catch(err => {
                    console.error("Error loading seat statuses:", err);
                    alert("Error loading seat data. Please try again.");
                });
            }
            
            // Save changes to database
            saveBtn.addEventListener("click", function () {
                const lab = labSelect.value;
                
                if (!lab) {
                    alert("Please select a laboratory.");
                    return;
                }
                
                // Prepare data to send - only include seats marked as unavailable
                const seatsToSave = {};
                for (const seat in seatStatus) {
                    if (seatStatus[seat] === 'unavailable') {
                        seatsToSave[seat] = 'unavailable';
                    }
                }
                
                fetch("../db/save_seat_status.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        laboratory: lab,
                        seatStatus: seatsToSave
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then(data => {
                    if (data.status === "success") {
                        alert("Seat statuses saved successfully!");
                    } else {
                        alert("Error saving seat statuses: " + (data.message || "Unknown error"));
                    }
                })
                .catch(err => {
                    console.error("Error saving seat statuses:", err);
                    alert("Error saving seat statuses. Please try again.");
                });
            });
            
            // Watch for changes in laboratory
            labSelect.addEventListener("change", function() {
                if (labSelect.value) {
                    generateSeats();
                    loadSeatStatuses();
                }
            });
            
            // Initial generation
            generateSeats();
        });
    </script>
</body>
</html>