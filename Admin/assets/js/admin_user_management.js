// File: assets/js/admin_user_management.js

document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("authToken");
    if (!token) {
        alert("You must log in first.");
        window.location.href = "login.html";
        return;
    }
    
    try {
        const response = await fetch("http://localhost/backend/controllers/admin_users.php", {
            method: "GET",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            }
        });
        
        const text = await response.text();

        try {
            const data = JSON.parse(text);
            if (data.success) {
                populateUserTable(data.users);
            } else {
                alert(data.message);
            }
            
        } catch (jsonError) {
            console.error("Error parsing JSON:", jsonError);
            alert("An error occurred while fetching users.");

        }
        
    } catch (error) {
        console.error("Error fetching users:", error);
    }
});

function populateUserTable(users) {
    const tableBody = document.querySelector(".user-table tbody");
    tableBody.innerHTML = ""; 
    users.forEach(user => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.verification_status}</td>
            <td>
                <a href="edit_user.php?id=${user.id}" class="btn small-btn">Edit</a>
                <a href="delete_user.php?id=${user.id}" class="btn small-btn danger">Delete</a>
            </td>
        `;
        tableBody.appendChild(tr);
    });
}
