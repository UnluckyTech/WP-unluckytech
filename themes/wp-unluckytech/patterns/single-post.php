<?php
/**
 * Title: Single Post
 * Slug: unluckytech/singlepost
 * Categories: text
 * Description: Displays a single post.
 */
?>

<main class="wp-block-group" style="margin-top:0">
    <div class="single-post-container">
        <!-- Left Side: Post Content -->
        <div class="single-post-content">
            <!-- wp:post-title /--> <!-- Displays the post title -->
            <!-- wp:post-meta /--> <!-- Displays the post meta (e.g., date, author) -->
            <!-- wp:post-content /--> <!-- Displays the post content -->
        </div>

        <!-- Right Side: Sidebar -->
        <div class="single-post-sidebar">
            <div class="user-box">
                <img src="https://via.placeholder.com/80" alt="User Logo">
                <div class="user-name">User Name</div>
            </div>

            <div class="latest-posts">
                <div class="latest-posts-title">Latest Posts</div>
                <div class="latest-posts-container">
                    <?php
                    // Query to get the latest posts
                    $args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 3,
                        'post__not_in'   => array( get_the_ID() ), // Exclude current post
                    );
                    $latest_posts_query = new WP_Query( $args );

                    if ( $latest_posts_query->have_posts() ) :
                        while ( $latest_posts_query->have_posts() ) : $latest_posts_query->the_post();
                            ?>
                            <a href="<?php the_permalink(); ?>" class="single-post-link">
                                <div class="single-post-card">
                                    <div class="single-post-image">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail('medium'); ?>
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/200" alt="Placeholder Image" />
                                        <?php endif; ?>
                                    </div>
                                    <div class="single-post-content">
                                        <div class="single-post-date"><?php echo get_the_date(); ?></div>
                                        <div class="single-post-title">
                                            <h2><?php the_title(); ?></h2>
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
            </div>
        </div>
    </div>
</main>
