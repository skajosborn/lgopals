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
        $new_text = '<p>Our lab-grown opals aren’t an alternative — they’re the evolution. By replicating the Earth’s pressure cycles in a controlled environment, we create opal that is optically and molecularly identical to mined stone, yet structurally superior in every measurable way.</p>' .
                    '<p>Engineered for absolute consistency and atomic-level brilliance, each block delivers flawless performance for precision lapidary, bespoke horology, and high-end craftsmanship. No fractures. No unpredictability. No environmental cost.</p>' .
                    '<p>Same fire. Same beauty.<br>Zero footprint. Total integrity. The new standard for the modern craftsman.</p>';

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

        // --- APPEND FAQ SECTION ---
        $faq_section = '
        <section class="faq-section">
            <div class="faq-container">
                <div class="faq-header">
                    <p class="section-label">TECHNICAL BRIEF</p>
                    <h2 class="faq-title">Common Questions</h2>
                </div>
                
                <div class="faq-grid">
                    <div class="faq-item">
                        <h3 class="faq-question">Is lab-grown opal structurally identical to mined opal?</h3>
                        <p class="faq-answer">Synthetic opal shares the same SiO2 silica composition and crystal lattice as geological opal. The play-of-color phenomenon occurs through identical Bragg Diffraction physics. The distinction: our material incorporates a polymer stabilizer in place of the water content found in natural specimens—eliminating the primary failure mechanism.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">How do professionals distinguish synthetic from natural?</h3>
                        <p class="faq-answer">Under 10x magnification, synthetic opal exhibits a characteristic columnar structure—sometimes called "lizard skin"—resulting from the controlled growth process. Natural opal displays more irregular internal architecture. To the naked eye, high-grade synthetic is virtually indistinguishable from premium natural material.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">What is the failure rate compared to mined material?</h3>
                        <p class="faq-answer">Natural opal\'s 5-10% water content makes it vulnerable to dehydration crazing, thermal shock fracturing, and impact failure. Lab-grown opal contains zero water. The polymer matrix permanently stabilizes the silica structure. Material produced today will maintain identical optical and structural properties decades from now.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">What are the machining considerations?</h3>
                        <p class="faq-answer">Lab-grown opal machines predictably. Standard lapidary equipment, diamond tooling for cutting and drilling, standard polishing compounds. No special humidity storage required. No temperature acclimation period. The material behaves consistently across batches—critical for production workflows.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">How does pricing compare to premium natural opal?</h3>
                        <p class="faq-answer">High-grade Australian black opal commands $500-1000+ per carat with significant quality variance. Our blocks deliver consistent, vibrant play-of-color at a fraction of that cost. You pay for performance and reliability rather than geological scarcity.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">What is the environmental differential?</h3>
                        <p class="faq-answer">Traditional opal extraction involves open-pit excavation, aquifer depletion, diesel-powered heavy machinery, and tons of waste rock per carat recovered. Controlled synthesis eliminates habitat destruction, reduces water consumption by orders of magnitude, and produces zero mining waste. Same optical properties. Drastically reduced footprint.</p>
                    </div>
                </div>
            </div>
        </section>';
        
        $content .= $faq_section;
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
