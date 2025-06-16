<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php
                if (have_posts()) :
                    while (have_posts()) :
                        the_post();
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <header class="entry-header">
                                <?php
                                if (is_singular()) :
                                    the_title('<h1 class="entry-title">', '</h1>');
                                else :
                                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                                endif;

                                if ('post' === get_post_type()) :
                                    ?>
                                    <div class="entry-meta">
                                        <span class="posted-on">
                                            <?php echo esc_html__('Posted on', 'pe-mp-theme'); ?>
                                            <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                        </span>
                                        <span class="byline">
                                            <?php echo esc_html__('by', 'pe-mp-theme'); ?>
                                            <span class="author vcard">
                                                <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                    <?php echo esc_html(get_the_author()); ?>
                                                </a>
                                            </span>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="entry-content">
                                <?php
                                if (is_singular()) :
                                    the_content();
                                else :
                                    the_excerpt();
                                    ?>
                                    <a href="<?php echo esc_url(get_permalink()); ?>" class="read-more">
                                        <?php echo esc_html__('Read More', 'pe-mp-theme'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <footer class="entry-footer">
                                <?php
                                $categories_list = get_the_category_list(esc_html__(', ', 'pe-mp-theme'));
                                if ($categories_list) {
                                    printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'pe-mp-theme') . '</span>', $categories_list);
                                }

                                $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'pe-mp-theme'));
                                if ($tags_list) {
                                    printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'pe-mp-theme') . '</span>', $tags_list);
                                }
                                ?>
                            </footer>
                        </article>
                        <?php
                    endwhile;

                    the_posts_navigation(array(
                        'prev_text' => esc_html__('Older posts', 'pe-mp-theme'),
                        'next_text' => esc_html__('Newer posts', 'pe-mp-theme'),
                    ));

                else :
                    ?>
                    <div class="no-results">
                        <h2><?php esc_html_e('Nothing Found', 'pe-mp-theme'); ?></h2>
                        <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for.', 'pe-mp-theme'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer(); 