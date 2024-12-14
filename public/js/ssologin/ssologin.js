$(document).ready(function() {
    $('#search-teacher-sso').select2({
        placeholder: "Nhập thông tin giáo viên",
        allowClear: true,
        minimumInputLength: 2, // Chỉ bắt đầu tìm kiếm khi người dùng gõ ít nhất 2 ký tự
        ajax: {
            url: '/api/class/teacher/search', // Địa chỉ API trong Laravel
            dataType: 'json',
            delay: 250, // Thời gian trì hoãn để không gửi quá nhiều yêu cầu
            data: function (params) {
                return {
                    searchTerm: params.term, // Truyền từ khóa tìm kiếm
                };
            },
            processResults: function (data) {
                // Dữ liệu trả về từ server phải có định dạng đúng với yêu cầu của Select2
                var results = $.map(data, function (teacher) {
                    return {
                        id: teacher.id,   // ID của giáo viên
                        text: teacher.name + '(' + teacher.email + ')' // Tên giáo viên
                    };
                });

                // Nếu bạn cần phân trang, có thể bổ sung thêm phần này:
                return {
                    results: results
                };
            },
            cache: true // Cache kết quả để giảm tải server
        }
    });

    $('.btn-clear').on('click', function() {
        // Reset all select options to empty
        $('#search-teacher-sso').val('');

        // Submit the form after clearing
        $('.search-form').submit();
    });
});
