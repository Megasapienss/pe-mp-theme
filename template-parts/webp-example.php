<?php
/**
 * Example template showing how to use WebP functions
 * This file demonstrates various ways to implement WebP images in your theme
 */

// Get the post thumbnail ID
$thumbnail_id = get_post_thumbnail_id();

if ($thumbnail_id) {
    ?>
    <div class="webp-examples">
        <h2>WebP Image Examples</h2>
        
        <!-- Example 1: Simple optimized image URL -->
        <div class="example">
            <h3>1. Optimized Image URL</h3>
            <img src="<?php pe_mp_optimized_image_url($thumbnail_id, 'medium'); ?>" alt="Optimized image">
        </div>
        
        <!-- Example 2: Picture element with fallback -->
        <div class="example">
            <h3>2. Picture Element with Fallback</h3>
            <?php pe_mp_picture_element($thumbnail_id, 'medium', array('alt' => 'Picture element example')); ?>
        </div>
        
        <!-- Example 3: Responsive image with multiple sizes -->
        <div class="example">
            <h3>3. Responsive Image</h3>
            <?php 
            pe_mp_responsive_image($thumbnail_id, array(
                'thumbnail' => '150w',
                'medium' => '300w',
                'large' => '1024w'
            ), array(
                'alt' => 'Responsive image example',
                'sizes' => '(max-width: 768px) 100vw, 50vw'
            ));
            ?>
        </div>
        
        <!-- Example 4: Manual implementation -->
        <div class="example">
            <h3>4. Manual Implementation</h3>
            <?php
            $webp_url = pe_mp_get_webp_url($thumbnail_id);
            $original_image = wp_get_attachment_image_src($thumbnail_id, 'medium');
            
            if ($webp_url && $original_image) {
                ?>
                <picture>
                    <source srcset="<?php echo esc_url($webp_url); ?>" type="image/webp">
                    <img src="<?php echo esc_url($original_image[0]); ?>" 
                         width="<?php echo $original_image[1]; ?>" 
                         height="<?php echo $original_image[2]; ?>" 
                         alt="Manual implementation">
                </picture>
                <?php
            } else {
                echo wp_get_attachment_image($thumbnail_id, 'medium');
            }
            ?>
        </div>
        
        <!-- Example 5: Background image with WebP support -->
        <div class="example">
            <h3>5. CSS Background Image</h3>
            <style>
                .webp-bg {
                    width: 300px;
                    height: 200px;
                    background-size: cover;
                    background-position: center;
                    border: 1px solid #ccc;
                }
                <?php if (pe_mp_browser_supports_webp() && pe_mp_get_webp_url($thumbnail_id)): ?>
                .webp-bg {
                    background-image: url('<?php echo esc_url(pe_mp_get_webp_url($thumbnail_id)); ?>');
                }
                <?php else: ?>
                .webp-bg {
                    background-image: url('<?php echo esc_url(wp_get_attachment_image_url($thumbnail_id, 'medium')); ?>');
                }
                <?php endif; ?>
            </style>
            <div class="webp-bg"></div>
        </div>
    </div>
    <?php
} else {
    echo '<p>No featured image set for this post.</p>';
}
?> 