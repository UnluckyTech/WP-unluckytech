<?php
/**
 * Title: Technical Consultation
 * Slug: unluckytech/techconsult
 * Categories: text
 * Description: Services page for Technical Consultation offered by UnluckyTech.
 */
?>

<div class="services-container">

    <!-- Services Banner -->
    <div class="services-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="services-banner-overlay">
            <h1 class="services-title">Technical Consultation</h1>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-container">
            <div class="summary-left">
                <h2 class="summary-title">Expert Technical Consultation Services</h2>
            </div>
            <div class="summary-right">
                <p class="summary-description">
                    Our team of experts provides in-depth technical consultation services to help your business navigate complex technology decisions. From choosing the right infrastructure to optimizing workflows, we guide you through every step.
                </p>
            </div>
        </div>
    </div>

    <!-- Types of Consultation Section -->
    <div class="consultation-section">
        <h2 class="consultation-heading">Our Consultation Services</h2>
        <div class="consultation-grid">
            <div class="consultation-item">
                <i class="fas fa-network-wired consultation-icon"></i>
                <h3>Infrastructure Assessment</h3>
                <p>We evaluate your existing infrastructure and provide recommendations for enhancements or upgrades to improve efficiency.</p>
            </div>
            <div class="consultation-item">
                <i class="fas fa-tools consultation-icon"></i>
                <h3>Software Selection</h3>
                <p>Our team helps you select the most suitable software solutions tailored to your business needs.</p>
            </div>
            <div class="consultation-item">
                <i class="fas fa-chart-line consultation-icon"></i>
                <h3>Tech Stack Optimization</h3>
                <p>Optimize your tech stack for better performance, scalability, and cost-efficiency.</p>
            </div>
            <div class="consultation-item">
                <i class="fas fa-cloud consultation-icon"></i>
                <h3>Cloud Integration</h3>
                <p>We assist with cloud strategy, helping you migrate or integrate your infrastructure into cloud environments.</p>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="why-choose-us-section">
        <h2 class="why-choose-us-heading">Why Choose Us for Technical Consultation?</h2>
        <div class="why-choose-us-container">
            <div class="why-item">
                <i class="fas fa-user-cog why-icon"></i>
                <h3>Experienced Consultants</h3>
                <p>Our consultants have years of experience across multiple industries and technology sectors.</p>
            </div>
            <div class="why-item">
                <i class="fas fa-lightbulb why-icon"></i>
                <h3>Innovative Solutions</h3>
                <p>We provide forward-thinking solutions to help future-proof your business.</p>
            </div>
            <div class="why-item">
                <i class="fas fa-check why-icon"></i>
                <h3>Proven Results</h3>
                <p>Our consultations have led to tangible improvements in efficiency, cost savings, and performance for many of our clients.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="cta-section">
        <h2 class="cta-heading">Looking for Expert Technical Consultation?</h2>
        <p class="cta-description">Let our experienced consultants help you navigate the complexities of technology. Contact us today for a consultation.</p>
        <a href="/contact" class="cta-button">Get in Touch</a>
    </div>

</div>

<script>
    // Optional: Add interaction if needed
    document.querySelectorAll('.techconsult-question').forEach(item => {
        item.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            answer.style.display = (answer.style.display === 'block') ? 'none' : 'block';
        });
    });
</script>
