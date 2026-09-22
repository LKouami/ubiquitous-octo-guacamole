<?php
/**
 * Installation automatique du contenu à l'activation du thème :
 * images, pages (blocs Gutenberg modifiables), menus et réglages de lecture.
 *
 * Relançable depuis Outils > Contenu CIPLEV (ne crée que ce qui manque).
 *
 * @package CIPLEV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Petits constructeurs de blocs.
 * ---------------------------------------------------------------------- */

function ciplev_b_h( $text, $level = 2 ) {
	$attrs = 2 === $level ? '' : ' {"level":' . $level . '}';
	return "<!-- wp:heading{$attrs} -->\n<h{$level} class=\"wp-block-heading\">{$text}</h{$level}>\n<!-- /wp:heading -->\n\n";
}

function ciplev_b_p( $html, $class = '' ) {
	if ( $class ) {
		return "<!-- wp:paragraph {\"className\":\"{$class}\"} -->\n<p class=\"{$class}\">{$html}</p>\n<!-- /wp:paragraph -->\n\n";
	}
	return "<!-- wp:paragraph -->\n<p>{$html}</p>\n<!-- /wp:paragraph -->\n\n";
}

function ciplev_b_ul( array $items, $class = '' ) {
	$out = $class
		? "<!-- wp:list {\"className\":\"{$class}\"} -->\n<ul class=\"wp-block-list {$class}\">"
		: "<!-- wp:list -->\n<ul class=\"wp-block-list\">";
	foreach ( $items as $item ) {
		$out .= "<!-- wp:list-item -->\n<li>{$item}</li>\n<!-- /wp:list-item -->";
	}
	return $out . "</ul>\n<!-- /wp:list -->\n\n";
}

function ciplev_b_group( $inner, $class ) {
	return "<!-- wp:group {\"className\":\"{$class}\"} -->\n<div class=\"wp-block-group {$class}\">{$inner}</div>\n<!-- /wp:group -->\n\n";
}

function ciplev_b_cols( array $columns, $class = '' ) {
	$out = $class
		? "<!-- wp:columns {\"className\":\"{$class}\"} -->\n<div class=\"wp-block-columns {$class}\">"
		: "<!-- wp:columns -->\n<div class=\"wp-block-columns\">";
	foreach ( $columns as $col ) {
		$out .= "<!-- wp:column -->\n<div class=\"wp-block-column\">{$col}</div>\n<!-- /wp:column -->\n\n";
	}
	return $out . "</div>\n<!-- /wp:columns -->\n\n";
}

function ciplev_b_table( array $head, array $rows ) {
	$out = "<!-- wp:table -->\n<figure class=\"wp-block-table\"><table class=\"has-fixed-layout\"><thead><tr>";
	foreach ( $head as $cell ) {
		$out .= "<th>{$cell}</th>";
	}
	$out .= '</tr></thead><tbody>';
	foreach ( $rows as $row ) {
		$out .= '<tr>';
		foreach ( $row as $cell ) {
			$out .= "<td>{$cell}</td>";
		}
		$out .= '</tr>';
	}
	return $out . "</tbody></table></figure>\n<!-- /wp:table -->\n\n";
}

function ciplev_b_details( $summary, $inner ) {
	return "<!-- wp:details -->\n<details class=\"wp-block-details\"><summary>{$summary}</summary>{$inner}</details>\n<!-- /wp:details -->\n\n";
}

function ciplev_b_img( $id, $alt = '' ) {
	if ( ! $id ) {
		return '';
	}
	$src = wp_get_attachment_image_url( $id, 'full' );
	$alt = esc_attr( $alt );
	return "<!-- wp:image {\"id\":{$id},\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-full\"><img src=\"{$src}\" alt=\"{$alt}\" class=\"wp-image-{$id}\"/></figure>\n<!-- /wp:image -->\n\n";
}

function ciplev_b_button( $text, $url ) {
	return "<!-- wp:buttons -->\n<div class=\"wp-block-buttons\"><!-- wp:button -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link wp-element-button\" href=\"{$url}\">{$text}</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n\n";
}

function ciplev_b_html( $html ) {
	return "<!-- wp:html -->\n{$html}\n<!-- /wp:html -->\n\n";
}

/**
 * Mention « à compléter » bien visible pour l'équipe éditoriale.
 */
function ciplev_todo( $text ) {
	return ciplev_b_p( '<strong>À compléter :</strong> ' . $text, 'ciplev-todo' );
}

/* -------------------------------------------------------------------------
 * Images.
 * ---------------------------------------------------------------------- */

