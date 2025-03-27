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
    <title>Lab Rules</title>
    <style>
        /* Custom Styles */
        .lab-rules-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .lab-rules-card h5 {
            color: #4b3f72; /* Primary color */
            font-weight: bold;
            text-transform: uppercase;
        }

        .lab-rules-card p {
            font-size: 1rem;
            line-height: 1.6;
            color: #333;
        }

        .lab-rules-card ul {
            list-style-type: disc;
            padding-left: 20px;
        }

        .lab-rules-card ul li {
            margin-bottom: 10px;
        }

        .lab-rules-card hr {
            border-top: 2px solid #4b3f72;
            opacity: 0.5;
        }

        .lab-rules-card .btn-primary {
            background-color: #4b3f72;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
        }

        .lab-rules-card .btn-primary:hover {
            background-color: #3a315a;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'userHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    
    <!-- Main Container -->
    <main class="container py-4">
        <div class="row justify-content-center">
            <!-- Lab Rules Card -->
            <div class="col-12 col-md-8 col-lg-8">
                <div class="lab-rules-card">
                    <div class="text-center mb-4">
                        <h5 class="mb-3"><strong>University of Cebu</strong></h5>
                        <p class="mb-3" style="font-size: 0.9em;"><strong>College of Information & Computer Studies</strong></p>
                        <h4 class="mb-3"><strong>Laboratory Rules and Regulations</strong></h4>
                        <hr>
                    </div>

                    <p>To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>

                    <div class="mb-4">
                        <p><strong>1. Maintain Silence and Discipline</strong></p>
                        <p>Mobile phones, walkmans, and other personal equipment must be switched off.</p>

                        <p><strong>2. No Games Allowed</strong></p>
                        <p>Games, including computer-related games, card games, and other disruptive activities, are prohibited.</p>

                        <p><strong>3. Internet Usage</strong></p>
                        <p>Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing software are strictly prohibited.</p>

                        <p><strong>4. Prohibited Websites</strong></p>
                        <p>Accessing websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</p>

                        <p><strong>5. File and System Integrity</strong></p>
                        <p>Deleting computer files and changing the setup of the computer is a major offense.</p>

                        <p><strong>6. Computer Time Usage</strong></p>
                        <p>A fifteen-minute allowance is given for each use. Otherwise, the unit will be given to those who wish to "sit-in".</p>

                        <p><strong>7. Proper Decorum</strong></p>
                        <ul>
                            <li>Do not enter the lab unless the instructor is present.</li>
                            <li>All bags, knapsacks, and similar items must be deposited at the counter.</li>
                            <li>Follow the seating arrangement of your instructor.</li>
                            <li>At the end of class, close all software programs.</li>
                            <li>Return all chairs to their proper places after use.</li>
                        </ul>

                        <p><strong>8. Prohibited Activities</strong></p>
                        <p>Chewing gum, eating, drinking, smoking, and vandalism are prohibited inside the lab.</p>

                        <p><strong>9. Disturbances</strong></p>
                        <p>Anyone causing a continual disturbance will be asked to leave the lab. Offensive acts or gestures are not tolerated.</p>

                        <p><strong>10. Hostile Behavior</strong></p>
                        <p>Persons exhibiting hostile or threatening behavior will be asked to leave the lab.</p>

                        <p><strong>11. Serious Offenses</strong></p>
                        <p>For serious offenses, the lab personnel may call the Civil Security Office (CSU) for assistance.</p>

                        <p><strong>12. Technical Issues</strong></p>
                        <p>Report any technical problems or difficulties to the laboratory supervisor, student assistant, or instructor immediately.</p>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5 class="mb-3"><strong>Disciplinary Action</strong></h5>
                        <ul>
                            <li><strong>First Offense:</strong> Suspension from classes recommended by the Head, Dean, or OIC.</li>
                            <li><strong>Second and Subsequent Offenses:</strong> Heavier sanctions will be endorsed to the Guidance Center.</li>
                        </ul>
                    </div>

                    <div class="text-center">
                        <a href="userDashboard.php" class="btn btn-primary">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/sideNav.js"></script>
</body>
</html>