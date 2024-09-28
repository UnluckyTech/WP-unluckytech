// Constants for element IDs
const ELEMENT_IDS = {
    searchForm: 'searchForm',
    searchInput: 'searchInput',
    searchBar: 'searchBar',
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
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
