<?php
// سیستم گزارش‌گیری
function wdc_generate_report() {
    global $wpdb;
    
    $stats = array(
        'total_used' => get_transient('wdc_used_coupons'),
        'remaining' => wdc_get_remaining_coupons(),
        'usage_by_date' => $wpdb->get_results("
            SELECT DATE(post_date) as date, COUNT(*) as count 
            FROM {$wpdb->posts} p
            JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE pm.meta_key = 'used_coupons'
            GROUP BY DATE(post_date)
        ")
    );
    
    return $stats;
}

function wdc_display_report() {
    // Implementation for displaying the report
}
