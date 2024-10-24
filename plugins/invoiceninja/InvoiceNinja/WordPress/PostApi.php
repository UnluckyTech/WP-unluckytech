<?php

/**
 * @package Invoice Ninja
 */

namespace InvoiceNinja\WordPress;

use \InvoiceNinja\Api\InvoiceApi;
use \InvoiceNinja\Api\ClientApi;

class PostApi
{
    public $post_types = [];

    public function register()
    {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueStyles' ] );

        if ( ! empty( $this->post_types) ) 
        {
            add_action( 'init', [ $this, 'init' ] );
        }

        add_filter( 'the_content', [ $this, 'addDynamicContent' ] );
    }

    public function init()
    {
        if ( ! session_id() ) {
            session_start();
        }

        foreach ($this->post_types as $type)
        {            
            $product_label = get_option( 'invoiceninja_product_label', 'Product' );
            $slug = strtolower( $product_label );

            $query_args = array(
                'post_type' => 'invoiceninja_product',
                'posts_per_page' => 5,
                'order_by' => 'title', // date, menu_order
                'order' => 'ASC',
            );
            $query = new \WP_Query($query_args);
                
            register_post_type(
                $type['id'], [
                    'labels' => [
                        'name' => $type['name'],
                        'singular_name' => $type['singular_name'],
                    ],
                    'hierarchical' => true,
                    'show_in_menu' => $query->have_posts(),
                    'public' => true,
                    'has_archive' => true,
                    'menu_icon' => 'dashicons-products',
                    'show_in_rest' => true,
                    'rewrite' => [ 'slug' => sanitize_title( $slug ) ],
                    /*
                    'capabilities' => [
                        'edit_post' => 'edit_post',
                        'read_post' => 'read_post',
                        'delete_post' => 'delete_post',
                        'edit_posts' => 'edit_posts',
                        'edit_others_posts' => 'edit_others_posts',
                        'publish_posts' => 'publish_posts',
                        'read_private_posts' => 'read_private_posts',
                        'delete_posts' => 'delete_posts',
                        'delete_private_posts' => 'delete_private_posts',
                        'delete_published_posts' => 'delete_published_posts',
                        'delete_others_posts' => 'delete_others_posts',
                        'edit_private_posts' => 'edit_private_posts',
                        'edit_published_posts' => 'edit_published_posts',
                        'create_posts' => 'create_posts', 
                    ],
                    */
                ],
            );       
        }

        flush_rewrite_rules();

        add_shortcode('invoiceninja_purchase', [ $this, 'purchaseShortcode' ] );
        add_shortcode('invoiceninja_checkout', [ $this, 'checkoutShortcode' ] );
        add_shortcode('invoiceninja_client_portal', [ $this, 'clientPortalShortcode' ] );
    }

    public function addDynamicContent($content)
    {
        // Fix for "Updating failed. The response is not a valid JSON response." when editing posts
        if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) 
        {
            return $content;
        }

        $profile = json_decode( get_option( 'invoiceninja_profile' ) );

        if ( is_singular( 'invoiceninja_product' ) ) {
            $post_id = get_the_ID();
            $product_id = get_post_meta( $post_id, 'product_id', true );
            $price = get_post_meta( $post_id, 'price', true );
            $online_purchases = get_option( 'invoiceninja_online_purchases' );

            $price = '<p>' . \InvoiceNinja\Utils\Formatting::formatMoney( $price ) . '</p>';

            if ( $online_purchases == 'single' || $online_purchases == 'multiple' ) {
                $content = $price . '[invoiceninja_purchase product_id="' . $product_id . '"]' . $content;
            }
        }

