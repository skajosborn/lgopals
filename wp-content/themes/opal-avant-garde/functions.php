<?php
/**
 * Opal Avant-Garde Theme Functions
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
    wp_enqueue_style(
        "opal-avant-garde-style",
        get_stylesheet_uri(),
        array("twentytwentyfour-style"),
        wp_get_theme()->get("Version")
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

// Replace text and images in content
function opal_replace_content_text($content) {
    if (is_front_page()) {
        // --- MASTER GRADE SECTION ---
        $new_title = 'The Molecular Standard of the Future';
        $new_text = '<p style="color:#4a4a4a;">Our lab-grown opals aren’t an alternative — they’re the evolution. By replicating the Earth’s pressure cycles in a controlled environment, we create opal that is optically and molecularly identical to mined stone, yet structurally superior in every measurable way.</p>' .
                    '<p style="color:#4a4a4a;">Engineered for absolute consistency and atomic-level brilliance, each block delivers flawless performance for precision lapidary, bespoke horology, and high-end craftsmanship. No fractures. No unpredictability. No environmental cost.</p>' .
                    '<p style="color:#4a4a4a;">Same fire. Same beauty.<br>Zero footprint. Total integrity. The new standard for the modern craftsman.</p>';

        // Use more specific search strings to avoid recursive replacements
        $title_searches = [
            'Ethereal Fire',
            'The Future of Fire',
            'The Future of Luxury',
            'Master Grade Material'
        ];
        $content = str_replace($title_searches, $new_title, $content);
        
        $content = str_replace('High-Performance Material for the Master Lapidary', '', $content);
        
        $content = str_replace('We believe luxury should not cost the earth. Our lab-grown opals possess the same molecular brilliance as mined gems—without the ecological footprint. Each stone is hand-selected for designers who demand both perfection and integrity.', 
                               $new_text, $content);
        $content = str_replace('Engineered for absolute consistency and atomic-level brilliance. Our lab-grown opal blocks are the superior choice for high-precision lapidary and bespoke horology. By replicating the Earth\'s natural pressure cycles in a controlled environment, we eliminate the structural flaws inherent in mined gems. Same brilliance. Zero footprint. Total perfection.',
                               $new_text, $content);

        // --- TESTIMONIAL REMOVAL ---
        // Remove the Austin TX testimonial section
        $content = preg_replace('/<section[^>]*class="[^"]*(testimonial-section|section-graphite)[^"]*"[^>]*>.*?MASTER JEWELER, AUSTIN TX.*?<\/section>/is', '', $content);
        $content = preg_replace('/<div[^>]*class="[^"]*(testimonial-section|section-graphite)[^"]*"[^>]*>.*?MASTER JEWELER, AUSTIN TX.*?<\/div>/is', '', $content);
        
        // Also catch it if it's just a blockquote with that text
        $content = preg_replace('/<blockquote[^>]*>.*?MASTER JEWELER, AUSTIN TX.*?<\/blockquote>/is', '', $content);

        // --- ETHICAL ORIGIN SECTION ---
        $content = str_replace('Ethical Origin', 'Integrity in Every Carat', $content);
        $content = str_replace('Created in controlled environments with zero mining impact on communities or ecosystems. Our lab-grown opals offer the same stunning beauty without the environmental cost of traditional mining operations.',
                               'The era of destructive extraction is over. We offer a path forward where science eliminates the need for earth-shifting machinery. Our opals represent the apex of sustainable luxury—molecularly identical to mined stones, but ethically produced for the modern creator who values the future of our planet.', $content);
        
        // Image replacements
        $old_img = 'wp-content/uploads/2025/01/earth-opals.png';
        $new_img = 'wp-content/uploads/opalearth.png';
        $content = str_replace($old_img, $new_img, $content);
    }
    
    return $content;
}
add_filter('the_content', 'opal_replace_content_text');

// Also apply replacements to blocks for block themes
function opal_replace_block_text($block_content, $block) {
    if (is_front_page() && !is_admin()) {
        $new_title = 'The Molecular Standard of the Future';
        $replacements = [
            'Ethereal Fire' => $new_title,
            'The Future of Fire' => $new_title,
            'The Future of Luxury' => $new_title,
            'Master Grade Material' => $new_title,
            'Designer Lab-Grown Opals for the Discerning Creator' => '',
            'Ethical Origin' => 'Integrity in Every Carat'
        ];
        foreach ($replacements as $old => $new) {
            $block_content = str_replace($old, $new, $block_content);
        }
    }
    return $block_content;
}
add_filter('render_block', 'opal_replace_block_text', 10, 2);

// Replace FAQ page content with unified FAQ section
function opal_unified_faq_content($content) {
    if (is_page('faq') || is_page('faqs') || is_page('frequently-asked-questions')) {
        $faq_html = '
<style>
/* Hide the WordPress page title and make page seamless */
body main > .wp-block-group:first-child,
body main h1.wp-block-post-title,
body .entry-header,
body main > div:first-child > h1 {
    display: none !important;
}
body, body .wp-site-blocks, body main {
    background: #ffffff !important;
}
</style>
<div style="background:#ffffff; padding:140px 10vw 100px; min-height:100vh;">
    <h1 style="color:#000000; font-size:clamp(1.8rem,4vw,2.8rem); font-weight:700; text-transform:uppercase; letter-spacing:0.2em; margin-bottom:1rem; font-family:Montserrat,sans-serif; line-height:1.2;">Frequently Asked Questions</h1>
    <p style="color:#333333; font-size:1rem; margin-bottom:4rem; font-family:Montserrat,sans-serif; max-width:600px;">Everything you need to know about our lab-grown opal blocks.</p>

    <div style="display:flex; flex-direction:column; gap:0; max-width:900px;">
        <div style="padding:2rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
            <h3 style="color:#000000; font-size:1.1rem; font-weight:600; margin-bottom:0.75rem; font-family:Montserrat,sans-serif;">What is lab-grown opal?</h3>
            <p style="color:#333333; line-height:1.8; font-family:Montserrat,sans-serif; font-size:0.95rem; margin:0;">Our lab-grown opal is a synthetic opal composed of approximately 80% resin and 20% silica. It shares the same SiO₂ composition and crystal lattice as geological opal. The play-of-color phenomenon occurs through identical Bragg Diffraction physics. It is mold-formed in 110x110x40mm blocks.</p>
        </div>

        <div style="padding:2rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
            <h3 style="color:#000000; font-size:1.1rem; font-weight:600; margin-bottom:0.75rem; font-family:Montserrat,sans-serif;">How can I work with lab-grown opal?</h3>
            <p style="color:#333333; line-height:1.8; font-family:Montserrat,sans-serif; font-size:0.95rem; margin:0;">Lab-grown opal can be cut with carbide tools of all sorts, turned on wood lathes, and worked using standard lapidary equipment. Diamond tooling works for cutting and drilling, and standard polishing compounds apply. These opals have a pencil hardness of 3-5H. No special humidity storage or temperature acclimation required.</p>
        </div>

        <div style="padding:2rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
            <h3 style="color:#000000; font-size:1.1rem; font-weight:600; margin-bottom:0.75rem; font-family:Montserrat,sans-serif;">Why do these opals look different from other synthetics?</h3>
            <p style="color:#333333; line-height:1.8; font-family:Montserrat,sans-serif; font-size:0.95rem; margin:0;">Most lab-grown opals are formed through a sedimentation process that yields a columnar orientation of the opal fire. Our Aurora opals have a non-directional pattern because our curing process does not allow for sedimentation—creating a unique, one-of-a-kind play of color in each block.</p>
        </div>

        <div style="padding:2rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
            <h3 style="color:#000000; font-size:1.1rem; font-weight:600; margin-bottom:0.75rem; font-family:Montserrat,sans-serif;">Is lab-grown opal structurally identical to mined opal?</h3>
            <p style="color:#333333; line-height:1.8; font-family:Montserrat,sans-serif; font-size:0.95rem; margin:0;">Synthetic opal shares the same SiO₂ silica composition and crystal lattice as geological opal. The distinction: our material incorporates a polymer stabilizer in place of the water content found in natural specimens—eliminating the primary failure mechanism of natural opal.</p>
        </div>

        <div style="padding:2rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
            <h3 style="color:#000000; font-size:1.1rem; font-weight:600; margin-bottom:0.75rem; font-family:Montserrat,sans-serif;">How do professionals distinguish synthetic from natural?</h3>
            <p style="color:#333333; line-height:1.8; font-family:Montserrat,sans-serif; font-size:0.95rem; margin:0;">Under 10x magnification, synthetic opal exhibits a characteristic columnar structure—sometimes called "lizard skin"—resulting from the controlled growth process. Natural opal displays more irregular internal architecture. To the naked eye, high-grade synthetic is virtually indistinguishable from premium natural material.</p>
        </div>

        <div style="padding:2rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
            <h3 style="color:#000000; font-size:1.1rem; font-weight:600; margin-bottom:0.75rem; font-family:Montserrat,sans-serif;">What is the durability compared to mined material?</h3>
            <p style="color:#333333; line-height:1.8; font-family:Montserrat,sans-serif; font-size:0.95rem; margin:0;">Natural opal\'s 5-10% water content makes it vulnerable to dehydration crazing, thermal shock fracturing, and impact failure. Lab-grown opal contains zero water. The polymer matrix permanently stabilizes the silica structure. Material produced today will maintain identical optical and structural properties decades from now.</p>
        </div>

        <div style="padding:2rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
            <h3 style="color:#000000; font-size:1.1rem; font-weight:600; margin-bottom:0.75rem; font-family:Montserrat,sans-serif;">How does pricing compare to premium natural opal?</h3>
            <p style="color:#333333; line-height:1.8; font-family:Montserrat,sans-serif; font-size:0.95rem; margin:0;">High-grade Australian black opal commands $500-1000+ per carat with significant quality variance. Our blocks deliver consistent, vibrant play-of-color at a fraction of that cost. You pay for performance and reliability rather than geological scarcity.</p>
        </div>

        <div style="padding:2rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
            <h3 style="color:#000000; font-size:1.1rem; font-weight:600; margin-bottom:0.75rem; font-family:Montserrat,sans-serif;">What is the environmental impact?</h3>
            <p style="color:#333333; line-height:1.8; font-family:Montserrat,sans-serif; font-size:0.95rem; margin:0;">Traditional opal extraction involves open-pit excavation, aquifer depletion, diesel-powered heavy machinery, and tons of waste rock per carat recovered. Controlled synthesis eliminates habitat destruction, reduces water consumption by orders of magnitude, and produces zero mining waste. Same optical properties. Drastically reduced footprint.</p>
        </div>

        <div style="padding:2rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
            <h3 style="color:#000000; font-size:1.1rem; font-weight:600; margin-bottom:0.75rem; font-family:Montserrat,sans-serif;">Do you offer wholesale pricing?</h3>
            <p style="color:#333333; line-height:1.8; font-family:Montserrat,sans-serif; font-size:0.95rem; margin:0;">Yes. We are interested in partnering with retailers and are happy to offer discounts on larger orders. Please reach out to <a href="mailto:info@labgrownopals.com" style="color:#b58e4f;">info@labgrownopals.com</a> for wholesale inquiries.</p>
        </div>

        <div style="padding:2rem 0;">
            <h3 style="color:#000000; font-size:1.1rem; font-weight:600; margin-bottom:0.75rem; font-family:Montserrat,sans-serif;">What payment methods do you accept?</h3>
            <p style="color:#333333; line-height:1.8; font-family:Montserrat,sans-serif; font-size:0.95rem; margin:0;">In most cases, we can accommodate alternative payment methods. When paying with a check, we will send out your order once the check has cleared. We can also accept Venmo, Cashapp, and Zelle. Please reach out to <a href="mailto:support@labgrownopals.com" style="color:#b58e4f;">support@labgrownopals.com</a> to discuss your specific needs.</p>
        </div>
    </div>
</div>';
        return $faq_html;
    }
    return $content;
}
add_filter('the_content', 'opal_unified_faq_content', 999);
