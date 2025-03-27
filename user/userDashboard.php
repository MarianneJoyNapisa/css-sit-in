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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <style>
        .profile-picture {
            width: 100px; /* Fixed width */
            height: 100px; /* Fixed height */
            border-radius: 50%; /* Make it circular */
            object-fit: cover; /* Ensure the image covers the area without distortion */
            margin-bottom: 15px; /* Space below the image */
        }

        /* Announcement Container */
        .announcement-container {
            max-height: 300px; /* Adjust based on your design */
            overflow-y: auto; /* Enable scrolling if content exceeds the height */
            padding-right: 10px; /* Add some padding for the scrollbar */
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
    </style>
    <title>User Dashboard</title>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'userHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    
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
                            <div id="user-details" class="user-details">
                                <!-- User details will be dynamically populated here -->
                            </div>
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
                                <div id="announcements-list">
                                    <!-- Announcements will be dynamically inserted here -->
                                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script src="../js/fetchAnnouncement.js"></script>
    <script src="../js/searchStudent.js"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Fetch and display user details
        fetchUserDetails();

        // Fetch and display announcements
        fetchAnnouncements();

        // Handle form submission (for posting announcements, if applicable)
        const announcementForm = document.getElementById('announcementForm');
        if (announcementForm) {
            announcementForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent the default form submission

                const formData = new FormData(announcementForm);

                fetch('../db/announcements.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            fetchAnnouncements(); // Refresh the announcements list
                            announcementForm.reset(); // Clear the form
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while posting the announcement.');
                    });
            });
        }
    });

    // Function to fetch and display user details
    function fetchUserDetails() {
        fetch("../db/fetch_userdetails.php") // Endpoint to fetch user details
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    const userDetails = document.getElementById("user-details");

                    // Construct the middle initial
                    const middleInitial = data.data.middlename ? data.data.middlename.charAt(0) + "." : "";

                    // Construct the full name
                    const fullName = `${data.data.firstname} ${middleInitial} ${data.data.lastname}`;

                    // Construct the profile image URL
                    const profileImage = data.data.image ? `../images/${data.data.image}` : '../images/default_image.png';

                    // Construct the HTML for user details
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

    // Function to fetch and display announcements
    function fetchAnnouncements() {
        fetch('../db/announcements.php')
            .then(response => {
                console.log('Raw Response:', response); // Log the raw response
                return response.json();
            })
            .then(data => {
                console.log('Parsed Data:', data); // Log the parsed JSON data
                if (data.status === 'success') {
                    const announcementsList = document.getElementById('announcements-list');
                    if (announcementsList) {
                        announcementsList.innerHTML = ''; // Clear existing content

                        if (data.data.length > 0) {
                            data.data.forEach(announcement => {
                                const announcementItem = document.createElement('div');
                                announcementItem.className = 'announcement-item mb-3';

                                const announcementHeader = document.createElement('h6');
                                announcementHeader.className = 'text-secondary';
                                announcementHeader.textContent = `${announcement.author_name} | ${announcement.created_at}`;

                                const announcementContent = document.createElement('p');
                                announcementContent.textContent = announcement.content;

                                announcementItem.appendChild(announcementHeader);
                                announcementItem.appendChild(announcementContent);
                                announcementsList.appendChild(announcementItem);
                            });
                        } else {
                            const noAnnouncements = document.createElement('p');
                            noAnnouncements.textContent = 'No announcements found.';
                            announcementsList.appendChild(noAnnouncements);
                        }
                    } else {
                        console.error('Element with id "announcements-list" not found.');
                    }
                } else {
                    alert('Error fetching announcements: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error fetching announcements:', error);
                alert('An error occurred while fetching announcements.');
            });
    }
    </script>
</body>
</html>