<?php
/**
 * Title: Projects
 * Slug: unluckytech/posts
 * Categories: text
 * Description: A section to display recent posts.
 */
?>

<div class="posts-wrapper">
    <div class="posts-inner-container">
        <div class="posts-title">LATEST</div>
        <hr class="posts-top-divider">
        <div class="posts-container">
            <?php
            // Get the ID of the "Videos" category
            $video_category_id = get_cat_ID('Videos');

            // Query to get four posts excluding the "Videos" category
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 4,
                'category__not_in' => array($video_category_id),
            );
            $query = new WP_Query( $args );

            if ( $query->have_posts() ) :
                while ( $query->have_posts() ) : $query->the_post();
                    ?>
                    <a href="<?php the_permalink(); ?>" class="post-link">
                        <div class="post-card">
                            <div class="post-image">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail('medium', ['loading' => 'lazy']); ?>
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/200" alt="Placeholder Image" loading="lazy" />
                                <?php endif; ?>
                            </div>
                            <div class="post-content">
                                <div class="post-card-title">
                                    <h2><?php the_title(); ?></h2>
                                    <div class="title-post-divider"></div>
                                    <div class="post-date">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                </div>
                                <div class="post-description">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>No posts found</p>';
            endif;
            ?>
        </div>
        <hr class="posts-bottom-divider">
        <!-- More Button -->
        <div class="more-button-container">
            <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="more-button" aria-label="Read more blog posts from Unlucky Tech">MORE</a>
        </div>
    </div>
</div>
