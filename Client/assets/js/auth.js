document.addEventListener("DOMContentLoaded", function () {
    // Register Event Listener
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener("submit", async function (event) {
            event.preventDefault(); // Prevent default form submission
            
            // Collect form data
            const formData = new FormData(registerForm);
            
            try {
                const response = await fetch("http://localhost/Backend/auth/register.php", {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();
                
                if (response.ok) {
                    alert(data.message); // Success alert
                    window.location.href = "login.html"; // Redirect to login page
                } else {
                    alert("Error: " + data.message); // Show error message
                }
            } catch (error) {
                console.error("Registration Error:", error);
                alert("An unexpected error occurred.");
            }
        });
    }

    // Login Event Listener
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", async function (event) {
            event.preventDefault();
            
            // Collect form data
            const formData = new FormData(loginForm);
            
            try {
                const response = await fetch("http://localhost/Backend/auth/login.php", {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.message); // Success alert
                    
                    // Store user data in localStorage
                    localStorage.setItem("user", JSON.stringify(data.user));
                    
                    window.location.href = "dashboard.html"; // Redirect after login
                } else {
                    alert("Error: " + data.message); // Show error message
                }
            } catch (error) {
                console.error("Login Error:", error);
                alert("An unexpected error occurred.");
            }
        });
    }
});
