let slideIndex = 1;
showSlides(slideIndex);

// Automatically change slides every 3 seconds
let slideTimer = setInterval(() => plusSlides(1), 3000);

function plusSlides(n) {
    showSlides(slideIndex += n);
    resetTimer();  // Reset timer whenever slides are manually changed
}

function currentSlide(n) {
    showSlides(slideIndex = n);
    resetTimer();  // Reset timer whenever slides are manually changed
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("slide");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  // Hide all slides
    }
    slides[slideIndex-1].style.display = "block";  // Show the current slide
}

function resetTimer() {
    clearInterval(slideTimer);
    slideTimer = setInterval(() => plusSlides(1), 3000);
}
