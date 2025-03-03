document.addEventListener("DOMContentLoaded", function () {
    // Select the first element with class "loginform"
    const loginForm = document.getElementsByClassName("loginform")[0];
    if (!loginForm) {
      console.error("Login form not found");
      return;
    }
  
    // Add submit event listener to the form
    loginForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(loginForm);
      try {
        const response = await fetch("http://localhost/backend/controllers/login.php", {
          method: "POST",
          body: formData
        });
        const text = await response.text();
        try {
          const data = JSON.parse(text);
          if (data.success) {
            alert("Login Successful");
            if (data.role === 'admin') {
              window.location.href = "http://localhost/admin/dashboard.html";
            } else {
              window.location.href = "wallet.html";
            }
          } else {
            alert(data.message);
          }
        } catch (jsonError) {
          console.error("Error parsing JSON:", jsonError);
          alert("Error processing server response.");
        }
      } catch (error) {
        console.error("Error during login:", error);
        alert("Error during the login");
      }
    });
  });
  