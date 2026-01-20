<?php
/**
 * Frontend functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class Izbor_Dizni_Frontend {

    /**
     * Initialize
     */
    public static function init() {
        add_shortcode('izbor_dizne', array(__CLASS__, 'shortcode_izbor_dizne'));
        add_action('wp_ajax_izbor_dizni_get_dizna_data', array(__CLASS__, 'ajax_get_dizna_data'));
        add_action('wp_ajax_nopriv_izbor_dizni_get_dizna_data', array(__CLASS__, 'ajax_get_dizna_data'));
    }

    /**
     * Shortcode handler
     */
    public static function shortcode_izbor_dizne($atts) {
        $atts = shortcode_atts(array(
            'kultura' => '',
        ), $atts);

        ob_start();

        if (!empty($atts['kultura'])) {
            // Display specific kultura
            $term = get_term_by('slug', $atts['kultura'], 'kultura');
            if ($term) {
                self::render_kultura_view($term->term_id);
            } else {
                echo '<p>Kultura nije pronađena.</p>';
            }
        } else {
            // Check if kultura is selected via GET parameter
            if (isset($_GET['kultura_id'])) {
                $term_id = intval($_GET['kultura_id']);
                self::render_kultura_view($term_id);
            } else {
                // Display kultura selection
                self::render_kultura_selection();
            }
        }

        return ob_get_clean();
    }

    /**
     * Render kultura selection screen
     */
    private static function render_kultura_selection() {
        $kulture = get_terms(array(
            'taxonomy' => 'kultura',
            'hide_empty' => false,
        ));

        if (empty($kulture)) {
            echo '<p>Trenutno nema dostupnih kultura.</p>';
            return;
        }

        ?>
        <div class="izbor-dizni-kultura-selection">
            <h2>Izaberite kulturu</h2>
            <div class="izbor-dizni-kultura-grid">
                <?php foreach ($kulture as $kultura): ?>
                    <?php
                    $slika_kulture = get_field('slika_kulture_faze', 'kultura_' . $kultura->term_id);
                    ?>
                    <div class="izbor-dizni-kultura-item">
                        <a href="?kultura_id=<?php echo esc_attr($kultura->term_id); ?>" class="izbor-dizni-kultura-link">
                            <?php if ($slika_kulture): ?>
                                <div class="izbor-dizni-kultura-image">
                                    <img src="<?php echo esc_url($slika_kulture['sizes']['medium'] ?? $slika_kulture['url']); ?>"
                                         alt="<?php echo esc_attr($kultura->name); ?>" />
                                </div>
                            <?php endif; ?>
                            <h3><?php echo esc_html($kultura->name); ?></h3>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render kultura view with nozzles
     */
    private static function render_kultura_view($term_id) {
        $term = get_term($term_id, 'kultura');

        if (!$term || is_wp_error($term)) {
            echo '<p>Kultura nije pronađena.</p>';
            return;
        }

        $slika_kulture = get_field('slika_kulture_faze', 'kultura_' . $term_id);
        $pozicionirane_dizne = get_field('pozicionirane_dizne', 'kultura_' . $term_id);

        ?>
        <div class="izbor-dizni-kultura-view">
            <div class="izbor-dizni-header">
                <h2><?php echo esc_html($term->name); ?></h2>
                <a href="<?php echo esc_url(remove_query_arg('kultura_id')); ?>" class="izbor-dizni-back-button">
                    ← Nazad na izbor kulture
                </a>
            </div>

            <?php if ($slika_kulture): ?>
                <div class="izbor-dizni-canvas-container">
                    <div class="izbor-dizni-canvas-wrapper">
                        <img src="<?php echo esc_url($slika_kulture['url']); ?>"
                             alt="<?php echo esc_attr($term->name); ?>"
                             class="izbor-dizni-canvas-image" />

                        <div class="izbor-dizni-overlay">
                            <?php if ($pozicionirane_dizne && is_array($pozicionirane_dizne)): ?>
                                <?php foreach ($pozicionirane_dizne as $dizna_data): ?>
                                    <?php
                                    if (!isset($dizna_data['dizna']) || !$dizna_data['dizna']) {
                                        continue;
                                    }

                                    $dizna_id = $dizna_data['dizna'];
                                    $dizna_post = get_post($dizna_id);

                                    if (!$dizna_post) {
                                        continue;
                                    }

                                    $thumbnail = get_the_post_thumbnail_url($dizna_id, 'thumbnail');
                                    $pos_x = isset($dizna_data['pozicija_x']) ? $dizna_data['pozicija_x'] : 50;
                                    $pos_y = isset($dizna_data['pozicija_y']) ? $dizna_data['pozicija_y'] : 50;
                                    ?>
                                    <div class="izbor-dizni-nozzle"
                                         data-dizna-id="<?php echo esc_attr($dizna_id); ?>"
                                         style="position: absolute;
                                                left: <?php echo esc_attr($pos_x); ?>%;
                                                top: <?php echo esc_attr($pos_y); ?>%;
                                                transform: translate(-50%, -50%);">
                                        <?php if ($thumbnail): ?>
                                            <img src="<?php echo esc_url($thumbnail); ?>"
                                                 alt="<?php echo esc_attr($dizna_post->post_title); ?>"
                                                 class="izbor-dizni-nozzle-image" />
                                        <?php else: ?>
                                            <div class="izbor-dizni-nozzle-placeholder">
                                                <?php echo esc_html(substr($dizna_post->post_title, 0, 3)); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p>Slika kulture nije dostupna.</p>
            <?php endif; ?>
        </div>

        <!-- Popup Modal -->
        <div id="izbor-dizni-popup" class="izbor-dizni-popup" style="display: none;">
            <div class="izbor-dizni-popup-overlay"></div>
            <div class="izbor-dizni-popup-content">
                <button class="izbor-dizni-popup-close">&times;</button>
                <div class="izbor-dizni-popup-body">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX handler to get dizna data
     */
    public static function ajax_get_dizna_data() {
        $dizna_id = isset($_POST['dizna_id']) ? intval($_POST['dizna_id']) : 0;

        if (!$dizna_id) {
            wp_send_json_error('Invalid dizna ID');
        }

        $dizna = get_post($dizna_id);

        if (!$dizna || $dizna->post_type !== 'dizna') {
            wp_send_json_error('Dizna not found');
        }

        // Get ACF fields
        $primena = get_field('primena', $dizna_id);
        $vreme_primene = get_field('vreme_primene', $dizna_id);
        $materijal = get_field('materijal', $dizna_id);
        $sema_mlaza = get_field('sema_mlaza', $dizna_id);
        $radni_pritisak = get_field('radni_pritisak', $dizna_id);
        $brzina_hoda = get_field('brzina_hoda', $dizna_id);
        $protok = get_field('protok', $dizna_id);
        $featured_image = get_the_post_thumbnail_url($dizna_id, 'medium');

        ob_start();
        ?>
        <h2><?php echo esc_html($dizna->post_title); ?></h2>

        <div class="izbor-dizni-popup-grid">
            <div class="izbor-dizni-popup-images">
                <?php if ($featured_image): ?>
                    <div class="izbor-dizni-popup-image">
                        <h3>Slika dizne</h3>
                        <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr($dizna->post_title); ?>" />
                    </div>
                <?php endif; ?>

                <?php if ($sema_mlaza): ?>
                    <div class="izbor-dizni-popup-image">
                        <h3>Šema mlaza</h3>
                        <img src="<?php echo esc_url($sema_mlaza['url']); ?>" alt="Šema mlaza" />
                    </div>
                <?php endif; ?>
            </div>

            <div class="izbor-dizni-popup-data">
                <table class="izbor-dizni-popup-table">
                    <tbody>
                        <?php if ($primena): ?>
                            <tr>
                                <th>Primena:</th>
                                <td><?php echo esc_html($primena); ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($vreme_primene): ?>
                            <tr>
                                <th>Vreme primene:</th>
                                <td><?php echo esc_html($vreme_primene); ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($materijal): ?>
                            <tr>
                                <th>Materijal:</th>
                                <td><?php echo esc_html($materijal); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <h3>Preporučeni radni parametri</h3>
                <table class="izbor-dizni-popup-table">
                    <tbody>
                        <?php if ($radni_pritisak): ?>
                            <tr>
                                <th>Radni pritisak:</th>
                                <td><?php echo esc_html($radni_pritisak); ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($brzina_hoda): ?>
                            <tr>
                                <th>Brzina hoda:</th>
                                <td><?php echo esc_html($brzina_hoda); ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($protok): ?>
                            <tr>
                                <th>Protok:</th>
                                <td><?php echo esc_html($protok); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        $html = ob_get_clean();

        wp_send_json_success($html);
    }
}
