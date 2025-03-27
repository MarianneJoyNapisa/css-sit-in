<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Get the current file name
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Set page title dynamically
    if ($currentPage == "userDashboard.php" || $currentPage == "adminDashboard.php") {
        $pageTitle = "Dashboard";
    } elseif ($currentPage == "userProfile.php" || $currentPage == "adminProfile.php") {
        $pageTitle = "Profile";
    } elseif ($currentPage == "userAnnouncement.php") {
        $pageTitle = "Announcement";
    } elseif ($currentPage == "userLabRules.php") {
        $pageTitle = "Lab Rules";
    } elseif ($currentPage == "userSitInRules.php") {
        $pageTitle = "Sit-In Rules";
    } elseif ($currentPage == "adminStudentList.php") {
        $pageTitle = "Student List";
    } elseif ($currentPage == "adminCurrentSitIn.php") {
        $pageTitle = "Current Sit-In Page";
    } elseif ($currentPage == "adminSitInReport.php") {
        $pageTitle = "Sit-In Records";
    } elseif ($currentPage == "adminLabReports.php") {
        $pageTitle = "Lab Reports";
    } else {
        $pageTitle = "Page"; // Default title if not listed
    }

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }

    // Use session data instead of querying the database
    $username = $_SESSION['username'] ?? 'Unknown User';
    $profileImage = !empty($_SESSION['image']) ? "../images/{$_SESSION['image']}" : '../images/default_image.png';
?>

<!-- Header -->
<div class="headerPage">
    <button id="sideNav_toggleBtn">
        <i class="bi bi-list fs-4 p-2"></i>
    </button>
    <h3 class="m-0"><?php echo $pageTitle; ?></h3>
    <i class="bi bi-bell fs-4 p-2"></i>
</div>

<!-- Sidebar -->
<div class="sideNavBar" id="sidenavBar">
    <!-- User Profile Section -->
    <a href="adminProfile.php" class="text-decoration-none">
        <div class="userProfile">
            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="User Profile Picture" class="rounded-circle img-fluid profilePic" id="sidebarProfileImage">
            <span class="text-white"><?php echo htmlspecialchars($username); ?></span>  
        </div>
    </a>
    <!-- Navigation Links -->
    <div class="navLinks">
        <a href="adminDashboard.php" class="navItem">
            <i class="bi bi-house-door-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="adminStudentList.php" class="navItem">
            <i class="bi bi-people-fill"></i>
            <span>Student List</span>
        </a>
        <a href="adminCurrentSitIn.php" class="navItem">
            <i class="bi bi-journal-bookmark-fill"></i>
            <span>Current Sit-In Log</span>
        </a>
        <a href="adminSitInReport.php" class="navItem">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span>Sit-In Records</span>
        </a>
        <a href="adminLabReports.php" class="navItem">
            <i class="bi bi-file-spreadsheet"></i>
            <span>Lab Reports</span>
        </a>
        <a href="adminViewFeedback.php" class="navItem">
            <i class="bi bi-chat-right-dots-fill"></i>
            <span>View Feedback</span>
        </a>
        <a href="../login.php" class="navItem">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>
</div>
