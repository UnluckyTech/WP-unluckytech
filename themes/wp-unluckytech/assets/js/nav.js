document.addEventListener('DOMContentLoaded', () => {
    const menuIcon = document.getElementById('menuIcon');
    const navMenu = document.getElementById('navMenu');

    menuIcon.addEventListener('click', (event) => {
        event.preventDefault();
        navMenu.classList.toggle('open');
    });
});

function toggleTheme() {
    document.body.classList.toggle('dark-theme');
    let icon = document.querySelector('.theme-toggle i');
    if (document.body.classList.contains('dark-theme')) {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    } else {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    }
}

// Apply dark theme by default
document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('dark-theme');
    let icon = document.querySelector('.theme-toggle i');
    icon.classList.remove('fa-sun');
    icon.classList.add('fa-moon');
});
