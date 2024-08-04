// Constants for element IDs
const ELEMENT_IDS = {
    sunIcon: 'sun-icon',
    moonIcon: 'moon-icon',
    searchForm: 'searchForm',
    categorySelect: 'category',
    tagsSelect: 'tags',
    searchInput: 'searchInput',
    searchBar: 'searchBar',
};

// Function to toggle the theme
function toggleTheme() {
    const currentTheme = document.body.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    toggleIcons(newTheme); // Update icons based on new theme
}

// Function to toggle icons based on the theme
function toggleIcons(theme) {
    const sunIcon = document.getElementById(ELEMENT_IDS.sunIcon);
    const moonIcon = document.getElementById(ELEMENT_IDS.moonIcon);
    sunIcon.style.display = theme === 'dark' ? 'none' : 'inline';
    moonIcon.style.display = theme === 'dark' ? 'inline' : 'none';
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', () => {
    // Load saved theme or default to 'dark'
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.body.setAttribute('data-theme', savedTheme);
    toggleIcons(savedTheme); // Set initial icons state
    setupHamburgerMenu(); // Initialize hamburger menu
    populateCategories(); // Populate category dropdown
    populateTags(); // Populate tags dropdown
});

// Function to set up the hamburger menu and overlay
function setupHamburgerMenu() {
    const hamMenu = document.querySelector('.ham-menu');
    const offScreenMenu = document.querySelector('.off-screen-menu');
    const overlay = document.createElement('div');
    overlay.classList.add('overlay');
    document.body.appendChild(overlay);

    if (hamMenu && offScreenMenu) {
        hamMenu.addEventListener('click', () => {
            hamMenu.classList.toggle('active');
            offScreenMenu.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            hamMenu.classList.remove('active');
            offScreenMenu.classList.remove('active');
            overlay.classList.remove('active');
        });
    }
}

// Add event listener for search form submission
const searchForm = document.getElementById('searchForm');
const searchInput = document.getElementById('searchInput');

if (searchForm) {
    searchForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const query = encodeURIComponent(searchInput.value.trim()); // Get the search query
        const selectedCategories = getSelectedOptions('category');
        const selectedTags = getSelectedOptions('tags');

        // If any tags are selected, redirect to the first selected tag's URL
        if (selectedTags.length > 0) {
            const tag = selectedTags[0]; // Use the first selected tag
            window.location.href = `/tag/${tag}/`; // Redirect to the tag URL
        } else {
            // If no tags are selected, redirect to the search results page
            const searchURL = `/?s=${query}&category=${selectedCategories}`;
            window.location.href = searchURL; // Redirect to the search results page
        }
    });
}

// Helper function to get selected options from a multi-select
function getSelectedOptions(selectId) {
    const selectElement = document.getElementById(selectId);
    return Array.from(selectElement.selectedOptions).map(option => encodeURIComponent(option.value));
}

// Function to populate the category dropdown
function populateCategories() {
    fetch('/wp-admin/admin-ajax.php?action=get_categories')
        .then(handleResponse)
        .then(data => populateSelect(data, ELEMENT_IDS.categorySelect))
        .catch(handleError('categories'));
}

// Function to populate the tags dropdown
function populateTags() {
    fetch('/wp-admin/admin-ajax.php?action=get_tags')
        .then(handleResponse)
        .then(data => populateSelect(data, ELEMENT_IDS.tagsSelect))
        .catch(handleError('tags'));
}

// Helper function to handle fetch responses
function handleResponse(response) {
    if (!response.ok) {
        throw new Error(`Network response was not ok (Status: ${response.status} - ${response.statusText})`);
    }
    return response.json();
}

// Helper function to populate select elements
function populateSelect(data, selectId) {
    const selectElement = document.getElementById(selectId);
    if (selectElement) {
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            selectElement.appendChild(option);
        });
    }
}

// Helper function to handle errors
function handleError(type) {
    return function(error) {
        console.error(`Error fetching ${type}:`, error);
    };
}

// Function to toggle the search bar
function toggleSearchBar() {
    const searchBar = document.getElementById(ELEMENT_IDS.searchBar);
    const overlay = document.querySelector('.overlay');

    searchBar.style.display = searchBar.style.display === "block" ? "none" : "block";
    overlay.classList.toggle('active');

    overlay.addEventListener('click', () => {
        searchBar.style.display = "none";
        overlay.classList.remove('active');
    });
}

// Global error handling
window.onerror = function(message, source, lineno, colno, error) {
    console.error(`Global error caught: ${message} at ${source}:${lineno}:${colno}`, error);
};

// Additional error handling for uncaught promise rejections
window.addEventListener('unhandledrejection', function(event) {
    console.error(`Unhandled rejection: ${event.reason}`);
});
