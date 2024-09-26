    // Toggle User Login Form
    function toggleUserLogin() {
        const userLoginForm = document.getElementById('userLoginForm');
        if (userLoginForm.style.display === 'none' || userLoginForm.style.display === '') {
            userLoginForm.style.display = 'block';
        } else {
            userLoginForm.style.display = 'none';
        }
    }
