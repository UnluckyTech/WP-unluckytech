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

<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/bg1.png' ) ); ?>","id":3838,"dimRatio":50,"overlayColor":"contrast","align":"full"} -->
<div class="wp-block-cover alignfull">
    <span aria-hidden="true" class="has-contrast-background-color has-background-dim"></span>
    <img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/bg1.png' ) ); ?>" data-object-fit="cover"/>
    <div class="wp-block-cover__inner-container">
        <div class="unluckytech-categories-container">
            <a href="<?php echo get_category_link(get_category_by_slug('projects')->term_id); ?>" class="unluckytech-category-button">Projects</a>
            <a href="<?php echo get_category_link(get_category_by_slug('guides')->term_id); ?>" class="unluckytech-category-button">Guides</a>
            <a href="<?php echo get_category_link(get_category_by_slug('reference')->term_id); ?>" class="unluckytech-category-button">Reference</a>
        </div>
    </div>
</div>
<!-- /wp:cover -->

</body>
</html>
