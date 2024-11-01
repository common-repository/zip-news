<?php

@include_once "api.php";
@include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

// Creating the widget
class ZNAW_ZipNewsWidget extends WP_Widget {
    function __construct() {
        $title = __('Zip.News Widget', 'znaw');
        $description = __('Zip.News api integration', 'znaw');
        parent::__construct('ZNAW_ZipNewsWidget', $title, array('description' => $description));
    }

    // Creating widget front-end
    public function widget($args, $instance) {
        wp_enqueue_style('znaw-main-css');
        $title = apply_filters('widget_title', array_key_exists('title', $instance) ? $instance['title'] : $this->name);

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $params = array();
        if (isset($instance['query']) && strlen($instance['query']) > 0) {
            $params['query'] = $instance['query'];
        }

        if (isset($instance['country']) && strlen($instance['country']) > 0) {
            $params['country'] = $instance['country'];
        }

        if (isset($instance['websites']) && strlen($instance['websites']) > 0) {
            $params['websites'] = $instance['websites'];
        }

        if (isset($instance['size'])) {
            $params['pageSize'] = $instance['size'];
        }

        $options = get_option('znaw_options');
        $params['userID'] = isset($options['user_id']) ? $options['user_id'] : 'demo';
        $params['apiToken'] = isset($options['api_token']) ? $options['api_token'] : 'demo';
        $expiration_period = isset($options['expiration_period']) ? $options['expiration_period'] : 7200;

        $params_string = print_r($params, true);
        $transient_key = 'znaw.api.cache.' . hash('sha256', $params_string);

        $cached_articles = get_option($transient_key);
        if (true || !$cached_articles || empty($cached_articles) || time() - $cached_articles['expire_time'] > 0) { // 2 hours
            $lock = WP_Upgrader::create_lock($transient_key);
            if ($lock) {
                try {
                    $api_articles = znaw_fetch_related_articles($params);
                    $cached_articles = array('articles' => $api_articles, 'expire_time' => time() + $expiration_period);
                    update_option($transient_key, $cached_articles);
                } catch (Exception $e) {
                    error_log("API error: ".$e);
                    echo "<p class='znaw-error'>{$e->getMessage()}</p>";
                } finally {
                    WP_Upgrader::release_lock($transient_key);
                }
            }
        }
        if ($cached_articles && array_key_exists('articles', $cached_articles)) {
            $articles = $cached_articles['articles'];
            if ($overridden_template = locate_template('related-articles.php')) {
                load_template($overridden_template, false, $articles);
            } else {
                load_template(dirname(__FILE__) . '/templates/related-articles.php', false, $articles);
            }
        }

        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Zip.News Articles', 'znaw');
        }

        if (isset($instance['size'])) {
            $size = $instance['size'];
        } else {
            $size = 10;
        }

