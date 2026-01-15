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
    $child_style_path = get_stylesheet_directory() . "/style.css";
    $child_style_ver = file_exists($child_style_path) ? filemtime($child_style_path) : wp_get_theme()->get("Version");
    wp_enqueue_style(
        "opal-avant-garde-style",
        get_stylesheet_uri(),
        array("twentytwentyfour-style"),
        $child_style_ver
    );
}
add_action("wp_enqueue_scripts", "opal_enqueue_styles");

// Add custom body class
function opal_body_class($classes) {
    $classes[] = "opal-elegant-v3";
    if (is_page('contact') || is_page('contact-us')) {
        $classes[] = "is-contact-page";
    }
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

// Global FAQ Section HTML
function opal_get_faq_section_html() {
    return '
    <section class="faq-section">
        <div class="faq-container">
            <div class="faq-header">
                <h1 class="faq-title">Common Questions</h1>
            </div>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <h3 class="faq-question">What is Aurora Opal?</h3>
                    <p class="faq-answer">Aurora Opal is a synthetic opal composed of approximately 80% resin and 20% silica. It is mold formed in 110x110x40mm blocks.</p>
                </div>
                
                <div class="faq-item">
                    <h3 class="faq-question">How can I work with Aurora Opal?</h3>
                    <p class="faq-answer">Aurora Opal can be cut with carbide tools of all sorts, it can be turned on wood lathes and can also be worked using lapidary equipment. These opals have a pencil hardness of 3-5H.</p>
                </div>
                
                <div class="faq-item">
                    <h3 class="faq-question">Why do these opals look so different from other synthetic opals?</h3>
                    <p class="faq-answer">Our other lab grown opals are formed through a sedimentation process that yields a column orientation of the opal fire. Aurora opals have a non-direction pattern because our curing process does not allow for a specific orientation of the silica spheres.</p>
                </div>
                
                <div class="faq-item">
                    <h3 class="faq-question">Do you offer wholesale pricing?</h3>
                    <p class="faq-answer">Yes! We are interested in partnering with retailers and are happy to offer discounts on larger order. Please reach out to info@labgrownopals.com</p>
                </div>
                
                <div class="faq-item">
                    <h3 class="faq-question">Can I pay using a check or other payment platform?</h3>
                    <p class="faq-answer">In most cases. When paying with a check we will send out your order once the check has cleared. We can also accept payment via Zelle or Venmo. Please reach out to support@labgrownopals.com.</p>
                </div>
                
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
}

// Global Contact Section HTML
function opal_get_contact_section_html() {
    $success_msg = '';
    if (isset($_GET['contact_success'])) {
        $success_msg = '<div style="background:#d4edda; color:#155724; padding:1.5rem; border-radius:8px; margin-bottom:2rem; border:1px solid #c3e6cb; font-weight:600; text-align:center;">Thank you! Your message has been sent successfully.</div>';
    }

    return '
    <section class="contact-page-section">
        <div class="contact-container">
            ' . $success_msg . '
            <div class="wp-block-columns is-layout-flex">
                <div class="wp-block-column has-black-background-color" style="background-color:#1a1a1a; padding: 4rem;">
                    <h2 style="color:white; font-family:var(--font-display); font-size:3rem; margin-bottom:2rem; font-weight:300;">Get in touch</h2>
                    <p style="color:#ccc; font-size:1.1rem; line-height:1.6; margin-bottom:3rem;">We’re here to answer your questions and listen to your suggestions.</p>
                    <div style="color:white; line-height:2;">
                        <p style="margin-bottom:2rem;">United States<br>P.O. Box 205<br>South Casco, ME 04077</p>
                        <p><a href="mailto:info@labgrownopals.com" style="color:white; text-decoration:none; border-bottom:1px solid var(--gold);">info@labgrownopals.com</a></p>
                    </div>
                </div>
                <div class="wp-block-column" style="background-color:white; padding: 4rem;">
                    <form class="opal-contact-form" method="POST">
                        <input type="hidden" name="opal_contact_submit" value="1">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div class="form-group">
                                <label style="display:block; font-weight:600; margin-bottom:0.5rem; font-size:0.85rem;">First Name *</label>
                                <input type="text" name="first_name" required style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:4px;">
                            </div>
                            <div class="form-group">
                                <label style="display:block; font-weight:600; margin-bottom:0.5rem; font-size:0.85rem;">Last Name</label>
                                <input type="text" name="last_name" style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:4px;">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="display:block; font-weight:600; margin-bottom:0.5rem; font-size:0.85rem;">Email *</label>
                            <input type="email" name="email" required style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="display:block; font-weight:600; margin-bottom:0.5rem; font-size:0.85rem;">Message *</label>
                            <textarea name="message" rows="5" required style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:4px;"></textarea>
                        </div>
                        <p style="font-size:0.8rem; color:#666; margin-bottom:2rem;">By submitting this form, you agree to our processing of your data in accordance with our Privacy Policy.</p>
                        <button type="submit" style="background:#ad6d74; color:white; border:none; padding:1rem 2rem; border-radius:4px; cursor:pointer; font-weight:600; width:100%; font-size:1rem;">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>';
}

// Handle Contact Form Submission
add_action('template_redirect', function() {
    if (isset($_POST['opal_contact_submit'])) {
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);
        
        $to = get_option('admin_email');
        $subject = 'New Contact Form Submission from ' . $first_name;
        $body = "Name: $first_name $last_name\nEmail: $email\n\nMessage:\n$message";
        $headers = array('Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email);
        
        wp_mail($to, $subject, $body, $headers);
        
        $redirect_url = remove_query_arg('contact_success', $_SERVER['REQUEST_URI']);
        wp_redirect(add_query_arg('contact_success', '1', $redirect_url));
        exit;
    }
});

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

        // Explicitly remove any FAQ sections from homepage
        $content = preg_replace('/<section[^>]*class="faq-section">.*?<\/section>/is', '', $content);

        // --- REMOVE UNWANTED "BEGIN YOUR COLLECTION" SECTION ---
        // Surgical removal of text only to prevent accidental deletion of nearby sections
        $content = str_replace('BEGIN YOUR COLLECTION', '', $content);
        $content = str_replace('Questions about our opals or custom orders? We are here to help bring your vision to life.', '', $content);
    }

    if (is_page(40) || is_page('faqs') || is_page('faq') || is_page('faq-s')) {
        return opal_get_faq_section_html();
    }

    if (is_page('contact') || is_page('contact-us')) {
        return opal_get_contact_section_html();
    }

    return $content;
}
add_filter('the_content', 'opal_replace_content_text', 999);

// Force content for FAQ page if empty
function opal_force_faq_content($posts) {
    if (empty($posts) || !is_main_query() || is_admin()) return $posts;
    
    foreach ($posts as $post) {
        if ($post->ID == 40 || $post->post_name == 'faqs') {
            if (empty($post->post_content)) {
                $post->post_content = '<!-- faq placeholder -->';
            }
        }
    }
    return $posts;
}
add_filter('the_posts', 'opal_force_faq_content');

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
    
    // Ensure FAQ page content block is replaced
    if ($block['blockName'] === 'core/post-content') {
        if (is_page(40) || is_page('faqs') || is_page('faq') || is_page('faq-s')) {
            return opal_get_faq_section_html();
        }
    }
    
    return $block_content;
}
add_filter('render_block', 'opal_replace_block_text', 10, 2);
