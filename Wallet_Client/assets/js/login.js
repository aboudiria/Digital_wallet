document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        e.preventDefault();

        let email = document.getElementById('loginEmail').value;
        let password = document.getElementById('loginPassword').value;

        // Validate email format
        if (!validateEmail(email)) {
            alert("Please enter a valid email address.");
            return;
        }

        // Simulating login process
        setTimeout(() => {
            alert("Login Successful!");
        }, 1000);
    });
});

// Function to validate email format
function validateEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return emailPattern.test(email);
}

// Function to toggle password visibility
function togglePassword(fieldId, iconElement) {
    let field = document.getElementById(fieldId);
    field.type = field.type === "password" ? "text" : "password";
    iconElement.textContent = field.type === "password" ? "👁️" : "🙈";
}

// Function to handle social logins
function socialLogin(platform) {
    alert(`Logging in with ${platform}...`);
}
