// Function to toggle the theme
function toggleTheme() {
    const currentTheme = document.body.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme');
    document.body.setAttribute('data-theme', savedTheme || 'dark');

    // Add event listener for hamburger menu click
    const hamMenu = document.querySelector(".ham-menu");
    const offScreenMenu = document.querySelector(".off-screen-menu");

    if (hamMenu && offScreenMenu) {
        hamMenu.addEventListener("click", () => {
            hamMenu.classList.toggle("active");
            offScreenMenu.classList.toggle("active");
        });
    }
});

// Save theme preference to localStorage
document.body.addEventListener('change', () => {
    localStorage.setItem('theme', document.body.getAttribute('data-theme'));
});
