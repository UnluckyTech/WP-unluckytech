<?php
/**
 * Title: About
 * Slug: unluckytech/about
 * Categories: text, featured
 * Description: This would be used for the about page of the website.
 */
?>

<div class="about-container">
    
    <!-- About Me Banner -->
    <div class="about-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="about-banner-overlay">
            <h1 class="about-title">About Me</h1>
        </div>
    </div> 

    <div class="about-content">

        <!-- Container for About Text and Profile Card -->
        <div class="about-section">
            <div class="about-text">
                <!-- Who Am I Section - Collapsible on mobile -->
                <h3 class="about-question">Who Am I?</h3>
                <div class="about-answer">
                    <p>Hello! I'm Scott, a dedicated IT professional with a passion for solving technical challenges. My curiosity has led me to new projects focused on improving and refining existing concepts. With years of experience in areas like systems administration, web development, and IT infrastructure, I am committed to staying ahead by continuously learning and adapting to new technological trends.</p>
                </div>

                <!-- Why UnluckyTech Section - Collapsible on mobile -->
                <h3 class="about-question">Why UnluckyTech?</h3>
                <div class="about-answer">
                    <p>We all have those moments where things don't go as planned. Instead of seeing these moments as just misfortune, we view them as opportunities for growth. The goal with “UnluckyTech” is to help you navigate and learn from these challenges, so you’re better prepared for the next time luck isn’t on your side.</p>
                </div>
            </div>
        
            <!-- Mini Profile Card -->
            <div class="profile-card">
                <img src="/wp-content/themes/wp-unluckytech/assets/images/termicon.png" alt="Profile Image" class="profile-image">
                <h3>Scott Tawse</h3>
                <p>IT Professional | Web Developer | Tech Enthusiast</p>
                <div class="social-icons">
                    <a href="https://github.com/unluckytech" target="_blank" class="social-icon">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="https://linkedin.com/in/stawse" target="_blank" class="social-icon">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="https://twitter.com/yourusername" target="_blank" class="social-icon">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
                <a href="/contact" class="button">Contact Me</a>
            </div>
        </div>

        <!-- New Container for Certifications, Experience, and Education Cards -->
        <div class="info-cards">
            <a href="/certifications" class="info-card">
                <h3>Certifications</h3>
                <ul>
                    <li>CompTIA A+</li>
                    <li>CompTIA Network+</li>
                    <li>CompTIA Linux+</li>
                    <li>Microsoft Office Specialist</li>
                </ul>
            </a>
            <a href="experience" class="info-card">
                <h3>Experience</h3>
                <p>Over a decade of hands-on experience in IT infrastructure, system administration, web development, and technical support across multiple industries.</p>
            </a>
            <a href="education" class="info-card">
                <h3>Education</h3>
                <p>A.A. Information Technology Management and Cybersecurity</p>
            </a>
        </div>

        <!-- FAQ Section -->
        <div class="faq-section">
            <h3>FAQ</h3>
            <div class="faq-item">
                <button class="faq-question">What services do you offer?</button>
                <div class="faq-answer">
                    <p>I offer a wide range of IT services, including web development, system configuration, network setup, server management, and technical consultation.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">How long have you been in the IT industry?</button>
                <div class="faq-answer">
                    <p>I have over 15 years of experience in the IT industry, working across various technical domains and tackling numerous challenges.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">What platforms do you specialize in?</button>
                <div class="faq-answer">
                    <p>I specialize in WordPress, Drupal, Linux, and Windows systems, with a strong focus on custom development and advanced integrations.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">How can I get in touch with you?</button>
                <div class="faq-answer">
                    <p>You can contact me via the 'Contact Me' button on this page, or reach out through my social media profiles on GitHub, LinkedIn, or Twitter.</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript to handle dropdowns for about-text section and FAQ separately -->
<script>
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


</script>
