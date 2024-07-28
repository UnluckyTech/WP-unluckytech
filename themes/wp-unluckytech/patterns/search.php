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
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    $args = array(
        'post_type' => 'post', // Adjust if using custom post types
        's' => $search_query,
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

    $search_results = new WP_Query($args);

    if ($search_results->have_posts()) {
        echo '<div class="search-wrapper">';
        echo '<div class="search-inner-container">';
        echo '<div class="search-top">';
        echo '<div class="search-title">';
        echo '<h1>Search Results for: ' . esc_html($search_query) . '</h1>';
        echo '</div>';
        // Sort Form
        echo '<form method="get" class="sort-form" action="">';
        echo '<input type="hidden" name="s" value="' . esc_attr($search_query) . '">';
        echo '<label for="sort-by">Sort by:</label>';
        echo '<select name="sort" id="sort-by" onchange="this.form.submit()">';
        echo '<option value="date" ' . (isset($_GET['sort']) && $_GET['sort'] == 'date' ? 'selected' : '') . '>Date</option>';
        echo '<option value="title" ' . (isset($_GET['sort']) && $_GET['sort'] == 'title' ? 'selected' : '') . '>Title</option>';
        echo '<option value="popularity" ' . (isset($_GET['sort']) && $_GET['sort'] == 'popularity' ? 'selected' : '') . '>Popularity</option>';
        echo '</select>';
        echo '</form>';
        echo '</div>';
        echo '<div class="search-divider"></div>';
        echo '<div class="search-posts">';
        while ($search_results->have_posts()) {
            $search_results->the_post();
            echo '<a href="' . get_permalink() . '" class="search-post-link">';
            echo '<div class="search-post-card">';
            echo '<div class="search-post-image">';
            if (has_post_thumbnail()) {
                the_post_thumbnail('medium');
            } else {
                echo '<img src="https://via.placeholder.com/200" alt="Placeholder Image" />';
            }
            echo '</div>';
            echo '<div class="search-post-content">';
            echo '<div class="search-post-date">' . get_the_date() . '</div>';
            echo '<div class="search-post-title"><h2>' . get_the_title() . '</h2></div>';
            echo '<div class="search-post-excerpt">' . get_the_excerpt() . '</div>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
        }
        echo '</div>'; // .search-posts
        echo '<div class="search-divider"></div>'; // Divider after search results
        echo '<div class="pagination">';
        // Pagination
        echo paginate_links(array(
            'total' => $search_results->max_num_pages,
            'current' => $paged,
            'prev_text' => __('« Previous', 'textdomain'),
            'next_text' => __('Next »', 'textdomain'),
        ));
        echo '</div>';
        echo '</div>'; // .search-inner-container
        echo '</div>'; // .search-wrapper
    } else {
        echo '<div class="search-wrapper">';
        echo '<div class="search-inner-container">';
        echo '<div class="search-title">';
        echo '<h1>No results found for: ' . esc_html($search_query) . '</h1>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    wp_reset_postdata();
} else {
    echo '<div class="search-wrapper">';
    echo '<div class="search-inner-container">';
    echo '<div class="search-title">';
    echo '<h1>Please enter a search query.</h1>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>
