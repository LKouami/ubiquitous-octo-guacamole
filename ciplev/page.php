<?php
/**
 * Page standard.
 *
 * @package CIPLEV
 */

get_header();

while ( have_posts() ) :
	the_post();
	ciplev_page_hero( get_the_title(), has_excerpt() ? get_the_excerpt() : '' );
	?>
	<div class="container page-body">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
			<?php
			the_content();
			wp_link_pages();
			?>
		</article>
	</div>
	<?php
endwhile;

get_footer();
