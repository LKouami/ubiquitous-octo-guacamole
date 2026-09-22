<?php
/**
 * Page d'accueil.
 *
 * @package CIPLEV
 */

get_header();

$ciplev_slogan = ciplev_slogan();
$ciplev_link   = function ( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
};
?>

<section class="hero">
	<div class="container hero__inner">
		<div class="hero__content">
			<p class="eyebrow eyebrow--light"><?php esc_html_e( 'République Togolaise', 'ciplev' ); ?></p>
			<h1 class="hero__title"><?php esc_html_e( 'Comité Interministériel de Prévention et de Lutte contre l’Extrémisme Violent', 'ciplev' ); ?></h1>
			<?php if ( $ciplev_slogan ) : ?>
				<p class="hero__slogan"><span>CIPLEV :</span> <?php echo esc_html( $ciplev_slogan ); ?></p>
			<?php endif; ?>
			<p class="hero__text"><?php echo esc_html( ciplev_opt( 'ciplev_hero_text' ) ); ?></p>
			<div class="hero__actions">
				<a class="btn btn--yellow" href="<?php echo esc_url( $ciplev_link( 'le-ciplev' ) ); ?>"><?php esc_html_e( 'Découvrir le CIPLEV', 'ciplev' ); ?> <?php echo ciplev_icon( 'arrow' ); // phpcs:ignore ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( $ciplev_link( 'contact' ) ); ?>"><?php esc_html_e( 'Nous contacter', 'ciplev' ); ?></a>
			</div>
		</div>
		<div class="hero__visual">
			<div class="hero__logo-wrap">
				<img src="<?php echo esc_url( ciplev_logo_url() ); ?>" alt="<?php esc_attr_e( 'Logo officiel du CIPLEV', 'ciplev' ); ?>" width="319" height="506">
			</div>
		</div>
	</div>
	<span class="flag-bar" aria-hidden="true"></span>
</section>

<section class="facts" aria-label="<?php esc_attr_e( 'Le CIPLEV en chiffres', 'ciplev' ); ?>">
	<div class="container facts__grid">
		<div class="fact"><span class="fact__num">2019</span><span class="fact__label"><?php esc_html_e( 'Création par décret présidentiel n°2019-076/PR', 'ciplev' ); ?></span></div>
		<div class="fact"><span class="fact__num">3</span><span class="fact__label"><?php esc_html_e( 'Niveaux de responsabilité reliés par un Secrétariat permanent', 'ciplev' ); ?></span></div>
		<div class="fact"><span class="fact__num">3</span><span class="fact__label"><?php esc_html_e( 'Régions dotées de comités locaux : Savanes, Kara, Centrale', 'ciplev' ); ?></span></div>
		<div class="fact"><span class="fact__num">15</span><span class="fact__label"><?php esc_html_e( 'Membres au sein du Comité interministériel', 'ciplev' ); ?></span></div>
	</div>
</section>

<section class="section">
	<div class="container split">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Qui sommes-nous', 'ciplev' ); ?></p>
			<h2 class="section__title"><?php esc_html_e( 'Un mécanisme de proximité entre l’État et les populations', 'ciplev' ); ?></h2>
			<p class="lead"><?php esc_html_e( 'Le CIPLEV renforce la collaboration avec la population et marque la présence de l’État dans les zones affectées ou à risque.', 'ciplev' ); ?></p>
			<p><?php esc_html_e( 'Convaincu qu’une réponse purement militaire ne suffit pas face au terrorisme, le Gouvernement a fait le choix d’une approche préventive, globale et holistique : agir sur les causes profondes qui peuvent conduire à la radicalisation, avec la participation de la société civile, des femmes et des jeunes aux côtés des acteurs étatiques.', 'ciplev' ); ?></p>
			<a class="link-arrow" href="<?php echo esc_url( $ciplev_link( 'le-ciplev/historique' ) ); ?>"><?php esc_html_e( 'Notre histoire', 'ciplev' ); ?> <?php echo ciplev_icon( 'arrow' ); // phpcs:ignore ?></a>
		</div>
		<div class="missions">
			<article class="mission-card">
				<span class="mission-card__num">01</span>
				<h3><?php esc_html_e( 'Éradiquer ou réduire l’extrémisme violent', 'ciplev' ); ?></h3>
				<p><?php esc_html_e( 'Sur l’ensemble du territoire national, et particulièrement dans les zones affectées ou à risque, en donnant aux communautés de base les outils et le soutien dont elles ont besoin pour résister à ce fléau.', 'ciplev' ); ?></p>
			</article>
			<article class="mission-card mission-card--green">
				<span class="mission-card__num">02</span>
				<h3><?php esc_html_e( 'Renforcer la coopération', 'ciplev' ); ?></h3>
				<p><?php esc_html_e( 'Entre l’administration, les forces de défense et de sécurité et la société civile dans la prévention et la lutte contre l’extrémisme violent.', 'ciplev' ); ?></p>
			</article>
		</div>
	</div>
