<?php
/**
 * @package zipnews
 * Plugin Name: Zip.News API
 * Plugin URI: https://zip.news/wordpress-zipnews.zip
 * Description: Zip.News Integration that provides widgets for related articles from the zip news API
 * Tags: news api, zip.news, news, api, articles, zipnews, zip news
 * Version: 1.5.0
 * Requires at least: 5.5
 * Tested up to: 5.8
 * Requires PHP: 7.3
 * Author: Less is More ApS
 * Author URI: https://less-is-more.dk
 * License: GPLv2 or later
 * Text Domain: znaw
 */

include_once "zipnews-widget.php";
include_once "settings.php";
include_once "admin-notice.php";

add_action('widgets_init', 'znaw_load_widget');
function znaw_load_widget() {
    register_widget('ZNAW_ZipNewsWidget');
}

add_action('wp_enqueue_scripts', 'znaw_register_resources');
function znaw_register_resources() {
    wp_register_style('znaw_zipnews-css', plugins_url('assets/css/zipnews.css', __FILE__), [], '1.0.2');
}

function znaw_format_date($ts) {
    if (!ctype_digit($ts)) {
        $ts = strtotime($ts);
    }
    $diff = time() - $ts;
    if ($diff == 0) {
        return 'now';
    } elseif ($diff > 0) {
        $day_diff = floor($diff / 86400);
        if ($day_diff == 0) {
            if ($diff < 60) return 'just now';
            if ($diff < 120) return '1 minute ago';
            if ($diff < 3600) return floor($diff / 60) . ' minutes ago';

            // compare with current time to see if it was posted yesterday
            if (date('H') < ($diff / 3600)) return 'Yesterday';

            // if today
            if ($diff < 7200) return '1 hour ago';
            if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if ($day_diff == 1) {
            return 'Yesterday';
        }
        if ($day_diff < 7) {
            return $day_diff . ' days ago';
        }
        if ($day_diff < 31) {
            return ceil($day_diff / 7) . ' weeks ago';
        }
        if ($day_diff < 60) {
            return 'last month';
        }
        return date('F Y', $ts);
    } else {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if ($day_diff == 0) {
            if ($diff < 120) {
                return 'in a minute';
            }
            if ($diff < 3600) {
                return 'in ' . floor($diff / 60) . ' minutes';
            }
            if ($diff < 7200) {
                return 'in an hour';
            }
            if ($diff < 86400) {
                return 'in ' . floor($diff / 3600) . ' hours';
            }
        }
        if ($day_diff == 1) {
            return 'Tomorrow';
        }
        if ($day_diff < 4) {
            return date('l', $ts);
        }
        if ($day_diff < 7 + (7 - date('w'))) {
            return 'next week';
        }
        if (ceil($day_diff / 7) < 4) {
            return 'in ' . ceil($day_diff / 7) . ' weeks';
        }
        if (date('n', $ts) == date('n') + 1) {
            return 'next month';
        }
        return date('F Y', $ts);
    }
}

function znaw_excerpt_e($text) {
    $text = strip_shortcodes($text);
    $text = apply_filters('znaw_the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
    $excerpt_length = apply_filters('znaw_excerpt_length', 35);
    $excerpt_more = apply_filters('znaw_excerpt_more', ' ' . '[&hellip;]');
    $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
    echo $text;
}
