<?php
/**
 * Title: Search
 * Slug: unluckytech/search
 * Categories: text
 * Description: A section to display search results.
 */

/* Template Name: Search Results */

if (isset($_GET['s'])) {
    $search_query = sanitize_text_field($_GET['s']);
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $tag = isset($_GET['tag']) ? $_GET['tag'] : '';
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $posts_per_page = isset($_GET['posts_per_page']) && $_GET['posts_per_page'] != 'all' ? intval($_GET['posts_per_page']) : 5; // Default to 5 posts per page

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
        case 'title_asc':
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
            break;
        case 'title_desc':
            $args['orderby'] = 'title';
            $args['order'] = 'DESC';
            break;
        case 'date_asc':
            $args['orderby'] = 'date';
            $args['order'] = 'ASC';
            break;
        case 'date_desc':
        default:
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
    }

    $search_results = new WP_Query($args);
    ?>

    <div class="main-container">

        <div class="main-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/blog/blog.webp');">
            <div class="main-banner-overlay">
                <h1 class="main-title"><?php echo esc_html($search_query); ?></h1>
                <p class="search-result-count">
                    <?php
                    $found = $search_results->found_posts;
                    echo $found === 1 ? '1 result found' : esc_html($found) . ' results found';
                    ?>
                </p>
            </div>
        </div> 

        <div class="blog-inner-container">

            <div class="blog-top">
                <form method="get" class="sort-form" action="">
                    <input type="hidden" name="s" value="<?php echo esc_attr($search_query); ?>">

                    <div class="filter-group">
                        <label for="sort-by">Sort</label>
                        <select name="sort" id="sort-by">
                            <option value="date_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date_desc') ? 'selected' : ''; ?>>Newest</option>
                            <option value="date_asc"  <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date_asc')  ? 'selected' : ''; ?>>Oldest</option>
                            <option value="title_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'title_asc') ? 'selected' : ''; ?>>A → Z</option>
                            <option value="title_desc"<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'title_desc')? 'selected' : ''; ?>>Z → A</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="category">Category</label>
                        <select name="category" id="category">
                            <option value="all">All</option>
                            <?php foreach (get_categories() as $cat): ?>
                                <option value="<?php echo esc_attr($cat->slug); ?>"
                                    <?php echo (isset($_GET['category']) && $_GET['category'] == $cat->slug) ? 'selected' : ''; ?>>
                                    <?php echo esc_html($cat->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="tag">Tag</label>
                        <select name="tag" id="tag">
                            <option value="all">All</option>
                            <?php foreach (get_tags() as $tag): ?>
                                <option value="<?php echo esc_attr($tag->slug); ?>"
                                    <?php echo (isset($_GET['tag']) && $_GET['tag'] == $tag->slug) ? 'selected' : ''; ?>>
                                    <?php echo esc_html($tag->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="sort-button">Apply</button>
                </form>
            </div>

            <?php if ($search_results->have_posts()) : ?>
                <div class="blog-posts">
                    <?php
                    while ($search_results->have_posts()) : $search_results->the_post();
                        ?>
                        <div class="blog-post-card">
                            <a href="<?php the_permalink(); ?>" class="blog-post-image-link">
                                <div class="blog-post-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium'); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/placeholder.webp" alt="No thumbnail" />
                                    <?php endif; ?>
                                </div>
                            </a>
                            <a href="<?php the_permalink(); ?>" class="mainblog-post-link">
                                <div class="blog-post-content">
                                    <div class="blog-post-title-container">
                                        <div class="blog-post-title">
                                            <h2><?php the_title(); ?></h2>
                                        </div>
                                        <div class="title-divider"></div>
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
                                    <div class="meta-divider"></div>
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
                                    <div class="meta-divider"></div>
                                    <div class="blog-post-date">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    ?>
                </div><!-- .blog-posts -->

                <div class="pagination-container">
                    <div class="pagination">
                        <?php
                        echo paginate_links(array(
                            'total' => $search_results->max_num_pages,
                            'current' => $paged,
                            'prev_text' => __('Previous', 'textdomain'),
                            'next_text' => __('Next', 'textdomain'),
                        ));
                        ?>
                    </div>

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

            <?php else : ?>
                <p><?php _e('No search results found.', 'textdomain'); ?></p>
            <?php endif; ?>
            
            <?php
            wp_reset_postdata();
            ?>

        </div><!-- .blog-inner-container -->
    </div><!-- .main-container -->

<?php
}
?>
