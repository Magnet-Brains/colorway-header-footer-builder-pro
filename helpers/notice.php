<?php

namespace ColorwayHF;

if (!defined('ABSPATH'))
    die('Forbidden');
/* ColorwayHF notice class. */

class Notice {
    /* Dismiss Notice. */

    public function dismiss() {

        $id = ( isset($_POST['id']) ) ? $_POST['id'] : '';
        $time = ( isset($_POST['time']) ) ? $_POST['time'] : '';
        $meta = ( isset($_POST['meta']) ) ? $_POST['meta'] : '';

        // Valid inputs?
        if (!empty($id)) {

            if ('user' === $meta) {
                update_user_meta(get_current_user_id(), $id, true);
            } else {
                set_transient($id, true, $time);
            }

            wp_send_json_success();
        }

        wp_send_json_error();
    }

    /* Enqueue Scripts. */

    public function enqueue_scripts() {
        echo "
			<script>
			jQuery(document).ready(function ($) {
				$( '.colorwayhf-notice.is-dismissible' ).on( 'click', '.notice-dismiss', function() {
					//console.log('test');
					_this 		= $( this ).parents( '.colorwayhf-active-notice' );
					var id 	= _this.attr( 'id' ) || '';
					var time 	= _this.attr( 'dismissible-time' ) || '';
					var meta 	= _this.attr( 'dismissible-meta' ) || '';
			
					$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							action 	: 'colorwayhf-notices',
							id 		: id,
							meta 	: meta,
							time 	: time,
						},
					});
			
				});
			
			});
			</script>
		";
    }

    /* Show Notices */

    public static function push($notice) {

        $defaults = [
            'id' => '',
            'type' => 'info',
            'show_if' => true,
            'message' => '',
            'class' => 'colorwayhf-active-notice',
            'dismissible' => false,
            'btn' => [],
            'dismissible-meta' => 'user',
            'dismissible-time' => WEEK_IN_SECONDS,
            'data' => '',
        ];

        $notice = wp_parse_args($notice, $defaults);

        $classes = ['colorwayhf-notice', 'notice'];

        $classes[] = $notice['class'];
        if (isset($notice['type'])) {
            $classes[] = 'notice-' . $notice['type'];
        }

        // Is notice dismissible?
        if (true === $notice['dismissible']) {
            $classes[] = 'is-dismissible';

            // Dismissable time.
            $notice['data'] = ' dismissible-time=' . esc_attr($notice['dismissible-time']) . ' ';
        }

        // Notice ID.
        $notice_id = 'colorwayhf-sites-notice-id-' . $notice['id'];
        $notice['id'] = $notice_id;
        if (!isset($notice['id'])) {
            $notice_id = 'colorwayhf-sites-notice-id-' . $notice['id'];
            $notice['id'] = $notice_id;
        } else {
            $notice_id = $notice['id'];
        }

        $notice['classes'] = implode(' ', $classes);

        // User meta.
        $notice['data'] .= ' dismissible-meta=' . esc_attr($notice['dismissible-meta']) . ' ';
        if ('user' === $notice['dismissible-meta']) {
            $expired = get_user_meta(get_current_user_id(), $notice_id, true);
        } elseif ('transient' === $notice['dismissible-meta']) {
            $expired = get_transient($notice_id);
        }

        // Notice visible after transient expire.
        if (isset($notice['show_if'])) {
            if (true === $notice['show_if']) {

                // Is transient expired?
                if (false === $expired || empty($expired)) {
                    self::markup($notice);
                }
            }
        } else {
            self::markup($notice);
        }
    }

    /* Markup Notice. */

    public static function markup($notice = []) {
        ?>
        <div id="<?php echo esc_attr($notice['id']); ?>" class="<?php echo esc_attr($notice['classes']); ?>" <?php echo \ColorwayHF\Utils::render($notice['data']); ?>>
            <p>
                <?php echo \ColorwayHF\Utils::kses($notice['message']); ?>
            </p>

            <?php if (!empty($notice['btn'])): ?>
                <p>
                    <a href="<?php echo esc_url($notice['btn']['url']); ?>" class="button-primary"><?php echo esc_html($notice['btn']['label']); ?></a>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }

}

new Notice();
