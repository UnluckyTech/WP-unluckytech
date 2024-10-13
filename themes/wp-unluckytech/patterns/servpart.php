<?php
/**
 * Title: servpart
 * Slug: unluckytech/servpart
 * Categories: text
 * Description: servpart section for the homepage.
 */
?>

<div class="servpart-section">
    <div class="servpart-list">
        <h2 class="servpart-title">services</h2>
        <div class="servpart-columns">
            <ul class="servpart-column">
                <li>
                    <a href="/web-development">
                        <i class="fas fa-code"></i> Web Development
                    </a>
                </li>
                <li>
                    <a href="/system-configuration">
                        <i class="fas fa-cogs"></i> System Configuration
                    </a>
                </li>
                <li>
                    <a href="/server-management">
                        <i class="fas fa-server"></i> Server Management
                    </a>
                </li>
            </ul>
            <ul class="servpart-column">
                <li>
                    <a href="/technical-consultation">
                        <i class="fas fa-headset"></i> Technical Consultation
                    </a>
                </li>
                <li>
                    <a href="/custom-pc">
                        <i class="fas fa-desktop"></i> Custom PC Builds
                    </a>
                </li>
                <li>
                    <a href="/it-support">
                        <i class="fas fa-tools"></i> IT Support
                    </a>
                </li>
            </ul>
        </div>
        <hr class="servpart-divider">

        <!-- New Quick Access Section -->
        <div class="quick-access-section">
            <div class="quick-access-columns">
                <ul class="qa-column">
                    <li>
                        <a href="https://www.helpwire.app/">
                            <i class="fas fa-desktop"></i> Remote Desktop
                        </a>
                    </li>
                </ul>
                <ul class="qa-column">
                    <li>
                        <a href="/contact">
                            <i class="fas fa-envelope"></i> Contact Us
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="servpart-slideshow">
        <div class="slideshow-container">
            <div class="mySlides active">
                <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/home/serv1.webp' ) ); ?>" alt="Service 1">
            </div>
            <div class="mySlides active">
                <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/home/serv2.webp' ) ); ?>" alt="Service 2">
            </div>
        </div>
        <div class="arrow-container">
            <div class="arrow-up" onclick="plusSlides(-1)">&#10094;</div>
            <div class="arrow-down" onclick="plusSlides(1)">&#10095;</div>
        </div>
    </div>

</div>


