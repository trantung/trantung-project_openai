$(document).ready(function() {
    $('#nav_tab_list a').on('click', function (e) {
        // Cập nhật hash trong URL khi click tab
        var targetTab = $(this).attr('href');
        window.location.hash = targetTab; // Cập nhật hash trong URL
    });

    // Khi trang được tải lại, kiểm tra hash trên URL
    if (window.location.hash) {
        var activeTab = window.location.hash; // Lấy hash từ URL
        // Active tab dựa trên hash trong URL
        $('#nav_tab_list a[href="' + activeTab + '"]').tab('show');
    } else {
        // Nếu không có hash, chọn tab đầu tiên
        $('#nav_tab_list a:first').tab('show');
    }

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

    $('#course_ids').select2({
        placeholder: "-- Chọn khóa học --",
        allowClear: true,
        tags: true
    });

    $('#course_ids_teacher').select2({
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
    // $('#search-teacher-other').select2({
    //     placeholder: "Nhập tên giáo viên",
    //     allowClear: true,
    //     tags: true
    // });

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

    // // Khởi tạo Choices.js cho select
    var choiceSelectOrgUser = new Choices('#search-teacher-other', {
        removeItemButton: true,
        searchEnabled: true,
        searchChoices: true,
        itemSelectText: '',
        placeholder: true,
        placeholderValue: 'Nhập tên giáo viên'
    });

    var selectedTeachers = [];
    var typingTimer;
    var doneTypingInterval = 1000;

    $('#course-teacher-enrol').on('change', function() {
        choiceSelectOrgUser.clearStore();
        $('.table-course-class-chosen-teacher tbody').empty();
        selectedTeachers = []
        if($(this).val() != ''){
            $('.search-teacher-other-box').show();
        }else{
            $('.search-teacher-other-box').hide();
        }
        
    });
    
    $("#chooseTeacherModal .choices__input").on("input", function() {
        clearTimeout(typingTimer);
        var searchTerm = $(this).val();
        if (searchTerm.length >= 2) {
            searchTeacherResponse(searchTerm);
        }
    });
    
    function searchTeacherResponse(searchTerm) {
        $.ajax({
            url: '/api/class/teacher/search',
            type: 'GET',
            data: {
                'searchTerm': searchTerm
            },
            success: function(response) {
                updateChoices(response);
            },
            error: function(err) {
                console.log(err);
            }
        });
    }
    
    function updateChoices(teachers) {
        choiceSelectOrgUser.clearChoices();
    
        if (teachers.length) {
            choiceSelectOrgUser.setChoices(
                teachers.map((teacher) => ({
                    value: teacher.id,
                    label: teacher.name + ' (' + teacher.email + ')',
                })),
                'value',
                'label',
                true
            );
        } else {
            choiceSelectOrgUser.setChoices([
                { value: '', label: 'Không tìm thấy kết quả', disabled: true },
            ]);
        }
    }

    $('#search-teacher-other').on('change', function() {
        var teacherId = $(this).val();
        var teacherLabel = $(this).find('option:selected').text();
    
        var name = teacherLabel.split('(')[0].trim();
        var email = teacherLabel.match(/\((.*?)\)/)?.[1];
    
        if (teacherId && !selectedTeachers.includes(teacherId)) {
            selectedTeachers.push(teacherId);
    
            var rowCount = $('.chosen-teachers tr').length + 1;
            var newRow = `
                <tr id="teacher-other-${teacherId}">
                    <td class="text-center">${rowCount}</td>
                    <td class="text-center">${name}</td>
                    <td class="text-center">${email}</td>
                    <td class="text-center">
                        <input type="checkbox" class="checkbox-chosen-teachers" value="${teacherId}">
                    </td>
                </tr>
            `;
            $('.chosen-teachers').append(newRow);
        }
    });
    
    $('.add-teacher').on('click', function() {
        var selectedTeacherIds = [];
        
        // Lấy tất cả các checkbox được chọn
        $('.checkbox-chosen-teachers:checked').each(function() {
            selectedTeacherIds.push($(this).val());
        });
    
        if (selectedTeacherIds.length === 0) {
            alert('Vui lòng chọn ít nhất một giáo viên trước khi thêm!');
            return; // Dừng thực hiện nếu không có giáo viên nào được chọn
        }
        // Hiển thị danh sách ID trong console để kiểm tra
        console.log("Selected Teacher IDs: " + selectedTeacherIds);
    
        // Gửi dữ liệu lên server qua AJAX
        $.ajax({
            url: '/api/class/teacher/enrol', // Đường dẫn đến endpoint xử lý trên server
            type: 'POST', // Phương thức gửi yêu cầu
            data: {
                teacherIds: selectedTeacherIds,
                courseId: $('#course-teacher-enrol').val(),
                classId: $('#classIdTeacher').val()
            },
            success: function(response) {
                // Xử lý khi gửi thành công
                console.log('Gửi thành công:', response);
                if(response.status){
                    location.reload();
                }else{
                    alert(response.message);
                }
            },
            error: function(err) {
                // Xử lý khi có lỗi xảy ra
                console.error('Có lỗi xảy ra:', err);
                // alert('Gửi thất bại. Vui lòng thử lại.');
            }
        });
    });
});