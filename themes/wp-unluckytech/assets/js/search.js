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
