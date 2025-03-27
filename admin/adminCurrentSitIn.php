<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">

    <title>Current Sit-In</title>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'adminHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    <main>
        <div class="container mt-4">
            <h5 class="text-primary">Sit-In Logs</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sit-In ID</th>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Purpose</th>
                        <th>Lab</th>
                        <th>Sessions</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sitInTableBody">
                    <!-- Data will be inserted here dynamically -->
                </tbody>
            </table>
        </div>
    </main>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script>
        // Function to load sit-in logs
        function loadSitInLogs() {
            fetch("../db/fetchsitin_logs.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        let tableBody = document.getElementById("sitInTableBody");
                        tableBody.innerHTML = "";

                        data.data.forEach(log => {
                            let row = `<tr>
                                <td>${log.id}</td>
                                <td>${log.id_number}</td>
                                <td>${log.name}</td>
                                <td>${log.purpose}</td>
                                <td>${log.lab}</td>
                                <td>${log.sessions}</td>
                                <td>${log.status}</td>
                                <td>
                                    ${log.status === "Active" ? 
                                        `<button class="btn btn-danger btn-sm" onclick="timeoutSitIn(${log.id})">Timeout</button>` 
                                        : log.timeout}
                                </td>
                            </tr>`;
                            tableBody.innerHTML += row;
                        });
                    }
                })
                .catch(error => console.error("Error fetching sit-in logs:", error));
        }

        // Function to handle timeout action
        function timeoutSitIn(id) {
            if (confirm("Are you sure you want to timeout this sit-in?")) {
                fetch("../db/timeout_sitin.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${encodeURIComponent(id)}`
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    window.location.href = "adminSitInReport.php"; // Redirect after timeout
                })
                .catch(error => console.error("Error:", error));
            }
        }

        // Load sit-in logs on page load
        document.addEventListener("DOMContentLoaded", loadSitInLogs);
    </script>
</body>
</html>
