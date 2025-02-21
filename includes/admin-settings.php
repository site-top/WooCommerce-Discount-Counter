<?php
function wdc_add_settings_field() {
    add_settings_field(
        'wdc_total_coupons',
        'تعداد کل کدهای تخفیف',
        'wdc_total_coupons_callback',
        'general'
    );
    register_setting('general', 'wdc_total_coupons');
}
add_action('admin_init', 'wdc_add_settings_field');
<?php
// صفحه تنظیمات مدیریت
function wdc_add_admin_menu() {
    add_menu_page(
        'تنظیمات شمارنده تخفیف',
        'شمارنده تخفیف',
        'manage_options',
        'wdc-settings',
        'wdc_settings_page',
        'dashicons-clock',
        56
    );
}
add_action('admin_menu', 'wdc_add_admin_menu');

function wdc_settings_page() {
    // بررسی دسترسی مدیر
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // دریافت تنظیمات فعلی
    $settings = get_option('wdc_settings', array(
        'total_coupons' => 100,
        'popup_enabled' => true,
        'popup_text' => 'تنها %count% کد تخفیف باقی مانده است!',
        'popup_position' => 'bottom-right',
        'popup_bg_color' => '#ffffff',
        'popup_text_color' => '#000000'
    ));
    ?>
    
    <div class="wrap">
        <h1>تنظیمات شمارنده تخفیف</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th>تعداد کل کدهای تخفیف:</th>
                    <td>
                        <input type="number" name="wdc_total_coupons" 
                               value="<?php echo esc_attr($settings['total_coupons']); ?>" min="1">
                    </td>
                </tr>
                
                <tr>
                    <th>نمایش پاپ‌آپ:</th>
                    <td>
                        <label>
                            <input type="checkbox" name="wdc_popup_enabled" 
                                   <?php checked($settings['popup_enabled']); ?>>
                            فعال
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th>متن پاپ‌آپ:</th>
                    <td>
                        <textarea name="wdc_popup_text" rows="3" cols="50"><?php 
                            echo esc_textarea($settings['popup_text']); 
                        ?></textarea>
                        <p class="description">از %count% برای نمایش تعداد باقیمانده استفاده کنید</p>
                    </td>
                </tr>
                
                <tr>
                    <th>موقعیت نمایش:</th>
                    <td>
                        <select name="wdc_popup_position">
                            <option value="bottom-right" <?php selected($settings['popup_position'], 'bottom-right'); ?>>
                                پایین راست
                            </option>
                            <option value="bottom-left" <?php selected($settings['popup_position'], 'bottom-left'); ?>>
                                پایین چپ
                            </option>
                            <option value="bottom-center" <?php selected($settings['popup_position'], 'bottom-center'); ?>>
                                پایین وسط
                            </option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th>رنگ پس‌زمینه:</th>
                    <td>
                        <input type="color" name="wdc_popup_bg_color" 
                               value="<?php echo esc_attr($settings['popup_bg_color']); ?>">
                    </td>
                </tr>
                
                <tr>
                    <th>رنگ متن:</th>
                    <td>
                        <input type="color" name="wdc_popup_text_color" 
                               value="<?php echo esc_attr($settings['popup_text_color']); ?>">
                    </td>
                </tr>
            </table>
            
            <?php wp_nonce_field('wdc_settings_nonce'); ?>
            <input type="submit" name="wdc_save_settings" class="button button-primary" value="ذخیره تنظیمات">
        </form>
    </div>
    <?php
}

function wdc_save_settings() {
    if (isset($_POST['wdc_save_settings']) && check_admin_referer('wdc_settings_nonce')) {
        $settings = array(
            'total_coupons' => absint($_POST['wdc_total_coupons']),
            'popup_enabled' => isset($_POST['wdc_popup_enabled']),
            'popup_text' => sanitize_textarea_field($_POST['wdc_popup_text']),
            'popup_position' => sanitize_text_field($_POST['wdc_popup_position']),
            'popup_bg_color' => sanitize_hex_color($_POST['wdc_popup_bg_color']),
            'popup_text_color' => sanitize_hex_color($_POST['wdc_popup_text_color'])
        );
        update_option('wdc_settings', $settings);
        add_settings_error('wdc_settings', 'settings_updated', 'تنظیمات با موفقیت ذخیره شد.', 'updated');
    }
}
add_action('admin_init', 'wdc_save_settings');
