<?php
require "/var/www/html/wp-load.php";

$post_id = 851;
$post = get_post($post_id);

if (!$post) {
    die("Post not found");
}

$content = $post->post_content;

// Find the "Identical Brilliance" section and replace its image
// The image block looks like this:
// <!-- wp:image {"sizeSlug":"large","linkDestination":"none","className":"feature-image"} -->
// <figure class="wp-block-image size-large feature-image"><img src="http://localhost:8088/wp-content/uploads/2025/01/opal2.png" alt="Comparing lab-grown and natural opals" /></figure>
// <!-- /wp:image -->

$pattern = '/(<!-- wp:image \{[^}]*\} -->\s*<figure[^>]*><img src=")[^"]*(" alt="Comparing lab-grown and natural opals" \/><\/figure>\s*<!-- \/wp:image -->)/';
$new_image_url = 'http://localhost:8088/wp-content/uploads/opalss.png';
$content = preg_replace($pattern, '$1' . $new_image_url . '$2', $content);

// Update the post
$result = wp_update_post([
    'ID' => $post_id,
    'post_content' => $content,
]);

if ($result) {
    echo "Successfully updated post 851 with opalss.png";
} else {
    echo "Failed to update post";
}
