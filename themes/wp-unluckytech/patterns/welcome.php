<?php
/**
 * Title: Welcome
 * Slug: unluckytech/welcome
 * Categories: featured
 * Description: A welcome section for the homepage.
 */
?>

<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/bg1.png' ) ); ?>","id":3838,"dimRatio":50,"overlayColor":"contrast","align":"full"} -->
<div class="wp-block-cover alignfull custom-cover-height">
    <span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim"></span>
    <img class="wp-block-cover__image-background wp-image-3838" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/bg1.png' ) ); ?>" data-object-fit="cover"/>
    <div class="wp-block-cover__inner-container">
        <div class="content-inner container-fluid">
            <div class="row welcome-container">
                <div class="col-12 col-md-6 video-wrapper">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ?si=We4i3UmGKV5htVFO" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div>
                <div class="col-12 col-md-6 text-wrapper">
                    <h2 class="welcome-heading">Welcome to UnluckyTech</h2>
                    <hr class="welcome-divider">
                    <p class="welcome-description">Still a work in progress!</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /wp:cover -->
