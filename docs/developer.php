<?php
/**
 * فیلتر برای تغییر متن پاپ‌آپ
 */
add_filter('wdc_popup_text', function($text) {
    return 'متن سفارشی شما';
});

/**
 * اکشن برای اجرای کد بعد از استفاده از کد تخفیف
 */
add_action('wdc_after_coupon_used', function($coupon_id) {
    // کد شما اینجا
});
