<?php
/**
 * Title: Blog
 * Slug: unluckytech/blog
 * Categories: text, featured
 * Description: This would be used for the blog of the website.
 */
?>

<div class="blog-wrapper">
    <div class="blog-inner-container">
        <div class="blog-top">
            <div class="blog-title">
                <h1>All Projects</h1>
            </div>
            <!-- Sort Form -->
            <form method="get" class="sort-form" action="">
                <label for="sort-by">Sort by:</label>
                <select name="sort" id="sort-by" onchange="this.form.submit()">
                    <option value="date" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date') ? 'selected' : ''; ?>>Date</option>
                    <option value="title" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'title') ? 'selected' : ''; ?>>Title</option>
                    <option value="popularity" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'popularity') ? 'selected' : ''; ?>>Popularity</option>
                </select>
            </form>
        </div>
        <div class="blog-divider"></div>
        <?php
        // Set up the custom query to limit posts per page and handle sorting
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'date';
        
        $args = array(
            'posts_per_page' => 20,
            'paged' => $paged,
        );
        
        switch ($sort) {
            case 'title':
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
            case 'popularity':
                $args['meta_key'] = 'post_views_count';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'date':
            default:
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
        }
        
        $custom_query = new WP_Query($args);
        ?>
        <?php if ($custom_query->have_posts()) : ?>
            <div class="blog-posts">
                <?php
                // Start the loop
                while ($custom_query->have_posts()) : $custom_query->the_post();
                    ?>
                    <a href="<?php the_permalink(); ?>" class="blog-post-link">
                        <div class="blog-post-card">
                            <div class="blog-post-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php else : ?>
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
                ?>
            </div><!-- .blog-posts -->
            <div class="blog-divider"></div>
            <div class="pagination">
                <?php
                // Pagination
                echo paginate_links(array(
                    'total' => $custom_query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => __('« Previous', 'textdomain'),
                    'next_text' => __('Next »', 'textdomain'),
                ));
                ?>
            </div>
        <?php else : ?>
            <p><?php _e('No posts found.', 'textdomain'); ?></p>
        <?php endif; ?>
        <?php
        // Reset post data
        wp_reset_postdata();
        ?>
    </div><!-- .blog-inner-container -->
</div><!-- .blog-wrapper -->
