<?php

/**
 * Hover Preload Module
 * 
 * Handles client-side image preloading on link hover for better user experience
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add JavaScript to preload images on link hover
 */
function pe_mp_preload_on_hover()
{
    // Only add on frontend
    if (is_admin()) {
        return;
    }
    ?>
    <script>
    (function() {
        // Preload images when hovering over links
        function preloadImage(url) {
            if (!url) return;
            
            var img = new Image();
            img.src = url;
        }
        
        // Find all links that might lead to pages with featured images
        var links = document.querySelectorAll('a[href*="/"]');
        
        links.forEach(function(link) {
            link.addEventListener('mouseenter', function() {
                // Only preload if we haven't already
                if (link.dataset.preloaded) return;
                
                // Get the href
                var href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
                
                // Make an AJAX request to get the page's featured image
                fetch(href + '?preload=1', {
                    method: 'HEAD',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(response) {
                    // Check if the page has a featured image in meta tags
                    return fetch(href);
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(html) {
                    // Extract featured image from meta tags
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');
                    
                    // Check for Open Graph image
                    var ogImage = doc.querySelector('meta[property="og:image"]');
                    if (ogImage) {
                        preloadImage(ogImage.getAttribute('content'));
                    }
                    
                    // Check for Twitter image
                    var twitterImage = doc.querySelector('meta[name="twitter:image"]');
                    if (twitterImage) {
                        preloadImage(twitterImage.getAttribute('content'));
                    }
                    
                    link.dataset.preloaded = 'true';
                })
                .catch(function(error) {
                    // Silently fail - this is just an optimization
                });
            });
        });
    })();
    </script>
    <?php
}
add_action('wp_footer', 'pe_mp_preload_on_hover');

/**
 * Add preload headers for AJAX requests
 */
function pe_mp_preload_headers()
{
    if (isset($_GET['preload']) && $_GET['preload'] === '1') {
        // This is a preload request, add appropriate headers
        header('X-Preload: true');
        
        // If this is a single post/page, add the featured image URL to headers
        if (is_single() || is_page() || is_singular('provider')) {
            $thumbnail_id = get_post_thumbnail_id();
            if ($thumbnail_id) {
                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                if ($thumbnail_url) {
                    header('X-Featured-Image: ' . $thumbnail_url);
                }
            }
        }
    }
}
add_action('template_redirect', 'pe_mp_preload_headers'); 