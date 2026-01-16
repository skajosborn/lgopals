<?php
require "/var/www/html/wp-load.php";
$post = get_post(851);
echo $post->post_content;
