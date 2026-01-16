<?php
require "/var/www/html/wp-load.php";

$post_id = 851;
$p = get_post($post_id);
if (!$p) {
    fwrite(STDERR, "post not found\n");
    exit(1);
}

$content = $p->post_content;

// Grab the final "Begin Your Collection" block (section-white) at the end.
$re = '/<!-- wp:group \{"className":"section-white"[\s\S]*?<!-- \/wp:group -->\s*$/';
if (!preg_match($re, $content, $m)) {
    fwrite(STDERR, "begin block not found\n");
    exit(1);
}

$begin = $m[0];
$content_wo = preg_replace($re, '', $content, 1);

// Insert it right above the Curated Selection block (section-light).
$marker = '<!-- wp:group {"className":"section-light","layout":{"type":"constrained"}} -->';
if (strpos($content_wo, $marker) === false) {
    fwrite(STDERR, "curated marker not found\n");
    exit(1);
}

$new = str_replace($marker, $begin . "\n\n" . $marker, $content_wo);

wp_update_post([
    "ID" => $post_id,
    "post_content" => $new,
]);

echo "updated\n";

