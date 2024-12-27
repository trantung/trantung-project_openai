$(document).ready(function() {
    $('.theme-links .toggle-open').click(function(e) {
        e.preventDefault();
        if ($('.theme-links').hasClass('closed')) {
            $('.theme-links').removeClass('closed').addClass('opened');
        } else {
            $('.theme-links').removeClass('opened').addClass('closed');
        }
    });

    $('.header-content .login-open').click(function(e) {
        $('.theme-loginform-inner').toggle();
    })

    $('#main-navigation .main-menu .bookmarks-item').click(function(e) {
        $('.theme-bookmarks').toggle();
    })

    $('.theme-plugins .popover-region-toggle').click(function(e) {
        $('.popover-region-notifications').toggleClass('collapsed');
    })

    $('#main-navigation .main-menu .search-item').click(function(e) {
        $('.theme-searchform').toggle();
    })

    $('#main-navigation .theme-searchform .search-close').click(function(e) {
        $('.theme-searchform').toggle();
    })

    $('#main-navigation .main-menu li.dropdown').hover(function() {
        // Khi vào
        $(this).find('ul').stop(true, true).slideDown(200);
    }, function() {
        // Khi rời
        $(this).find('ul').stop(true, true).slideUp(200);
    });


    setTimeout(function() {
        $('#error-alert, #success-alert').fadeOut();
    }, 3000); // 3 giây


    // $('.above-block').on('click', '.tree-item', function(e) {
    //     console.log(123)
    //     // Ngăn chặn hành vi mặc định và sự kiện click bong bóng
    //     // e.preventDefault();
    //     e.stopPropagation();
    //     // $(".dropdown-menu").removeClass("show");
    //     const $addSubItemButton = $(e.target).closest('.fa-plus');
    //     const $viewItemButton = $(e.target).closest('.fa-sitemap');
    //     if ($addSubItemButton.length) {
    //         e.preventDefault();
    //         return;
    //     }

    //     if ($viewItemButton.length) {
    //         return;
    //     }

    //     var $this = $(this);
    //     var dataType = $this.data('type');
    //     var categoryId = $this.data('category-id');
    //     var $currentBlock = $this.closest('.above-block');

    //     // Xóa phần đóng các tree-item khác

    //     // Toggle trạng thái mở/đóng của item hiện tại
    //     var $containElement = $currentBlock.find('.contain-category-' + categoryId);
    //     $containElement.slideToggle();

    //     // Đổi icon collapse/expand cho item hiện tại
    //     var $icon = $this.find('.btn-collapse');
    //     $icon.toggleClass('fa-chevron-down fa-chevron-right');

    //     // Thay đổi trạng thái active cho item hiện tại, chỉ trong khối hiện tại
    //     // $currentBlock.find('.tree-item').removeClass('item-active');
    //     // $this.addClass('item-active');
    //     $currentBlock.find('.tree-item').removeClass('active');
    //     $this.addClass('active');
    //     if(dataType == 'category'){
    //         getDetailCategory(categoryId);
    //     }
    //     if(dataType == 'section'){
    //         getDetailSection(categoryId);
    //     }
    //     if(dataType == 'course'){
    //         getDetailCourse(categoryId);
    //     }
    //     // Toggle class hide cho #info_product
    //     $('#info_product').removeClass('hide');

    //     // Đảm bảo #info_course luôn bị ẩn
    //     $('#info_course').addClass('hide');
    // });

    // function getDetailCategory(parent_id){
    //     $.ajax({
    //         url: "/api/lms/category/detail",
    //         type: "POST",
    //         data: {
    //             parent_id: parent_id
    //         },
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function(response) {
    //             renderCategoryDetail(response, parent_id);
    //             renderHtmlRightPart(response, parent_id);
    //         },
    //         error: function(xhr, status, error) {
    //             // console.error('Error:', error);
    //             // alert('Failed to create category');
    //         }
    //     });
    // }

    // function getDetailSection(section_id){
    //     $.ajax({
    //         url: "/api/lms/section/detail",
    //         type: "POST",
    //         data: {
    //             section_id: section_id
    //         },
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function(response) {
    //             renderSectionDetail(response, section_id);
    //             renderHtmlRightPart(response, section_id);
    //         },
    //         error: function(xhr, status, error) {
    //             // console.error('Error:', error);
    //             // alert('Failed to create category');
    //         }
    //     });
    // }

    // function getDetailCourse(course_id){
    //     $.ajax({
    //         url: "/api/lms/course/detail",
    //         type: "POST",
    //         data: {
    //             course_id: course_id
    //         },
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function(response) {
    //             renderHtmlRightPart(response, course_id);
    //         },
    //         error: function(xhr, status, error) {
    //             // console.error('Error:', error);
    //             // alert('Failed to create category');
    //         }
    //     });
    // }

    // function renderCategoryDetail(response, parent_id) {
    //     if (response.length > 0) {
    //         var parentElement = $('.contain-category-' + parent_id); // Lấy phần tử cha
    //         parentElement.empty(); // Xóa các phần tử con cũ
    //         console.log(parentElement);

    //         response.forEach(function(item, index) {
    //             // Kiểm tra loại item (course hoặc không)
    //             var icon = item.moodle_type === 'course' ? 'fa-book' : 'fa-archive'; // Chọn icon
    //             var rightItemHtml = '';

    //             let iconArrow = '';
    //             let containCategory = '';

    //             if (item.moodle_type === 'course') {
    //                 // Nếu là course, thêm icon sitemap
    //                 rightItemHtml = `
    //                     <a href="/product/detail/${item.id}">
    //                         <i class="fa fa-sitemap" title="Xem cấu trúc" aria-hidden="true"></i>
    //                     </a>
    //                     <i class="fa fa-trash delete-archive-product" title="Xóa" aria-hidden="true"></i>
    //                 `;
    //             } else {
    //                 iconArrow = `<i class="fa btn-expand-collapse fa-chevron-right btn-collapse" title="Mở rộng" data-category-id='${item.id}' aria-hidden="true"></i>`;

    //                 containCategory = `<div data-current="${item.id}"
    //                     class="tree-item-contain contain-category-${item.id} tree-item-move ui-sortable 123"
    //                     style="width: calc(100% - 4%); display: none;">
    //                 </div>`

    //                 // Nếu không phải là course, thêm div btn-group
    //                 rightItemHtml = `
    //                     <div class="btn-group">
    //                         <i class="fa fa-plus" title="Thêm sản phẩm con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
    //                         <div class="dropdown-menu">
    //                             <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại sản phẩm</b></h5>
    //                             <div class="dropdown-item add-child-product" data-child-level="2" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                 <i class="fa fa-plus" aria-hidden="true"></i>
    //                                 <i class="fa fa-archive" aria-hidden="true"></i>
    //                                 <div>Sản phẩm</div>
    //                             </div>
    //                             <div class="dropdown-item add-child-course" data-child-level="2" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                 <i class="fa fa-plus" aria-hidden="true"></i>
    //                                 <i class="fa fa-book" aria-hidden="true"></i>
    //                                 <div>Khóa học</div>
    //                             </div>
    //                         </div>
    //                     </div>
    //                     <i class="fa fa-trash delete-archive-product" title="Xóa" aria-hidden="true"></i>
    //                 `;
    //             }

    //             // Tạo HTML cho từng item
    //             var newItem = `
    //                 <div class="tree-item-cover cover-category-${item.id} ui-sortable-handle">
    //                     <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="${item.id}">
    //                     <div class="level-${index + 1} tree-item view-course-children view-course-children-${item.id}" data-type="${item.moodle_type}" data-category-id="${item.id}" style="width: calc(100% - 4%);">
    //                         <div class="left-item">
    //                             <i class="fas fa-arrows-alt drag-handle"></i>
    //                             ${iconArrow}
    //                             <i class="fa ${icon}" aria-hidden="true"></i>
    //                             <div class="product-name-w100 product-name-${item.id}" data-category-id="${item.id}">${item.moodle_name}</div>
    //                         </div>
    //                         <div class="right-item">
    //                             ${rightItemHtml}
    //                         </div>
    //                     </div>
    //                 </div>
    //                 ${containCategory}
    //             `;
    //             parentElement.append(newItem); // Thêm item con vào
    //         });
    //     }
    // }

    // function renderSectionDetail(response, parent_id) {
    //     if (response.length > 0) {
    //         var parentElement = $('.contain-category-' + parent_id); // Lấy phần tử cha
    //         parentElement.empty(); // Xóa các phần tử con cũ

    //         response.forEach(function(item, index) {
    //             // Kiểm tra loại item (course hoặc không)
    //             let icon = 'fa-folder-open fa-folder-color'; // Chọn icon
    //             var rightItemHtml = '';
    //             if(item.moodle_type === 'quiz'){
    //                 icon = 'fa-list-ol';
    //             }

    //             if (item.moodle_type === 'section') {
    //                 // Nếu là course, thêm icon sitemap
    //                 rightItemHtml = `
    //                     <i class="icon_is_lesson is_lesson_${item.moodle_id} fa-bookmark fa" title="${item.moodle_name}" data-id="${item.id}" data-category-id="${item.moodle_id}" aria-hidden="true"></i>
    //                     <div class="btn-group">
    //                         <i class="fa fa-plus icon-dropdown-menu" title="Thêm thư mục con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
    //                         <div class="dropdown-menu">
    //                             <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại thư mục</b></h5>
    //                             <div class="dropdown-item add-child-folder" data-type="course_sections" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                 <i class="fa fa-plus" aria-hidden="true"></i>
    //                                 <i class="fa fa-folder-open fa-folder-color" aria-hidden="true"></i>
    //                                 <div>Thư mục</div>
    //                             </div>
    //                             <div class="dropdown-item add-child-quiz" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}" data-parent-id="${item.id}">
    //                                 <i class="fa fa-plus" aria-hidden="true"></i>
    //                                 <i class="fa fa-list-ol" aria-hidden="true"></i>
    //                                 <div>Quiz</div>
    //                             </div>
    //                             <div class="dropdown-item add-child-video" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                 <i class="fa fa-plus" aria-hidden="true"></i>
    //                                 <i class="fa fa-video-camera" aria-hidden="true"></i>
    //                                 <div>Video</div>
    //                             </div>
    //                             <div class="dropdown-item add-child-resource" data-cmid="" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                 <i class="fa fa-plus" aria-hidden="true"></i>
    //                                 <i class="fa fa-file" aria-hidden="true"></i>
    //                                 <div>File</div>
    //                             </div>
    //                             <div class="dropdown-item add-interactive-book" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                 <i class="fa fa-plus" aria-hidden="true"></i>
    //                                 <i class="fa fa-list-ol" aria-hidden="true"></i>
    //                                 <div>Interactive Book</div>
    //                             </div>
    //                             <div class="dropdown-item add-child-assign" data-cmid="" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                 <i class="fa fa-plus" aria-hidden="true"></i>
    //                                 <i class="fa fa-file" aria-hidden="true"></i>
    //                                 <div>Bài tập</div>
    //                             </div>
    //                             <div class="dropdown-item add-child-url" data-cmid="" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                 <i class="fa fa-plus" aria-hidden="true"></i>
    //                                 <i class="fa fa-link" aria-hidden="true"></i>
    //                                 <div>URL</div>
    //                             </div>
    //                             <div class="dropdown-item add-child-scorm" data-cmid="" data-type="${item.moodle_type}" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                 <i class="fa fa-plus" aria-hidden="true"></i>
    //                                 <i class="fa fa-file-archive-o" aria-hidden="true"></i>
    //                                 <div>Scorm</div>
    //                             </div>
    //                         </div>
    //                     </div>
    //                     <i class="fa fa-trash icon-action-delete" title="Xóa" aria-hidden="true" data-type="course_sections" data-child-level="${index + 1}" data-course-id="{{$courseData->moodle_id}}" data-category-id="${item.moodle_id}" data-name="${item.moodle_name}"></i>
    //                 `;
    //             } else {
    //                 // Nếu không phải là course, thêm div btn-group
    //                 rightItemHtml = `
    //                     <div class="btn-group">
    //                         <i class="fa fa-play icon-quiz-action-play-temp play-temp" data-type="${item.moodle_type}" data-href="#" title="Làm bài" aria-hidden="true"></i>
    //                         <i class="fa fa-trash icon-quiz-action-delete" title="Xoá" aria-hidden="true" data-category-id="${item.parent_id}" data-parent-id="${item.parent_id}" data-instance="45960" data-cmid="${item.moodle_id}" data-type="quiz" data-child-level="${index + 1}" data-name="${item.moodle_name}"></i>
    //                     </div>
    //                 `;
    //             }

    //             // Tạo HTML cho từng item
    //             let productClass = item.moodle_type === 'quiz' ? 'product-name-w100 w-100 expand-quiz' : 'product-name-w100 w-100';
    //             var newItem = `
    //                 <div class="tree-item-cover cover-category-${item.id} ui-sortable-handle">
    //                     <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="${item.id}">
    //                     <div class="level-2 tree-item view-course-children view-course-children-${item.id}" data-category-id="${item.id}" style="width: calc(100% - 4%);">
    //                         <div class="left-item">
    //                             <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
    //                             <i class="fa ${icon} expand-folder" aria-hidden="true" data-type="course_sections" data-path="${item.id}" data-child-level="${index + 1}" data-category-id="${item.moodle_id}"></i>
    //                             <div class="${productClass} product-name-${item.id}" data-parent="${item.parent_id}" data-cmid="${item.moodle_id}" data-type="${item.moodle_type}" data-category-id="${item.id}">${item.moodle_name}</div>
    //                         </div>
    //                         <div class="right-item">
    //                             ${rightItemHtml}
    //                         </div>
    //                     </div>
    //                 </div>
    //             `;
    //             parentElement.append(newItem); // Thêm item con vào
    //         });
    //     }
    // }

    // function renderHtmlRightPart(response, parent_id){
    //     var parentElement = $('.left-side-content'); // Lấy phần tử cha
    //     parentElement.empty(); // Xóa các phần tử con cũ
    //     if (response.length > 0) {
    //         response.forEach(function(item, index) {
    //             let icon = ''; // Chọn icon
    //             let blur = '';
    //             let dimmed = '';
    //             let completionMark = '';

    //             if (item.moodle_type === 'course') {
    //                 icon = 'fa-book';
    //                 blur = 'blur';
    //             }

    //             if (item.moodle_type === 'quiz') {
    //                 icon = 'fa-list-ol';
    //                 if (item.availabilityinfo != null) {
    //                     dimmed = 'dimmed';
    //                     completionMark = item.availabilityinfo;
    //                 }
    //             }

    //             if (item.moodle_type === 'section') {
    //                 icon = 'fa-folder-open fa-folder-color';
    //             }

    //             if (item.moodle_type === 'category') {
    //                 icon = 'fa-archive';
    //             }

    //             // Tạo HTML cho từng item
    //             var newItem = `
    //                 <div class="normal-item ${dimmed}" style="align-items: flex-start;">
    //                     <i class="mt-1 fa ${icon}" aria-hidden="true"></i>
    //                     <div class="${blur}">
    //                         ${item.moodle_name} <br>
    //                         ${completionMark}
    //                     </div>
    //                 </div>
    //             `;
    //             parentElement.append(newItem); // Thêm item con vào
    //         });
    //     }
    // }

})
