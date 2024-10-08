<?php
/**
 * Title: Web Development
 * Slug: unluckytech/webdev
 * Categories: text
 * Description: Detailed page for the Web Development service offered by UnluckyTech.
 */
?>

<div class="services-container" style="font-family: 'Roboto', sans-serif;">

    <!-- Services Banner -->
    <div class="services-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="services-banner-overlay">
            <h1 class="services-title animate-fade-in">Web Development</h1>
        </div>
    </div>

    <!-- Service Overview Section -->
    <div class="service-overview">
        <h2 class="service-heading animate-slide-in">Bringing Your Ideas to Life Online</h2>
        <p class="service-description animate-fade-in">
            At UnluckyTech, we specialize in creating robust, scalable, and visually stunning websites tailored to meet your business goals. Our web development team is dedicated to delivering high-quality websites that are responsive, SEO-friendly, and easy to manage.
        </p>
    </div>

    <!-- Core Features Section -->
    <div class="features-section">
        <h2 class="features-heading animate-slide-in">Our Web Development Features</h2>
        <div class="features-list">
            <div class="feature-item animate-fade-in">
                <i class="fas fa-mobile-alt feature-icon"></i>
                <h3 class="feature-title">Responsive Design</h3>
                <p class="feature-description">Ensuring your website looks perfect on any device—desktop, tablet, or mobile.</p>
            </div>
            <div class="feature-item animate-fade-in">
                <i class="fas fa-search feature-icon"></i>
                <h3 class="feature-title">SEO Optimized</h3>
                <p class="feature-description">Our websites are built with SEO best practices in mind, helping your business rank higher in search engine results.</p>
            </div>
            <div class="feature-item animate-fade-in">
                <i class="fas fa-code feature-icon"></i>
                <h3 class="feature-title">Custom Development</h3>
                <p class="feature-description">Tailor-made web solutions that cater to your unique business requirements and objectives.</p>
            </div>
            <div class="feature-item animate-fade-in">
                <i class="fas fa-shopping-cart feature-icon"></i>
                <h3 class="feature-title">E-commerce Solutions</h3>
                <p class="feature-description">We provide secure and scalable e-commerce platforms, allowing you to sell your products or services online.</p>
            </div>
        </div>
    </div>

    <!-- Technology Stack Section (3 Cards in 1 Row) -->
    <div class="tech-stack-section">
        <h2 class="tech-stack-heading animate-slide-in">Technology Stack We Use</h2>
        <div class="tech-stack-grid">
            <div class="tech-card">
                <i class="fas fa-desktop tech-icon"></i>
                <h3>Frontend Technologies</h3>
                <p>HTML5, CSS3, JavaScript (React, Vue.js)</p>
            </div>
            <div class="tech-card">
                <i class="fas fa-server tech-icon"></i>
                <h3>Backend Technologies</h3>
                <p>PHP, Python, Node.js</p>
            </div>
            <div class="tech-card">
                <i class="fas fa-database tech-icon"></i>
                <h3>Platforms & Databases</h3>
                <p>WordPress, Shopify, MySQL, MongoDB</p>
            </div>
        </div>
    </div>

    <!-- Call to Action Section (Centered) -->
    <div class="cta-section">
        <h2 class="cta-heading animate-slide-in">Ready to Start Your Project?</h2>
        <p class="cta-description animate-fade-in">Get in touch with our team today and let’s build something amazing together.</p>
        <a href="/contact" class="cta-button animate-fade-in">Request a Quote</a>
    </div>

    <!-- FAQ Section with JavaScript Toggle -->
    <div class="faq-section-webdev">
        <h2 class="faq-heading animate-slide-in">Frequently Asked Questions</h2>
        <div class="faq-item">
            <h3 class="web-question">How long does it take to build a website?</h3>
            <p class="faq-answer">Depending on the complexity, most websites take between 4 to 12 weeks to complete from the design phase to deployment.</p>
        </div>
        <div class="faq-item">
            <h3 class="web-question">Do you offer website maintenance?</h3>
            <p class="faq-answer">Yes, we provide ongoing maintenance and support to ensure your website remains updated and secure.</p>
        </div>
    </div>

</div>

<script>
    document.querySelectorAll('.web-question').forEach(item => {
        item.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            answer.style.display = (answer.style.display === 'block') ? 'none' : 'block';
        });
    });
</script>