        if ( isset( $_SESSION['invoiceninja_cart'] ) && ! empty( $_SESSION['invoiceninja_cart'] ) ) {
            $cart = $_SESSION['invoiceninja_cart'];
            $color = '#0000EE';

            if ($profile->settings->primary_color) {
                $color = $profile->settings->primary_color;
            }    

            $str = '<div class="invoiceninja-cart">
                    <div class="cart-header" style="background-color: ' . $color . ';">';

            /*
            if ( count($cart) == 1 ) {
                $str .= '1 item in cart';
            } else {
                $str .= count($cart) . ' items in cart';
            }
            */

            $str .= '&nbsp;';

            $str .= '[invoiceninja_checkout]</div>';
            $str .= '<table>
                     <form method="POST" action="" id="invoiceninja_cart">
                     <input type="hidden" id="cart_action" name="cart_action" value=""/>
                     <input type="hidden" id="product_id" name="product_id"/>
                     <input type="hidden" id="quantity" name="quantity"/>';
            $str .= wp_nonce_field('invoiceninja_checkout', 'invoiceninja_nonce');                

            foreach ( $_SESSION['invoiceninja_cart'] as $product_id => $quantity ) {

                $args = [
                    'post_type' => 'invoiceninja_product',
                    'meta_query' => [
                        [
                            'key' => 'product_id',
                            'value' => $product_id,
                            'compare' => 'EQUAL',
                        ],
                    ],
                ];
                
                $query = new \WP_Query( $args );
                
                if ( $query->have_posts() ) {
                    $query->the_post();
                    $post_id = get_the_ID();
                    
                    $url = get_permalink();
                    $product = get_the_title();
                    $price = get_post_meta( $post_id, 'price', true );
                    $max_quantity = get_post_meta( $post_id, 'max_quantity', true );
                    $in_stock_quantity = get_post_meta( $post_id, 'in_stock_quantity', true );

                    $str .= '<tr><td style="width: 0px">';

                    $image_url = '';
                    if ( has_post_thumbnail( $post_id ) ) {
                        $image_url = get_the_post_thumbnail_url( $post_id, 'medium' );
                        $str .= '<img src="' . esc_url( $image_url ) . '"/>';
                    }
                    
                    $str .= '</td>
                        <td style="width: 30%"><a href="' . esc_url( $url ) . '">' . $product . '</a><br/>' . \InvoiceNinja\Utils\Formatting::formatMoney( $price ) . '</td>
                        <td><select onchange="in_update_cart(\'' . $product_id . '\', this.value)">';

                    $max = 99;
                    if ($max_quantity > 0) {
                        $max = min( $max, $max_quantity );
                    }
                    if ($profile->track_inventory) {
                        $max = min( $max, $in_stock_quantity );
                    }
                    for ($i=1; $i<=$max; $i++) {
                        $str .= '<option value="' . $i . '"' . ($quantity == $i ? 'SELECTED' : '') . '>' . $i . '</option>';
                    }

                    $str .= '</select></td>
                        <td>' . \InvoiceNinja\Utils\Formatting::formatMoney( $quantity * $price ) . '</td>
                        <td>
                            <img style="height: 16px; width: 16px; cursor: pointer;" onclick="in_update_cart(\'' . $product_id . '\', 0)" src="data:image/png;base64,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AGr0SZUECBAgQCBUQAAI5XQYAQIECBCoISAA1OiTKgkQIECAQKiAABDK6TACBAgQIFBDQACo0SdVEiBAgACBUAEBIJTTYQQIECBAoIaAAFCjT6okQIAAAQKhAgJAKKfDCBAgQIBADQEBoEafVEmAAAECBEIFBIBQTocRIECAAIEaAgJAjT6pkgABAgQIhAoIAKGcDiNAgAABAjUEBIAafVIlAQIECBAIFRAAQjkdRoAAAQIEaggIADX6pEoCBAgQIBAqIACEcjqMAAECBAjUEBAAavRJlQQIECBAIFRAAAjldBgBAgQIEKghIADU6JMqCRAgQIBAqIAAEMrpMAIECBAgUENAAKjRJ1USIECAAIFQAQEglNNhBAgQIECghoAAUKNPqiRAgAABAqECAkAop8MIECBAgEANAQGgRp9USYAAAQIEQgUEgFBOhxEgQIAAgRoCAkCNPqmSAAECBAiECggAoZwOI0CAAAECNQQEgBp9UiUBAgQIEAgVEABCOR1GgAABAgRqCAgANfqkSgIECBAgECogAIRyOowAAQIECNQQEABq9EmVBAgQIEAgVEAACOV0GAECBAgQqCEgANTokyoJECBAgECogAAQyukwAgQIECBQQ0AAqNEnVRIgQIAAgVABASCU02EECBAgQKCGgABQo0+qJECAAAECoQICQCinwwgQIECAQA0BAaBGn1RJgAABAgRCBQSAUE6HESBAgACBGgICQI0+qZIAAQIECIQKCAChnA4jQIAAAQI1BASAGn1SJQECBAgQCBUQAEI5HUaAAAECBGoICAA1+qRKAgQIECAQKiAAhHI6jAABAgQI1BAQAGr0SZUECBAgQCBUQAAI5XQYAQIECBCoISAA1OiTKgkQIECAQKiAABDK6TACBAgQIFBDQACo0SdVEiBAgACBUAEBIJTTYQQIECBAoIaAAFCjT6okQIAAAQKhAgJAKKfDCBAgQIBADQEBoEafVEmAAAECBEIF/g+K0kpbOKPy1AAAAABJRU5ErkJggg==">
                        </tr>';
                }
            }


            $str .= '</form></table>';
                       
            $content = $str . '</div>' . $content;
        }
        
