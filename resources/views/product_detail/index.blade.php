@extends('layouts.app')

@section('content')
<link href="{{ URL::asset('css/courseDetail.css') }}" rel="stylesheet">

<div id="main-content">
    <input type="hidden" id="currentUserEmail" value="{{ $currentEmail }}">
    <input type="hidden" id="courseId" value="{{ $courseData->id }}">
    <div class="container-fluid">
        <div class="row">
            <div id="region-main" class="content-col col-md-12">
                <div id="page-content">
                    <span class="notifications" id="user-notifications"></span>
                    <div role="main">
                        <span id="maincontent"></span>
                        <div class="container boxLoad">
                            <h2>
                                <a href="{{ route('course.index') }}">
                                    <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                                    {{ $courseData->moodle_name }}
                                </a>
                            </h2>
                            <div class="message-product hidden">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <div id="content_message">Cập nhật sản phẩm thành công!</div>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                            <div class="course-detail-page">
                                @include('product_detail.left.product_detail_left_part', [
                                    'name1' => 'Tên thư mục',
                                    'products' => $products
                                ])
                                @include('product_detail.right.product_detail_right_part')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="popup_question_type" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    THÊM CÂU HỎI MỚI
                </h5>
                <!-- <input id="popup_search" onkeyup="realFilter()" type="text" placeholder="Tìm kiếm loại câu hỏi"> -->
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div><!-- .modal-header -->
            <div class="modal-body">
                <div class="popup_content">
                    <div class="popup_content_box popup_content_box_1 shadow">
                        <div class="text_question_type">
                            1. Multiple choice
                        </div><!-- .question_type -->
                        <div class="button_question_type">
                                <button class="btn btn-secondary" data-link="https://english.ican.vn/classroom/local/omo_management/quiz/upsert_question_bank.php?course=206&amp;section=2&amp;is_course_detail=1&amp;edit-view=1&amp;qtype=multichoice">Moodle</button>
                        </div><!-- .button_question_type -->
                    </div>
                    
                    <input type="hidden" class="question-sub-quiz-id" value="53058">
                </div><!-- .popup_content -->
            </div><!-- .modal-body -->
        </div>
    </div>
</div>
<div class="modal" data-backdrop="static" id="popup_upsert_question" tabindex="-1" style="/* display: none; */" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Chỉnh sửa câu hỏi
                </h5>
                <button type="button" style="font-size: 3em;" class="close btn-close-edit-md" data-dismiss="modal" aria-label="Close" id="yui_3_17_2_1_1729853647785_60">
                    <span aria-hidden="true">×</span>
                </button>
            </div><!-- .modal-header -->

            <div class="modal-body">
                <div class="popup_content">
                    <iframe id="edit_view_qs" width="100%" height="232" src="https://english.ican.vn/classroom/local/omo_management/quiz/upsert_question_bank.php?course=171&amp;section=10457&amp;id=238507&amp;quiz_id=31113&amp;qtype=gapselect&amp;quiz_section_custom_id=10018&amp;quiz_slots_custom_id=44198&amp;edit-view=1&amp;is_course_detail=1" allowfullscreen="" data-gtm-yt-inspected-6="true"></iframe>
                </div><!-- .popup_content -->
            </div><!-- .modal-body -->
        </div>
    </div>
</div>
<div class="modal fade show" id="modal-availability" tabindex="-1" role="dialog" style="display: none;" aria-labelledby="modal-availability-label" aria-modal="true" style="padding-right: 17px; display: block;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-lesson-event-label">Chọn tài nguyên cần hoàn thành</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="availability-items">
                    <!-- <div class="availability-item availability-item-11560 level-1">
                        <input type="checkbox" class="folder-check check-availability level-1" data-instance="11560" data-type="course_sections" data-path="11560" data-cmid="null">
                        <div class="normal-item">
                            <i class="ml-2 fa collape-parent fa-chevron-down" data-instance="11560" data-level="1"></i>
                            <div class="ml-1">Thư mục 1</div>
                        </div>
                    </div>
                    <div class="availability-item csr-instance-parent-11560 availability-item-4339 level-2">
                        <input type="checkbox" class="activity-check check-availability level-2" data-instance="4339" data-type="resource" data-path="11560/4339" data-cmid="65714">
                        <div class="normal-item">
                            <div class="ml-1">File 4339</div>
                        </div>
                    </div>
                    <div class="availability-item availability-item-11561 level-1">
                        <input type="checkbox" class="folder-check check-availability level-1" data-instance="11561" data-type="course_sections" data-path="11561" data-cmid="null">
                        <div class="normal-item">
                            <i class="ml-2 fa expand-parent fa-chevron-right" data-instance="11561" data-level="1"></i>
                            <div class="ml-1">Thư mục 2</div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-choose-activity" data-cmid="">Xác nhận</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('js/product_detail/product_detail.js') }}"></script>
