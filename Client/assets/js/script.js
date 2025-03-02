document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("registerForm");
  
    registerForm.addEventListener("submit", async function (event) {
        event.preventDefault();
        
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;
        const email = document.getElementById("email").value;
        const fullname = document.getElementById("fullname").value;
        const phone = document.getElementById("phone").value;
        const address = document.getElementById("address").value;
        const idDocument = document.getElementById("id_document").files[0];
  
        if (!validatePassword(password)) {
            alert("Password must be at least 8 characters long, include a special character, a digit, and an alphabetic letter.");
            return;
        }
    
        if (password !== confirmPassword) {
            alert("Passwords do not match! Please enter the same password.");
            return;
        }
    
        if (!validateEmail(email)) {
            alert("Invalid email format! Please enter a correct email.");
            return;
        }
    
        
        const formData = new FormData();
        formData.append("fullname", fullname);
        formData.append("email", email);
        formData.append("password", password);
        formData.append("confirm_password", confirmPassword);
        formData.append("address", address);
        formData.append("phone", phone);
        formData.append("id_document", idDocument);
    
        try {
            const response = await fetch("http://localhost/Digital_Wallet/Backend/controllers/register.php", {
                method: "POST",
                body: formData
            });
    
            const result = await response.json();
    
            if (result.success) {
                alert("Registration successful. Please wait for verification.");
                window.location.href = "wallet.html";
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred during registration. Please try again.");
        }
    });
  
    function validatePassword(password) {
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return passwordRegex.test(password);
    }
  
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});
