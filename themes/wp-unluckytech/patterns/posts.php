<?php
/**
 * Title: Projects
 * Slug: unluckytech/posts
 * Categories: text
 * Description: A section to display recent posts.
 */
?>

<!-- patterns/posts.php -->
<div class="posts-wrapper">
    <div class="posts-inner-container">
        <div class="posts-title">Projects</div>
        <hr class="posts-divider">
        <div class="posts-container">
            <?php
            // Query to get four posts
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 4,
            );
            $query = new WP_Query( $args );

            if ( $query->have_posts() ) :
                while ( $query->have_posts() ) : $query->the_post();
                    ?>
                    <a href="<?php the_permalink(); ?>" class="post-link">
                        <div class="post-card">
                            <div class="post-date">
                                <?php echo get_the_date(); ?>
                            </div>
                            <div class="post-image">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/200" alt="Placeholder Image" />
                                <?php endif; ?>
                            </div>
                            <div class="post-title">
                                <h2><?php the_title(); ?></h2>
                            </div>
                            <div class="post-description">
                                <?php the_excerpt(); ?>
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
        <hr class="posts-divider">
        <!-- More Button -->
        <div class="more-button-container">
            <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="more-button">More Projects</a>
        </div>
    </div>
</div>