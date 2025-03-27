<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <style>
        .announcement-container {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }
        .announcement-item {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }
        .search-student-container {
            max-width: 500px;
            margin: 0 auto;
        }
        #searchResults {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .student-result-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .student-result-item:last-child {
            border-bottom: none;
        }
    </style>
    <title>Admin Dashboard</title>
</head>
<body class="d-flex justify-content-center align-items-center">
    
    <?php include 'adminHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    
    <main>
        <div class="container">
            <div class="row justify-content-center g-2">
                
                <!-- Sit-In Modal -->
                <div class="modal fade" id="sitInModal" tabindex="-1" aria-labelledby="sitInModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="sitInModalLabel">Sit-In Form</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="sitInForm">
                                    <div class="mb-3">
                                        <label for="idNumber" class="form-label">ID Number</label>
                                        <input type="text" class="form-control" id="idNumber" name="idNumber" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="studentName" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="studentName" name="studentName" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="purpose" class="form-label">Purpose</label>
                                        <select class="form-select" id="purpose" name="purpose" required>
                                            <option value="" disabled selected>Select Purpose</option>
                                            <option value="C# Programming">C# Programming</option>
                                            <option value="Java Programming">Java Programming</option>
                                            <option value="Web Development">Web Development</option>
                                            <option value="Cisco Packet Tracer">Cisco Packet Tracer</option>
                                            <option value="Python Programming">Python Programming</option>
                                            <option value="PHP Programming">PHP Programming</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="lab" class="form-label">Lab</label>
                                        <select class="form-select" id="lab" name="lab" required>
                                            <option value="" disabled selected>Select Lab</option>
                                            <option value="544">544</option>
                                            <option value="542">542</option>
                                            <option value="530">530</option>
                                            <option value="524">524</option>
                                            <option value="526">526</option>
                                            <option value="528">528</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="remainingSessions" class="form-label">Remaining Sessions</label>
                                        <input type="text" class="form-control" id="remainingSessions" name="remainingSessions" readonly>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="submitSitIn">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Student Section -->
                <div class="search-student-container mb-2">
                    <label for="searchStudentInput" class="form-label">Search Student Sit-In</label>
                    <div class="input-group">
                        <input type="text" id="searchStudentInput" class="form-control" placeholder="Enter student name or ID...">
                        <button id="searchStudentButton" class="btn btn-primary">Search</button>
                    </div>
                </div>

                <div id="searchResults" class="mt-1"></div>

                <!-- Announcement Section -->
                <div class="col-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="text-primary">Post New Announcement</h5>
                            <form id="announcementForm" action="../db/announcements.php" method="POST">
                                <div class="mb-3">
                                    <label for="announcementTitle" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="announcementTitle" name="announcementTitle" required>
                                </div>
                                <div class="mb-3">
                                    <label for="announcementContent" class="form-label">Content</label>
                                    <textarea class="form-control" id="announcementContent" name="announcementContent" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Post Announcement</button>
                            </form>

                            <h5 class="text-primary mt-4">Posted Announcements</h5>
                            <div class="announcement-container" id="announcements-list">
                                <!-- Announcements will go here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overview Statistics Section -->
                <div class="col-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="text-primary">Overview Statistics</h5>
                            <div>
                                <p><strong>Student Registered:</strong> <span id="studentRegistered">Loading...</span></p>
                                <p><strong>Currently Sit-In:</strong> <span id="currentlySitIn">Loading...</span></p>
                                <p><strong>Total Sit-In:</strong> <span id="totalSitIn">Loading...</span></p>
                            </div>

                            <!-- Purpose Pie Chart -->
                            <h6 class="text-primary mt-4">Purpose Distribution</h6>
                            <canvas id="purposePieChart" width="300" height="300"></canvas>
                            <div id="purposeLegend" class="mt-2"></div> <!-- Purpose Legend -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js for pie charts -->
    <script src="../js/sideNav.js"></script>
    <script src="../js/fetchAnnouncement.js"></script>
    <script src="../js/searchStudent.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Fetch and display the latest announcements (limit to 5)
            fetch('../db/fetch_announcements.php')
                .then(response => response.json())
                .then(data => {
                    const announcementsList = document.getElementById("announcements-list");

                    // Limit to 5 announcements
                    const announcements = data.slice(0, 5);

                    announcements.forEach(announcement => {
                        const announcementItem = document.createElement("div");
                        announcementItem.classList.add("announcement-item");
                        announcementItem.innerHTML = `
                            <h6>${announcement.title}</h6>
                            <p>${announcement.content}</p>
                        `;
                        announcementsList.appendChild(announcementItem);
                    });
                })
                .catch(error => console.error("Error fetching announcements:", error));

            // Search for student details
            document.getElementById("searchStudentButton").addEventListener("click", function () {
                let idNumber = document.getElementById("searchStudentInput").value.trim();

                if (idNumber === "") {
                    alert("Please enter a student ID.");
                    return;
                }

                fetch(`../db/fetch_userdetails.php?idNumber=${encodeURIComponent(idNumber)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === "error") {
                            alert(data.message);
                        } else {
                            let user = data.data;

                            document.getElementById("idNumber").value = user.idNumber;
                            document.getElementById("studentName").value = user.fullname;
                            document.getElementById("remainingSessions").value = user.remaining_sessions;

                            // Show the modal
                            let sitInModal = new bootstrap.Modal(document.getElementById("sitInModal"));
                            sitInModal.show();
                        }
                    })
                    .catch(error => console.error("Error fetching user data:", error));
            });

            // Submit sit-in log
            document.getElementById("submitSitIn").addEventListener("click", function () {
                let idNumber = document.getElementById("idNumber").value;
                let studentName = document.getElementById("studentName").value;
                let purpose = document.getElementById("purpose").value;
                let lab = document.getElementById("lab").value;

                if (!purpose || !lab) {
                    alert("Please select a purpose and lab.");
                    return;
                }

                fetch("../db/submit_sitin.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({
                        idNumber: idNumber,
                        studentName: studentName,
                        purpose: purpose,
                        lab: lab
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        loadSitInLogs(); // Refresh table after submission
                        // Close the modal safely
                        let sitInModalElement = document.getElementById("sitInModal");
                        let sitInModal = bootstrap.Modal.getInstance(sitInModalElement) || new bootstrap.Modal(sitInModalElement);
                        sitInModal.hide();
                    }
                })
                .catch(error => console.error("Error submitting sit-in log:", error));
            });

            // Fetch sit-in statistics
            fetch('../db/fetchsitin_statistics.php')
                .then(response => response.json())
                .then(data => {
                    // Update statistics
                    document.getElementById('studentRegistered').textContent = data.studentRegistered;
                    document.getElementById('currentlySitIn').textContent = data.currentlySitIn;
                    document.getElementById('totalSitIn').textContent = data.totalSitIn;

                    // Prepare data for the purpose pie chart
                    const purposeCounts = data.purposeCounts;
                    const purposeLabels = [
                        "C# Programming",
                        "Java Programming",
                        "Web Development",
                        "Cisco Packet Tracer",
                        "Python Programming",
                        "PHP Programming"
                    ];

                    // Ensure all options appear in the legend (set missing values to 0)
                    const chartColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FF5722', '#8E44AD'];
                    const purposeData = purposeLabels.map(label => purposeCounts[label] || 0);

                    // Purpose Pie Chart
                    const purposeCanvas = document.getElementById('purposePieChart').getContext('2d');
                    const purposePieChart = new Chart(purposeCanvas, {
                        type: 'pie',
                        data: {
                            labels: purposeLabels,
                            datasets: [{
                                data: purposeData,
                                backgroundColor: chartColors,
                                hoverBackgroundColor: chartColors
                            }]
                        }
                    });
                })
                .catch(error => console.error("Error fetching statistics:", error));
        });
    </script>

</body>
</html>
