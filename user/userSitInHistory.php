<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID number from session or database
$id_number = $_SESSION['idno'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <title>Sit-In History</title>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'userHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    <main>
        <div class="container mt-4">
            <h5 class="text-primary mb-4">Sit-In History</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID Number</th>
                            <th>Name</th>
                            <th>Purpose</th>
                            <th>Lab</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="sitInHistoryTableBody">
                        <tr>
                            <td colspan="8" class="text-center">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    
    <script>
        // Function to load sit-in history
        function loadSitInHistory() {
            const id_number = "<?php echo $id_number; ?>";
            
            if (!id_number) {
                console.error("ID Number not available");
                document.getElementById("sitInHistoryTableBody").innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center text-danger">Error: User not properly authenticated</td>
                    </tr>`;
                return;
            }

            fetch(`../db/fetch_usersitinhistory.php?id_number=${encodeURIComponent(id_number)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const tableBody = document.getElementById("sitInHistoryTableBody");
                    
                    if (data.status === "success" && data.data && data.data.length > 0) {
                        tableBody.innerHTML = "";
                        
                        data.data.forEach(history => {
                            const row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${history.id_number || 'N/A'}</td>
                                <td>${history.name || 'N/A'}</td>
                                <td>${history.purpose || 'N/A'}</td>
                                <td>${history.lab || 'N/A'}</td>
                                <td>${formatDateTime(history.time_in)}</td>
                                <td>${history.timeout ? formatDateTime(history.timeout) : 'N/A'}</td>
                                <td>${formatDate(history.created_at)}</td>
                                <td>
                                    ${history.timeout ? 
                                        `<button class="btn btn-primary btn-sm" onclick="provideFeedback(${history.id})">
                                            <i class="bi bi-chat-left-text"></i> Feedback
                                        </button>` : 
                                        'N/A'}
                                </td>`;
                            tableBody.appendChild(row);
                        });
                    } else {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="8" class="text-center">
                                    ${data.message || 'No sit-in history found'}
                                </td>
                            </tr>`;
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    document.getElementById("sitInHistoryTableBody").innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center text-danger">
                                Error loading data: ${error.message}
                            </td>
                        </tr>`;
                });
        }

        // Helper function to format date and time
        function formatDateTime(datetimeString) {
            if (!datetimeString) return 'N/A';
            try {
                const date = new Date(datetimeString);
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            } catch (e) {
                return datetimeString;
            }
        }

        // Helper function to format date
        function formatDate(datetimeString) {
            if (!datetimeString) return 'N/A';
            try {
                const date = new Date(datetimeString);
                return date.toLocaleDateString();
            } catch (e) {
                return datetimeString;
            }
        }

        // Function to handle feedback
        function provideFeedback(id) {
            window.location.href = `feedback.php?sitin_id=${id}`;
        }

        // Load sit-in history on page load
        document.addEventListener("DOMContentLoaded", loadSitInHistory);
    </script>
</body>
</html>