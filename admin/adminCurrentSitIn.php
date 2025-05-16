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
                <tbody id="sitInTableBody"></tbody>
            </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script>
        function loadSitInLogs() {
            fetch("../db/fetchsitin_logs.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        let tableBody = document.getElementById("sitInTableBody");
                        tableBody.innerHTML = "";

                        // Sort by time_in (newest first) and then by status (Active first)
                        data.data.sort((a, b) => {
                            // First sort by time_in (newest first)
                            const dateA = new Date(a.time_in || a.created_at);
                            const dateB = new Date(b.time_in || b.created_at);
                            if (dateB > dateA) return 1;
                            if (dateB < dateA) return -1;
                            
                            // Then sort by status (Active first)
                            if (a.status === 'Active' && b.status !== 'Active') return -1;
                            if (a.status !== 'Active' && b.status === 'Active') return 1;
                            
                            return 0;
                        });

                        data.data.forEach(log => {
                            let actions = "";
                            let badgeClass = "bg-secondary";
                            
                            if (log.status === "Active") {
                                actions = `<button class="btn btn-danger btn-sm" onclick="timeoutSitIn(${log.id})">Timeout</button>`;
                                badgeClass = "bg-success";
                            } else if (log.status === "Reserved") {
                                actions = `<button class="btn btn-primary btn-sm" onclick="timeInSitIn(${log.id})">Time-In</button>`;
                                badgeClass = "bg-info";
                            } else {
                                actions = `
                                    ${log.timeout || ''}
                                    <br>
                                    <button class="btn btn-success btn-sm mt-1" onclick="addPoint(${log.id}, '${log.id_number}')" 
                                        ${log.points_added == 1 ? 'disabled' : ''}>
                                        ${log.points_added == 1 ? 'Point Added' : 'Add Point'}
                                    </button>`;
                            }

                            tableBody.innerHTML += `
                                <tr>
                                    <td>${log.id}</td>
                                    <td>${log.id_number}</td>
                                    <td>${log.name}</td>
                                    <td>${log.purpose}</td>
                                    <td>${log.lab}</td>
                                    <td>${log.remaining_sessions} remaining</td>
                                    <td><span class="badge ${badgeClass}">${log.status}</span></td>
                                    <td>${actions}</td>
                                </tr>`;
                        });
                    }
                })
                .catch(error => console.error("Error:", error));
        }

        function timeoutSitIn(id) {
            if (confirm("Timeout this sit-in?")) {
                fetch("../db/timeout_sitin.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${encodeURIComponent(id)}`
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    window.location.href = "adminSitInReport.php";
                })
                .catch(console.error);
            }
        }

        function addPoint(id, idno) {
            if (confirm("Add point for this session?")) {
                fetch("../db/add_points.php", {
                    method: "POST",
                    body: `sit_in_id=${encodeURIComponent(id)}&idno=${encodeURIComponent(idno)}`
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    const btn = document.querySelector(`button[onclick="addPoint(${id}, '${idno}')"]`);
                    if (btn) {
                        btn.textContent = "Point Added";
                        btn.disabled = true;
                        btn.classList.replace("btn-success", "btn-secondary");
                    }
                })
                .catch(console.error);
            }
        }

        function timeInSitIn(id) {
            if (confirm("Time in this reservation?")) {
                fetch("../db/timein_sitin.php", {
                    method: "POST",
                    body: `id=${encodeURIComponent(id)}`
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadSitInLogs();
                })
                .catch(console.error);
            }
        }

        document.addEventListener("DOMContentLoaded", loadSitInLogs);
    </script>
</body>
</html>