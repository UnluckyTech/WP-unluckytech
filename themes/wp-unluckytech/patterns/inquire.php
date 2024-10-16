<?php
/**
 * Title: Contact & Ticket Submission
 * Slug: unluckytech/inquire
 * Categories: text, featured
 * Description: This page allows users to contact me directly via email.
 */
?>

<!-- Contact Page Layout -->
<div class="main-container">
    <div class="main-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/home/bg1.webp');">
        <div class="main-banner-overlay">
            <h1 class="main-title">Contact Me</h1>
        </div>
    </div>

    <div class="main-content">
        <div class="contact-form-container">
            <h2>Get in Touch</h2>
            <p>If you have any questions or would like to reach out, feel free to send us a message! A ticket will be generated and we will get back to you as soon as we can.</p>

            <div class="tabs">
                <ul class="tab-links">
                    <li class="active"><a href="#contact" class="tab-link">Contact Me</a></li>
                    <li><a href="#ticket-status" class="tab-link">Ticket Status</a></li>
                </ul>
            </div>

            <div class="tab-content">
                <!-- Contact Me Form -->
                <!-- wp:pattern {"slug":"unluckytech/contact"} /-->
                <!-- Ticket Status Form -->
                <!-- wp:pattern {"slug":"unluckytech/ticket"} /-->

            </div>
    </div>
</div>

<script>
// Tab functionality
document.addEventListener("DOMContentLoaded", function() {
    const tabs = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab');

    // Function to show the selected tab
    function showTab(tab) {
        const target = document.querySelector(tab.getAttribute('href'));

        tabs.forEach(t => t.parentElement.classList.remove('active'));
        tabContents.forEach(tc => tc.classList.remove('active'));

        tab.parentElement.classList.add('active');
        target.classList.add('active');
    }

    // Check if there's a saved tab in localStorage
    const savedTab = localStorage.getItem('selectedTab');
    if (savedTab) {
        const savedTabLink = document.querySelector(`.tab-link[href="${savedTab}"]`);
        if (savedTabLink) {
            showTab(savedTabLink);
        }
    } else {
        // Default to the first tab if no tab is saved
        showTab(tabs[0]);
    }

    // Add click event listeners to tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            showTab(this);
            // Save the selected tab to localStorage
            localStorage.setItem('selectedTab', this.getAttribute('href'));
        });
    });
});
</script>
