<?php
/**
 * Title: Custom PC Builds
 * Slug: unluckytech/pc
 * Categories: text
 * Description: Services page for Custom PC Builds offered by UnluckyTech.
 */
?>

<div class="services-container">

    <!-- Services Banner -->
    <div class="services-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="services-banner-overlay">
            <h1 class="services-title">Custom PC Builds</h1>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-container">
            <div class="summary-left">
                <h2 class="summary-title">Custom PCs Built to Your Specifications</h2>
            </div>
            <div class="summary-right">
                <p class="summary-description">
                    We design and build custom PCs tailored to your specific needs, whether it's for gaming, professional workstations, or budget-friendly builds. Our experts ensure that every component is handpicked for performance and compatibility.
                </p>
            </div>
        </div>
    </div>

    <!-- Types of PC Builds Section -->
    <div class="pc-builds-section">
        <h2 class="pc-builds-heading">Our PC Build Categories</h2>
        <div class="pc-builds-grid">
            <div class="pc-build-item">
                <i class="fas fa-gamepad pc-build-icon"></i>
                <h3>Gaming PCs</h3>
                <p>High-performance builds designed for the latest AAA games, VR experiences, and more.</p>
            </div>
            <div class="pc-build-item">
                <i class="fas fa-laptop-code pc-build-icon"></i>
                <h3>Workstations</h3>
                <p>Powerful machines optimized for video editing, 3D rendering, and other professional workloads.</p>
            </div>
            <div class="pc-build-item">
                <i class="fas fa-dollar-sign pc-build-icon"></i>
                <h3>Budget Builds</h3>
                <p>Affordable PCs that deliver great performance without breaking the bank.</p>
            </div>
            <div class="pc-build-item">
                <i class="fas fa-server pc-build-icon"></i>
                <h3>Custom Servers</h3>
                <p>Tailored server solutions for small businesses, game hosting, or home media centers.</p>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="why-choose-us-section">
        <h2 class="why-choose-us-heading">Why Choose Us for Your Custom PC Build?</h2>
        <div class="why-choose-us-container">
            <div class="why-item">
                <i class="fas fa-microchip why-icon"></i>
                <h3>Expert Advice</h3>
                <p>Our team has years of experience in building PCs and can guide you to the perfect setup.</p>
            </div>
            <div class="why-item">
                <i class="fas fa-cogs why-icon"></i>
                <h3>Top-Quality Components</h3>
                <p>We use only the best parts from trusted brands to ensure performance and longevity.</p>
            </div>
            <div class="why-item">
                <i class="fas fa-hands-helping why-icon"></i>
                <h3>Personalized Builds</h3>
                <p>We tailor every build to your specific needs and budget, ensuring you get the best possible value.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="cta-section">
        <h2 class="cta-heading">Ready to Build Your Dream PC?</h2>
        <p class="cta-description">Get in touch with us today for a free consultation and let's start building your custom PC.</p>
        <a href="/contact" class="cta-button">Contact Us</a>
    </div>

</div>

<script>
    // Optional: Add interaction if needed
    document.querySelectorAll('.pc-question').forEach(item => {
        item.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            answer.style.display = (answer.style.display === 'block') ? 'none' : 'block';
        });
    });
</script>
