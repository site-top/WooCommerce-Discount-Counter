<?php
// کلاس امنیت
class WDC_Security {
    public static function validate_request() {
        if (!check_ajax_referer('wdc_nonce', 'nonce')) {
            wp_send_json_error('Invalid nonce');
        }
    }
    
    public static function sanitize_data($data) {
        return array_map('sanitize_text_field', $data);
    }
}
