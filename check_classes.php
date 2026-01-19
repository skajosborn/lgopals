<?php
require "/var/www/html/wp-load.php";
$post = get_page_by_path('contact');
if (!$post) {
    echo "Page not found by path 'contact'";
    exit;
}
// Manually set up the global $post and queried object for testing body_class
global $wp_query;
$wp_query->queried_object = $post;
$wp_query->queried_object_id = $post->ID;
$wp_query->is_page = true;

$classes = get_body_class();
echo implode(' ', $classes);
