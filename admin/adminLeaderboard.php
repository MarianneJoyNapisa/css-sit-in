<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <title>Leaderboard</title>
    <style>
        .gold { color: gold; }
        .silver { color: silver; }
        .bronze { color: #cd7f32; }
        .rank-cell { width: 80px; text-align: center; }
        .award-badge { 
            cursor: pointer; 
            font-size: 1.2rem;
            margin-right: 5px;
        }
        .awarded { color: #ffc107; }
        .awards-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'adminHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    <main>
        <div class="container mt-4">
            <!-- Awards Management Section -->
            <div class="awards-section">
                <h5 class="text-primary mb-3">Special Awards</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-warning text-white">
                                <i class="bi bi-award-fill"></i> Most Active User
                            </div>
                            <div class="card-body" id="mostActiveUser">
                                <p class="text-muted">No user selected yet</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <i class="bi bi-star-fill"></i> Top-Performing User
                            </div>
                            <div class="card-body" id="topPerformingUser">
                                <p class="text-muted">No user selected yet</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaderboard Section -->
            <h5 class="text-primary">Points Leaderboard</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th class="rank-cell">Rank</th>
                            <th>ID Number</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th>Points</th>
                            <th>Award</th>
                        </tr>
                    </thead>
                    <tbody id="leaderboardBody">
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Load leaderboard data
            fetchLeaderboard();
            
            // Load current awards
            fetchCurrentAwards();
        });

        function fetchLeaderboard() {
            fetch("../db/fetch_adminleaderboard.php")
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById("leaderboardBody");
                    tbody.innerHTML = "";

                    if (data.status === "success") {
                        let currentRank = 1;
                        let previousPoints = null;
                        
                        data.data.forEach((user, index) => {
                            // Handle ties - same points get same rank
                            if (user.points !== previousPoints) {
                                currentRank = index + 1;
                            }
                            previousPoints = user.points;
                            
                            // Determine medal icon for top 3
                            let rankDisplay = currentRank;
                            let medalIcon = '';
                            
                            if (currentRank === 1) {
                                medalIcon = '<i class="bi bi-trophy-fill gold"></i>';
                            } else if (currentRank === 2) {
                                medalIcon = '<i class="bi bi-trophy-fill silver"></i>';
                            } else if (currentRank === 3) {
                                medalIcon = '<i class="bi bi-trophy-fill bronze"></i>';
                            }
                            
                            let row = `
                                <tr data-idno="${user.idno}">
                                    <td class="rank-cell">${medalIcon} ${rankDisplay}</td>
                                    <td>${user.idno}</td>
                                    <td>${user.firstname} ${user.lastname}</td>
                                    <td>${user.course}</td>
                                    <td>${user.yearlvl}</td>
                                    <td>${user.points}</td>
                                    <td>
                                        <i class="bi bi-award-fill award-badge text-muted" 
                                           data-award="most_active" 
                                           title="Assign Most Active Award"
                                           onclick="assignAward('${user.idno}', '${user.firstname} ${user.lastname}', 'most_active')"></i>
                                        <i class="bi bi-star-fill award-badge text-muted" 
                                           data-award="top_performing" 
                                           title="Assign Top-Performing Award"
                                           onclick="assignAward('${user.idno}', '${user.firstname} ${user.lastname}', 'top_performing')"></i>
                                    </td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });
                    } else {
                        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Failed to load leaderboard</td></tr>`;
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    document.getElementById("leaderboardBody").innerHTML = 
                        `<tr><td colspan="7" class="text-center text-danger">An error occurred loading data</td></tr>`;
                });
        }

        function fetchCurrentAwards() {
            fetch("../db/fetch_awards.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        if (data.most_active) {
                            document.getElementById("mostActiveUser").innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${data.most_active.name}</strong><br>
                                        <small>${data.most_active.idno}</small>
                                    </div>
                                    <button class="btn btn-sm btn-danger" 
                                            onclick="removeAward('${data.most_active.idno}', 'most_active')">
                                        <i class="bi bi-x-circle"></i> Remove
                                    </button>
                                </div>
                            `;
                        }

                        if (data.top_performing) {
                            document.getElementById("topPerformingUser").innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${data.top_performing.name}</strong><br>
                                        <small>${data.top_performing.idno}</small>
                                    </div>
                                    <button class="btn btn-sm btn-danger" 
                                            onclick="removeAward('${data.top_performing.idno}', 'top_performing')">
                                        <i class="bi bi-x-circle"></i> Remove
                                    </button>
                                </div>
                            `;
                        }
                    }
                })
                .catch(error => {
                    console.error("Error fetching awards:", error);
                });
        }

        function assignAward(idno, name, awardType) {
            if (confirm(`Assign ${formatAwardName(awardType)} award to ${name}?`)) {
                fetch("../db/assign_awards.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `idno=${encodeURIComponent(idno)}&award_type=${encodeURIComponent(awardType)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Award assigned successfully!");
                        fetchCurrentAwards();
                        updateAwardBadges();
                    } else {
                        alert("Failed to assign award: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while assigning the award.");
                });
            }
        }

        function removeAward(idno, awardType) {
            if (confirm(`Remove ${formatAwardName(awardType)} award?`)) {
                fetch("../db/remove_awards.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `award_type=${encodeURIComponent(awardType)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Award removed successfully!");
                        fetchCurrentAwards();
                        updateAwardBadges();
                    } else {
                        alert("Failed to remove award: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while removing the award.");
                });
            }
        }

        function updateAwardBadges() {
            // This would be called after award changes to update the badge colors
            fetchCurrentAwards();
        }

        function formatAwardName(awardType) {
            return awardType === 'most_active' ? 'Most Active' : 'Top-Performing';
        }
    </script>
</body>
</html>