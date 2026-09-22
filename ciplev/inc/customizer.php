<?php
/**
 * Options du thème (Apparence > Personnaliser > Options CIPLEV).
 *
 * @package CIPLEV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les slogans proposés dans le document d'identité institutionnelle.
 */
function ciplev_slogans() {
	return array(
		'1'      => 'La cohésion, notre force',
		'2'      => 'La sécurité par nous et pour nous',
		'3'      => 'Protéger ensemble, bâtir pour tous',
		'4'      => 'Protéger et bâtir ensemble',
		'5'      => 'Que le dialogue prime',
		'6'      => 'Prévenir, protéger pour construire',
		'7'      => 'Pour la paix, chaque voix compte',
		'8'      => 'Cultivons la paix, déracinons la haine',
		'9'      => 'Prévenir les conflits par le dialogue',
		'custom' => '— Slogan personnalisé (champ ci-dessous) —',
	);
}

/**
 * Valeurs par défaut.
 */
function ciplev_defaults() {
	return array(
		'ciplev_slogan'        => '6',
		'ciplev_slogan_custom' => '',
		'ciplev_hero_text'     => 'Proche des populations, le CIPLEV porte leurs préoccupations jusqu’aux plus hautes autorités et marque la présence de l’État dans les zones affectées ou à risque.',
		'ciplev_address'       => 'Lomé, Adjidogomé — carrefour La Pampa',
		'ciplev_phone_1'       => '92 95 19 97',
		'ciplev_phone_2'       => '98 83 14 09',
		'ciplev_email'         => '',
		'ciplev_hours'         => '',
		'ciplev_facebook'      => '',
		'ciplev_x'             => '',
		'ciplev_youtube'       => '',
		'ciplev_linkedin'      => '',
	);
}

/**
 * Lecture d'une option avec sa valeur par défaut.
 */
function ciplev_opt( $key ) {
	$defaults = ciplev_defaults();
	return get_theme_mod( $key, $defaults[ $key ] ?? '' );
}

/**
 * Slogan retenu (sans le préfixe « CIPLEV : »).
 */
function ciplev_slogan() {
	$choice = ciplev_opt( 'ciplev_slogan' );
	if ( 'custom' === $choice ) {
		return trim( (string) ciplev_opt( 'ciplev_slogan_custom' ) );
	}
	$slogans = ciplev_slogans();
	return $slogans[ $choice ] ?? '';
}

/**
 * Numéro formaté pour un lien tel: (indicatif Togo +228).
 */
function ciplev_tel_href( $number ) {
	$digits = preg_replace( '/\D+/', '', (string) $number );
	if ( 8 === strlen( $digits ) ) {
		$digits = '228' . $digits;
	}
	return 'tel:+' . $digits;
}

