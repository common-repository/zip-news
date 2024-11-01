<?php
function znaw_general_admin_notice() {
    $options = get_option('znaw_options');
    if (!empty($options) && isset($options['user_id']) && isset($options['api_token'])) {
        return;
    }
    $settings_url = add_query_arg('page', 'znaw', get_admin_url(null, 'options-general.php'));;

    if (is_admin() && is_user_logged_in() && !wp_doing_ajax()) {
        echo "<div class='notice notice-warning is-dismissible'>
             <p>[Zip.News] please visit the <a href='{$settings_url}'>settings</a> page and fill your User ID and ApiToken.</p>
         </div>";
    }
}
add_action('admin_notices', 'znaw_general_admin_notice');