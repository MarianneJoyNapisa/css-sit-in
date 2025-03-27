<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug session data (remove after testing)
// var_dump($_SESSION);
// exit();

// Get the current file name
$currentPage = basename($_SERVER['PHP_SELF']);

// Set page title dynamically
$pageTitles = [
    "userDashboard.php" => "Dashboard",
    "adminDashboard.php" => "Dashboard",
    "userProfile.php" => "Profile",
    "adminProfile.php" => "Profile",
    "userAnnouncement.php" => "Announcement", // Added for user announcement page
    "adminAnnouncement.php" => "Announcement"  // Added for admin announcement page
];
$pageTitle = $pageTitles[$currentPage] ?? "Page";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Use session data for profile details
$username = $_SESSION['username'] ?? 'Unknown User';
$profileImage = !empty($_SESSION['image']) ? "../images/{$_SESSION['image']}" : '../images/default_image.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
</head>
<body>
    <?php include 'userHeaderSideNav.php'; ?>

    <div id="overlay" class="overlay"></div>

    <main class="p-4" style="margin-top: 70px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="bg-white shadow p-3 mb-5 bg-body rounded">
                        <form id="profile-form" enctype="multipart/form-data" class="d-flex flex-column gap-3">
                            <div class="userProfile text-center">
                                <!-- Fixed image source -->
                                <img id="profilePic" src="<?php echo $profileImage; ?>?t=<?php echo time(); ?>" 
                                alt="User Profile Picture" 
                                class="rounded-circle img-fluid profilePic">
                                <span id="display-username" class="text-dark d-block mt-2"><?php echo $username; ?></span>
                            </div>

                            <div class="regfields_container">
                                <div class="input-group">
                                    <input type="file" class="form-control" id="profile_pic" name="profile_pic" disabled readonly>
                                </div>
                            </div>

                            <div class="regfields_container">
                                <label class="form-label">ID Number</label>
                                <input type="text" id="idno" name="idno" class="form-control" disabled readonly>
                            </div>

                            <div id="namesGroup" class="d-flex flex-column flex-lg-row gap-3">
                                <div class="regfields_container flex-grow-1">
                                    <label class="form-label">Lastname</label>
                                    <input type="text" id="lastname" name="lastname" class="form-control" disabled readonly>
                                </div>
                                <div class="regfields_container flex-grow-1">
                                    <label class="form-label">Firstname</label>
                                    <input type="text" id="firstname" name="firstname" class="form-control" disabled readonly>
                                </div>
                                <div class="regfields_container flex-grow-1">
                                    <label class="form-label">Middlename</label>
                                    <input type="text" id="middlename" name="middlename" class="form-control" disabled readonly>
                                </div>
                            </div>

                            <div class="regfields_container">
                                <label class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" disabled readonly>
                            </div>

                            <div id="dropdownsGroup" class="d-flex flex-column flex-lg-row gap-3">
                                <!-- Course (Dropdown - Read-Only Initially) -->
                                <div class="regfields_container d-flex flex-column flex-grow-1">
                                    <label for="course">Course</label>
                                    <select class="form-select" name="course" id="course" disabled required>
                                        <option value="none">Select a Course</option>
                                        <option value="BSIT">BSIT</option>
                                        <option value="CS">CS</option>
                                        <option value="BEED">BEED</option>
                                        <option value="BSPSYCH">BSPSYCH</option>
                                        <option value="BSHM">BSHM</option>
                                    </select>
                                </div>

                                <!-- Year Level (Dropdown - Read-Only Until Edit) -->
                                <div class="regfields_container d-flex flex-column flex-grow-1">
                                    <label for="yearlvl">Year Level</label>
                                    <select class="form-select" name="yearlvl" id="yearlvl" disabled required>
                                        <option value="none">Select a Year</option>
                                        <option value="1st year">1st Year</option>
                                        <option value="2nd year">2nd Year</option>
                                        <option value="3rd year">3rd Year</option>
                                        <option value="4th year">4th Year</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" id="editBtn" class="btn btn-primary" onclick="enableEditing()">Edit</button>
                                <button type="button" id="saveBtn" class="btn btn-success d-none" onclick="saveProfile()">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetchProfile();
        });

        function fetchProfile() {
            fetch("../db/profiledb.php")
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        document.getElementById("idno").value = data.data.idno;
                        document.getElementById("lastname").value = data.data.lastname;
                        document.getElementById("firstname").value = data.data.firstname;
                        document.getElementById("middlename").value = data.data.middlename;
                        document.getElementById("email").value = data.data.email;
                        document.getElementById("course").value = data.data.course;
                        document.getElementById("yearlvl").value = data.data.yearlvl;

                        // Ensure the correct image path
                        let profilePic = data.data.image ? `../images/${data.data.image}` : '../images/default_image.png';
                        document.getElementById("profilePic").src = profilePic + "?t=" + new Date().getTime();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error("Error fetching profile:", error));
        }

        function enableEditing() {
            // Enable all input fields by removing the readonly attribute
            document.querySelectorAll("input").forEach(input => {
                input.removeAttribute("readonly");
                input.removeAttribute("disabled");
            });

            // Enable dropdowns by removing the disabled attribute
            document.querySelectorAll("select").forEach(select => {
                select.removeAttribute("disabled");
            });

            // Enable the file input
            document.getElementById("profile_pic").removeAttribute("disabled");

            // Hide the Edit button and show the Save button
            document.getElementById("editBtn").classList.add("d-none");
            document.getElementById("saveBtn").classList.remove("d-none");
        }

        function saveProfile() {
            const form = document.getElementById("profile-form");
            const formData = new FormData(form);

            fetch("../db/profiledb.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    // Re-enable readonly and disabled attributes after saving
                    document.querySelectorAll("input").forEach(input => {
                        input.setAttribute("readonly", true);
                    });
                    document.querySelectorAll("select").forEach(select => {
                        select.setAttribute("disabled", true);
                    });

                    // Disable the file input
                    document.getElementById("profile_pic").setAttribute("disabled", true);

                    // Hide the Save button and show the Edit button
                    document.getElementById("editBtn").classList.remove("d-none");
                    document.getElementById("saveBtn").classList.add("d-none");

                    // Update the profile picture if it was changed
                    if (data.image) {
                        document.getElementById("profilePic").src = `../images/${data.image}?t=${new Date().getTime()}`;
                    }
                }
            })
            .catch(error => console.error("Error saving profile:", error));
        }
    </script>
</body>
</html>