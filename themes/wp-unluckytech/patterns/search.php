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
    $args = array(
        'post_type' => 'post', // Adjust if using custom post types
        's' => $search_query,
    );
    $search_results = new WP_Query($args);
    if ($search_results->have_posts()) {
        echo '<div class="search-wrapper">';
        echo '<div class="search-inner-container">';
        echo '<div class="search-title">';
        echo '<h1>Search Results for: ' . esc_html($search_query) . '</h1>';
        echo '<div class="search-divider"></div>';
        echo '</div>';
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
