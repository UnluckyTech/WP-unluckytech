<?php
/**
 * Title: System Configuration
 * Slug: unluckytech/sysconfig
 * Categories: text
 * Description: Services page for System Configuration offered by UnluckyTech.
 */
?>

<div class="services-container">

    <!-- Services Banner -->
    <div class="services-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="services-banner-overlay">
            <h1 class="services-title">System Configuration</h1>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-container">
            <div class="summary-left">
                <h2 class="summary-title">Tailored System Configuration Solutions</h2>
            </div>
            <div class="summary-right">
                <p class="summary-description">
                    At UnluckyTech, we provide end-to-end System Configuration services for businesses of all sizes. Our services include server management, network configuration, automation, and more, ensuring optimal performance and reliability.
                </p>
            </div>
        </div>
    </div>

    <!-- Service Offerings Section -->
    <div class="offerings-section">
        <h2 class="offerings-heading">Our System Configuration Services</h2>
        <div class="offerings-grid">
            <div class="offering-item">
                <i class="fas fa-server offering-icon"></i>
                <h3>Server Setup & Configuration</h3>
                <p>We specialize in setting up and configuring Linux/Windows servers tailored to your business needs.</p>
            </div>
            <div class="offering-item">
                <i class="fas fa-network-wired offering-icon"></i>
                <h3>Network Configuration</h3>
                <p>From routing to switching, we ensure that your network infrastructure is configured for performance and security.</p>
            </div>
            <div class="offering-item">
                <i class="fas fa-cogs offering-icon"></i>
                <h3>Automation & Scripting</h3>
                <p>Streamline your processes with automated scripts, reducing manual work and human error.</p>
            </div>
            <div class="offering-item">
                <i class="fas fa-cloud offering-icon"></i>
                <h3>Cloud Infrastructure Setup</h3>
                <p>We configure cloud environments, ensuring scalability and high availability for your applications.</p>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="why-choose-us-section">
        <h2 class="why-choose-us-heading">Why Choose Us for System Configuration?</h2>
        <div class="why-choose-us-container">
            <div class="why-item">
                <i class="fas fa-check-circle why-icon"></i>
                <h3>Experienced Professionals</h3>
                <p>Our team consists of certified experts with years of experience in configuring diverse systems.</p>
            </div>
            <div class="why-item">
                <i class="fas fa-shield-alt why-icon"></i>
                <h3>Security Focused</h3>
                <p>We prioritize security in all configurations, ensuring your data is protected from unauthorized access.</p>
            </div>
            <div class="why-item">
                <i class="fas fa-rocket why-icon"></i>
                <h3>Efficiency & Performance</h3>
                <p>Our configurations are optimized for speed and performance, helping your business run smoothly.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="cta-section">
        <h2 class="cta-heading">Ready to Optimize Your System?</h2>
        <p class="cta-description">Get in touch with us for a consultation and let's make sure your systems are running at peak performance.</p>
        <a href="/contact" class="cta-button">Contact Us</a>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section-sysconfig">
        <h2 class="faq-heading">Frequently Asked Questions</h2>
        <div class="faq-item">
            <h3 class="sysconfig-question">How long does system configuration take?</h3>
            <p class="faq-answer">The time depends on the complexity of the setup, but most configurations are completed within a few days.</p>
        </div>
        <div class="faq-item">
            <h3 class="sysconfig-question">Do you offer support after the configuration?</h3>
            <p class="faq-answer">Yes, we offer ongoing support and maintenance to ensure your systems continue to perform optimally.</p>
        </div>
    </div>

</div>

<script>
    document.querySelectorAll('.sysconfig-question').forEach(item => {
        item.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            answer.style.display = (answer.style.display === 'block') ? 'none' : 'block';
        });
    });
</script>
