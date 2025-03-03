document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("registerForm");

    if (!registerForm) {
        console.error("Register form not found.");
        return;
    }

    registerForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        // Create FormData object to handle file upload correctly
        const formData = new FormData(registerForm);

        try {
            const response = await fetch("http://localhost/backend/controllers/register.php", {
                method: "POST",
                body: formData // Automatically handles multipart/form-data
            });

            // Ensure the response is valid JSON
            const text = await response.text();
            try {
                const result = JSON.parse(text);
                console.log("Server Response:", result);

                if (result.status === "success") {
                    alert(result.message);
                    window.location.href = "wallet.html";
                } else {
                    alert("Error: " + result.message);
                }
            } catch (jsonError) {
                console.error("Invalid JSON response:", text);
                alert("Unexpected server response. Check the console for details.");
            }
        } catch (error) {
            console.error("Error during registration:", error);
            alert("An error occurred during registration. Please try again later.");
        }
    });
});