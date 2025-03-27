<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- bootstrap 5 css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- bootstrap 5 icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!--extended css-->
    <link rel="stylesheet" href="css/globalStyle.css"> 

    <title>Registeration</title>
    <style>
    body{
        background-image: url('images/complab_bg.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;

        height: 100%;
        margin-top: 60px;
        margin-bottom: 60px;
    }
    .bg-white-styles{
        background-color: rgba(255, 255, 255, 0.8);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

    }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <!--main container-->
    <div class="container"> 
        <!--row for centering content-->
        <div class=" row justify-content-center"> 
            <!--columns for responsive width-->
            <div class="col-10 col-sm-8 col-md-8 col-lg-8 col-xl-8">

                <!--registeration form section container-->
                <div class="bg-white-styles p-5 justify-content-center rounded">
                    <form class="d-flex flex-column gap-3" id="registerForm" action="db/registerdb.php" method="POST">
                       
                    <!--Registration Title-->
                        <div class="text-center">
                            <h3 class="headers">Registration</h3>
                            <p>Create your account now</p>
                        </div>

                        <!--ID number-->
                        <div class="regfields_container">
                            <label class="form-label">ID Number</label>
                            <input type="text" id="idno" name="idno" placeholder="Enter Id number" class="form-control" autocomplete="off">
                            <div id="idnoError" class="invalid-feedback"></div>
                        </div>

                        <!--Personal Info Group-->
                        <div id="namesGroup" class="d-flex flex-column flex-lg-row gap-3 space">
                            <div class="regfields_container d-flex flex-column flex-grow-1">
                                <label class="form-label">Lastname</label>
                                <input type="text" id="lastname" name="lastname" placeholder="Enter lastname" class="form-control w-100" autocomplete="off">
                                <div id="lastnameError" class="invalid-feedback"></div>
                            </div>
                            <div class="regfields_container d-flex flex-column flex-grow-1">
                                <label class="form-label">Firstname</label>
                                <input type="text" id="firstname" name="firstname" placeholder="Enter firstname" class="form-control w-100" autocomplete="off">
                                <div id="firstnameError" class="invalid-feedback"></div>
                            </div>
                            <div class="regfields_container d-flex flex-column flex-grow-1">
                                <label class="form-label">Middlename</label>
                                <input type="text" id="middlename" name="middlename" placeholder="Enter middlename" class="form-control w-100" autocomplete="off">
                                <div id="middlenameError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        
                        <!--Email Address-->
                        <div class="regfields_container d-flex flex-column flex-grow-1">
                            <label class="form-label">Email</label>
                            <input type="text" id="email" name="email" placeholder="Enter email address" class="form-control w-100" autocomplete="off">
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>

                        <!--Dropdowns Group-->
                        <div id="dropdwonsGroup" class="d-flex flex-column flex-lg-row gap-3 space">
                            <div class="regfields_container d-flex flex-column flex-grow-1">
                                <label for="course" class="form-label">Course</label>
                                <select class="form-select" name="course" id="course">
                                    <option value="" disabled selected>Select a Course</option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="CS">CS</option>
                                    <option value="BEED">BEED</option>
                                    <option value="BSPSYCH">BSPSYCH</option>
                                    <option value="BSHM">BSHM</option>
                                </select>
                                <div id="courseError" class="invalid-feedback"></div>
                            </div>
                            <div class="regfields_container d-flex flex-column flex-grow-1">
                                <label for="yearlvl" class="form-label">Year Level</label>
                                <select class="form-select" name="yearlvl" id="yearlvl">
                                    <option value="" disabled selected>Select a Year</option>
                                    <option value="1st year">1st Year</option>
                                    <option value="2nd year">2nd Year</option>
                                    <option value="3rd year">3rd Year</option>
                                    <option value="4th year">4th Year</option>
                                </select>
                                <div id="yearlvlError" class="invalid-feedback"></div>
                            </div>
                        </div>

                        <!--Username-->
                        <div class="regfields_container">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" autocomplete="off">
                            <div id="usernameError" class="invalid-feedback"></div>
                        </div>

                        <!--Password-->
                        <div class="regfields_container">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" placeholder="Enter password" class="form-control" autocomplete="off">
                                <button type="button" class="btn btn-neutral-primary rounded-end" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div id="passwordError" class="invalid-feedback"></div>
                        </div>

                        <!--Confirm Password-->
                        <div class="regfields_container">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Enter confirm password" class="form-control" autocomplete="off">
                                <button type="button" class="btn btn-neutral-primary" id="toggleConfirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div id="confirmPasswordError" class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Register</button>
                        <p class="text-center mt-3">Already have an account?<a href="login.php" class="ms-1 text-decoration-color fw-bold">Login</a></p>
                    </form>
                    
                    <!-- Toast Notification -->
                    <div aria-live="polite" aria-atomic="true" class="position-relative">
                        <div class="toast-container position-fixed top-0 start-0 p-3 mt-3">
                            <!-- Toast for Success -->
                            <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                                <div class="toast-header bg-success text-white">
                                    <strong class="me-auto">Success</strong>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                </div>
                            </div>

                            <!-- Toast for Error -->
                            <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                                <div class="toast-header bg-danger text-white">
                                    <strong class="me-auto">Error</strong>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/notif.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () { //wait for the page to load
        const registerForm = document.getElementById("registerForm"); // select the form and input fields

        // Input Fields
        const inputs = {
            idno: document.getElementById("idno"),
            lastname: document.getElementById("lastname"),
            firstname: document.getElementById("firstname"),
            middlename: document.getElementById("middlename"),
            email: document.getElementById("email"),
            course: document.getElementById("course"),
            yearlvl: document.getElementById("yearlvl"),
            username: document.getElementById("username"),
            password: document.getElementById("password"),
            confirmPassword: document.getElementById("confirmPassword"),
        };
        
        // Custom Error Messages
        const errorMessages = {
            idno: "ID Number is required.",
            lastname: "Last Name is required.",
            firstname: "First Name is required.",
            middlename: "Middle Name is required.",
            email: "Email is required.",
            course: "Course is required.",
            yearlvl: "Year Level is required.",
            username: "Username is required.",
            password: "Password must be at least 6 characters long.",
            confirmPassword: "Confirm Password is required.",
        };

        // Password Toggle
        document.querySelectorAll(".input-group button").forEach(button => {
            button.addEventListener("click", function () {
                const input = this.previousElementSibling;
                input.type = input.type === "password" ? "text" : "password";
                this.innerHTML = input.type === "password" ? `<i class="bi bi-eye"></i>` : `<i class="bi bi-eye-slash"></i>`;
            });
        });

        // Validate Form on Submit
        registerForm.addEventListener("submit", async function (event) {
            event.preventDefault();
            let isValid = true;

            // Helper Functions
            function showError(input, message) {
                console.log("showError called for:", input.id, "with message:", message); // Debugging
                const errorElement = input.closest('.regfields_container').querySelector('.invalid-feedback');
                input.classList.add("is-invalid");
                errorElement.textContent = message;
                errorElement.style.display = "block";
                isValid = false;
            }

            function clearError(input) {
                const errorElement = input.closest('.regfields_container').querySelector('.invalid-feedback');
                input.classList.remove("is-invalid");
                errorElement.textContent = "";
                errorElement.style.display = "none";
            }
            
            // Validate Inputs
            Object.keys(inputs).forEach(key => {
                const input = inputs[key];
                if (input.value.trim() === "") {
                    showError(input, errorMessages[key]); // Use the custom error message
                } else {
                    clearError(input);
                }
            });

            // Password Validation
            if (inputs.password.value.length < 6) {
                showError(inputs.password, errorMessages.password);
            }

            // Confirm Password Validation
            if (inputs.confirmPassword.value.trim() === "") {
                showError(inputs.confirmPassword, errorMessages.confirmPassword);
            } else if (inputs.confirmPassword.value !== inputs.password.value) {
                showError(inputs.confirmPassword, "Passwords do not match."); // Custom message for mismatch
            }
            if (!isValid) return; // Stop if invalid

            // FormData Submission
            const formData = new FormData(registerForm);

            try {
                const response = await fetch('db/registerdb.php', {
                    method: 'POST',
                    body: formData
                });

                const text = await response.text();
                console.log("Server Response:", text);

                // Check if the response is valid JSON
                let data;
                try {
                    data = JSON.parse(text);
                } catch (error) {
                    console.error("Failed to parse server response:", error);
                    showToast("An error occurred. Please check the server response.");
                    return;
                }

                if (data.status === "success") {
                    showToast(data.message || "Registration successful! Please log in.");
                    setTimeout(() => window.location.href = 'login.php', 1000);
                } else {
                    showToast(data.message || "Registration failed. Please try again.");
                }
            } catch (error) {
                console.error("Error:", error);
                showToast("An error occurred. Please check your internet connection.");
            }
                
        });
    });
    </script>
</body>
</html>