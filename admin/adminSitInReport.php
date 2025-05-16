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
        canvas {
            max-width: 400px !important;
            max-height: 250px !important;
            padding: 16px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .session-cell {
            font-weight: bold;
            color: #2c3e50;
        }
        .active-status {
            color: #28a745;
            font-weight: bold;
        }
    </style>
    <title>Sit-In Report</title>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'adminHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    <main>
        <div class="container mt-4">
            <h5 class="text-primary">Sit-In Report</h5>
            
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Sit-Ins</h6>
                            <h4 id="totalSitIns" class="card-text">0</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Active Now</h6>
                            <h4 id="activeSitIns" class="card-text">0</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Points Awarded</h6>
                            <h4 id="totalPoints" class="card-text">0</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row">
                <div class="col-md-6 d-flex flex-column align-items-center">
                    <h6 class="text-primary">Lab Usage</h6>
                    <canvas id="labPieChart" width="300" height="300"></canvas>
                </div>
                <div class="col-md-6 d-flex flex-column align-items-center">
                    <h6 class="text-primary">Purpose Distribution</h6>
                    <canvas id="purposePieChart" width="300" height="300"></canvas>
                </div>
            </div>

            <!-- Report Table -->
            <div class="table-responsive mt-4">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Sit-In ID</th>
                            <th>Student</th>
                            <th>Purpose</th>
                            <th>Lab</th>
                            <th>Remaining Sessions</th>
                            <th>Time In</th>
                            <th>Status</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody id="sitInReportTableBody">
                        <!-- Data will load here -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>

    <script>
        // Global variables for charts and data
        let labChart, purposeChart;
        let totalSitIns = 0, activeSitIns = 0, totalPoints = 0;

        // Load report data
        function loadSitInReport() {
            fetch("../db/fetchsitin_report.php")
                .then(response => {
                    if (!response.ok) throw new Error("Network error");
                    return response.json();
                })
                .then(data => {
                    if (data.status === "success") {
                        processReportData(data.data);
                    } else {
                        throw new Error(data.message || "Failed to load data");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    document.getElementById("sitInReportTableBody").innerHTML = 
                        `<tr><td colspan="8" class="text-center text-danger">Error: ${error.message}</td></tr>`;
                });
        }

        // Process and display the report data
        function processReportData(data) {
            const tableBody = document.getElementById("sitInReportTableBody");
            tableBody.innerHTML = "";
            
            const labCounts = {};
            const purposeCounts = {};
            
            // Reset counters
            totalSitIns = data.length;
            activeSitIns = 0;
            totalPoints = 0;

            data.forEach(log => {
                // Count statistics
                labCounts[log.lab] = (labCounts[log.lab] || 0) + 1;
                purposeCounts[log.purpose] = (purposeCounts[log.purpose] || 0) + 1;
                totalPoints += parseInt(log.earned_points) || 0;
                if (log.status === "Active" || log.time_out === null) activeSitIns++;

                // Create table row
                const row = document.createElement("tr");
                
                // Determine status display
                const status = log.status === "Active" || log.time_out === null ? 
                    '<span class="active-status">Active</span>' : 
                    '<span class="text-muted">Completed</span>';
                
                // Format time out
                const timeOut = log.time_out ? 
                    new Date(log.time_out).toLocaleString() : 
                    '<span class="text-muted">-</span>';

                row.innerHTML = `
                    <td>${log.id}</td>
                    <td>
                        <div>${log.id_number}</div>
                        <div class="text-primary fw-bold">${log.name}</div>
                    </td>
                    <td>${log.purpose}</td>
                    <td>${log.lab}</td>
                    <td class="session-cell">${log.adjusted_remaining_sessions || log.remaining_sessions || "N/A"}</td>
                    <td>${new Date(log.time_in).toLocaleString()}</td>
                    <td>${status}</td>
                    <td>${log.earned_points || 0}</td>
                `;
                
                tableBody.appendChild(row);
            });

            // Update summary cards
            document.getElementById("totalSitIns").textContent = totalSitIns;
            document.getElementById("activeSitIns").textContent = activeSitIns;
            document.getElementById("totalPoints").textContent = totalPoints;

            // Draw charts
            drawCharts(labCounts, purposeCounts);
        }

        // Draw pie charts
        function drawCharts(labCounts, purposeCounts) {
            const labCtx = document.getElementById('labPieChart').getContext('2d');
            const purposeCtx = document.getElementById('purposePieChart').getContext('2d');

            // Destroy previous charts if they exist
            if (labChart) labChart.destroy();
            if (purposeChart) purposeChart.destroy();

            // Generate colors
            const colors = [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                '#9966FF', '#FF9F40', '#8AC249', '#EA5545'
            ];

            // Lab Chart
            labChart = new Chart(labCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys(labCounts),
                    datasets: [{
                        data: Object.values(labCounts),
                        backgroundColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw} (${Math.round(context.parsed)}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Purpose Chart
            purposeChart = new Chart(purposeCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys(purposeCounts),
                    datasets: [{
                        data: Object.values(purposeCounts),
                        backgroundColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw} (${Math.round(context.parsed)}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize on page load
        document.addEventListener("DOMContentLoaded", loadSitInReport);
    </script>
</body>
</html>