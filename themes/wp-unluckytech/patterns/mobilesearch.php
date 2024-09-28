<?php
/**
 * Title: Mobile Search
 * Slug: unluckytech/mobilesearch
 * Categories: text, featured
 * Description: This would be used for navigation of the website.
 */
?>


<div class="off-screen-search-bar" id="offScreenSearchBar">
    <form id="mobileSearchForm" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <input type="text" id="mobileSearchInput" name="s" placeholder="Search projects..." required />
        <button type="submit"><i class="fas fa-search"></i> Search</button>
    </form>
</div>