// Function to toggle the theme
function toggleTheme() {
    const currentTheme = document.body.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);

    // Toggle icons
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');

    sunIcon.style.display = newTheme === 'dark' ? 'none' : 'inline';
    moonIcon.style.display = newTheme === 'dark' ? 'inline' : 'none';
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.body.setAttribute('data-theme', savedTheme);

    // Set the initial state of the icons
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');

    sunIcon.style.display = savedTheme === 'dark' ? 'none' : 'inline';
    moonIcon.style.display = savedTheme === 'dark' ? 'inline' : 'none';

    // Add event listener for hamburger menu click
    const hamMenu = document.querySelector(".ham-menu");
    const offScreenMenu = document.querySelector(".off-screen-menu");

    if (hamMenu && offScreenMenu) {
        hamMenu.addEventListener("click", () => {
            hamMenu.classList.toggle("active");
            offScreenMenu.classList.toggle("active");
        });
    }

    // Add event listener for search form submission
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');

    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            const query = encodeURIComponent(searchInput.value.trim()); // Get the search query and encode it
            const searchURL = `/?s=${query}`; // Build the search URL
            window.location.href = searchURL; // Redirect to the search results page
        });
    }

    // Toggle advanced options
    const toggleAdvancedButton = document.getElementById('toggleAdvanced');
    const advancedOptions = document.getElementById('advancedOptions');

    if (toggleAdvancedButton && advancedOptions) {
        toggleAdvancedButton.addEventListener('click', () => {
            const isVisible = advancedOptions.style.display === 'block';
            advancedOptions.style.display = isVisible ? 'none' : 'block';
            toggleAdvancedButton.textContent = isVisible ? 'Advanced Options' : 'Hide Advanced Options';
        });
    }

    // Function to populate the category dropdown
    function populateCategories() {
        fetch('/wp-admin/admin-ajax.php?action=get_categories')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response was not ok (Status: ${response.status} - ${response.statusText})`);
                }
                return response.json();
            })
            .then(data => {
                const categorySelect = document.getElementById('category'); // Ensure this ID matches your HTML
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching categories:', error);
            });
    }

    // Function to populate the tags dropdown
    function populateTags() {
        fetch('/wp-admin/admin-ajax.php?action=get_tags')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response was not ok (Status: ${response.status} - ${response.statusText})`);
                }
                return response.json();
            })
            .then(data => {
                const tagsSelect = document.getElementById('tags'); // Ensure this ID matches your HTML
                data.forEach(tag => {
                    const option = document.createElement('option');
                    option.value = tag.id;
                    option.textContent = tag.name;
                    tagsSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching tags:', error);
            });
    }

    // Populate dropdowns on page load
    populateCategories();
    populateTags();
});

// Function to toggle the search bar
function toggleSearchBar() {
    const searchBar = document.getElementById("searchBar");
    searchBar.style.display = searchBar.style.display === "block" ? "none" : "block";
}

// Global error handling
window.onerror = function(message, source, lineno, colno, error) {
    console.error(`Global error caught: ${message} at ${source}:${lineno}:${colno}`, error);
};

// Additional error handling for uncaught promise rejections
window.addEventListener('unhandledrejection', function(event) {
    console.error(`Unhandled rejection: ${event.reason}`);
});
