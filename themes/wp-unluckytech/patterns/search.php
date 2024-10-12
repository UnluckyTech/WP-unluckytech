<?php
/**
 * Title: Search
 * Slug: unluckytech/search
 * Categories: text
 * Description: A section to display search results.
 */
?>

<?php
/* Template Name: Search Results */

if (isset($_GET['s'])) {
    $search_query = sanitize_text_field($_GET['s']);
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'date';
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $tag = isset($_GET['tag']) ? $_GET['tag'] : '';
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $posts_per_page = isset($_GET['posts_per_page']) && $_GET['posts_per_page'] != 'all' ? intval($_GET['posts_per_page']) : 20;

    $args = array(
        'post_type' => 'post',
        's' => $search_query,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
    );

    if ($category != '' && $category != 'all') {
        $args['category_name'] = $category;
    }

    if ($tag != '' && $tag != 'all') {
        $args['tag'] = $tag;
    }

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

    $search_results = new WP_Query($args);

    ?>
    <div class="main-container">

        <div class="main-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
            <div class="main-banner-overlay">
                <h1 class="main-title">Search Results for: <?php echo esc_html($search_query); ?></h1>
            </div>
        </div> 

        <div class="search-inner-container">

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
            <div class="search-divider"></div>
            <?php
            if ($search_results->have_posts()) :
            ?>
                <div class="search-posts">
                    <?php
                    while ($search_results->have_posts()) : $search_results->the_post();
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
                <div class="search-divider"></div>
                <div class="pagination-container">
                    <div class="pagination">
                        <?php
                        echo paginate_links(array(
                            'total' => $search_results->max_num_pages,
                            'current' => $paged,
                            'prev_text' => __('« Previous', 'textdomain'),
                            'next_text' => __('Next »', 'textdomain'),
                        ));
                        ?>
                    </div>
                    <!-- Posts Per Page Dropdown -->
                    <div class="posts-per-page">
                        <form method="get" class="posts-per-page-form" action="">
                            <input type="hidden" name="s" value="<?php echo esc_attr($search_query); ?>">
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
                </div>
            <?php
            else :
            ?>
                <div class="search-title">
                    <h1>No results found for: <?php echo esc_html($search_query); ?></h1>
                </div>
            <?php
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="search-wrapper">
        <div class="search-inner-container">
            <div class="search-title">
                <h1>Please enter a search query.</h1>
            </div>
        </div>
    </div>
    <?php
}
?>
