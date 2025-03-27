// Function to show Bootstrap toast
function showToast(message, type = "success", redirectUrl = null) {
    let toastElement;

    if (type === "success") {
        toastElement = document.getElementById("successToast");
    } else {
        toastElement = document.getElementById("errorToast");
    }

    toastElement.querySelector(".toast-body").textContent = message;

    // Initialize and show the toast
    const toast = new bootstrap.Toast(toastElement);
    toast.show();

    // Redirect after the toast hides (if redirectUrl is provided)
    if (redirectUrl) {
        toastElement.addEventListener("hidden.bs.toast", () => {
            window.location.href = redirectUrl;
        });
    }
}

// Function to handle form submission for login and register
async function handleFormSubmit(event) {
    event.preventDefault(); // Prevent default form submission

    const form = event.target; // Get the form that was submitted
    const formData = new FormData(form);
    let endpoint = "";

    // Identify whether it’s the login or register form
    if (form.id === "registerForm") {
        endpoint = "db/registerdb.php"; // Registration script
    } else if (form.id === "loginForm") {
        endpoint = "db/logindb.php"; // Login script
    } else {
        console.error("Unknown form submitted.");
        showToast("An error occurred. Unknown form.", "error");
        return;
    }

    try {
        const response = await fetch(endpoint, {
            method: "POST",
            body: formData
        });

        const text = await response.text();
        console.log("Server Response:", text);

        // Try to parse JSON response
        let data;
        try {
            data = JSON.parse(text);
        } catch (error) {
            console.error("Failed to parse server response:", error);
            showToast("An error occurred. Please check the server response.", "error");
            return;
        }

        // Show toast based on server response
        showToast(data.message, data.status, data.redirect);
    } catch (error) {
        console.error("Error:", error);
        showToast("An error occurred. Please check your internet connection.", "error");
    }
}

// Attach event listeners to both forms (if they exist)
document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("registerForm");
    const loginForm = document.getElementById("loginForm");

    if (registerForm) {
        registerForm.addEventListener("submit", handleFormSubmit);
    }

    if (loginForm) {
        loginForm.addEventListener("submit", handleFormSubmit);
    }
});