function ciplev_import_image( $filename, $title ) {
	$existing = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'meta_key'    => '_ciplev_asset', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'  => $filename, // phpcs:ignore WordPress.DB.SlowDBQuery
			'fields'      => 'ids',
			'numberposts' => 1,
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	$path = CIPLEV_DIR . '/assets/img/' . $filename;
	if ( ! file_exists( $path ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$upload = wp_upload_bits( $filename, null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$type = wp_check_filetype( $upload['file'] );
	$id   = wp_insert_attachment(
		array(
			'post_mime_type' => $type['type'],
			'post_title'     => $title,
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $title );
	update_post_meta( $id, '_ciplev_asset', $filename );
	return (int) $id;
}

/* -------------------------------------------------------------------------
 * Contenu des pages (extrait du document « Identité institutionnelle »).
 * ---------------------------------------------------------------------- */

function ciplev_page_definitions( array $img ) {
	$pages = array();

	/* Accueil ------------------------------------------------------------ */
	$pages['accueil'] = array(
		'title'   => 'Accueil',
		'content' => ciplev_b_p( 'La page d’accueil est mise en page par le thème. Le slogan, le texte d’accroche et les coordonnées se modifient dans Apparence › Personnaliser › Options CIPLEV.' ),
	);

	/* Le CIPLEV (présentation) -------------------------------------------- */
	$pages['le-ciplev'] = array(
		'title'   => 'Le CIPLEV',
		'excerpt' => 'Un mécanisme de proximité au service de la paix et de la cohésion sociale au Togo.',
		'content' =>
			ciplev_b_cols(
				array(
					ciplev_b_h( 'Qui sommes-nous ?' )
					. ciplev_b_p( 'Le <strong>Comité Interministériel de Prévention et de Lutte contre l’Extrémisme Violent (CIPLEV)</strong> est un mécanisme visant à renforcer la collaboration avec la population et à marquer la présence de l’État dans les zones affectées ou à risque.', 'is-lead' )
					. ciplev_b_p( 'Il a été créé par le décret n°2019-076/PR du 15 mai 2019. Sa composition reflète une approche gouvernementale globale et permet la participation de la société civile, des femmes et des jeunes aux côtés des acteurs étatiques.' )
					. ciplev_b_group(
						ciplev_b_p( '<strong>Notre message :</strong> la proximité du CIPLEV avec les populations, pour remonter leurs difficultés aux plus hautes autorités.' ),
						'ciplev-callout'
					),
					ciplev_b_img( $img['logo'], 'Logo officiel du CIPLEV' ),
				),
				'ciplev-intro'
			)
			. ciplev_b_h( 'Dénomination officielle' )
			. ciplev_b_ul(
				array(
					'<strong>Dénomination :</strong> Comité Interministériel de Prévention et de Lutte contre l’Extrémisme Violent',
					'<strong>Sigle :</strong> CIPLEV',
					'<strong>Acte de création :</strong> décret n°2019-076/PR du 15 mai 2019',
				)
			)
			. ciplev_b_h( 'Nos valeurs' )
			. ciplev_b_p( 'Les valeurs institutionnelles du CIPLEV sont centrées sur la promotion de la paix, de la cohésion sociale, de la collaboration des populations avec les forces de défense et de sécurité (coproduction de la sécurité), du vivre-ensemble, de l’écoute active, de la gestion pacifique des conflits, de l’harmonie et de la stabilité.' )
			. ciplev_b_cols(
				array(
					ciplev_b_h( 'Proximité', 3 ) . ciplev_b_p( 'Une présence sur le terrain, des échanges réguliers avec les acteurs locaux et une écoute active. Elle permet d’être plus accessible et à l’écoute des communautés, de comprendre leurs préoccupations et d’agir de manière efficace.' ),
					ciplev_b_h( 'Disponibilité', 3 ) . ciplev_b_p( 'Une présence régulière et visible, et une réactivité face aux urgences.' ),
					ciplev_b_h( 'Équité', 3 ) . ciplev_b_p( 'Une approche non discriminatoire et une répartition juste des ressources et des opportunités, pour renforcer la cohésion sociale.' ),
				),
				'ciplev-cards'
			)
			. ciplev_b_p( 'Ces valeurs sont essentielles à la mission du CIPLEV : œuvrer à éradiquer ou à réduire sensiblement la propagation de l’extrémisme violent au Togo.' ),
	);

	/* Historique ----------------------------------------------------------- */
	$pages['historique'] = array(
		'title'   => 'Historique',
		'parent'  => 'le-ciplev',
		'excerpt' => 'De la création par décret présidentiel en 2019 au déploiement des comités locaux.',
		'content' =>
			ciplev_b_h( 'Aux origines du CIPLEV' )
			. ciplev_b_p( 'Le Gouvernement a réalisé qu’une approche purement militaire ne pourrait suffire à elle seule à régler la question du terrorisme, qui doit être envisagée de façon globale et holistique.' )
			. ciplev_b_p( 'Il est alors apparu important de mettre en place une approche préventive plus souple, afin de prendre en considération les facteurs ou les causes profondes pouvant conduire les individus à basculer vers la radicalisation, l’extrémisme violent, voire le terrorisme.' )
			. ciplev_b_p( 'D’où la création, par décret du Président de la République (<strong>décret n°2019-076/PR du 15 mai 2019</strong>), du Comité Interministériel de Prévention et de Lutte contre l’Extrémisme Violent, dont la composition reflète une approche gouvernementale globale et permet la participation de la société civile, des femmes et des jeunes aux côtés des acteurs étatiques.' )
			. ciplev_b_h( 'Dates clés' )
			. ciplev_b_table(
				array( 'Période', 'Activité' ),
				array(
					array( '15 mai 2019', 'Création du CIPLEV' ),
					array( '30 décembre 2019', 'Recrutement du personnel d’appui' ),
					array( '<em>[date à préciser]</em>', 'Installation des comités locaux de la région des Savanes' ),
					array( '08 au 10 février 2021', 'Installation des comités locaux de la région de la Kara' ),
					array( '13 au 15 juillet 2021', 'Installation des comités locaux de la région Centrale' ),
					array( 'Novembre 2022', 'Projet de prévention de l’extrémisme violent à travers le renforcement des capacités opérationnelles du CIPLEV' ),
				)
			)
			. ciplev_b_h( 'Actions menées dans le cadre du projet de renforcement des capacités', 3 )
			. ciplev_b_ul(
				array(
					'Formation des conducteurs de taxi-motos sur leur rôle dans la prévention et la lutte contre l’extrémisme violent',
					'Formation des professionnelles des médias sur leur rôle dans la prévention de l’extrémisme violent',
					'Formation des femmes leaders communautaires sur leur rôle dans la prévention de l’extrémisme violent',
					'Campagne de communication par affichage',
					'Formation des jeunes et des femmes sur leur rôle dans la prévention de l’extrémisme violent',
				)
			),
	);

	/* Responsables --------------------------------------------------------- */
	$person = function ( $img_id, $name, $period, $label ) {
		return ciplev_b_group(
			ciplev_b_img( $img_id, $name )
			. ciplev_b_p( $label, 'ciplev-person__label' )
			. ciplev_b_h( $name, 3 )
			. ciplev_b_p( $period, 'ciplev-person__period' ),
			'ciplev-person'
		);
	};

	$pages['responsables'] = array(
		'title'   => 'Responsables',
		'parent'  => 'le-ciplev',
		'excerpt' => 'Les responsables en fonction et ceux qui les ont précédés.',
		'content' =>
			ciplev_b_h( 'Comité de suivi' )
			. ciplev_b_cols(
				array(
					$person( $img['madjoulba'], 'Col Calixte Batossie MADJOULBA', '2023 à nos jours', 'Président en fonction' ),
					$person( $img['yark'], 'Gal Damehame YARK', '2019 – 2023', 'Ancien président' ),
				),
				'ciplev-people'
			)
			. ciplev_b_h( 'Comité technique (interministériel)' )
			. ciplev_b_cols(
				array(
					$person( $img['kadja'], 'CD Hodabalo Pitemnwé KADJA', '2024 à nos jours', 'Président en fonction' ),
					$person( $img['akobi'], 'Feu Col Félix AKOBI', '2019 – 2024', 'Ancien président' ),
				),
				'ciplev-people'
			)
			. ciplev_b_h( 'Secrétariat permanent' )
			. ciplev_b_table(
				array( 'Fonction', 'Responsable en fonction', 'Responsable passé' ),
				array(
					array( 'Secrétaire permanent', 'Col Nikabou LABANTE (2022 à nos jours)', 'Col Kpatcha MELEOU (2019 – 2022)' ),
					array( 'Secrétaire permanent adjoint', 'CE Koffi DZODZINAWO (2023 à nos jours)', '—' ),
				)
			),
	);

	/* Mot du Président ----------------------------------------------------- */
	$pages['mot-du-president'] = array(
		'title'   => 'Mot du Président',
		'parent'  => 'le-ciplev',
		'content' =>
			ciplev_todo( 'proposition de message à soumettre au Président pour validation avant publication.' )
			. ciplev_b_cols(
				array(
					ciplev_b_img( $img['madjoulba'], 'Col Calixte Batossie MADJOULBA' )
					. ciplev_b_p( '<strong>Col Calixte Batossie MADJOULBA</strong><br>Président du Comité de suivi', 'ciplev-signature' ),
					ciplev_b_p( 'Chères concitoyennes, chers concitoyens,', 'is-lead' )
					. ciplev_b_p( 'Face à la menace de l’extrémisme violent, le Togo a fait un choix clair : celui d’une réponse globale, qui ne repose pas uniquement sur la force, mais aussi sur la prévention, le dialogue et la confiance entre l’État et les populations.' )
					. ciplev_b_p( 'C’est tout le sens du CIPLEV. Depuis 2019, nos comités locaux et nos agents d’appui vont à la rencontre des communautés, écoutent leurs préoccupations et les portent jusqu’aux plus hautes autorités. Dans les régions des Savanes, de la Kara et Centrale, ce travail de proximité a permis de renforcer les liens entre les populations et les forces de défense et de sécurité.' )
					. ciplev_b_p( 'La sécurité est l’affaire de tous. Chaque citoyen, chaque leader communautaire, chaque jeune et chaque femme a un rôle à jouer pour préserver la paix et la cohésion sociale de notre pays.' )
					. ciplev_b_p( 'Ce site est un nouvel espace d’information et d’échange. Je vous invite à le parcourir, à mieux connaître nos missions et à vous rapprocher de nos comités locaux.' )
					. ciplev_b_p( 'Ensemble, prévenons, protégeons et construisons un Togo en paix.' ),
				),
				'ciplev-message'
			),
	);

	/* Missions et actions -------------------------------------------------- */
	$pages['missions-et-actions'] = array(
		'title'   => 'Missions et actions',
		'excerpt' => 'Prévenir, sensibiliser, dialoguer : les missions et le travail de terrain du CIPLEV.',
		'content' =>
			ciplev_b_h( 'Nos missions' )
			. ciplev_b_cols(
				array(
					ciplev_b_h( 'Éradiquer ou réduire l’extrémisme violent', 3 ) . ciplev_b_p( 'Éradiquer ou réduire sensiblement la propagation de l’extrémisme violent sur l’ensemble du territoire national, et particulièrement dans les zones affectées ou à risque, en donnant aux communautés de base les outils et le soutien dont elles ont besoin pour résister à ce fléau.' ),
					ciplev_b_h( 'Renforcer la coopération', 3 ) . ciplev_b_p( 'Renforcer la coopération et la collaboration entre l’administration, les forces de défense et de sécurité et la société civile dans la prévention et la lutte contre l’extrémisme violent.' ),
				),
				'ciplev-cards'
			)
			. ciplev_b_h( 'Nos activités' )
			. ciplev_b_p( 'Le CIPLEV met en œuvre une approche de prévention et de lutte contre l’extrémisme violent aux côtés des forces de défense et de sécurité (FDS) à travers les actions suivantes :' )
			. ciplev_b_ul(
				array(
					'Recueillir et analyser les informations et les données sur les zones à risque ;',
					'Évaluer la menace et identifier les zones affectées ou à risque ;',
					'Recueillir les données sur les besoins prioritaires des zones affectées ou à risque ;',
					'Sensibiliser la population en général et celle des zones à risque en particulier ;',
					'Promouvoir le dialogue, l’écoute et la confiance entre les pouvoirs publics et les populations vulnérables ou vivant dans les zones à risque, de façon permanente, à travers des échanges et des visites de terrain, afin d’apporter des réponses holistiques à leurs préoccupations et de marquer la présence de l’État ;',
					'Faciliter la réalisation de projets visant à obtenir l’adhésion et une meilleure collaboration de la population dans les zones affectées ou à risque ;',
					'Identifier et éradiquer les sources de conflit et de méfiance entre les FDS et la population à la base ;',
					'Créer un climat de confiance et de collaboration entre les FDS et la population.',
				),
				'ciplev-checklist'
			)
			. ciplev_b_h( 'Services aux populations' )
			. ciplev_b_group(
				ciplev_b_p( 'Les <strong>comités locaux de prévention et de lutte contre l’extrémisme violent</strong> (CLPLEV), appuyés par les agents d’appui du CIPLEV, ont pour mission d’intégrer les communautés et d’échanger régulièrement avec les populations pour recueillir leurs besoins prioritaires.' )
				. ciplev_b_p( 'Ils organisent également des séances d’information, de formation et de sensibilisation à l’endroit des populations sur les thématiques en lien avec l’extrémisme violent.' ),
				'ciplev-callout'
			)
			. ciplev_b_button( 'Contacter le CIPLEV', esc_url( home_url( '/contact/' ) ) ),
	);

	/* Organisation --------------------------------------------------------- */
	$pages['organisation'] = array(
		'title'   => 'Organisation',
		'excerpt' => 'Trois niveaux de responsabilité et un organe de liaison.',
		'content' =>
			ciplev_b_p( 'Le CIPLEV s’articule autour de <strong>trois niveaux de responsabilité</strong> — ministériel, technique et local — reliés par un organe de liaison, le <strong>Secrétariat permanent</strong>, qui abrite le siège de l’institution. Les comités locaux sont appuyés dans l’exercice de leurs fonctions par des agents d’appui.', 'is-lead' )
			. ciplev_b_html( ciplev_org_chart_html() )

			. ciplev_b_h( 'Comité de suivi — niveau ministériel' )
			. ciplev_b_cols(
				array(
					ciplev_b_h( 'Composition', 3 ) . ciplev_b_ul(
						array(
							'Le ministre de la Sécurité (président)',
							'Le ministre chargé de l’Administration territoriale (vice-président)',
							'Le ministre chargé de la Défense (membre)',
							'Le ministre chargé des Finances (membre)',
							'Le ministre chargé de l’Action sociale (membre)',
							'Le ministre chargé du Développement à la base (membre)',
						)
					),
					ciplev_b_h( 'Mission', 3 ) . ciplev_b_p( 'Le comité de suivi veille à la mise en œuvre des orientations définies par le Gouvernement dans le domaine de la prévention et de la lutte contre l’extrémisme violent.' )
					. ciplev_b_h( 'Fonctionnement', 3 ) . ciplev_b_p( 'Il se réunit au moins une fois par trimestre et chaque fois que de besoin, sur convocation de son président (art. 14).' ),
				)
			)

			. ciplev_b_h( 'Comité interministériel — niveau technique' )
			. ciplev_b_cols(
				array(
					ciplev_b_h( 'Composition : 15 membres', 3 ) . ciplev_b_ul(
						array(
							'Deux représentants du ministère de la Sécurité',
							'Un représentant du ministère chargé de l’Administration territoriale',
							'Un représentant de la Primature',
							'Un représentant du ministère de la Défense (cabinet)',
							'Un représentant du ministère chargé des Finances',
							'Un représentant du ministère chargé de l’Action sociale',
							'Un représentant du ministère du Développement à la base',
							'Un représentant du ministère chargé de l’Agriculture',
							'Un représentant de l’état-major général des FAT (opérations)',
							'Un représentant des confessions religieuses (catholique, protestante et musulmane)',
							'Un représentant du ministère <em>[à préciser]</em>',
							'Un représentant du ministère chargé du Tourisme',
							'Un représentant du ministère chargé du Secteur privé',
						)
					),
					ciplev_b_h( 'Fonctionnement', 3 )
					. ciplev_b_p( 'Les membres sont nommés par arrêté du ministre chargé de la Sécurité pour une période de trois ans, renouvelable une fois, sur proposition de leurs structures de tutelle.' )
					. ciplev_b_p( 'Le comité se réunit en session plénière au moins une fois par trimestre, sur convocation de son président ou à la demande de ses membres. Il établit son règlement intérieur et est dirigé par un bureau exécutif composé :' )
					. ciplev_b_ul(
						array(
							'du ministère chargé de la Sécurité, président ;',
							'du ministère chargé de l’Administration territoriale, 1<sup>er</sup> vice-président ;',
							'du ministre chargé de la Défense, 2<sup>e</sup> vice-président ;',
							'd’un représentant de la société civile, 1<sup>er</sup> rapporteur ;',
							'd’un représentant du ministère chargé de l’Action sociale, 2<sup>e</sup> rapporteur.',
						)
					),
				)
			)
			. ciplev_b_h( 'Missions du comité interministériel', 3 )
			. ciplev_b_ul(
				array(
					'Recueillir et analyser les informations et les données sur les zones à risque',
					'Évaluer les menaces et identifier les zones affectées ou à risque',
					'Recueillir des données sur les besoins prioritaires des zones affectées ou à risque',
					'Sensibiliser la population en général et celle des zones à risque en particulier',
					'Promouvoir le dialogue, l’écoute et la confiance entre les pouvoirs publics et les populations vulnérables ou vivant dans les zones à risque',
					'Faciliter la réalisation des projets visant à obtenir l’adhésion et une meilleure collaboration de la population',
					'Identifier et éradiquer les sources de conflit et de méfiance entre les FDS et la population à la base',
					'Créer un climat de confiance et de collaboration entre les FDS et les populations',
				),
				'ciplev-checklist'
			)

			. ciplev_b_h( 'Comités locaux — niveau local de prévention' )
			. ciplev_b_cols(
				array(
					ciplev_b_h( 'Composition', 3 ) . ciplev_b_ul(
						array(
							'Les comités préfectoraux de prévention et de lutte contre l’extrémisme violent',
							'Les comités cantonaux de prévention et de lutte contre l’extrémisme violent',
						)
					)
					. ciplev_b_h( 'Fonctionnement', 3 ) . ciplev_b_p( 'Le CIPLEV procède à l’installation progressive des CLPLEV en tenant compte de l’urgence et des zones affectées ou à risque. Les comités locaux sont appuyés par les agents d’appui du CIPLEV.' ),
					ciplev_b_h( 'Missions', 3 ) . ciplev_b_ul(
						array(
							'Recueillir et transmettre au CIPLEV les informations et données sur les zones à risque',
							'Évaluer la menace et identifier les zones affectées ou à risque',
							'Recueillir des données prioritaires des zones affectées ou à risque',
							'Sensibiliser la population',
							'Promouvoir le dialogue, l’écoute et la confiance entre les pouvoirs publics et les populations',
							'Faciliter la réalisation des projets afin d’y marquer la présence de l’État',
							'Créer un climat de confiance et de collaboration entre les forces de sécurité et la population',
							'Proposer au CIPLEV toutes mesures ou actions susceptibles de contribuer à la prévention et à la lutte contre l’extrémisme violent',
						)
					),
				)
			)

			. ciplev_b_h( 'Secrétariat permanent — organe de liaison' )
			. ciplev_b_cols(
				array(
					ciplev_b_h( 'Composition', 3 ) . ciplev_b_ul(
						array(
							'Un secrétaire permanent',
							'Un secrétaire permanent adjoint',
							'Un département des Opérations',
							'Un département Projets, planification, suivi et évaluation',
							'Un département de la Communication, des relations publiques et de la documentation',
							'Un département des Ressources humaines et de l’administration',
						)
					),
					ciplev_b_h( 'Mission', 3 ) . ciplev_b_p( 'Servir de liaison entre le comité de suivi, le comité technique et les comités locaux. Le Secrétariat permanent abrite le siège de l’institution.' )
					. ciplev_b_h( 'Fonctionnement', 3 ) . ciplev_b_p( 'Le Secrétariat permanent fonctionne comme tous les services de l’État. Les agents d’appui remontent les informations par un canal sécurisé au département des Opérations, qui les transmet à l’autorité compétente.' ),
				)
			),
	);

	/* Actualités (page des articles) --------------------------------------- */
	$pages['actualites'] = array(
		'title'   => 'Actualités',
		'content' => '',
	);

	/* FAQ ------------------------------------------------------------------ */
	$answer_todo = ciplev_b_p( '<em>Réponse à rédiger et à valider par le CIPLEV.</em>', 'ciplev-todo' );
	$pages['faq'] = array(
		'title'   => 'Foire aux questions',
		'excerpt' => 'Les réponses aux questions les plus fréquentes sur le CIPLEV et l’extrémisme violent.',
		'content' =>
			ciplev_b_details(
				'Quelle est la mission du CIPLEV ?',
				ciplev_b_p( 'Le CIPLEV a pour mission d’éradiquer ou de réduire sensiblement la propagation de l’extrémisme violent sur l’ensemble du territoire national, particulièrement dans les zones affectées ou à risque, en donnant aux communautés de base les outils et le soutien nécessaires pour y résister. Il renforce également la coopération entre l’administration, les forces de défense et de sécurité et la société civile.' )
			)
			. ciplev_b_details( 'Quelle différence entre terrorisme et djihadisme ?', $answer_todo )
			. ciplev_b_details( 'Quelle est la source de financement des GAT ?', $answer_todo )
			. ciplev_b_details( 'D’où proviennent les armes des GAT ?', $answer_todo )
			. ciplev_b_details( 'Quel est le mode de recrutement des GAT ?', $answer_todo )
			. ciplev_b_details( 'Le terrorisme n’est-il pas la conséquence de la mauvaise gouvernance ?', $answer_todo )
			. ciplev_b_details( 'Que veulent concrètement les GAT ?', $answer_todo )
			. ciplev_b_details(
				'Comment se composent les CLPLEV ?',
				ciplev_b_p( 'Les comités locaux de prévention et de lutte contre l’extrémisme violent (CLPLEV) regroupent les comités préfectoraux et les comités cantonaux. Ils sont installés progressivement par le CIPLEV en tenant compte de l’urgence et des zones affectées ou à risque.' )
				. ciplev_b_p( '<em>Détail de la composition des membres à compléter.</em>', 'ciplev-todo' )
			)
			. ciplev_b_details(
				'Quel est le travail des agents d’appui sur le terrain ?',
				ciplev_b_p( 'Les agents d’appui accompagnent les comités locaux. Ils s’intègrent aux communautés, échangent régulièrement avec les populations pour recueillir leurs besoins prioritaires, participent aux séances d’information, de formation et de sensibilisation, et remontent les informations par un canal sécurisé au département des Opérations du Secrétariat permanent.' )
			)
			. ciplev_b_details(
				'Quel est le rôle de la jeunesse dans la prévention et la lutte contre l’extrémisme violent ?',
				ciplev_b_p( 'La composition du CIPLEV permet la participation des jeunes aux côtés des acteurs étatiques, et des formations sont organisées pour renforcer leur rôle dans la prévention de l’extrémisme violent.' )
				. ciplev_b_p( '<em>Réponse à développer par le CIPLEV.</em>', 'ciplev-todo' )
			)
			. ciplev_b_p( 'Vous ne trouvez pas la réponse à votre question ? <a href="' . esc_url( home_url( '/contact/' ) ) . '">Contactez-nous</a>.' ),
	);

	/* Contact -------------------------------------------------------------- */
	$pages['contact'] = array(
		'title'   => 'Contact',
		'excerpt' => 'Le Secrétariat permanent du CIPLEV est à votre écoute.',
		'content' =>
			ciplev_b_cols(
				array(
					ciplev_b_h( 'Siège du CIPLEV' )
					. ciplev_b_p( 'Secrétariat permanent<br>Lomé, Adjidogomé — carrefour La Pampa<br>Togo' )
					. ciplev_b_h( 'Téléphone', 3 )
					. ciplev_b_p( '<a href="tel:+22892951997">(+228) 92 95 19 97</a><br><a href="tel:+22898831409">(+228) 98 83 14 09</a>' )
					. ciplev_b_h( 'E-mail', 3 )
					. ciplev_todo( 'adresse e-mail officielle.' )
					. ciplev_b_h( 'Au plus près de chez vous', 3 )
					. ciplev_b_p( 'Dans les régions des Savanes, de la Kara et Centrale, rapprochez-vous du comité local de prévention (CLPLEV) de votre préfecture ou de votre canton.' ),
					ciplev_b_html( '<div class="ciplev-map"><iframe title="Carte : siège du CIPLEV à Adjidogomé, Lomé" src="https://maps.google.com/maps?q=Adjidogom%C3%A9%2C%20Lom%C3%A9%2C%20Togo&amp;z=14&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>' ),
				),
				'ciplev-contact'
			),
	);

	/* Mentions légales ----------------------------------------------------- */
	$pages['mentions-legales'] = array(
		'title'   => 'Mentions légales',
		'content' =>
			ciplev_b_h( 'Éditeur du site' )
			. ciplev_b_p( 'Comité Interministériel de Prévention et de Lutte contre l’Extrémisme Violent (CIPLEV)<br>Secrétariat permanent — Lomé, Adjidogomé, carrefour La Pampa, Togo<br>Téléphone : (+228) 92 95 19 97 / 98 83 14 09' )
			. ciplev_b_h( 'Directeur de la publication' )
			. ciplev_todo( 'nom et fonction du directeur de la publication.' )
			. ciplev_b_h( 'Hébergement' )
			. ciplev_todo( 'nom et adresse de l’hébergeur.' )
			. ciplev_b_h( 'Propriété intellectuelle' )
			. ciplev_b_p( 'Le logo, les textes et les photographies publiés sur ce site sont la propriété du CIPLEV, sauf mention contraire. Toute reproduction sans autorisation préalable est interdite.' ),
	);

	return $pages;
}

/**
 * Schéma d'organisation (HTML statique, stylé par le thème).
 */
function ciplev_org_chart_html() {
	return '<div class="org-chart" role="img" aria-label="Organigramme : Comité de suivi, Comité interministériel et Comités locaux, reliés par le Secrétariat permanent">'
		. '<div class="org-chart__levels">'
		. '<div class="org-chart__box"><span>Niveau ministériel</span><strong>Comité de suivi</strong></div>'
		. '<div class="org-chart__box"><span>Niveau technique</span><strong>Comité interministériel</strong></div>'
		. '<div class="org-chart__box"><span>Niveau local</span><strong>Comités locaux</strong><em>préfectoraux et cantonaux · agents d’appui</em></div>'
		. '</div>'
		. '<div class="org-chart__liaison"><span>Organe de liaison · siège</span><strong>Secrétariat permanent</strong></div>'
		. '</div>';
}

/* -------------------------------------------------------------------------
 * Installation.
 * ---------------------------------------------------------------------- */

function ciplev_install_content() {
	$img = array(
		'logo'      => ciplev_import_image( 'logo-ciplev.png', 'Logo officiel du CIPLEV' ),
		'madjoulba' => ciplev_import_image( 'madjoulba.jpg', 'Col Calixte Batossie MADJOULBA' ),
		'yark'      => ciplev_import_image( 'yark.jpg', 'Gal Damehame YARK' ),
		'kadja'     => ciplev_import_image( 'kadja.jpg', 'CD Hodabalo Pitemnwé KADJA' ),
		'akobi'     => ciplev_import_image( 'akobi.jpg', 'Feu Col Félix AKOBI' ),
	);

	if ( $img['logo'] && ! get_theme_mod( 'custom_logo' ) ) {
		set_theme_mod( 'custom_logo', $img['logo'] );
	}

	// Permaliens lisibles (nécessaires aux liens internes /contact/…).
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	$ids   = array();
	$order = 0;
	foreach ( ciplev_page_definitions( $img ) as $slug => $def ) {
		$parent_id = isset( $def['parent'] ) ? ( $ids[ $def['parent'] ] ?? 0 ) : 0;
		$path      = isset( $def['parent'] ) ? $def['parent'] . '/' . $slug : $slug;
		$existing  = get_page_by_path( $path );

		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			continue;
		}

		$ids[ $slug ] = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $def['title'],
				'post_name'    => $slug,
				'post_content' => $def['content'],
				'post_excerpt' => $def['excerpt'] ?? '',
				'post_parent'  => $parent_id,
				'menu_order'   => $order++,
			)
		);
	}

	// Lecture : page d'accueil statique + page des actualités.
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['accueil'] );
	update_option( 'page_for_posts', $ids['actualites'] );

	// Identité du site.
	if ( in_array( get_option( 'blogname' ), array( '', 'My WordPress Website', 'Mon site WordPress', 'Mon site' ), true ) ) {
		update_option( 'blogname', 'CIPLEV' );
	}
	update_option( 'blogdescription', 'Comité Interministériel de Prévention et de Lutte contre l’Extrémisme Violent' );
	if ( ! get_option( 'timezone_string' ) ) {
		update_option( 'timezone_string', 'Africa/Lome' );
	}
	update_option( 'date_format', 'j F Y' );

	ciplev_install_menus( $ids );

	// Supprime la page d'exemple et l'article « Bonjour tout le monde » d'une installation neuve.
	foreach ( array( 'sample-page', 'page-d-exemple' ) as $sample ) {
		$p = get_page_by_path( $sample );
		if ( $p && 'publish' === $p->post_status ) {
			wp_trash_post( $p->ID );
		}
	}
	$hello = get_page_by_path( 'hello-world', OBJECT, 'post' ) ?: get_page_by_path( 'bonjour-tout-le-monde', OBJECT, 'post' );
	if ( $hello ) {
		wp_trash_post( $hello->ID );
	}

	flush_rewrite_rules();
	update_option( 'ciplev_installed', CIPLEV_VERSION );
}

