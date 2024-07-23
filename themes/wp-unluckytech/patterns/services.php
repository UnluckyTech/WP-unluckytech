<?php
/**
 * Title: Services
 * Slug: unluckytech/services
 * Categories: text
 * Description: Services section for the homepage.
 */
?>

<!-- Services Section -->
<div class="services-section">
    <div class="services-wrapper">
        <div class="wp-block-group">
            <div class="services-title">SERVICES</div>
            <div class="services-list">
                <ul>
                    <li><?php _e('Web Development', 'wp-unluckytech'); ?></li>
                    <li><?php _e('Graphic Design', 'wp-unluckytech'); ?></li>
                    <li><?php _e('SEO Optimization', 'wp-unluckytech'); ?></li>
                    <li><?php _e('Social Media Management', 'wp-unluckytech'); ?></li>
                    <li><?php _e('Content Creation', 'wp-unluckytech'); ?></li>
                    <li><?php _e('IT Consulting', 'wp-unluckytech'); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="services-slideshow">
        <div class="slideshow-container">
            <?php
            $images = array(
                'https://via.placeholder.com/600x400',
                'https://via.placeholder.com/300x200',
                'https://via.placeholder.com/300x200',
            );
            foreach ($images as $index => $image) {
                echo '<div class="mySlides">';
                echo '<img src="' . esc_url($image) . '" alt="Service Image ' . ($index + 1) . '">';
                echo '</div>';
            }
            ?>
            <div class="dot-container">
                <?php foreach ($images as $index => $image) : ?>
                    <span class="dot" onclick="currentSlide(<?php echo $index + 1; ?>)"></span>
                <?php endforeach; ?>
            </div>
            <div class="services-title-2">SERVICES</div>
        </div>
    </div>
</div>
