<?php
/**
 * Title: Search Bar
 * Slug: unluckytech/searchbar
 * Categories: text
 * Description: A section to display search results.
 */
?>

<!-- Search Bar -->
<div class="search-bar" id="searchBar">
    <form id="searchForm" method="get">
        <input type="text" id="searchInput" name="s" placeholder="Search projects..." value="<?php echo isset($_GET['s']) ? esc_attr($_GET['s']) : ''; ?>" />
        <div class="search-options">
            <div class="category-select">
                <label for="category">Category:</label>
                <select id="category" name="category" class="styled-select" multiple>
                    <option value="all"><?php _e('All Categories', 'textdomain'); ?></option>
                    <?php
                    // Fetch categories dynamically
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        echo '<option value="' . esc_attr($category->slug) . '"' . (isset($_GET['category']) && $_GET['category'] == $category->slug ? ' selected' : '') . '>' . esc_html($category->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="tag-select">
                <label for="tags">Tags:</label>
                <select id="tags" name="tags[]" class="styled-select" multiple>
                    <option value="all"><?php _e('All Tags', 'textdomain'); ?></option>
                    <?php
                    // Fetch tags dynamically
                    $tags = get_tags();
                    foreach ($tags as $tag) {
                        echo '<option value="' . esc_attr($tag->slug) . '"' . (isset($_GET['tags']) && in_array($tag->slug, $_GET['tags']) ? ' selected' : '') . '>' . esc_html($tag->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <button type="submit"><i class="fas fa-search"></i> Search</button>
    </form>
</div>
