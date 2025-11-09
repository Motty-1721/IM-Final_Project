// This file handles search and filter functionality for reservations

// Wait for the page to fully load
document.addEventListener('DOMContentLoaded', function() {

    // Get the search input box
    const searchInput = document.getElementById('searchInput');

    // Get all filter buttons
    const filterButtons = document.querySelectorAll('.filter-btn');

    // Get the table body with all reservation rows
    const tableBody = document.getElementById('reservationsBody');

    // Store the current filter status
    let currentFilter = 'all';

    // Add search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterReservations();
        });
    }

    // Add click handlers to filter buttons
    filterButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });

            // Add active class to clicked button
            this.classList.add('active');

            // Get the filter value from the button
            currentFilter = this.getAttribute('data-filter');

            // Filter the reservations
            filterReservations();
        });
    });

    // Main function to filter reservations
    function filterReservations() {
        // Get search text and make it lowercase for easy comparison
        const searchText = searchInput ? searchInput.value.toLowerCase() : '';

        // Get all reservation rows
        const rows = tableBody.getElementsByTagName('tr');

        // Count how many rows are visible
        let visibleCount = 0;

        // Loop through each reservation row
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];

            // Get the status of this reservation
            const status = row.getAttribute('data-status');

            // Get all the text content in this row
            const rowText = row.textContent.toLowerCase();

            // Check if row matches the status filter
            const matchesFilter = currentFilter === 'all' || status === currentFilter;

            // Check if row matches the search text
            const matchesSearch = searchText === '' || rowText.includes(searchText);

            // Show row only if it matches both filter and search
            if (matchesFilter && matchesSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }

        // Show message if no results found
        showNoResultsMessage(visibleCount);
    }

    // Function to show message when no results are found
    function showNoResultsMessage(visibleCount) {
        // Remove any existing no results message
        const existingMessage = document.querySelector('.no-results-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // If no rows are visible, show a message
        if (visibleCount === 0) {
            const message = document.createElement('div');
            message.className = 'no-results-message';
            message.innerHTML = '<p>No reservations found matching your search.</p>';
            message.style.textAlign = 'center';
            message.style.padding = '30px';
            message.style.color = '#999';

            // Insert message after the table
            const table = document.getElementById('reservationsTable');
            table.parentNode.insertBefore(message, table.nextSibling);
        }
    }
});
