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
    } elseif ($currentPage == "userAnnouncement.php" || $currentPage == "adminAnnouncement.php") {
        $pageTitle = "Announcement";
    } elseif ($currentPage == "userLabRules.php" || $currentPage == "adminLabRules.php") {
        $pageTitle = "Lab Rules";
    } elseif ($currentPage == "userSitInRules.php") {
        $pageTitle = "Sit-In Rules";
    } elseif ($currentPage == "userSitInHistory.php") {
        $pageTitle = "Sit-In History";
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
    <a href="userProfile.php" class="text-decoration-none">
        <div class="userProfile">
            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="User Profile Picture" class="rounded-circle img-fluid profilePic" id="sidebarProfileImage">
            <span class="text-white"><?php echo htmlspecialchars($username); ?></span>  
        </div>
    </a>
    <!-- Navigation Links -->
    <div class="navLinks">
        <a href="userDashboard.php" class="navItem">
            <i class="bi bi-house-door-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="userAnnouncement.php" class="navItem">
            <i class="bi bi-megaphone-fill"></i>
            <span>Announcement</span>
        </a>
        <a href="userSitInHistory.php" class="navItem">
            <i class="bi bi-clock-fill"></i>
            <span>Sit-In History</span>
        </a>
        <a href="userSitInRules.php" class="navItem">
            <i class="bi bi-journal-bookmark-fill"></i>
            <span>Sit-In Rules</span>
        </a>
        <a href="userLabRules.php" class="navItem">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span>Lab Rules</span>
        </a>
        <a href="../login.php" class="navItem">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>
</div>
