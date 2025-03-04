document.addEventListener("DOMContentLoaded", async function () {
  const profileForm = document.getElementById("profileForm");
  const updateMessage = document.getElementById("updateMessage");

  async function fetchProfile() {
    try {
      const token = localStorage.getItem('authToken'); 

      if (!token) {
        alert("User not authenticated. Please login.");
        return;
      }

      const response = await fetch("http://localhost/backend/controllers/get_profile.php", {
        method: "GET",
        headers: {
          "Authorization": "Bearer " + localStorage.getItem("authToken"), 
          "Content-Type": "application/json"
      }

      });

      const data = await response.json();

      if (data.status === "success") {
        document.getElementById("fullname").value = data.user.name;
        document.getElementById("address").value = data.user.address;
        document.getElementById("phone").value = data.user.phone;
        document.getElementById("email").value = data.user.email;
      } else {
        alert(data.message); 
      }
    } catch (error) {
      console.error("Error fetching profile:", error);
    }
  }

  fetchProfile();

  profileForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(profileForm);
    const token = localStorage.getItem('authToken'); 

    if (!token) {
      alert("User not authenticated. Please login.");
      return;
    }

    try {
      const response = await fetch("http://localhost/backend/controllers/update_profile.php", {
        method: "POST",
        body: formData,
        headers: {
          'Authorization': `Bearer ${token}`, 
        }
      });
      const text = await response.text();

      try {
        const data = JSON.parse(text);
        if (data.status === "success") {
          updateMessage.textContent = data.message;
          updateMessage.style.color = "green";
        } else {
          updateMessage.textContent = data.message;
          updateMessage.style.color = "red";
        }
      } catch (jsonError) {
        console.error("Invalid JSON response:", text);
      }
    } catch (error) {
      console.error("Error updating profile:", error);
      alert("An error occurred while updating the profile.");
    }
  });
});