function ciplev_customize_register( $wp_customize ) {
	$defaults = ciplev_defaults();

	$wp_customize->add_panel(
		'ciplev_panel',
		array(
			'title'    => __( 'Options CIPLEV', 'ciplev' ),
			'priority' => 30,
		)
	);

	// Identité.
	$wp_customize->add_section( 'ciplev_identity', array( 'title' => __( 'Slogan & accueil', 'ciplev' ), 'panel' => 'ciplev_panel' ) );

	$wp_customize->add_setting( 'ciplev_slogan', array( 'default' => $defaults['ciplev_slogan'], 'sanitize_callback' => 'ciplev_sanitize_slogan' ) );
	$wp_customize->add_control(
		'ciplev_slogan',
		array(
			'label'       => __( 'Slogan retenu', 'ciplev' ),
			'description' => __( 'Les 9 propositions du document d’identité institutionnelle. Affiché sous la forme « CIPLEV : … ».', 'ciplev' ),
			'section'     => 'ciplev_identity',
			'type'        => 'select',
			'choices'     => ciplev_slogans(),
		)
	);

	$wp_customize->add_setting( 'ciplev_slogan_custom', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'ciplev_slogan_custom', array( 'label' => __( 'Slogan personnalisé', 'ciplev' ), 'section' => 'ciplev_identity', 'type' => 'text' ) );

	$wp_customize->add_setting( 'ciplev_hero_text', array( 'default' => $defaults['ciplev_hero_text'], 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'ciplev_hero_text', array( 'label' => __( 'Texte d’accroche de la page d’accueil', 'ciplev' ), 'section' => 'ciplev_identity', 'type' => 'textarea' ) );

	$wp_customize->add_setting( 'ciplev_blason', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'ciplev_blason',
			array(
				'label'       => __( 'Bloc blason officiel', 'ciplev' ),
				'description' => __( 'Fichier officiel fourni par l’État (armoiries, mention « République Togolaise », filet vert et ministère de tutelle). Ne pas modifier. Par défaut : bloc « Ministère de la Sécurité » fourni avec le thème.', 'ciplev' ),
				'section'     => 'ciplev_identity',
				'mime_type'   => 'image',
			)
		)
	);

	// Coordonnées.
	$wp_customize->add_section( 'ciplev_contact', array( 'title' => __( 'Coordonnées', 'ciplev' ), 'panel' => 'ciplev_panel' ) );
	$fields = array(
		'ciplev_address' => array( __( 'Adresse du siège', 'ciplev' ), 'text', 'sanitize_text_field' ),
		'ciplev_phone_1' => array( __( 'Téléphone 1', 'ciplev' ), 'text', 'sanitize_text_field' ),
		'ciplev_phone_2' => array( __( 'Téléphone 2', 'ciplev' ), 'text', 'sanitize_text_field' ),
		'ciplev_email'   => array( __( 'E-mail', 'ciplev' ), 'email', 'sanitize_email' ),
		'ciplev_hours'   => array( __( 'Horaires', 'ciplev' ), 'text', 'sanitize_text_field' ),
	);
	foreach ( $fields as $id => $f ) {
		$wp_customize->add_setting( $id, array( 'default' => $defaults[ $id ], 'sanitize_callback' => $f[2] ) );
		$wp_customize->add_control( $id, array( 'label' => $f[0], 'section' => 'ciplev_contact', 'type' => $f[1] ) );
	}

	// Réseaux sociaux.
	$wp_customize->add_section( 'ciplev_social', array( 'title' => __( 'Réseaux sociaux', 'ciplev' ), 'panel' => 'ciplev_panel' ) );
	foreach ( array( 'ciplev_facebook' => 'Facebook', 'ciplev_x' => 'X (Twitter)', 'ciplev_youtube' => 'YouTube', 'ciplev_linkedin' => 'LinkedIn' ) as $id => $label ) {
		$wp_customize->add_setting( $id, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( $id, array( 'label' => $label, 'section' => 'ciplev_social', 'type' => 'url' ) );
	}
}
add_action( 'customize_register', 'ciplev_customize_register' );

function ciplev_sanitize_slogan( $value ) {
	return array_key_exists( $value, ciplev_slogans() ) ? $value : '6';
}

/**
 * Liens vers les réseaux sociaux renseignés.
 */
function ciplev_social_links() {
	$links = array(
		'facebook' => ciplev_opt( 'ciplev_facebook' ),
		'x'        => ciplev_opt( 'ciplev_x' ),
		'youtube'  => ciplev_opt( 'ciplev_youtube' ),
		'linkedin' => ciplev_opt( 'ciplev_linkedin' ),
	);
	$links = array_filter( $links );
	if ( ! $links ) {
		return;
	}
	echo '<ul class="social">';
	foreach ( $links as $network => $url ) {
		printf(
			'<li><a href="%1$s" target="_blank" rel="noopener" aria-label="%2$s">%3$s</a></li>',
			esc_url( $url ),
			esc_attr( ucfirst( $network ) ),
			ciplev_icon( $network ) // phpcs:ignore WordPress.Security.EscapeOutput
		);
	}
	echo '</ul>';
}
