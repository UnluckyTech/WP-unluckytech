<?php
/**
 * Title: Server Management
 * Slug: unluckytech/servmanage
 * Categories: text
 * Description: Services page for Server Management offered by UnluckyTech.
 */
?>

<div class="main-container">

    <!-- Services Banner -->
    <div class="main-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="main-banner-overlay">
            <h1 class="main-title">Server Management</h1>
        </div>
    </div> 

    <!-- Summary Section (Service Overview) -->
    <div class="summary-section">
        <div class="summary-container">
            <!-- Left Container: Title -->
            <div class="summary-left">
                <h2 class="summary-title">Comprehensive Server Management Services</h2>
            </div>

            <!-- Right Container: Description -->
            <div class="summary-right">
                <p class="summary-description">
                    At UnluckyTech, we offer end-to-end server management solutions, from initial setup to ongoing monitoring and optimization. Our team of experts ensures that your servers run efficiently and securely, allowing you to focus on your core business operations.
                </p>
            </div>
        </div>
    </div>

    <!-- Core Features Section (Key Offerings) -->
    <div class="features-section">
        <h2 class="summary-title">Our Server Management Services</h2>
        <div class="service-cards">
            <div class="service-card">
                <i class="service-icon fas fa-tachometer-alt"></i>
                <h3 class="service-title">24/7 Monitoring</h3>
                <p class="service-description">Our team monitors your servers around the clock to ensure uptime, availability, and early issue detection.</p>
            </div>
            <div class="service-card">
                <i class="service-icon fas fa-shield-alt"></i>
                <h3 class="service-title">Security Updates</h3>
                <p class="service-description">We implement timely security patches and updates to protect your infrastructure from vulnerabilities.</p>
            </div>
            <div class="service-card">
                <i class="service-icon fas fa-database"></i>
                <h3 class="service-title">Backup & Recovery</h3>
                <p class="service-description">Regular backups and disaster recovery solutions to safeguard your data and ensure business continuity.</p>
            </div>
            <div class="service-card">
                <i class="service-icon fas fa-balance-scale"></i>
                <h3 class="service-title">Load Balancing</h3>
                <p class="service-description">Optimize your server load with advanced load balancing techniques to handle traffic efficiently.</p>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="features-section">
        <h2 class="summary-title">Why Choose Us for Server Management?</h2>
        <div class="service-cards">
            <div class="service-card">
                <i class="service-icon fas fa-user-tie"></i>
                <h3 class="service-title">Certified Experts</h3>
                <p class="service-description">Our team consists of certified server administrators with extensive experience managing complex infrastructures.</p>
            </div>
            <div class="service-card">
                <i class="service-icon fas fa-clock"></i>
                <h3 class="service-title">24/7 Availability</h3>
                <p class="service-description">We provide round-the-clock support to ensure that your server issues are resolved quickly.</p>
            </div>
            <div class="service-card">
                <i class="service-icon fas fa-cogs"></i>
                <h3 class="service-title">Proactive Maintenance</h3>
                <p class="service-description">We continuously optimize your servers to improve performance, security, and reliability.</p>
            </div>
        </div>
    </div>

    <div class="faq-container">
        <div class="faq-section">
            <h2 class="summary-title">Frequently Asked Questions: Server Management</h2>

            <div class="faq-item">
                <button class="faq-question">What is included in your server management service?</button>
                <div class="faq-answer" style="display: none;">
                    <p>Our server management service includes 24/7 monitoring, routine maintenance, security updates, backups, and performance optimization. We also provide technical support and emergency troubleshooting.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Do you offer server monitoring?</button>
                <div class="faq-answer" style="display: none;">
                    <p>Yes, we offer continuous server monitoring to ensure uptime and performance. If any issues arise, our team is alerted immediately to resolve them quickly.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">How often do you perform server maintenance?</button>
                <div class="faq-answer" style="display: none;">
                    <p>We perform regular maintenance as needed, including software updates, security patches, and performance tuning. Typically, maintenance is scheduled weekly or biweekly depending on the server's requirements.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">What types of servers do you manage?</button>
                <div class="faq-answer" style="display: none;">
                    <p>We manage a wide range of servers including dedicated servers, cloud servers, VPS, and hybrid environments. We work with Linux, Windows, and other platforms based on your infrastructure.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Can you assist with server migrations?</button>
                <div class="faq-answer" style="display: none;">
                    <p>Yes, we provide migration services, ensuring a seamless transition of your data, applications, and services from one server to another without downtime.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Do you offer backups as part of your service?</button>
                <div class="faq-answer" style="display: none;">
                    <p>Yes, regular backups are included in our server management service. We ensure your data is securely backed up and can be restored quickly in case of any issues.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="cta-section">
        <h2 class="summary-title">Need Professional Server Management?</h2>
        <p class="cta-description">Let us handle your server needs so you can focus on your business. Contact us today for a consultation.</p>
        <a href="/contact" class="button">Contact Us</a>
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