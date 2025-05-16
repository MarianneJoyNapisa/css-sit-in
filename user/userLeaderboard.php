<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Use session data
$username = $_SESSION['username'] ?? 'Unknown User';
$idno = $_SESSION['id_number'] ?? '';
$profileImage = !empty($_SESSION['image']) ? "../images/{$_SESSION['image']}" : '../images/default_image.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Leaderboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <style>
        .gold { color: gold; }
        .silver { color: silver; }
        .bronze { color: #cd7f32; }
        .rank-cell { width: 80px; text-align: center; }
        .award-badge { 
            font-size: 1rem;
            margin-right: 3px;
        }
        .awarded { color: #ffc107; }
        .awards-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .award-card {
            height: 100%;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
<?php include 'userHeaderSideNav.php'; ?>

<div id="overlay" class="overlay"></div>
<main class="container mt-4">
    <!-- Awards Display Section -->
    <div class="awards-section">
        <h5 class="text-primary mb-3">Special Awards</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card award-card border-warning">
                    <div class="card-header bg-warning text-white">
                        <i class="bi bi-award-fill"></i> Most Active User
                    </div>
                    <div class="card-body" id="mostActiveUser">
                        <p class="text-muted">Loading award information...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card award-card border-info">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-star-fill"></i> Top-Performing User
                    </div>
                    <div class="card-body" id="topPerformingUser">
                        <p class="text-muted">Loading award information...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Summary Card -->
    <div id="userSummaryCard" class="card mb-4 border-info shadow-sm" style="display: none;">
        <div class="card-body">
            <h6 class="card-title text-info">Your Ranking</h6>
            <p class="mb-1"><strong>Name:</strong> <span id="userName"></span></p>
            <p class="mb-1"><strong>Rank:</strong> <span id="userRank"></span></p>
            <p class="mb-0"><strong>Points:</strong> <span id="userPoints"></span></p>
        </div>
    </div>

    <!-- Leaderboard Table -->
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
                    <th>Awards</th>
                </tr>
            </thead>
            <tbody id="leaderboardBody">
                <tr><td colspan="7" class="text-center">Loading leaderboard...</td></tr>
            </tbody>
        </table>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/sideNav.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Load current awards first
    fetchCurrentAwards();
    
    // Then load the leaderboard
    fetchLeaderboard();
});

function fetchLeaderboard() {
    fetch("../db/fetch_userleaderboard.php")
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById("leaderboardBody");
            const userSummary = document.getElementById("userSummaryCard");
            const currentUserIdno = "<?php echo $idno; ?>";
            let userFound = false;

            tbody.innerHTML = "";
            if (data.status === "success") {
                let currentRank = 1;
                let previousPoints = null;

                data.data.forEach((user, index) => {
                    // Handle ties
                    if (user.points !== previousPoints) {
                        currentRank = index + 1;
                    }
                    previousPoints = user.points;

                    let medalIcon = '';
                    if (currentRank === 1) medalIcon = '<i class="bi bi-trophy-fill gold"></i>';
                    else if (currentRank === 2) medalIcon = '<i class="bi bi-trophy-fill silver"></i>';
                    else if (currentRank === 3) medalIcon = '<i class="bi bi-trophy-fill bronze"></i>';

                    // Create award badges (read-only)
                    let awardBadges = '';
                    if (user.has_awards) {
                        if (user.most_active) {
                            awardBadges += '<i class="bi bi-award-fill award-badge text-warning" title="Most Active User"></i>';
                        }
                        if (user.top_performing) {
                            awardBadges += '<i class="bi bi-star-fill award-badge text-info" title="Top-Performing User"></i>';
                        }
                    }

                    const row = ` 
                        <tr>
                            <td class="rank-cell">${medalIcon} ${currentRank}</td>
                            <td>${user.idno}</td>
                            <td>${user.firstname} ${user.lastname}</td>
                            <td>${user.course}</td>
                            <td>${user.yearlvl}</td>
                            <td>${user.points}</td>
                            <td>${awardBadges}</td>
                        </tr>
                    `;
                    tbody.innerHTML += row;

                    // If this is the current user, show their summary
                    if (user.idno === currentUserIdno && !userFound) {
                        document.getElementById("userName").textContent = user.firstname + " " + user.lastname;
                        document.getElementById("userRank").textContent = currentRank;
                        document.getElementById("userPoints").textContent = user.points;
                        userSummary.style.display = "block";
                        userFound = true;
                    }
                });

                if (!userFound) {
                    userSummary.innerHTML = '<div class="card-body text-danger">Your data was not found in the leaderboard.</div>';
                    userSummary.style.display = "block";
                }
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
                        <div>
                            <strong>${data.most_active.name}</strong><br>
                            <small class="text-muted">${data.most_active.idno}</small>
                        </div>
                    `;
                } else {
                    document.getElementById("mostActiveUser").innerHTML = `
                        <p class="text-muted">No Most Active User selected yet</p>
                    `;
                }

                if (data.top_performing) {
                    document.getElementById("topPerformingUser").innerHTML = `
                        <div>
                            <strong>${data.top_performing.name}</strong><br>
                            <small class="text-muted">${data.top_performing.idno}</small>
                        </div>
                    `;
                } else {
                    document.getElementById("topPerformingUser").innerHTML = `
                        <p class="text-muted">No Top-Performing User selected yet</p>
                    `;
                }
            }
        })
        .catch(error => {
            console.error("Error fetching awards:", error);
            document.getElementById("mostActiveUser").innerHTML = `
                <p class="text-danger">Error loading Most Active User</p>
            `;
            document.getElementById("topPerformingUser").innerHTML = `
                <p class="text-danger">Error loading Top-Performing User</p>
            `;
        });
}
</script>
</body>
</html>