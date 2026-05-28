<?php
/**
 * Title: Mobile Search
 * Slug: unluckytech/mobilesearch
 * Categories: text, featured
 * Description: Mobile search bar inside the off-screen menu.
 */
?>

<div class="off-screen-search-bar" id="offScreenSearchBar">
    <form id="mobileSearchForm" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <div class="mobile-search-input-group">
            <i class="fas fa-search mobile-search-icon"></i>
            <input type="text" id="mobileSearchInput" name="s"
                   placeholder="Search posts..."
                   value="<?php echo isset($_GET['s']) ? esc_attr($_GET['s']) : ''; ?>"
                   autocomplete="off" />
        </div>
        <button type="submit" class="mobile-search-submit">
            <i class="fas fa-arrow-right"></i>
        </button>
    </form>
</div>
