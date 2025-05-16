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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    
    <!-- Bootstrap CSS and Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">

    <style>
        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .announcement-container {
            max-height: 300px;
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

        .announcement-item h6 {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .announcement-item p {
            font-size: 16px;
            color: #333;
            margin-bottom: 0;
        }

        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
        }

        .toast {
            min-width: 300px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'userHeaderSideNav.php'; ?>
    <div id="overlay" class="overlay"></div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer"></div>

    <!-- Main Container -->
    <main>
        <div class="container">
            <!-- Row for centering content -->
            <div class="row justify-content-center g-4">
                <!-- Profile Overview Card -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <h3 class="card-title text-primary">Profile Overview</h3>
                            <div id="user-details" class="user-details"></div>
                            <a href="userProfile.php" class="btn btn-primary mt-3">Go to Profile</a>
                        </div>
                    </div>
                </div>

                <!-- Announcement Section -->
                <div class="col-12 col-md-6 col-lg-8">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h3 class="card-title text-primary">Announcements</h3>
                            <div class="announcement-container">
                                <div id="announcements-list"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lab Rules Card -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h3 class="text-primary">Lab Rules</h3>
                            <p>Maintain proper conduct and discipline inside the lab. Key rules include:</p>
                            <ul id="list">
                                <li>Keep silence and discipline at all times.</li>
                                <li>No eating, drinking, or vandalism inside the lab.</li>
                                <li>Unauthorized internet browsing is prohibited.</li>
                            </ul>
                            <a href="userLabRules.php" class="btn btn-primary mt-3">View Lab Rules</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script src="../js/fetchAnnouncement.js"></script>
    <script src="../js/searchStudent.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        fetchUserDetails();
        fetchAnnouncements();
        loadNotifications();
        setInterval(loadNotifications, 30000);
    });

    function fetchUserDetails() {
        fetch("../db/fetch_userdetails.php")
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    const userDetails = document.getElementById("user-details");
                    const middleInitial = data.data.middlename ? data.data.middlename.charAt(0) + "." : "";
                    const fullName = `${data.data.firstname} ${middleInitial} ${data.data.lastname}`;
                    const profileImage = data.data.image ? `../images/${data.data.image}` : '../images/default_image.png';
                    userDetails.innerHTML = `
                        <img src="${profileImage}" alt="Profile Picture" class="profile-picture">
                        <h4 class="mb-2 fs-5">${fullName}</h4>
                        <p class="text-muted">Remaining Sessions: ${data.data.remaining_sessions}</p>
                    `;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error("Error fetching user details:", error));
    }

    function fetchAnnouncements() {
        fetch('../db/announcements.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const announcementsList = document.getElementById('announcements-list');
                    announcementsList.innerHTML = '';
                    if (data.data.length > 0) {
                        data.data.forEach(announcement => {
                            const announcementItem = document.createElement('div');
                            announcementItem.className = 'announcement-item mb-3';
                            announcementItem.innerHTML = `
                                <h6 class="text-secondary">${announcement.author_name} | ${announcement.created_at}</h6>
                                <p>${announcement.content}</p>
                            `;
                            announcementsList.appendChild(announcementItem);
                        });
                    } else {
                        announcementsList.innerHTML = '<p>No announcements found.</p>';
                    }
                } else {
                    alert('Error fetching announcements: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error fetching announcements:', error);
            });
    }

function loadNotifications() {
    fetch('../db/fetch_notif.php')
        .then(response => response.json())
        .then(data => {
            if (data.status !== 'success' || data.data.length === 0) return;

            const latest = data.data[0]; // get only the most recent notification
            const toastContainer = document.getElementById("toastContainer");
            toastContainer.innerHTML = ''; // clear previous toast

            const toastEl = document.createElement('div');
            toastEl.className = `toast align-items-center text-white ${getToastClass(latest.message)} border-0 mb-2`;
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');

            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        🔔 ${latest.message}<br>
                        <small>${new Date(latest.created_at).toLocaleString()}</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;

            toastContainer.appendChild(toastEl);
            const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
            toast.show();
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
}

    function getToastClass(message) {
        const msg = message.toLowerCase();
        if (msg.includes('denied')) return 'bg-danger';
        if (msg.includes('approved')) return 'bg-success';
        return 'bg-info';
    }
    </script>
</body>
</html>
