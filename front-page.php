<?php
/**
 * Template de la page d'accueil – Centre l'Oxalis
 *
 * @package oxalis
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$team_members = oxalis_get_team_members();

$specialties = [
    [ 'key' => 'medecine',    'title' => 'Médecine générale & spécialités', 'icon' => '🩺' ],
    [ 'key' => 'paramedical', 'title' => 'Soins paramédicaux',              'icon' => '💆' ],
    [ 'key' => 'mentale',     'title' => 'Santé mentale & accompagnement',  'icon' => '🧠' ],
    [ 'key' => 'nutrition',   'title' => 'Nutrition & bien-être',           'icon' => '🌿' ],
];

$filter_defs = [
    [ 'key' => 'tous',        'label' => 'Tous' ],
    [ 'key' => 'medecine',    'label' => 'Médecine' ],
    [ 'key' => 'paramedical', 'label' => 'Paramédical' ],
    [ 'key' => 'mentale',     'label' => 'Santé mentale' ],
    [ 'key' => 'nutrition',   'label' => 'Nutrition & bien-être' ],
];
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'oxalis-site' ); ?>>
<?php wp_body_open(); ?>

<div class="site-wrap">

  <!-- ══ NAV ══════════════════════════════════════════════════════════════ -->
  <nav class="site-nav" data-nav aria-label="Navigation principale">
    <a href="#accueil" class="nav-logo">
      <span class="nav-logo__icon" aria-hidden="true">O</span>
      <span class="nav-logo__name">Centre l'Oxalis</span>
    </a>

    <div class="nav-links" data-desktop-nav>
      <a href="#centre"      class="nav-link">Le centre</a>
      <a href="#specialites" class="nav-link">Spécialités</a>
      <a href="#equipe"      class="nav-link">L'équipe</a>
      <a href="#horaires"    class="nav-link">Horaires</a>
      <a href="#contact"     class="nav-cta">Prendre rendez-vous</a>
    </div>

    <button class="nav-burger" data-mobile-btn aria-label="Ouvrir le menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </nav>

  <!-- ══ MENU MOBILE ═══════════════════════════════════════════════════════ -->
  <div class="mobile-overlay" data-mobile-overlay aria-hidden="true">
    <div class="mobile-drawer" data-mobile-drawer role="dialog" aria-label="Menu mobile">
      <button class="mobile-close" data-close-menu aria-label="Fermer le menu">✕</button>
      <a href="#centre"      class="mobile-link" data-menu-link>Le centre</a>
      <a href="#specialites" class="mobile-link" data-menu-link>Spécialités</a>
      <a href="#equipe"      class="mobile-link" data-menu-link>L'équipe</a>
      <a href="#horaires"    class="mobile-link" data-menu-link>Horaires</a>
      <a href="#contact"     class="mobile-cta"  data-menu-link>Prendre rendez-vous</a>
    </div>
  </div>

  <!-- ══ HÉRO ═══════════════════════════════════════════════════════════════ -->
  <header id="accueil" class="hero">
    <div class="hero__blob hero__blob--1" aria-hidden="true"></div>
    <div class="hero__blob hero__blob--2" aria-hidden="true"></div>

    <div class="container">
      <div class="hero__grid">

        <div class="hero__content">
          <span class="reveal badge">
            <span class="badge__dot" aria-hidden="true"></span>
            <?php echo esc_html( get_theme_mod( 'oxalis_hero_badge', 'Centre pluridisciplinaire · Koningslo' ) ); ?>
          </span>

          <h1 class="reveal hero__title">
            Bienvenue au<br>Centre <span class="text-accent">l'Oxalis</span>
          </h1>

          <p class="reveal hero__subtitle">Votre espace pluridisciplinaire à Koningslo</p>

          <p class="reveal hero__desc">
            <?php echo esc_html( get_theme_mod( 'oxalis_hero_description',
              "Au Centre l'Oxalis, nous réunissons médecins, kinésithérapeutes, ostéopathes, psychologues, diététicienne, sage-femme, coach de vie et autres spécialistes pour un suivi personnalisé et pluridisciplinaire."
            ) ); ?>
          </p>

          <div class="reveal hero__ctas">
            <a href="#equipe"      class="btn btn--primary">Découvrir l'équipe</a>
            <a href="#specialites" class="btn btn--outline">Nos spécialités</a>
          </div>
        </div>

        <div class="reveal hero__visual">
          <div class="hero__img-wrap">
            <img src="<?php echo oxalis_hero_image(); ?>"
                 alt="Espace du Centre l'Oxalis"
                 class="hero__img"
                 loading="eager">
            <div class="hero__img-overlay" aria-hidden="true"></div>
          </div>
          <div class="hero__badge-card">
            <span class="hero__badge-icon" aria-hidden="true">🌿</span>
            <span class="hero__badge-text">Un suivi adapté à chacun de vos besoins</span>
          </div>
        </div>

      </div>
    </div>
  </header>

  <!-- ══ LE CENTRE ══════════════════════════════════════════════════════════ -->
  <section id="centre" class="section section--centre">
    <div class="container">
      <div class="two-col">
        <div class="reveal">
          <span class="section-label">Le centre</span>
          <h2 class="section-title">Tous vos soins réunis sous un même toit</h2>
        </div>
        <div class="reveal reveal--late">
          <p class="body-text"><?php echo esc_html( get_theme_mod( 'oxalis_centre_p1',
            "Profitez de nos cours collectifs et individuels, conçus pour améliorer votre santé, votre mobilité et votre bien-être."
          ) ); ?></p>
          <p class="body-text"><?php echo esc_html( get_theme_mod( 'oxalis_centre_p2',
            "Chaque séance est adaptée à vos besoins, que ce soit pour la rééducation, la relaxation ou le développement personnel."
          ) ); ?></p>
          <blockquote class="section-quote"><?php echo esc_html( get_theme_mod( 'oxalis_centre_quote',
            "Un centre unique à Koningslo, pour tous vos soins et votre bien-être."
          ) ); ?></blockquote>
        </div>
      </div>
    </div>
  </section>

  <!-- ══ SPÉCIALITÉS ════════════════════════════════════════════════════════ -->
  <section id="specialites" class="section section--specs">
    <div class="container">
      <div class="reveal section-head">
        <span class="section-label">Nos spécialités</span>
        <h2 class="section-title">Quatre pôles, une approche globale</h2>
      </div>

      <div class="spec-grid">
        <?php foreach ( $specialties as $sp ) :
          $img = oxalis_spec_image( $sp['key'] );
        ?>
        <button class="reveal spec-card"
                data-spec-card="<?php echo esc_attr( $sp['key'] ); ?>"
                type="button"
                aria-label="<?php echo esc_attr( $sp['title'] ); ?>">
          <span class="spec-card__img"
                style="background-image:url('<?php echo $img; ?>')"
                role="img"
                aria-label="<?php echo esc_attr( $sp['title'] ); ?>">
            <span class="spec-card__icon" aria-hidden="true"><?php echo $sp['icon']; ?></span>
          </span>
          <span class="spec-card__foot">
            <span class="spec-card__title"><?php echo esc_html( $sp['title'] ); ?></span>
            <span class="spec-card__arrow" aria-hidden="true">→</span>
          </span>
        </button>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══ ÉQUIPE ═════════════════════════════════════════════════════════════ -->
  <section id="equipe" class="section section--team">
    <div class="container">

      <div class="reveal section-head">
        <span class="section-label">L'équipe</span>
        <h2 class="section-title">Des spécialistes à votre écoute</h2>
        <p class="section-desc">Cliquez sur un profil pour découvrir le parcours de chaque praticien et ses coordonnées.</p>
      </div>

      <!-- Filtres -->
      <div class="reveal team-filters" role="group" aria-label="Filtrer par spécialité">
        <?php foreach ( $filter_defs as $f ) : ?>
        <button class="filter-chip<?php echo $f['key'] === 'tous' ? ' is-active' : ''; ?>"
                data-filter="<?php echo esc_attr( $f['key'] ); ?>"
                type="button">
          <?php echo esc_html( $f['label'] ); ?>
        </button>
        <?php endforeach; ?>
      </div>

      <!-- Grille membres -->
      <div class="team-grid" data-team-grid>
        <?php foreach ( $team_members as $m ) :
          $cats = implode( ',', $m['categories'] );
        ?>
        <button class="reveal team-card"
                data-member-card
                data-categories="<?php echo esc_attr( $cats ); ?>"
                data-name="<?php echo esc_attr( $m['name'] ); ?>"
                data-role="<?php echo esc_attr( $m['role'] ); ?>"
                data-phone="<?php echo esc_attr( $m['phone'] ); ?>"
                data-bio="<?php echo esc_attr( $m['bio'] ); ?>"
                data-photo="<?php echo esc_attr( $m['photo'] ); ?>"
                type="button"
                aria-label="<?php echo esc_attr( $m['name'] ) . ' — ' . esc_attr( $m['role'] ); ?>">
          <span class="team-card__avatar">
            <span class="team-card__photo"
                  role="img"
                  aria-label="<?php echo esc_attr( $m['name'] ); ?>"
                  <?php if ( $m['photo'] ) : ?>
                  style="background-image:url('<?php echo esc_url( $m['photo'] ); ?>')"
                  <?php endif; ?>></span>
          </span>
          <span class="team-card__name"><?php echo esc_html( $m['name'] ); ?></span>
          <span class="team-card__role"><?php echo esc_html( $m['role'] ); ?></span>
          <span class="team-card__cta" aria-hidden="true">Voir le profil <span>→</span></span>
        </button>
        <?php endforeach; ?>
      </div>

    </div>
  </section>

  <!-- ══ MODAL MEMBRE ═══════════════════════════════════════════════════════ -->
  <div class="modal-overlay" data-modal-overlay role="dialog" aria-modal="true" aria-hidden="true" aria-label="Profil du praticien">
    <div class="modal" data-modal-inner>
      <div class="modal__header">
        <button class="modal__close" data-close-modal type="button" aria-label="Fermer">✕</button>
      </div>
      <div class="modal__body">
        <div class="modal__photo-ring">
          <span class="modal__photo" data-modal-photo role="img" aria-label=""></span>
        </div>
        <div class="modal__info">
          <h3 class="modal__name" data-modal-name></h3>
          <span class="modal__role" data-modal-role></span>
        </div>
        <p class="modal__bio" data-modal-bio></p>
        <div class="modal__actions">
          <a class="btn btn--primary modal__phone" data-modal-phone href="#">📞 —</a>
        </div>
        <p class="modal__hint">Infos & réservations</p>
      </div>
    </div>
  </div>

  <!-- ══ HORAIRES ════════════════════════════════════════════════════════════ -->
  <section id="horaires" class="section section--hours">
    <div class="hours__blob" aria-hidden="true"></div>
    <div class="container">
      <div class="two-col">
        <div class="reveal">
          <span class="section-label section-label--light">Horaires</span>
          <h2 class="section-title section-title--light">Quand nous rendre visite</h2>
          <p class="hours__intro"><?php echo esc_html( get_theme_mod( 'oxalis_hours_intro',
            "Le centre est ouvert toute la semaine. Les consultations se font sur rendez-vous — contactez directement le praticien de votre choix."
          ) ); ?></p>
        </div>

        <div class="reveal reveal--late">
          <div class="hours__card">
            <?php
            $rows = [
              [ get_theme_mod( 'oxalis_h1_day', 'Lundi – Vendredi' ), get_theme_mod( 'oxalis_h1_time', '8h00 – 19h00' ) ],
              [ get_theme_mod( 'oxalis_h2_day', 'Samedi' ),           get_theme_mod( 'oxalis_h2_time', '9h00 – 13h00' ) ],
              [ get_theme_mod( 'oxalis_h3_day', 'Dimanche' ),         get_theme_mod( 'oxalis_h3_time', 'Fermé' ) ],
              [ get_theme_mod( 'oxalis_h4_day', 'Consultations' ),    get_theme_mod( 'oxalis_h4_time', 'Sur rendez-vous' ) ],
            ];
            foreach ( $rows as $row ) : ?>
            <div class="hours__row">
              <span class="hours__day"><?php echo esc_html( $row[0] ); ?></span>
              <span class="hours__time"><?php echo esc_html( $row[1] ); ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══ CONTACT ════════════════════════════════════════════════════════════ -->
  <section id="contact" class="section section--contact">
    <div class="container">
      <div class="reveal section-head">
        <span class="section-label">Contact</span>
        <h2 class="section-title">Venez nous rencontrer</h2>
      </div>

      <div class="two-col">
        <div class="reveal contact-col">
          <div class="contact-card">
            <span class="contact-card__icon" aria-hidden="true">📍</span>
            <div>
              <span class="contact-card__label">Adresse</span>
              <span class="contact-card__text">
                <?php echo esc_html( get_theme_mod( 'oxalis_address_1', 'Koningslo, 1800 Vilvoorde' ) ); ?><br>
                <?php echo esc_html( get_theme_mod( 'oxalis_address_2', 'Belgique' ) ); ?>
              </span>
            </div>
          </div>
          <div class="contact-card">
            <span class="contact-card__icon" aria-hidden="true">🗓️</span>
            <div>
              <span class="contact-card__label">Prendre rendez-vous</span>
              <span class="contact-card__text">
                Chaque praticien gère ses propres réservations. Retrouvez les coordonnées dans la section <a href="#equipe" style="color:var(--accent);font-weight:700">L'équipe</a>.
              </span>
            </div>
          </div>
        </div>

        <div class="reveal reveal--late contact-map">
          <div class="contact-map__grid" aria-hidden="true"></div>
          <div class="contact-map__content">
            <span class="contact-map__pin">📌</span>
            <span class="contact-map__name">Centre l'Oxalis</span>
            <span class="contact-map__city">Koningslo · Vilvoorde</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══ FOOTER ══════════════════════════════════════════════════════════════ -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer__row">
        <div class="footer__brand">
          <div class="footer__logo">
            <span class="nav-logo__icon" aria-hidden="true">O</span>
            <span class="footer__logo-name">Centre l'Oxalis</span>
          </div>
          <p class="footer__tagline">Votre espace pluridisciplinaire à Koningslo, pour tous vos soins et votre bien-être.</p>
        </div>
        <div class="footer__cols">
          <div class="footer__col">
            <span class="footer__col-title">Navigation</span>
            <a href="#centre"      class="footer__link">Le centre</a>
            <a href="#specialites" class="footer__link">Spécialités</a>
            <a href="#equipe"      class="footer__link">L'équipe</a>
            <a href="#horaires"    class="footer__link">Horaires</a>
          </div>
          <div class="footer__col">
            <span class="footer__col-title">Le centre</span>
            <span class="footer__link"><?php echo esc_html( get_theme_mod( 'oxalis_address_1', 'Koningslo, 1800 Vilvoorde' ) ); ?></span>
            <span class="footer__link"><?php echo esc_html( get_theme_mod( 'oxalis_address_2', 'Belgique' ) ); ?></span>
            <a href="#contact" class="footer__link footer__link--accent">Prendre rendez-vous →</a>
          </div>
        </div>
      </div>
      <div class="footer__bottom">
        <span>© <?php echo esc_html( date( 'Y' ) ); ?> Centre l'Oxalis · Koningslo</span>
      </div>
    </div>
  </footer>

</div><!-- .site-wrap -->

<?php wp_footer(); ?>
</body>
</html>
