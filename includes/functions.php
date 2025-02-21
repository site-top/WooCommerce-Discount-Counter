<?php

function wdc_count_coupon_usage($order_id) {
    $order = wc_get_order($order_id);
    if ($order->get_used_coupons()) {
        $used_count = get_transient('wdc_used_coupons');
        $used_count = ($used_count) ? $used_count + 1 : 1;
        set_transient('wdc_used_coupons', $used_count, DAY_IN_SECONDS);
    }
}
add_action('woocommerce_order_status_completed', 'wdc_count_coupon_usage');

function wdc_display_popup() {
    $total = get_option('wdc_total_coupons');
    $used = get_transient('wdc_used_coupons');
    $remaining = $total - $used;
    
    if ($remaining > 0) {
        include plugin_dir_path(__FILE__) . 'templates/popup.php';
    }
}
add_action('wp_footer', 'wdc_display_popup');

function wdc_save_settings() {
    if (isset($_POST['wdc_settings'])) {
        $settings = array(
            'total_coupons' => absint($_POST['total_coupons']),
            'popup_position' => sanitize_text_field($_POST['popup_position'])
        );
        update_option('wdc_settings', $settings);
    }
}
add_action('admin_init', 'wdc_save_settings');

// پیگیری استفاده از کد تخفیف
function wdc_track_coupon_usage($coupon_code) {
    wp_cache_delete('wdc_coupon_count', 'wdc');
    $count = wdc_get_coupon_count();
    wp_cache_set('wdc_coupon_count', $count + 1, 'wdc', HOUR_IN_SECONDS);
}
add_action('woocommerce_applied_coupon', 'wdc_track_coupon_usage');
// توابع اصلی
function wdc_get_remaining_coupons() {
    $cached = wp_cache_get('wdc_remaining_coupons', 'wdc');
    
    if (false === $cached) {
        $total = get_option('wdc_total_coupons');
        $used = get_transient('wdc_used_coupons');
        $remaining = $total - $used;
        
        wp_cache_set('wdc_remaining_coupons', $remaining, 'wdc', HOUR_IN_SECONDS);
        return $remaining;
    }
    
    return $cached;
}

function wdc_update_counter() {
    $remaining = wdc_get_remaining_coupons();
    $remaining--;
    wp_cache_set('wdc_remaining_coupons', $remaining, 'wdc', HOUR_IN_SECONDS);
}

function wdc_display_popup() {
    $remaining = wdc_get_remaining_coupons();
    
    if ($remaining > 0) {
        include plugin_dir_path(__FILE__) . 'templates/popup.php';
    }
}

function wdc_init_cache() {
    wp_cache_add_global_groups('wdc');
}
add_action('init', 'wdc_init_cache');

function wdc_get_popup_styles() {
    $settings = get_option('wdc_settings');
    $styles = array(
        'background-color' => $settings['popup_bg_color'],
        'color' => $settings['popup_text_color']
    );
    
    switch($settings['popup_position']) {
        case 'bottom-left':
            $styles['left'] = '20px';
            $styles['right'] = 'auto';
            break;
        case 'bottom-center':
            $styles['left'] = '50%';
            $styles['transform'] = 'translateX(-50%)';
            $styles['right'] = 'auto';
            break;
        default:
            $styles['right'] = '20px';
    }
    
    return $styles;
}