</section>

<section class="section section--soft">
	<div class="container">
		<div class="section__head">
			<p class="eyebrow"><?php esc_html_e( 'Nos actions', 'ciplev' ); ?></p>
			<h2 class="section__title"><?php esc_html_e( 'Une approche de prévention aux côtés des forces de défense et de sécurité', 'ciplev' ); ?></h2>
		</div>
		<?php
		$ciplev_actions = array(
			array( 'search', __( 'Recueillir et analyser', 'ciplev' ), __( 'les informations et les données sur les zones à risque.', 'ciplev' ) ),
			array( 'radar', __( 'Évaluer la menace', 'ciplev' ), __( 'et identifier les zones affectées ou à risque.', 'ciplev' ) ),
			array( 'list', __( 'Identifier les besoins', 'ciplev' ), __( 'prioritaires des populations des zones affectées ou à risque.', 'ciplev' ) ),
			array( 'megaphone', __( 'Sensibiliser', 'ciplev' ), __( 'la population en général et celle des zones à risque en particulier.', 'ciplev' ) ),
			array( 'chat', __( 'Promouvoir le dialogue', 'ciplev' ), __( 'l’écoute et la confiance entre pouvoirs publics et populations, par des échanges et visites de terrain.', 'ciplev' ) ),
			array( 'build', __( 'Faciliter des projets', 'ciplev' ), __( 'favorisant l’adhésion et la collaboration de la population dans les zones à risque.', 'ciplev' ) ),
			array( 'target', __( 'Éradiquer les sources de conflit', 'ciplev' ), __( 'et de méfiance entre les FDS et la population à la base.', 'ciplev' ) ),
			array( 'handshake', __( 'Bâtir la confiance', 'ciplev' ), __( 'et un climat de collaboration durable entre les FDS et la population.', 'ciplev' ) ),
		);
		?>
		<div class="actions-grid">
			<?php foreach ( $ciplev_actions as $ciplev_a ) : ?>
				<article class="action">
					<span class="action__icon"><?php echo ciplev_icon( $ciplev_a[0] ); // phpcs:ignore ?></span>
					<h3 class="action__title"><?php echo esc_html( $ciplev_a[1] ); ?></h3>
					<p><?php echo esc_html( $ciplev_a[2] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="section__more"><a class="btn btn--blue" href="<?php echo esc_url( $ciplev_link( 'missions-et-actions' ) ); ?>"><?php esc_html_e( 'Missions et actions', 'ciplev' ); ?> <?php echo ciplev_icon( 'arrow' ); // phpcs:ignore ?></a></p>
	</div>
</section>

<section class="section values">
	<div class="container">
		<div class="section__head section__head--light">
			<p class="eyebrow eyebrow--light"><?php esc_html_e( 'Nos valeurs', 'ciplev' ); ?></p>
			<h2 class="section__title"><?php esc_html_e( 'Proximité, disponibilité, équité', 'ciplev' ); ?></h2>
		</div>
		<div class="values__grid">
			<article class="value">
				<span class="value__icon"><?php echo ciplev_icon( 'users' ); // phpcs:ignore ?></span>
				<h3><?php esc_html_e( 'Proximité', 'ciplev' ); ?></h3>
				<p><?php esc_html_e( 'Une présence sur le terrain, des échanges réguliers avec les acteurs locaux et une écoute active pour comprendre les préoccupations des communautés et agir efficacement.', 'ciplev' ); ?></p>
			</article>
			<article class="value">
				<span class="value__icon"><?php echo ciplev_icon( 'bolt' ); // phpcs:ignore ?></span>
				<h3><?php esc_html_e( 'Disponibilité', 'ciplev' ); ?></h3>
				<p><?php esc_html_e( 'Une présence régulière et visible, et une réactivité face aux urgences.', 'ciplev' ); ?></p>
			</article>
			<article class="value">
				<span class="value__icon"><?php echo ciplev_icon( 'scale' ); // phpcs:ignore ?></span>
				<h3><?php esc_html_e( 'Équité', 'ciplev' ); ?></h3>
				<p><?php esc_html_e( 'Une approche non discriminatoire et une répartition juste des ressources et des opportunités, au service de la cohésion sociale.', 'ciplev' ); ?></p>
			</article>
		</div>
		<ul class="chips">
			<?php
			foreach ( array( 'Paix', 'Cohésion sociale', 'Coproduction de la sécurité', 'Vivre-ensemble', 'Écoute active', 'Gestion pacifique des conflits', 'Harmonie', 'Stabilité' ) as $ciplev_v ) {
				echo '<li>' . esc_html( $ciplev_v ) . '</li>';
			}
			?>
		</ul>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section__head">
			<p class="eyebrow"><?php esc_html_e( 'Organisation', 'ciplev' ); ?></p>
			<h2 class="section__title"><?php esc_html_e( 'Trois niveaux de responsabilité, un organe de liaison', 'ciplev' ); ?></h2>
		</div>
		<div class="org">
			<div class="org__levels">
				<article class="org__level">
					<span class="org__tag"><?php esc_html_e( 'Niveau ministériel', 'ciplev' ); ?></span>
					<h3><?php esc_html_e( 'Comité de suivi', 'ciplev' ); ?></h3>
					<p><?php esc_html_e( 'Présidé par le ministre de la Sécurité, il veille à la mise en œuvre des orientations du Gouvernement.', 'ciplev' ); ?></p>
				</article>
				<article class="org__level">
					<span class="org__tag"><?php esc_html_e( 'Niveau technique', 'ciplev' ); ?></span>
					<h3><?php esc_html_e( 'Comité interministériel', 'ciplev' ); ?></h3>
					<p><?php esc_html_e( '15 membres issus des ministères, de l’état-major et des confessions religieuses.', 'ciplev' ); ?></p>
				</article>
				<article class="org__level">
					<span class="org__tag"><?php esc_html_e( 'Niveau local', 'ciplev' ); ?></span>
					<h3><?php esc_html_e( 'Comités locaux', 'ciplev' ); ?></h3>
					<p><?php esc_html_e( 'Comités préfectoraux et cantonaux, appuyés par les agents d’appui sur le terrain.', 'ciplev' ); ?></p>
				</article>
			</div>
			<div class="org__link">
				<span class="org__link-icon"><?php echo ciplev_icon( 'landmark' ); // phpcs:ignore ?></span>
				<div>
					<h3><?php esc_html_e( 'Secrétariat permanent', 'ciplev' ); ?></h3>
					<p><?php esc_html_e( 'Organe de liaison entre les trois niveaux, il abrite le siège de l’institution.', 'ciplev' ); ?></p>
				</div>
				<a class="link-arrow" href="<?php echo esc_url( $ciplev_link( 'organisation' ) ); ?>"><?php esc_html_e( 'Voir l’organisation', 'ciplev' ); ?> <?php echo ciplev_icon( 'arrow' ); // phpcs:ignore ?></a>
			</div>
		</div>
	</div>
</section>

<section class="section section--soft">
	<div class="container">
		<div class="section__head">
			<p class="eyebrow"><?php esc_html_e( 'Repères', 'ciplev' ); ?></p>
			<h2 class="section__title"><?php esc_html_e( 'Les grandes étapes', 'ciplev' ); ?></h2>
		</div>
		<ol class="timeline">
			<li><span class="timeline__date"><?php esc_html_e( '15 mai 2019', 'ciplev' ); ?></span><p><?php esc_html_e( 'Création du CIPLEV par décret du Président de la République.', 'ciplev' ); ?></p></li>
			<li><span class="timeline__date"><?php esc_html_e( '30 décembre 2019', 'ciplev' ); ?></span><p><?php esc_html_e( 'Recrutement du personnel d’appui.', 'ciplev' ); ?></p></li>
			<li><span class="timeline__date"><?php esc_html_e( 'Région des Savanes', 'ciplev' ); ?></span><p><?php esc_html_e( 'Installation des premiers comités locaux de prévention.', 'ciplev' ); ?></p></li>
			<li><span class="timeline__date"><?php esc_html_e( 'Février 2021', 'ciplev' ); ?></span><p><?php esc_html_e( 'Installation des comités locaux de la région de la Kara.', 'ciplev' ); ?></p></li>
			<li><span class="timeline__date"><?php esc_html_e( 'Juillet 2021', 'ciplev' ); ?></span><p><?php esc_html_e( 'Installation des comités locaux de la région Centrale.', 'ciplev' ); ?></p></li>
			<li><span class="timeline__date"><?php esc_html_e( 'Novembre 2022', 'ciplev' ); ?></span><p><?php esc_html_e( 'Projet de renforcement des capacités opérationnelles : formations et campagne de communication.', 'ciplev' ); ?></p></li>
		</ol>
	</div>
</section>

<?php
$ciplev_news = new WP_Query(
	array(
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
if ( $ciplev_news->have_posts() ) :
	?>
	<section class="section">
		<div class="container">
			<div class="section__head section__head--row">
				<div>
					<p class="eyebrow"><?php esc_html_e( 'Actualités', 'ciplev' ); ?></p>
					<h2 class="section__title"><?php esc_html_e( 'Sur le terrain', 'ciplev' ); ?></h2>
				</div>
				<?php $ciplev_blog = (int) get_option( 'page_for_posts' ); ?>
				<?php if ( $ciplev_blog ) : ?>
					<a class="link-arrow" href="<?php echo esc_url( get_permalink( $ciplev_blog ) ); ?>"><?php esc_html_e( 'Toutes les actualités', 'ciplev' ); ?> <?php echo ciplev_icon( 'arrow' ); // phpcs:ignore ?></a>
				<?php endif; ?>
			</div>
			<div class="cards">
				<?php
				while ( $ciplev_news->have_posts() ) :
					$ciplev_news->the_post();
					get_template_part( 'template-parts/card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<section class="cta">
	<div class="container cta__inner">
		<div>
			<h2><?php esc_html_e( 'Une préoccupation dans votre localité ?', 'ciplev' ); ?></h2>
			<p><?php esc_html_e( 'Rapprochez-vous du comité local de prévention de votre préfecture ou de votre canton, ou contactez directement le Secrétariat permanent.', 'ciplev' ); ?></p>
		</div>
		<div class="cta__actions">
			<?php if ( ciplev_opt( 'ciplev_phone_1' ) ) : ?>
				<a class="btn btn--yellow" href="<?php echo esc_attr( ciplev_tel_href( ciplev_opt( 'ciplev_phone_1' ) ) ); ?>"><?php echo ciplev_icon( 'phone' ); // phpcs:ignore ?> <?php echo esc_html( ciplev_opt( 'ciplev_phone_1' ) ); ?></a>
			<?php endif; ?>
			<a class="btn btn--ghost" href="<?php echo esc_url( $ciplev_link( 'contact' ) ); ?>"><?php esc_html_e( 'Toutes nos coordonnées', 'ciplev' ); ?></a>
		</div>
	</div>
</section>

<?php
get_footer();
