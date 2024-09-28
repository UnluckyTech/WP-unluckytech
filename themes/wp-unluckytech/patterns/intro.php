<?php
/**
 * Title: Intro
 * Slug: unluckytech/intro
 * Categories: featured
 * Description: A intro section for the homepage.
 */
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/bg1.png' ) ); ?>","id":3838,"dimRatio":50,"overlayColor":"contrast","align":"full"} -->
<div class="wp-block-cover alignfull custom-cover-height">
    <div class="intro-overlay"></div>
    <img class="wp-block-cover__image-background wp-image-3838" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/bg1.png' ) ); ?>" data-object-fit="cover"/>
    <div class="wp-block-cover__inner-container">
        <div class="content-inner container-fluid">
            <h2 class="intro-heading">About Me</h2>
        </div>
    </div>
</div>
<!-- /wp:cover -->
