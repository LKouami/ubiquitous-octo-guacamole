<?php
/**
 * En-tête du site.
 *
 * @package CIPLEV
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#006a4f">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#contenu"><?php esc_html_e( 'Aller au contenu', 'ciplev' ); ?></a>

<header class="site-header" id="haut">
	<div class="gov-band">
		<div class="container gov-band__inner">
			<a class="gov-band__name" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<strong>CIPLEV</strong>
				<span><?php esc_html_e( 'Comité Interministériel de Prévention et de Lutte contre l’Extrémisme Violent', 'ciplev' ); ?></span>
			</a>
			<ul class="gov-band__contact">
				<?php if ( ciplev_opt( 'ciplev_phone_1' ) ) : ?>
					<li><a href="<?php echo esc_attr( ciplev_tel_href( ciplev_opt( 'ciplev_phone_1' ) ) ); ?>"><?php echo ciplev_icon( 'phone' ); // phpcs:ignore ?><?php echo esc_html( ciplev_opt( 'ciplev_phone_1' ) ); ?></a></li>
				<?php endif; ?>
				<?php if ( ciplev_opt( 'ciplev_email' ) ) : ?>
					<li class="gov-band__email"><a href="mailto:<?php echo esc_attr( antispambot( ciplev_opt( 'ciplev_email' ) ) ); ?>"><?php echo ciplev_icon( 'mail' ); // phpcs:ignore ?><?php echo esc_html( antispambot( ciplev_opt( 'ciplev_email' ) ) ); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>

	<div class="nav-row">
		<div class="container nav-row__inner">
			<a class="bloc-blason" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( ciplev_blason_url() ); ?>" alt="<?php esc_attr_e( 'République Togolaise — Ministère de la Sécurité', 'ciplev' ); ?>" width="620" height="708">
			</a>

			<nav class="main-nav" id="menu-principal" aria-label="<?php esc_attr_e( 'Menu principal', 'ciplev' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'menu',
						'fallback_cb'    => 'ciplev_menu_fallback',
						'depth'          => 2,
					)
				);
				?>
			</nav>

			<a class="entity-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( ciplev_logo_url() ); ?>" alt="<?php esc_attr_e( 'Logo du CIPLEV', 'ciplev' ); ?>" width="319" height="506">
			</a>

			<button class="nav-toggle" aria-expanded="false" aria-controls="menu-principal">
				<span class="nav-toggle__open"><?php echo ciplev_icon( 'menu' ); // phpcs:ignore ?></span>
				<span class="nav-toggle__close"><?php echo ciplev_icon( 'close' ); // phpcs:ignore ?></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'ciplev' ); ?></span>
			</button>
		</div>
	</div>
	<span class="green-rule" aria-hidden="true"></span>
</header>

<main id="contenu" class="site-main">
