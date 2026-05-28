<?php
/**
 * Title: Navigation
 * Slug: unluckytech/nav
 * Categories: text, featured
 * Description: This would be used for navigation of the website.
 */
?>

<div class="search-bar" id="searchBar">
    <form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-bar-form">

        <div class="search-input-group">
            <i class="fas fa-search search-input-icon"></i>
            <input type="text" id="searchInput" name="s"
                   placeholder="Search posts..."
                   value="<?php echo isset($_GET['s']) ? esc_attr($_GET['s']) : ''; ?>"
                   autocomplete="off" />
        </div>

        <div class="search-filters">
            <select name="category" class="search-select">
                <option value="all">All Categories</option>
                <?php foreach (get_categories() as $cat): ?>
                    <option value="<?php echo esc_attr($cat->slug); ?>"
                        <?php echo (isset($_GET['category']) && $_GET['category'] === $cat->slug) ? 'selected' : ''; ?>>
                        <?php echo esc_html($cat->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="tag" class="search-select">
                <option value="all">All Tags</option>
                <?php foreach (get_tags() as $tag): ?>
                    <option value="<?php echo esc_attr($tag->slug); ?>"
                        <?php echo (isset($_GET['tag']) && $_GET['tag'] === $tag->slug) ? 'selected' : ''; ?>>
                        <?php echo esc_html($tag->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="search-submit">Search</button>
    </form>
</div>
