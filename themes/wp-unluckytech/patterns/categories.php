<?php
/**
 * Title: Categories
 * Slug: unluckytech/categories
 * Categories: text, featured
 * Description: This would be used for listing categories.
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/categories.css">
</head>
<body>

<div class="categories-container">
    <div class="categories-section">
        <h2>Categories</h2>
        <a href="<?php echo get_category_link(get_category_by_slug('projects')->term_id); ?>" class="category-item" style="background-image: url('<?php echo esc_url( get_theme_file_uri( 'assets/images/docs/projects.webp' ) ); ?>');">
            <div class="cat-overlay"></div>
            <div class="category-content">Projects</div>
        </a>
        <a href="<?php echo get_category_link(get_category_by_slug('guides')->term_id); ?>" class="category-item" style="background-image: url('<?php echo esc_url( get_theme_file_uri( 'assets/images/docs/guides.webp' ) ); ?>');">
            <div class="cat-overlay"></div>
            <div class="category-content">Guides</div>
        </a>
        <a href="<?php echo get_category_link(get_category_by_slug('reference')->term_id); ?>" class="category-item" style="background-image: url('<?php echo esc_url( get_theme_file_uri( 'assets/images/docs/reference.webp' ) ); ?>');">
            <div class="cat-overlay"></div>
            <div class="category-content">Reference</div>
        </a>
        <a href="https://docs.unluckytech.com" class="mobile-item" style="background-image: url('<?php echo esc_url( get_theme_file_uri( 'assets/images/docs/gitbooks.webp' ) ); ?>');">
            <div class="cat-overlay"></div>
            <div class="category-content">Alternative</div>
        </a>
    </div>
    <div class="alternative-section">
        <h2>Alternative</h2>
        <a href="https://docs.unluckytech.com" class="alt-item" style="background-image: url('<?php echo esc_url( get_theme_file_uri( 'assets/images/docs/gitbooks.webp' ) ); ?>');">
            <div class="cat-overlay"></div>
            <div class="alt-inner">
                <div class="alt-content">Documentation</div>
                <p class="alt-description">Check here if you can't find what you are looking for!</p>
            </div>
        </a>
    </div>
</div>

</body>
</html>
