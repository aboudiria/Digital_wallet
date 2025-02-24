document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('confirmPassword').value;
    
    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return;
    }

    
    alert("Registration Successful!");
});
