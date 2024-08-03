let unluckytechSlideIndex = 1;
unluckytechShowSlides(unluckytechSlideIndex);

// Automatically change slides every 3 seconds
let unluckytechSlideTimer = setInterval(() => unluckytechPlusSlides(1), 3000);

function unluckytechPlusSlides(n) {
    unluckytechShowSlides(unluckytechSlideIndex += n);
    unluckytechResetTimer();  // Reset timer whenever slides are manually changed
}

function unluckytechCurrentSlide(n) {
    unluckytechShowSlides(unluckytechSlideIndex = n);
    unluckytechResetTimer();  // Reset timer whenever slides are manually changed
}

function unluckytechShowSlides(n) {
    let i;
    let slides = document.getElementsByClassName("unluckytech-slide");
    let dots = document.getElementsByClassName("unluckytech-dot");
    if (n > slides.length) {unluckytechSlideIndex = 1}
    if (n < 1) {unluckytechSlideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  // Hide all slides
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[unluckytechSlideIndex-1].style.display = "block";  // Show the current slide
    dots[unluckytechSlideIndex-1].className += " active";  // Add active class to the corresponding dot
}

function unluckytechResetTimer() {
    clearInterval(unluckytechSlideTimer);
    unluckytechSlideTimer = setInterval(() => unluckytechPlusSlides(1), 3000);
}
