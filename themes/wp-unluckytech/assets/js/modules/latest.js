let slideIndex = 1;
showSlides(slideIndex);

// Function to set the current slide
function unluckytechCurrentSlide(n) {
    showSlides(slideIndex = n);
}

// Function to show slides
function showSlides(n) {
    let slides = document.getElementsByClassName("unluckytech-slide");
    let dots = document.getElementsByClassName("unluckytech-dot");

    if (n > slides.length) { slideIndex = 1; }
    if (n < 1) { slideIndex = slides.length; }

    // Hide all slides
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    // Remove "active" class from all dots
    for (let i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }

    // Show the current slide and set the corresponding dot to active
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}
