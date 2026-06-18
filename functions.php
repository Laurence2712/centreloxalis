<?php
/**
 * Fonctions du thème Centre l'Oxalis
 *
 * @package oxalis
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ── Theme setup ───────────────────────────────────────────────────────────────
add_action( 'after_setup_theme', 'oxalis_setup' );
function oxalis_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    load_theme_textdomain( 'oxalis', get_stylesheet_directory() . '/languages' );
}

// ── Scripts & Styles ──────────────────────────────────────────────────────────
add_action( 'wp_enqueue_scripts', 'oxalis_enqueue' );
function oxalis_enqueue() {
    // Dequeue parent theme styles so they don't conflict
    wp_dequeue_style( 'twenty-twenty-four-style' );
    wp_dequeue_style( 'twenty-twenty-four-print-style' );

    // Google Fonts preconnect + stylesheet
    wp_enqueue_style( 'oxalis-fonts',
        'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&family=Mulish:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Newsreader:ital,opsz,wght@1,18..72,400;1,18..72,500&display=swap',
        [], null
    );

    wp_enqueue_style( 'oxalis-style',
        get_stylesheet_directory_uri() . '/assets/css/oxalis.css',
        [ 'oxalis-fonts' ], '1.0.0'
    );

    wp_enqueue_script( 'oxalis-js',
        get_stylesheet_directory_uri() . '/assets/js/oxalis.js',
        [], '1.0.0', true
    );
}

// Add preconnect for Google Fonts
add_action( 'wp_head', 'oxalis_preconnect', 1 );
function oxalis_preconnect() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}

// ── Custom Post Type: Membres de l'équipe ────────────────────────────────────
add_action( 'init', 'oxalis_register_team_cpt' );
function oxalis_register_team_cpt() {
    register_post_type( 'oxalis_team', [
        'labels' => [
            'name'               => __( "Membres de l'équipe", 'oxalis' ),
            'singular_name'      => __( "Membre de l'équipe", 'oxalis' ),
            'add_new_item'       => __( 'Ajouter un membre', 'oxalis' ),
            'edit_item'          => __( 'Modifier le membre', 'oxalis' ),
            'new_item'           => __( 'Nouveau membre', 'oxalis' ),
            'not_found'          => __( 'Aucun membre trouvé', 'oxalis' ),
            'not_found_in_trash' => __( 'Aucun membre dans la corbeille', 'oxalis' ),
        ],
        'public'           => false,
        'show_ui'          => true,
        'show_in_menu'     => true,
        'menu_icon'        => 'dashicons-groups',
        'menu_position'    => 20,
        'supports'         => [ 'title', 'editor', 'thumbnail', 'page-attributes' ],
        'has_archive'      => false,
        'rewrite'          => false,
        'show_in_rest'     => true,
        'capability_type'  => 'post',
    ] );
}

// ── Taxonomy: Spécialités ────────────────────────────────────────────────────
add_action( 'init', 'oxalis_register_specialty_tax' );
function oxalis_register_specialty_tax() {
    register_taxonomy( 'oxalis_specialty', 'oxalis_team', [
        'labels' => [
            'name'          => __( 'Spécialités', 'oxalis' ),
            'singular_name' => __( 'Spécialité', 'oxalis' ),
            'all_items'     => __( 'Toutes les spécialités', 'oxalis' ),
            'edit_item'     => __( 'Modifier la spécialité', 'oxalis' ),
            'add_new_item'  => __( 'Ajouter une spécialité', 'oxalis' ),
            'menu_name'     => __( 'Spécialités', 'oxalis' ),
        ],
        'hierarchical'      => false,
        'public'            => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => false,
    ] );
}

// Insert the four default specialty terms once
add_action( 'init', 'oxalis_seed_specialty_terms', 20 );
function oxalis_seed_specialty_terms() {
    if ( ! taxonomy_exists( 'oxalis_specialty' ) ) {
        return;
    }
    $terms = [
        'medecine'    => 'Médecine',
        'paramedical' => 'Paramédical',
        'mentale'     => 'Santé mentale',
        'nutrition'   => 'Nutrition & bien-être',
    ];
    foreach ( $terms as $slug => $name ) {
        if ( ! term_exists( $slug, 'oxalis_specialty' ) ) {
            wp_insert_term( $name, 'oxalis_specialty', [ 'slug' => $slug ] );
        }
    }
}

// ── Meta Box: Rôle & Téléphone ────────────────────────────────────────────────
add_action( 'add_meta_boxes', 'oxalis_add_team_metabox' );
function oxalis_add_team_metabox() {
    add_meta_box(
        'oxalis_team_details',
        __( 'Informations du praticien', 'oxalis' ),
        'oxalis_team_metabox_html',
        'oxalis_team',
        'normal',
        'high'
    );
}

function oxalis_team_metabox_html( $post ) {
    wp_nonce_field( 'oxalis_team_save', 'oxalis_team_nonce' );
    $role  = get_post_meta( $post->ID, '_oxalis_role', true );
    $phone = get_post_meta( $post->ID, '_oxalis_phone', true );
    ?>
    <p>
        <label for="oxalis_role"><strong><?php esc_html_e( 'Rôle / Spécialité', 'oxalis' ); ?></strong></label><br>
        <input type="text" id="oxalis_role" name="oxalis_role"
               value="<?php echo esc_attr( $role ); ?>"
               class="widefat" placeholder="ex : Kinésithérapeute · Ostéopathe">
        <span class="description"><?php esc_html_e( 'Affiché sous le nom sur la carte (ex : Kinésithérapeute · Ostéopathe).', 'oxalis' ); ?></span>
    </p>
    <p>
        <label for="oxalis_phone"><strong><?php esc_html_e( 'Téléphone', 'oxalis' ); ?></strong></label><br>
        <input type="text" id="oxalis_phone" name="oxalis_phone"
               value="<?php echo esc_attr( $phone ); ?>"
               class="regular-text" placeholder="ex : 0484 76 56 29">
        <span class="description"><?php esc_html_e( 'Lien de prise de rendez-vous dans la fiche profil.', 'oxalis' ); ?></span>
    </p>
    <p class="description" style="margin-top:12px;padding:10px;background:#f9f3fd;border-left:3px solid #8f54a4;border-radius:4px">
        <?php esc_html_e( "💡 Photo : utilisez l'Image à la une (à droite) pour définir la photo du praticien. Biographie : saisissez-la dans le corps du texte ci-dessus.", 'oxalis' ); ?>
    </p>
    <?php
}

add_action( 'save_post_oxalis_team', 'oxalis_save_team_meta' );
function oxalis_save_team_meta( $post_id ) {
    if ( ! isset( $_POST['oxalis_team_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['oxalis_team_nonce'], 'oxalis_team_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['oxalis_role'] ) ) {
        update_post_meta( $post_id, '_oxalis_role', sanitize_text_field( $_POST['oxalis_role'] ) );
    }
    if ( isset( $_POST['oxalis_phone'] ) ) {
        update_post_meta( $post_id, '_oxalis_phone', sanitize_text_field( $_POST['oxalis_phone'] ) );
    }
}

// ── Customizer ────────────────────────────────────────────────────────────────
add_action( 'customize_register', 'oxalis_customizer' );
function oxalis_customizer( $wp_customize ) {

    $wp_customize->add_panel( 'oxalis_panel', [
        'title'    => "Centre l'Oxalis",
        'priority' => 25,
    ] );

    // ── Hero ──
    $wp_customize->add_section( 'oxalis_hero', [
        'title' => __( 'Section Héro', 'oxalis' ),
        'panel' => 'oxalis_panel',
    ] );

    oxalis_setting( $wp_customize, 'oxalis_hero_badge',
        __( 'Texte du badge', 'oxalis' ), 'oxalis_hero',
        'Centre pluridisciplinaire · Koningslo'
    );
    oxalis_setting( $wp_customize, 'oxalis_hero_description',
        __( 'Description', 'oxalis' ), 'oxalis_hero',
        "Au Centre l'Oxalis, nous réunissons médecins, kinésithérapeutes, ostéopathes, psychologues, diététicienne, sage-femme, coach de vie et autres spécialistes pour un suivi personnalisé et pluridisciplinaire.",
        'textarea'
    );
    oxalis_image_setting( $wp_customize, 'oxalis_hero_image',
        __( 'Image principale', 'oxalis' ), 'oxalis_hero'
    );

    // ── Le centre ──
    $wp_customize->add_section( 'oxalis_centre', [
        'title' => __( 'Section Le Centre', 'oxalis' ),
        'panel' => 'oxalis_panel',
    ] );
    oxalis_setting( $wp_customize, 'oxalis_centre_p1',
        __( 'Paragraphe 1', 'oxalis' ), 'oxalis_centre',
        'Profitez de nos cours collectifs et individuels, conçus pour améliorer votre santé, votre mobilité et votre bien-être.',
        'textarea'
    );
    oxalis_setting( $wp_customize, 'oxalis_centre_p2',
        __( 'Paragraphe 2', 'oxalis' ), 'oxalis_centre',
        'Chaque séance est adaptée à vos besoins, que ce soit pour la rééducation, la relaxation ou le développement personnel.',
        'textarea'
    );
    oxalis_setting( $wp_customize, 'oxalis_centre_quote',
        __( 'Citation mise en avant', 'oxalis' ), 'oxalis_centre',
        "Un centre unique à Koningslo, pour tous vos soins et votre bien-être."
    );

    // ── Spécialités (images de fond) ──
    $wp_customize->add_section( 'oxalis_specialties', [
        'title' => __( 'Section Spécialités – Images', 'oxalis' ),
        'panel' => 'oxalis_panel',
    ] );
    $spec_imgs = [
        'oxalis_spec_medecine_img'    => __( 'Médecine – Image de fond', 'oxalis' ),
        'oxalis_spec_paramedical_img' => __( 'Paramédical – Image de fond', 'oxalis' ),
        'oxalis_spec_mentale_img'     => __( 'Santé mentale – Image de fond', 'oxalis' ),
        'oxalis_spec_nutrition_img'   => __( 'Nutrition – Image de fond', 'oxalis' ),
    ];
    foreach ( $spec_imgs as $id => $label ) {
        oxalis_image_setting( $wp_customize, $id, $label, 'oxalis_specialties' );
    }

    // ── Horaires ──
    $wp_customize->add_section( 'oxalis_hours', [
        'title' => __( 'Horaires', 'oxalis' ),
        'panel' => 'oxalis_panel',
    ] );
    oxalis_setting( $wp_customize, 'oxalis_hours_intro',
        __( 'Texte introductif', 'oxalis' ), 'oxalis_hours',
        "Le centre est ouvert toute la semaine. Les consultations se font sur rendez-vous — contactez directement le praticien de votre choix.",
        'textarea'
    );
    $hour_fields = [
        [ 'oxalis_h1_day',  'Ligne 1 – Jour(s)',   'Lundi – Vendredi' ],
        [ 'oxalis_h1_time', 'Ligne 1 – Horaire',   '8h00 – 19h00' ],
        [ 'oxalis_h2_day',  'Ligne 2 – Jour(s)',   'Samedi' ],
        [ 'oxalis_h2_time', 'Ligne 2 – Horaire',   '9h00 – 13h00' ],
        [ 'oxalis_h3_day',  'Ligne 3 – Jour(s)',   'Dimanche' ],
        [ 'oxalis_h3_time', 'Ligne 3 – Horaire',   'Fermé' ],
        [ 'oxalis_h4_day',  'Ligne 4 – Jour(s)',   'Consultations' ],
        [ 'oxalis_h4_time', 'Ligne 4 – Horaire',   'Sur rendez-vous' ],
    ];
    foreach ( $hour_fields as [ $id, $label, $default ] ) {
        oxalis_setting( $wp_customize, $id, __( $label, 'oxalis' ), 'oxalis_hours', $default );
    }

    // ── Contact ──
    $wp_customize->add_section( 'oxalis_contact', [
        'title' => __( 'Contact & Adresse', 'oxalis' ),
        'panel' => 'oxalis_panel',
    ] );
    oxalis_setting( $wp_customize, 'oxalis_address_1',
        __( 'Adresse ligne 1', 'oxalis' ), 'oxalis_contact', 'Koningslo, 1800 Vilvoorde'
    );
    oxalis_setting( $wp_customize, 'oxalis_address_2',
        __( 'Adresse ligne 2', 'oxalis' ), 'oxalis_contact', 'Belgique'
    );
    oxalis_setting( $wp_customize, 'oxalis_appt_text',
        __( 'Texte prise de RDV', 'oxalis' ), 'oxalis_contact',
        "Chaque praticien gère ses propres réservations. Retrouvez les coordonnées dans la section L'équipe.",
        'textarea'
    );
}

// Helper: simple text/textarea customizer setting
function oxalis_setting( $wpc, $id, $label, $section, $default = '', $type = 'text' ) {
    $wpc->add_setting( $id, [
        'default'           => $default,
        'sanitize_callback' => $type === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field',
        'transport'         => 'refresh',
    ] );
    $wpc->add_control( $id, [
        'label'   => $label,
        'section' => $section,
        'type'    => $type,
    ] );
}

// Helper: image customizer setting
function oxalis_image_setting( $wpc, $id, $label, $section ) {
    $wpc->add_setting( $id, [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ] );
    $wpc->add_control( new WP_Customize_Image_Control( $wpc, $id, [
        'label'   => $label,
        'section' => $section,
    ] ) );
}

// ── Team data helper ──────────────────────────────────────────────────────────
function oxalis_get_team_members() {
    $query = new WP_Query( [
        'post_type'      => 'oxalis_team',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ] );

    if ( $query->have_posts() ) {
        $members = [];
        foreach ( $query->posts as $post ) {
            $terms = wp_get_post_terms( $post->ID, 'oxalis_specialty', [ 'fields' => 'slugs' ] );
            $members[] = [
                'name'       => get_the_title( $post ),
                'role'       => get_post_meta( $post->ID, '_oxalis_role', true ),
                'phone'      => get_post_meta( $post->ID, '_oxalis_phone', true ),
                'bio'        => wp_strip_all_tags( $post->post_content ),
                'photo'      => (string) get_the_post_thumbnail_url( $post->ID, 'medium' ),
                'categories' => is_array( $terms ) ? $terms : [],
            ];
        }
        wp_reset_postdata();
        return $members;
    }

    // Default members (used until CPT posts are created)
    return oxalis_default_members();
}

function oxalis_default_members() {
    $img = get_stylesheet_directory_uri() . '/assets/images/';
    return [
        [ 'name' => 'Sandy Hereng',      'role' => 'Ostéopathe · Kinésithérapeute',          'phone' => '0484 76 56 29',   'bio' => "Kinésithérapeute depuis 2011 et diplômée en ostéopathie en 2017, je reçois chaque jour mes patients au Centre l'Oxalis en mettant à profit ces deux approches complémentaires.", 'photo' => $img . 'p-sandy.png',      'categories' => [ 'paramedical' ] ],
        [ 'name' => 'Lola Deneux',       'role' => 'Kinésithérapeute',                        'phone' => '+33 6 20 02 16 40', 'bio' => "Récemment diplômée d'un master en kinésithérapie à la Haute École Léonard de Vinci. Profondément altruiste, je suis passionnée par le fonctionnement humain.", 'photo' => $img . 'p-lola.png',       'categories' => [ 'paramedical' ] ],
        [ 'name' => 'Manon Nuyts',       'role' => 'Kinésithérapeute du sport',               'phone' => '0479 68 18 75',   'bio' => "Diplômée d'un master en kinésithérapie et récemment spécialisée en kinésithérapie du sport grâce à un certificat interuniversitaire obtenu à l'UCL.", 'photo' => $img . 'p-manon.png',      'categories' => [ 'paramedical' ] ],
        [ 'name' => 'Christelle Mertens','role' => 'Infirmière',                              'phone' => '0470 95 07 17',   'bio' => "Infirmière confirmée avec plus de 10 ans d'expérience dans le domaine paramédical et médical, en milieu hospitalier et extra-hospitalier.", 'photo' => $img . 'p-christelle.png', 'categories' => [ 'paramedical' ] ],
        [ 'name' => 'Colette Maezelle',  'role' => 'Massothérapie · Gym douce & relaxation', 'phone' => '0496 94 51 19',   'bio' => "Études de secrétaire médico-sociale avec assistance au médecin. Formée en aromathérapie et en bien-être. J'anime des cours de gym douce AFES — abdos, fessiers, équilibre, souplesse — ainsi que de la relaxation, de la respiration et de la danse libre.", 'photo' => $img . 'p-colette.png',    'categories' => [ 'paramedical', 'nutrition' ] ],
        [ 'name' => 'Karima Manouach',   'role' => 'Sage-femme',                             'phone' => '0486 55 21 74',   'bio' => "Sage-femme diplômée depuis 2014 de la Haute École Libre de Bruxelles, je propose des consultations prénatales et postnatales ainsi que des séances de préparation à l'accouchement.", 'photo' => $img . 'p-karima.png',    'categories' => [ 'medecine' ] ],
        [ 'name' => 'Julia',             'role' => 'Psychologue',                             'phone' => '',               'bio' => '', 'photo' => $img . 'p-julia.png',    'categories' => [ 'mentale' ] ],
        [ 'name' => 'Camille Delens',    'role' => 'Coach de vie',                            'phone' => '0470 20 96 96',   'bio' => "Formée à la Leading & Coaching Academy, école certifiée ICF (International Coaching Federation), je propose un accompagnement en coaching de vie et de développement personnel.", 'photo' => $img . 'p-camille.png',   'categories' => [ 'mentale' ] ],
        [ 'name' => 'Barbara Radomme',   'role' => 'Yoga · Pilates · Méditation (DunkY)',    'phone' => '0499 43 37 18',   'bio' => "DunkY, c'est quoi ? Des séances en français, in English, in het Nederlands ! Des cours de Yoga et de Pilates, de Méditation et de Relaxation.", 'photo' => $img . 'p-barbara.png',   'categories' => [ 'nutrition', 'mentale' ] ],
        [ 'name' => 'Claire Deprez',     'role' => 'Diététicienne',                           'phone' => '0472 36 75 34',   'bio' => '', 'photo' => $img . 'p-claire.png',   'categories' => [ 'nutrition' ] ],
    ];
}

// ── Specialty image helper ────────────────────────────────────────────────────
function oxalis_spec_image( $key ) {
    $defaults = [
        'medecine'    => 'room-medecine.png',
        'paramedical' => 'room-soins.png',
        'mentale'     => 'room-mentale.png',
        'nutrition'   => 'room-nutrition.png',
    ];
    $mod_key = 'oxalis_spec_' . $key . '_img';
    $custom  = get_theme_mod( $mod_key, '' );
    if ( $custom ) {
        return esc_url( $custom );
    }
    return esc_url( get_stylesheet_directory_uri() . '/assets/images/' . ( $defaults[ $key ] ?? '' ) );
}

// ── Hero image helper ─────────────────────────────────────────────────────────
function oxalis_hero_image() {
    $custom = get_theme_mod( 'oxalis_hero_image', '' );
    if ( $custom ) {
        return esc_url( $custom );
    }
    return esc_url( get_stylesheet_directory_uri() . '/assets/images/room-nutrition.png' );
}
