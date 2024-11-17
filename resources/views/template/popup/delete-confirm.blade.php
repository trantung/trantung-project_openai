<!-- Popup Structure -->
<div class="popup-wrapper mform delete-confirm-popup" id="delete-confirm-popup">
    <div class="popup-content">
        <!-- Header -->
        <div class="popup-header">
            <h3 class="my-0">Xác Nhận Xóa</h3>
            <span class="popup-close" onclick="closePopupDelete()">&times;</span>
        </div>

        <!-- Body -->
        <div class="popup-body">
            <p>Bạn có chắc muốn xóa những file 1 đã được chọn?</p>
        </div>

        <!-- Footer -->
        <div class="popup-footer">
            <div class="text-center">
                <button class="fp-dlg-butconfirm btn-primary btn">Đồng ý</button>
                <button class="fp-dlg-butcancel btn-secondary btn" id="yui_3_17_2_1_1730535132004_2349">Huỷ bỏ</button>
            </div>
        </div>
    </div>
</div>

<script>
    function closePopupDelete() {
        $('.delete-confirm-popup').css('display', 'none');
    }

    // Xử lý sự kiện click cho nút hủy
    $(document).on('click', '.fp-dlg-butcancel', function(event) {
        closePopupDelete();
    });
</script>
