<?php
/**
 * Liste des articles (Actualités), archives et recherche.
 *
 * @package CIPLEV
 */

get_header();

if ( is_home() && get_option( 'page_for_posts' ) ) {
	$ciplev_title = get_the_title( (int) get_option( 'page_for_posts' ) );
} elseif ( is_search() ) {
	/* translators: %s: terme recherché */
	$ciplev_title = sprintf( __( 'Résultats pour « %s »', 'ciplev' ), get_search_query() );
} elseif ( is_archive() ) {
	$ciplev_title = wp_strip_all_tags( get_the_archive_title() );
} else {
	$ciplev_title = __( 'Actualités', 'ciplev' );
}
ciplev_page_hero( $ciplev_title, is_home() ? __( 'Activités, formations et sensibilisations du CIPLEV sur le terrain.', 'ciplev' ) : '' );
?>
<div class="container page-body page-body--wide">
	<?php if ( have_posts() ) : ?>
		<div class="cards">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/card' );
			endwhile;
			?>
		</div>
		<?php
		the_posts_pagination(
			array(
				'prev_text' => __( 'Précédent', 'ciplev' ),
				'next_text' => __( 'Suivant', 'ciplev' ),
			)
		);
		?>
	<?php else : ?>
		<div class="empty">
			<p><?php esc_html_e( 'Aucune publication pour le moment. Revenez bientôt.', 'ciplev' ); ?></p>
		</div>
	<?php endif; ?>
</div>
<?php
get_footer();
