<?php
/**
 * Title: Alternative
 * Slug: unluckytech/alternative
 * Categories: text, featured
 * Description: This section offers an alternative view with reference to external documentation.
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alternative View</title>
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/alternative.css">
</head>
<body>

<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/bg2.jpg' ) ); ?>","id":3839,"dimRatio":50,"overlayColor":"contrast","align":"full"} -->
<div class="wp-block-cover alignfull">
    <span aria-hidden="true" class="has-contrast-background-color has-background-dim"></span>
    <img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/bg2.jpg' ) ); ?>" data-object-fit="cover"/>
    <div class="wp-block-cover__inner-container">
        <div class="unluckytech-alternative-container">
            <div class="unluckytech-alternative-image">
                <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/example.jpg' ) ); ?>" alt="Example Image">
            </div>
            <div class="unluckytech-alternative-text">
                <h1 class="unluckytech-alternative-title">Alternative View</h1>
                <p class="unluckytech-alternative-description">For an alternative view of what is offered on this site, please refer to <a href="https://docs.unluckytech.com">https://docs.unluckytech.com</a>.</p>
            </div>
        </div>
    </div>
</div>
<!-- /wp:cover -->

</body>
</html>
