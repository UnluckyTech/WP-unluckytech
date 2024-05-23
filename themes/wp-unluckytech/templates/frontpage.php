<?php
/*
Template Name: Frontpage
*/
?>



<!DOCTYPE html>
<html <?php language_attributes(); ?>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <style>
        .particle-network-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: -1; /* Ensure it's behind other elements */
        }
        #particle-canvas {
            display: block;
            width: 100%;
            height: 100%;
        }
        .navigation-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: white;
            padding: 10px 30px; /* Adjust padding */
            z-index: 1; /* Ensure it is above the particle animation */
            position: relative;
            width: 100%;
            height: 60px; /* Ensure height is similar to reference */
            box-sizing: border-box; /* Include padding in the width */
            border-bottom: 1px solid #e0e0e0; /* Optional: add a border for a cleaner look */
        }
        .navigation-wrapper .logo {
            display: flex;
            align-items: center;
            height: 100%;
        }
        .wp-block-navigation {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            padding: 0;
        }
        .wp-block-navigation a {
            margin: 0 15px;
            font-size: 14px; /* Adjust font size */
            font-weight: bold;
            text-decoration: none;
            color: black;
        }
        .search-icon {
            display: flex;
            align-items: center;
            height: 100%;
        }
        .search-icon i {
            font-size: 18px; /* Adjust the size of the icon */
            cursor: pointer;
            color: black; /* Match the color of the navigation items */
        }
        .content-wrapper {
            max-width: 80%; /* Set a maximum width for the content area */
            margin: 0 auto; /* Center the content area */
            padding: 20px; /* Adjust padding as needed */
            background-color: white; /* Ensure background color is applied */
            box-sizing: border-box; /* Ensure padding is included in the width */
        }
    </style>
</head>
<body <?php body_class(); ?>

    <!-- Particle animation container -->
    <div class="particle-network-animation">
        <div class="glow glow-1"></div>
        <div class="glow glow-2"></div>
        <div class="glow glow-3"></div>
        <canvas id="particle-canvas"></canvas>
    </div>

    <!-- Navigation wrapper -->
    <div class="navigation-wrapper">
        <!-- Logo -->
        <div class="logo">
            <img src="" alt="UnluckyTECH" />
        </div>

        <!-- Navigation -->
        <div class="wp-block-navigation">
            <a href="https://unluckytech.local/about">About</a>
            <a href="https://docs.unluckytech.com/">Docs</a>
            <a href="https://forums.unluckytech.com/">Forum</a>
            <a href="https://store.unluckytech.com/">Store</a>
            <a href="https://youtube.com/@UnluckyTechs">Videos</a>
        </div>

        <!-- Search Icon -->
        <div class="search-icon">
            <i class="fas fa-search"></i> <!-- Font Awesome search icon -->
        </div>
    </div>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Main content -->
        <div class="wp-block-group">
            <!-- wp:heading {"level":1} -->
            <h1 class="wp-block-heading">Heading 1</h1>
            <!-- /wp:heading -->

            <!-- wp:heading -->
            <h2 class="wp-block-heading">Heading 2</h2>
            <!-- /wp:heading -->

            <!-- wp:heading {"level":3} -->
            <h3 class="wp-block-heading">Heading 3</h3>
            <!-- /wp:heading -->

            <!-- wp:heading {"level":4} -->
            <h4 class="wp-block-heading">Heading 4</h4>
            <!-- /wp:heading -->

            <!-- wp:heading {"level":5} -->
            <h5 class="wp-block-heading">Heading 5</h5>
            <!-- /wp:heading -->

            <!-- wp:heading {"level":6} -->
            <h6 class="wp-block-heading">Heading 6</h6>
            <!-- /wp:heading -->

            <!-- wp:paragraph -->
            <p></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons -->
            <div class="wp-block-buttons">
                <!-- wp:button -->
                <div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Button</a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->

            <!-- wp:paragraph -->
            <p>This is an example page. It's different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph -->
            <p>This is an example page. It's different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>
            <!-- /wp:paragraph -->

            <!-- wp:quote -->
            <blockquote class="wp-block-quote">
                <!-- wp:paragraph -->
                <p>Hi there! I'm a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like piña coladas. (And gettin' caught in the rain.)</p>
                <!-- /wp:paragraph -->
            </blockquote>
            <!-- /wp:quote -->

            <!-- wp:paragraph -->
            <p>...or something like this:</p>
            <!-- /wp:paragraph -->

            <!-- wp:quote -->
            <blockquote class="wp-block-quote">
                <!-- wp:paragraph -->
                <p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p>
                <!-- /wp:paragraph -->
            </blockquote>
            <!-- /wp:quote -->

            <!-- wp:paragraph -->
            <p>As a new WordPress user, you should go to <a href="http://unluckytech.local/wp-admin/">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>
            <!-- /wp:paragraph -->
        </div>
    </div>

    <!-- Footer scripts -->
    <?php wp_footer(); ?>
    <script src="<?php echo get_template_directory_uri(); ?>/js/particle-network.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</body>
</html>
