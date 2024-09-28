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
        <div class="blog-header">
            <div class="blog-overlay"></div>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/bg1.png" alt="Blog Header Image">
            <div class="blog-title">
                <h1>All Posts</h1>
            </div>
        </div>
        <div class="blog-top">
            <!-- Sort Form -->
            <form method="get" class="sort-form" action="">
                <select name="sort" id="sort-by">
                    <option value="date_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date_desc') ? 'selected' : ''; ?>>Date: descending</option>
                    <option value="date_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date_asc') ? 'selected' : ''; ?>>Date: ascending</option>
                    <option value="title_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'title_asc') ? 'selected' : ''; ?>>Title: ascending</option>
                    <option value="title_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'title_desc') ? 'selected' : ''; ?>>Title: descending</option>
                </select>

                <label for="category" class="sr-only">Category</label>
                <select name="category" id="category">
                    <option value="all"><?php _e('All Categories', 'textdomain'); ?></option>
                    <?php
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        echo '<option value="' . esc_attr($category->slug) . '"' . (isset($_GET['category']) && $_GET['category'] == $category->slug ? ' selected' : '') . '>' . esc_html($category->name) . '</option>';
                    }
                    ?>
                </select>

                <label for="tag" class="sr-only">Tag</label>
                <select name="tag" id="tag">
                    <option value="all"><?php _e('All Tags', 'textdomain'); ?></option>
                    <?php
                    $tags = get_tags();
                    foreach ($tags as $tag) {
                        echo '<option value="' . esc_attr($tag->slug) . '"' . (isset($_GET['tag']) && $_GET['tag'] == $tag->slug ? ' selected' : '') . '>' . esc_html($tag->name) . '</option>';
                    }
                    ?>
                </select>

                <button type="submit" class="apply-button">Apply</button>
            </form>
        </div>

        <?php
        // Set up the custom query to limit posts per page and handle sorting
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $tag = isset($_GET['tag']) ? $_GET['tag'] : '';
        $posts_per_page = isset($_GET['posts_per_page']) && $_GET['posts_per_page'] != 'all' ? intval($_GET['posts_per_page']) : 5; // Default to 5 posts per page

        // Preserve other query parameters
        $query_args = array(
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
        );

        if ($category != '' && $category != 'all') {
            $query_args['category_name'] = $category;
        }

        if ($tag != '' && $tag != 'all') {
            $query_args['tag'] = $tag;
        }

        switch ($sort) {
            case 'title_asc':
                $query_args['orderby'] = 'title';
                $query_args['order'] = 'ASC';
                break;
            case 'title_desc':
                $query_args['orderby'] = 'title';
                $query_args['order'] = 'DESC';
                break;
            case 'date_asc':
                $query_args['orderby'] = 'date';
                $query_args['order'] = 'ASC';
                break;
            case 'date_desc':
            default:
                $query_args['orderby'] = 'date';
                $query_args['order'] = 'DESC';
                break;
        }

        $custom_query = new WP_Query($query_args);
        ?>
        <?php if ($custom_query->have_posts()) : ?>
            <div class="blog-posts">
                <?php
                // Start the loop
                while ($custom_query->have_posts()) : $custom_query->the_post();
                    ?>
                    <div class="blog-post-card">
                        <a href="<?php the_permalink(); ?>" class="blog-post-image-link">
                            <div class="blog-post-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php else : ?>
                                    <img src="https://via.placeholder.com/200" alt="Placeholder Image" />
                                <?php endif; ?>
                            </div>
                        </a>
                        <a href="<?php the_permalink(); ?>" class="mainblog-post-link">
                            <div class="blog-post-content">
                                <div class="blog-post-title-container">
                                    <div class="blog-post-title">
                                        <h2><?php the_title(); ?></h2>
                                    </div>
                                    <div class="title-divider"></div> <!-- Divider element -->
                                </div>
                                <div class="blog-post-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </a>
                        <div class="blog-post-meta-content">
                            <div class="meta-inner-container">
                                <div class="blog-post-category">
                                    <?php
                                    $category = get_the_category();
                                    if (!empty($category)) {
                                        echo esc_html($category[0]->name);
                                    }
                                    ?>
                                </div>
                                <div class="meta-divider"></div> <!-- Divider after category -->
                                <div class="blog-post-tags">
                                    <?php
                                    $tags = get_the_tags();
                                    if ($tags) {
                                        foreach ($tags as $tag) {
                                            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="meta-divider"></div> <!-- Divider before date -->
                                <div class="blog-post-date">
                                    <?php echo get_the_date(); ?>
                                </div>
                            </div>
                        </div><!-- .blog-post-meta-content -->
                    </div>
                    <?php
                endwhile;
                ?>
            </div><!-- .blog-posts -->
            <div class="pagination-container"> <!-- New container for pagination and posts per page -->
                <div class="pagination">
                    <?php
                    // Pagination
                    if ($posts_per_page == -1) {
                        // Show "All" button
                        echo '<span class="pagination-all-button">All</span>';
                    } else {
                        echo paginate_links(array(
                            'total' => $custom_query->max_num_pages,
                            'current' => $paged,
                            'prev_text' => __('Previous', 'textdomain'),
                            'next_text' => __('Next', 'textdomain'),
                        ));
                    }
                    ?>
                </div>
                <!-- Posts Per Page Dropdown -->
                <div class="posts-per-page">
                    <form method="get" class="posts-per-page-form" action="">
                        <label for="posts-per-page">Posts per page:</label>
                        <select name="posts_per_page" id="posts-per-page" onchange="this.form.submit()">
                            <option value="5" <?php echo (isset($_GET['posts_per_page']) && $_GET['posts_per_page'] == '5') ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?php echo (isset($_GET['posts_per_page']) && $_GET['posts_per_page'] == '10') ? 'selected' : ''; ?>>10</option>
                            <option value="20" <?php echo (isset($_GET['posts_per_page']) && $_GET['posts_per_page'] == '20') ? 'selected' : ''; ?>>20</option>
                            <option value="50" <?php echo (isset($_GET['posts_per_page']) && $_GET['posts_per_page'] == '50') ? 'selected' : ''; ?>>50</option>
                            <option value="all" <?php echo (isset($_GET['posts_per_page']) && $_GET['posts_per_page'] == 'all') ? 'selected' : ''; ?>>All</option>
                        </select>
                    </form>
                </div>
            </div> <!-- End of pagination-container -->
        <?php else : ?>
            <p><?php _e('No posts found.', 'textdomain'); ?></p>
        <?php endif; ?>
        <?php
        // Reset post data
        wp_reset_postdata();
        ?>
    </div><!-- .blog-inner-container -->
</div><!-- .blog-wrapper -->
