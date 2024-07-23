<?php
/**
 * Title: Blog
 * Slug: unluckytech/blog
 * Categories: text, featured
 * Description: This would be used for the blog of the website.
 */
?>

<?php

<div class="archive-wrapper">
    <div class="archive-inner-container">
        <?php if ( have_posts() ) : ?>
            <header class="archive-header">
                <h1 class="archive-title">
                    <?php
                    if ( is_category() ) :
                        single_cat_title( 'Category: ' );
                    elseif ( is_tag() ) :
                        single_tag_title( 'Tag: ' );
                    elseif ( is_author() ) :
                        global $author;
                        $author_info = get_userdata( $author );
                        echo 'Author: ' . $author_info->display_name;
                    elseif ( is_date() ) :
                        echo 'Date Archive';
                    else :
                        echo 'Archives';
                    endif;
                    ?>
                </h1>
            </header><!-- .archive-header -->

            <div class="archive-posts">
                <?php
                // Start the loop
                while ( have_posts() ) : the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="post-meta">
                            <span class="post-date"><?php the_date(); ?></span>
                        </div>
                        <div class="post-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    </article><!-- #post-<?php the_ID(); ?> -->
                    <?php
                endwhile;

                // Pagination
                the_posts_pagination( array(
                    'prev_text' => __( 'Previous page', 'textdomain' ),
                    'next_text' => __( 'Next page', 'textdomain' ),
                ) );
                ?>
            </div><!-- .archive-posts -->

        <?php else : ?>
            <p><?php _e( 'No posts found.', 'textdomain' ); ?></p>
        <?php endif; ?>
    </div><!-- .archive-inner-container -->
</div><!-- .archive-wrapper -->