$(document).ready(function() {
    $('#filter-class').select2({
        placeholder: "Nhập tên lớp",
        allowClear: true,
        tags: true
    });
    $('#filter-course').select2({
        placeholder: "Nhập tên khóa học",
        allowClear: true,
        tags: true
    });
    $('#course_id').select2({
        placeholder: "-- Chọn khóa học --",
        allowClear: true,
        tags: true
    });

    $('.btn-clear').on('click', function() {
        // Reset all select options to empty
        $('#filter-class').val('');
        $('#status').val('');
        $('#filter-course').val('');

        // Submit the form after clearing
        $('.search-form').submit();
    });

    $('#filter-student-name').select2({
        placeholder: "Nhập tên học sinh",
        allowClear: true,
        tags: true
    });
    
    $('#filter-teacher-name').select2({
        placeholder: "Nhập tên giáo viên",
        allowClear: true,
        tags: true
    });

    $('#search-teacher-other').select2({
        placeholder: "Nhập tên giáo viên",
        allowClear: true,
        tags: true
    });

    $('#search-student-other').select2({
        placeholder: "Nhập tên học sinh",
        allowClear: true,
        tags: true
    });

    $('#filter-schedule').select2({
        placeholder: "Nhập tên sự kiện",
        allowClear: true,
        tags: true
    });

    // $('.btnDeleteClass').on('click', function() {
    //     const classId = $('.btnDeleteClass').attr('data-class-id');
    //     const className = $('.btnDeleteClass').attr('data-class-name');
        
    //     $('.deleteClassModal .this_item_name').text(className);

    //     $('.deleteClassModal #classesId').val(classId);

    //     $('.deleteClassModal').modal('show');
    // });

    $('.btn-action-delete-class').on('click', function() {
        $('#deleteClassForm').submit();
    });

    $(".btn-show-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        const input = $(this).closest(".input-group").find("input");
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
});