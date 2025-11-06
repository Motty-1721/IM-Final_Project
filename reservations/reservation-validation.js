// This file handles form validation for reservation forms

// Wait for the page to fully load before running the code
document.addEventListener('DOMContentLoaded', function() {

    // Get the reservation form if it exists on the page
    const reservationForm = document.querySelector('form[action*="reservation"]');
    if (reservationForm) {
        setupReservationValidation();
    }
});

// Set up validation for reservation form
function setupReservationValidation() {
    const form = document.querySelector('form[action*="reservation"]');
    const dateField = document.getElementById('reservation_date');
    const timeField = document.getElementById('reservation_time');
    const guestsField = document.getElementById('number_of_guests');

    // Add real-time date validation
    if (dateField) {
        dateField.addEventListener('change', function() {
            validateDate();
        });
    }

    // Validate form when user tries to submit
    form.addEventListener('submit', function(event) {
        // Clear any previous error messages
        clearErrors();

        let isValid = true;

        // Check if date is selected and not in the past
        if (dateField && !validateDate()) {
            isValid = false;
        }

        // Check if time is selected
        if (timeField && timeField.value === '') {
            showError(timeField, 'Please select a reservation time');
            isValid = false;
        }

        // Check if number of guests is selected
        if (guestsField && guestsField.value === '') {
            showError(guestsField, 'Please select number of guests');
            isValid = false;
        }

        // If form is not valid, stop it from submitting
        if (!isValid) {
            event.preventDefault();
        }
    });
}

// Validate that the selected date is not in the past
function validateDate() {
    const dateField = document.getElementById('reservation_date');
    if (!dateField) return true;

    const selectedDate = new Date(dateField.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Set to start of day for comparison

    // Remove any existing date indicator
    const existingIndicator = dateField.parentElement.querySelector('.date-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }

    if (dateField.value === '') {
        showError(dateField, 'Please select a reservation date');
        return false;
    }

    if (selectedDate < today) {
        showError(dateField, 'Cannot make reservations for past dates');
        return false;
    }

    // Show success indicator for valid date
    const indicator = document.createElement('div');
    indicator.className = 'date-indicator';
    indicator.textContent = '✓ Valid date selected';
    indicator.style.color = '#2e7d32';
    indicator.style.fontSize = '14px';
    indicator.style.marginTop = '5px';
    dateField.style.borderColor = '#66bb6a';
    dateField.parentElement.appendChild(indicator);

    return true;
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

    // Remove all indicators
    const indicators = document.querySelectorAll('.date-indicator');
    indicators.forEach(function(indicator) {
        indicator.remove();
    });

    // Reset all field borders
    const fields = document.querySelectorAll('input, select, textarea');
    fields.forEach(function(field) {
        field.style.borderColor = '#ddd';
    });
}
