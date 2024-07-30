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
        <div class="main-post-content">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="single-post-banner" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url() ); ?>');"></div>
            <?php endif; ?>

            <div class="single-post-title">
                <!-- wp:post-title /--> <!-- Displays the post title -->
            </div>
            <hr class="date-top-divider"> <!-- Bottom Divider -->
            <div class="main-single-post-date">
                Updated on <?php the_modified_date(); ?>
            </div>
            <hr class="date-bottom-divider"> <!-- Bottom Divider -->
            <div class="single-post-meta">
                <!-- wp:post-meta /--> <!-- Displays the post meta (e.g., date, author) -->
            </div>

            <div class="single-post-content">
                <!-- wp:post-content /--> <!-- Displays the post content -->
            </div>
        
            <div class="main-discussion">
                <!-- Discussion Section -->
                <?php
                // Get the first category of the current post
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    $category = $categories[0];
                    $category_slug = $category->slug;
                    $discussion_url = 'https://forums.unluckytech.com/c/' . $category_slug;
                } else {
                    $discussion_url = 'https://forums.unluckytech.com'; // Default URL if no category found
                }
                ?>
                <a href="<?php echo esc_url( $discussion_url ); ?>" class="discussion-link">
                    <div class="discussion-section" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/discussion.jpg' ); ?>');">
                        <div class="discussion-overlay">
                            <h2 class="discussion-title">DISCUSSION</h2>
                        </div>
                    </div>
                </a>    
            </div>
        
        </div>

        <!-- Right Side: Sidebar -->
        <div class="single-post-sidebar">
            <div class="single-post-box">
                <div class="user-box">
                    <div class="user-box-inner">
                        <img src="/wp-content/themes/wp-unluckytech/assets/images/termicon.png" alt="User Logo">
                        <div class="user-name">UnluckyTech</div>
                        <div class="user-box-divider"></div>
                        <div class="user-box-social">
                            <a href="https://x.com/UnluckyTech" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.youtube.com/@UnluckyTechs" target="_blank"><i class="fab fa-youtube"></i></a>
                            <a href="https://www.twitch.tv/unluckytech" target="_blank"><i class="fab fa-twitch"></i></a>
                        </div>
                    </div>
                </div>

                <div class="latest-posts">
                    <div class="latest-posts-title">LATEST</div>
                    <div class="latest-posts-container">
                        <hr class="latest-posts-top-divider"> <!-- Top Divider -->
                        
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
                                        <div class="latest-post-content">
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
    </div>
</main>