function ciplev_install_menus( array $ids ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );

	if ( empty( $locations['primary'] ) || ! wp_get_nav_menu_object( $locations['primary'] ) ) {
		$menu_id = wp_get_nav_menu_object( 'Menu principal' ) ? wp_get_nav_menu_object( 'Menu principal' )->term_id : wp_create_nav_menu( 'Menu principal' );
		if ( ! is_wp_error( $menu_id ) ) {
			$add = function ( $slug, $parent = 0, $title = '' ) use ( $menu_id, $ids ) {
				return wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-object-id' => $ids[ $slug ],
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
						'menu-item-parent-id' => $parent,
						'menu-item-title'     => $title,
					)
				);
			};
			$add( 'accueil' );
			$about = $add( 'le-ciplev' );
			$add( 'le-ciplev', $about, 'Présentation' );
			$add( 'historique', $about );
			$add( 'responsables', $about );
			$add( 'mot-du-president', $about );
			$add( 'missions-et-actions' );
			$add( 'organisation' );
			$add( 'actualites' );
			$add( 'faq', 0, 'FAQ' );
			$add( 'contact' );
			$locations['primary'] = $menu_id;
		}
	}

	if ( empty( $locations['footer'] ) || ! wp_get_nav_menu_object( $locations['footer'] ) ) {
		$menu_id = wp_get_nav_menu_object( 'Pied de page' ) ? wp_get_nav_menu_object( 'Pied de page' )->term_id : wp_create_nav_menu( 'Pied de page' );
		if ( ! is_wp_error( $menu_id ) ) {
			foreach ( array( 'le-ciplev', 'missions-et-actions', 'organisation', 'actualites', 'faq', 'contact', 'mentions-legales' ) as $slug ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-object-id' => $ids[ $slug ],
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
						'menu-item-title'     => 'faq' === $slug ? 'FAQ' : '',
					)
				);
			}
			$locations['footer'] = $menu_id;
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Lancement automatique à la première activation.
 */
