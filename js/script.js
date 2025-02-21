(function($) {
    'use strict';
    
    // نمایش پاپ‌آپ
    function showPopup() {
        $('#discount-popup').hide().fadeIn(1000);
    }
    
    // به‌روزرسانی شمارنده
    function updateCounter() {
        var remainingCoupons = wdcData.coupon_count - wdcData.coupon_used;
        $('#remaining-coupons').text(remainingCoupons);
    }
    
    // اجرای اولیه
    $(document).ready(function() {
        updateCounter();
        showPopup();
    });
})(jQuery);
