<?php
/**
 * Opal Avant-Garde Theme Functions
 *
 * This is a block theme. Page structure / markup lives in `templates/` and `parts/`.
 * Keep this file for runtime logic only (enqueue, handlers, WooCommerce filters).
 */

// Enqueue Google Fonts and theme styles
function opal_enqueue_styles() {
    // Google Fonts - Elegant pairing
    wp_enqueue_style(
        "opal-google-fonts",
        "https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Montserrat:wght@300;400;500;600;700;800;900&display=swap",
        array(),
        null
    );
    
    // Parent theme
    wp_enqueue_style("twentytwentyfour-style", get_template_directory_uri() . "/style.css");
    
    // Child theme
    $child_style_path = get_stylesheet_directory() . "/style.css";
    $child_style_ver = file_exists($child_style_path) ? filemtime($child_style_path) : wp_get_theme()->get("Version");
    wp_enqueue_style(
        "opal-avant-garde-style",
        get_stylesheet_uri(),
        array("twentytwentyfour-style"),
        $child_style_ver
    );
}
add_action("wp_enqueue_scripts", "opal_enqueue_styles");

// Add custom body class
function opal_body_class($classes) {
    $classes[] = "opal-elegant-v3";
    return $classes;
}
add_filter("body_class", "opal_body_class");

// Remove default WordPress emoji scripts for cleaner output
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_print_styles", "print_emoji_styles");

// Preconnect to Google Fonts for performance
function opal_preconnect_fonts() {
    echo "<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">\n";
    echo "<link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>\n";
}
add_action("wp_head", "opal_preconnect_fonts", 1);

// Handle Contact Form Submission
add_action('template_redirect', function() {
    if (isset($_POST['opal_contact_submit'])) {
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);
        
        $to = get_option('admin_email');
        $subject = 'New Contact Form Submission from ' . $first_name;
        $body = "Name: $first_name $last_name\nEmail: $email\n\nMessage:\n$message";
        $headers = array('Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email);
        
        wp_mail($to, $subject, $body, $headers);
        
        $redirect_url = remove_query_arg('contact_success', $_SERVER['REQUEST_URI']);
        wp_redirect(add_query_arg('contact_success', '1', $redirect_url));
        exit;
    }
});

// (All page markup has been moved to templates; no content/nav rewrite filters.)

// Change 'What You Want!?' to 'You\'ve Selected' on the cart page
function opal_change_cart_attribute_label($translated_text, $text, $domain) {
    if ($text === 'What You Want!?') {
        $translated_text = 'You\'ve Selected';
    }
    return $translated_text;
}
add_filter('gettext', 'opal_change_cart_attribute_label', 20, 3);

// More robust way to change WooCommerce attribute labels
function opal_change_attribute_label($label, $name, $product) {
    if (strpos($label, 'What You Want') !== false) {
        return 'You\'ve Selected';
    }
    return $label;
}
add_filter('woocommerce_attribute_label', 'opal_change_attribute_label', 10, 3);

// Even more aggressive: filter the cart item data
function opal_change_cart_item_data_label($item_data, $cart_item) {
    foreach ($item_data as $key => $data) {
        if (strpos($data['name'], 'What You Want') !== false) {
            $item_data[$key]['name'] = 'You\'ve Selected';
        }
    }
    return $item_data;
}
add_filter('woocommerce_get_item_data', 'opal_change_cart_item_data_label', 10, 2);

// Filter the actual variation data output
function opal_change_variation_labels($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            if (strpos($key, 'What You Want') !== false) {
                unset($data[$key]);
                $data['You\'ve Selected'] = $value;
            }
        }
    }
    return $data;
}
// This is not a standard filter, but let's try gettext again with a higher priority and exact match check
function opal_final_gettext_fix($translated_text, $text, $domain) {
    $search = 'What You Want!?';
    if (trim($text) === $search || $text === 'What You Want!?') {
        return 'You\'ve Selected';
    }
    return $translated_text;
}
add_filter('gettext', 'opal_final_gettext_fix', 999, 3);
add_filter('ngettext', 'opal_final_gettext_fix', 999, 3);

// Force change attribute labels everywhere
add_filter('woocommerce_attribute_label', function($label, $name, $product) {
    if (strpos(strtolower($label), 'what you want') !== false) {
        return 'You\'ve Selected';
    }
    return $label;
}, 999, 3);

// Filter the cart item data names directly
add_filter('woocommerce_get_item_data', function($item_data, $cart_item) {
    foreach ($item_data as $key => $data) {
        if (isset($data['name']) && strpos(strtolower($data['name']), 'what you want') !== false) {
            $item_data[$key]['name'] = 'You\'ve Selected';
        }
    }
    return $item_data;
}, 999, 2);

// Fix for missing cart item thumbnails (e.g. Blue/Red Glitter Opal)
add_filter('woocommerce_cart_item_thumbnail', function($thumbnail, $cart_item, $cart_item_key) {
    $product = $cart_item['data'];
    
    // If thumbnail is empty or contains the default placeholder, try to get the parent image
    if (empty($thumbnail) || strpos($thumbnail, 'placeholder.png') !== false) {
        if ($product->is_type('variation')) {
            $parent_id = $product->get_parent_id();
            $parent_product = wc_get_product($parent_id);
            if ($parent_product) {
                $thumbnail = $parent_product->get_image();
            }
        }
    }
    
    // If we still have a placeholder or empty, and it's a variation, force the parent image
    if ($product->is_type('variation') && (empty($thumbnail) || strpos($thumbnail, 'placeholder.png') !== false)) {
        $thumbnail = get_the_post_thumbnail($product->get_parent_id(), 'woocommerce_thumbnail');
    }

    return $thumbnail;
}, 999, 3);

// Global fix for variation images falling back to parent image
add_filter('woocommerce_product_get_image', function($image, $product, $size, $attr, $placeholder) {
    if ($product && $product->is_type('variation')) {
        // If image is empty or a placeholder
        if (empty($image) || strpos($image, 'placeholder.png') !== false || strpos($image, 'src=""') !== false) {
            $parent_id = $product->get_parent_id();
            $parent_product = wc_get_product($parent_id);
            if ($parent_product) {
                return $parent_product->get_image($size, $attr, $placeholder);
            }
        }
    }
    return $image;
}, 999, 5);
