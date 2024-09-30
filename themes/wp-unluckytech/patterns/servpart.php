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
        <ul>
            <li>Web Development</li>
            <li>Graphic Design</li>
            <li>SEO Optimization</li>
            <li>Social Media Management</li>
            <li>Content Creation</li>
            <li>IT Consulting</li>
        </ul>
    </div>
    <div class="servpart-slideshow">
        <div class="slideshow-container">
            <div class="mySlides active">
                <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/serv1.jpg' ) ); ?>" alt="Service 1">
            </div>
            <div class="mySlides active">
                <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/serv2.jpg' ) ); ?>" alt="Service 2">
            </div>
            <!-- Add more slides as needed -->
        </div>
        <div class="arrow-container">
            <div class="arrow-up" onclick="plusSlides(-1)">&#10094;</div>
            <div class="arrow-down" onclick="plusSlides(1)">&#10095;</div>
        </div>
    </div>
</div>

<!-- Include the slideshow JavaScript -->
<script src="<?php echo esc_url( get_theme_file_uri( 'assets/js/slideshow.js' ) ); ?>"></script>