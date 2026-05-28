<?php
/**
 * Title: Blog
 * Slug: unluckytech/blog
 * Categories: text, featured
 * Description: This would be used for the blog of the website.
 */
?>

<div class="main-container">

    <div class="main-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/blog/blog.webp');">
        <div class="main-banner-overlay">
            <h1 class="main-title">All Posts</h1>
        </div>
    </div>

    <div class="blog-inner-container">

        <div class="blog-top">
            <form method="get" class="sort-form" action="">

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

        <?php
        $paged        = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $sort         = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
        $category     = isset($_GET['category']) ? $_GET['category'] : '';
        $tag          = isset($_GET['tag']) ? $_GET['tag'] : '';
        $posts_per_page = isset($_GET['posts_per_page']) && $_GET['posts_per_page'] != 'all' ? intval($_GET['posts_per_page']) : 5;

        $query_args = array(
            'posts_per_page' => $posts_per_page,
            'paged'          => $paged,
        );

        if ($category != '' && $category != 'all') {
            $query_args['category_name'] = $category;
        }

        if ($tag != '' && $tag != 'all') {
            $query_args['tag'] = $tag;
        }

        switch ($sort) {
            case 'title_asc':  $query_args['orderby'] = 'title'; $query_args['order'] = 'ASC';  break;
            case 'title_desc': $query_args['orderby'] = 'title'; $query_args['order'] = 'DESC'; break;
            case 'date_asc':   $query_args['orderby'] = 'date';  $query_args['order'] = 'ASC';  break;
            default:           $query_args['orderby'] = 'date';  $query_args['order'] = 'DESC'; break;
        }

        $custom_query = new WP_Query($query_args);
        ?>

        <?php if ($custom_query->have_posts()) : ?>
            <div class="blog-posts">
                <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
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
                                    $cats = get_the_category();
                                    if (!empty($cats)) echo esc_html($cats[0]->name);
                                    ?>
                                </div>
                                <div class="meta-divider"></div>
                                <div class="blog-post-tags">
                                    <?php
                                    $post_tags = get_the_tags();
                                    if ($post_tags) {
                                        foreach ($post_tags as $t) {
                                            echo '<a href="' . esc_url(get_tag_link($t->term_id)) . '">' . esc_html($t->name) . '</a>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="meta-divider"></div>
                                <div class="blog-post-date"><?php echo get_the_date(); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="pagination-container">
                <div class="pagination">
                    <?php echo paginate_links(array(
                        'total'     => $custom_query->max_num_pages,
                        'current'   => $paged,
                        'prev_text' => '&larr; Previous',
                        'next_text' => 'Next &rarr;',
                        'add_args'  => array(
                            'sort'          => $sort ?: null,
                            'category'      => $category ?: null,
                            'tag'           => $tag ?: null,
                            'posts_per_page'=> isset($_GET['posts_per_page']) ? $_GET['posts_per_page'] : null,
                        ),
                    )); ?>
                </div>
                <div class="posts-per-page">
                    <form method="get" class="posts-per-page-form" action="">
                        <label for="posts-per-page">Per page:</label>
                        <select name="posts_per_page" id="posts-per-page" onchange="this.form.submit()">
                            <?php foreach ([5, 10, 20, 50] as $n): ?>
                                <option value="<?php echo $n; ?>" <?php echo (isset($_GET['posts_per_page']) && $_GET['posts_per_page'] == $n) ? 'selected' : ''; ?>><?php echo $n; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>

        <?php else : ?>
            <div class="blog-no-results">
                <i class="fas fa-search"></i>
                <p>No posts found. Try adjusting your filters.</p>
            </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
</div>
