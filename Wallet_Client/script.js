document.addEventListener("DOMContentLoaded", function () {
    // Form submit event handler
    document.getElementById('registrationForm').addEventListener('submit', function (e) {
        e.preventDefault();

        let password = document.getElementById('password').value;
        let confirmPassword = document.getElementById('confirmPassword').value;
        let email = document.getElementById('email').value;

        // Check if passwords match
        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return;
        }

        // Validate password strength
        if (!validatePassword(password)) {
            alert("Password must be at least 8 characters long and contain a mix of letters, digits, and special characters.");
            return;
        }

        // Validate email format
        if (!validateEmail(email)) {
            alert("Please enter a valid email address.");
            return;
        }

        // Simulating API Call
        setTimeout(() => {
            alert("Registration Successful!");
        }, 1000);
    });
});

// Function to validate password strength
function validatePassword(password) {
    // Password must be at least 8 characters
    if (password.length < 8) {
        return false;
    }

    // Check for at least one letter, one digit, and one special character
    const hasLetter = /[a-zA-Z]/.test(password);
    const hasDigit = /\d/.test(password);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    return hasLetter && hasDigit && hasSpecialChar;
}

// Function to validate email format
function validateEmail(email) {
    // Basic email regex pattern (checks for valid format like user@example.com)
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return emailPattern.test(email);
}

// Function to toggle password visibility
function togglePassword(fieldId, iconElement) {
    let field = document.getElementById(fieldId);
    field.type = field.type === "password" ? "text" : "password";
    iconElement.textContent = field.type === "password" ? "👁️" : "🙈";
}

// Function to handle social sign-ups
function socialSignup(platform) {
    alert(`Signing up with ${platform}...`);
}
