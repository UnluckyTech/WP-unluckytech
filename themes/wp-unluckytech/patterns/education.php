<?php
/**
 * Title: Education
 * Slug: unluckytech/education
 * Categories: text, featured
 * Description: This page details the educational background.
 */
?>

<div class="education-container">

    <!-- Education Banner -->
    <div class="education-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="education-banner-overlay">
            <h1 class="education-title">Education</h1>
        </div>
    </div>

    <!-- Education Content Section -->
    <div class="education-content">

        <!-- Degree and School Container -->
        <div class="education-info">
            <!-- Degree and Core Classes Container -->
            <div class="education-degree-container">
                <div class="education-degree">
                    <h2>A.A. Information Technology Management and Cybersecurity</h2>
                </div>

                <!-- Core Classes and Skills Container -->
                <div class="classes-skills-container">
                    <div class="education-classes">
                        <h2 class="dropdown-header">Core Classes</h2>
                        <div class="dropdown-content">
                            <ul>
                                <li>Networking Basics</li>
                                <li>A+ Certification</li>
                                <li>JAVA</li>
                                <li>C++</li>
                                <li>Operating Systems</li>
                                <li>Intro to Technology</li>
                            </ul>
                        </div>
                    </div>

                    <div class="education-skills">
                        <h2 class="dropdown-header">Skills Gained</h2>
                        <div class="dropdown-content">
                            <ul>
                                <li>Network Configuration & Troubleshooting</li>
                                <li>System Security & Cyber Defense</li>
                                <li>Database Management</li>
                                <li>Software Development (JAVA, C++)</li>
                                <li>Operating Systems Administration</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- School Info Container -->
            <div class="education-school">
                <img src="/wp-content/themes/wp-unluckytech/assets/images/irsc.webp" alt="Indian River State College" class="school-image">
                <p>Located in Florida, this institution provided a comprehensive education in Information Technology Management and Cybersecurity.</p>

                <!-- Custom Buttons for IRSC and Degree -->
                <div class="education-buttons-container">
                    <a href="https://www.irsc.edu" target="_blank" class="education-button irsc-button">IRSC</a>
                    <a href="https://irsc.edu/programs/computer-information-technology.html" target="_blank" class="education-button degree-button">DEGREE</a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript for dropdown functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get all dropdown headers
        const dropdownHeaders = document.querySelectorAll('.dropdown-header');

        // Add click event to each dropdown header
        dropdownHeaders.forEach(header => {
            header.addEventListener('click', function() {
                this.classList.toggle('active');
                const content = this.nextElementSibling;

                // Toggle visibility of dropdown content
                if (content.style.display === "block") {
                    content.style.display = "none";
                } else {
                    content.style.display = "block";
                }
            });
        });
    });
</script>
