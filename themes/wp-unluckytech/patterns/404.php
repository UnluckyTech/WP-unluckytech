<?php
/**
 * Title: 404
 * Slug: unluckytech/404
 * Categories: text, featured
 * Description: This would be used for the 404 page of the website.
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/404.css">
    <title><?php _e( 'Page Not Found', 'unluckytech' ); ?></title>
</head>
<body>
    <div class="error-page-container">
        <div class="error-page-content">
            <h1><?php _e( '404', 'unluckytech' ); ?></h1>
            <p><?php _e( 'The page you are looking for is not available.', 'unluckytech' ); ?></p>
            <a href="<?php echo home_url(); ?>" class="home-button"><?php _e( 'Go to Homepage', 'unluckytech' ); ?></a>
        </div>
    </div>
</body>
</html>