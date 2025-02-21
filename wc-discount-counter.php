<?php
/*
Plugin Name: WooCommerce Discount Counter
Description: نمایش شمارنده کد تخفیف در قالب پاپ آپ
Version: 1.0.0
Author: Your Name
Text Domain: wdc
Domain Path: /languages
*/

if (!defined('ABSPATH')) exit;

// تعریف ثابت‌ها
define('WDC_VERSION', '1.0.0');
define('WDC_PATH', plugin_dir_path(__FILE__));
define('WDC_URL', plugin_dir_url(__FILE__));

// افزودن استایل و اسکریپت
function wdc_enqueue_scripts() {
    wp_enqueue_style('wdc-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_script('wdc-script', plugins_url('js/script.js', __FILE__), array('jquery'), '1.0', true);
    
    // ارسال متغیر به جاوااسکریپت
    wp_localize_script('wdc-script', 'wdcData', array(
        'coupon_count' => get_option('wdc_coupon_limit', 100),
        'coupon_used' => get_option('wdc_coupon_used', 0)
    ));
}
add_action('wp_enqueue_scripts', 'wdc_enqueue_scripts');

// اضافه کردن پاپ آپ به فوتر
function wdc_add_popup() {
    ?>
    <div id="discount-popup" class="discount-popup">
        <div class="popup-content">
            <h4>کد تخفیف محدود!</h4>
            <p>تنها <span id="remaining-coupons"></span> کد تخفیف باقی مانده است</p>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'wdc_add_popup');

// شمارش استفاده از کد تخفیف
function wdc_count_coupon_usage($order_id) {
    $used = get_option('wdc_coupon_used', 0);
    update_option('wdc_coupon_used', $used + 1);
}
add_action('woocommerce_order_status_completed', 'wdc_count_coupon_usage');

// بارگذاری فایل‌ها
require_once WDC_PATH . 'includes/functions.php';
require_once WDC_PATH . 'includes/admin-settings.php';
require_once WDC_PATH . 'includes/security.php';
require_once WDC_PATH . 'includes/cache.php';
require_once WDC_PATH . 'includes/reports.php';

// فعال‌سازی افزونه
register_activation_hook(__FILE__, 'wdc_activate');

// غیرفعال‌سازی افزونه
register_deactivation_hook(__FILE__, 'wdc_deactivate');

load_plugin_textdomain('wdc', false, dirname(plugin_basename(__FILE__)) . '/languages');
