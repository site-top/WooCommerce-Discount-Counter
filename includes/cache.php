<?php
// مدیریت کش
function wdc_cache_init() {
    wp_cache_add_global_groups('wdc_cache');
    
    // کش کردن تنظیمات
    $settings = wp_cache_get('settings', 'wdc_cache');
    if (false === $settings) {
        $settings = get_option('wdc_settings');
        wp_cache_set('settings', $settings, 'wdc_cache', HOUR_IN_SECONDS);
    }
    
    return $settings;
}

function wdc_clear_cache() {
    wp_cache_delete('settings', 'wdc_cache');
}
