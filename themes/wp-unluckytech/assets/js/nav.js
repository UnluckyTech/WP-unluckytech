// Constants for element IDs
const ELEMENT_IDS = {
    sunIcon: 'sun-icon',
    moonIcon: 'moon-icon',
    searchForm: 'searchForm',
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

// Global error handling
window.onerror = function(message, source, lineno, colno, error) {
    console.error(`Global error caught: ${message} at ${source}:${lineno}:${colno}`, error);
};

// Additional error handling for uncaught promise rejections
window.addEventListener('unhandledrejection', function(event) {
    console.error(`Unhandled rejection: ${event.reason}`);
});

let lastScrollTop = 0;
const header = document.querySelector('.navigation-wrapper');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    if (currentScroll > lastScrollTop) {
        // Scrolling down
        header.style.top = '-60px'; // Hide the header (adjust the value based on your header height)
    } else {
        // Scrolling up
        header.style.top = '0'; // Show the header
    }

    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // For Mobile or negative scrolling
});