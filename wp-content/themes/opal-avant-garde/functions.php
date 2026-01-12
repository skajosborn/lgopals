<?php
add_action("wp_enqueue_scripts", function() {
    wp_enqueue_style("google-fonts", "https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=Inter:wght@300;400;600&display=swap", false);
});

// Remove core block patterns to clean up the editor
add_action("init", function() {
    remove_theme_support("core-block-patterns");
}, 11);

// Add custom body class for design hooks
add_filter("body_class", function($classes) {
    $classes[] = "opal-design-v1";
    return $classes;
});