        //$content = wp_json_encode( $_SESSION['invoiceninja_cart'] ) . $content;

        return $content;
    }

    public function enqueueStyles()
    {
        global $post;

        // Check if $post is set and not null
        if ($post && $post->post_type == 'invoiceninja_product') {
            wp_enqueue_style('product-styles', plugins_url('/../../assets/css/product.css', __FILE__), [], time());
            
            $custom_css = esc_attr(get_option('invoiceninja_product_css'));
            wp_add_inline_style('product-styles', $custom_css);
        }
    
        if ($post && get_the_ID() == get_option('invoiceninja_product_page_id')) {
            wp_enqueue_style('products-styles', plugins_url('/../../assets/css/products.css', __FILE__), [], time());
    
            $color = '#0000EE';
            $profile = json_decode(get_option('invoiceninja_profile'));
            if ($profile->settings->primary_color) {
                $color = $profile->settings->primary_color;
            }
    
            $custom_css = esc_attr(get_option('invoiceninja_products_css')) . '
                a:hover div.divider {
                    border-color: ' . esc_attr($color) . ';
                }
            ';
    
            wp_add_inline_style('products-styles', $custom_css);
        }
    
        if (!is_admin()) {
            wp_enqueue_style('frontend-styles', plugins_url('/../../assets/css/frontend.css', __FILE__), [], time());
            wp_enqueue_script('frontend-scripts', plugins_url('/../../assets/js/frontend.js', __FILE__), [], time(), false);
        }
    }

    public function setPostTypes($types)
    {
        $this->post_types = $types;

        return $this;
    }

    public function purchaseShortcode($atts)
    {
        $is_single = get_option('invoiceninja_online_purchases') == 'single';
        $is_multiple = get_option('invoiceninja_online_purchases') == 'multiple';
        $profile = json_decode( get_option( 'invoiceninja_profile' ) );

        $atts = shortcode_atts(array(
            'product_id' => '',
        ), $atts, 'invoiceninja_purchase');

        $in_stock = true;

        if ( $profile->track_inventory ) {
            $args = [
                'post_type' => 'invoiceninja_product',
                'meta_query' => [
                    [
                        'key' => 'product_id',
                        'value' => $atts['product_id'],
                        'compare' => 'EQUAL',
                    ],
                ],
            ];
            
            $query = new \WP_Query( $args );
            
            if ( $query->have_posts() ) {
                $query->the_post();
                $post_id = get_the_ID();
                $max_quantity = get_post_meta( $post_id, 'max_quantity', true );
                $in_stock_quantity = get_post_meta( $post_id, 'in_stock_quantity', true );
                $in_stock = $in_stock_quantity > 0;
            }    
        }

        if ( isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['invoiceninja_purchase']) ) {
            if ( isset($_POST['invoiceninja_nonce']) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['invoiceninja_nonce'] ) ), 'invoiceninja_purchase_' . esc_attr( $atts['product_id'] ) ) ) {
                $product_id = isset($_POST['product_id']) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : false;
                if ($product_id) {
                    if ($is_single) {
                        if ( $invoice = InvoiceApi::create( [$product_id => 1] ) ) {
                            wp_redirect($invoice->invitations[0]->link);    
                            exit;
                        }                                            
                    } else {
                        if ( ! isset( $_SESSION['invoiceninja_cart'] ) ) {
                            $_SESSION['invoiceninja_cart'] = [];
                        }

                        if (isset($_SESSION['invoiceninja_cart'][$product_id])) {
                            $_SESSION['invoiceninja_cart'][$product_id] = sanitize_text_field($_SESSION['invoiceninja_cart'][$product_id]) + 1;

                            $max = 99;
                            if ($max_quantity > 0) {
                                $max = min( $max, $max_quantity );
                            }
                            if ($profile->track_inventory) {
                                $max = min( $max, $in_stock_quantity );
                            }
                            $_SESSION['invoiceninja_cart'][$product_id] = min( $max, sanitize_text_field( $_SESSION['invoiceninja_cart'][$product_id] ) );
                        } else {
                            $_SESSION['invoiceninja_cart'][$product_id] = 1;
                        }
                    }
                }
                
                if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
                    $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) . sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
                    wp_safe_redirect($current_url);
                }
            }
        }
    
        ob_start();
        
        if ( $is_single || $is_multiple ) {
            if ( $in_stock ) {
                ?>
                <form method="post" action="">
                    <?php wp_nonce_field('invoiceninja_purchase_' . esc_attr($atts['product_id']), 'invoiceninja_nonce'); ?>
                    <input type="hidden" name="product_id" value="<?php echo esc_attr($atts['product_id']); ?>">
                    <button type="submit" name="invoiceninja_purchase"><?php echo ($is_single ? 
                        esc_attr( get_option( 'invoiceninja_buy_now_label', 'Buy Now' ) ) : 
                        esc_attr( get_option( 'invoiceninja_add_to_cart_label', 'Add to Cart' ) ) ) ?></button>
                </form>
                <?php
            } else {
                echo '<div>' . esc_attr( get_option( 'invoiceninja_out_of_stock_label', 'Out of Stock' ) ) . '</div>';
            }
        }

        return ob_get_clean();    
    }

    public function checkoutShortcode()
    {
        if ( isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['cart_action'] ) ) {
            if ( isset($_POST['invoiceninja_nonce']) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['invoiceninja_nonce'] ) ), 'invoiceninja_checkout' ) ) {
                $action = sanitize_text_field( wp_unslash( $_POST['cart_action'] ) );
                if ($action == 'update') {
                    $product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : false;
                    $quantity = isset( $_POST['quantity'] ) ? sanitize_text_field( wp_unslash( $_POST['quantity'] ) ) : false;

                    if ( $quantity == 0 ) {
                        unset( $_SESSION['invoiceninja_cart'][$product_id] );
                    } else {
                        $_SESSION['invoiceninja_cart'][$product_id] = $quantity;
                    }

                    if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
                        $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) . sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
                        wp_safe_redirect($current_url);
                    }
                } else if ($action == 'checkout') {
                    if ( isset( $_SESSION['invoiceninja_cart'] ) && $invoice = InvoiceApi::create( $_SESSION['invoiceninja_cart'] ) ) {
                        unset( $_SESSION['invoiceninja_cart'] );
                        wp_redirect($invoice->invitations[0]->link);
                        exit;
                    }
                }
            }
        }            

        ob_start();
        
        ?>
        <form method="post" action="">
            <?php wp_nonce_field('invoiceninja_checkout', 'invoiceninja_nonce'); ?>
            <input type="hidden" name="cart_action" value="checkout"/>
            <button type="submit"><?php echo esc_attr( get_option( 'invoiceninja_checkout_label', 'Checkout' ) ); ?></button>
        </form>
        <?php

        return ob_get_clean();    
    }

    public function clientPortalShortcode($atts)
    {
        $atts = shortcode_atts(array(
            'label' => 'Client Portal',
            'sso' => 'false',
        ), $atts, 'invoiceninja_client_portal');

        if ( isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['invoiceninja_client_portal'] ) ) {
            if ( isset($_POST['invoiceninja_nonce']) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['invoiceninja_nonce'] ) ), 'invoiceninja_client_portal' ) ) {
                $user = wp_get_current_user();
                $client = ClientApi::find( $user->user_email );

                foreach ( $client->contacts as $contact ) {
                    if ( $contact->email == $user->user_email ) {
                        $url = get_option( 'invoiceninja_api_url ');
                        $url = rtrim( $url, '/' );
                        $url = rtrim( $url, 'api/v1' );
                        $url = rtrim( $url, '/' );                        
                        if (! $url) {
                            $url = 'https://invoicing.co';
                        }
                        $url .= '/client/key_login/' . $contact->contact_key;
                        if ($atts['sso'] == 'true') {
                            $url .= '?client_hash=' . $client->client_hash;
                        }
                        wp_redirect($url);
                        exit;
                    }
                }
            }
        }            

        ob_start();
        
        ?>
        <form method="post" action="">
            <?php wp_nonce_field('invoiceninja_client_portal', 'invoiceninja_nonce'); ?>
            <button type="submit" name="invoiceninja_client_portal"><?php echo esc_attr( $atts['label'] ) ?></button>
        </form>
        <?php

        return ob_get_clean();    
    }
}