let slideIndex = 0;
let slideTimer;

// Initialize the slideshow
showSlides(); 

// Automatically change slides every 3 seconds
slideTimer = setInterval(() => plusSlides(1), 3000);

function plusSlides(n) {
    // Increment or decrement the slide index and show the appropriate slide
    slideIndex += n;
    showSlides();
    resetTimer();  // Reset timer whenever slides are manually changed
}

function showSlides() {
    let slides = document.getElementsByClassName("mySlides");
    
    // If slideIndex is greater than the total number of slides, reset it
    if (slideIndex >= slides.length) { slideIndex = 0; }
    if (slideIndex < 0) { slideIndex = slides.length - 1; }
    
    // Hide all slides
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
    }

    // Show the current slide
    slides[slideIndex].style.display = "block"; 
}

function resetTimer() {
    // Clear the current interval and reset it for auto sliding
    clearInterval(slideTimer);
    slideTimer = setInterval(() => plusSlides(1), 3000);
}
