<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Notifications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <style>
        .notification-item {
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'userHeaderSideNav.php'; ?>

    <div id="overlay" class="overlay"></div>

    <main class="container mt-4">
        <h2 class="text-primary mb-4">My Notifications</h2>
        <div id="notif-list">
            <!-- Notifications will be loaded here -->
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script src="../js/fetchAnnouncement.js"></script>
    <script src="../js/searchStudent.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    fetchNotifications();

    function fetchNotifications() {
        fetch('../db/fetch_notif.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('notif-list'); // FIXED ID
                    container.innerHTML = '';

                    if (data.status !== 'success' || data.data.length === 0) {
                        container.innerHTML = '<p class="text-muted">No notifications.</p>';
                        return;
                    }

                    data.data.forEach(notification => {
                        const div = document.createElement('div');

                        // Choose alert class based on message content
                        let alertClass = 'alert-info';
                        const msg = notification.message.toLowerCase();

                        if (msg.includes('denied')) {
                            alertClass = 'alert-danger';
                        } else if (msg.includes('approved')) {
                            alertClass = 'alert-success';
                        }

                        div.className = `alert ${alertClass} notification-item`;
                        div.innerHTML = `
                            <strong>🔔</strong> ${notification.message}
                            <br><small class="text-muted">${new Date(notification.created_at).toLocaleString()}</small>
                        `;
                        container.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                });
    }
});
    </script>
</body>
</html>
