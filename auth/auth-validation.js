// This file handles form validation for login and registration

// Wait for the page to fully load before running the code
document.addEventListener('DOMContentLoaded', function() {

    // Get the registration form if it exists on the page
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        setupRegistrationValidation();
    }

    // Get the login form if it exists on the page
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        setupLoginValidation();
    }
});

// Set up validation for registration form
function setupRegistrationValidation() {
    const form = document.getElementById('registerForm');
    const fullName = document.getElementById('full_name');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    // Check passwords match while user is typing
    confirmPassword.addEventListener('input', function() {
        checkPasswordsMatch();
    });

    password.addEventListener('input', function() {
        if (confirmPassword.value !== '') {
            checkPasswordsMatch();
        }
        checkPasswordStrength();
    });

    // Validate form when user tries to submit
    form.addEventListener('submit', function(event) {
        // Clear any previous error messages
        clearErrors();

        let isValid = true;

        // Check if full name is empty
        if (fullName.value.trim() === '') {
            showError(fullName, 'Please enter your full name');
            isValid = false;
        }

        // Check if email is valid
        if (!isValidEmail(email.value)) {
            showError(email, 'Please enter a valid email address');
            isValid = false;
        }

        // Check if password is strong enough
        if (password.value.length < 6) {
            showError(password, 'Password must be at least 6 characters long');
            isValid = false;
        }

        // Check if passwords match
        if (password.value !== confirmPassword.value) {
            showError(confirmPassword, 'Passwords do not match');
            isValid = false;
        }

        // If form is not valid, stop it from submitting
        if (!isValid) {
            event.preventDefault();
        }
    });
}

// Set up validation for login form
function setupLoginValidation() {
    const form = document.getElementById('loginForm');
    const email = document.getElementById('email');
    const password = document.getElementById('password');

    // Validate form when user tries to submit
    form.addEventListener('submit', function(event) {
        // Clear any previous error messages
        clearErrors();

        let isValid = true;

        // Check if email is valid
        if (!isValidEmail(email.value)) {
            showError(email, 'Please enter a valid email address');
            isValid = false;
        }

        // Check if password is empty
        if (password.value.trim() === '') {
            showError(password, 'Please enter your password');
            isValid = false;
        }

        // If form is not valid, stop it from submitting
        if (!isValid) {
            event.preventDefault();
        }
    });
}

// Check if passwords match and show message
function checkPasswordsMatch() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    // Remove any existing match indicator
    const existingIndicator = confirmPassword.parentElement.querySelector('.password-match-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }

    // Create a new indicator
    const indicator = document.createElement('div');
    indicator.className = 'password-match-indicator';
    indicator.style.marginTop = '5px';
    indicator.style.fontSize = '14px';

    if (confirmPassword.value === '') {
        return;
    }

    if (password.value === confirmPassword.value) {
        indicator.textContent = '✓ Passwords match';
        indicator.style.color = '#2e7d32';
        confirmPassword.style.borderColor = '#66bb6a';
    } else {
        indicator.textContent = '✗ Passwords do not match';
        indicator.style.color = '#c62828';
        confirmPassword.style.borderColor = '#ef5350';
    }

    confirmPassword.parentElement.appendChild(indicator);
}

// Check password strength and show message
function checkPasswordStrength() {
    const password = document.getElementById('password');

    // Remove any existing strength indicator
    const existingIndicator = password.parentElement.querySelector('.password-strength-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }

    if (password.value === '') {
        password.style.borderColor = '#ddd';
        return;
    }

    // Create a new indicator
    const indicator = document.createElement('div');
    indicator.className = 'password-strength-indicator';
    indicator.style.marginTop = '5px';
    indicator.style.fontSize = '14px';

    const length = password.value.length;

    if (length < 6) {
        indicator.textContent = 'Weak - Use at least 6 characters';
        indicator.style.color = '#c62828';
        password.style.borderColor = '#ef5350';
    } else if (length < 10) {
        indicator.textContent = 'Good password strength';
        indicator.style.color = '#f57c00';
        password.style.borderColor = '#ffa726';
    } else {
        indicator.textContent = 'Strong password!';
        indicator.style.color = '#2e7d32';
        password.style.borderColor = '#66bb6a';
    }

    password.parentElement.appendChild(indicator);
}

// Check if email address is valid
function isValidEmail(email) {
    // Simple email check pattern
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email);
}

// Show error message under a field
function showError(field, message) {
    // Create error message element
    const error = document.createElement('div');
    error.className = 'error-message';
    error.textContent = message;
    error.style.color = '#c62828';
    error.style.fontSize = '14px';
    error.style.marginTop = '5px';

    // Change field border to red
    field.style.borderColor = '#ef5350';

    // Add error message after the field
    field.parentElement.appendChild(error);
}

// Clear all error messages
function clearErrors() {
    // Remove all error messages
    const errors = document.querySelectorAll('.error-message');
    errors.forEach(function(error) {
        error.remove();
    });

    // Reset all field borders
    const fields = document.querySelectorAll('input');
    fields.forEach(function(field) {
        field.style.borderColor = '#ddd';
    });
}
