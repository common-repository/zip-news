<?php

add_filter('plugin_action_links_wordpress-zipnews/wordpress-zipnews.php', 'znaw_action_links', 10, 4);
function znaw_action_links($actions, $plugin_file, $plugin_data, $context) {
    $link = add_query_arg('page', 'znaw', get_admin_url(null, 'options-general.php'));
    $actions['settings'] = "<a href='{$link}'>" . esc_html__('Settings') . "</a>";
    return $actions;
}

add_action('admin_menu', 'znaw_add_settings_page');
function znaw_add_settings_page() {
    $title = esc_html__('Zip.News Settings', 'znaw');
    add_options_page($title, $title, 'manage_options', 'znaw', 'znaw_render_settings_page');
}

function znaw_render_settings_page() {
    ?>
    <form action="options.php" method="post">
        <?php
        settings_fields('znaw_options');
        do_settings_sections('znaw'); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>"/>
    </form>
    <?php
}

add_action('admin_init', 'znaw_register_settings');
function znaw_register_settings() {
    register_setting('znaw_options', 'znaw_options', 'znaw_options_validate');
    add_settings_section('znaw_settings', 'Zip.News Settings', 'znaw_section_text', 'znaw');

    add_settings_field('znaw_settings_user_id', 'User ID', 'znaw_settings_user_id', 'znaw', 'znaw_settings');
    add_settings_field('znaw_settings_api_token', 'API Token', 'znaw_settings_api_token', 'znaw', 'znaw_settings');
    add_settings_field('znaw_settings_expiration_period', 'Refresh period', 'znaw_settings_expiration_period', 'znaw', 'znaw_settings');
}

function znaw_options_validate($input) {
    $newinput = array();
    if (isset($input['user_id']) && preg_match("/[a-zA-Z0-9]+/", $input['user_id'])) {
        $newinput['user_id'] = $input['user_id'];
    }
    if (isset($input['api_token']) && preg_match("/[a-zA-Z0-9]+/", $input['api_token'])) {
        $newinput['api_token'] = $input['api_token'];
    }
    // 2 hours or 10 min
    if (isset($input['expiration_period']) && (in_array($input['expiration_period'], array(600, 7200)))) {
        $newinput['expiration_period'] = $input['expiration_period'];
    }
    return $newinput;
}

function znaw_section_text() {
    echo '<p>Here you can set your access user id and token for the API. To obtain an ID and a Token, please follow <a href="https://zip.news/es/howToNewsApiDoc.html">this guide</a>.</p>';
}

function znaw_settings_user_id() {
    $options = get_option('znaw_options');
    if (!empty($options)) {
        $value = array_key_exists('user_id', $options) ? esc_attr($options['user_id']) : '';
    } else {
        $value = '';
    }
    echo "<input name='znaw_options[user_id]' type='text' value='{$value}' />";
}

function znaw_settings_api_token() {
    $options = get_option('znaw_options');
    if (!empty($options)) {
        $value = array_key_exists('api_token', $options) ? esc_attr($options['api_token']) : '';
    } else {
        $value = '';
    }
    echo "<input name='znaw_options[api_token]' type='text' value='{$value}' />";
}

function znaw_settings_expiration_period() {
    $options = get_option('znaw_options');
    if (!empty($options)) {
        $value = array_key_exists('expiration_period', $options) ? esc_attr($options['expiration_period']) : '7200';
    } else {
        $value = '';
    }
    echo "<select name='znaw_options[expiration_period]'>";

    echo "<option value='7200'".($value == 7200 ? " selected='selected'" : "").">2 hours</option>";
    echo "<option value='600'".($value == 600 ? " selected='selected'" : "").">10 minutes</option>";
    echo "</select>";
    echo "<p class='description'>Only accounts with level <a href='https://zip.news/prices.html'>NEWS API</a> level will have enough API calls per day to be able to call the server every 10 minutes</p>";
}
