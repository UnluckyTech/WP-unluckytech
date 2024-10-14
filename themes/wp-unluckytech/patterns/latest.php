<?php
/**
 * Title: Latest
 * Slug: unluckytech/latest
 * Categories: text, featured
 * Description: This would be used for the latest page of the website.
 */
?>

<div class="unluckytech-slideshow-container">
    <?php
    // Fetch the latest posts excluding the 'Videos' category
    $latest_posts = new WP_Query(array(
        'posts_per_page' => 3,
        'post_status' => 'publish',
        'category__not_in' => array(get_cat_ID('Videos')), // Exclude 'Videos' category
    ));

    if ($latest_posts->have_posts()) :
        $slide_index = 0;
        while ($latest_posts->have_posts()) : $latest_posts->the_post();
            $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full') ?: '/assets/images/home/placeholder.webp';
            $post_title = get_the_title(); // Get the post title
            $post_link = get_permalink(); // Get the post link
    ?>
            <a href="<?php echo esc_url($post_link); ?>" class="unluckytech-slide-link">
                <div class="unluckytech-slide" style="display: <?php echo $slide_index === 0 ? 'block' : 'none'; ?>;">
                    <img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php echo esc_attr($post_title); ?>">
                    <div class="unluckytech-overlay"></div> <!-- Overlay -->
                    <div class="unluckytech-title"><?php echo esc_html($post_title); ?></div> <!-- Title -->
                </div>
            </a>
    <?php
            $slide_index++;
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

