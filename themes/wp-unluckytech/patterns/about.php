<?php
/**
 * Title: About
 * Slug: unluckytech/about
 * Categories: text, featured
 * Description: This would be used for the about page of the website.
 */
?>

<div class="about-container">
    <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/bg1.png' ) ); ?>" alt="Banner" class="about-banner">
    <div class="about-content">
        <div class="about-text">
            <h2>About Me</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
        <div class="about-profile">
            <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/profile-icon.png' ) ); ?>" alt="Profile Icon">
            <div class="about-buttons">
                <a href="#" class="button">Button 1</a>
                <a href="#" class="button">Button 2</a>
                <a href="#" class="button">Button 3</a>
            </div>
        </div>
    </div>
</div>