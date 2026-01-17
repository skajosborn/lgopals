<?php
require "/var/www/html/wp-load.php";

$post_id = 851;
$post = get_post($post_id);

if (!$post) {
    die("Post not found");
}

$content = $post->post_content;

// Find the "Ethical Origin" section and replace its image
// The image block looks like this:
// <!-- wp:image {"sizeSlug":"large","linkDestination":"none","className":"feature-image"} -->
// <figure class="wp-block-image size-large feature-image"><img src="http://localhost:8088/wp-content/uploads/2025/01/earth-opals.png" alt="Ethical lab-grown opals" /></figure>
// <!-- /wp:image -->

$pattern = '/(<!-- wp:image \{[^}]*\} -->\s*<figure[^>]*><img src=")[^"]*(" alt="Ethical lab-grown opals" \/><\/figure>\s*<!-- \/wp:image -->)/';
$new_image_url = 'http://localhost:8088/wp-content/uploads/earthopal.png';
$content = preg_replace($pattern, '$1' . $new_image_url . '$2', $content);

// Update the post
$result = wp_update_post([
    'ID' => $post_id,
    'post_content' => $content,
]);

if ($result) {
    echo "Successfully updated post 851 with earthopal.png";
} else {
    echo "Failed to update post";
}