        $query = isset($instance['query']) ? $instance['query'] : '';
        $country = isset($instance['country']) ? $instance['country'] : '';
        $websites = isset($instance['websites']) ? $instance['websites'] : '';

        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Widget title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('query'); ?>"><?php esc_html_e('Search query:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('query'); ?>"
                   name="<?php echo $this->get_field_name('query'); ?>" type="text"
                   value="<?php echo esc_attr($query); ?>"/>
            For information on how to build a query please refer to the <a href="https://zip.news/us/howToSearch.html">Zip.News
                How to search tutorial.</a>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('country'); ?>"><?php esc_html_e('Country:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('country'); ?>"
                    name="<?php echo $this->get_field_name('country'); ?>">
                <option value="AUTO">Auto detect</option>
                <option value="ASIA" <?php if ('ASIA' == $country) {
                    echo 'selected="selected"';
                } ?>>Asia
                </option>
                <option value="AL" <?php if ('AL' == $country) {
                    echo 'selected="selected"';
                } ?>>Albania
                </option>
                <option value="Austria" <?php if ('Austria' == $country) {
                    echo 'selected="selected"';
                } ?>>Austria
                </option>
                <option value="AU" <?php if ('AU' == $country) {
                    echo 'selected="selected"';
                } ?>>Australia
                </option>
                <option value="BY" <?php if ('BY' == $country) {
                    echo 'selected="selected"';
                } ?>>Belarus
                </option>
                <option value="BR" <?php if ('BR' == $country) {
                    echo 'selected="selected"';
                } ?>>Brazil
                </option>
                <option value="BE" <?php if ('BE' == $country) {
                    echo 'selected="selected"';
                } ?>>Belgium
                </option>
                <option value="BG" <?php if ('BG' == $country) {
                    echo 'selected="selected"';
                } ?>>Bulgaria
                </option>
                <option value="CA" <?php if ('CA' == $country) {
                    echo 'selected="selected"';
                } ?>>Canada
                </option>
                <option value="CY" <?php if ('CY' == $country) {
                    echo 'selected="selected"';
                } ?>>Cyprus
                </option>
                <option value="CZ" <?php if ('CZ' == $country) {
                    echo 'selected="selected"';
                } ?>>Czech Republic
                </option>
                <option value="DK" <?php if ('DK' == $country) {
                    echo 'selected="selected"';
                } ?>>Denmark
                </option>
                <option value="EE" <?php if ('EE' == $country) {
                    echo 'selected="selected"';
                } ?>>Estonia
                </option>
                <option value="EE_RU" <?php if ('EE_RU' == $country) {
                    echo 'selected="selected"';
                } ?>>Estonia-ru
                </option>
                <option value="EE_EN" <?php if ('EE_EN' == $country) {
                    echo 'selected="selected"';
                } ?>>Estonia-en
                </option>
                <option value="ES" <?php if ('ES' == $country) {
                    echo 'selected="selected"';
                } ?>>España
                </option>
                <option value="FI" <?php if ('FI' == $country) {
                    echo 'selected="selected"';
                } ?>>Finland
                </option>
                <option value="FR" <?php if ('FR' == $country) {
                    echo 'selected="selected"';
                } ?>>France
                </option>
                <option value="GR" <?php if ('GR' == $country) {
                    echo 'selected="selected"';
                } ?>>Greece
                </option>
                <option value="GB" <?php if ('GB' == $country) {
                    echo 'selected="selected"';
                } ?>>Great Britain
                </option>
                <option value="Germany" <?php if ('Germany' == $country) {
                    echo 'selected="selected"';
                } ?>>Germany
                </option>
                <option value="HU" <?php if ('HU' == $country) {
                    echo 'selected="selected"';
                } ?>>Hungary
                </option>
                <option value="IS" <?php if ('IS' == $country) {
                    echo 'selected="selected"';
                } ?>>Iceland
                </option>
                <option value="IE" <?php if ('IE' == $country) {
                    echo 'selected="selected"';
                } ?>>Ireland, Republic of Ireland
                </option>
                <option value="IL" <?php if ('IL' == $country) {
                    echo 'selected="selected"';
                } ?>>Israel
                </option>
                <option value="IT" <?php if ('IT' == $country) {
                    echo 'selected="selected"';
                } ?>>Italy
                </option>
                <option value="LT" <?php if ('LT' == $country) {
                    echo 'selected="selected"';
                } ?>>Lithuania
                </option>
                <option value="LT_RU" <?php if ('LT_RU' == $country) {
                    echo 'selected="selected"';
                } ?>>Lithuania-ru
                </option>
                <option value="LT_EN" <?php if ('LT_EN' == $country) {
                    echo 'selected="selected"';
                } ?>>Lithuania-en
                </option>
                <option value="LV" <?php if ('LV' == $country) {
                    echo 'selected="selected"';
                } ?>>Latvia
                </option>
                <option value="LV_RU" <?php if ('LV_RU' == $country) {
                    echo 'selected="selected"';
                } ?>>Latvia-ru
                </option>
                <option value="LV_EN" <?php if ('LV_EN' == $country) {
                    echo 'selected="selected"';
                } ?>>Latvia-en
                </option>
                <option value="LI" <?php if ('LI' == $country) {
                    echo 'selected="selected"';
                } ?>>Liechtenstein
                </option>
                <option value="LU" <?php if ('LU' == $country) {
                    echo 'selected="selected"';
                } ?>>Luxembourg
                </option>
                <option value="MT" <?php if ('MT' == $country) {
                    echo 'selected="selected"';
                } ?>>Malta
                </option>
                <option value="MX" <?php if ('MX' == $country) {
                    echo 'selected="selected"';
                } ?>>Mexico
                </option>
                <option value="MD" <?php if ('MD' == $country) {
                    echo 'selected="selected"';
                } ?>>Moldova
                </option>
                <option value="MK" <?php if ('MK' == $country) {
                    echo 'selected="selected"';
                } ?>>Macedonia
                </option>
                <option value="NZ" <?php if ('NZ' == $country) {
                    echo 'selected="selected"';
                } ?>>New Zealand
                </option>
                <option value="NO" <?php if ('NO' == $country) {
                    echo 'selected="selected"';
                } ?>>Norway
                </option>
                <option value="NL" <?php if ('NL' == $country) {
                    echo 'selected="selected"';
                } ?>>Netherlands
                </option>
                <option value="PL" <?php if ('PL' == $country) {
                    echo 'selected="selected"';
                } ?>>Poland
                </option>
                <option value="PT" <?php if ('PT' == $country) {
                    echo 'selected="selected"';
                } ?>>Portuguese
                </option>
                <option value="RU" <?php if ('RU' == $country) {
                    echo 'selected="selected"';
                } ?>>Russia
                </option>
                <option value="RS" <?php if ('RS' == $country) {
                    echo 'selected="selected"';
                } ?>>Serbia
                </option>
                <option value="RO" <?php if ('RO' == $country) {
                    echo 'selected="selected"';
                } ?>>Romania
                </option>
                <option value="SE" <?php if ('SE' == $country) {
                    echo 'selected="selected"';
                } ?>>Sweden
                </option>
                <option value="SK" <?php if ('SK' == $country) {
                    echo 'selected="selected"';
                } ?>>Slovakia
                </option>
                <option value="SI" <?php if ('SI' == $country) {
                    echo 'selected="selected"';
                } ?>>Slovenia
                </option>
                <option value="CH" <?php if ('CH' == $country) {
                    echo 'selected="selected"';
                } ?>>Switzerland
                </option>
                <option value="TR" <?php if ('TR' == $country) {
                    echo 'selected="selected"';
                } ?>>Turkey
                </option>
                <option value="UA" <?php if ('UA' == $country) {
                    echo 'selected="selected"';
                } ?>>Ukraine
                </option>
                <option value="VA" <?php if ('VA' == $country) {
                    echo 'selected="selected"';
                } ?>>Vatican
                </option>
                <option value="US" <?php if ('US' == $country) {
                    echo 'selected="selected"';
                } ?>>USA
                </option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('websites'); ?>"><?php _e('Only from Websites:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('websites'); ?>"
                   name="<?php echo $this->get_field_name('websites'); ?>" type="text"
                   value="<?php echo esc_attr($websites); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Related count:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('size'); ?>"
                   name="<?php echo $this->get_field_name('size'); ?>" type="text"
                   value="<?php echo esc_attr($size); ?>"/>
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $new_instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $new_instance['query'] = (!empty($new_instance['query'])) ? strip_tags($new_instance['query']) : '';
        $new_instance['websites'] = (!empty($new_instance['websites'])) ? strip_tags($new_instance['websites']) : '';
        return $new_instance;
    }

}