function ciplev_maybe_install() {
	if ( ! get_option( 'ciplev_installed' ) ) {
		ciplev_install_content();
		set_transient( 'ciplev_installed_notice', 1, 60 );
	}
}
add_action( 'after_switch_theme', 'ciplev_maybe_install' );

/**
 * Outils > Contenu CIPLEV : relancer l'installation (ne recrée que ce qui manque).
 */
function ciplev_tools_page() {
	add_management_page( 'Contenu CIPLEV', 'Contenu CIPLEV', 'manage_options', 'ciplev-content', 'ciplev_tools_page_render' );
}
add_action( 'admin_menu', 'ciplev_tools_page' );

function ciplev_tools_page_render() {
	?>
	<div class="wrap">
		<h1>Contenu CIPLEV</h1>
		<p>Recrée les pages, images et menus du site CIPLEV qui manqueraient. Les pages existantes ne sont jamais modifiées.</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="ciplev_reinstall">
			<?php wp_nonce_field( 'ciplev_reinstall' ); ?>
			<?php submit_button( 'Installer le contenu manquant' ); ?>
		</form>
	</div>
	<?php
}

function ciplev_handle_reinstall() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Accès refusé.' );
	}
	check_admin_referer( 'ciplev_reinstall' );
	ciplev_install_content();
	set_transient( 'ciplev_installed_notice', 1, 60 );
	wp_safe_redirect( admin_url( 'tools.php?page=ciplev-content' ) );
	exit;
}
add_action( 'admin_post_ciplev_reinstall', 'ciplev_handle_reinstall' );

function ciplev_admin_notice() {
	if ( get_transient( 'ciplev_installed_notice' ) ) {
		delete_transient( 'ciplev_installed_notice' );
		echo '<div class="notice notice-success is-dismissible"><p><strong>CIPLEV :</strong> les pages, menus et images ont été installés. Choisissez le slogan dans <a href="' . esc_url( admin_url( 'customize.php?autofocus[panel]=ciplev_panel' ) ) . '">Apparence › Personnaliser › Options CIPLEV</a>.</p></div>';
	}
}
add_action( 'admin_notices', 'ciplev_admin_notice' );
