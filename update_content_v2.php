<?php
require "/var/www/html/wp-load.php";

$post_id = 851;
$post = get_post($post_id);

if (!$post) {
    die("Post not found");
}

$content = $post->post_content;

// Remove the section that has "Questions about our opals"
// We'll find the start of the block and the end of the block.
$search_text = "Questions about our opals";
$pos = strpos($content, $search_text);

if ($pos !== false) {
    // Find the start of the group block before this text
    $start_pos = strrpos(substr($content, 0, $pos), '<!-- wp:group');
    
    // Find the end of the group block after this text
    // We need to match the closing tag for this specific block.
    // This is tricky with nested blocks, but this one doesn't seem nested.
    $end_pos = strpos($content, '<!-- /wp:group -->', $pos);
    
    if ($start_pos !== false && $end_pos !== false) {
        $end_pos += strlen('<!-- /wp:group -->');
        $content = substr_replace($content, '', $start_pos, $end_pos - $start_pos);
    }
}

// Ensure "Curated Selection" is changed to "Begin Your Collection" (if not already done)
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
