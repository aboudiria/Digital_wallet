document.addEventListener("DOMContentLoaded", function() {
    const registerForm = document.getElementById("registerForm");
  
    registerForm.addEventListener("submit", function(event) {
      // Retrieve form field values
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm_password").value;
      
      let errors = [];
  
      // 1. Check if password and confirm password match
      if (password !== confirmPassword) {
        errors.push("Passwords do not match.");
      }
  
      // 2. Check for password strength:
      // At least 8 characters, one alphabetic letter, one digit, and one special character
      const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
      if (!passwordPattern.test(password)) {
        errors.push("Password must be at least 8 characters long and include a digit, a special character, and an alphabetic character.");
      }
  
      // 3. Validate the email format using regex
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        errors.push("Please enter a valid email address.");
      }
  
      // If there are errors, prevent form submission and display them via an alert
      if (errors.length > 0) {
        event.preventDefault();
        alert("There were errors in your submission:\n" + errors.join("\n"));
      }
    });
  });
  