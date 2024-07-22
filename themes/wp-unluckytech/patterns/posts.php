<?php
/**
 * Title: Projects
 * Slug: unluckytech/posts
 * Categories: text
 * Description: A section to display recent posts.
 */
?>

<!-- patterns/posts.php -->
<div class="posts-container">
    <?php
    // Query to get three posts
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 4,
    );
    $query = new WP_Query( $args );

    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();
            ?>
            <div class="post-card">
                <div class="post-date">
                    <?php echo get_the_date(); ?>
                </div>
                <div class="post-image">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail(); ?>
                    <?php else: ?>
                        <img src="https://via.placeholder.com/200" alt="Placeholder Image" />
                    <?php endif; ?>
                </div>
                <div class="post-title">
                    <h2><?php the_title(); ?></h2>
                </div>
                <hr class="post-divider"> <!-- Added line -->
                <div class="post-description">
                    <?php the_excerpt(); ?>
                </div>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>No posts found</p>';
    endif;
    ?>
</div>
