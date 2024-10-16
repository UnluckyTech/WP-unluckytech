<?php
/**
 * Title: Welcome
 * Slug: unluckytech/welcome
 * Categories: featured
 * Description: A welcome section for the homepage.
 */
?>

<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/home/bg1.webp' ) ); ?>","id":3838,"dimRatio":50,"overlayColor":"contrast","align":"full"} -->
<div class="wp-block-cover alignfull custom-cover-height">
    <span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim"></span>
    
    <img class="wp-block-cover__image-background wp-image-3838" alt="" 
         src="<?php echo esc_url( get_theme_file_uri( 'assets/images/home/bg1.webp' ) ); ?>"
         srcset="<?php echo esc_url( get_theme_file_uri( 'assets/images/home/bg1-mobile.webp' ) ); ?> 768w, 
                 <?php echo esc_url( get_theme_file_uri( 'assets/images/home/bg1.webp' ) ); ?> 1200w"
         sizes="(max-width: 768px) 768px, 1200px"
         data-object-fit="cover" />

    <div class="wp-block-cover__inner-container">
        <div class="content-inner container-fluid">
            <div class="row welcome-container">
                <div class="col-12 col-md-6 video-wrapper" style="position: relative;">
                    <!-- Lazy load placeholder image for non-critical content -->
                    <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/placeholder.webp' ) ); ?>" 
                         alt="Video Placeholder" style="width: 100%; height: auto;" loading="lazy">
                </div>
                <div class="col-12 col-md-6 text-wrapper">
                    <h2 class="welcome-heading">Welcome to UnluckyTech</h2>
                    <hr class="welcome-divider">
                    <p class="welcome-description">Explore resources and services to support your technical projects and inquiries.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /wp:cover -->
