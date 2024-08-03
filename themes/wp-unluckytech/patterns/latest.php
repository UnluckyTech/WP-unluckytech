<?php
/**
 * Title: Latest
 * Slug: unluckytech/latest
 * Categories: text, featured
 * Description: This would be used for the latest page of the website.
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest</title>
    <link rel="stylesheet" href="latest.css">
</head>
<body>

<div class="unluckytech-slideshow-container">
    <?php
    // Fetch the latest posts
    $latest_posts = new WP_Query(array(
        'posts_per_page' => 3,
        'post_status' => 'publish',
    ));

    if ($latest_posts->have_posts()) :
        while ($latest_posts->have_posts()) : $latest_posts->the_post();
            $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    ?>
            <div class="unluckytech-slide">
                <img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php the_title(); ?>">
            </div>
    <?php
        endwhile;
        wp_reset_postdata();
    endif;
    ?>

    <!-- Dots -->
    <div class="unluckytech-dots-container">
        <?php for ($i = 1; $i <= $latest_posts->post_count; $i++) : ?>
            <span class="unluckytech-dot" onclick="unluckytechCurrentSlide(<?php echo $i; ?>)"></span>
        <?php endfor; ?>
    </div>
</div>

</body>
</html>
