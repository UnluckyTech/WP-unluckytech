// ----------------------------------
// Navigation Functionality (from nav.js)
// ----------------------------------

// Constants for element IDs
const ELEMENT_IDS = {
    searchForm: 'searchForm',
    searchInput: 'searchInput',
    searchBar: 'searchBar',
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    setupHamburgerMenu(); // Initialize hamburger menu
    populateTags(); // Populate tags dropdown
    handleSubmit();
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

// Function to determine if the screen is in mobile view
function isMobile() {
    return window.innerWidth <= 768; // Define mobile as screen width 768px or below
}

let lastScrollTop = 0;
const header = document.querySelector('.navigation-wrapper');

// Handle scroll events to hide/show navigation based on scroll direction (desktop only)
window.addEventListener('scroll', () => {
    // Only apply the auto-hide functionality on desktop (not mobile)
    if (!isMobile()) {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            // Scrolling down, hide the header
            header.style.top = '-60px'; // Adjust based on header height
        } else {
            // Scrolling up, show the header
            header.style.top = '0';
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Prevent negative scrolling issues
    } else {
        // On mobile, ensure the header is always visible
        header.style.top = '0';
    }
});

// Handle window resize to ensure correct behavior when switching between desktop and mobile
window.addEventListener('resize', () => {
    if (isMobile()) {
        // Ensure the header is always visible on mobile when resizing
        header.style.top = '0';
    }
});

// ----------------------------------
// Search Functionality (from search.js)
// ----------------------------------

// Function to toggle the search bar
function toggleSearchBar() {
    const searchBar = document.getElementById(ELEMENT_IDS.searchBar);
    const overlay = document.querySelector('.overlay');

    // Toggle the search bar visibility
    if (searchBar.style.display === "block") {
        searchBar.style.display = "none";
        overlay.classList.remove('active');
    } else {
        searchBar.style.display = "block";
        overlay.classList.add('active');

        // Event listener to close the search bar when clicking outside of it
        document.addEventListener('click', function handleClickOutside(event) {
            // Check if the click is outside the search bar
            if (!searchBar.contains(event.target) && !event.target.closest('.search-icon')) {
                searchBar.style.display = "none"; // Hide the search bar
                overlay.classList.remove('active'); // Hide the overlay
                document.removeEventListener('click', handleClickOutside); // Remove the event listener after hiding
            }
        });
    }

    // Event listener to close the search bar when clicking on the overlay
    overlay.addEventListener('click', () => {
        searchBar.style.display = "none";
        overlay.classList.remove('active');
    });
}

// ----------------------------------
// Login Functionality (from login.js)
// ----------------------------------

// Toggle User Login Form
function toggleUserLogin() {
    const userLoginForm = document.getElementById('userLoginForm');
    const overlay = document.querySelector('.overlay'); // Optional: reuse the overlay

    if (userLoginForm.style.display === 'none' || userLoginForm.style.display === '') {
        userLoginForm.style.display = 'block';
        overlay.classList.add('active'); // Optional: show overlay
        document.addEventListener('click', handleClickOutsideLogin);
    } else {
        userLoginForm.style.display = 'none';
        overlay.classList.remove('active'); // Optional: hide overlay
        document.removeEventListener('click', handleClickOutsideLogin);
    }
}

// Handle click outside the user login form to close it
function handleClickOutsideLogin(event) {
    const userLoginForm = document.getElementById('userLoginForm');
    const userIcon = document.querySelector('.user-login'); // Assuming user icon has this class

    // Close the login form if the click is outside of the login form and not on the icon
    if (!userLoginForm.contains(event.target) && !userIcon.contains(event.target)) {
        userLoginForm.style.display = 'none';
        const overlay = document.querySelector('.overlay');
        overlay.classList.remove('active'); // Optional: hide overlay
        document.removeEventListener('click', handleClickOutsideLogin);
    }
}

// ----------------------------------
// Form Submission Functionality (from search.js)
// ----------------------------------

// Function to handle form submission for search
function handleSubmit() {
    const searchInput = document.getElementById('searchInput').value;
    const category = document.querySelector('input[name="category"]:checked').value;
    const tag = document.querySelector('input[name="tag"]:checked').value;

    // Construct the URL based on the search input, category, and tag
    let url = '/?s=' + encodeURIComponent(searchInput);

    if (category !== 'all') {
        url += `&category=${category}`;
    }

    if (tag !== 'all') {
        url += `&tag=${tag}`;
    }

    // Redirect to the constructed URL
    window.location.href = url;
    return false; // Prevent default form submission
}
