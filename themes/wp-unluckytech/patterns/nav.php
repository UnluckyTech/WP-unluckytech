<?php
/**
 * Title: Navigation
 * Slug: unluckytech/nav
 * Categories: text, featured
 * Description: This would be used for navigation of the website.
 */
?>

<div class="search-bar" id="searchBar">
    <form method="get" class="filter-form" action="" onsubmit="return handleSubmit();">
        <input type="text" id="searchInput" name="s" placeholder="Search projects..." />
        <div class="search-options">
            <div class="category-select">
                <label for="category-list">Category:</label>
                <div class="category-box">
                    <ul id="category-list" class="styled-select">
                        <li>
                            <input type="radio" name="category" id="category-all" value="all" class="styled-select" checked>
                            <label for="category-all"><?php _e('All', 'textdomain'); ?></label>
                        </li>
                        <?php
                        $categories = get_categories();
                        foreach ($categories as $category) {
                            echo '<li>
                                    <input type="radio" name="category" id="category-' . esc_attr($category->slug) . '" value="' . esc_attr($category->slug) . '"' . (isset($_GET['category']) && $_GET['category'] == $category->slug ? ' checked' : '') . '>
                                    <label for="category-' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</label>
                                </li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="tag-select">
                <label for="tag-list">Tags:</label>
                <div class="tag-box">
                    <ul id="tag-list" class="styled-select">
                        <li>
                            <input type="radio" name="tag" id="tag-all" value="all" class="styled-select" checked>
                            <label for="tag-all"><?php _e('All', 'textdomain'); ?></label>
                        </li>
                        <?php
                        $tags = get_tags();
                        foreach ($tags as $tag) {
                            echo '<li>
                                    <input type="radio" name="tag" id="tag-' . esc_attr($tag->slug) . '" value="' . esc_attr($tag->slug) . '"' . (isset($_GET['tag']) && $_GET['tag'] == $tag->slug ? ' checked' : '') . '>
                                    <label for="tag-' . esc_attr($tag->slug) . '">' . esc_html($tag->name) . '</label>
                                </li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Apply Button -->
        <button type="submit" class="apply-button">Search</button>
    </form>
</div>



<script>
function handleSubmit() {
    const category = document.querySelector('input[name="category"]:checked').value;
    const tag = document.querySelector('input[name="tag"]:checked').value;

    // Construct the URL based on the selected category and tag
    let url = category !== 'all' ? `/category/${category}/` : '/';

    if (tag !== 'all') {
        url += `?tag=${tag}`;
    }

    // Redirect to the constructed URL
    window.location.href = url;
    return false; // Prevent default form submission
}
</script>
