<?php
/**
 * Title: Videos
 * Slug: unluckytech/videos
 * Categories: text, featured
 * Description: This section offers an alternative view with reference to external documentation.
 */

// Query to get posts from the "Video" category
$args = array(
    'category_name' => 'video', // Replace with the slug of your Video category
    'posts_per_page' => 4, // Adjust the number of posts to display
);

$video_posts = new WP_Query($args);
?>

<div class="video-section">
    <h2>Videos</h2>
    <div class="video-grid desktop-view">
        <?php if ($video_posts->have_posts()) : ?>
            <?php while ($video_posts->have_posts()) : $video_posts->the_post(); ?>
                <div class="video-item">
                    <a href="<?php the_permalink(); ?>" class="video-link" target="_blank">
                        <div class="video-thumbnail">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" />
                                <div class="video-overlay"></div>
                                <div class="video-title"><?php the_title(); ?></div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p>No videos found in this category.</p>
        <?php endif; ?>
    </div>
    <div class="video-divider"></div>
    <div class="video-slider mobile-view">
        <?php if ($video_posts->have_posts()) : ?>
            <?php $index = 0; ?>
            <?php while ($video_posts->have_posts()) : $video_posts->the_post(); ?>
                <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                    <a href="<?php the_permalink(); ?>" class="video-link" target="_blank">
                        <div class="video-thumbnail">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" />
                                <div class="video-overlay"></div>
                                <div class="video-title"><?php the_title(); ?></div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <?php $index++; ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p>No videos found in this category.</p>
        <?php endif; ?>
    </div>
    <div class="dots-container mobile-view">
        <?php for ($i = 0; $i < $index; $i++) : ?>
            <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $i; ?>)"></span>
        <?php endfor; ?>
    </div>
    <div class="more-videos-container">
        <a href="<?php echo get_category_link(get_category_by_slug('video')->term_id); ?>" class="more-videos">More</a>
    </div>
</div>