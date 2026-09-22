<?php
/**
 * Pied de page.
 *
 * @package CIPLEV
 */
$ciplev_slogan = ciplev_slogan();
?>
</main>

<footer class="site-footer">
	<span class="flag-bar" aria-hidden="true"></span>
	<div class="container site-footer__grid">
		<div class="site-footer__about">
			<a class="brand brand--light" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img class="brand__logo" src="<?php echo esc_url( ciplev_logo_url() ); ?>" alt="" width="44" height="70" loading="lazy">
				<span class="brand__text">
					<span class="brand__name">CIPLEV</span>
					<?php if ( $ciplev_slogan ) : ?>
						<span class="brand__full"><?php echo esc_html( $ciplev_slogan ); ?></span>
					<?php endif; ?>
				</span>
			</a>
			<p><?php esc_html_e( 'Comité Interministériel de Prévention et de Lutte contre l’Extrémisme Violent, créé par le décret n°2019-076/PR du 15 mai 2019.', 'ciplev' ); ?></p>
			<?php ciplev_social_links(); ?>
		</div>

		<div>
			<h2 class="site-footer__title"><?php esc_html_e( 'Navigation', 'ciplev' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-menu',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</div>

		<div>
			<h2 class="site-footer__title"><?php esc_html_e( 'Nous joindre', 'ciplev' ); ?></h2>
			<ul class="contact-list">
				<li><?php echo ciplev_icon( 'pin' ); // phpcs:ignore ?><span><?php echo esc_html( ciplev_opt( 'ciplev_address' ) ); ?></span></li>
				<?php foreach ( array( 'ciplev_phone_1', 'ciplev_phone_2' ) as $ciplev_key ) : ?>
					<?php if ( ciplev_opt( $ciplev_key ) ) : ?>
						<li><?php echo ciplev_icon( 'phone' ); // phpcs:ignore ?><a href="<?php echo esc_attr( ciplev_tel_href( ciplev_opt( $ciplev_key ) ) ); ?>">(+228) <?php echo esc_html( ciplev_opt( $ciplev_key ) ); ?></a></li>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if ( ciplev_opt( 'ciplev_email' ) ) : ?>
					<li><?php echo ciplev_icon( 'mail' ); // phpcs:ignore ?><a href="mailto:<?php echo esc_attr( antispambot( ciplev_opt( 'ciplev_email' ) ) ); ?>"><?php echo esc_html( antispambot( ciplev_opt( 'ciplev_email' ) ) ); ?></a></li>
				<?php endif; ?>
				<?php if ( ciplev_opt( 'ciplev_hours' ) ) : ?>
					<li><?php echo ciplev_icon( 'clock' ); // phpcs:ignore ?><span><?php echo esc_html( ciplev_opt( 'ciplev_hours' ) ); ?></span></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>

	<div class="site-footer__bottom">
		<div class="container">
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> CIPLEV — <?php esc_html_e( 'République Togolaise. Tous droits réservés.', 'ciplev' ); ?></p>
			<a href="#haut" class="to-top"><?php esc_html_e( 'Haut de page', 'ciplev' ); ?> ↑</a>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
