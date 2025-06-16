<?php

/**
 * The template for displaying provider archive
 */

get_header();

// Get current category and region
$current_category = get_queried_object();
$current_region = isset($_GET['region']) ? sanitize_text_field($_GET['region']) : '';
?>

<div class="provider-archive">
    <div class="container">
        <!-- Archive Header -->
        <header class="archive-header">
            <h1 class="archive-title">
                Providers
            </h1>

            <?php if (have_posts()): ?>
                <div class="archive-meta">
                    <?php
                    global $wp_query;
                    printf(
                        _n('%s Result', '%s Results', $wp_query->found_posts, 'pe-mp-theme'),
                        number_format_i18n($wp_query->found_posts)
                    );
                    ?>
                </div>
            <?php endif; ?>
        </header>

        <!-- Search and Filters Section -->
        <div class="search-filters">
            <!-- Search Block -->
            <div class="search-block">
                <form action="<?php echo esc_url(home_url('/')); ?>" method="get" class="search-form">
                    <input type="hidden" name="post_type" value="provider">

                    <div class="search-form__main">
                        <div class="search-form__field search-form__field--name">
                            <input type="text"
                                name="s"
                                class="form-input"
                                placeholder="<?php _e('Search by provider name', 'pe-mp-theme'); ?>"
                                value="<?php echo isset($_GET['s']) ? esc_attr($_GET['s']) : ''; ?>">
                        </div>

                        <div class="search-form__field search-form__field--category">
                            <select name="provider-type" class="form-select">
                                <option value=""><?php _e('All Categories', 'pe-mp-theme'); ?></option>
                                <?php
                                $provider_types = get_terms(array(
                                    'taxonomy' => 'provider-type',
                                    'hide_empty' => true,
                                ));
                                foreach ($provider_types as $type) {
                                    $selected = isset($_GET['provider-type']) && $_GET['provider-type'] == $type->slug ? 'selected' : '';
                                    echo '<option value="' . esc_attr($type->slug) . '" ' . $selected . '>' . esc_html($type->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Filters Toolbar -->
            <div class="filters-toolbar">
                <div class="filters-toolbar__sort">
                    <label for="sort-by"><?php _e('Sort By:', 'pe-mp-theme'); ?></label>
                    <select id="sort-by" name="sort" class="form-select">
                        <option value="name" <?php selected(isset($_GET['orderby']) && $_GET['orderby'] === 'name'); ?>><?php _e('Name', 'pe-mp-theme'); ?></option>
                        <option value="rating" <?php selected(isset($_GET['orderby']) && $_GET['orderby'] === 'rating'); ?>><?php _e('Rating', 'pe-mp-theme'); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <?php if (have_posts()): ?>
            <div class="provider-grid">
                <?php
                while (have_posts()) {
                    the_post();
                    get_template_part('template-parts/provider-card');
                }
                ?>
            </div>

            <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('Previous', 'pe-mp-theme'),
                'next_text' => __('Next', 'pe-mp-theme'),
            ));
            ?>

        <?php else: ?>
            <div class="no-results">
                <p><?php _e('No providers found.', 'pe-mp-theme'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();
?>