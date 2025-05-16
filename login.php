<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/globalStyle.css">
    
    <style>
        body{
            background-image: url('images/complab_bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .bg-white-styles{
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

        }
        #logo{
            max-width: 100px;
            height: auto;
        }
    </style>

    <title>Login</title>
</head>
<body class="d-flex justify-content-center align-items-center">
    <!--main container-->
    <div class="container">
        <!--row for centering content-->
        <div class=" row justify-content-center">
            <!--columns for responsive width-->
            <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-4">
                <!--registeration form section container-->
                <div class="bg-white-styles p-4 d-flex flex-column gap-3 justify-content-center rounded">

                    <img src="images/css_logo.png" alt="CCS_Logo" class="img-fluid mx-auto" id="logo">
                    <h3 class="headers text-center">LOGIN</h3>

                    <form class="d-flex flex-column gap-3" id="loginForm" action="db/logindb.php" method="POST">
                        
                        <!--username -->
                        <div id="usernameFields">
                            <label class="form-label">Username</label>
                            <input type="text" id="username" name="username" placeholder="Enter your username" class="form-control" autocomplete="off">        
                        </div>
                        <div id="usernameError" class="invalid-feedback"></div>
                        
                        <!--password -->
                        <div id="passwordFields">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" placeholder="Enter your password" class="form-control" autocomplete="off">
                                <button type="button" class="btn btn-neutral-primary" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div id="passwordError" class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary" >Login</button>
                    </form>
                    <p class="text-center">Don't have an account?<a href="register.php" class="ms-1 text-decoration-color">Register</a></p>
                    
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
        document.addEventListener("DOMContentLoaded", function () {
            const loginForm = document.getElementById("loginForm");
            const username = document.getElementById("username");
            const password = document.getElementById("password");
            const usernameError = document.getElementById("usernameError");
            const passwordError = document.getElementById("passwordError");

            loginForm.addEventListener("submit", function (event) {
                let isValid = true;

                if (username.value.trim() === "") {
                    username.classList.add("is-invalid");
                    usernameError.style.display = "block";
                    isValid = false;
                } else {
                    username.classList.remove("is-invalid");
                    usernameError.style.display = "none";
                }

                if (password.value.trim() === "") {
                    password.classList.add("is-invalid");
                    passwordError.style.display = "block";
                    isValid = false;
                } else {
                    password.classList.remove("is-invalid");
                    passwordError.style.display = "none";
                }

                if (!isValid) {
                    event.preventDefault();
                }
            });

            document.getElementById("togglePassword").addEventListener("click", function () {
                if (password.type === "password") {
                    password.type = "text";
                    this.innerHTML = '<i class="bi bi-eye-slash"></i>';
                } else {
                    password.type = "password";
                    this.innerHTML = '<i class="bi bi-eye"></i>';
                }
            });
        });
    </script>
</body>
</html>