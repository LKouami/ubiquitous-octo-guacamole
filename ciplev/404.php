<?php
/**
 * Page introuvable.
 *
 * @package CIPLEV
 */

get_header();
ciplev_page_hero( __( 'Page introuvable', 'ciplev' ) );
?>
<div class="container page-body">
	<div class="empty">
		<p><?php esc_html_e( 'La page que vous recherchez n’existe pas ou a été déplacée.', 'ciplev' ); ?></p>
		<p><a class="btn btn--blue" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Retour à l’accueil', 'ciplev' ); ?></a></p>
	</div>
</div>
<?php
get_footer();
