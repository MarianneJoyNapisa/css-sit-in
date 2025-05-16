<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get user data from session
$idno = $_SESSION['id_number'] ?? '';
$username = $_SESSION['username'] ?? 'Unknown User';
$fullname = ($_SESSION['firstname'] ?? '') . ' ' . ($_SESSION['lastname'] ?? '');
$remainingSessions = $_SESSION['remaining_sessions'] ?? 0;
$profileImage = !empty($_SESSION['image']) ? "../images/{$_SESSION['image']}" : '../images/default_image.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Reservation</title>
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
            position: relative;
        }
        .seat-btn:hover:not(.occupied) {
            transform: scale(1.05);
        }
        .seat-btn.selected {
            background-color: #0d6efd;
            color: white;
        }
        .seat-btn.occupied {
            background-color: #dc3545;
            color: white;
            cursor: not-allowed;
        }
        .seat-btn.occupied::after {
            position: absolute;
            font-size: 1.2em;
        }
        #purposeTextarea {
            resize: none;
            height: 100px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'userHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    <main class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Make a Reservation</h5>
            </div>
            <div class="card-body">
                <form id="reservationForm">
                    <!-- User Information Section -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">ID Number</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($idno); ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Student Name</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Remaining Sessions</label>
                            <input type="text" class="form-control" value="<?php echo $remainingSessions; ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="purpose" class="form-label">Purpose</label>
                            <select class="form-select" id="purpose" required>
                                <option value="" disabled selected>Select Purpose</option>
                                <option value="C# Programming">C# Programming</option>
                                <option value="Java Programming">Java Programming</option>
                                <option value="Web Development">Web Development</option>
                                <option value="Cisco Packet Tracer">Cisco Packet Tracer</option>
                                <option value="Python Programming">Python Programming</option>
                                <option value="PHP Programming">PHP Programming</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Other Purpose Field -->
                    <div class="mb-3" id="otherPurposeContainer" style="display: none;">
                        <label for="purposeTextarea" class="form-label">Specify Purpose</label>
                        <textarea class="form-control" id="purposeTextarea" placeholder="Please specify your purpose..."></textarea>
                    </div>
                    
                    <!-- Reservation Details Section -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="laboratory" class="form-label">Laboratory</label>
                            <select class="form-select" id="laboratory" required>
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
                        <div class="col-md-4">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="time" class="form-label">Time Slot</label>
                            <input type="time" class="form-control" id="time" required>
                        </div>
                    </div>
                    
                    <!-- Seat Selection Section -->
                    <div class="mb-4">
                        <label class="form-label">Select Seat</label>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Click on an available seat to select it
                        </div>
                        <div class="seat-grid" id="seatGrid">
                            <!-- Seats will be populated by JavaScript -->
                        </div>
                        <input type="hidden" id="selectedSeat" name="selectedSeat" required>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button id="reserveBtn" type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Reserve Seat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const seatGrid = document.getElementById("seatGrid");
        const selectedSeatInput = document.getElementById("selectedSeat");
        let selectedSeat = null;
        let seatRefreshInterval = null;

        // Generate 48 seat buttons
        function generateSeats() {
            seatGrid.innerHTML = '';
            for (let i = 1; i <= 48; i++) {
                const btn = document.createElement("button");
                btn.type = "button"; // Prevent form submission on click
                btn.className = "btn btn-outline-secondary seat-btn";
                btn.innerHTML = `<span>Seat ${i}</span>`;
                btn.dataset.seat = i;

                btn.addEventListener("click", function () {
                    if (btn.classList.contains("occupied")) {
                        alert("This seat is already taken. Please select another seat.");
                        return;
                    }

                    // Deselect all other seats
                    document.querySelectorAll(".seat-btn").forEach(b => b.classList.remove("selected"));

                    // Mark this seat as selected
                    btn.classList.add("selected");
                    selectedSeat = i;
                    if (selectedSeatInput) selectedSeatInput.value = i;
                });

                seatGrid.appendChild(btn);
            }
        }

        // Fetch occupied seats from the server
        function fetchOccupiedSeats() {
            const lab = document.getElementById("laboratory").value;
            const date = document.getElementById("date").value;
            const time = document.getElementById("time").value;

            if (!lab || !date || !time) return;

            fetch("../db/fetch_occupiedseat.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ laboratory: lab, date: date, time: time })
            })
            .then(response => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.json();
            })
            .then(data => {
                console.log("Fetched occupied seats:", data.occupiedSeats);

                document.querySelectorAll(".seat-btn").forEach(btn => {
                    btn.classList.remove("occupied");
                    btn.disabled = false;
                });

                if (Array.isArray(data.occupiedSeats)) {
                    data.occupiedSeats.forEach(seat => {
                        const btn = document.querySelector(`.seat-btn[data-seat="${seat}"]`);
                        if (btn) {
                            btn.classList.add("occupied");
                            btn.disabled = true;
                        }
                    });
                }
            })
            .catch(error => console.error("Error fetching occupied seats:", error));
        }

        // Refresh seat occupancy every 30 seconds
        function startSeatRefresh() {
            if (seatRefreshInterval) clearInterval(seatRefreshInterval);

            seatRefreshInterval = setInterval(() => {
                const lab = document.getElementById("laboratory").value;
                const date = document.getElementById("date").value;
                const time = document.getElementById("time").value;

                if (lab && date && time) {
                    fetchOccupiedSeats();
                }
            }, 30000); // every 30s
        }

        // Watch for lab/date/time changes
        ["laboratory", "date", "time"].forEach(id => {
            const el = document.getElementById(id);
            el.addEventListener("change", () => {
                fetchOccupiedSeats();
                startSeatRefresh();
            });
        });

        // Show/hide textarea for "Other" purpose
        document.getElementById("purpose").addEventListener("change", function () {
            const otherPurposeContainer = document.getElementById("otherPurposeContainer");
            otherPurposeContainer.style.display = this.value === "Other" ? "block" : "none";
        });

        // Handle reservation submission
        document.getElementById("reservationForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const lab = document.getElementById("laboratory").value;
            const date = document.getElementById("date").value;
            const time = document.getElementById("time").value;
            const purposeSelect = document.getElementById("purpose");
            const purpose = purposeSelect.value === "Other"
                ? document.getElementById("purposeTextarea").value
                : purposeSelect.value;

            if (!selectedSeat) {
                alert("Please select a seat.");
                return;
            }

            fetch("../db/process_reservation.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    laboratory: lab,
                    seat: selectedSeat,
                    date: date,
                    time: time,
                    purpose: purpose
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    location.reload();
                }
            })
            .catch(err => {
                console.error(err);
                alert("Something went wrong. Try again later.");
            });
        });

        // Initialize
        generateSeats();
        fetchOccupiedSeats();
        startSeatRefresh();
    });
    </script>

</body>
</html>