document.addEventListener('DOMContentLoaded', function () {
    const signupForm = document.getElementById('signupForm');
    const loginForm = document.getElementById('loginForm');

    signupForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Clear previous error messages
        document.getElementById('nameError').textContent = '';
        document.getElementById('usernameError').textContent = '';
        document.getElementById('emailError').textContent = '';
        document.getElementById('passwordError').textContent = '';

        const name = document.getElementById('signupName').value.trim();
        const username = document.getElementById('signupUsername').value.trim();
        const email = document.getElementById('signupEmail').value.trim();
        const password = document.getElementById('signupPassword').value.trim();
        let valid = true;

        // Validate name
        const namePattern = /^[a-zA-Z\s]+$/;
        if (!namePattern.test(name)) {
            document.getElementById('nameError').textContent = 'Name must contain only letters and spaces.';
            valid = false;
        }

        // Validate username (only checking non-empty here, uniqueness check will be server-side)
        if (username === '') {
            document.getElementById('usernameError').textContent = 'Username is required.';
            valid = false;
        }

        // Validate email
        if (email === '') {
            document.getElementById('emailError').textContent = 'Email is required.';
            valid = false;
        }

        // Validate password
        const passwordPattern = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,}$/;
        if (!passwordPattern.test(password)) {
            document.getElementById('passwordError').textContent = 'Password must be at least 6 characters long, contain at least one uppercase letter and one number.';
            valid = false;
        }

        if (valid) {
            const formData = new FormData(signupForm);

            fetch('php/signup.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('signupMessage').textContent = data.message;
                if (data.success) {
                    // Clear form fields on successful sign-up
                    signupForm.reset();
                } else if (data.errors) {
                    if (data.errors.username) {
                        document.getElementById('usernameError').textContent = data.errors.username;
                    }
                }
            })
            .catch(error => {
                document.getElementById('signupMessage').textContent = 'An error occurred. Please try again.';
                console.error('Error:', error);
            });
        }
    });

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(loginForm);
    
        fetch('php/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to feed.php on successful login
                window.location.href = 'feed.php';
            } else {
                document.getElementById('loginMessage').textContent = data.message;
            }
        })
        .catch(error => {
            document.getElementById('loginMessage').textContent = 'An error occurred. Please try again.';
            console.error('Error:', error);
        });
    });
});
