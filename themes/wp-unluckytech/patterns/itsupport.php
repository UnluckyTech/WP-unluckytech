<?php
/**
 * Title: IT Support
 * Slug: unluckytech/itsupport
 * Categories: text
 * Description: Services page for IT Support offered by UnluckyTech.
 */
?>

<div class="services-container">

    <!-- Services Banner -->
    <div class="services-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="services-banner-overlay">
            <h1 class="services-title">IT Support</h1>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-container">
            <div class="summary-left">
                <h2 class="summary-title">Comprehensive IT Support Services</h2>
            </div>
            <div class="summary-right">
                <p class="summary-description">
                    Our IT support services cover everything from troubleshooting hardware and software issues to system monitoring and upgrades. Whether you need on-site support or remote assistance, we have the tools and expertise to help.
                </p>
            </div>
        </div>
    </div>

    <!-- Types of IT Support Section -->
    <div class="it-support-section">
        <h2 class="it-support-heading">Types of IT Support We Offer</h2>
        <div class="it-support-grid">
            <div class="it-support-item">
                <i class="fas fa-headset it-support-icon"></i>
                <h3>Remote Support</h3>
                <p>Get help from our expert team remotely for software issues, network problems, and more.</p>
            </div>
            <div class="it-support-item">
                <i class="fas fa-tools it-support-icon"></i>
                <h3>On-Site Support</h3>
                <p>We offer on-site support for more complex issues that require physical access to your systems.</p>
            </div>
            <div class="it-support-item">
                <i class="fas fa-desktop it-support-icon"></i>
                <h3>Hardware Troubleshooting</h3>
                <p>From faulty hardware to device repairs, we handle all your technical issues with care.</p>
            </div>
            <div class="it-support-item">
                <i class="fas fa-shield-alt it-support-icon"></i>
                <h3>System Monitoring</h3>
                <p>We offer proactive monitoring to detect and resolve issues before they cause major problems.</p>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="why-choose-us-section">
        <h2 class="why-choose-us-heading">Why Choose Us for IT Support?</h2>
        <div class="why-choose-us-container">
            <div class="why-item">
                <i class="fas fa-clock why-icon"></i>
                <h3>Quick Response</h3>
                <p>Our IT experts are ready to assist you quickly, ensuring minimal downtime for your business.</p>
            </div>
            <div class="why-item">
                <i class="fas fa-user-tie why-icon"></i>
                <h3>Expert Technicians</h3>
                <p>Our team has extensive experience with all types of IT systems and troubleshooting.</p>
            </div>
            <div class="why-item">
                <i class="fas fa-screwdriver why-icon"></i>
                <h3>Flexible Solutions</h3>
                <p>We tailor our services to meet your specific needs, offering both on-site and remote support options.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="cta-section">
        <h2 class="cta-heading">Need Immediate IT Support?</h2>
        <p class="cta-description">Contact us today for a quick response and personalized IT solutions to keep your systems running smoothly.</p>
        <a href="/contact" class="cta-button">Get Support</a>
    </div>

</div>

<script>
    // Optional: Add interaction if needed
    document.querySelectorAll('.it-support-item').forEach(item => {
        item.addEventListener('click', function() {
            const details = this.querySelector('.it-support-details');
            details.style.display = (details.style.display === 'block') ? 'none' : 'block';
        });
    });
</script>
