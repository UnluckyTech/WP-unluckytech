<!-- wp:template-part {"slug":"header","area":"header","tagName":"header"} /-->

<?php

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        ?>
        <div class="single-post-container">
            <h1 class="single-post-title"><?php the_title(); ?></h1>
            <div class="single-post-meta">
                <span class="single-post-date"><?php echo get_the_date(); ?></span>
            </div>
            <div class="single-post-content">
                <?php the_content(); ?>
            </div>
        </div>
        <?php
    endwhile;
endif;

?>

<!-- wp:template-part {"slug":"footer","area":"footer","tagName":"footer"} /-->