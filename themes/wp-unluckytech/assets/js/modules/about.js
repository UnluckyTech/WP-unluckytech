    document.addEventListener("DOMContentLoaded", function() {
        // About section dropdown functionality (independent of FAQ)
        const aboutQuestions = document.querySelectorAll('.about-question');

        aboutQuestions.forEach(question => {
            question.addEventListener('click', function() {
                this.classList.toggle('active');
                const answer = this.nextElementSibling;
                if (answer.style.display === "block") {
                    answer.style.display = "none";
                } else {
                    answer.style.display = "block";
                }
            });
        });

        // FAQ section dropdown functionality (independent of About)
        const faqQuestions = document.querySelectorAll('.faq-question');

        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                this.classList.toggle('active');
                const answer = this.nextElementSibling;
                if (answer.style.display === "block") {
                    answer.style.display = "none";
                } else {
                    answer.style.display = "block";
                }
            });
        });
    });
document.addEventListener("DOMContentLoaded", function() {
    const infoCardsContainer = document.querySelector('.info-cards');
    const infoCards = document.querySelectorAll('.info-card');
    let startX, scrollLeft, isDown = false;

    // Mouse/Touch Down
    infoCardsContainer.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - infoCardsContainer.offsetLeft;
        scrollLeft = infoCardsContainer.scrollLeft;
    });

    infoCardsContainer.addEventListener('touchstart', (e) => {
        isDown = true;
        startX = e.touches[0].pageX - infoCardsContainer.offsetLeft;
        scrollLeft = infoCardsContainer.scrollLeft;
    });

    // Mouse/Touch Up
    infoCardsContainer.addEventListener('mouseup', () => {
        isDown = false;
    });

    infoCardsContainer.addEventListener('touchend', () => {
        isDown = false;
    });

    // Mouse/Touch Leave
    infoCardsContainer.addEventListener('mouseleave', () => {
        isDown = false;
    });

    // Mouse/Touch Move
    infoCardsContainer.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - infoCardsContainer.offsetLeft;
        const walk = (x - startX) * 3; //scroll-fast
        infoCardsContainer.scrollLeft = scrollLeft - walk;
    });

    infoCardsContainer.addEventListener('touchmove', (e) => {
        if (!isDown) return;
        const x = e.touches[0].pageX - infoCardsContainer.offsetLeft;
        const walk = (x - startX) * 3; //scroll-fast
        infoCardsContainer.scrollLeft = scrollLeft - walk;
    });
});
