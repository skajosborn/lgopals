<?php
require "/var/www/html/wp-load.php";

$template_part_id = 19; // wp_template_part: header
$path = "/var/www/html/wp-content/themes/opal-avant-garde/parts/header.html";

if (!file_exists($path)) {
    fwrite(STDERR, "header.html not found at {$path}\n");
    exit(1);
}

$content = file_get_contents($path);
if ($content === false || trim($content) === '') {
    fwrite(STDERR, "header.html empty/unreadable\n");
    exit(1);
}

$updated = wp_update_post([
    "ID" => $template_part_id,
    "post_content" => $content,
], true);

if (is_wp_error($updated)) {
    fwrite(STDERR, $updated->get_error_message() . "\n");
    exit(1);
}

echo "updated\n";

