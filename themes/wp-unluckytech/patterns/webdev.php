<?php
/**
 * Title: Web Development
 * Slug: unluckytech/webdev
 * Categories: text
 * Description: Detailed page for the Web Development service offered by UnluckyTech.
 */
?>

<div class="services-container">

    <!-- Services Banner -->
    <div class="services-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="services-banner-overlay">
            <h1 class="services-title">Web Development</h1>
        </div>
    </div>

    <!-- Service Overview Section -->
    <div class="summary-section">
        <div class="summary-container">
            <!-- Left Container: Title -->
            <div class="summary-left">
                <h2 class="summary-title">Bringing Your Ideas to Life Online</h2>
            </div>

            <!-- Right Container: Description -->
            <div class="summary-right">
                <p class="summary-description">
                    At UnluckyTech, we specialize in creating robust, scalable, and visually stunning websites tailored to meet your business goals. Our web development team is dedicated to delivering high-quality websites that are responsive, SEO-friendly, and easy to manage.
                </p>
            </div>
        </div>
    </div>

    <!-- Core Features Section -->
    <div class="features-section">
        <h2 class="summary-title">Our Web Development Features</h2>
        <div class="service-cards">
            <div class="service-card">
                <i class="service-icon fas fa-mobile-alt"></i>
                <h3 class="service-title">Responsive Design</h3>
                <p class="service-description">Ensuring your website looks perfect on any device—desktop, tablet, or mobile.</p>
            </div>
            <div class="service-card">
                <i class="service-icon fas fa-search"></i>
                <h3 class="service-title">SEO Optimized</h3>
                <p class="service-description">Our websites are built with SEO best practices in mind, helping your business rank higher in search engine results.</p>
            </div>
            <div class="service-card">
                <i class="service-icon fas fa-code"></i>
                <h3 class="service-title">Custom Development</h3>
                <p class="service-description">Tailor-made web solutions that cater to your unique business requirements and objectives.</p>
            </div>
            <div class="service-card">
                <i class="service-icon fas fa-shopping-cart"></i>
                <h3 class="service-title">E-commerce Solutions</h3>
                <p class="service-description">We provide secure and scalable e-commerce platforms, allowing you to sell your products or services online.</p>
            </div>
        </div>
    </div>

    <!-- Technology Stack Section (2 Cards per Row) -->
    <div class="tech-stack-section">
        <h2 class="summary-title">Technology Stack We Use</h2>
        <div class="service-cards">
            <div class="service-card">
                <i class="service-icon fas fa-desktop"></i>
                <h3 class="service-title">Frontend Technologies</h3>
                <p class="service-description">HTML5, CSS3, JavaScript (React, Vue.js)</p>
            </div>
            <div class="service-card">
                <i class="service-icon fas fa-server"></i>
                <h3 class="service-title">Backend Technologies</h3>
                <p class="service-description">PHP, Python, Node.js</p>
            </div>
            <div class="service-card">
                <i class="service-icon fas fa-database"></i>
                <h3 class="service-title">Platforms & Databases</h3>
                <p class="service-description">WordPress, Shopify, MySQL, MongoDB</p>
            </div>
        </div>
    </div>

    <!-- webfaq Section with JavaScript Toggle -->
    <div class="faq-container">
        <div class="faq-section">
            <h2 class="summary-title">Frequently Asked Questions</h2>
            <div class="faq-item">
                <button class="faq-question">How long does it take to build a website?</button>
                <div class="faq-answer" style="display: none;">
                    <p>Depending on the complexity, most websites take between 4 to 12 weeks to complete from the design phase to deployment.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Do you offer website maintenance?</button>
                <div class="faq-answer" style="display: none;">
                    <p>Yes, we provide ongoing maintenance and support to ensure your website remains updated and secure.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Section (Centered) -->
    <div class="cta-section">
        <h2 class="summary-title">Ready to Start Your Project?</h2>
        <p class="cta-description">Get in touch with our team today and let’s build something amazing together.</p>
        <a href="/contact" class="button">Request a Quote</a>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questions = document.querySelectorAll('.faq-question');
        
        questions.forEach(question => {
            question.addEventListener('click', function() {
                const answer = this.nextElementSibling;

                // Toggle the display of the answer
                if (answer.style.display === 'none' || answer.style.display === '') {
                    answer.style.display = 'block';
                } else {
                    answer.style.display = 'none';
                }
            });
        });
    });
</script>
