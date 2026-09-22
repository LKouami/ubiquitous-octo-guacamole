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
	<meta name="theme-color" content="#0b3d91">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#contenu"><?php esc_html_e( 'Aller au contenu', 'ciplev' ); ?></a>

<div class="topbar">
	<div class="container topbar__inner">
		<span class="topbar__country">
			<span class="topbar__flag" aria-hidden="true"></span>
			<?php esc_html_e( 'République Togolaise', 'ciplev' ); ?>
		</span>
		<ul class="topbar__contact">
			<?php if ( ciplev_opt( 'ciplev_phone_1' ) ) : ?>
				<li><a href="<?php echo esc_attr( ciplev_tel_href( ciplev_opt( 'ciplev_phone_1' ) ) ); ?>"><?php echo ciplev_icon( 'phone' ); // phpcs:ignore ?><?php echo esc_html( ciplev_opt( 'ciplev_phone_1' ) ); ?></a></li>
			<?php endif; ?>
			<?php if ( ciplev_opt( 'ciplev_email' ) ) : ?>
				<li><a href="mailto:<?php echo esc_attr( antispambot( ciplev_opt( 'ciplev_email' ) ) ); ?>"><?php echo ciplev_icon( 'mail' ); // phpcs:ignore ?><?php echo esc_html( antispambot( ciplev_opt( 'ciplev_email' ) ) ); ?></a></li>
			<?php endif; ?>
			<li class="topbar__address"><?php echo ciplev_icon( 'pin' ); // phpcs:ignore ?><?php echo esc_html( ciplev_opt( 'ciplev_address' ) ); ?></li>
		</ul>
	</div>
</div>

<header class="site-header" id="haut">
	<div class="container site-header__inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img class="brand__logo" src="<?php echo esc_url( ciplev_logo_url() ); ?>" alt="<?php esc_attr_e( 'Logo du CIPLEV', 'ciplev' ); ?>" width="44" height="70">
			<span class="brand__text">
				<span class="brand__name">CIPLEV</span>
				<span class="brand__full"><?php esc_html_e( 'Comité Interministériel de Prévention et de Lutte contre l’Extrémisme Violent', 'ciplev' ); ?></span>
			</span>
		</a>

		<button class="nav-toggle" aria-expanded="false" aria-controls="menu-principal">
			<span class="nav-toggle__open"><?php echo ciplev_icon( 'menu' ); // phpcs:ignore ?></span>
			<span class="nav-toggle__close"><?php echo ciplev_icon( 'close' ); // phpcs:ignore ?></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'ciplev' ); ?></span>
		</button>

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
	</div>
</header>

<main id="contenu" class="site-main">
