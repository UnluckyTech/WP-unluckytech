// Function to toggle the theme
function toggleTheme() {
    const currentTheme = document.body.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);

    // Toggle icons
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');

    if (newTheme === 'dark') {
        sunIcon.style.display = 'none';
        moonIcon.style.display = 'inline';
    } else {
        sunIcon.style.display = 'inline';
        moonIcon.style.display = 'none';
    }
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.body.setAttribute('data-theme', savedTheme);

    // Set the initial state of the icons
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');

    if (savedTheme === 'dark') {
        sunIcon.style.display = 'none';
        moonIcon.style.display = 'inline';
    } else {
        sunIcon.style.display = 'inline';
        moonIcon.style.display = 'none';
    }

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
            const searchURL = '/?s=' + query; // Build the search URL
            window.location.href = searchURL; // Redirect to the search results page
        });
    }
});

// Function to toggle the search bar
function toggleSearchBar() {
    const searchBar = document.getElementById("searchBar");
    if (searchBar.style.display === "block") {
        searchBar.style.display = "none";
    } else {
        searchBar.style.display = "block";
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Function to populate the category dropdown
    function populateCategories() {
        fetch('/wp-admin/admin-ajax.php?action=get_categories')
            .then(response => response.json())
            .then(data => {
                const categorySelect = document.getElementById('category');
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
            });
    }

    // Function to populate the tags dropdown
    function populateTags() {
        fetch('/wp-admin/admin-ajax.php?action=get_tags')
            .then(response => response.json())
            .then(data => {
                const tagsSelect = document.getElementById('tags');
                data.forEach(tag => {
                    const option = document.createElement('option');
                    option.value = tag.id;
                    option.textContent = tag.name;
                    tagsSelect.appendChild(option);
                });
            });
    }

    // Populate dropdowns on page load
    populateCategories();
    populateTags();
});

