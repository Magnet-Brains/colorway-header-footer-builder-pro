<?php

namespace ColorwayHF;

defined('ABSPATH') || exit;

/* Global helper class. */
class Utils {
    /* Get Colorway Header Footer Builder older version if has any. */

    public static function old_version() {
        $version = get_option('colorwayhf_version');
        return null == $version ? -1 : $version;
    }

    /* Set Colorway Header Footer Builder installed version as current version. */

//    public static function set_version(){
//        //return update_option('colorwayhf_version', \ColorwayHF::plugin_url());
//    }

    /* Auto generate classname from path. */
    public static function make_classname($dirname) {
        $dirname = pathinfo($dirname, PATHINFO_FILENAME);
        $class_name = explode('-', $dirname);
        $class_name = array_map('ucfirst', $class_name);
        $class_name = implode('_', $class_name);

        return $class_name;
    }

    public static function google_fonts($font_families = []) {
        $fonts_url = '';
        if ($font_families) {
            $query_args = array(
                'family' => urlencode(implode('|', $font_families))
            );

            $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
        }

        return esc_url_raw($fonts_url);
    }

    public static function kses($raw) {

        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href' => array(),
                'rel' => array(),
                'title' => array(),
            ),
            'abbr' => array(
                'title' => array(),
            ),
            'b' => array(),
            'blockquote' => array(
                'cite' => array(),
            ),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'del' => array(
                'datetime' => array(),
                'title' => array(),
            ),
            'dd' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'dl' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(
                'class' => array(),
            ),
            'h2' => array(
                'class' => array(),
            ),
            'h3' => array(
                'class' => array(),
            ),
            'h4' => array(
                'class' => array(),
            ),
            'h5' => array(
                'class' => array(),
            ),
            'h6' => array(
                'class' => array(),
            ),
            'i' => array(
                'class' => array(),
            ),
            'img' => array(
                'alt' => array(),
                'class' => array(),
                'height' => array(),
                'src' => array(),
                'width' => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'q' => array(
                'cite' => array(),
                'title' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'iframe' => array(
                'width' => array(),
                'height' => array(),
                'scrolling' => array(),
                'frameborder' => array(),
                'allow' => array(),
                'src' => array(),
            ),
            'strike' => array(),
            'br' => array(),
            'strong' => array(),
            'data-wow-duration' => array(),
            'data-wow-delay' => array(),
            'data-wallpaper-options' => array(),
            'data-stellar-background-ratio' => array(),
            'ul' => array(
                'class' => array(),
            ),
        );

        if (function_exists('wp_kses')) { // WP is here
            return wp_kses($raw, $allowed_tags);
        } else {
            return $raw;
        }
    }

    public static function kspan($text) {
        return str_replace(['{', '}'], ['<span>', '</span>'], self::kses($text));
    }

    public static function trim_words($text, $num_words) {
        return wp_trim_words($text, $num_words, '');
    }

    public static function array_push_assoc($array, $key, $value) {
        $array[$key] = $value;
        return $array;
    }

    public static function render($content) {
        if (stripos($content, "colorwayhf-has-lisence") !== false) {
            return null;
        }

        return $content;
    }

    public static function render_elementor_content_css($content_id) {
        if (class_exists('\Elementor\Core\Files\CSS\Post')) {
            $css_file = new \Elementor\Core\Files\CSS\Post($content_id);
            $css_file->enqueue();
        }
    }

    public static function render_elementor_content($content_id) {
        $elementor_instance = \Elementor\Plugin::instance();
        return $elementor_instance->frontend->get_builder_content_for_display($content_id);
    }

    public static function render_tab_content($content, $id) {
        return str_replace('.elementor-' . $id . ' ', '#elementor .elementor-' . $id . ' ', $content);
    }

    public static function img_meta($id) {
        $attachment = get_post($id);
        if ($attachment == null || $attachment->post_type != 'attachment') {
            return null;
        }
        return [
            'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'href' => get_permalink($attachment->ID),
            'src' => $attachment->guid,
            'title' => $attachment->post_title
        ];
    }

}
