<?php
/**
 * Title: 404 Page Not Found
 * Slug: unluckytech/404
 * Categories: error
 * Description: This is the 404 error page of the website.
 */
?>

<div class="error-container">
    
    <!-- 404 Banner -->
    <div class="error-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="error-banner-overlay">
            <h1 class="error-title">404</h1>
            <p class="error-message">Oops! The page you’re looking for can’t be found.</p>
        </div>
    </div> 

    <div class="error-content">
        <!-- Latest Posts Section -->
        <div class="latest-posts-section">
            <h2>Latest Posts</h2>
            <?php
            // Query to get latest posts
            $args = array(
                'posts_per_page' => 4, // Adjust the number of posts to display
                'post_status' => 'publish',
            );
            $latest_posts = new WP_Query($args);
            ?>

            <div class="latest-posts-grid desktop-view">
                <?php if ($latest_posts->have_posts()) : ?>
                    <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
                        <div class="latest-post-item">
                            <a href="<?php the_permalink(); ?>" class="post-link">
                                <div class="latest-post-thumbnail">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" />
                                        <div class="post-overlay"></div>
                                        <div class="post-title"><?php the_title(); ?></div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p>No recent posts found.</p>
                <?php endif; ?>
            </div>

            <div class="latest-post-divider"></div>

            <!-- Mobile Slider View -->
            <div class="latest-post-slider mobile-view">
                <?php if ($latest_posts->have_posts()) : ?>
                    <?php $index = 0; ?>
                    <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
                        <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
                            <a href="<?php the_permalink(); ?>" class="post-link">
                                <div class="latest-post-thumbnail">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" />
                                        <div class="post-overlay"></div>
                                        <div class="post-title"><?php the_title(); ?></div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                        <?php $index++; ?>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p>No recent posts found.</p>
                <?php endif; ?>
            </div>

            <!-- Slider Dots for Mobile -->
            <div class="dots-container mobile-view">
                <?php for ($i = 0; $i < $index; $i++) : ?>
                    <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $i; ?>)"></span>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let slides = document.querySelectorAll('.mobile-view .slide');
    let dots = document.querySelectorAll('.dots-container .dot');
    let currentSlideIndex = 0;

    // Function to show the current slide
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            dots[i].classList.remove('active');
        });
        slides[index].classList.add('active');
        dots[index].classList.add('active');
    }

    // Initialize the first slide as active
    showSlide(currentSlideIndex);

    // Add click event listeners for dots (if you have them)
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlideIndex = index;
            showSlide(currentSlideIndex);
        });
    });
});
</script>