<?php
/**
 * Fichier index de secours – ne devrait jamais être affiché
 * car front-page.php prend la main sur la page d'accueil.
 *
 * @package oxalis
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>
<main id="primary">
    <div style="max-width:640px;margin:120px auto;text-align:center;font-family:'Bricolage Grotesque',sans-serif;padding:20px">
        <h1 style="font-size:2rem;color:#3a2742">Centre l'Oxalis</h1>
        <p style="margin-top:16px;color:#5b5363">Aucun contenu trouvé.</p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-block;margin-top:24px;padding:12px 28px;background:#8f54a4;color:#fff;border-radius:999px;text-decoration:none;font-weight:700">
            Retour à l'accueil
        </a>
    </div>
</main>
<?php
get_footer();
