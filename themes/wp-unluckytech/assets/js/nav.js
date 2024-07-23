// Function to toggle the theme
function toggleTheme() {
  // Check current theme
  const currentTheme = document.body.getAttribute('data-theme');

  // Toggle theme between dark and light
  if (currentTheme === 'dark') {
      document.body.setAttribute('data-theme', 'light');
  } else {
      document.body.setAttribute('data-theme', 'dark');
  }
}

// Initialize theme on page load based on saved preference or default to dark mode
document.addEventListener('DOMContentLoaded', () => {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
      document.body.setAttribute('data-theme', savedTheme);
  } else {
      document.body.setAttribute('data-theme', 'dark'); // Default to dark mode
  }

  // Add event listener for menu icon click
  const menuIcon = document.getElementById('menuIcon');
  const navMenu = document.getElementById('navMenu');

  if (menuIcon && navMenu) {
      menuIcon.addEventListener('click', () => {
          if (navMenu.classList.contains('open')) {
              navMenu.classList.remove('open');
          } else {
              navMenu.classList.add('open');
          }
      });
  }
});

// Save theme preference to localStorage
document.body.addEventListener('change', () => {
  localStorage.setItem('theme', document.body.getAttribute('data-theme'));
});
