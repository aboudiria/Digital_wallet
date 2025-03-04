document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementsByClassName("loginform")[0];
  if (!loginForm) {
      console.error("Login form not found");
      return;
  }

  loginForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(loginForm);

      try {
          const response = await fetch("http://localhost/backend/controllers/login.php", {
              method: "POST",
              body: formData
          });

          const text = await response.text();
          console.log("Raw Response:", text);

          try {
              const data = JSON.parse(text);
              console.log("Parsed Data:", data); 

              if (data.status === "success") {
                  localStorage.setItem("authToken", data.token);
                  
                  console.log("User Role:", data.role);

                  if (data.role === "admin") {
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
          alert("Error during login request.");
      }
  });
});
