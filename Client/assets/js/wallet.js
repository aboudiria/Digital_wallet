document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("authToken");
    if (!token) {
        alert("You must log in first.");
        window.location.href = "login.html";
        return;
    }
    
    await fetchWalletData(token);
});

async function fetchWalletData(token) {
    try {
        const response = await fetch("http://localhost/backend/controllers/wallet.php", {
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
                document.querySelector(".wallet-stats .stat-card:nth-child(1) p").textContent = `$${parseFloat(data.balance).toFixed(2)}`;
                document.querySelector(".wallet-stats .stat-card:nth-child(2) p").textContent = `$${parseFloat(data.total_deposits).toFixed(2)}`;
                document.querySelector(".wallet-stats .stat-card:nth-child(3) p").textContent = `$${parseFloat(data.total_withdrawals).toFixed(2)}`;
            } else {
                alert("Failed to fetch wallet data. Please log in again.");
                localStorage.removeItem("authToken");
                window.location.href = "login.html";
            }
            
        } catch (jsonError) {
            console.error("Invalid JSON response:", text);
            throw new Error("Invalid JSON response.");
            
        }

    } catch (error) {
        console.error("Error fetching wallet data:", error);
    }
}
