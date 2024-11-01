<?php

function znaw_fetch_related_articles($params) {
    error_log('[ZN] Fetching from the API');
    $query = http_build_query($params);

    error_log($query);

    $api = wp_remote_get("https://zip.news/us/partnerapi/search?".$query);
    if (is_wp_error($api)) {
        $error_log = print_r($api, true);
        error_log('[ZN] THE ERROR IS HERE'.$error_log);
        throw new Exception($error_log);
    }
    $json = json_decode($api['body']);

    if (isset($json->status) && $json->status != 200) {
        if (isset($json->error) && isset($json->message)) {
            throw new Exception("{$json->error}: {$json->message}");
        }
    }

    if (isset($json->articles)) {
        return $json->articles;
    } else {
        return [];
    }
}