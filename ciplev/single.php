<?php
/**
 * Article seul.
 *
 * @package CIPLEV
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<header class="page-hero page-hero--post">
		<div class="container">
			<?php ciplev_breadcrumb(); ?>
			<p class="page-hero__meta"><?php ciplev_posted_on(); ?> <?php the_category( ', ' ); ?></p>
			<h1 class="page-hero__title"><?php the_title(); ?></h1>
		</div>
		<span class="flag-bar" aria-hidden="true"></span>
	</header>
	<div class="container page-body">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="entry-thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
			<?php endif; ?>
			<?php the_content(); ?>
		</article>
		<nav class="post-nav">
			<?php
			the_post_navigation(
				array(
					'prev_text' => '<span>' . esc_html__( 'Article précédent', 'ciplev' ) . '</span>%title',
					'next_text' => '<span>' . esc_html__( 'Article suivant', 'ciplev' ) . '</span>%title',
				)
			);
			?>
		</nav>
	</div>
	<?php
endwhile;

get_footer();
