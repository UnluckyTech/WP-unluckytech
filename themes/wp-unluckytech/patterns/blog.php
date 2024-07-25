<?php
/**
 * Title: Blog
 * Slug: unluckytech/blog
 * Categories: text, featured
 * Description: This would be used for the blog of the website.
 */
?>

<div class="blog-wrapper">
    <div class="blog-banner">
        <h1>All Projects</h1>
    </div>
    <div class="blog-inner-container">
        <?php if ( have_posts() ) : ?>
            <div class="blog-posts">
                <?php
                // Start the loop
                while ( have_posts() ) : the_post();
                    ?>
                    <a href="<?php the_permalink(); ?>" class="blog-post-link">
                        <div class="blog-post-card">
                            <div class="blog-post-image">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/200" alt="Placeholder Image" />
                                <?php endif; ?>
                            </div>
                            <div class="blog-post-content">
                                <div class="blog-post-date">
                                    <?php echo get_the_date(); ?>
                                </div>
                                <div class="blog-post-title">
                                    <h2><?php the_title(); ?></h2>
                                </div>
                                <div class="blog-post-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php
                endwhile;

                // Pagination
                the_posts_pagination( array(
                    'prev_text' => __( 'Previous page', 'textdomain' ),
                    'next_text' => __( 'Next page', 'textdomain' ),
                ) );
                ?>
            </div><!-- .blog-posts -->

        <?php else : ?>
            <p><?php _e( 'No posts found.', 'textdomain' ); ?></p>
        <?php endif; ?>
    </div><!-- .blog-inner-container -->
</div><!-- .blog-wrapper -->
