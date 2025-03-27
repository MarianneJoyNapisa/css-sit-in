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
    </style>
    <title>Sit-In Report</title>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'adminHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    <main>
        <div class="container mt-4">
            <h5 class="text-primary">Sit-In Report</h5>
            <!-- Pie Charts Section -->
            <div class="row">
                <!-- Lab Chart -->
                <div class="col-md-6 d-flex flex-column align-items-center">
                    <h6 class="text-primary">Lab Usage</h6>
                    <canvas id="labPieChart" width="300" height="300"></canvas>
                    <div id="labLegend" class="mt-2"></div> <!-- Lab Legend -->
                </div>

                <!-- Purpose Chart -->
                <div class="col-md-6 d-flex flex-column align-items-center">
                    <h6 class="text-primary">Purpose Distribution</h6>
                    <canvas id="purposePieChart" width="300" height="300"></canvas>
                    <div id="purposeLegend" class="mt-2"></div> <!-- Purpose Legend -->
                </div>
            </div>

            <!-- Sit-In Report Table -->
            <table class="table table-striped mt-5">
                <thead>
                    <tr>
                        <th>Sit-In ID</th>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Purpose</th>
                        <th>Lab</th>
                        <th>Sessions</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>
                </thead>
                <tbody id="sitInReportTableBody">
                    <!-- Data will be inserted dynamically -->
                </tbody>
            </table>
        </div>
    </main>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js for pie charts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script>
        let labCounts = {}; // Stores counts for each lab
        let purposeCounts = {}; // Stores counts for each purpose

        function loadSitInReport() {
            fetch("../db/fetchsitin_report.php")
            .then(response => response.text()) 
            .then(text => {
                console.log("Raw response:", text); 
                return JSON.parse(text); 
            })
            .then(data => {
                if (data.status === "success") {
                    let tableBody = document.getElementById("sitInReportTableBody");
                    tableBody.innerHTML = "";

                    data.data.forEach(log => {
                        // Count Labs
                        labCounts[log.lab] = (labCounts[log.lab] || 0) + 1;
                        
                        // Count Purposes
                        purposeCounts[log.purpose] = (purposeCounts[log.purpose] || 0) + 1;

                        // Append row
                        let row = `<tr>
                            <td>${log.id}</td>
                            <td>${log.id_number}</td>
                            <td>${log.name}</td>
                            <td>${log.purpose}</td>
                            <td>${log.lab}</td>
                            <td>${log.sessions}</td>
                            <td>${log.time_in}</td>
                            <td>${log.time_out}</td>
                        </tr>`;
                        tableBody.innerHTML += row;
                    });

                    // Draw Pie Charts after data is loaded
                    drawPieCharts();
                } else {
                    console.error("Error:", data.message);
                }
            })
            .catch(error => console.error("Error fetching sit-in report:", error));
        }

        function drawPieCharts() {
            const labCanvas = document.getElementById('labPieChart').getContext('2d');
            const purposeCanvas = document.getElementById('purposePieChart').getContext('2d');

            // Define consistent labels and colors
            const labLabels = ["544", "542", "530", "524", "526", "528"];
            const purposeLabels = [
                "C# Programming",
                "Java Programming",
                "Web Development",
                "Cisco Packet Tracer",
                "Python Programming",
                "PHP Programming"
            ];
        
            const chartColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FF5722', '#8E44AD'];

            // Ensure all options appear in the legend (set missing values to 0)
            const labData = labLabels.map(label => labCounts[label] || 0);
            const purposeData = purposeLabels.map(label => purposeCounts[label] || 0);

            // Lab Pie Chart
            new Chart(labCanvas, {
                type: 'pie',
                data: {
                    labels: labLabels,
                    datasets: [{
                        label: 'Lab Usage',
                        data: labData,
                        backgroundColor: chartColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right' // Adjust legend position if needed
                        }
                    }
                }
            });

            // Purpose Pie Chart
            new Chart(purposeCanvas, {
                type: 'pie',
                data: {
                    labels: purposeLabels,
                    datasets: [{
                        label: 'Purpose',
                        data: purposeData,
                        backgroundColor: chartColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }


        // Load sit-in report logs on page load
        document.addEventListener("DOMContentLoaded", loadSitInReport);
    </script>
</body>
</html>
