document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("registerForm");

    registerForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the form from submitting normally
        
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;
        const email = document.getElementById("email").value;

        // Validate Password
        if (!validatePassword(password)) {
            alert("Password must be at least 8 characters long, include a special character, a digit, and an alphabetic letter.");
            return;
        }

        // Check if Confirm Password Matches
        if (password !== confirmPassword) {
            alert("Passwords do not match! Please enter the same password.");
            return;
        }

        // Validate Email Format
        if (!validateEmail(email)) {
            alert("Invalid email format! Please enter a correct email.");
            return;
        }

        // Validate File Upload
        const idDocument = document.getElementById("id_document").files[0];
        if (!idDocument) {
            alert("Please upload an ID document.");
            return;
        }

        // Prepare Form Data
        const formData = new FormData(registerForm);

        // Send Form Data via Fetch API
        fetch('../', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            if (data === "User registered successfully.") {
                window.location.href = "wallet.html"; 
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("There was an error during registration.");
        });
    });

    // Function to Validate Password
    function validatePassword(password) {
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return passwordRegex.test(password);
    }

    // Function to Validate Email
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});
