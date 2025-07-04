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
                <hr class="date-top-divider"> <!-- Bottom Divider -->
            </div>
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
                    <div class="discussion-section" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/discussion.webp' ); ?>');">
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
                        <img src="/wp-content/themes/wp-unluckytech/assets/images/termicon.webp" alt="User Logo">
                        <div class="user-name">UnluckyTech</div>
                        <div class="user-box-divider"></div>
                        <div class="user-box-social">
                            <a href="https://x.com/UnluckyTech" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.youtube.com/@UnluckyTechs" target="_blank"><i class="fab fa-youtube"></i></a>
                            <a href="https://www.twitch.tv/unluckytech" target="_blank"><i class="fab fa-twitch"></i></a>
                        </div>
                    </div>
                </div>

                <div class="related-posts">
                    <div class="related-posts-title">RELATED</div>
                    <div class="related-posts-container">
                        <hr class="related-posts-top-divider">

                        <?php
                        // Get tags for the current post
                        $post_tags = wp_get_post_tags( get_the_ID() );

                        if ( $post_tags ) {
                            $tag_ids = array_map( function( $tag ) {
                                return $tag->term_id;
                            }, $post_tags );

                            $related_args = array(
                                'tag__in'             => $tag_ids,
                                'post__not_in'        => array( get_the_ID() ),
                                'posts_per_page'      => 3,
                                'ignore_sticky_posts' => 1,
                            );

                            $related_query = new WP_Query( $related_args );

                            if ( $related_query->have_posts() ) :
                                while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                                    <a href="<?php the_permalink(); ?>" class="single-post-link">
                                        <div class="single-post-card">
                                            <div class="single-post-image">
                                                <?php if ( has_post_thumbnail() ) : ?>
                                                    <?php the_post_thumbnail('medium'); ?>
                                                <?php else : ?>
                                                    <img src="https://via.placeholder.com/200" alt="Placeholder Image" />
                                                <?php endif; ?>
                                            </div>
                                            <div class="latest-post-content">
                                                <div class="single-post-date"><?php echo get_the_date(); ?></div>
                                                <div class="single-post-title">
                                                    <h3><?php the_title(); ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endwhile;
                                wp_reset_postdata();
                            else :
                                echo '<p>No related posts found</p>';
                            endif;
                        } else {
                            echo '<p>No related tags to match posts.</p>';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="post-info-box">
                    <div class="post-info-inner">
                        <div class="post-info-title">POST INFO</div>
                        <div class="post-info-content">
                            <!-- Displaying the categories -->
                            <div class="post-categories">
                                <strong>Category:</strong>
                                <?php the_category(', '); ?>
                            </div>
                            <!-- Displaying the tags -->
                            <div class="post-tags">
                                <strong>Tags:</strong>
                                <?php the_tags('', ', ', ''); ?>
                            </div>
                            <!-- Displaying the last updated date -->
                            <div class="post-last-updated">
                                <strong>Last Updated:</strong>
                                <?php the_modified_date(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin-only box -->
                <?php if ( current_user_can( 'administrator' ) ) : ?>
                    <div class="admin-post-box">
                        <div class="admin-box-inner">
                            <div class="admin-box-title">Admin Options</div>
                            <div class="admin-box-content">
                                <!-- Edit Post Link -->
                                <?php edit_post_link( 'Edit Post', '<div class="admin-edit-link">', '</div>' ); ?>
                                
                                <!-- Delete Post Link -->
                                <a href="<?php echo esc_url( get_delete_post_link( get_the_ID() ) ); ?>" class="admin-delete-link" onclick="return confirm('Are you sure you want to delete this post?');">
                                    Delete Post
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>    
        </div>
    </div>
</main>
