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
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].className = slides[i].className.replace(" active", "");
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].className += " active";
    dots[slideIndex-1].className += " active";
}

function resetTimer() {
    clearInterval(slideTimer);
    slideTimer = setInterval(() => plusSlides(1), 3000);
}
