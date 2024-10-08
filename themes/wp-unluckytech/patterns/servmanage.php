<?php
/**
 * Title: Server Management
 * Slug: unluckytech/servmanage
 * Categories: text
 * Description: Services page for Server Management offered by UnluckyTech.
 */
?>

<div class="services-container">

    <!-- Services Banner -->
    <div class="services-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="services-banner-overlay">
            <h1 class="services-title">Server Management</h1>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-container">
            <div class="summary-left">
                <h2 class="summary-title">Comprehensive Server Management Services</h2>
            </div>
            <div class="summary-right">
                <p class="summary-description">
                    At UnluckyTech, we offer end-to-end server management solutions, from initial setup to ongoing monitoring and optimization. Our team of experts ensures that your servers run efficiently and securely, allowing you to focus on your core business operations.
                </p>
            </div>
        </div>
    </div>

    <!-- Key Offerings Section -->
    <div class="offerings-section">
        <h2 class="offerings-heading">Our Server Management Services</h2>
        <div class="offerings-grid">
            <div class="offering-item">
                <i class="fas fa-tachometer-alt offering-icon"></i>
                <h3>24/7 Monitoring</h3>
                <p>Our team monitors your servers around the clock to ensure uptime, availability, and early issue detection.</p>
            </div>
            <div class="offering-item">
                <i class="fas fa-shield-alt offering-icon"></i>
                <h3>Security Updates</h3>
                <p>We implement timely security patches and updates to protect your infrastructure from vulnerabilities.</p>
            </div>
            <div class="offering-item">
                <i class="fas fa-database offering-icon"></i>
                <h3>Backup & Recovery</h3>
                <p>Regular backups and disaster recovery solutions to safeguard your data and ensure business continuity.</p>
            </div>
            <div class="offering-item">
                <i class="fas fa-balance-scale offering-icon"></i>
                <h3>Load Balancing</h3>
                <p>Optimize your server load with advanced load balancing techniques to handle traffic efficiently.</p>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="why-choose-us-section">
        <h2 class="why-choose-us-heading">Why Choose Us for Server Management?</h2>
        <div class="why-choose-us-container">
            <div class="why-item">
                <i class="fas fa-user-tie why-icon"></i>
                <h3>Certified Experts</h3>
                <p>Our team consists of certified server administrators with extensive experience managing complex infrastructures.</p>
            </div>
            <div class="why-item">
                <i class="fas fa-clock why-icon"></i>
                <h3>24/7 Availability</h3>
                <p>We provide round-the-clock support to ensure that your server issues are resolved quickly.</p>
            </div>
            <div class="why-item">
                <i class="fas fa-cogs why-icon"></i>
                <h3>Proactive Maintenance</h3>
                <p>We continuously optimize your servers to improve performance, security, and reliability.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="cta-section">
        <h2 class="cta-heading">Need Professional Server Management?</h2>
        <p class="cta-description">Let us handle your server needs so you can focus on your business. Contact us today for a consultation.</p>
        <a href="/contact" class="cta-button">Contact Us</a>
    </div>

</div>

<script>
    // Optional: Add interaction if needed
    document.querySelectorAll('.sysmanage-question').forEach(item => {
        item.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            answer.style.display = (answer.style.display === 'block') ? 'none' : 'block';
        });
    });
</script>
