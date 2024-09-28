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

