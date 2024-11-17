$(document).ready(function() {
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

    loadSectionData(courseId);

    $('#add_product').on('click', function(e) {
        e.preventDefault();
        const dataType = $(this).data('type');
        if(dataType == 'section'){
            const couseId = $(this).data('course-id');
            createSection(couseId)
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
                console.log(response);
                localStorage.setItem('currentUser', $('#currentUserEmail').val());
                renderSectionList(response, clickedCategoryId);
            },
            error: function(error) {
                console.error('Lỗi khi tải danh sách category:', error);
            }
        });
    }

    function renderSectionList(categories, clickedCategoryId = null) {
        let html = '';
        categories.forEach(function(item, index) {
            let mainClass = (index === 0 || item.id === clickedCategoryId) ? 'view-product-children active' : 'view-course-children';
            html += `
                <div class="tree-item-cover cover-category-${item.id} ui-sortable-handle">
                    <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="${item.id}">
                    <div class="level-${index + 1} tree-item ${mainClass}" data-type="${item.moodle_type}" data-category-id="${item.id}" style="width: calc(100% - 4%);">
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
                                    <div class="dropdown-item add-child-quiz" data-type="quiz" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-list-ol" aria-hidden="true"></i>
                                        <div>Quiz</div>
                                    </div>
                                    <div class="dropdown-item add-child-video" data-type="video" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                                        <div>Video</div>
                                    </div>
                                    <div class="dropdown-item add-child-resource" data-cmid="" data-type="resource" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-file" aria-hidden="true"></i>
                                        <div>File</div>
                                    </div>
                                    <div class="dropdown-item add-interactive-book" data-type="interactive_book" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-list-ol" aria-hidden="true"></i>
                                        <div>Interactive Book</div>
                                    </div>
                                    <div class="dropdown-item add-child-assign" data-cmid="" data-type="assign" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-file" aria-hidden="true"></i>
                                        <div>Bài tập</div>
                                    </div>
                                    <div class="dropdown-item add-child-url" data-cmid="" data-type="url" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-link" aria-hidden="true"></i>
                                        <div>URL</div>
                                    </div>
                                    <div class="dropdown-item add-child-scorm" data-cmid="" data-type="scorm" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                        <div>Scorm</div>
                                    </div>
                                </div>
                            </div>
                            <i class="fa fa-trash icon-action-delete" title="Xóa" aria-hidden="true" data-type="course_sections" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-name="${item.moodle_name}"></i>
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
            // // console.log("Thêm khóa học mới vào danh mục với ID: " + sectionId + ", parent: " + parentId);

            // addActivityToSection(sectionId, parentId, courseId, 'quiz');
            showPopupQuestionType(sectionId, parentId, courseId, 'quiz');
        });

        $('.above-block').on('click', '.add-child-url', function(e) {
            e.stopPropagation(); // Ngừng sự kiện "bong bóng"
            const sectionId = $(this).data('category-id');
            const parentId = $(this).data('parent-id');
            const courseId = $(this).data('course-id');
            // // console.log("Thêm khóa học mới vào danh mục với ID: " + sectionId + ", parent: " + parentId);

            addActivityToSection(sectionId, parentId, courseId, 'url');
        });

        if (clickedCategoryId) {
            $(`.cover-category-${clickedCategoryId} .view-product-children`).addClass('active');
        }

        // Tự động click vào item có class active (vừa được chọn hoặc là item đầu tiên)
        $('.view-product-children.active').click();
    }

    function addActivityToSection(sectionId, parentId, courseId, type, questionName = null, questionType = null, questionId = null) {
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
                questionId: questionId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response) {
                    loadSectionData(courseId, clickedCategoryId);
                }
                $('#popup_question_type').modal('hide');
            },
            error: function(xhr, status, error) {
                // console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    function showPopupQuestionType(sectionId, parentId, courseId, type) {
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
                                        <button class="btn btn-secondary" data-activity-type="${type}" data-course-id="${courseId}" data-category-id="${sectionId}" data-parent-id="${parentId}" data-question-name="${testName}" data-question-type=${lowerCaseRoundName} data-question-id=${idBaikiemtra}>Select</button>
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

        // Xử lý sự kiện, ví dụ hiển thị thông báo với dữ liệu đã chọn
        console.log(`Question Name: ${questionName}, Question Type: ${questionType}, Question ID: ${questionId}`);
        addActivityToSection(sectionId, parentId, courseId, activityType, questionName, questionType, questionId);
        // Thực hiện các hành động khác, ví dụ gửi yêu cầu AJAX hoặc cập nhật giao diện
    });

    function createSection(couseId){
        const currentUser = localStorage.getItem('currentUser');
        $.ajax({
            url: "/api/lms/section/create",
            type: "POST",
            data: {
                couseId: couseId,
                currentUser: currentUser
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response) {
                    loadSectionData(couseId);
                }
            },
            error: function(xhr, status, error) {
                // console.error('Error:', error);
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
        if ($addSubItemButton.length) {
            // console.log(333)
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
            getDetailActivity(categoryId, dataType);
        }

        if(dataType == 'url'){
            console.log(123);
            $('#setting_quiz_list_question').hide();
            $('#setting_course_sections').hide();
            $('#setting_dynamic_form').show();
            $('#form-folder-info').addClass('hide');
            $('#form-url-setting').addClass('hide');
            $('.section-quiz-part').hide();
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
                renderSectionDetail(response.data, section_id);
                renderHtmlRightPart(response.data, section_id);
                renderFormSection(response.main);
            },
            error: function(xhr, status, error) {
                // console.error('Error:', error);
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
                    renderTemplateUrl(response.data.cm, response.selectedParentId, response.sectionData);
                }

                if(activity_type == 'quiz'){
                    renderFormQuiz(response.data.cm, response.selectedParentId, response.sectionData, response.str_availability_cmid)
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    function renderSectionDetail(response, parent_id) {
        if (response.length > 0) {
            var parentElement = $('.contain-category-' + parent_id); // Lấy phần tử cha
            parentElement.empty(); // Xóa các phần tử con cũ
    
            response.forEach(function(item, index) {
                // Kiểm tra loại item (course hoặc không)
                let icon = 'fa-folder-open fa-folder-color expand-folder'; // Chọn icon
                var rightItemHtml = '';
                if(item.moodle_type === 'quiz'){
                    icon = 'fa-list-ol expand-quiz';
                }
                if(item.moodle_type === 'url'){
                    icon = 'fa-link expand-url';
                }
    
                if (item.moodle_type === 'section') {
                    // Nếu là course, thêm icon sitemap
                    rightItemHtml = `
                        <i class="icon_is_lesson is_lesson_${item.moodle_id} fa-bookmark fa" title="${item.moodle_name}" data-id="${item.id}" data-category-id="${item.moodle_id}" aria-hidden="true"></i>
                        <div class="btn-group">
                            <i class="fa fa-plus icon-dropdown-menu" title="Thêm thư mục con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
                            <div class="dropdown-menu">
                                <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại thư mục</b></h5>
                                <div class="dropdown-item add-child-folder" data-type="course_sections" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-folder-open fa-folder-color" aria-hidden="true"></i>
                                    <div>Thư mục</div>
                                </div>
                                <div class="dropdown-item add-child-quiz" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                    <div>Quiz</div>
                                </div>
                                <div class="dropdown-item add-child-video" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                                    <div>Video</div>
                                </div>
                                <div class="dropdown-item add-child-resource" data-cmid="" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-file" aria-hidden="true"></i>
                                    <div>File</div>
                                </div>
                                <div class="dropdown-item add-interactive-book" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                    <div>Interactive Book</div>
                                </div>
                                <div class="dropdown-item add-child-assign" data-cmid="" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-file" aria-hidden="true"></i>
                                    <div>Bài tập</div>
                                </div>
                                <div class="dropdown-item add-child-url" data-cmid="" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                    <div>URL</div>
                                </div>
                                <div class="dropdown-item add-child-scorm" data-cmid="" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                    <div>Scorm</div>
                                </div>
                            </div>
                        </div>
                        <i class="fa fa-trash icon-action-delete" title="Xóa" aria-hidden="true" data-type="course_sections" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-name="${item.moodle_name}"></i>
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
                var newItem = `
                    <div class="tree-item-cover cover-category-${item.id} ui-sortable-handle">
                        <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="${item.id}">
                        <div class="level-2 tree-item view-course-children view-course-children-${item.id}" data-type="${item.moodle_type}" data-category-id="${item.id}" style="width: calc(100% - 4%);">
                            <div class="left-item">
                                <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
                                <i class="fa ${icon}" aria-hidden="true" data-type="course_sections" data-path="${item.id}" data-child-level="${index + 1}" data-category-id="${item.moodle_id}"></i>
                                <div class="${productClass} product-name-${item.id}" data-parent="${item.parent_id}" data-cmid="${item.moodle_id}" data-type="${item.moodle_type}" data-category-id="${item.id}">${item.moodle_name}</div>
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
            <div class="mt-4" style="text-align: center">
                <a href="#" class="btn btn-primary btn-save-section">Cập nhật</a>
            </div>
        `;
    
        // Thêm nội dung vào parentElement
        parentElement.append(newItem); 

        $('.btn-save-section').on('click', function(event) {
            event.preventDefault();
            updateSection(response.id); // Gọi hàm updateSection để xử lý cập nhật
        });
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
                loadSectionData(courseId);
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
                            <option selected="" value="0">Không giới hạn</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5">
                        <h5>Cách tính điểm</h5>
                    </div>
                    <div class="col-7">
                        <select class="w-100" name="grademethod" id="grademethod">
                            <option selected="" value="1">Lần cao nhất</option>
                            <option value="2">Điểm trung bình</option>
                            <option value="3">Thử nghiệm lần đầu</option>
                            <option value="4">Kiểm tra lần cuối</option>
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
                            <option value="adaptive">Adaptive mode</option>
                            <option value="adaptivenopenalty">Adaptive mode (no penalties)</option>
                            <option selected="" value="deferredfeedback">Deferred feedback</option>
                            <option value="deferredcbm">Deferred feedback with CBM</option>
                            <option value="immediatefeedback">Immediate feedback</option>
                            <option value="immediatecbm">Immediate feedback with CBM</option>
                            <option value="interactive">Interactive with multiple tries</option>
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
                            <input class="form-check-input" name="attemptimmediatelyopen" style="width: auto" type="checkbox" checked="" id="id_attemptimmediatelyopen" value="1">
                            <label class="form-check-label" for="id_attemptimmediatelyopen">Bài làm</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" style="width: auto" name="correctnessimmediatelyopen" checked="" id="id_correctnessimmediatelyopen" value="1">
                            <label class="form-check-label" for="id_correctnessimmediatelyopen">Nếu đúng</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" style="width: auto" name="marksimmediatelyopen" checked="" id="id_marksimmediatelyopen" value="1">
                            <label class="form-check-label" for="id_marksimmediatelyopen">Điểm</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" style="width: auto" name="rightanswerimmediatelyopen" checked="" id="id_rightanswerimmediatelyopen" value="1">
                            <label class="form-check-label" for="id_rightanswerimmediatelyopen">Câu trả lời đúng</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" style="width: auto" name="generalfeedbackimmediatelyopen" checked="" id="id_generalfeedbackimmediatelyopen" value="1">
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
                            <option value="0">Không chỉ rõ việc hoàn thành hoạt động</option>
                            <option selected="" value="2">Khi các điều kiện được thỏa mãn, đánh dấu hoạt động như là đã hoàn thành</option>
                        </select>
                        <div class="form-check form-check-completionview" style="">
                            <input class="form-check-input" type="checkbox" style="width: auto" name="completionview" checked="" id="quiz_completionview" value="1" ${response.completionview == 1 ? 'checked' : ''}>
                            <label class="form-check-label" for="completionview">Học viên phải xem hoạt động này để hoàn thành nó</label>
                        </div>
                        <div class="form-check form-check-completionpass" style="">
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
                <div class="mt-4" style="text-align: center">
                    <a href="#" class="btn btn-primary btn-save-activity">Cập nhật</a>
                </div>
            </div>
        `;
    
        // Thêm nội dung vào parentElement
        parentElement.append(newItem); 

        $('.btn-save-activity').on('click', function(event) {
            event.preventDefault();
            updateActivityQuiz(response.main_id, selectedParentId, courseId, 'quiz')
        });

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

    function renderTemplateUrl(response, selectedParentId, sectionData){
        var parentElement = $('#wrapper-dynamic-url-form');
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
                    <select class="w-100 custom-select" name="url-parent" id="url-parent">
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
                                <input type="text" class="form-control " name="externalurl" id="id_externalurl" value="http://" style="width: 100%;" ="style="&quot;width:" 100%;&quot;"="">
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
                    <div id="fitem_id_name" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="id_name">
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
                    <div id="fitem_id_visible" class="form-group row  fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break " for="id_visible">
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
                                <a href="javascript:void(0);" class="btn btn-secondary showAvailabilityModal" data-toggle="modal" data-target="#modal-availability" data-cmid="57749" data-instance="45960" data-type="quiz">Chọn</a>
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
                                <input type="checkbox" name="completionview" class="form-check-input " value="1" id="id_completionview" checked="" aria-describedby="id_completionview_description">
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
                    <div class="button-side-content mt-2 hide">
                        <input type="submit" name="btn-save-dynamic-form-url" class="btn-save-dynamic-form-url btn btn-primary" value="Lưu">
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
            if(item.level == 1){
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

            if (item.level === 2) {
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
                loadSectionData(courseId);
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
});