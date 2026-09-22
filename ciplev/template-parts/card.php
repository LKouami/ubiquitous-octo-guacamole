<?php
/**
 * Carte d'article.
 *
 * @package CIPLEV
 */
?>
<article <?php post_class( 'card' ); ?>>
	<a class="card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'ciplev-card', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
		<?php else : ?>
			<span class="card__placeholder"><img src="<?php echo esc_url( ciplev_logo_url() ); ?>" alt="" loading="lazy"></span>
		<?php endif; ?>
	</a>
	<div class="card__body">
		<p class="card__meta"><?php ciplev_posted_on(); ?></p>
		<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
	</div>
</article>
