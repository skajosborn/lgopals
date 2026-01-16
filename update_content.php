<?php
require "/var/www/html/wp-load.php";

$post_id = 851;
$post = get_post($post_id);

if (!$post) {
    die("Post not found");
}

$content = $post->post_content;

// 1. Remove the "section-white" block containing "Begin Your Collection"
// Pattern to match the entire section-white block
$remove_pattern = '/<!-- wp:group \{"className":"section-white"[^}]*\} -->[\s\S]*?<!-- \/wp:group -->\s*/';
$content = preg_replace($remove_pattern, '', $content);

// 2. Change "Curated Selection" to "Begin Your Collection" in the "section-light" block
$content = str_replace('Curated Selection', 'Begin Your Collection', $content);

// Update the post
$result = wp_update_post([
    'ID' => $post_id,
    'post_content' => $content,
]);

if ($result) {
    echo "Successfully updated post 851";
} else {
    echo "Failed to update post";
}
