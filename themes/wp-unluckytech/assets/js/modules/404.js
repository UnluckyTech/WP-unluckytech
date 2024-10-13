document.addEventListener('DOMContentLoaded', function () {
    let slides = document.querySelectorAll('.mobile-view .slide');
    let dots = document.querySelectorAll('.dots-container .dot');
    let currentSlideIndex = 0;

    // Function to show the current slide
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            dots[i].classList.remove('active');
        });
        slides[index].classList.add('active');
        dots[index].classList.add('active');
    }

    // Initialize the first slide as active
    showSlide(currentSlideIndex);

    // Add click event listeners for dots (if you have them)
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlideIndex = index;
            showSlide(currentSlideIndex);
        });
    });
});