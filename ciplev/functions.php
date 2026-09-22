<?php
/**
 * Thème CIPLEV — fonctions principales.
 *
 * @package CIPLEV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CIPLEV_VERSION', '1.1.0' );
define( 'CIPLEV_DIR', get_template_directory() );
define( 'CIPLEV_URI', get_template_directory_uri() );

require CIPLEV_DIR . '/inc/icons.php';
require CIPLEV_DIR . '/inc/customizer.php';
require CIPLEV_DIR . '/inc/install.php';

/**
 * Supports du thème.
 */
function ciplev_setup() {
	load_theme_textdomain( 'ciplev', CIPLEV_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 160,
			'width'       => 100,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support(
		'editor-color-palette',
		array(
			array( 'name' => 'Vert République', 'slug' => 'vert', 'color' => '#006a4f' ),
			array( 'name' => 'Jaune République', 'slug' => 'jaune', 'color' => '#ffcf11' ),
			array( 'name' => 'Rouge République', 'slug' => 'rouge', 'color' => '#d11135' ),
			array( 'name' => 'Bleu CIPLEV (secondaire)', 'slug' => 'bleu', 'color' => '#1879c2' ),
			array( 'name' => 'Gris ardoise (secondaire)', 'slug' => 'ardoise', 'color' => '#415e70' ),
			array( 'name' => 'Gris clair', 'slug' => 'gris-clair', 'color' => '#f3f5f9' ),
			array( 'name' => 'Encre', 'slug' => 'encre', 'color' => '#1a2332' ),
		)
	);

	add_editor_style( array( ciplev_fonts_url(), 'assets/css/editor.css' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'ciplev' ),
			'footer'  => __( 'Menu du pied de page', 'ciplev' ),
		)
	);

	add_image_size( 'ciplev-card', 640, 400, true );
	add_image_size( 'ciplev-portrait', 400, 400, true );
}
add_action( 'after_setup_theme', 'ciplev_setup' );

/**
 * URL Google Fonts.
 */
function ciplev_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,400;0,600;0,700;0,800;1,400&family=Source+Serif+4:ital,opsz,wght@1,8..60,600;1,8..60,700&display=swap';
}

/**
 * Feuilles de style et scripts.
 */
function ciplev_assets() {
	wp_enqueue_style( 'ciplev-fonts', ciplev_fonts_url(), array(), null );
	wp_enqueue_style( 'ciplev-style', get_stylesheet_uri(), array( 'ciplev-fonts' ), CIPLEV_VERSION );
	wp_enqueue_script( 'ciplev-main', CIPLEV_URI . '/assets/js/main.js', array(), CIPLEV_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
}
add_action( 'wp_enqueue_scripts', 'ciplev_assets' );

function ciplev_preconnect( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'ciplev_preconnect', 10, 2 );

/**
 * Zones de widgets.
 */
function ciplev_widgets() {
	register_sidebar(
		array(
			'name'          => __( 'Barre latérale des articles', 'ciplev' ),
			'id'            => 'sidebar-blog',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'ciplev_widgets' );

/**
 * Logo : logo personnalisé si défini, sinon logo fourni avec le thème.
 */
function ciplev_logo_url() {
	$id = get_theme_mod( 'custom_logo' );
	if ( $id ) {
		$src = wp_get_attachment_image_url( $id, 'full' );
		if ( $src ) {
			return $src;
		}
	}
	return CIPLEV_URI . '/assets/img/logo-ciplev.png';
}

/**
 * Bloc blason officiel (armoiries + « République Togolaise » + ministère de tutelle).
 * Remplaçable dans Apparence › Personnaliser › Options CIPLEV › Bloc blason.
 */
function ciplev_blason_url() {
	$id = (int) get_theme_mod( 'ciplev_blason' );
	if ( $id ) {
		$src = wp_get_attachment_image_url( $id, 'full' );
		if ( $src ) {
			return $src;
		}
	}
	return CIPLEV_URI . '/assets/img/bloc-blason.svg';
}

/**
 * Menu de repli si aucun menu n'est assigné.
 */
function ciplev_menu_fallback() {
	echo '<ul class="menu">';
	wp_list_pages( array( 'title_li' => '', 'depth' => 2 ) );
	echo '</ul>';
}

/**
 * Bandeau de titre des pages intérieures.
 */
function ciplev_page_hero( $title, $subtitle = '' ) {
	?>
	<header class="page-hero">
		<div class="container">
			<?php if ( function_exists( 'ciplev_breadcrumb' ) ) { ciplev_breadcrumb(); } ?>
			<h1 class="page-hero__title"><?php echo esc_html( $title ); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="page-hero__subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</div>
		<span class="flag-bar" aria-hidden="true"></span>
	</header>
	<?php
}

/**
 * Fil d'Ariane simple.
 */
function ciplev_breadcrumb() {
	if ( is_front_page() ) {
		return;
	}
	$items   = array();
	$items[] = '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Accueil', 'ciplev' ) . '</a>';

	if ( is_page() ) {
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
		foreach ( $ancestors as $ancestor ) {
			$items[] = '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
		}
	} elseif ( is_singular( 'post' ) || is_home() || is_archive() ) {
		$blog = (int) get_option( 'page_for_posts' );
		if ( $blog && ! is_home() ) {
			$items[] = '<a href="' . esc_url( get_permalink( $blog ) ) . '">' . esc_html( get_the_title( $blog ) ) . '</a>';
		}
	}

	echo '<nav class="breadcrumb" aria-label="' . esc_attr__( "Fil d'Ariane", 'ciplev' ) . '">' . implode( '<span aria-hidden="true">/</span>', $items ) . '</nav>'; // phpcs:ignore WordPress.Security.EscapeOutput
}

/**
 * Extrait plus court.
 */
add_filter( 'excerpt_length', fn() => 24 );
add_filter( 'excerpt_more', fn() => '…' );

/**
 * Date en français lisible.
 */
function ciplev_posted_on() {
	printf(
		'<time class="meta-date" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
}
