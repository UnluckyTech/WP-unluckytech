document.addEventListener("DOMContentLoaded", function() {
    // Get all dropdown headers
    const dropdownHeaders = document.querySelectorAll('.dropdown-header');

    // Add click event to each dropdown header
    dropdownHeaders.forEach(header => {
        header.addEventListener('click', function() {
            this.classList.toggle('active');
            const content = this.nextElementSibling;

            // Toggle visibility of dropdown content
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
        });
    });
});