document.addEventListener('DOMContentLoaded', function() {
    let slideIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    function showSlides() {
        slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === slideIndex);
            dots[index].classList.toggle('active', index === slideIndex);
        });
    }

    function nextSlide() {
        slideIndex = (slideIndex + 1) % slides.length;
        showSlides();
    }

    function currentSlide(index) {
        slideIndex = index;
        showSlides();
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => currentSlide(index));
    });

    // Show the first slide initially
    showSlides();

    // Automatically change slides every 3 seconds
    setInterval(nextSlide, 3000);
});
