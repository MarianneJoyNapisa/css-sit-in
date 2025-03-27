<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <title>Announcement Page</title>
    <style>
        .ann-Card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .accordion-button:not(.collapsed) {
            background-color: #e9ecef;
            color: #000;
        }
        .search-filter-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-filter-container input,
        .search-filter-container button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-filter-container button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .search-filter-container button:hover {
            background-color: #0056b3;
        }
        .full-width-accordion {
            width: 100%;
        }
        .no-announcements {
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>
<body>

    <?php include 'userHeaderSideNav.php'; ?>

    <div id="overlay" class="overlay"></div>

<!-- Main Content -->
    <main class="p-4" style="margin-top: 70px;">
        <div class="row">
                <!-- Announcement Section -->
            <div class="col-12">
                <div id="announcementSection">
                    <!-- Search and Filter Section -->
                    <form method="get" class="search-filter-container">
                        <input type="text" id="searchBar" name="SearchTerm" placeholder="Search announcements..." class="form-control">
                        <input type="date" id="datePicker" name="SelectedDate" class="form-control">
                        <button type="submit" id="filterButton" class="btn btn-primary">Filter</button>
                    </form>

                    <!-- Accordion -->
                    <div class="accordion full-width-accordion" id="announcementAccordion">
                        <!-- Today -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingToday">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseToday" aria-expanded="true" aria-controls="collapseToday">
                                    Today
                                </button>
                            </h2>
                            <div id="collapseToday" class="accordion-collapse collapse show" aria-labelledby="headingToday" data-bs-parent="#announcementAccordion">
                                <div class="accordion-body">
                                    <div id="today-announcements">
                                        <!-- Announcements for today will be dynamically inserted here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Yesterday -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingYesterday">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseYesterday" aria-expanded="false" aria-controls="collapseYesterday">
                                    Yesterday
                                </button>
                            </h2>
                            <div id="collapseYesterday" class="accordion-collapse collapse" aria-labelledby="headingYesterday" data-bs-parent="#announcementAccordion">
                                <div class="accordion-body">
                                    <div id="yesterday-announcements">
                                        <!-- Announcements for yesterday will be dynamically inserted here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- A Week Ago -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingWeekAgo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWeekAgo" aria-expanded="false" aria-controls="collapseWeekAgo">
                                    A Week Ago
                                </button>
                            </h2>
                            <div id="collapseWeekAgo" class="accordion-collapse collapse" aria-labelledby="headingWeekAgo" data-bs-parent="#announcementAccordion">
                                <div class="accordion-body">
                                    <div id="week-ago-announcements">
                                        <!-- Announcements from a week ago will be dynamically inserted here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/sideNav.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        fetchAnnouncements();
    });

    function fetchAnnouncements() {
        fetch('../db/announcements.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const todayAnnouncements = document.getElementById('today-announcements');
                    const yesterdayAnnouncements = document.getElementById('yesterday-announcements');
                    const weekAgoAnnouncements = document.getElementById('week-ago-announcements');

                    // Clear existing content
                    todayAnnouncements.innerHTML = '';
                    yesterdayAnnouncements.innerHTML = '';
                    weekAgoAnnouncements.innerHTML = '';

                    // Get today's date, yesterday's date, and a week ago's date
                    const today = new Date();
                    const yesterday = new Date(today);
                    yesterday.setDate(today.getDate() - 1);
                    const weekAgo = new Date(today);
                    weekAgo.setDate(today.getDate() - 7);

                    // Format dates to match the server's date format (YYYY-MM-DD)
                    const formatDate = (date) => date.toISOString().split('T')[0];

                    // Filter and display announcements
                    data.data.forEach(announcement => {
                        const announcementDate = new Date(announcement.created_at);
                        const announcementDateFormatted = formatDate(announcementDate);

                        const announcementItem = document.createElement('div');
                        announcementItem.className = 'ann-Card';

                        const announcementHeader = document.createElement('h6');
                        announcementHeader.className = 'text-secondary';
                        announcementHeader.textContent = `${announcement.author_name} | ${announcement.created_at}`;

                        const announcementContent = document.createElement('p');
                        announcementContent.textContent = announcement.content;

                        announcementItem.appendChild(announcementHeader);
                        announcementItem.appendChild(announcementContent);

                        if (announcementDateFormatted === formatDate(today)) {
                            todayAnnouncements.appendChild(announcementItem);
                        } else if (announcementDateFormatted === formatDate(yesterday)) {
                            yesterdayAnnouncements.appendChild(announcementItem);
                        } else if (announcementDateFormatted === formatDate(weekAgo)) {
                            weekAgoAnnouncements.appendChild(announcementItem);
                        }
                    });

                    // Display "No announcements" messages if no announcements are found
                    if (todayAnnouncements.innerHTML === '') {
                        todayAnnouncements.innerHTML = '<div class="no-announcements">No announcements today.</div>';
                    }
                    if (yesterdayAnnouncements.innerHTML === '') {
                        yesterdayAnnouncements.innerHTML = '<div class="no-announcements">No announcements yesterday.</div>';
                    }
                    if (weekAgoAnnouncements.innerHTML === '') {
                        weekAgoAnnouncements.innerHTML = '<div class="no-announcements">No announcements a week ago.</div>';
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

        function fetchAnnouncements() {
            fetch('../db/announcements.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const todayAnnouncements = document.getElementById('today-announcements');
                        const yesterdayAnnouncements = document.getElementById('yesterday-announcements');
                        const weekAgoAnnouncements = document.getElementById('week-ago-announcements');

                        // Clear existing content
                        todayAnnouncements.innerHTML = '';
                        yesterdayAnnouncements.innerHTML = '';
                        weekAgoAnnouncements.innerHTML = '';

                        // Get today's date, yesterday's date, and a week ago's date
                        const today = new Date();
                        const yesterday = new Date(today);
                        yesterday.setDate(today.getDate() - 1);
                        const weekAgo = new Date(today);
                        weekAgo.setDate(today.getDate() - 7);

                        // Format dates to match the server's date format (YYYY-MM-DD)
                        const formatDate = (date) => date.toISOString().split('T')[0];

                        // Filter and display announcements
                        data.data.forEach(announcement => {
                            const announcementDate = new Date(announcement.created_at);
                            const announcementDateFormatted = formatDate(announcementDate);

                            const announcementItem = document.createElement('div');
                            announcementItem.className = 'ann-Card';

                            const announcementHeader = document.createElement('h6');
                            announcementHeader.className = 'text-secondary';
                            announcementHeader.textContent = `${announcement.author_name} | ${announcement.created_at}`;

                            const announcementContent = document.createElement('p');
                            announcementContent.textContent = announcement.content;

                            announcementItem.appendChild(announcementHeader);
                            announcementItem.appendChild(announcementContent);

                            if (announcementDateFormatted === formatDate(today)) {
                                todayAnnouncements.appendChild(announcementItem);
                            } else if (announcementDateFormatted === formatDate(yesterday)) {
                                yesterdayAnnouncements.appendChild(announcementItem);
                            } else if (announcementDateFormatted === formatDate(weekAgo)) {
                                weekAgoAnnouncements.appendChild(announcementItem);
                            }
                        });

                        // Display "No announcements" messages if no announcements are found
                        if (todayAnnouncements.innerHTML === '') {
                            todayAnnouncements.innerHTML = '<div class="no-announcements">No announcements today.</div>';
                        }
                        if (yesterdayAnnouncements.innerHTML === '') {
                            yesterdayAnnouncements.innerHTML = '<div class="no-announcements">No announcements yesterday.</div>';
                        }
                        if (weekAgoAnnouncements.innerHTML === '') {
                            weekAgoAnnouncements.innerHTML = '<div class="no-announcements">No announcements a week ago.</div>';
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