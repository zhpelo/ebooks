<?php
get_header();
?>
<main class="container-fluid container-lg">


<?php

	$archive_title    = '';
	$archive_subtitle = '';

	if ( is_search() ) {
		global $wp_query;

		$archive_title = sprintf(
			'%1$s %2$s',
			'<span class="color-accent">' . __( 'Search:', 'ebooks' ) . '</span>',
			'&ldquo;' . get_search_query() . '&rdquo;'
		);

		if ( $wp_query->found_posts ) {
			$archive_subtitle = sprintf(
				/* translators: %s: Number of search results. */
				_n(
					'We found %s result for your search.',
					'We found %s results for your search.',
					$wp_query->found_posts,
					'ebooks'
				),
				number_format_i18n( $wp_query->found_posts )
			);
		} else {
			$archive_subtitle = __( 'We could not find any results for your search. You can give it another try through the search form below.', 'ebooks' );
		}
	} elseif ( is_archive() && ! have_posts() ) {
		$archive_title = __( 'Nothing Found', 'ebooks' );
	} elseif ( ! is_home() ) {
		$archive_title    = get_the_archive_title();
		$archive_subtitle = get_the_archive_description();
	}

	if ( $archive_title || $archive_subtitle ) {
		?>

        <div class="row">
            <div class="col-md-12 mt-3">
            <?php if ( $archive_title ) { ?>
                    <h1 class="archive-title"><?php echo wp_kses_post( $archive_title ); ?></h1>
                <?php } ?>

                <?php if ( $archive_subtitle ) { ?>
                    <div class="archive-subtitle section-inner thin max-percentage intro-text"><?php echo wp_kses_post( wpautop( $archive_subtitle ) ); ?></div>
                <?php } ?>
            </div>
        </div>
	<?php
	}
?>
    <div class="row">
        <div class="col-md-9">
            <div class="my-3 p-3 bg-burlywood rounded shadow-sm">
                <?php if (have_posts()) {
                    $i = 0;
                    while (have_posts()) {
                        $i++;
                        if ($i > 1) {
                            echo '<hr class="post-separator styled-separator is-style-wide section-inner" aria-hidden="true" />';
                        }
                        the_post();
                ?>
                        <div class="row g-0 overflow-hidden flex-md-row mb-4 h-md-250">
                            <div class="col p-4 d-flex flex-column position-static">
                                
                                <?php the_title( '<h2 class="h3 mb-1"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
                                <div class="mb-1 text-muted">
                                    <strong><?php the_category('>'); ?></strong>
                                    <?php the_time( get_option( 'date_format' ) ); ?>
                                </div>
                                <?php the_excerpt(); ?>
                            </div>
                            <div class="col-auto d-none d-lg-block">
                                <?php the_post_thumbnail('cover-sm'); ?>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>

            <?php get_template_part( 'template-parts/pagination' ); ?>
        </div>
        <div class="col-md-3">
            <div class="my-3 p-3 bg-burlywood rounded shadow-sm">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>    
</main>
<?php 
get_footer();
