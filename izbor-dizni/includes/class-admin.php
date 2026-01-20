<?php
/**
 * Admin functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class Izbor_Dizni_Admin {

    /**
     * Initialize
     */
    public static function init() {
        add_action('kultura_edit_form_fields', array(__CLASS__, 'add_drag_drop_interface'), 20, 2);
        add_action('kultura_add_form_fields', array(__CLASS__, 'add_drag_drop_interface_notice'), 20);
    }

    /**
     * Add notice on add form
     */
    public static function add_drag_drop_interface_notice() {
        ?>
        <div class="form-field">
            <p><strong>Napomena:</strong> Drag & drop interfejs za pozicioniranje dizni biće dostupan nakon što sačuvate kulturu i dodate sliku faza rasta.</p>
        </div>
        <?php
    }

    /**
     * Add drag & drop interface to kultura edit page
     */
    public static function add_drag_drop_interface($term) {
        // Get the image
        $slika_kulture = get_field('slika_kulture_faze', 'kultura_' . $term->term_id);
        $pozicionirane_dizne = get_field('pozicionirane_dizne', 'kultura_' . $term->term_id);

        if (!$pozicionirane_dizne) {
            $pozicionirane_dizne = array();
        }

        // Get all dizne
        $all_dizne = get_posts(array(
            'post_type' => 'dizna',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));

        ?>
        <tr class="form-field">
            <th scope="row">
                <label>Drag & Drop interfejs</label>
            </th>
            <td>
                <?php if ($slika_kulture): ?>
                    <div id="izbor-dizni-drag-drop-container">
                        <div class="izbor-dizni-controls">
                            <button type="button" class="button button-secondary" id="izbor-dizni-add-nozzle">
                                Dodaj diznu
                            </button>
                            <select id="izbor-dizni-nozzle-select" style="display:none; margin-left: 10px;">
                                <option value="">-- Izaberite diznu --</option>
                                <?php foreach ($all_dizne as $dizna): ?>
                                    <option value="<?php echo esc_attr($dizna->ID); ?>"
                                            data-thumbnail="<?php echo esc_url(get_the_post_thumbnail_url($dizna->ID, 'thumbnail')); ?>">
                                        <?php echo esc_html($dizna->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="izbor-dizni-canvas-wrapper" style="position: relative; display: inline-block; margin-top: 20px;">
                            <img src="<?php echo esc_url($slika_kulture['url']); ?>"
                                 id="izbor-dizni-canvas-image"
                                 style="max-width: 100%; height: auto; display: block;" />

                            <div id="izbor-dizni-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                <?php
                                if ($pozicionirane_dizne && is_array($pozicionirane_dizne)):
                                    foreach ($pozicionirane_dizne as $index => $dizna_data):
                                        if (isset($dizna_data['dizna']) && $dizna_data['dizna']):
                                            $dizna_id = $dizna_data['dizna'];
                                            $dizna_post = get_post($dizna_id);
                                            if ($dizna_post):
                                                $thumbnail = get_the_post_thumbnail_url($dizna_id, 'thumbnail');
                                                $pos_x = isset($dizna_data['pozicija_x']) ? $dizna_data['pozicija_x'] : 50;
                                                $pos_y = isset($dizna_data['pozicija_y']) ? $dizna_data['pozicija_y'] : 50;
                                ?>
                                <div class="izbor-dizni-nozzle-icon"
                                     data-index="<?php echo esc_attr($index); ?>"
                                     data-dizna-id="<?php echo esc_attr($dizna_id); ?>"
                                     style="position: absolute;
                                            left: <?php echo esc_attr($pos_x); ?>%;
                                            top: <?php echo esc_attr($pos_y); ?>%;
                                            transform: translate(-50%, -50%);
                                            cursor: move;">
                                    <?php if ($thumbnail): ?>
                                        <img src="<?php echo esc_url($thumbnail); ?>"
                                             style="width: 50px; height: 50px; border: 2px solid #0073aa; border-radius: 5px; background: white;"
                                             title="<?php echo esc_attr($dizna_post->post_title); ?>" />
                                    <?php else: ?>
                                        <div style="width: 50px; height: 50px; background: #0073aa; color: white;
                                                    display: flex; align-items: center; justify-content: center;
                                                    border-radius: 5px; font-size: 10px; text-align: center;">
                                            <?php echo esc_html(substr($dizna_post->post_title, 0, 3)); ?>
                                        </div>
                                    <?php endif; ?>
                                    <button type="button" class="izbor-dizni-remove-nozzle"
                                            style="position: absolute; top: -8px; right: -8px;
                                                   background: red; color: white; border: none;
                                                   border-radius: 50%; width: 20px; height: 20px;
                                                   cursor: pointer; font-size: 12px; line-height: 1;">
                                        ×
                                    </button>
                                </div>
                                <?php
                                            endif;
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>

                        <p class="description" style="margin-top: 10px;">
                            Kliknite na "Dodaj diznu", izaberite diznu iz liste, i prevucite je na željeno mesto na slici.
                            Pozicije će automatski biti sačuvane kada sačuvate promene.
                        </p>
                    </div>
                <?php else: ?>
                    <p>Molimo prvo dodajte sliku kulture u fazama rasta.</p>
                <?php endif; ?>
            </td>
        </tr>

        <script type="text/javascript">
            // Store term ID for JavaScript
            var izborDizniTermId = <?php echo (int)$term->term_id; ?>;
        </script>
        <?php
    }
}