<script src="{{ URL::asset('js/activity/video.js') }}"></script>
<script src="{{ URL::asset('js/activity/quiz.js') }}"></script>
<script>
    // let courseId = $('#courseId').val();
    // $(document).ready(function() {
        
    //     loadSectionData(courseId);

    //     $('#add_product').on('click', function(e) {
    //         e.preventDefault();
    //         const dataType = $(this).data('type');
    //         if(dataType == 'section'){
    //             const couseId = $(this).data('course-id');
    //             createSection(couseId)
    //         }
    //     });
    // });

    // function loadSectionData(courseId, clickedCategoryId = null) {
    //     $.ajax({
    //         url: '/api/lms/section/list',
    //         method: 'POST',
    //         data: {
    //             course_id: courseId
    //         },
    //         success: function(response) {
    //             localStorage.setItem('currentUser', $('#currentUserEmail').val());
    //             renderSectionList(response, clickedCategoryId);
    //         },
    //         error: function(error) {
    //             console.error('Lỗi khi tải danh sách category:', error);
    //         }
    //     });
    // }

    // function renderSectionList(categories, clickedCategoryId = null) {
    //     let html = '';
    //     categories.forEach(function(item, index) {
    //         let mainClass = (index === 0 || item.id === clickedCategoryId) ? 'view-product-children active' : 'view-course-children';
    //         html += `
    //             <div class="tree-item-cover cover-category-${item.id} ui-sortable-handle">
    //                 <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="${item.id}">
    //                 <div class="level-${index + 1} tree-item ${mainClass}" data-type="${item.moodle_type}" data-category-id="${item.id}" style="width: calc(100% - 4%);">
    //                     <div class="left-item">
    //                         <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
    //                         <i class="fa btn-expand-collapse fa-chevron-right btn-collapse" title="Mở rộng" data-category-id="${item.id}" aria-hidden="true"></i>
    //                         <i class="fa fa-folder-open fa-folder-color expand-folder" aria-hidden="true" data-type="course_sections" data-path="${item.id}" data-child-level="${index + 1}" data-category-id="${item.moodle_id}"></i>
    //                         <div class="product-name-w100 product-name-${item.id}" data-category-id="${item.id}">${item.moodle_name}</div>
    //                     </div>
    //                     <div class="right-item">
    //                         <i class="icon_is_lesson is_lesson_${item.moodle_id} fa-bookmark fa" title="${item.moodle_name}" data-id="${item.id}" data-category-id="${item.moodle_id}" aria-hidden="true"></i>
    //                         <div class="btn-group">
    //                             <i class="fa fa-plus icon-dropdown-menu" title="Thêm thư mục con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
    //                             <div class="dropdown-menu">
    //                                 <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại thư mục</b></h5>
    //                                 <div class="dropdown-item add-child-quiz" data-type="quiz" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}" data-parent-id="${item.id}">
    //                                     <i class="fa fa-plus" aria-hidden="true"></i>
    //                                     <i class="fa fa-list-ol" aria-hidden="true"></i>
    //                                     <div>Quiz</div>
    //                                 </div>
    //                                 <div class="dropdown-item add-child-video" data-type="video" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                     <i class="fa fa-plus" aria-hidden="true"></i>
    //                                     <i class="fa fa-video-camera" aria-hidden="true"></i>
    //                                     <div>Video</div>
    //                                 </div>
    //                                 <div class="dropdown-item add-child-resource" data-cmid="" data-type="resource" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                     <i class="fa fa-plus" aria-hidden="true"></i>
    //                                     <i class="fa fa-file" aria-hidden="true"></i>
    //                                     <div>File</div>
    //                                 </div>
    //                                 <div class="dropdown-item add-interactive-book" data-type="interactive_book" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                     <i class="fa fa-plus" aria-hidden="true"></i>
    //                                     <i class="fa fa-list-ol" aria-hidden="true"></i>
    //                                     <div>Interactive Book</div>
    //                                 </div>
    //                                 <div class="dropdown-item add-child-assign" data-cmid="" data-type="assign" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                     <i class="fa fa-plus" aria-hidden="true"></i>
    //                                     <i class="fa fa-file" aria-hidden="true"></i>
    //                                     <div>Bài tập</div>
    //                                 </div>
    //                                 <div class="dropdown-item add-child-url" data-cmid="" data-type="url" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                     <i class="fa fa-plus" aria-hidden="true"></i>
    //                                     <i class="fa fa-link" aria-hidden="true"></i>
    //                                     <div>URL</div>
    //                                 </div>
    //                                 <div class="dropdown-item add-child-scorm" data-cmid="" data-type="scorm" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                     <i class="fa fa-plus" aria-hidden="true"></i>
    //                                     <i class="fa fa-file-archive-o" aria-hidden="true"></i>
    //                                     <div>Scorm</div>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                         <i class="fa fa-trash icon-action-delete" title="Xóa" aria-hidden="true" data-type="course_sections" data-child-level="${index + 1}" data-course-id="${courseId}" data-category-id="${item.moodle_id}" data-name="${item.moodle_name}"></i>
    //                     </div>
    //                 </div>
    //                 <div data-current="${item.id}"
    //                     class="tree-item-contain contain-category-${item.id} tree-item-move ui-sortable"
    //                     style="width: calc(100% - 4%); display: none;">
    //                 </div>
    //             </div>`;
    //     });

    //     $('.tree-items').html(html);

    //     $('.above-block').off('click', '.add-child-quiz');

    //     $('.above-block').on('click', '.add-child-quiz', function(e) {
    //         e.stopPropagation(); // Ngừng sự kiện "bong bóng"
    //         const sectionId = $(this).data('category-id');
    //         const parentId = $(this).data('parent-id');
    //         const courseId = $(this).data('course-id');
    //         // console.log("Thêm khóa học mới vào danh mục với ID: " + sectionId + ", parent: " + parentId);

    //         addActivityToSection(sectionId, parentId, courseId, 'quiz');
    //     });

    //     if (clickedCategoryId) {
    //         $(`.cover-category-${clickedCategoryId} .view-product-children`).addClass('active');
    //     }

    //     // Tự động click vào item có class active (vừa được chọn hoặc là item đầu tiên)
    //     $('.view-product-children.active').click();
    // }

    // function addActivityToSection(sectionId, parentId, courseId, type) {
    //     const clickedCategoryId = parentId;
    //     const currentUser = localStorage.getItem('currentUser');
    //     // console.log("Thêm khóa học vào danh mục " + categoryId + " ở parent " + parentId);
    //     $.ajax({
    //         url: "/api/lms/activity/create",
    //         type: "POST",
    //         data: {
    //             parent_id: parentId,
    //             section_id: sectionId,
    //             course_id: courseId,
    //             type: type,
    //             currentUser: currentUser
    //         },
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function(response) {
    //             if(response) {
    //                 loadSectionData(courseId, clickedCategoryId);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             // console.error('Error:', error);
    //             // alert('Failed to create category');
    //         }
    //     });
    // }

    // function createSection(couseId){
    //     const currentUser = localStorage.getItem('currentUser');
    //     $.ajax({
    //         url: "/api/lms/section/create",
    //         type: "POST",
    //         data: {
    //             couseId: couseId,
    //             currentUser: currentUser
    //         },
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function(response) {
    //             if(response) {
    //                 loadSectionData(couseId);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             // console.error('Error:', error);
    //             // alert('Failed to create category');
    //         }
    //     });
    // }
</script>
@endsection
