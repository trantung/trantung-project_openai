$(document).ready(function() {
    let dragCounter = 0;
    let dropZoneCounter = 0;
    let leaveTimer;
    // let uploadedImages = [];
    let uploadedFiles = [];
    // let selectedIndexes = [];

    let newStorageLevel = false;

    function resetStorageLevelFirstTime(){
        if(!newStorageLevel){
            for (let i = 1; i <= 10; i++) { // 10 là số level tối đa, bạn có thể thay đổi
                localStorage.removeItem(`level-${i}`);
            }
            newStorageLevel = true;
        }
    }

    resetStorageLevelFirstTime();

    $('.course-detail-page .tree-item').click(function() {
        // Lấy giá trị type từ 'data-item-type'
        var itemType = $(this).data('item-type');

        // Ẩn tất cả các form
        $('[id^="form-"]').addClass('hide');

        // Hiển thị form tương ứng
        $('#form-' + itemType + (itemType === 'folder' ? '-info' : '-setting')).removeClass('hide');
    });

    $('.above-block').on('click', '.btn-group .fa-plus', function(e) {
        e.stopPropagation(); // Ngăn không cho sự kiện click bong bóng ra ngoài
        // Đóng tất cả các dropdown đang mở trừ cái hiện tại
        $(".dropdown-menu").not($(this).siblings(".dropdown-menu")).removeClass("show");
        $(".fa-plus").not(this).removeClass("show").attr("aria-expanded", "false");

        // Toggle dropdown hiện tại
        $(this).siblings(".dropdown-menu").toggleClass("show");

        // Thay đổi trạng thái của icon fa-plus
        $(this).attr('aria-expanded', function(_, attr){
            return attr === 'true' ? 'false' : 'true';
        });

        // Toggle class 'show' cho chính nút này
        $(this).toggleClass("show");
    });

    let courseId = $('#courseId').val();
    let courseMoodleId = $('#courseMoodleId').val();

    loadSectionData(courseId);

    $('#add_product').on('click', function(e) {
        e.preventDefault();
        const dataType = $(this).data('type');
        if(dataType == 'section'){
            const couseId = $(this).data('course-id');
            createSection(couseId, 1)
        }
    });
    
    function loadSectionData(courseId, clickedCategoryId = null) {
        $.ajax({
            url: '/api/lms/section/list',
            method: 'POST',
            data: {
                course_id: courseId
            },
            success: function(response) {
                localStorage.setItem('currentUser', $('#currentUserEmail').val());
                renderSectionList(response, clickedCategoryId);
            },
            error: function(error) {
                console.error('Lỗi khi tải danh sách category:', error);
            }
        });
    }

    function autoClickLevels() {
        const maxLevel = 10; // Số level tối đa, có thể thay đổi
        const levels = [];
    
        // Lấy dữ liệu từ localStorage
        for (let i = 1; i <= maxLevel; i++) {
            const categoryId = localStorage.getItem(`level-${i}`);
            if (categoryId) {
                levels.push({ level: i, categoryId: parseInt(categoryId, 10) });
            } else {
                break; // Dừng khi không có thêm level nào
            }
        }
    
        // Click từ level nhỏ nhất đến lớn nhất
        function clickLevel(index) {
            if (index >= levels.length) return; // Dừng nếu đã xử lý hết các level
    
            const { level, categoryId } = levels[index];
            const $element = $(`.tree-item[data-level="${level}"][data-category-id="${categoryId}"]`);
    
            if ($element.length) {
                $element.trigger('click'); // Kích hoạt click
                setTimeout(() => clickLevel(index + 1), 200); // Delay để đảm bảo UI cập nhật
            } else {
                console.warn(`Không tìm thấy phần tử cho level ${level} với category ID ${categoryId}`);
            }
        }
    
        // Bắt đầu click từ level 1
        if (levels.length > 0) {
            clickLevel(0);
        }else{
            $('.view-product-children.active').click();
        }
    }

    function renderSectionList(categories, clickedCategoryId = null) {
        let html = '';
        categories.forEach(function(item, index) {
            let classView = 'view-course-children';
            if(item.moodle_type == 'section'){
                classView = 'view-product-children';
            }
            let mainClass = (index === 0 || item.id === clickedCategoryId) ? 'active' : '';
            let disabled = item.visible == 1 ? '' : 'disabled';
            var level = item.level;
            html += `
                <div class="tree-item-cover cover-category-${item.id} ui-sortable-handle">
                    <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="${item.id}">
                    <div class="level-${level} tree-item ${classView} ${disabled} ${mainClass}" data-level=${level} data-type="${item.moodle_type}" data-category-id="${item.id}" style="width: calc(100% - 4%);">
                        <div class="left-item">
                            <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
                            <i class="fa btn-expand-collapse fa-chevron-right btn-collapse" title="Mở rộng" data-category-id="${item.id}" aria-hidden="true"></i>
                            <i class="fa fa-folder-open fa-folder-color expand-folder" aria-hidden="true" data-type="course_sections" data-path="${item.id}" data-child-level="${index + 1}" data-category-id="${item.moodle_id}"></i>
                            <div class="product-name-w100 product-name-${item.id}" data-category-id="${item.id}">${item.moodle_name}</div>
                        </div>
                        <div class="right-item">
                            <i class="icon_is_lesson is_lesson_${item.moodle_id} fa-bookmark fa" title="${item.moodle_name}" data-id="${item.id}" data-category-id="${item.moodle_id}" aria-hidden="true"></i>
                            <div class="btn-group">
                                <i class="fa fa-plus icon-dropdown-menu" title="Thêm thư mục con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
                                <div class="dropdown-menu">
                                    <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại thư mục</b></h5>
                                    <div class="dropdown-item add-child-quiz" data-type="quiz" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-list-ol" aria-hidden="true"></i>
                                        <div>Quiz</div>
                                    </div>
                                    <div class="dropdown-item add-child-video" data-type="video" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                                        <div>Video</div>
                                    </div>
                                    <div class="dropdown-item add-child-resource" data-cmid="" data-type="resource" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-file" aria-hidden="true"></i>
                                        <div>File</div>
                                    </div>
                                    <div class="dropdown-item add-interactive-book" data-type="interactive_book" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-list-ol" aria-hidden="true"></i>
                                        <div>Interactive Book</div>
                                    </div>
                                    <div class="dropdown-item add-child-assign" data-cmid="" data-type="assign" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-file" aria-hidden="true"></i>
                                        <div>Bài tập</div>
                                    </div>
                                    <div class="dropdown-item add-child-url" data-cmid="" data-type="url" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-link" aria-hidden="true"></i>
                                        <div>URL</div>
                                    </div>
                                    <div class="dropdown-item add-child-scorm" data-cmid="" data-type="scorm" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                        <div>Scorm</div>
                                    </div>
                                </div>
                            </div>
                            <i class="fa fa-trash icon-action-delete" title="Xóa" aria-hidden="true" data-type="course_sections" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="${courseMoodleId}" data-parent-id="${item.parent_id}" data-section-id="${item.moodle_id}" data-id="${item.id}" data-name="${item.moodle_name}"></i>
                        </div>
                    </div>
                    <div data-current="${item.id}"
                        class="tree-item-contain contain-category-${item.id} tree-item-move ui-sortable"
                        style="width: calc(100% - 4%); display: none;">
                    </div>
                </div>`;
        });

        $('.tree-items').html(html);

        $('.above-block').off('click', '.add-child-quiz');

        $('.above-block').on('click', '.add-child-quiz', function(e) {
            e.stopPropagation(); // Ngừng sự kiện "bong bóng"
            const sectionId = $(this).data('category-id');
            const parentId = $(this).data('parent-id');
            const courseId = $(this).data('course-id');
            const level = $(this).data('child-level');
            const currentLevel = $(this).data('current-level');

            for (let i = currentLevel; i <= 10; i++) {
                localStorage.removeItem(`level-${i}`);
            }
    
            localStorage.setItem(`level-${currentLevel}`, parentId);
            // // console.log("Thêm khóa học mới vào danh mục với ID: " + sectionId + ", parent: " + parentId);

            // addActivityToSection(sectionId, parentId, courseId, 'quiz');
            showPopupQuestionType(sectionId, parentId, courseId, 'quiz', level);
        });

        $('.above-block').off('click', '.add-child-url');
        $('.above-block').on('click', '.add-child-url', function(e) {
            e.stopPropagation();
            const sectionId = $(this).data('category-id');
            const parentId = $(this).data('parent-id');
            const courseId = $(this).data('course-id');
            const level = $(this).data('child-level');
            const currentLevel = $(this).data('current-level');

            for (let i = currentLevel; i <= 10; i++) {
                localStorage.removeItem(`level-${i}`);
            }
    
            localStorage.setItem(`level-${currentLevel}`, parentId);
            
            addActivityToSection(sectionId, parentId, courseId, 'url', level);
        });

        $('.above-block').off('click', '.add-child-resource');
        $('.above-block').on('click', '.add-child-resource', function(e) {
            e.stopPropagation();
            const sectionId = $(this).data('category-id');
            const parentId = $(this).data('parent-id');
            const courseId = $(this).data('course-id');
            const level = $(this).data('child-level');
            const currentLevel = $(this).data('current-level');
            
            for (let i = currentLevel; i <= 10; i++) {
                localStorage.removeItem(`level-${i}`);
            }
    
            localStorage.setItem(`level-${currentLevel}`, parentId);
            
            addActivityToSection(sectionId, parentId, courseId, 'resource', level);
        });

        $('.above-block').off('click', '.add-child-assign');
        $('.above-block').on('click', '.add-child-assign', function(e) {
            e.stopPropagation();
            const sectionId = $(this).data('category-id');
            const parentId = $(this).data('parent-id');
            const courseId = $(this).data('course-id');
            const level = $(this).data('child-level');
            const currentLevel = $(this).data('current-level');

            for (let i = currentLevel; i <= 10; i++) {
                localStorage.removeItem(`level-${i}`);
            }
    
            localStorage.setItem(`level-${currentLevel}`, parentId);
            
            addActivityToSection(sectionId, parentId, courseId, 'assign', level);
        });

        // $('.view-product-children.active').click();

        autoClickLevels();

        // if (clickedCategoryId) {
        //     $('.view-product-children.active').removeClass('active');
        //     $(`.cover-category-${clickedCategoryId} .view-product-children`).addClass('active');
        //     $(`.cover-category-${clickedCategoryId} .view-product-children`).click();
        // }

        // Tự động click vào item có class active (vừa được chọn hoặc là item đầu tiên)
        // $('.view-product-children.active').click();
    }

    function addActivityToSection(sectionId, parentId, courseId, type, level = null, questionName = null, questionType = null, questionId = null) {
        const clickedCategoryId = parentId;
        const currentUser = localStorage.getItem('currentUser');
        // console.log("Thêm khóa học vào danh mục " + categoryId + " ở parent " + parentId);
        $.ajax({
            url: "/api/lms/activity/create",
            type: "POST",
            data: {
                parent_id: parentId,
                section_id: sectionId,
                course_id: courseId,
                type: type,
                currentUser: currentUser,
                questionName: questionName,
                questionType: questionType,
                questionId: questionId,
                level: level
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.error) {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                    $('.message-product').removeClass('hidden');
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                }else{
                    loadSectionData(courseId, clickedCategoryId);
                }
                $('#popup_question_type').modal('hide');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    function showPopupQuestionType(sectionId, parentId, courseId, type, level) {
        const currentUser = localStorage.getItem('currentUser');
        $.ajax({
            url: "/api/ielts/exam/list",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    const parentElement = $('#popup_question_type .popup_content');
                    parentElement.empty(); // Xóa nội dung cũ
                    let i = 1;
                    // *** Bước 1: Hiển thị thông tin bài kiểm tra và các type dưới dạng tag ***
                    const examData = response.data;
                    // Lấy danh sách các type từ rounds
                    const types = examData.rounds.map(round => round.name);
                    // Tạo HTML cho thông tin bài kiểm tra và các type
                    const examInfoHtml = `
                        <div class="popup_content_box shadow">
                            <div class="text_question_type mb-2">
                                ${i}. ${examData.name}
                            </div>
                            <ul class="tags">
                                ${types.map(type => `<li><a href="#" class="tag">${type}</a></li>`).join('')}
                            </ul>
                            <div class="button_question_type">
                                <button class="btn btn-secondary" data-activity-type="${type}" data-course-id="${courseId}" data-category-id="${sectionId}" data-parent-id="${parentId}" data-question-name="${examData.name}" data-question-type="exam" data-question-id=${examData.idMockContest}>Select</button>
                            </div>
                        </div>
                    `;
                    parentElement.append(examInfoHtml);
                    // *** Bước 2: Lặp qua các rounds và hiển thị thông tin từng bài kiểm tra ***
                    examData.rounds.forEach(function(round, index) {
                        const roundName = round.name;
                        const roundType = round.type;
                        
                        // Lặp qua listBaikiemtra để hiển thị từng bài kiểm tra của round
                        round.listBaikiemtra.forEach(function(testItem) {
                            const testName = testItem.name;
                            const idBaikiemtra = testItem.idBaikiemtra;
                            const lowerCaseRoundName = roundName.toLowerCase();
                            // Tạo phần HTML cho từng bài kiểm tra trong round
                            const testHtml = `
                                <div class="popup_content_box shadow">
                                    <div class="text_question_type mb-2">
                                        ${i + 1}. ${testName}
                                    </div>
                                    <ul class="tags">
                                        <li><a href="#" class="tag">${roundName}</a></li>
                                    </ul>
                                    <div class="button_question_type">
                                        <button class="btn btn-secondary" data-level="${level}" data-activity-type="${type}" data-course-id="${courseId}" data-category-id="${sectionId}" data-parent-id="${parentId}" data-question-name="${testName}" data-question-type=${lowerCaseRoundName} data-question-id=${idBaikiemtra}>Select</button>
                                    </div>
                                </div>
                            `;
                
                            // Append HTML của từng bài kiểm tra vào popup content
                            parentElement.append(testHtml);
                            i++; // Tăng chỉ số i để hiển thị đúng thứ tự
                        });
                    });
                }
                // Hiển thị popup
                $('#popup_question_type').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    
    $(document).on('click', '.tags a', function(event) {
        event.preventDefault(); // Ngăn hành vi mặc định (click và chuyển hướng)
    });

    $(document).on('click', '.button_question_type .btn-secondary', function() {
        // Lấy giá trị từ các thuộc tính data- của nút được click
        const questionName = $(this).data('question-name');
        const questionType = $(this).data('question-type');
        const questionId = $(this).data('question-id');

        const sectionId = $(this).data('category-id');
        const parentId = $(this).data('parent-id');
        const courseId = $(this).data('course-id');
        const activityType = $(this).data('activity-type');

        const level = $(this).data('level');

        // Xử lý sự kiện, ví dụ hiển thị thông báo với dữ liệu đã chọn
        console.log(`Question Name: ${questionName}, Question Type: ${questionType}, Question ID: ${questionId}`);
        addActivityToSection(sectionId, parentId, courseId, activityType, level, questionName, questionType, questionId);
        // Thực hiện các hành động khác, ví dụ gửi yêu cầu AJAX hoặc cập nhật giao diện
    });

    function createSection(couseId, level){
        const currentUser = localStorage.getItem('currentUser');
        $.ajax({
            url: "/api/lms/section/create",
            type: "POST",
            data: {
                couseId: couseId,
                currentUser: currentUser,
                level: level
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.error) {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                    $('.message-product').removeClass('hidden');
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                }else{
                    loadSectionData(couseId);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    // Bắt sự kiện click trên toàn bộ document
    $(document).on("click", function() {
        // Đóng tất cả các dropdown khi click ra ngoài
        $(".dropdown-menu").removeClass("show");
        $(".fa-plus").removeClass("show").attr("aria-expanded", "false");
    });

    // Ngăn không cho sự kiện click trên dropdown lan ra ngoài
    $(".dropdown-menu").on("click", function(e) {
        e.stopPropagation(); // Ngăn sự kiện lan ra document
    });

    $('.above-block').on('click', '.tree-item', function(e) {
        // console.log(222)
        // Ngăn chặn hành vi mặc định và sự kiện click bong bóng
        // e.preventDefault();
        e.stopPropagation();
        // $(".dropdown-menu").removeClass("show");
        const $addSubItemButton = $(e.target).closest('.fa-plus');
        const $viewItemButton = $(e.target).closest('.fa-sitemap');

        const $trashItemButton = $(e.target).closest('.fa-trash');
        if ($addSubItemButton.length) {
            e.preventDefault();
            return;
        }

        if ($trashItemButton.length) {
            e.preventDefault();
            return;
        }

        if ($viewItemButton.length) {
            return;
        }

        var $this = $(this);
        var dataType = $this.data('type');
        var categoryId = $this.data('category-id');
        var $currentBlock = $this.closest('.above-block');
        var currentLevel = $this.data('level');

        for (let i = currentLevel + 1; i <= 10; i++) { // 10 là số level tối đa, bạn có thể thay đổi
            localStorage.removeItem(`level-${i}`);
        }

        // **2. Lưu lại `localStorage` cho cấp độ hiện tại**
        localStorage.setItem(`level-${currentLevel}`, categoryId);

        // Xóa phần đóng các tree-item khác

        // Toggle trạng thái mở/đóng của item hiện tại
        var $containElement = $currentBlock.find('.contain-category-' + categoryId);
        $containElement.slideToggle();

        // Đổi icon collapse/expand cho item hiện tại
        var $icon = $this.find('.btn-collapse');
        $icon.toggleClass('fa-chevron-down fa-chevron-right');

        // Thay đổi trạng thái active cho item hiện tại, chỉ trong khối hiện tại
        // $currentBlock.find('.tree-item').removeClass('item-active');
        // $this.addClass('item-active');
        $currentBlock.find('.tree-item').removeClass('active');
        $this.addClass('active');

        if(dataType == 'section'){
            $('#setting_quiz_list_question').hide();
            $('#setting_course_sections').show();
            $('#setting_dynamic_form').hide();
            $('#form-folder-info').removeClass('hide');
            $('#form-quiz-setting').addClass('hide');
            $('.section-quiz-part').show();
            $('#wrapper-dynamic-url-form').empty();
            $('#wrapper-dynamic-resource-form').empty();
            $('#wrapper-dynamic-assign-form').empty();
            getDetailSection(categoryId);
        }

        if(dataType == 'quiz'){
            $('#setting_quiz_list_question').show();
            $('#setting_course_sections').show();
            $('#setting_dynamic_form').hide();
            $('#form-folder-info').addClass('hide');
            $('#form-quiz-setting').removeClass('hide');
            $('.section-quiz-part').show();
            $('#wrapper-dynamic-url-form').empty();
            $('#wrapper-dynamic-resource-form').empty();
            getDetailActivity(categoryId, dataType);
        }

        if(dataType == 'url'){
            $('#setting_quiz_list_question').hide();
            $('#setting_course_sections').hide();
            $('#setting_dynamic_form').show();
            $('#form-folder-info').addClass('hide');
            $('#form-url-setting').addClass('hide');
            $('.section-quiz-part').hide();
            $('#wrapper-dynamic-resource-form').empty();
            $('#wrapper-dynamic-assign-form').empty();
            getDetailActivity(categoryId, dataType);
        }

        if(dataType == 'resource'){
            $('#setting_quiz_list_question').hide();
            $('#setting_course_sections').hide();
            $('#setting_dynamic_form').show();
            $('#form-folder-info').addClass('hide');
            $('#form-url-setting').addClass('hide');
            $('.section-quiz-part').hide();
            $('#wrapper-dynamic-url-form').empty();
            $('#wrapper-dynamic-assign-form').empty();
            getDetailActivity(categoryId, dataType);
        }

        if(dataType == 'assign'){
            $('#setting_quiz_list_question').hide();
            $('#setting_course_sections').hide();
            $('#setting_dynamic_form').show();
            $('#form-folder-info').addClass('hide');
            $('#form-url-setting').addClass('hide');
            $('.section-quiz-part').hide();
            $('#wrapper-dynamic-url-form').empty();
            $('#wrapper-dynamic-resource-form').empty();
            getDetailActivity(categoryId, dataType);
        }
        // Toggle class hide cho #info_product
        $('#info_product').removeClass('hide');

        // Đảm bảo #info_course luôn bị ẩn
        $('#info_course').addClass('hide');
    });

    function getDetailSection(section_id){
        $.ajax({
            url: "/api/lms/section/detail",
            type: "POST",
            data: {
                section_id: section_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                renderSectionDetail(response.data, response.main, section_id);
                renderHtmlRightPart(response.data, section_id);
                renderFormSection(response.main);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    function getDetailActivity(activity_id, activity_type){
        $.ajax({
            url: "/api/lms/activity/detail",
            type: "POST",
            data: {
                activity_id: activity_id,
                activity_type: activity_type
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(activity_type == 'url'){
                    uploadedFiles = []
                    renderTemplateUrl(response.data.cm, response.selectedParentId, response.sectionData, response.str_availability_cmid);
                }

                if(activity_type == 'quiz'){
                    uploadedFiles = []
                    renderFormQuiz(response.data.cm, response.selectedParentId, response.sectionData, response.str_availability_cmid)
                }

                if(activity_type == 'resource'){
                    uploadedFiles = []
                    renderTemplateResource(response.data.cm, response.selectedParentId, response.sectionData, response.str_availability_cmid)
                }

                if(activity_type == 'assign'){
                    uploadedFiles = []
                    renderTemplateAssign(response.data.cm, response.selectedParentId, response.sectionData, response.str_availability_cmid)
                }
                
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    function renderSectionDetail(response, mainData, parent_id) {
        if (response.length > 0) {
            var parentElement = $('.contain-category-' + parent_id); // Lấy phần tử cha
            parentElement.empty(); // Xóa các phần tử con cũ
    
            response.forEach(function(item, index) {
                // Kiểm tra loại item (course hoặc không)
                let icon = 'fa-folder-open fa-folder-color expand-folder'; // Chọn icon
                let disabled = item.visible == 1 ? '' : 'disabled';
                var rightItemHtml = '';
                var level = item.level;
                if(item.moodle_type === 'quiz'){
                    icon = 'fa-list-ol expand-quiz';
                }
                if(item.moodle_type === 'url'){
                    icon = 'fa-link expand-url';
                }
                if(item.moodle_type === 'resource'){
                    icon = 'fa-file expand-resource';
                }
                if(item.moodle_type === 'assign'){
                    icon = 'fa-file expand-assign';
                }
    
                if (item.moodle_type === 'section') {
                    // Nếu là course, thêm icon sitemap
                    rightItemHtml = `
                        <i class="icon_is_lesson is_lesson_${item.moodle_id} fa-bookmark fa" title="${item.moodle_name}" data-id="${item.id}" data-category-id="${item.moodle_id}" aria-hidden="true"></i>
                        <div class="btn-group">
                            <i class="fa fa-plus icon-dropdown-menu" title="Thêm thư mục con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
                            <div class="dropdown-menu">
                                <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại thư mục</b></h5>
                                <div class="dropdown-item add-child-folder" data-type="course_sections" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-folder-open fa-folder-color" aria-hidden="true"></i>
                                    <div>Thư mục</div>
                                </div>
                                <div class="dropdown-item add-child-quiz" data-type="${item.moodle_type}" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                    <div>Quiz</div>
                                </div>
                                <div class="dropdown-item add-child-video" data-type="${item.moodle_type}" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                                    <div>Video</div>
                                </div>
                                <div class="dropdown-item add-child-resource" data-cmid="" data-type="${item.moodle_type}" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-file" aria-hidden="true"></i>
                                    <div>File</div>
                                </div>
                                <div class="dropdown-item add-interactive-book" data-type="${item.moodle_type}" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                    <div>Interactive Book</div>
                                </div>
                                <div class="dropdown-item add-child-assign" data-cmid="" data-type="${item.moodle_type}" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-file" aria-hidden="true"></i>
                                    <div>Bài tập</div>
                                </div>
                                <div class="dropdown-item add-child-url" data-cmid="" data-type="${item.moodle_type}" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                    <div>URL</div>
                                </div>
                                <div class="dropdown-item add-child-scorm" data-cmid="" data-type="${item.moodle_type}" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                    <div>Scorm</div>
                                </div>
                            </div>
                        </div>
                        <i class="fa fa-trash icon-action-delete" title="Xóa" aria-hidden="true" data-type="course_sections" data-child-level="${level + 1}" data-current-level="${level}" data-course-id="${courseMoodleId}" data-parent-id="${item.parent_id}" data-section-id="${item.moodle_id}" data-id="${item.id}" data-name="${item.moodle_name}"></i>
                    `;
                } else {
                    // Nếu không phải là section, thêm div btn-group
                    rightItemHtml = `
                        <div class="btn-group">
                            <i class="fa fa-play icon-${item.moodle_type}-action-play-temp play-temp" data-type="${item.moodle_type}" data-href="#" title="Làm bài" aria-hidden="true"></i>
                            <i class="fa fa-trash icon-${item.moodle_type}-action-delete" title="Xoá" aria-hidden="true" data-category-id="${item.parent_id}" data-parent-id="${item.parent_id}" data-instance="45960" data-cmid="${item.moodle_id}" data-type="quiz" data-child-level="${index + 1}" data-name="${item.moodle_name}"></i>
                        </div>
                    `;
                }
    
                // Tạo HTML cho từng item
                let productClass = 'product-name-w100 w-100';
                if(item.moodle_type === 'quiz') {
                    productClass = 'product-name-w100 w-100 expand-quiz';
                }
                if(item.moodle_type === 'url') {
                    productClass = 'product-name-w100 w-100 expand-url';
                }
                if(item.moodle_type === 'resource') {
                    productClass = 'product-name-w100 w-100 expand-resource';
                }
                
                var newItem = `
                    <div class="tree-item-cover cover-category-${item.id} ui-sortable-handle">
                        <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="${item.id}">
                        <div class="level-${level} tree-item view-course-children ${disabled} view-course-children-${item.id}" data-level=${level} data-type="${item.moodle_type}" data-category-id="${item.id}" style="width: calc(100% - 4%);">
                            <div class="left-item">
                                <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
                                <i class="fa ${icon}" aria-hidden="true" data-type="course_sections" data-path="${item.id}" data-child-level="${level + 1}" data-category-id="${item.moodle_id}"></i>
                                <div class="${productClass} box-name product-name-${item.id}" data-parent="${item.parent_id}" data-cmid="${item.moodle_id}" data-type="${item.moodle_type}" data-category-id="${item.id}">${item.moodle_name}</div>
                            </div>
                            <div class="right-item">
                                ${rightItemHtml}
                            </div>
                        </div>
                    </div>
                `;
                parentElement.append(newItem); // Thêm item con vào
            });
        }

        $('.btn-save').attr('data-type', mainData.moodle_type);
        $('.btn-save').attr('data-item-id', mainData.id);
        $('.btn-save').attr('data-course-id', courseId);
        $('.btn-save').attr('data-parent-id', mainData.parent_id);
    }

    function renderHtmlRightPart(response, parent_id){
        var parentElement = $('.left-side-content'); // Lấy phần tử cha
        parentElement.empty(); // Xóa các phần tử con cũ
        if (response.length > 0) {
            response.forEach(function(item, index) {
                let icon = ''; // Chọn icon
                let blur = '';
                let dimmed = '';
                let completionMark = '';
    
                if (item.moodle_type === 'course') {
                    icon = 'fa-book';
                    blur = 'blur';
                }

                if (item.moodle_type === 'quiz') {
                    icon = 'fa-list-ol';
                    if (item.availabilityinfo != null) {
                        dimmed = 'dimmed';
                        completionMark = item.availabilityinfo;
                    }
                }

                if (item.moodle_type === 'resource') {
                    icon = 'fa-file';
                    if (item.availabilityinfo != null) {
                        dimmed = 'dimmed';
                        completionMark = item.availabilityinfo;
                    }
                }

                if (item.moodle_type === 'assign') {
                    icon = 'fa-file';
                    if (item.availabilityinfo != null) {
                        dimmed = 'dimmed';
                        completionMark = item.availabilityinfo;
                    }
                }

                if (item.moodle_type === 'url') {
                    icon = 'fa-link';
                    if (item.availabilityinfo != null) {
                        dimmed = 'dimmed';
                        completionMark = item.availabilityinfo;
                    }
                }
                
                if (item.moodle_type === 'section') {
                    icon = 'fa-folder-open fa-folder-color';
                }

                if (item.moodle_type === 'category') {
                    icon = 'fa-archive';
                }

                // Tạo HTML cho từng item
                var newItem = `
                    <div class="normal-item ${dimmed}" style="align-items: flex-start;">
                        <i class="mt-1 fa ${icon}" aria-hidden="true"></i>
                        <div class="${blur}">
                            ${item.moodle_name} <br>
                            ${completionMark}
                        </div>
                    </div>
                `;
                parentElement.append(newItem); // Thêm item con vào
            });
        }
    }

    function renderFormSection(response) {
        var parentElement = $('#form-folder-info');
        parentElement.empty();
    
        // Tạo nội dung HTML mới
        var newItem = `
            <div class="folder-name">
                <h4>${response.moodle_name}</h4>
            </div>
            <div class="mb-2">
                <label>Tên thư mục <span class="asterisk">(*)</span></label>
                <input type="text" id="section_name" name="section_name" value="${response.moodle_name || ''}" placeholder="Nhập tên sản phẩm">
            </div>
            <div class="mb-2">
                <label>Tóm tắt</label>
                <textarea id="section_summary" name="section_summary" placeholder="Nhập tóm tắt" rows="3">${response.summary || ''}</textarea>
            </div>
            <div class="mb-2">
                <label>Trạng thái</label>
                <select name="section_status" id="section_status">
                    <option ${response.visible == 1 ? 'selected' : ''} value="1">Hiện</option>
                    <option ${response.visible == 0 ? 'selected' : ''} value="0">Ẩn</option>
                </select>
            </div>
            <div class="mb-2">
                <label>Người tạo</label>
                <input type="text" id="section_created_by" value="${response.creator || ''}" disabled="">
            </div>
            <div class="mb-2">
                <label>Ngày tạo</label>
                <input type="text" id="section_created_at" value="${response.created_at}" disabled="">
            </div>
            <div class="mb-2">
                <label>Người cập nhật</label>
                <input type="text" id="section_updated_by" value="${response.modifier || ''}" disabled="">
            </div>
            <div class="mb-2">
                <label>Ngày cập nhật</label>
                <input type="text" id="section_updated_at" value="${response.updated_at}" disabled="">
            </div>
            <div>
                <input type="hidden" id="hidden_instance" name="hidden_instance" value="${response.moodle_id}">
                <input type="hidden" id="hidden_type" name="hidden_type" value="course_sections">
                <input type="hidden" id="hidden_path" name="hidden_path" value="${response.moodle_id}">
            </div>
            <div class="mt-4 hide" style="text-align: center">
                <a href="#" class="btn btn-primary btn-save-section">Cập nhật</a>
            </div>
        `;
    
        // Thêm nội dung vào parentElement
        parentElement.append(newItem); 

        // $('.btn-save-section').on('click', function(event) {
        //     event.preventDefault();
        //     updateSection(response.id); // Gọi hàm updateSection để xử lý cập nhật
        // });
    }

    function updateSection(id) {
        const currentUser = localStorage.getItem('currentUser');
        var productData = {
            id: id,
            section_name: $('#section_name').val(),
            section_status: $('#section_status').val(),
            section_summary: $('#section_summary').val(),
            currentUser: currentUser
        };
    
        $.ajax({
            url: '/api/lms/section/update',
            method: 'POST',
            data: productData,
            success: function(response) {
                loadSectionData(courseId, id);
                if (response.success) {
                    $('.message-product .alert').addClass('alert-success');
                    $('.message-product .alert').removeClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.success);
                } else {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                }
                $('.message-product').removeClass('hidden');
                $('html, body').animate({ scrollTop: 0 }, 'fast');
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }

    function renderFormQuiz(response, selectedParentId, sectionData, str_availability_cmid) {
        console.log(response)
        var parentElement = $('#form-quiz-setting');
        parentElement.empty();

        var parentSelectHTML = '';
        parentSelectHTML = `
            <div class="row mb-2">
                <div class="col-5">
                    <h5>Thư mục cha</h5>
                </div>
                <div class="col-7 quiz-parent-box">
                    <select class="w-100" name="quiz-parent" id="quiz-parent">
                        ${sectionData.map(section => {
                            // Kiểm tra nếu section id bằng với selectedParentId thì thêm selected
                            const isSelected = section.id === selectedParentId ? 'selected' : '';
                            return `<option value="${section.id}" ${isSelected}>${section.moodle_name}</option>`;
                        }).join('')}
                    </select>
                </div>
            </div>
        `;
    
        // Tạo nội dung HTML mới
        var newItem = `
            <div class="quiz-settings">
                <h4 class="text-center">${response.moodle_name}</h4>
                <div class="row">
                    <div class="col-12">
                        <h5><b>Thông tin chung</b></h5>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Tên bài quiz</h5>
                    </div>
                    <div class="col-7"><input class="w-100" type="text" id="quiz_name" name="quiz_name" value="${response.moodle_name || ''}" placeholder="Nhập tên sản phẩm"></div>
                </div>
                ${parentSelectHTML}
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Loại tài nguyên</h5>
                    </div>
                    <div class="col-7"><select class="w-100" disabled="" name="quiz_type" id="quiz_type">
                            <option selected="" value="quiz">Quiz</option>
                        </select> </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Loại học liệu</h5>
                    </div>
                    <div class="col-7">
                        <select class="w-100" name="quiz_settings_type" id="quiz_settings_type">
                            <option selected="" value="1">Pre Class</option>
                            <option value="2">In Class</option>
                            <option value="3">Post Class</option>
                            <option value="4">Mock Test</option>
                            <option value="5">Loại học liệu mới</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Trạng thái</h5>
                    </div>
                    <div class="col-7">
                        <select class="w-100" name="quiz_status" id="quiz_status">
                            <option ${response.visible == 0 ? 'selected' : ''} value="0">Ẩn</option>
                            <option ${response.visible == 1 ? 'selected' : ''} value="1">Hiện</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <h5><b>Điểm</b></h5>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Thang điểm</h5>
                    </div>
                    <div class="col-7"><input class="i-grade-quiz w-100" min="0" type="number" name="gradeQuiz" value="${response.grade || '0'}"></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Số lần làm bài</h5>
                    </div>
                    <div class="col-7">
                        <select class="w-100" name="attempts" id="attempts">
                            <option ${response.attempts == 0 ? 'selected' : ''} value="0">Không giới hạn</option>
                            <option ${response.attempts == 1 ? 'selected' : ''} value="1">1</option>
                            <option ${response.attempts == 2 ? 'selected' : ''} value="2">2</option>
                            <option ${response.attempts == 3 ? 'selected' : ''} value="3">3</option>
                            <option ${response.attempts == 4 ? 'selected' : ''} value="4">4</option>
                            <option ${response.attempts == 5 ? 'selected' : ''} value="5">5</option>
                            <option ${response.attempts == 6 ? 'selected' : ''} value="6">6</option>
                            <option ${response.attempts == 7 ? 'selected' : ''} value="7">7</option>
                            <option ${response.attempts == 8 ? 'selected' : ''} value="8">8</option>
                            <option ${response.attempts == 9 ? 'selected' : ''} value="9">9</option>
                            <option ${response.attempts == 10 ? 'selected' : ''} value="10">10</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Cách tính điểm</h5>
                    </div>
                    <div class="col-7">
                        <select class="w-100" name="grademethod" id="grademethod">
                            <option ${response.grademethod == 1 ? 'selected' : ''} value="1">Lần cao nhất</option>
                            <option ${response.grademethod == 2 ? 'selected' : ''} value="2">Điểm trung bình</option>
                            <option ${response.grademethod == 3 ? 'selected' : ''} value="3">Thử nghiệm lần đầu</option>
                            <option ${response.grademethod == 4 ? 'selected' : ''} value="4">Kiểm tra lần cuối</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <h5><b>Hành vi câu hỏi</b></h5>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Thay đổi vị trí đáp án trong các câu hỏi</h5>
                    </div>
                    <div class="col-7">
                        <select class="w-100" name="shuffleanswers" id="shuffleanswers">
                            <option value="0">Không</option>
                            <option selected="" value="1">Có</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Hành vi của các câu hỏi như thế nào</h5>
                    </div>
                    <div class="col-7">
                        <select class="w-100" name="preferredbehaviour" id="preferredbehaviour">
                            <option ${response.preferredbehaviour == 'adaptive' ? 'selected' : ''} value="adaptive">Adaptive mode</option>
                            <option ${response.preferredbehaviour == 'adaptivenopenalty' ? 'selected' : ''} value="adaptivenopenalty">Adaptive mode (no penalties)</option>
                            <option ${response.preferredbehaviour == 'deferredfeedback' ? 'selected' : ''} value="deferredfeedback">Deferred feedback</option>
                            <option ${response.preferredbehaviour == 'deferredcbm' ? 'selected' : ''} value="deferredcbm">Deferred feedback with CBM</option>
                            <option ${response.preferredbehaviour == 'immediatefeedback' ? 'selected' : ''} value="immediatefeedback">Immediate feedback</option>
                            <option ${response.preferredbehaviour == 'immediatecbm' ? 'selected' : ''} value="immediatecbm">Immediate feedback with CBM</option>
                            <option ${response.preferredbehaviour == 'interactive' ? 'selected' : ''} value="interactive">Interactive with multiple tries</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <h5><b>Xem lại các tùy chọn</b></h5>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Sau khi nộp bài</h5>
                    </div>
                    <div class="col-7">
                        <div class="form-check">
                            <input class="form-check-input" name="attemptimmediatelyopen" style="width: auto" type="checkbox" ${response.reviewattempt == '65536' ? '' : 'checked'} id="id_attemptimmediatelyopen" value="1">
                            <label class="form-check-label" for="id_attemptimmediatelyopen">Bài làm</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" style="width: auto" name="correctnessimmediatelyopen" ${response.reviewcorrectness == '0' ? '' : 'checked'} id="id_correctnessimmediatelyopen" value="1">
                            <label class="form-check-label" for="id_correctnessimmediatelyopen">Nếu đúng</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" style="width: auto" name="marksimmediatelyopen" ${response.reviewmarks == '0' ? '' : 'checked'} id="id_marksimmediatelyopen" value="1">
                            <label class="form-check-label" for="id_marksimmediatelyopen">Điểm</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" style="width: auto" name="rightanswerimmediatelyopen" ${response.reviewrightanswer == '0' ? '' : 'checked'} id="id_rightanswerimmediatelyopen" value="1">
                            <label class="form-check-label" for="id_rightanswerimmediatelyopen">Câu trả lời đúng</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" style="width: auto" name="generalfeedbackimmediatelyopen" ${response.reviewgeneralfeedback == '0' ? '' : 'checked'} id="id_generalfeedbackimmediatelyopen" value="1">
                            <label class="form-check-label" for="id_generalfeedbackimmediatelyopen"> Phản hồi chung</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <h5><b>Không cho phép truy cập</b></h5>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Activity completion</h5>
                    </div>
                    <div class="col-7"><a href="javascript:void(0);" class="btn btn-secondary showAvailabilityModal" data-cmid="57749" data-instance="45960" data-type="quiz">Chọn</a></div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <h5><b>Hoạt động hoàn thành</b></h5>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Completion tracking</h5>
                    </div>
                    <div class="col-7">
                        <select class="w-100" name="completion" id="quiz_completion">
                            <option ${response.completion == '0' ? 'selected' : ''} value="0">Không chỉ rõ việc hoàn thành hoạt động</option>
                            <option ${response.completion == '2' ? 'selected' : ''} value="2">Khi các điều kiện được thỏa mãn, đánh dấu hoạt động như là đã hoàn thành</option>
                        </select>
                        <div class="form-check form-check-completionview" ${response.completion == 2 ? '' : 'style="display: none;"'}>
                            <input class="form-check-input" type="checkbox" style="width: auto" name="completionview" id="quiz_completionview" value="1" ${response.completionview == 1 ? 'checked' : ''}>
                            <label class="form-check-label" for="completionview">Học viên phải xem hoạt động này để hoàn thành nó</label>
                        </div>
                        <div class="form-check form-check-completionpass" ${response.completion == 2 ? '' : 'style="display: none;"'}>
                            <input class="form-check-input" type="checkbox" style="width: auto" name="completionpass" id="quiz_completionpass" value="1" ${response.completionpassgrade == 1 ? 'checked' : ''}>
                            <label class="form-check-label" for="quiz_completionpass">Yêu cầu điểm qua môn</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2 grade-box" ${response.completionpassgrade == 1 ? '' : 'style="display: none;"'}>
                    <div class="col-5">
                        <h5>Điểm để qua</h5>
                    </div>
                    <div class="col-7"><input class="w-100" min="0" type="number" id="quiz_grade_pass" name="gradePass" value="${response.gradepass || '0'}"></div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <h5><b>Lịch sử</b></h5>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Người tạo</h5>
                    </div>
                    <div class="col-7"><input disabled="" class="i-grade-quiz w-100" type="text" name="created_by" value="${response.creator || ''}"></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Ngày tạo</h5>
                    </div>
                    <div class="col-7"><input disabled="" class="i-grade-quiz w-100" type="text" name="created_at" value="${response.created_at}"></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Người cập nhật</h5>
                    </div>
                    <div class="col-7"><input disabled="" class="i-grade-quiz w-100" type="text" name="created_by" value="${response.modifier || ''}"></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Ngày cập nhật</h5>
                    </div>
                    <div class="col-7">
                        <input disabled="" class="i-grade-quiz w-100" type="text" name="created_at" value="${response.updated_at}">
                    </div>
                </div>
                <input type="hidden" name="availability_item" id="availability-item-${response.main_id}" class="cmid-must-complete" value="${str_availability_cmid}">
                <input type="hidden" name="availability_folder" id="availability-folder-${response.main_id}" class="folfer-must-complete" value="">
                <input type="hidden" name="change_availability" id="change-availability-${response.main_id}" value="0">
                <input type="hidden" name="activity_cmid" id="activity-cmid" value="${response.moodle_id}">
                <div class="mt-4 hide" style="text-align: center">
                    <a href="#" class="btn btn-primary btn-save-activity">Cập nhật</a>
                </div>
            </div>
        `;
    
        // Thêm nội dung vào parentElement
        parentElement.append(newItem); 

        // $('.btn-save-activity').on('click', function(event) {
        //     event.preventDefault();
        //     updateActivityQuiz(response.main_id, selectedParentId, courseId, 'quiz')
        // });

        $('.btn-save').attr('data-type', response.moodle_type);
        $('.btn-save').attr('data-item-id', response.main_id);
        $('.btn-save').attr('data-course-id', courseId);
        $('.btn-save').attr('data-parent-id', selectedParentId);

        $('.showAvailabilityModal').on('click', function(event) {
            event.preventDefault();
            getDataAvailablity(response.main_id, response.parent_id);
        });

        $('#quiz_completionpass').on('change', function() {
            // Kiểm tra trạng thái của checkbox
            if ($(this).is(':checked')) {
                // Nếu checkbox được chọn, hiển thị 'grade-box'
                $('.grade-box').show();
            } else {
                // Nếu checkbox không được chọn, ẩn 'grade-box'
                $('.grade-box').hide();
            }
        });

        $('#quiz_completion').on('change', function() {
            // Kiểm tra trạng thái của checkbox
            if ($(this).val() == 0) {
                $('.form-check-completionview').hide();
                $('.form-check-completionpass').hide();
            } else {
                $('.form-check-completionview').show();
                $('.form-check-completionpass').show();
            }
        });
    }

    function renderTemplateUrl(response, selectedParentId, sectionData, str_availability_cmid){
        var parentElement = $('#wrapper-dynamic-url-form');
        parentElement.empty();

        var parentSelectHTML = '';
        parentSelectHTML = `
            <div id="fitem_url-parent" class="form-group row  fitem   ">
                <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                    <label class="d-inline word-break " for="url-parent">
                        Thư mục cha
                    </label>
                    <div class="form-label-addon d-flex align-items-center align-self-start">
                    </div>
                </div>
                <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                    <select class="w-100 custom-select" name="url-parent" id="url-parent">
                        ${sectionData.map(section => {
                            // Kiểm tra nếu section id bằng với selectedParentId thì thêm selected
                            const isSelected = section.id === selectedParentId ? 'selected' : '';
                            return `<option value="${section.id}" ${isSelected}>${section.moodle_name}</option>`;
                        }).join('')}
                    </select>
                    <div class="form-control-feedback invalid-feedback" id="id_error_url-parent">
                    </div>
                </div>
            </div>
        `;

        const completionexpected = response.completionexpected; //output: 1732936920

        const date = completionexpected === 0 ? new Date() : new Date(completionexpected * 1000);

        // Lấy ngày, tháng, năm, giờ, phút
        const day = date.getDate();
        const month = date.getMonth() + 1; // Tháng bắt đầu từ 0 nên cần +1
        const year = date.getFullYear();
        const startHour = date.getHours();
        const startMinute = date.getMinutes();
        const customData = JSON.parse(response.customdata);
        const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
        var newItem = `
            <form data-random-ids="1" autocomplete="off" action="#" method="post" accept-charset="utf-8" id="mform1_jSAfylBsza9VpuD" class="mform" data-boost-form-errors-enhanced="1">
                <div class="left-side-content mt-2">
                    <div class="text-right mb-3">
                        <a href="javascript:void(0);" class="btn-act-view btn-preview-url">
                            <i class="fa fa-arrows-alt fa-2x" title="Xem nội dung" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="preview-url d-none"></div>
                    <div class="preview-setting">
                        <div id="fitem_id_introeditor" class="form-group row fitem">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break ">
                                    Mô tả
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start"></div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement">
                                <textarea id="url_intro" name="url_intro" class="tinymce-textarea" cols="70" rows="3">${response.intro}</textarea>
                                <div class="form-control-feedback invalid-feedback" id="id_error_introeditor"></div>
                            </div>
                        </div>
                        <div id="fitem_id_externalurl" class="form-group row fitem">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break" for="id_externalurl">
                                    URL
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                    <div class="text-danger" title="Bắt buộc">
                                    <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Bắt buộc" aria-label="Bắt buộc"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement">
                                <input type="text" class="form-control " name="externalurl" id="id_externalurl" value="${response.externalurl || 'http://'}" style="width: 100%;" ="style="&quot;width:" 100%;&quot;"="">
                                <div class="form-control-feedback invalid-feedback" id="id_error_externalurl"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-side-content mt-2 hide">
                    <div class="wrapper-label-name">
                        <h4>URL</h4>
                    </div>
                    <div class="box-head">
                        <h4>Thông tin chung</h4>
                    </div>
                    <div id="fitem_url_name" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="url_name">
                                Tên
                            </label>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                                <div class="text-danger" title="Bắt buộc">
                                    <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Bắt buộc" aria-label="Bắt buộc"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                            <input type="text" class="form-control " name="url_name" id="url_name" value="${response.moodle_name}" size="48">
                            <div class="form-control-feedback invalid-feedback" id="id_error_name">
                            </div>
                        </div>
                    </div>
                    ${parentSelectHTML}
                    <div id="fitem_url_status" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="url_status">
                                Trạng thái
                            </label>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                            <select class="custom-select" name="url_status" id="url_status" style="width: 100% !important">
                                <option ${response.visible == 0 ? 'selected' : ''} value="0">Ẩn</option>
                                <option ${response.visible == 1 ? 'selected' : ''} value="1">Hiện</option>
                            </select>
                            <div class="form-control-feedback invalid-feedback" id="id_error_visible">
                            </div>
                        </div>
                    </div>
                    <div id="fitem_id_display" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="id_display">
                                Hiển thị
                            </label>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                                <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>This setting, together with the URL file type and whether the browser allows embedding, determines how the URL is displayed. Options may include:</p>
                                    <ul>
                                    <li>Automatic - The best display option for the URL is selected automatically</li>
                                    <li>Embed - The URL is displayed within the page below the navigation bar together with the URL description and any blocks</li>
                                    <li>Open - Only the URL is displayed in the browser window</li>
                                    <li>In pop-up - The URL is displayed in a new browser window without menus or an address bar</li>
                                    <li>In frame - The URL is displayed within a frame below the navigation bar and URL description</li>
                                    <li>New window - The URL is displayed in a new browser window with menus and an address bar</li>
                                    </ul>
                                    </div> " data-html="true" tabindex="0" data-trigger="focus">
                                <i class="icon fa fa-question-circle text-info fa-fw " title="Trợ giúp về Hiển thị" aria-label="Trợ giúp về Hiển thị"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                            <select class="custom-select" name="display" id="id_display" style="width: 100% !important">
                                <option ${customData.display == 0 ? 'selected' : ''} value="0">Tự động</option>
                                <option ${customData.display == 1 ? 'selected' : ''} value="1" selected="">Lồng ghép (Embed)</option>
                                <option ${customData.display == 5 ? 'selected' : ''} value="5">Mở</option>
                                <option ${customData.display == 6 ? 'selected' : ''} value="6">Cửa sổ đổ xuống</option>
                            </select>
                            <div class="form-control-feedback invalid-feedback" id="id_error_display">
                            </div>
                        </div>
                    </div>
                    <div id="fitem_id_popupwidth" class="form-group row  fitem   " hidden="hidden" style="display: none;">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="id_popupwidth" hidden="hidden" style="display: none;">
                                Chiều rộng cửa sổ pop-up (tính bằng độ phân giải)
                            </label>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                            <input type="text" class="form-control " name="popupwidth" id="id_popupwidth" value="620" size="3" disabled="disabled">
                            <div class="form-control-feedback invalid-feedback" id="id_error_popupwidth">
                            </div>
                        </div>
                    </div>
                    <div id="fitem_id_popupheight" class="form-group row  fitem   " hidden="hidden" style="display: none;">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="id_popupheight" hidden="hidden" style="display: none;">
                                Chiều cao cửa sổ pop-up (tính bằng độ phân giải)
                            </label>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                            <input type="text" class="form-control " name="popupheight" id="id_popupheight" value="450" size="3" disabled="disabled">
                            <div class="form-control-feedback invalid-feedback" id="id_error_popupheight">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row  fitem">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-9 checkbox">
                            <div class="form-check d-flex">
                                <input type="checkbox" ${response.showdescription == 1 ? 'checked' : ''} name="printintro" class="form-check-input " value="1" id="id_printintro">
                                <label for="id_printintro">
                                    Hiển thị mô tả URL
                                </label>
                                <div class="ml-2 d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="form-control-feedback invalid-feedback" id="id_error_printintro">
                            </div>
                        </div>
                    </div>
                    <div id="fitem_id_activitycompletionheader" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <span class="d-inline-block ">
                                Hoàn thành các hoạt động
                            </span>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement pt-2" data-fieldtype="static">
                            <div class="form-control-static">
                                <a href="javascript:void(0);" class="btn btn-secondary showAvailabilityModal" data-cmid="57749" data-instance="45960" data-type="url">Chọn</a>
                            </div>
                            <div class="form-control-feedback invalid-feedback" id="id_error_activitycompletionheader">
                            </div>
                        </div>
                    </div>
                    <div id="fitem_id_completion" class="form-group row  fitem   ">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="id_completion">
                                Kiểm tra độ hoàn thành
                            </label>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                                <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>Nếu được kích hoạt, hoàn thành hoạt động được theo dõi, thủ công hoặc tự động, dựa trên các điều kiện nhất định. Nhiều điều kiện có thể được đặt nếu muốn. Nếu vậy, hoạt động sẽ chỉ được xem là hoàn thành khi TẤT CẢ điều kiện được thỏa.</p>

                        <p>Một dấu chọn kế bên tên hoạt động trên trang khóa học chỉ ra khi nào hoạt động hoàn tất.</p>
                        </div> <div class=&quot;helpdoclink&quot;><a href=&quot;https://docs.moodle.org/311/vi/activity/completion&quot;><i class=&quot;icon fa fa-info-circle fa-fw iconhelp icon-pre&quot; aria-hidden=&quot;true&quot;  ></i>Trợ giúp thêm</a></div>" data-html="true" tabindex="0" data-trigger="focus">
                                <i class="icon fa fa-question-circle text-info fa-fw " title="Trợ giúp về Kiểm tra độ hoàn thành" aria-label="Trợ giúp về Kiểm tra độ hoàn thành"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                            <select class="custom-select" name="completion" id="id_completion" style="width: 100% !important">
                                <option ${response.completion == 0 ? 'selected' : ''} value="0">Không chỉ rõ việc hoàn thành hoạt động</option>
                                <option ${response.completion == 2 ? 'selected' : ''} value="2">Khi các điều kiện được thỏa mãn, đánh dấu hoạt động như là đã hoàn thành</option>
                            </select>
                            <div class="form-control-feedback invalid-feedback" id="id_error_completion">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row fitem_id_completionview fitem" style="${response.completion == 0 ? 'display: none;' : ''}">
                        <div class="col-md-3">
                            <label for="id_completionview">
                                Yêu cầu phải xem
                            </label>
                        </div>
                        <div class="col-md-9 checkbox">
                            <div class="form-check d-flex">
                                <input type="checkbox" name="completionview" class="form-check-input " value="1" id="id_completionview" ${response.completionview == 1 ? 'checked' : ''} aria-describedby="id_completionview_description">
                                <span id="id_completionview_description">
                                    Học viên phải xem hoạt động này để hoàn thành nó
                                </span>
                                <div class="ml-2 d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="form-control-feedback invalid-feedback" id="id_error_completionview">
                            </div>
                        </div>
                    </div>
                    <div id="fitem_id_completionexpected" class="form-group row fitem" data-groupname="completionexpected"  style="${response.completion == 0 ? 'display: none;' : ''}">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <p id="id_completionexpected_label" class="mb-0 word-break" aria-hidden="true" style="cursor: default;">
                                Ngày hoàn thành dự kiến là
                            </p>

                            <div class="form-label-addon d-flex align-items-center align-self-start">
                                <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>Thiết lập này chỉ định ngày giờ hoạt động hoàn thành theo mong đợi. Ngày giờ không được hiển thị cho học sinh và chỉ được hiển thị trong báo cáo hoàn thành hoạt động.</p>
                        </div> " data-html="true" tabindex="0" data-trigger="focus">
                                <i class="icon fa fa-question-circle text-info fa-fw " title="Trợ giúp về Ngày hoàn thành dự kiến là" aria-label="Trợ giúp về Ngày hoàn thành dự kiến là"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="date_time_selector">
                            <div class="mb-2">
                                <div class="d-flex flex-wrap">
                                    <div class="form-group  fitem">
                                        <input type="date" id="completionexpected_date" class="search-date form-control  search-date-startdate" ${response.completionexpected == 0 ? 'disabled' : ''} value="${formattedDate}">
                                    </div>

                                    <div class="form-group  fitem">
                                        <select class="ml-2 form-control" style="min-height:2.3rem;" id="completionexpected_starthour" name="completionexpected_starthour" ${response.completionexpected == 0 ? 'disabled' : ''}>
                                            ${[...Array(24).keys()].map(hour => {
                                                const hourValue = hour < 10 ? '0' + hour : hour;
                                                const selected = (hourValue == startHour) ? 'selected' : '';
                                                return `<option value="${hourValue}" ${selected}>${hourValue}</option>`;
                                            }).join('')}
                                        </select>
                                    </div>

                                    <div class="form-group  fitem">
                                        <select class="form-control" id="completionexpected_startminute" style="min-height:2.3rem;" name="completionexpected_startminute" ${response.completionexpected == 0 ? 'disabled' : ''}>
                                            ${[...Array(60).keys()].map(minute => {
                                                const minuteValue = minute < 10 ? '0' + minute : minute;
                                                const selected = (minuteValue == startMinute) ? 'selected' : '';
                                                return `<option value="${minuteValue}" ${selected}>${minuteValue}</option>`;
                                            }).join('')}
                                        </select>
                                    </div>

                                    <label data-fieldtype="checkbox" class="form-check  fitem  ">
                                        <input type="checkbox" name="completionexpected[enabled]" class="form-check-input " ${response.completionexpected == 0 ? '' : 'checked'} id="id_completionexpected" value="1">
                                        Mở
                                    </label>
                                </div>
                            </div>
                            <div class="form-control-feedback invalid-feedback" id="id_error_completionexpected">
                            </div>
                        </div>
                    </div>
                    <div class="box-head">
                        <h4>Lịch sử</h4>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_created_by" class="form-group row  fitem   ">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_created_by">
                                    Người tạo
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="created_by" id="id_created_by" value="${response.creator || ''}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_created_by">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_created_at" class="form-group row  fitem">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_created_at">
                                    Ngày tạo
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="created_at" id="id_created_at" value="${response.created_at}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_created_at">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_updated_by" class="form-group row  fitem   ">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_updated_by">
                                    Người cập nhật
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="updated_by" id="id_updated_by" value="${response.modifier || ''}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_updated_by">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_updated_at" class="form-group row  fitem   ">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_updated_at">
                                    Ngày cập nhật
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="updated_at" id="id_updated_at" value="${response.updated_at}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_updated_at">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="availability_item" id="availability-item-${response.main_id}" class="cmid-must-complete" value="${str_availability_cmid}">
                    <input type="hidden" name="availability_folder" id="availability-folder-${response.main_id}" class="folfer-must-complete" value="">
                    <input type="hidden" name="change_availability" id="change-availability-${response.main_id}" value="0">
                    <input type="hidden" name="activity_cmid" id="activity-cmid" value="${response.moodle_id}">
                    <div class="mt-4 hide" style="text-align: center">
                        <a href="#" class="btn btn-primary btn-save-activity">Cập nhật</a>
                    </div>
                </div>
            </form>
        `;
    
        // Thêm nội dung vào parentElement
        parentElement.append(newItem);

        tinymce.execCommand('mceRemoveEditor', true, 'url_intro');

        // Khởi tạo TinyMCE cho textarea vừa được append
        tinymce.init({
            selector: '#url_intro', // Chọn đúng id hoặc class của textarea mà bạn muốn khởi tạo
            height: 300,
            width: '100%',
            plugins: 'preview searchreplace autolink directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help',
            toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
            image_advtab: true,
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });

        $("[data-toggle=popover]").popover();

        $('#id_completionexpected').on('change', function() {
            // Kiểm tra trạng thái của checkbox
            if ($(this).is(':checked')) {
                // Nếu checkbox được chọn, hiển thị 'grade-box'
                $('#completionexpected_starthour').prop('disabled', false);
                $('#completionexpected_startminute').prop('disabled', false);
                $('#completionexpected_date').prop('disabled', false);
            } else {
                // Nếu checkbox không được chọn, ẩn 'grade-box'
                $('#completionexpected_starthour').prop('disabled', true);
                $('#completionexpected_startminute').prop('disabled', true);
                $('#completionexpected_date').prop('disabled', true);
            }
        });

        // $('.btn-save-activity').on('click', function(event) {
        //     event.preventDefault();
        //     updateActivityUrl(response.main_id, selectedParentId, courseId, 'url')
        // });
        $('.btn-save').attr('data-type', response.moodle_type);
        $('.btn-save').attr('data-item-id', response.main_id);
        $('.btn-save').attr('data-course-id', courseId);
        $('.btn-save').attr('data-parent-id', selectedParentId);

        $('.showAvailabilityModal').on('click', function(event) {
            event.preventDefault();
            getDataAvailablity(response.main_id, response.parent_id);
        });

        $('#id_completion').on('change', function() {
            // Kiểm tra trạng thái của checkbox
            if ($(this).val() == 2) {
                // Nếu checkbox được chọn, hiển thị 'grade-box'
                $('.fitem_id_completionview').show();
                $('#fitem_id_completionexpected').show();
            } else {
                // Nếu checkbox không được chọn, ẩn 'grade-box'
                $('.fitem_id_completionview').hide();
                $('#fitem_id_completionexpected').hide();
            }
        });
    }

    function getDataAvailablity(activity_id, section_id){
        $.ajax({
            url: "/api/lms/availablity/detail",
            type: "POST",
            data: {
                activity_id: activity_id,
                section_id: section_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                renderAvailabilityModalHtml(response)
                $('.btn-choose-activity').attr('data-cmid', activity_id);
                
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
        
    }

    function renderAvailabilityModalHtml(response){
        var parentElement = $('#modal-availability .modal-body .availability-items');
        parentElement.empty();

        var array_csr_info = response.array_csr_info;
        var str_availability_cmid = response.str_availability_cmid;

        let newHtml = '';

        array_csr_info.forEach(function(item, index) {
            if(item.level == 1 && item.visible == 1){
                newHtml += `
                    <div class="availability-item  availability-item-${item.id} level-1">
                        <input type="checkbox" class="folder-check check-availability level-1" data-instance="${item.id}" data-type="course_sections" data-path="${item.id}" data-cmid="null">
                        <div class="normal-item">
                            <i class="ml-2 fa collape-parent fa-chevron-down" data-instance="${item.id}" data-level="1"></i>
                            <div class="ml-1">${item.moodle_name}</div>
                        </div>
                    </div>
                `;
            }

            if (item.level === 2 && item.visible == 1) {
                const isChecked = str_availability_cmid.includes(item.id) ? 'checked' : '';
                newHtml += `
                    <div class="availability-item csr-instance-parent-${item.section_id} availability-item-${item.main_id} level-2" data-parent-id="${item.section_id}">
                        <input 
                            type="checkbox" 
                            class="activity-check check-availability level-2" 
                            data-instance="${item.moodle_id}" 
                            data-type="${item.moodle_type}" 
                            data-path="${item.section_id}/${item.main_id}" 
                            data-cmid="${item.moodle_id}"
                            ${isChecked}
                        >
                        <div class="normal-item">
                            <div class="ml-1">${item.moodle_name}</div>
                        </div>
                    </div>
                `;
            }
        });
        parentElement.html(newHtml);
        $('#modal-availability').modal('show');
        handleCheckboxEvents();
    }

    function handleCheckboxEvents() {
        // Khi checkbox level 1 được chọn
        $('.folder-check.level-1').on('change', function() {
            const isChecked = $(this).is(':checked');
            const parentId = $(this).data('instance');
            console.log(parentId);
            // Check/Uncheck tất cả các checkbox level 2 con của nó
            $(`.level-2[data-parent-id="${parentId}"] input[type="checkbox"]`).prop('checked', isChecked);
        });

        // Khi checkbox level 2 được chọn
        $('.activity-check.level-2').on('change', function() {
            const parentId = $(this).closest('.availability-item').data('parent-id');
            const allLevel2Checkboxes = $(`.level-2[data-parent-id="${parentId}"] input[type="checkbox"]`);
            const allChecked = allLevel2Checkboxes.length === allLevel2Checkboxes.filter(':checked').length;

            // Nếu tất cả các con đều được check thì check checkbox level 1
            $(`.folder-check.level-1[data-instance="${parentId}"]`).prop('checked', allChecked);
        });
    }

    $(document).on('click', '.btn-choose-activity', function() {
        // Lấy tất cả các checkbox level 2 đã được checked
        const checkedActivities = $('.availability-items .activity-check.level-2:checked');
        const cmid = $(this).attr('data-cmid');
        let checkedCmidList = [];
    
        // Duyệt qua các checkbox đã checked và lấy giá trị data-cmid
        checkedActivities.each(function() {
            const cmid = $(this).data('cmid');
            if (cmid) {
                checkedCmidList.push(cmid);
            }
        });
    
        // Nối thành chuỗi với dấu phẩy
        const resultString = checkedCmidList.join(',');
    
        $('#availability-item-'+cmid).val(resultString);
        // Hiển thị chuỗi trong console
        console.log(resultString);

        $('#modal-availability').modal('hide');
    });

    function updateActivityQuiz(activity_id, selectedParentId, courseId, type) {
        const clickedCategoryId = selectedParentId;
        const currentUser = localStorage.getItem('currentUser');

        var formData = {
            quiz_name: $("#quiz_name").val(),
            quiz_section: $("#quiz-parent").val(),
            quiz_visible: $("#quiz_status").val(),
            gradeQuiz: $("input[name='gradeQuiz']").val(),
            attempts: $("#attempts").val(),
            grademethod: $("#grademethod").val(),
            shuffleanswers: $("#shuffleanswers").val(),
            preferredbehaviour: $("#preferredbehaviour").val(),
            attemptimmediatelyopen: $("#id_attemptimmediatelyopen").is(":checked") ? 1 : 0,
            correctnessimmediatelyopen: $("#id_correctnessimmediatelyopen").is(":checked") ? 1 : 0,
            marksimmediatelyopen: $("#id_marksimmediatelyopen").is(":checked") ? 1 : 0,
            rightanswerimmediatelyopen: $("#id_rightanswerimmediatelyopen").is(":checked") ? 1 : 0,
            generalfeedbackimmediatelyopen: $("#id_generalfeedbackimmediatelyopen").is(":checked") ? 1 : 0,
            completion: $("#quiz_completion").val(),
            completionview: $("#quiz_completionview").is(":checked") ? 1 : 0,
            completionpass: $("#quiz_completionpass").is(":checked") ? 1 : 0,
            gradePass: $("#quiz_grade_pass").val(),
            availability_item: $("#availability-item-"+activity_id).val(),
            section_id: selectedParentId,
            course_id: courseId,
            type: type,
            currentUser: currentUser,
            activity_id: activity_id
        };

        $.ajax({
            url: "/api/lms/activity/update",
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                loadSectionData(courseId, selectedParentId);
                if (response.success) {
                    $('.message-product .alert').addClass('alert-success');
                    $('.message-product .alert').removeClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.success);
                } else {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                }
                $('.message-product').removeClass('hidden');
                $('html, body').animate({ scrollTop: 0 }, 'fast');
            },
            error: function(xhr, status, error) {
                // console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    function updateActivityUrl(activity_id, selectedParentId, courseId, type) {
        const clickedCategoryId = selectedParentId;
        const currentUser = localStorage.getItem('currentUser');

        var formData = {
            // url_intro: $('#url_intro').val(),
            url_intro: tinymce.get('url_intro').getContent(),
            externalurl: $('#id_externalurl').val(),
            url_name: $("#url_name").val(),
            url_section: $("#url-parent").val(),
            url_visible: $("#url_status").val(),
            url_display: $("#id_display").val(),
            url_printintro: $("#id_printintro").is(":checked") ? 1 : 0,
            completion: $("#id_completion").val(),
            completionview: $("#id_completionview").is(":checked") ? 1 : 0,
            availability_item: $("#availability-item-"+activity_id).val(),
            section_id: selectedParentId,
            course_id: courseId,
            type: type,
            currentUser: currentUser,
            activity_id: activity_id,
            completionexpected: $("#id_completionexpected").is(":checked") ? 1 : 0,
            completionexpected_date: $("#completionexpected_date").val(),
            completionexpected_starthour: $("#completionexpected_starthour").val(),
            completionexpected_startminute: $("#completionexpected_startminute").val(),
        };

        $.ajax({
            url: "/api/lms/activity/update",
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                loadSectionData(courseId, selectedParentId);
                if (response.success) {
                    $('.message-product .alert').addClass('alert-success');
                    $('.message-product .alert').removeClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.success);
                } else {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                }
                $('.message-product').removeClass('hidden');
                $('html, body').animate({ scrollTop: 0 }, 'fast');
            },
            error: function(xhr, status, error) {
                // console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    $('.btn-save').on('click', function(event) {
        event.preventDefault();
        const dataType = $(this).attr('data-type');
        const dataItemId = $(this).attr('data-item-id');
        const dataCourseId = $(this).attr('data-course-id');
        const dataParentId = $(this).attr('data-parent-id');

        if(dataType == 'section'){
            updateSection(dataItemId); 
        }
        if(dataType == 'quiz'){
            updateActivityQuiz(dataItemId, dataParentId, dataCourseId, dataType);
        }
        if(dataType == 'url'){
            updateActivityUrl(dataItemId, dataParentId, dataCourseId, dataType); 
        }
        if(dataType == 'resource'){
            updateActivityResource(dataItemId, dataParentId, dataCourseId, dataType); 
        }
        if(dataType == 'assign'){
            updateActivityAssign(dataItemId, dataParentId, dataCourseId, dataType); 
        }
    });

    // code upload file to resource

    function renderTemplateResource(response, selectedParentId, sectionData, str_availability_cmid){
        var parentElement = $('#wrapper-dynamic-resource-form');
        parentElement.empty();
        var parentSelectHTML = '';
        parentSelectHTML = `
            <div id="fitem_id_instance_parent" class="form-group row  fitem   ">
                <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                    <label class="d-inline word-break " for="id_instance_parent">
                        Thư mục cha
                    </label>
                    <div class="form-label-addon d-flex align-items-center align-self-start">
                    </div>
                </div>
                <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                    <select class="w-100 custom-select" name="resource-parent" id="resource-parent">
                        ${sectionData.map(section => {
                            // Kiểm tra nếu section id bằng với selectedParentId thì thêm selected
                            const isSelected = section.id === selectedParentId ? 'selected' : '';
                            return `<option value="${section.id}" ${isSelected}>${section.moodle_name}</option>`;
                        }).join('')}
                    </select>
                    <div class="form-control-feedback invalid-feedback" id="id_error_instance_parent">
                    </div>
                </div>
            </div>
        `;

        const completionexpected = response.completionexpected; //output: 1732936920

        const date = completionexpected === 0 ? new Date() : new Date(completionexpected * 1000);

        // Lấy ngày, tháng, năm, giờ, phút
        const day = date.getDate();
        const month = date.getMonth() + 1; // Tháng bắt đầu từ 0 nên cần +1
        const year = date.getFullYear();
        const startHour = date.getHours();
        const startMinute = date.getMinutes();
        const customData = JSON.parse(response.customdata);
        const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
        var newItem = `
            <form data-random-ids="1" autocomplete="off" action="#" method="post" accept-charset="utf-8" id="mform1_jSAfylBsza9VpuD" class="mform" data-boost-form-errors-enhanced="1">
                <div class="left-side-content mt-2">
                    <label>Chọn tập tin</label>
                    <div id="fitem_id_overviewfiles_filemanager" class="form-group row fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <p id="id_overviewfiles_filemanager_label" class="mb-0 d-inline" aria-hidden="true" style="cursor: default;">
                                Hinh ảnh của khóa học
                            </p>
                
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                                
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="filemanager">
                            <fieldset class="w-100 m-0 p-0 border-0" id="id_overviewfiles_filemanager_fieldset">
                                <legend class="sr-only">Hinh ảnh của khóa học</legend>
                                <div id="filemanager-671dac14e74d7" class="filemanager w-100 fm-loaded fm-nomkdir fm-nofiles fm-noitems">
                                    <div class="fp-restrictions">
                                        <span>Kích thước tối đa với một tập tin Không giới hạn, số lượng tập tin đính kèm tối đa:1</span>
                                        <span class="dnduploadnotsupported-message"> - Kéo thả không được hỗ trợ<a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>Nếu có nhiều tệp trong thư mục, tệp chính là cái xuất hiện trên trang hiển thị. Các tệp khác như hình ảnh hay phim có thể được nhúng bên trong. Trong trình quản lí tệp tệp chính được chỉ dẫn với tiêu đề in đậm.</p>
                                        </div> " data-html="true" tabindex="0" data-trigger="focus">
                                            <i class="icon fa fa-question-circle text-info fa-fw " title="Trợ giúp về Đặt tập tin chính" aria-label="Trợ giúp về Đặt tập tin chính"></i>
                                        </a></span>
                                    </div>
                                    <div class="fp-navbar bg-faded card mb-0">
                                        <div class="filemanager-toolbar icon-no-spacing">
                                            <div class="fp-toolbar">
                                                <div class="fp-btn-add">
                                                    <a role="button" title="Thêm..." class="btn btn-secondary btn-sm" href="#">
                                                        <i class="icon fa-regular fa-file fa-fw " aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                <div class="fp-btn-mkdir">
                                                    <a role="button" title="Tạo thư mục" class="btn btn-secondary btn-sm" href="#">
                                                        <i class="icon fa fa-folder-o fa-fw " aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                <div class="fp-btn-download">
                                                    <a role="button" title="Tải" class="btn btn-secondary btn-sm" href="#">
                                                        <i class="icon fa fa-download fa-fw " aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                <div class="fp-btn-delete">
                                                    <a role="button" title="Xoá" class="btn btn-secondary btn-sm" href="#">
                                                        <i class="icon fa fa-trash fa-fw " aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                <span class="fp-img-downloading">
                                                    <span class="sr-only">Đang tải...</span>
                                                    <i class="icon fa fa-circle-o-notch fa-spin fa-fw " aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <div class="fp-viewbar btn-group float-sm-right">
                                                <a title="Hiển thị thư mục với biểu tượng tệp" class="fp-vb-icons btn btn-secondary btn-sm">
                                                    <i class="icon fa fa-th fa-fw " aria-hidden="true"></i>
                                                </a>
                                                <a title="Hiển thi thư mục với chi tiết tệp" class="fp-vb-details btn btn-secondary btn-sm checked">
                                                    <i class="icon fa fa-list fa-fw " aria-hidden="true"></i>
                                                </a>
                                                <a title="Hiển thị thư mục như cây tập tin" class="fp-vb-tree btn btn-secondary btn-sm">
                                                    <i class="icon fa fa-folder fa-fw " aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="fp-pathbar"><span class="fp-path-folder first last odd"><a class="fp-path-folder-name aalink" href="#">Tập tin</a></span></div>
                                    </div>
                                    <div class="filemanager-loading mdl-align"><i class="icon fa fa-circle-o-notch fa-spin fa-fw " aria-hidden="true"></i><span class="sr-only">Đang tải...</span></div>
                                    <div class="filemanager-container card">
                                        <div class="fm-content-wrapper">
                                            <div class="fp-content">
                                                <div class="fp-tableview">
                                                    <div class="yui3-widget yui3-datatable yui3-datatable-sortable">
                                                        <div class="yui3-datatable-content">
                                                            <table cellspacing="0" class="yui3-datatable-table" id="table-image-upload" style="width: 100%;">
                                                                <thead class="yui3-datatable-columns">
                                                                    <tr>
                                                                        <th colspan="1" rowspan="1" class="yui3-datatable-header yui3-datatable-first-header scope="col">
                                                                            <label class="sr-only">Chọn tất cả/không chọn gì</label>
                                                                            <input type="checkbox" data-action="toggle" data-toggle="master" data-togglegroup="file-selections">
                                                                        </th>
                                                                        <th colspan="1" rowspan="1" class="yui3-datatable-header yui3-datatable-col-displayname yui3-datatable-sortable-column" scope="col" data-yui3-col-id="displayname" title="Sort by Tên">
                                                                            <div class="yui3-datatable-sort-liner" tabindex="0" unselectable="on">
                                                                                Tên
                                                                                <span class="yui3-datatable-sort-indicator"></span>
                                                                            </div>
                                                                        </th>
                                                                        <th colspan="1" rowspan="1" class="yui3-datatable-header yui3-datatable-col-datemodified yui3-datatable-sortable-column" scope="col" data-yui3-col-id="datemodified" title="Sort by Sửa lần cuối">
                                                                            <div class="yui3-datatable-sort-liner" tabindex="0" unselectable="on">
                                                                                Sửa lần cuối
                                                                                <span class="yui3-datatable-sort-indicator"></span>
                                                                            </div>
                                                                        </th>
                                                                        <th colspan="1" rowspan="1" class="yui3-datatable-header yui3-datatable-col-size yui3-datatable-sortable-column" scope="col" data-yui3-col-id="size" title="Sort by Kích thước">
                                                                            <div class="yui3-datatable-sort-liner" tabindex="0" unselectable="on">
                                                                                Kích thước
                                                                                <span class="yui3-datatable-sort-indicator"></span>
                                                                            </div>
                                                                        </th>
                                                                        <th colspan="1" rowspan="1" class="yui3-datatable-header yui3-datatable-col-mimetype yui3-datatable-sortable-column" scope="col" data-yui3-col-id="mimetype" title="Sort by Loại">
                                                                            <div class="yui3-datatable-sort-liner" tabindex="0" unselectable="on">
                                                                                Loại<span class="yui3-datatable-sort-indicator"></span>
                                                                            </div>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <!-- <tbody class="yui3-datatable-message">
                                                                    <tr>
                                                                        <td class="yui3-datatable-message-content" colspan="5"></td>
                                                                    </tr>
                                                                </tbody> -->
                                                                <tbody class="yui3-datatable-data">
                                                                    
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fp-iconview d-none">
                                                </div>
                                                <div class="fp-treeview d-none">
                                                    <div class="ygtvitem" id="ygtv0">
                                                        <div class="ygtvchildren" id="ygtvc0">
                                                            <div class="ygtvitem" id="ygtv1">
                                                                <table id="ygtvtableel1" border="0" cellpadding="0" cellspacing="0"
                                                                    class="ygtvtable ygtvdepth0 ygtv-expanded ygtv-highlight0 fp-folder">
                                                                    <tbody>
                                                                        <tr class="ygtvrow">
                                                                            <td id="ygtvt1" class="ygtvcell ygtvlm">
                                                                                <a href="#" class="ygtvspacer">&nbsp;</a>
                                                                            </td>
                                                                            <td id="ygtvcontentel1" class="ygtvcell ygtvhtml ygtvcontent">
                                                                                <span
                                                                                    class="fp-filename-icon fp-folder">
                                                                                    <a href="#">
                                                                                        <span class="fp-icon"><img
                                                                                                src="https://english.ican.vn/classroom/theme/image.php/mb2cg/core/1706779039/f/folder-24"></span>
                                                                                        <span class="fp-reficons1"></span>
                                                                                        <span class="fp-reficons2"></span>
                                                                                        <span class="fp-filename">Tập tin</span>
                                                                                    </a>
                                                                                    <a class="fp-contextmenu" href="#" onclick="return false;">
                                                                                        <i
                                                                                            class="icon fa fa-ellipsis-v fa-fw " title="▶" aria-label="▶"></i>
                                                                                        </a>
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div class="ygtvchildren" id="ygtvc1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fm-empty-container">
                                                <div class="dndupload-message">Thêm các tập tin bằng cách kéo thả.<br>
                                                    <div class="dndupload-arrow"></div>
                                                </div>
                                            </div>
                                            <div class="dndupload-target">Tải các tập tin lên bằng việc thả chúng vào đây<br>
                                                <div class="dndupload-arrow"></div>
                                            </div>
                                            <div class="dndupload-progressbars"></div>
                                            <div class="dndupload-uploadinprogress"><i class="icon fa fa-circle-o-notch fa-spin fa-fw " aria-hidden="true"></i><span class="sr-only">Đang tải...</span></div>
                                        </div>
                                        <div class="filemanager-updating"><i class="icon fa fa-circle-o-notch fa-spin fa-fw " aria-hidden="true"></i><span class="sr-only">Đang tải...</span></div>
                                    </div>
                                </div>
                                <noscript>
                                    <div>
                                        <object type='text/html' data='https://english.ican.vn/classroom/repository/draftfiles_manager.php?env=filemanager&amp;action=browse&amp;itemid=116273913&amp;subdirs=0&amp;maxbytes=-1&amp;areamaxbytes=-1&amp;maxfiles=1&amp;ctx_id=1&amp;course=1&amp;sesskey=elt8O8b6jE' height='160' width='600' style='border:1px solid #000'></object>
                                    </div>
                                </noscript>
                                <input value="116273913" name="overviewfiles_filemanager" type="hidden" id="id_overviewfiles_filemanager">
                                <input name="image_course" class="hidden" type="file" id="image_course">
                            </fieldset>
                            <div class="form-control-feedback invalid-feedback" id="id_error_overviewfiles_filemanager">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-side-content mt-2 hide">
                    <div class="wrapper-label-name">
                        <h4>FILE</h4>
                    </div>
                    <div class="box-head">
                        <h4>Thông tin chung</h4>
                    </div>
                    <div id="fitem_id_name" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="id_name">
                                Tên file
                            </label>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                                <div class="text-danger" title="Bắt buộc">
                                    <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Bắt buộc" aria-label="Bắt buộc"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                            <input type="text" class="form-control " name="resource_name" id="resource_name" value="${response.moodle_name}" size="48">
                            <div class="form-control-feedback invalid-feedback" id="id_error_name">
                            </div>
                        </div>
                    </div>
                    ${parentSelectHTML}
                    <div id="fitem_id_visible" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="id_visible">
                                Trạng thái
                            </label>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                            <select class="custom-select" name="resource_status" id="resource_status" style="width: 100% !important">
                                <option ${response.visible == 0 ? 'selected' : ''} value="0">Ẩn</option>
                                <option ${response.visible == 1 ? 'selected' : ''} value="1">Hiện</option>
                            </select>
                            <div class="form-control-feedback invalid-feedback" id="id_error_visible">
                            </div>
                        </div>
                    </div>
                    <div id="fitem_id_activitycompletionheader" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <span class="d-inline-block ">
                                Hoàn thành các hoạt động
                            </span>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement pt-2" data-fieldtype="static">
                            <div class="form-control-static">
                                <a href="javascript:void(0);" class="btn btn-secondary showAvailabilityModal" data-cmid="57749" data-instance="45960" data-type="resource">Chọn</a>
                            </div>
                            <div class="form-control-feedback invalid-feedback" id="id_error_activitycompletionheader">
                            </div>
                        </div>
                    </div>
                    <div id="fitem_id_completion_resource" class="form-group row  fitem   ">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="id_completion_resource">
                                Kiểm tra độ hoàn thành
                            </label>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                                <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>Nếu được kích hoạt, hoàn thành hoạt động được theo dõi, thủ công hoặc tự động, dựa trên các điều kiện nhất định. Nhiều điều kiện có thể được đặt nếu muốn. Nếu vậy, hoạt động sẽ chỉ được xem là hoàn thành khi TẤT CẢ điều kiện được thỏa.</p>

                        <p>Một dấu chọn kế bên tên hoạt động trên trang khóa học chỉ ra khi nào hoạt động hoàn tất.</p>
                        </div> <div class=&quot;helpdoclink&quot;><a href=&quot;https://docs.moodle.org/311/vi/activity/completion&quot;><i class=&quot;icon fa fa-info-circle fa-fw iconhelp icon-pre&quot; aria-hidden=&quot;true&quot;  ></i>Trợ giúp thêm</a></div>" data-html="true" tabindex="0" data-trigger="focus">
                                <i class="icon fa fa-question-circle text-info fa-fw " title="Trợ giúp về Kiểm tra độ hoàn thành" aria-label="Trợ giúp về Kiểm tra độ hoàn thành"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                            <select class="custom-select" name="completion" id="id_completion_resource" style="width: 100% !important">
                                <option ${response.completion == 0 ? 'selected' : ''} value="0">Không chỉ rõ việc hoàn thành hoạt động</option>
                                <option ${response.completion == 2 ? 'selected' : ''} value="2">Khi các điều kiện được thỏa mãn, đánh dấu hoạt động như là đã hoàn thành</option>
                            </select>
                            <div class="form-control-feedback invalid-feedback" id="id_error_completion">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row fitem_id_completionview_resource fitem" style="${response.completion == 0 ? 'display: none;' : ''}">
                        <div class="col-md-3">
                            <label for="id_completionview_resource">
                                Yêu cầu phải xem
                            </label>
                        </div>
                        <div class="col-md-9 checkbox">
                            <div class="form-check d-flex">
                                <input type="checkbox" name="completionview" class="form-check-input " value="1" id="id_completionview_resource" ${response.completionview == 1 ? 'checked' : ''} aria-describedby="id_completionview_resource_description">
                                <span id="id_completionview_resource_description">
                                    Học viên phải xem hoạt động này để hoàn thành nó
                                </span>
                                <div class="ml-2 d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="form-control-feedback invalid-feedback" id="id_error_completionview">
                            </div>
                        </div>
                    </div>
                    <div class="box-head">
                        <h4>Lịch sử</h4>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_created_by" class="form-group row  fitem   ">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_created_by">
                                    Người tạo
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="created_by" id="id_created_by" value="${response.creator || ''}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_created_by">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_created_at" class="form-group row  fitem">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_created_at">
                                    Ngày tạo
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="created_at" id="id_created_at" value="${response.created_at}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_created_at">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_updated_by" class="form-group row  fitem   ">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_updated_by">
                                    Người cập nhật
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="updated_by" id="id_updated_by" value="${response.modifier || ''}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_updated_by">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_updated_at" class="form-group row  fitem   ">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_updated_at">
                                    Ngày cập nhật
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="updated_at" id="id_updated_at" value="${response.updated_at}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_updated_at">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="availability_item" id="availability-item-${response.main_id}" class="cmid-must-complete" value="${str_availability_cmid}">
                    <input type="hidden" name="availability_folder" id="availability-folder-${response.main_id}" class="folfer-must-complete" value="">
                    <input type="hidden" name="change_availability" id="change-availability-${response.main_id}" value="0">
                    <input type="hidden" name="activity_cmid" id="activity-cmid" value="${response.moodle_id}">
                    <div class="mt-4 hide" style="text-align: center">
                        <a href="#" class="btn btn-primary btn-save-activity">Cập nhật</a>
                    </div>
                </div>
            </form>
        `;
    
        // Thêm nội dung vào parentElement
        parentElement.append(newItem);
        
        $("[data-toggle=popover]").popover();

        $('#id_completionexpected').on('change', function() {
            // Kiểm tra trạng thái của checkbox
            if ($(this).is(':checked')) {
                // Nếu checkbox được chọn, hiển thị 'grade-box'
                $('#completionexpected_starthour').prop('disabled', false);
                $('#completionexpected_startminute').prop('disabled', false);
                $('#completionexpected_date').prop('disabled', false);
            } else {
                // Nếu checkbox không được chọn, ẩn 'grade-box'
                $('#completionexpected_starthour').prop('disabled', true);
                $('#completionexpected_startminute').prop('disabled', true);
                $('#completionexpected_date').prop('disabled', true);
            }
        });

        // $('.btn-save-activity').on('click', function(event) {
        //     event.preventDefault();
        //     updateActivityUrl(response.main_id, selectedParentId, courseId, 'url')
        // });
        $('.btn-save').attr('data-type', response.moodle_type);
        $('.btn-save').attr('data-item-id', response.main_id);
        $('.btn-save').attr('data-course-id', courseId);
        $('.btn-save').attr('data-parent-id', selectedParentId);

        $('.showAvailabilityModal').on('click', function(event) {
            event.preventDefault();
            getDataAvailablity(response.main_id, response.parent_id);
        });

        $('#id_completion').on('change', function() {
            // Kiểm tra trạng thái của checkbox
            if ($(this).val() == 2) {
                // Nếu checkbox được chọn, hiển thị 'grade-box'
                $('.fitem_id_completionview').show();
                $('#fitem_id_completionexpected').show();
            } else {
                // Nếu checkbox không được chọn, ẩn 'grade-box'
                $('.fitem_id_completionview').hide();
                $('#fitem_id_completionexpected').hide();
            }
        });

        const $dropZone = $(".filemanager-container");
        const $fileManager = $(".filemanager.fm-loaded");
        // Ngăn chặn hành vi mặc định của các sự kiện
        $(document).on("dragenter dragover dragleave drop", function (e) {
            e.preventDefault();
            e.stopPropagation();
        });

        // Thêm class dndupload-ready khi người dùng bắt đầu kéo ảnh vào trang
        $(document).on("dragenter", function () {
            if (dragCounter === 0) { // Chỉ thêm class khi bắt đầu kéo vào trang
                clearTimeout(leaveTimer);
                $dropZone.addClass("dndupload-ready");
            }
            dragCounter++; // Tăng counter toàn cục
        });

        // Xóa class dndupload-ready khi rời khỏi toàn bộ trang
        $(document).on("dragleave", function () {
            dragCounter--;
            if (dragCounter === 0) { // Chỉ xóa khi không còn gì được kéo trên trang
                leaveTimer = setTimeout(() => {
                    $dropZone.removeClass("dndupload-ready");
                }, 100);
            }
        });

        // Thêm class dndupload-over khi ảnh vào phạm vi vùng thả
        $dropZone.on("dragenter", function () {
            dropZoneCounter++;
            if (dropZoneCounter === 1) { // Chỉ thêm class khi lần đầu vào vùng thả
                $dropZone.addClass("dndupload-over");
            }
        });

        // Xóa class dndupload-over khi ảnh rời khỏi vùng thả
        $dropZone.on("dragleave", function () {
            dropZoneCounter--;
            if (dropZoneCounter === 0) { // Chỉ xóa class khi hoàn toàn rời khỏi vùng thả
                $dropZone.removeClass("dndupload-over");
            }
        });

        // Bỏ cả dndupload-ready và dndupload-over khi ảnh được thả
        $dropZone.on("drop", function (e) {
            const $fileManager = $(".filemanager.fm-loaded");
            if ($fileManager.hasClass("fm-maxfiles")) {
                $dropZone.removeClass("dndupload-ready");
                alert("Đã đạt đến giới hạn file tối đa. Không thể thả thêm file vào.");
                return;
            }
            e.preventDefault();
            e.stopPropagation();
            $dropZone.removeClass("dndupload-ready dndupload-over");
            console.log("removeClass dndupload-ready dndupload-over");

            const files = e.originalEvent.dataTransfer.files;
            console.log(files);
            handleFiles(files);
            assignFilesToInput(files);
            // Xóa class fm-noitems khi ảnh được thả vào
            $fileManager.removeClass("fm-noitems");
            // console.log("removeClass fm-noitems");
            
            dragCounter = 0; // Reset counter khi thả file
        });

        const filesUploaded = response.filesuploaded;

        filesUploaded.forEach(fileData => {
            fileData.forEach(file => {
                handleFilesDetail([file]);
                $fileManager.removeClass("fm-noitems");
                $dropZone.removeClass("dndupload-ready dndupload-over");
            });
        });

        $('#image_course').on('change', function () {
            const files = this.files;
            if (files.length > 0) {
                console.log("Files have been assigned to the input file:", files);
                // Thực hiện xử lý upload hoặc các thao tác khác với files
            }
        });

        $(".fp-vb-icons").on("click", function() {
            $(this).addClass("checked");
            $(".fp-vb-details, .fp-vb-tree").removeClass("checked");
            updateView();
        });

        $(".fp-vb-details").on("click", function() {
            $(this).addClass("checked");
            $(".fp-vb-icons, .fp-vb-tree").removeClass("checked");
            updateView();
        });

        $(".fp-vb-tree").on("click", function() {
            $(this).addClass("checked");
            $(".fp-vb-icons, .fp-vb-details").removeClass("checked");
            updateView();
        });

        $('.fp-btn-delete a').on('click', function(e) {
            e.preventDefault();
            
            // uploadedFiles = [];
            // Lấy các checkbox đã được chọn
            var selectedIndexes = [];
            $('#table-image-upload tbody input[type="checkbox"]:checked').each(function() {
                var index = $(this).data('index'); 
                selectedIndexes.push(index);
            });
            
            var popupTitle = $('.delete-confirm-popup .popup-header h3');
            var popupMessage = $('.delete-confirm-popup .popup-body p');
            var confirmButton = $('.delete-confirm-popup .fp-dlg-butconfirm');
            var cancelButton = $('.delete-confirm-popup .fp-dlg-butcancel');

            if (selectedIndexes.length === 0) {
                popupTitle.text("Lỗi");
                popupMessage.text("Vui lòng chọn ít nhất một file để xóa.");
                confirmButton.off('click').on('click', function() {
                    $('.delete-confirm-popup').css('display', 'none');
                });
                cancelButton.hide();
            } else {
                popupTitle.text("Xác Nhận Xóa");
                popupMessage.text("Bạn có chắc muốn xóa những file đã được chọn?");
                confirmButton.off('click').on('click', function() {
                    deleteSelectedFiles(selectedIndexes); // Gọi hàm xóa file
                    $('.delete-confirm-popup').css('display', 'none');
                });
                cancelButton.show();
            }

            // Hiển thị popup
            $('.delete-confirm-popup').css('display', 'flex');
        });
    }

    function assignFilesToInput(files) {
        // Tạo một DataTransfer để gán file vào
        let dataTransfer = new DataTransfer();
        
        // Thêm các file được kéo vào DataTransfer
        Array.from(files).forEach(file => dataTransfer.items.add(file));
        
        // Gán DataTransfer vào input file
        const fileInput = document.getElementById('image_course');
        fileInput.files = dataTransfer.files;

        // Kích hoạt sự kiện change để các xử lý khác có thể nhận biết thay đổi này
        $(fileInput).trigger('change');
    }

    function dataURLToBlob(dataURL, mimeType, fileName) {
        try {
            // Tách phần header và dữ liệu
            const base64Data = dataURL.split(',')[1];
            const binaryData = atob(base64Data);
            const arrayBuffer = new Uint8Array(binaryData.length);
    
            // Tạo ArrayBuffer từ chuỗi nhị phân
            for (let i = 0; i < binaryData.length; i++) {
                arrayBuffer[i] = binaryData.charCodeAt(i);
            }
    
            // Tạo Blob từ ArrayBuffer
            const blob = new Blob([arrayBuffer], { type: mimeType });
    
            // Tạo đối tượng File từ Blob
            const file = new File([blob], fileName, { type: mimeType });
            return file;
        } catch (error) {
            console.error("Lỗi khi chuyển đổi Data URL thành Blob:", error);
            return null;
        }
    }

    function handleFilesDetail(files) {
        $.each(files, function (index, file) {
            // Kiểm tra trùng lặp
            const isDuplicate = uploadedFiles.some(uploadedFile => uploadedFile.name === file.name);
            
            if (isDuplicate) {
                // Hiển thị popup nếu file trùng
                showDuplicatePopup(file);
            } else {
                // Chuyển đổi đối tượng file JSON thành Blob
                const blob = dataURLToBlob(file.url, file.type, file.name);
                if (blob) {
                    processFile(blob);
                } else {
                    console.error("Không thể chuyển đổi URL thành Blob:", file);
                }
            }
        });
    }

    function handleFiles(files) {
        $.each(files, function (index, file) {
            const isDuplicate = uploadedFiles.some(uploadedFile => uploadedFile.name === file.name);
            if (isDuplicate) {
                // Hiển thị popup nếu file trùng
                showDuplicatePopup(file);
            } else {
                // Xử lý file nếu không trùng
                processFile(file);
            }
        });
    }
    
    function generateNewFileName(originalName) {
        // Lấy tên cơ bản và phần mở rộng
        const baseName = originalName.substring(0, originalName.lastIndexOf(".")) || originalName;
        const extension = originalName.substring(originalName.lastIndexOf(".")) || "";
    
        // Tìm tất cả các file trùng tên
        const matchingFiles = uploadedFiles.filter(file => 
            file.name.startsWith(baseName) && 
            file.name.endsWith(extension)
        );
    
        // Lọc các số thứ tự trong tên file (nếu có)
        let maxIndex = 0;
        matchingFiles.forEach(file => {
            const match = file.name.match(new RegExp(`^${baseName} \\((\\d+)\\)${extension.replace('.', '\\.')}$`));
            if (match && match[1]) {
                maxIndex = Math.max(maxIndex, parseInt(match[1], 10));
            }
        });
    
        // Tăng số thứ tự lên 1
        const newIndex = maxIndex + 1;
    
        // Trả về tên file mới
        return `${baseName} (${newIndex})${extension}`;
    }
    
    function showDuplicatePopup(file) {
        // Tạo tên file mới
        const newFileName = generateNewFileName(file.name);
    
        // Cập nhật nội dung popup
        $(".modal-body p").text(`Đã có một tập tin là ${file.name}`);
        $(".fp-dlg-butrename").text(`Đổi tên thành "${newFileName}"`);
    
        // Hiển thị modal
        $("#fileDuplicatePopup").modal('show');
    
        // Xử lý sự kiện nút "Ghi đè"
        $(".fp-dlg-butoverwrite").off("click").on("click", function () {
            uploadedFiles = uploadedFiles.filter(uploadedFile => uploadedFile.name !== file.name); // Ghi đè file cũ
            processFile(file);
            closePopup();
        });
    
        // Xử lý sự kiện nút "Đổi tên"
        $(".fp-dlg-butrename").off("click").on("click", function () {
            const renamedFile = renameFile(file, newFileName);
            processFile(renamedFile);
            closePopup();
        });
    
        // Xử lý sự kiện nút "Hủy bỏ"
        $(".fp-dlg-butcancel, .close").off("click").on("click", function () {
            closePopup();
        });
    }
    
    function renameFile(file, newName) {
        const renamedFile = new File([file], newName, { type: file.type });
        return renamedFile;
    }
    
    // Hàm xử lý file
    function processFile(file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            const fileData = {
                name: file.name,
                size: (file.size / 1024).toFixed(2) + " KB",
                type: file.type || "Không xác định",
                url: event.target.result,
                date: new Date().toLocaleString()
            };
            uploadedFiles.push(fileData);
    
            // Cập nhật giao diện
            updateView();
        };
        reader.readAsDataURL(file);
    }
    
    // Đóng popup
    function closePopup() {
        $('#fileDuplicatePopup').modal('hide');
    }

    function updateView() {
        const $fileManager = $(".filemanager.fm-loaded");
        // Kiểm tra nút nào đang active để xác định chế độ hiển thị
        if ($(".fp-vb-details").hasClass("checked")) {
            showTableView();
        } else if ($(".fp-vb-icons").hasClass("checked")) {
            showIconView();
        } else if ($(".fp-vb-tree").hasClass("checked")) {
            showTreeView();
        }
        if (uploadedFiles.length == 0) {
            $fileManager.removeClass("fm-maxfiles");
        }
    }

    function showTableView() {
        var imageUploadTable;
    
        // Khởi tạo bảng nếu chưa tồn tại hoặc xóa nội dung cũ nếu đã có
        if ($.fn.dataTable.isDataTable('#table-image-upload')) {
            imageUploadTable = $('#table-image-upload').DataTable();
            imageUploadTable.clear();
        } else {
            imageUploadTable = $('#table-image-upload').DataTable({
                "dom": '<"dt-buttons"lBf><"clear">rt',
                "paging": false,
                "autoWidth": true,
                "bLengthChange": false,
                "bFilter": false,
                "order": [[1, 'asc']],
                "columns": [
                    { "width": "5%", "orderable": false },
                    { "width": "30%" },
                    { "width": "20%" },
                    { "width": "20%" },
                    { "width": "25%" }
                ]
            });
        }
    
        $('#table-image-upload tbody').empty();
    
        $.each(uploadedFiles, function (index, file) {
            var fileSize = file.size;
            var fileType = file.type || "Không xác định";
            var fileName = file.name;
            var currentDate = new Date().toLocaleString();
            var fileIcon = getFileIcon(file); // Lấy biểu tượng tương ứng
    
            var row = `
                <tr>
                    <td><input type="checkbox" class="image-checkbox" data-index="${index}"></td>
                    <td>
                        <span class="fp-filename-icon fp-hascontextmenu">
                            <a href="#">
                                <span class="fp-icon">${fileIcon}</span>
                                <span class="fp-filename">${fileName}</span>
                            </a>
                            <a class="fp-contextmenu" href="#" onclick="return false;">
                                <i class="icon fa fa-ellipsis-v fa-fw" title="▶" aria-label="▶"></i>
                            </a>
                        </span>
                    </td>
                    <td>${currentDate}</td>
                    <td>${fileSize}</td>
                    <td>${fileType}</td>
                </tr>`;
            // Thêm hàng vào bảng
            imageUploadTable.row.add($(row)).draw();
        });
    
        $(".fp-tableview").removeClass("d-none");
        $(".fp-iconview, .fp-treeview").addClass("d-none");
    }
    
    // Hàm trả về biểu tượng tương ứng
    function getFileIcon(file) {
        const extension = file.name.split('.').pop().toLowerCase();
        const mimeType = file.type;
    
        if (mimeType.startsWith('image/')) {
            return `<img src="${file.url}" class="realpreview">`; // Hiển thị hình ảnh xem trước
        }
    
        switch (extension) {
            case 'pdf':
                return `<i class="fa-regular fa-file-pdf text-danger h3"></i>`;
            case 'doc':
            case 'docx':
                return `<i class="fa-regular fa-file-word text-primary h3"></i>`;
            case 'xls':
            case 'xlsx':
                return `<i class="fa-regular fa-file-excel text-success h3"></i>`;
            case 'ppt':
            case 'pptx':
                return `<i class="fa-regular fa-file-powerpoint text-warning h3"></i>`;
            case 'zip':
            case 'rar':
                return `<i class="fa-regular fa-file-zipper text-muted h3"></i>`;
            case 'txt':
                return `<i class="fa-regular fa-file-lines text-secondary h3"></i>`;
            case 'mp3':
            case 'wav':
                return `<i class="fa-regular fa-file-audio text-info h3"></i>`;
            case 'mp4':
            case 'avi':
            case 'mov':
                return `<i class="fa-regular fa-file-video text-info h3"></i>`;
            default:
                return `<i class="fa-regular fa-file text-muted h3"></i>`;
        }
    }
    
    // Hàm định dạng kích thước file
    function formatFileSize(size) {
        if (size < 1024) return size + " B";
        if (size < 1048576) return (size / 1024).toFixed(1) + " KB";
        if (size < 1073741824) return (size / 1048576).toFixed(1) + " MB";
        return (size / 1073741824).toFixed(1) + " GB";
    }

    // Hàm hiển thị chế độ icon view
    function showIconView() {
        const iconView = $(".fp-iconview");
        iconView.empty();

        $.each(uploadedFiles, function(index, file) {
            var fileIcon = getFileIcon(file);
            const iconItem = `
                <div class="fp-file fp-hascontextmenu">
                    <a href="#" class="d-block aabtn">
                        <div style="position:relative;">
                            <div class="fp-thumbnail" style="width: 112px; height: 112px;">
                                ${fileIcon}
                            </div>
                        </div>
                        <div class="fp-filename-field">
                            <div class="fp-filename text-truncate" style="width: 112px;">${file.name}</div>
                        </div>
                    </a>
                    <a class="fp-contextmenu btn btn-icon btn-light border icon-no-margin icon-size-3" href="#">
                        <span><i class="icon fa fa-ellipsis-v fa-fw" title="▶" aria-label="▶"></i></span>
                    </a>
                </div>`;
            iconView.append(iconItem);
        });
        $(".fp-iconview").removeClass("d-none");
        $(".fp-tableview, .fp-treeview").addClass("d-none");
    }

    // Hàm hiển thị chế độ tree view
    function showTreeView() {
        const treeView = $(".fp-treeview #ygtvc1");
        treeView.empty();

        $.each(uploadedFiles, function(index, file) {
            var fileIcon = getFileIcon(file);
            const treeItem = `
                <div class="ygtvitem" id="ygtv${index + 1}">
                    <table id="ygtvtableel2" border="0" cellpadding="0" cellspacing="0"
                        class="ygtvtable ygtvdepth1 ygtv-collapsed ygtv-highlight0 fp-hascontextmenu">
                        <tbody>
                            <tr class="ygtvrow">
                                <td class="ygtvcell ygtvblankdepthcell">
                                    <div class="ygtvspacer"></div>
                                </td>
                                <td class="ygtvcell ygtvln">
                                    <div class="ygtvspacer"></div>
                                </td>
                                <td id="ygtvcontentel2" class="ygtvcell ygtvhtml ygtvcontent"><span
                                        class="fp-filename-icon fp-hascontextmenu">
                                        <a href="#">
                                            <span class="fp-icon">${fileIcon}</span>
                                            <span class="fp-reficons1"></span>
                                            <span class="fp-reficons2"></span>
                                            <span class="fp-filename">${file.name}</span>
                                        </a>
                                        <a class="fp-contextmenu" href="#" onclick="return false;"><i
                                                class="icon fa fa-ellipsis-v fa-fw " title="▶"
                                                aria-label="▶"></i></a>
                                    </span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="ygtvchildren" id="ygtvc2" style="display:none;"></div>
                </div>`;
            treeView.append(treeItem);
        });
        $(".fp-treeview").removeClass("d-none");
        $(".fp-tableview, .fp-iconview").addClass("d-none");
    }

    function deleteSelectedFiles(selectedIndexes) {
        const $fileManager = $(".filemanager.fm-loaded");
        // Sắp xếp mảng selectedIndexes theo thứ tự giảm dần
        selectedIndexes.sort((a, b) => b - a);
        console.log(selectedIndexes, uploadedFiles);

        // Xóa file từ mảng uploadedFiles
        selectedIndexes.forEach(function(index) {
            uploadedFiles.splice(index, 1); // Xóa file tại vị trí index trong mảng
        });

        // Cập nhật bảng dữ liệu
        var imageUploadTable = $('#table-image-upload').DataTable();
        imageUploadTable.clear(); // Xóa tất cả các hàng hiện có

        // Thêm lại tất cả các hình ảnh còn lại vào bảng
        uploadedFiles.forEach(function(image, index) {
            var fileIcon = getFileIcon(image); // Lấy biểu tượng tương ứng
            console.log(image);
            var row = `
                <tr>
                    <td><input type="checkbox" class="image-checkbox" data-index="${index}"></td>
                    <td>
                        <span class="fp-filename-icon fp-hascontextmenu">
                            <a href="#">
                                <span class="fp-icon">${fileIcon}</span>
                                <span class="fp-filename">${image.name}</span>
                            </a>
                            <a class="fp-contextmenu" href="#" onclick="return false;">
                                <i class="icon fa fa-ellipsis-v fa-fw" title="▶" aria-label="▶"></i>
                            </a>
                        </span>
                    </td>
                    <td>${image.date}</td>
                    <td>${image.size}</td>
                    <td>${image.type}</td>
                </tr>`;
            
            imageUploadTable.row.add($(row));
        });

        imageUploadTable.draw(); // Vẽ lại bảng

        // Bỏ chọn ô header sau khi xóa
        $('#table-image-upload thead input[type="checkbox"]').prop('checked', false);

        if(uploadedFiles.length == 0){
            $fileManager.removeClass("fm-maxfiles");
            $fileManager.addClass("fm-noitems");
        }

        document.getElementById('image_course').value = '';
    }

    // Khi click ra ngoài popup
    $(window).on('click', function(event) {
        if ($(event.target).is('#delete-confirm-popup')) {
            $('#delete-confirm-popup').hide(); // Ẩn popup nếu click ra ngoài
        }
    });

    function loadImageUploadTable(file) {
        var imageUploadTable;
        const $fileManager = $(".filemanager.fm-loaded");
        // Khởi tạo bảng nếu chưa tồn tại hoặc xóa nội dung cũ nếu đã có
        if ($.fn.dataTable.isDataTable('#table-image-upload')) {
            imageUploadTable = $('#table-image-upload').DataTable();
        } else {
            imageUploadTable = $('#table-image-upload').DataTable({
                "dom": '<"dt-buttons"lBf><"clear">rt',
                "paging": false,
                "autoWidth": true,
                "bLengthChange": false,
                "bFilter": false,
                "order": [[1, 'asc']],
                "columns": [
                    { "width": "5%", "orderable": false },
                    { "width": "30%" },
                    { "width": "20%" },
                    { "width": "20%" },
                    { "width": "25%" }
                ]
            });
        }

        $('#table-image-upload tbody').empty();

        var fileSize = (file.size / 1024).toFixed(2) + " KB";
        var fileType = file.type || "Không xác định";
        var fileName = file.name;
        var currentDate = new Date().toLocaleString();

        var reader = new FileReader();
        reader.onload = function(event) {
            // Lưu thông tin ảnh vào mảng
            var imageIndex = uploadedFiles.length;
            uploadedFiles.push({
                name: fileName,
                size: fileSize,
                type: fileType,
                url: event.target.result,
                date: currentDate
            });

            // Tạo hàng mới với dữ liệu của file và thêm data-index cho checkbox
            var row = `
                <tr>
                    <td><input type="checkbox" class="image-checkbox" data-index="${imageIndex}"></td>
                    <td>
                        <span class="fp-filename-icon fp-hascontextmenu">
                            <a href="#">
                                <span class="fp-icon"><img src="${event.target.result}" class="realpreview"></span>
                                <span class="fp-filename">${fileName}</span>
                            </a>
                            <a class="fp-contextmenu" href="#" onclick="return false;">
                                <i class="icon fa fa-ellipsis-v fa-fw" title="▶" aria-label="▶"></i>
                            </a>
                        </span>
                    </td>
                    <td>${currentDate}</td>
                    <td>${fileSize}</td>
                    <td>${fileType}</td>
                </tr>`;

            // Thêm hàng vào bảng
            imageUploadTable.row.add($(row)).draw();
        };

        $fileManager.addClass("fm-maxfiles");

        reader.readAsDataURL(file);
    }

    $(document).on('click', '.fp-btn-download a', function(e) {
        e.preventDefault();

        // Lọc các checkbox đã được chọn
        var selectedImages = [];
        $('#table-image-upload .image-checkbox:checked').each(function() {
            var index = $(this).data('index');
            if (uploadedFiles[index]) {
                selectedImages.push(uploadedFiles[index]);
            }
        });

        if (selectedImages.length > 0) {
            // Nếu có checkbox được chọn, tải xuống các ảnh đã chọn
            selectedImages.forEach(function(image) {
                var link = document.createElement('a');
                link.href = image.url;
                link.download = image.name;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        } else {
            // Nếu không có checkbox nào được chọn, tạo file ZIP chứa tất cả các ảnh
            var zip = new JSZip();
            var imgFolder = zip.folder("images");

            uploadedFiles.forEach(function(image) {
                var base64Data = image.url.split(',')[1];
                imgFolder.file(image.name, base64Data, {base64: true});
            });

            // Tạo file ZIP và tải xuống
            zip.generateAsync({type: "blob"}).then(function(content) {
                var link = document.createElement('a');
                link.href = URL.createObjectURL(content);
                link.download = "images.zip";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        }
    });

    function base64ToFile(base64String, filename, mimeType) {
        const byteCharacters = atob(base64String.split(',')[1]); // Tách phần base64
        const byteArrays = [];
    
        for (let offset = 0; offset < byteCharacters.length; offset++) {
            byteArrays.push(byteCharacters.charCodeAt(offset));
        }
    
        const byteArray = new Uint8Array(byteArrays);
        return new File([byteArray], filename, { type: mimeType });
    }

    function updateActivityResource(activity_id, selectedParentId, courseId, type) {
        const clickedCategoryId = selectedParentId;
        const currentUser = localStorage.getItem('currentUser');

        const formData = new FormData();
        formData.append('resource_name', $("#resource_name").val());
        formData.append('resource_section', $("#resource-parent").val());
        formData.append('resource_visible', $("#resource_status").val());
        formData.append('completion', $("#id_completion_resource").val());
        formData.append('completionview', $("#id_completionview_resource").is(":checked") ? 1 : 0);
        formData.append('availability_item', $("#availability-item-" + activity_id).val());
        formData.append('section_id', selectedParentId);
        formData.append('course_id', courseId);
        formData.append('type', type);
        formData.append('currentUser', currentUser);
        formData.append('activity_id', activity_id);

        // Thêm các tệp đã tải lên vào FormData
        if (uploadedFiles && uploadedFiles.length > 0) {
            uploadedFiles.forEach((fileData, index) => {
                const file = base64ToFile(fileData.url, fileData.name, fileData.type);
                formData.append(`files[${index}]`, file);
            });
        }
        $.ajax({
            url: "/api/lms/activity/update",
            type: "POST",
            data: formData,
            processData: false, // Quan trọng: Không xử lý dữ liệu FormData
            contentType: false, // Quan trọng: Đặt nội dung là false để tự động thêm header multipart/form-data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                loadSectionData(courseId, selectedParentId);
                if (response.success) {
                    $('.message-product .alert').addClass('alert-success');
                    $('.message-product .alert').removeClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.success);
                } else {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                }
                uploadedFiles = [];
                $('.message-product').removeClass('hidden');
                $('html, body').animate({ scrollTop: 0 }, 'fast');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function updateActivityAssign(activity_id, selectedParentId, courseId, type) {
        const clickedCategoryId = selectedParentId;
        const currentUser = localStorage.getItem('currentUser');

        const formData = new FormData();
        formData.append('assign_name', $("#assign_name").val());
        formData.append('assign_section', $("#assign-parent").val());
        formData.append('assign_intro', tinymce.get('assign_intro').getContent());
        formData.append('assign_visible', $("#assign_status").val());
        formData.append('assignsubmission_onlinetext', $("#id_assignsubmission_onlinetext_enabled").is(":checked") ? 1 : 0);
        formData.append('assignsubmission_file', $("#id_assignsubmission_file_enabled").is(":checked") ? 1 : 0);
        formData.append('assignsubmission_file_filetypes', $("#id_assignsubmission_file_filetypes").val());
        formData.append('assign_grade', $(".i-grade-assign").val());
        formData.append('completion', $("#assign_completion").val());
        formData.append('completionview', $("#assign_completionview").is(":checked") ? 1 : 0);
        formData.append('completionsubmit', $("#assign_completionsubmit").is(":checked") ? 1 : 0);
        formData.append('completionusegrade', $("#assign_completionusegrade").is(":checked") ? 1 : 0);
        formData.append('gradePass', $("#assign_grade_pass").val());
        formData.append('availability_item', $("#availability-item-" + activity_id).val());
        formData.append('section_id', selectedParentId);
        formData.append('course_id', courseId);
        formData.append('type', type);
        formData.append('currentUser', currentUser);
        formData.append('activity_id', activity_id);

        // Thêm các tệp đã tải lên vào FormData
        if (uploadedFiles && uploadedFiles.length > 0) {
            uploadedFiles.forEach((fileData, index) => {
                const file = base64ToFile(fileData.url, fileData.name, fileData.type);
                formData.append(`files[${index}]`, file);
            });
        }
        $.ajax({
            url: "/api/lms/activity/update",
            type: "POST",
            data: formData,
            processData: false, // Quan trọng: Không xử lý dữ liệu FormData
            contentType: false, // Quan trọng: Đặt nội dung là false để tự động thêm header multipart/form-data
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                loadSectionData(courseId, selectedParentId);
                if (response.success) {
                    $('.message-product .alert').addClass('alert-success');
                    $('.message-product .alert').removeClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.success);
                } else {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                }
                uploadedFiles = [];
                $('.message-product').removeClass('hidden');
                $('html, body').animate({ scrollTop: 0 }, 'fast');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    //end upload file

    $('.above-block').on('click', '.fa-trash', function(e) {
        e.stopPropagation(); // Ngăn không cho sự kiện click bong bóng ra ngoài
        const type = $(this).attr('data-type');
        if(type == "course_sections"){
            deleteSection(this);
        }else{
            deleteModule(this);
        }
    });

    //delete Section
    function deleteSection(button){
        var section_id = $(button).attr("data-section-id");
        var course_id = $(button).attr("data-course-id");
        var id = $(button).attr("data-id");
        var name = $(button).attr("data-name");
        var type = $(button).attr("data-type");
        $('.popup_modal_icon_action_delete .this_item_name').text(name);
            
        // Gán ID và loại sản phẩm vào các nút delete và archive trong modal
        $('.popup_modal_icon_action_delete .delete-this-item').attr({
            'data-section-id': '',
            'data-course-id': '',
            'data-id': '',
            'data-cmid': '',
            'data-parent-id': '',
            'data-type': ''
        });

        // Gán giá trị mới cho các thuộc tính data-* của nút delete
        $('.popup_modal_icon_action_delete .delete-this-item').attr({
            'data-section-id': section_id,
            'data-course-id': course_id,
            'data-id': id,
            'data-type': type
        });
            
        // Hiển thị modal
        $('.popup_modal_icon_action_delete').modal('show');
    }
    function callAjaxDeleteSection(button){
        var section_id = $(button).attr("data-section-id");
        var course_id = $(button).attr("data-course-id");
        var id = $(button).data("id");
        $.ajax({
            url: "/api/lms/section/delete",
            type: "POST",
            data: {
                section_id: section_id,
                course_id: course_id,
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.error) {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                    $('.message-product').removeClass('hidden');
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                }else{
                    loadSectionData(courseId);
                }
                $('.popup_modal_icon_action_delete').modal('hide');
            },
            error: function(xhr, status, error) {
            }
        });
    }
    //end delete section

    //delete module
    function deleteModule(button){
        var cmid = $(button).attr("data-cmid");
        var parent_id = $(button).attr("data-parent-id");
        var name = $(button).attr("data-name");
        var type = $(button).attr("data-type");
        $('.popup_modal_icon_action_delete .this_item_name').text(name);
            
        // Gán ID và loại sản phẩm vào các nút delete và archive trong modal
        $('.popup_modal_icon_action_delete .delete-this-item').attr({
            'data-section-id': '',
            'data-course-id': '',
            'data-id': '',
            'data-cmid': '',
            'data-parent-id': '',
            'data-type': ''
        });

        // Gán giá trị mới cho các thuộc tính data-* của nút delete
        $('.popup_modal_icon_action_delete .delete-this-item').attr({
            'data-cmid': cmid,
            'data-parent-id': parent_id,
            'data-type': type
        });
            
        // Hiển thị modal
        $('.popup_modal_icon_action_delete').modal('show');
        
    }

    function callAjaxDeleteModule(button){
        var cmid = $(button).attr('data-cmid');
        var parent_id = $(button).attr('data-parent-id');
        $.ajax({
            url: "/api/lms/activity/delete",
            type: "POST",
            data: {
                cmid: cmid,
                parent_id: parent_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.error) {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                    $('.message-product').removeClass('hidden');
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                }else{
                    loadSectionData(courseId);
                }
                
                $('.popup_modal_icon_action_delete').modal('hide');
            },
            error: function(xhr, status, error) {
            }
        });
    }
    //end delete module

    $('.delete-this-item').on('click', function () {
        // Lấy thông tin từ data-attribute của nút
        const type = $(this).attr('data-type');
        deleteSectionOrModule(this, type);
    });

    function deleteSectionOrModule(button, type){
        if(type == "course_sections"){
            callAjaxDeleteSection(button);
        }else{
            callAjaxDeleteModule(button);
        }
    }

    function renderTemplateAssign(response, selectedParentId, sectionData, str_availability_cmid){
        var parentElement = $('#wrapper-dynamic-assign-form');
        parentElement.empty();

        console.log(response, selectedParentId, sectionData, str_availability_cmid);

        var parentSelectHTML = '';
        parentSelectHTML = `
            <div id="fitem_assign-parent" class="form-group row  fitem   ">
                <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                    <label class="d-inline word-break " for="assign-parent">
                        Thư mục cha
                    </label>
                    <div class="form-label-addon d-flex align-items-center align-self-start">
                    </div>
                </div>
                <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                    <select class="w-100 custom-select" name="assign-parent" id="assign-parent">
                        ${sectionData.map(section => {
                            // Kiểm tra nếu section id bằng với selectedParentId thì thêm selected
                            const isSelected = section.id === selectedParentId ? 'selected' : '';
                            return `<option value="${section.id}" ${isSelected}>${section.moodle_name}</option>`;
                        }).join('')}
                    </select>
                    <div class="form-control-feedback invalid-feedback" id="id_error_assign-parent">
                    </div>
                </div>
            </div>
        `;

        const completionexpected = response.completionexpected; //output: 1732936920

        var newItem = `
            <form data-random-ids="1" autocomplete="off" action="#" method="post" accept-charset="utf-8" id="mform1_jSAfylBsza9VpuD" class="mform" data-boost-form-errors-enhanced="1">
                <div class="left-side-content mt-2">
                    <div class="preview-url d-none"></div>
                    <div class="preview-setting">
                        <div class="text-left"><h4 class="mb-0">Miêu tả</h4></div>
                        <div id="fitem_id_introeditor" class="form-group row fitem">
                            <div class="form-inline align-items-start felement col-md-12" data-fieldtype="editor">
                                <textarea id="assign_intro" name="assign_intro" class="tinymce-textarea" cols="70" rows="3">${response.intro}</textarea>
                                <div class="form-control-feedback invalid-feedback" id="id_error_introeditor"></div>
                            </div>
                        </div>
                        <div class="text-left"><h4 class="mb-0">File bổ sung</h4></div>
                        <div id="fitem_id_overviewfiles_filemanager" class="form-group row fitem">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <p id="id_overviewfiles_filemanager_label" class="mb-0 d-inline" aria-hidden="true" style="cursor: default;">
                                    Hinh ảnh của khóa học
                                </p>
                    
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                    
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="filemanager">
                                <fieldset class="w-100 m-0 p-0 border-0" id="id_overviewfiles_filemanager_fieldset">
                                    <legend class="sr-only">Hinh ảnh của khóa học</legend>
                                    <div id="filemanager-671dac14e74d7" class="filemanager w-100 fm-loaded fm-nomkdir fm-nofiles fm-noitems">
                                        <div class="fp-restrictions">
                                            <span>Kích thước tối đa với một tập tin Không giới hạn, số lượng tập tin đính kèm tối đa:1</span>
                                            <span class="dnduploadnotsupported-message"> - Kéo thả không được hỗ trợ<a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>Nếu có nhiều tệp trong thư mục, tệp chính là cái xuất hiện trên trang hiển thị. Các tệp khác như hình ảnh hay phim có thể được nhúng bên trong. Trong trình quản lí tệp tệp chính được chỉ dẫn với tiêu đề in đậm.</p>
                                            </div> " data-html="true" tabindex="0" data-trigger="focus">
                                                <i class="icon fa fa-question-circle text-info fa-fw " title="Trợ giúp về Đặt tập tin chính" aria-label="Trợ giúp về Đặt tập tin chính"></i>
                                            </a></span>
                                        </div>
                                        <div class="fp-navbar bg-faded card mb-0">
                                            <div class="filemanager-toolbar icon-no-spacing">
                                                <div class="fp-toolbar">
                                                    <div class="fp-btn-add">
                                                        <a role="button" title="Thêm..." class="btn btn-secondary btn-sm" href="#">
                                                            <i class="icon fa-regular fa-file fa-fw " aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                    <div class="fp-btn-mkdir">
                                                        <a role="button" title="Tạo thư mục" class="btn btn-secondary btn-sm" href="#">
                                                            <i class="icon fa fa-folder-o fa-fw " aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                    <div class="fp-btn-download">
                                                        <a role="button" title="Tải" class="btn btn-secondary btn-sm" href="#">
                                                            <i class="icon fa fa-download fa-fw " aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                    <div class="fp-btn-delete">
                                                        <a role="button" title="Xoá" class="btn btn-secondary btn-sm" href="#">
                                                            <i class="icon fa fa-trash fa-fw " aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                    <span class="fp-img-downloading">
                                                        <span class="sr-only">Đang tải...</span>
                                                        <i class="icon fa fa-circle-o-notch fa-spin fa-fw " aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <div class="fp-viewbar btn-group float-sm-right">
                                                    <a title="Hiển thị thư mục với biểu tượng tệp" class="fp-vb-icons btn btn-secondary btn-sm">
                                                        <i class="icon fa fa-th fa-fw " aria-hidden="true"></i>
                                                    </a>
                                                    <a title="Hiển thi thư mục với chi tiết tệp" class="fp-vb-details btn btn-secondary btn-sm checked">
                                                        <i class="icon fa fa-list fa-fw " aria-hidden="true"></i>
                                                    </a>
                                                    <a title="Hiển thị thư mục như cây tập tin" class="fp-vb-tree btn btn-secondary btn-sm">
                                                        <i class="icon fa fa-folder fa-fw " aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="fp-pathbar"><span class="fp-path-folder first last odd"><a class="fp-path-folder-name aalink" href="#">Tập tin</a></span></div>
                                        </div>
                                        <div class="filemanager-loading mdl-align"><i class="icon fa fa-circle-o-notch fa-spin fa-fw " aria-hidden="true"></i><span class="sr-only">Đang tải...</span></div>
                                        <div class="filemanager-container card">
                                            <div class="fm-content-wrapper">
                                                <div class="fp-content">
                                                    <div class="fp-tableview">
                                                        <div class="yui3-widget yui3-datatable yui3-datatable-sortable">
                                                            <div class="yui3-datatable-content">
                                                                <table cellspacing="0" class="yui3-datatable-table" id="table-image-upload" style="width: 100%;">
                                                                    <thead class="yui3-datatable-columns">
                                                                        <tr>
                                                                            <th colspan="1" rowspan="1" class="yui3-datatable-header yui3-datatable-first-header scope="col">
                                                                                <label class="sr-only">Chọn tất cả/không chọn gì</label>
                                                                                <input type="checkbox" data-action="toggle" data-toggle="master" data-togglegroup="file-selections">
                                                                            </th>
                                                                            <th colspan="1" rowspan="1" class="yui3-datatable-header yui3-datatable-col-displayname yui3-datatable-sortable-column" scope="col" data-yui3-col-id="displayname" title="Sort by Tên">
                                                                                <div class="yui3-datatable-sort-liner" tabindex="0" unselectable="on">
                                                                                    Tên
                                                                                    <span class="yui3-datatable-sort-indicator"></span>
                                                                                </div>
                                                                            </th>
                                                                            <th colspan="1" rowspan="1" class="yui3-datatable-header yui3-datatable-col-datemodified yui3-datatable-sortable-column" scope="col" data-yui3-col-id="datemodified" title="Sort by Sửa lần cuối">
                                                                                <div class="yui3-datatable-sort-liner" tabindex="0" unselectable="on">
                                                                                    Sửa lần cuối
                                                                                    <span class="yui3-datatable-sort-indicator"></span>
                                                                                </div>
                                                                            </th>
                                                                            <th colspan="1" rowspan="1" class="yui3-datatable-header yui3-datatable-col-size yui3-datatable-sortable-column" scope="col" data-yui3-col-id="size" title="Sort by Kích thước">
                                                                                <div class="yui3-datatable-sort-liner" tabindex="0" unselectable="on">
                                                                                    Kích thước
                                                                                    <span class="yui3-datatable-sort-indicator"></span>
                                                                                </div>
                                                                            </th>
                                                                            <th colspan="1" rowspan="1" class="yui3-datatable-header yui3-datatable-col-mimetype yui3-datatable-sortable-column" scope="col" data-yui3-col-id="mimetype" title="Sort by Loại">
                                                                                <div class="yui3-datatable-sort-liner" tabindex="0" unselectable="on">
                                                                                    Loại<span class="yui3-datatable-sort-indicator"></span>
                                                                                </div>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <!-- <tbody class="yui3-datatable-message">
                                                                        <tr>
                                                                            <td class="yui3-datatable-message-content" colspan="5"></td>
                                                                        </tr>
                                                                    </tbody> -->
                                                                    <tbody class="yui3-datatable-data">
                                                                        
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="fp-iconview d-none">
                                                    </div>
                                                    <div class="fp-treeview d-none">
                                                        <div class="ygtvitem" id="ygtv0">
                                                            <div class="ygtvchildren" id="ygtvc0">
                                                                <div class="ygtvitem" id="ygtv1">
                                                                    <table id="ygtvtableel1" border="0" cellpadding="0" cellspacing="0"
                                                                        class="ygtvtable ygtvdepth0 ygtv-expanded ygtv-highlight0 fp-folder">
                                                                        <tbody>
                                                                            <tr class="ygtvrow">
                                                                                <td id="ygtvt1" class="ygtvcell ygtvlm">
                                                                                    <a href="#" class="ygtvspacer">&nbsp;</a>
                                                                                </td>
                                                                                <td id="ygtvcontentel1" class="ygtvcell ygtvhtml ygtvcontent">
                                                                                    <span
                                                                                        class="fp-filename-icon fp-folder">
                                                                                        <a href="#">
                                                                                            <span class="fp-icon"><img
                                                                                                    src="https://english.ican.vn/classroom/theme/image.php/mb2cg/core/1706779039/f/folder-24"></span>
                                                                                            <span class="fp-reficons1"></span>
                                                                                            <span class="fp-reficons2"></span>
                                                                                            <span class="fp-filename">Tập tin</span>
                                                                                        </a>
                                                                                        <a class="fp-contextmenu" href="#" onclick="return false;">
                                                                                            <i
                                                                                                class="icon fa fa-ellipsis-v fa-fw " title="▶" aria-label="▶"></i>
                                                                                            </a>
                                                                                    </span>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div class="ygtvchildren" id="ygtvc1">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fm-empty-container">
                                                    <div class="dndupload-message">Thêm các tập tin bằng cách kéo thả.<br>
                                                        <div class="dndupload-arrow"></div>
                                                    </div>
                                                </div>
                                                <div class="dndupload-target">Tải các tập tin lên bằng việc thả chúng vào đây<br>
                                                    <div class="dndupload-arrow"></div>
                                                </div>
                                                <div class="dndupload-progressbars"></div>
                                                <div class="dndupload-uploadinprogress"><i class="icon fa fa-circle-o-notch fa-spin fa-fw " aria-hidden="true"></i><span class="sr-only">Đang tải...</span></div>
                                            </div>
                                            <div class="filemanager-updating"><i class="icon fa fa-circle-o-notch fa-spin fa-fw " aria-hidden="true"></i><span class="sr-only">Đang tải...</span></div>
                                        </div>
                                    </div>
                                    <noscript>
                                        <div>
                                            <object type='text/html' data='https://english.ican.vn/classroom/repository/draftfiles_manager.php?env=filemanager&amp;action=browse&amp;itemid=116273913&amp;subdirs=0&amp;maxbytes=-1&amp;areamaxbytes=-1&amp;maxfiles=1&amp;ctx_id=1&amp;course=1&amp;sesskey=elt8O8b6jE' height='160' width='600' style='border:1px solid #000'></object>
                                        </div>
                                    </noscript>
                                    <input value="116273913" name="overviewfiles_filemanager" type="hidden" id="id_overviewfiles_filemanager">
                                    <input name="image_course" class="hidden" type="file" id="image_course">
                                </fieldset>
                                <div class="form-control-feedback invalid-feedback" id="id_error_overviewfiles_filemanager">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-side-content mt-2 hide">
                    <div class="box-head">
                        <h4>Thông tin chung</h4>
                    </div>
                    <div id="fitem_assign_name" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="assign_name">
                                Tên bài tập
                            </label>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                                <div class="text-danger" title="Bắt buộc">
                                    <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Bắt buộc" aria-label="Bắt buộc"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                            <input type="text" class="form-control " name="assign_name" id="assign_name" value="${response.moodle_name}" size="48">
                            <div class="form-control-feedback invalid-feedback" id="id_error_name">
                            </div>
                        </div>
                    </div>
                    ${parentSelectHTML}
                    <div id="fitem_assign_status" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="assign_status">
                                Trạng thái
                            </label>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                            <select class="custom-select" name="assign_status" id="assign_status" style="width: 100% !important">
                                <option ${response.visible == 0 ? 'selected' : ''} value="0">Ẩn</option>
                                <option ${response.visible == 1 ? 'selected' : ''} value="1">Hiện</option>
                            </select>
                            <div class="form-control-feedback invalid-feedback" id="id_error_visible">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <h4><b>Loại bài nộp</b></h4>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break">
                                Loại bài nộp
                            </label>
                        </div>
                        <div class="col-9 assignsubmission_type">
                            <div class="form-group row  fitem  ">
                                <div class="">
                                </div>
                                <div class="col-md-9 checkbox">
                                    <div class="form-check d-flex">
                                        <input type="checkbox" ${response.onlinetext == '1' ? 'checked' : ''} name="assignsubmission_onlinetext_enabled" class="form-check-input " value="1" id="id_assignsubmission_onlinetext_enabled">
                                        <label for="id_assignsubmission_onlinetext_enabled">
                                            Văn bản trực tuyến
                                        </label>
                                        <div class="ml-2 d-flex align-items-center align-self-start">
                                        </div>
                                    </div>
                                    <div class="form-control-feedback invalid-feedback" id="id_error_assignsubmission_onlinetext_enabled">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row  fitem  ">
                                <div class="">
                                </div>
                                <div class="col-md-9 checkbox">
                                    <div class="form-check d-flex">
                                        <input type="checkbox" ${response.file == '1' ? 'checked' : ''} name="assignsubmission_file_enabled" class="form-check-input " value="1" id="id_assignsubmission_file_enabled">
                                        <label for="id_assignsubmission_file_enabled">
                                            File bài làm
                                        </label>
                                        <div class="ml-2 d-flex align-items-center align-self-start">
                                        </div>
                                    </div>
                                    <div class="form-control-feedback invalid-feedback" id="id_error_assignsubmission_file_enabled">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="fitem_fgroup_id_assignsubmission_file_filetypes" class="form-group row  fitem   assignsubmission-file-filetypes" data-groupname="assignsubmission_file_filetypes" ${response.file == 1 ? '' : 'style="display: none;"'}>
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <p id="fgroup_id_assignsubmission_file_filetypes_label" class="mb-0 word-break" aria-hidden="true" style="cursor: default;">
                                Các loại tập tin được chấp nhận
                            </p>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="filetypes">
                            <fieldset class="w-100 m-0 p-0 border-0">
                                <legend class="sr-only">Các loại tập tin được chấp nhận</legend>
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="form-group  fitem  " style="">
                                        <label class="col-form-label sr-only" for="id_assignsubmission_file_filetypes" style="">
                                            Các loại tập tin được chấp nhận
                                        </label>
                                        <span data-fieldtype="text">
                                            <input type="text" class="form-control " name="assignsubmission_file_filetypes[filetypes]" id="id_assignsubmission_file_filetypes" value="${response.filetypeslist || '*'}" placeholder="">
                                        </span>
                                        <div class="form-control-feedback invalid-feedback" id="id_error_assignsubmission_file_filetypes">
                                        </div>
                                    </div>
                                    &nbsp;
                                    <span data-filetypesbrowser="id_assignsubmission_file_filetypes">
                                        <input type="button" class="btn btn-secondary showFileTypeModal" data-filetypeswidget="browsertrigger" value="Chọn">
                                    </span>
                                    &nbsp;
                                    <div data-filetypesdescriptions="id_assignsubmission_file_filetypes"><div class="form-filetypes-descriptions w-100">
                                        <ul class="list-unstyled unstyled">
                                            <li>All file types <small class="text-muted muted"></small></li>
                                        </ul>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-control-feedback invalid-feedback" id="fgroup_id_error_assignsubmission_file_filetypes">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <h5><b>Điểm</b></h5>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-3">
                            <label class="d-inline word-break">
                                Thang điểm
                            </label>
                        </div>
                        <div class="col-9">
                            <input class="i-grade-assign w-100" min="0" type="number" name="gradeAssign" value="${response.grade || '0'}">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <h4><b>Hoạt động hoàn thành</b></h4>
                        </div>
                    </div>
                    <div id="fitem_id_activitycompletionheader" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <span class="d-inline-block ">
                                Hoàn thành các hoạt động
                            </span>
                            <div class="form-label-addon d-flex align-items-center align-self-start">
                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement pt-2" data-fieldtype="static">
                            <div class="form-control-static">
                                <a href="javascript:void(0);" class="btn btn-secondary showAvailabilityModal" data-cmid="57749" data-instance="45960" data-type="url">Chọn</a>
                            </div>
                            <div class="form-control-feedback invalid-feedback" id="id_error_activitycompletionheader">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-3">
                            <label class="d-inline word-break">
                                Completion tracking
                            </label>
                        </div>
                        <div class="col-9">
                            <select class="w-100" name="completion" id="assign_completion">
                                <option ${response.completion == '0' ? 'selected' : ''} value="0">Không chỉ rõ việc hoàn thành hoạt động</option>
                                <option ${response.completion == '2' ? 'selected' : ''} value="2">Khi các điều kiện được thỏa mãn, đánh dấu hoạt động như là đã hoàn thành</option>
                            </select>
                            <div class="form-check form-check-completionview" ${response.completion == 2 ? '' : 'style="display: none;"'}>
                                <input class="form-check-input" type="checkbox" style="width: auto" name="completionview" id="assign_completionview" value="1" ${response.completionview == 1 ? 'checked' : ''}>
                                <label class="form-check-label" for="completionview">Học viên phải xem hoạt động này để hoàn thành nó</label>
                            </div>
                            <div class="form-check form-check-completionsubmit" ${response.completion == 2 ? '' : 'style="display: none;"'}>
                                <input class="form-check-input" type="checkbox" style="width: auto" name="completionsubmit" id="assign_completionsubmit" value="1" ${response.completionsubmit == 1 ? 'checked' : ''}>
                                <label class="form-check-label" for="completionsubmit">Học viên phải nộp bài để hoàn thành bài tập</label>
                            </div>
                            <div class="form-check form-check-completionusegrade" ${response.completion == 2 ? '' : 'style="display: none;"'}>
                                <input class="form-check-input" type="checkbox" style="width: auto" name="completionusegrade" id="assign_completionusegrade" value="1" ${response.completionpassgrade == 1 ? 'checked' : ''}>
                                <label class="form-check-label" for="assign_completionusegrade">Học viên phải nhận điểm số để hoàn thành hoạt động này</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 grade-box" ${response.completionpassgrade == 1 ? '' : 'style="display: none;"'}>
                        <div class="col-5">
                            <label class="d-inline word-break">
                                Điểm để qua
                            </label>
                        </div>
                        <div class="col-7"><input class="w-100" min="0" type="number" id="assign_grade_pass" name="gradePass" value="${response.gradepass || '0'}"></div>
                    </div>
                    <div class="box-head">
                        <h4>Lịch sử</h4>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_created_by" class="form-group row  fitem   ">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_created_by">
                                    Người tạo
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="created_by" id="id_created_by" value="${response.creator || ''}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_created_by">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_created_at" class="form-group row  fitem">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_created_at">
                                    Ngày tạo
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="created_at" id="id_created_at" value="${response.created_at}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_created_at">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_updated_by" class="form-group row  fitem   ">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_updated_by">
                                    Người cập nhật
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="updated_by" id="id_updated_by" value="${response.modifier || ''}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_updated_by">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div id="fitem_id_updated_at" class="form-group row  fitem   ">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break " for="id_updated_at">
                                    Ngày cập nhật
                                </label>
                                <div class="form-label-addon d-flex align-items-center align-self-start">
                                </div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control " name="updated_at" id="id_updated_at" value="${response.updated_at}" disabled="1">
                                <div class="form-control-feedback invalid-feedback" id="id_error_updated_at">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="availability_item" id="availability-item-${response.main_id}" class="cmid-must-complete" value="${str_availability_cmid}">
                    <input type="hidden" name="availability_folder" id="availability-folder-${response.main_id}" class="folfer-must-complete" value="">
                    <input type="hidden" name="change_availability" id="change-availability-${response.main_id}" value="0">
                    <input type="hidden" name="activity_cmid" id="activity-cmid" value="${response.moodle_id}">
                    <div class="mt-4 hide" style="text-align: center">
                        <a href="#" class="btn btn-primary btn-save-activity">Cập nhật</a>
                    </div>
                </div>
            </form>
        `;
    
        // Thêm nội dung vào parentElement
        parentElement.append(newItem);

        tinymce.execCommand('mceRemoveEditor', true, 'assign_intro');

        // Khởi tạo TinyMCE cho textarea vừa được append
        tinymce.init({
            selector: '#assign_intro', // Chọn đúng id hoặc class của textarea mà bạn muốn khởi tạo
            height: 300,
            width: '100%',
            plugins: 'preview searchreplace autolink directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help',
            toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
            image_advtab: true,
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });

        $("[data-toggle=popover]").popover();

        // $('.btn-save-activity').on('click', function(event) {
        //     event.preventDefault();
        //     updateActivityUrl(response.main_id, selectedParentId, courseId, 'url')
        // });
        $('.btn-save').attr('data-type', response.moodle_type);
        $('.btn-save').attr('data-item-id', response.main_id);
        $('.btn-save').attr('data-course-id', courseId);
        $('.btn-save').attr('data-parent-id', selectedParentId);

        $('.showFileTypeModal').on('click', function(event) {
            event.preventDefault();
            $('#filetypesModal').modal('show');
            // getDataAvailablity(response.main_id, response.parent_id);
        });

        $('.showAvailabilityModal').on('click', function(event) {
            event.preventDefault();
            getDataAvailablity(response.main_id, response.parent_id);
        });

        $('#assign_completion').on('change', function() {
            // Kiểm tra trạng thái của checkbox
            if ($(this).val() == 2) {
                // Nếu checkbox được chọn, hiển thị 'grade-box'
                $('.form-check-completionview').show();
                $('.form-check-completionsubmit').show();
                $('.form-check-completionusegrade').show();
            } else {
                // Nếu checkbox không được chọn, ẩn 'grade-box'
                $('.form-check-completionview').hide();
                $('.form-check-completionsubmit').hide();
                $('.form-check-completionusegrade').hide();
            }
        });

        $('#assign_completionusegrade').on('change', function() {
            // Kiểm tra trạng thái của checkbox
            if ($(this).is(':checked')) {
                // Nếu checkbox được chọn, hiển thị 'grade-box'
                $('.grade-box').show();
            } else {
                // Nếu checkbox không được chọn, ẩn 'grade-box'
                $('.grade-box').hide();
            }
        });

        $('#id_assignsubmission_file_enabled').on('change', function() {
            // Kiểm tra trạng thái của checkbox
            if ($(this).is(':checked')) {
                // Nếu checkbox được chọn, hiển thị 'grade-box'
                $('#fitem_fgroup_id_assignsubmission_file_filetypes').show();
            } else {
                // Nếu checkbox không được chọn, ẩn 'grade-box'
                $('#fitem_fgroup_id_assignsubmission_file_filetypes').hide();
            }
        });

        const $dropZone = $(".filemanager-container");
        const $fileManager = $(".filemanager.fm-loaded");
        // Ngăn chặn hành vi mặc định của các sự kiện
        $(document).on("dragenter dragover dragleave drop", function (e) {
            e.preventDefault();
            e.stopPropagation();
        });

        // Thêm class dndupload-ready khi người dùng bắt đầu kéo ảnh vào trang
        $(document).on("dragenter", function () {
            if (dragCounter === 0) { // Chỉ thêm class khi bắt đầu kéo vào trang
                clearTimeout(leaveTimer);
                $dropZone.addClass("dndupload-ready");
            }
            dragCounter++; // Tăng counter toàn cục
        });

        // Xóa class dndupload-ready khi rời khỏi toàn bộ trang
        $(document).on("dragleave", function () {
            dragCounter--;
            if (dragCounter === 0) { // Chỉ xóa khi không còn gì được kéo trên trang
                leaveTimer = setTimeout(() => {
                    $dropZone.removeClass("dndupload-ready");
                }, 100);
            }
        });

        // Thêm class dndupload-over khi ảnh vào phạm vi vùng thả
        $dropZone.on("dragenter", function () {
            dropZoneCounter++;
            if (dropZoneCounter === 1) { // Chỉ thêm class khi lần đầu vào vùng thả
                $dropZone.addClass("dndupload-over");
            }
        });

        // Xóa class dndupload-over khi ảnh rời khỏi vùng thả
        $dropZone.on("dragleave", function () {
            dropZoneCounter--;
            if (dropZoneCounter === 0) { // Chỉ xóa class khi hoàn toàn rời khỏi vùng thả
                $dropZone.removeClass("dndupload-over");
            }
        });

        // Bỏ cả dndupload-ready và dndupload-over khi ảnh được thả
        $dropZone.on("drop", function (e) {
            const $fileManager = $(".filemanager.fm-loaded");
            if ($fileManager.hasClass("fm-maxfiles")) {
                $dropZone.removeClass("dndupload-ready");
                alert("Đã đạt đến giới hạn file tối đa. Không thể thả thêm file vào.");
                return;
            }
            e.preventDefault();
            e.stopPropagation();
            $dropZone.removeClass("dndupload-ready dndupload-over");
            console.log("removeClass dndupload-ready dndupload-over");

            const files = e.originalEvent.dataTransfer.files;
            console.log(files);
            handleFiles(files);
            assignFilesToInput(files);
            // Xóa class fm-noitems khi ảnh được thả vào
            $fileManager.removeClass("fm-noitems");
            // console.log("removeClass fm-noitems");
            
            dragCounter = 0; // Reset counter khi thả file
        });

        const filesUploaded = response.filesuploaded;

        filesUploaded.forEach(fileData => {
            fileData.forEach(file => {
                handleFilesDetail([file]);
                $fileManager.removeClass("fm-noitems");
                $dropZone.removeClass("dndupload-ready dndupload-over");
            });
        });

        $('#image_course').on('change', function () {
            const files = this.files;
            if (files.length > 0) {
                console.log("Files have been assigned to the input file:", files);
                // Thực hiện xử lý upload hoặc các thao tác khác với files
            }
        });

        $(".fp-vb-icons").on("click", function() {
            $(this).addClass("checked");
            $(".fp-vb-details, .fp-vb-tree").removeClass("checked");
            updateView();
        });

        $(".fp-vb-details").on("click", function() {
            $(this).addClass("checked");
            $(".fp-vb-icons, .fp-vb-tree").removeClass("checked");
            updateView();
        });

        $(".fp-vb-tree").on("click", function() {
            $(this).addClass("checked");
            $(".fp-vb-icons, .fp-vb-details").removeClass("checked");
            updateView();
        });

        $('.fp-btn-delete a').on('click', function(e) {
            e.preventDefault();
            
            var selectedIndexes = [];
            $('#table-image-upload tbody input[type="checkbox"]:checked').each(function() {
                var index = $(this).data('index'); 
                selectedIndexes.push(index);
            });
            
            var popupTitle = $('.delete-confirm-popup .popup-header h3');
            var popupMessage = $('.delete-confirm-popup .popup-body p');
            var confirmButton = $('.delete-confirm-popup .fp-dlg-butconfirm');
            var cancelButton = $('.delete-confirm-popup .fp-dlg-butcancel');

            if (selectedIndexes.length === 0) {
                popupTitle.text("Lỗi");
                popupMessage.text("Vui lòng chọn ít nhất một file để xóa.");
                confirmButton.off('click').on('click', function() {
                    $('.delete-confirm-popup').css('display', 'none');
                });
                cancelButton.hide();
            } else {
                popupTitle.text("Xác Nhận Xóa");
                popupMessage.text("Bạn có chắc muốn xóa những file đã được chọn?");
                confirmButton.off('click').on('click', function() {
                    deleteSelectedFiles(selectedIndexes); // Gọi hàm xóa file
                    $('.delete-confirm-popup').css('display', 'none');
                });
                cancelButton.show();
            }

            // Hiển thị popup
            $('.delete-confirm-popup').css('display', 'flex');
        });
    }

    $('[data-filetypesbrowserkey][aria-expanded]').each(function () {
        const $item = $(this); // Lấy phần tử hiện tại
        const $expandLink = $item.find('[data-filetypesbrowserfeature="hideifexpanded"]'); // Nút "Mở rộng"
        const $collapseLink = $item.find('[data-filetypesbrowserfeature="hideifcollapsed"]'); // Nút "Rút gọn"
        const $childList = $item.find('ul[role="group"]'); // Danh sách con

        // Ẩn nút "Rút gọn" ban đầu nếu danh sách đang đóng
        if ($item.attr('aria-expanded') === "false") {
            $collapseLink.hide();
        } else {
            $expandLink.hide();
        }

        // Xử lý sự kiện click vào nút "Mở rộng"
        $expandLink.find('a').on('click', function (e) {
            e.preventDefault();
            $item.attr('aria-expanded', 'true'); // Cập nhật trạng thái
            $childList.slideDown(); // Hiển thị danh sách con
            $expandLink.hide(); // Ẩn nút "Mở rộng"
            $collapseLink.show(); // Hiển thị nút "Rút gọn"
        });

        // Xử lý sự kiện click vào nút "Rút gọn"
        $collapseLink.find('a').on('click', function (e) {
            e.preventDefault();
            $item.attr('aria-expanded', 'false'); // Cập nhật trạng thái
            $childList.slideUp(); // Ẩn danh sách con
            $collapseLink.hide(); // Ẩn nút "Rút gọn"
            $expandLink.show(); // Hiển thị nút "Mở rộng"
        });
    });

    $('input[type="checkbox"][data-filetypesbrowserkey]').on('click', function () {
        const key = $(this).data('filetypesbrowserkey'); // Lấy key của checkbox
        const $parentDiv = $(`[data-filetypesbrowserkey="${key}"]`); // Tìm div chứa key
        const $expandLink = $parentDiv.find('[data-filetypesbrowserfeature="hideifexpanded"]'); // Nút "Mở rộng"
        const $collapseLink = $parentDiv.find('[data-filetypesbrowserfeature="hideifcollapsed"]'); // Nút "Rút gọn"
        const $childList = $parentDiv.find('ul[role="group"]'); // Danh sách con

        if ($(this).is(':checked')) {
            // Nếu checkbox được checked, đóng danh sách
            $parentDiv.attr('aria-expanded', 'false'); // Đặt trạng thái đóng
            $childList.slideUp(); // Ẩn danh sách con
            $collapseLink.hide(); // Ẩn nút "Rút gọn"
            $expandLink.show(); // Hiển thị nút "Mở rộng"
        } else {
            // Nếu checkbox được unchecked, mở danh sách
            $parentDiv.attr('aria-expanded', 'true'); // Đặt trạng thái mở
            $childList.slideDown(); // Hiển thị danh sách con
            $collapseLink.show(); // Hiển thị nút "Rút gọn"
            $expandLink.hide(); // Ẩn nút "Mở rộng"
        }
    });

    $('input[type="checkbox"][data-filetypesbrowserkey="*"]').on('click', function () {
        const isChecked = $(this).is(':checked'); // Kiểm tra trạng thái checked
        const $allOtherItems = $('[data-filetypesbrowserkey]').not('[data-filetypesbrowserkey="*"]'); // Lấy tất cả các loại file khác

        if (isChecked) {
            // Nếu checkbox "All file types" được checked, ẩn tất cả loại file khác
            $allOtherItems.hide();
        } else {
            // Nếu checkbox "All file types" được unchecked, hiển thị lại tất cả loại file khác
            $allOtherItems.show();
        }
    });

    $('#filetypesModal .close').on('click', function(event) {
        event.preventDefault();
        $('#filetypesModal').modal('hide');
        // getDataAvailablity(response.main_id, response.parent_id);
    });

    $('#filetypesModal button[data-action="cancel"]').on('click', function(event) {
        event.preventDefault();
        $('#filetypesModal').modal('hide');
        // getDataAvailablity(response.main_id, response.parent_id);
    });

    $('#filetypesModal button[data-action="save"]').on('click', function () {
        let selectedFileTypes = []; // Mảng lưu các giá trị được chọn
        let fileTypeDescriptions = []; // Mảng lưu mô tả của các loại file
    
        // Kiểm tra xem checkbox "All file types (*)" có được chọn không
        const allFileTypesCheckbox = $('[data-filetypesbrowserkey="*"] input[type="checkbox"]');
        if (allFileTypesCheckbox.is(':checked')) {
            selectedFileTypes.push(allFileTypesCheckbox.attr('data-filetypesbrowserkey'));
            fileTypeDescriptions.push({
                name: $('[data-filetypesbrowserkey="*"]').find('[data-filetypesname]').text().trim(),
                extensions: $('[data-filetypesbrowserkey="*"]').find('[data-filetypesextensions]').text().trim(),
            });
        } else {
            $('div[role="treeitem"]').each(function () {
                const parentDiv = $(this); // Div hiện tại
                const parentCheckbox = parentDiv.find('> label > input[type="checkbox"]').first(); // Checkbox cha
                const parentKey = parentCheckbox.attr('data-filetypesbrowserkey'); // Key của checkbox cha
                const isParentChecked = parentCheckbox.is(':checked'); // Kiểm tra trạng thái của checkbox cha
        
                if (isParentChecked) {
                    selectedFileTypes.push(parentKey);
        
                    const name = parentDiv.find('> label > [data-filetypesname]').text().trim();
                    const extensions = parentDiv.find('> label > [data-filetypesextensions]').text().trim();
                    if (name && extensions) {
                        fileTypeDescriptions.push({ name, extensions });
                    }
                } else {
                    parentDiv.find('ul input[type="checkbox"]').each(function () {
                        const childCheckbox = $(this);
                        const childKey = childCheckbox.attr('data-filetypesbrowserkey');
                        const isChildChecked = childCheckbox.is(':checked'); // Trạng thái của checkbox con
        
                        if (isChildChecked && childKey) {
                            console.log(567)
                            // Nếu checkbox con được chọn, thêm giá trị vào danh sách
                            selectedFileTypes.push(childKey);
        
                            const childName = childCheckbox
                                .closest('label')
                                .find('[data-filetypesname]')
                                .text()
                                .trim();
                            const childExtensions = childCheckbox
                                .closest('label')
                                .find('[data-filetypesextensions]')
                                .text()
                                .trim();
        
                            if (childName && childExtensions) {
                                fileTypeDescriptions.push({ name: childName, extensions: childExtensions });
                            }
                        }
                    });
                }
            });
        }
    
        // Cập nhật giá trị cho input
        const inputFileType = $('input[name="assignsubmission_file_filetypes[filetypes]"]');
        inputFileType.val(selectedFileTypes.join(', ')); // Gán các loại file vào input
    
        // Cập nhật mô tả cho div chứa mô tả loại file
        const descriptionContainer = $('div[data-filetypesdescriptions="id_assignsubmission_file_filetypes"]');
        const descriptionList = descriptionContainer.find('ul'); // Lấy ul trong div chứa mô tả
        descriptionList.empty(); // Xóa các mô tả cũ
    
        if (fileTypeDescriptions.length > 0) {
            fileTypeDescriptions.forEach(function (fileType) {
                const listItem = $('<li></li>')
                    .text(fileType.name)
                    .append('<small class="text-muted muted"> ' + fileType.extensions + '</small>');
                descriptionList.append(listItem);
            });
        }

        $('#filetypesModal').modal('hide');
    });

    // $('#filetypesModal button[data-action="save"]').on('click', function () {
    //     let selectedFileTypes = [];
    //     let fileTypeDescriptions = [];
    //     const allFileTypesCheckbox = $('[data-filetypesbrowserkey="*"] input[type="checkbox"]');
    //     if (allFileTypesCheckbox.is(':checked')) {
    //         selectedFileTypes.push(allFileTypesCheckbox.attr('data-filetypesbrowserkey'));
    //         fileTypeDescriptions.push({
    //             name: $('[data-filetypesbrowserkey="*"]').find('[data-filetypesname]').text().trim(),
    //             extensions: $('[data-filetypesbrowserkey="*"]').find('[data-filetypesextensions]').text().trim(),
    //         });
    //     } else {
    //         // Lặp qua tất cả các div chứa thông tin loại file (cha)
    //         $('div[role="treeitem"]').each(function() {
    //             var $parentDiv = $(this);
    //             var $parentCheckbox = $parentDiv.find('input[type="checkbox"]');

    //             // Nếu checkbox cha được chọn
    //             if ($parentCheckbox.prop('checked')) {
    //                 var parentKey = $parentDiv.data('filetypesbrowserkey');
    //                 var parentName = $parentDiv.find('[data-filetypesname]').text();
    //                 var parentExtensions = $parentDiv.find('[data-filetypesextensions]').text();

    //                 // Thêm vào mảng các file types của cha
    //                 selectedFileTypes.push({
    //                     key: parentKey,
    //                     name: parentName,
    //                     extensions: parentExtensions
    //                 });
    //             } else {
    //                 // Nếu checkbox cha không được chọn, kiểm tra các checkbox con trong ul
    //                 $parentDiv.find('ul li').each(function() {
    //                     var $childLi = $(this);
    //                     var $childCheckbox = $childLi.find('input[type="checkbox"]');

    //                     // Nếu checkbox con được chọn
    //                     if ($childCheckbox.prop('checked')) {
    //                         var childKey = $childLi.data('filetypesbrowserkey');
    //                         var childName = $childLi.find('span').text();
    //                         var childExtensions = $childLi.find('small').text();

    //                         // Thêm vào mảng các file types con
    //                         selectedFileTypes.push({
    //                             key: childKey,
    //                             name: childName,
    //                             extensions: childExtensions
    //                         });
    //                     }
    //                 });
    //             }
    //         });

    //         console.log(selectedFileTypes); // In kết quả
    //     }

    // });

    //search product detail
    $('.input-search').on('input', debounce(function() {
        const searchTerm = this.value.trim();
        // console.log(searchTerm);
        const parentId = courseId;
        var parentElement = $('.results-search');
        parentElement.empty();
        if (searchTerm.length >= 1) {
            fetchProducts(searchTerm, parentId);
        }
    }, 300));

    function fetchProducts(searchTerm, parentId) {
        $.ajax({
            url: '/api/productdetail/search',
            type: 'POST',
            data: {
                searchTerm: searchTerm,
                parentId: parentId
            },
            success: function (response) {
                console.log(response);
                updateProductList(response);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }

    function updateProductList(products) {
        var parentElement = $('.results-search');
        parentElement.empty();
        products.forEach(function(item, index) {
            // Kiểm tra loại item (course hoặc không)
            let icon = 'fa-folder-open fa-folder-color expand-folder'; // Chọn icon
            if(item.moodle_type === 'quiz'){
                icon = 'fa-list-ol expand-quiz';
            }
            if(item.moodle_type === 'url'){
                icon = 'fa-link expand-url';
            }
            if(item.moodle_type === 'resource'){
                icon = 'fa-file expand-resource';
            }
            if(item.moodle_type === 'assign'){
                icon = 'fa-file expand-assign';
            }
            // Tạo HTML cho từng item
            var level = item.level;
            var newItem = `
                <div class="s-tree-item-cover s-cover-category-${item.id}" data-level="${level}" data-cover-category="${item.id}">
                    <div class="s-level-${index + 1} s-tree-item " data-child-level="${level}" data-category-id="${item.id}" style="width: calc(100% - 0%)">
                        <div class="s-left-item">
                            <i class="fa ${icon}" aria-hidden="true" data-child-level="${level}" data-category-id="${item.id}"></i>
                            <div class="s-expand-folder s-box-name" data-child-level="${level}" data-category-id="${item.id}" id="s-folder-${item.id}">
                                ${item.moodle_name}
                            </div>
                        </div>
                        <div class="s-right-item"></div>
                        <div class="s-tree-item-contain s-tree-item-contain-1 s-contain-category-${item.id}" style="width: calc(100% - 0%)"></div>
                    </div>
                </div>
            `;
            parentElement.append(newItem); // Thêm item con vào
        });
    }

    function debounce(func, delay) {
        let debounceTimer;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(context, args), delay);
        }
    }
    
    $(document).on('click', '.s-tree-item-cover', function () {
        $('.s-tree-item-cover .s-tree-item').removeClass('active');

        const treeItem = $(this).find('.s-tree-item');
        
        treeItem.addClass('active');
        // Lấy giá trị của category id từ phần tử con có class s-tree-item
        const categoryId = $(this).find('.s-tree-item').data('category-id');

        const currentLevel = $(this).find('.s-tree-item').data('child-level');
            
        for (let i = 1; i <= 10; i++) {
            localStorage.removeItem(`level-${i}`);
        }

        localStorage.setItem(`level-${currentLevel}`, categoryId);

        $.ajax({
            url: '/api/productdetail/search',
            type: 'POST',
            data: {
                id: categoryId,
            },
            success: function (response) {
                renderSectionList(response);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });
    //end search product detail
});

