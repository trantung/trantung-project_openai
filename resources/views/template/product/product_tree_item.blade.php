{{-- <div class="tree-item-cover cover-category-{{ $item['id'] }} ui-sortable-handle">
    <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="{{ $item['id'] }}">
    <div class="level-{{ $level }} tree-item {{ $level == 1 && $loop->first ? 'view-product-children' : 'view-course-children' }} {{ $level == 1 && $loop->first ? 'active' : '' }}" data-category-id="{{ $item['id'] }}" style="width: calc(100% - 4%);">
        <div class="left-item">
            <i class="fas fa-arrows-alt drag-handle"></i>
            @if($item['type'] == 'folder')
                <i class="fa btn-expand-collapse {{ ($level == 1 && $loop->first) || (isset($loop->parent) && $loop->parent->first && $loop->first) ? 'fa-chevron-down' : 'fa-chevron-right' }} btn-collapse"
                   title="{{ ($level == 1 && $loop->first) || (isset($loop->parent) && $loop->parent->first && $loop->first) ? 'Thu gọn' : 'Mở rộng' }}"
                   data-category-id="{{ $item['id'] }}" aria-hidden="true"></i>
            @endif
            <i class="fa {{ $item['type'] == 'folder' ? 'fa-archive' : 'fa-book' }}" aria-hidden="true"></i>
            <div class="product-name-w100 product-name-{{ $item['id'] }}" data-category-id="{{ $item['id'] }}">{{ $item['name'] }}</div>
        </div>
        <div class="right-item">
            @if($item['type'] == 'folder')
                <div class="btn-group">
                    <i class="fa fa-plus" title="Thêm sản phẩm con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
                    <div class="dropdown-menu">
                        <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại sản phẩm</b></h5>
                        <div class="dropdown-item add-child-product" data-child-level="{{ $level + 1 }}" data-category-id="{{ $item['id'] }}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <i class="fa fa-archive" aria-hidden="true"></i>
                            <div>Sản phẩm</div>
                        </div>
                        <div class="dropdown-item add-child-course" data-child-level="{{ $level + 1 }}" data-category-id="{{ $item['id'] }}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <i class="fa fa-book" aria-hidden="true"></i>
                            <div>Khóa học</div>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('course.detail', ['course_id' => $item['id']]) }}">
                    <i class="fa fa-sitemap" title="Xem cấu trúc" aria-hidden="true"></i>
                </a>
            @endif
            <i class="fa fa-trash delete-archive-product" title="Xóa" aria-hidden="true" data-toggle="modal" data-target="#deleteOrArchiveModal" data-product-id="{{ $item['id'] }}" data-product-name="{{ $item['name'] }}" data-product-type="{{ $item['type'] }}"></i>
        </div>
    </div>
    @if($item['type'] == 'folder' && isset($item['children']))
        <div data-current="{{ $item['id'] }}"
             class="tree-item-contain tree-item-contain-{{ $level }} contain-category-{{ $item['id'] }} tree-item-move ui-sortable"
             style="width: calc(100% - 4%); {{ !($level == 1 && $loop->first) && !(isset($loop->parent) && $loop->parent->first && $loop->first) ? 'display: none;' : '' }}">
            @foreach($item['children'] as $child)
                @include('template.product.product_tree_item', ['item' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div> --}}

<script>

    $(document).ready(function() {
        loadCategoryList();
    });

    function loadCategoryList(clickedCategoryId = null) {
        $.ajax({
            url: '/api/lms/category/list',
            method: 'GET',
            success: function(response) {
                renderCategoryList(response, clickedCategoryId);
            },
            error: function(error) {
                console.error('Lỗi khi tải danh sách category:', error);
            }
        });
    }

    // function renderCategoryList(categories) {
    //     let html = '';
    //     categories.forEach(function(item, index) {
    //         let mainClass = index === 0 ? 'view-product-children active' : 'view-course-children ';
    //         html += `
    //             <div class="tree-item-cover cover-category-${item.id} ui-sortable-handle">
    //                 <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="${item.id}">
    //                 <div class="level-${index + 1} tree-item ${mainClass}" data-type="${item.moodle_type}" data-category-id="${item.id}" style="width: calc(100% - 4%);">
    //                     <div class="left-item">
    //                         <i class="fas fa-arrows-alt drag-handle"></i>
    //                         <i class="fa btn-expand-collapse fa-chevron-right btn-collapse" title="Mở rộng" data-category-id="${item.id}" aria-hidden="true"></i>
    //                         <i class="fa fa-archive" aria-hidden="true"></i>
    //                         <div class="product-name-w100 product-name-${item.id}" data-category-id="${item.id}">${item.moodle_name}</div>
    //                     </div>
    //                     <div class="right-item">
    //                         <div class="btn-group">
    //                             <i class="fa fa-plus" title="Thêm sản phẩm con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
    //                             <div class="dropdown-menu">
    //                                 <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại sản phẩm</b></h5>
    //                                 <div class="dropdown-item add-child-product" data-child-level="2" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                     <i class="fa fa-plus" aria-hidden="true"></i>
    //                                     <i class="fa fa-archive" aria-hidden="true"></i>
    //                                     <div>Sản phẩm</div>
    //                                 </div>
    //                                 <div class="dropdown-item add-child-course" data-child-level="2" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
    //                                     <i class="fa fa-plus" aria-hidden="true"></i>
    //                                     <i class="fa fa-book" aria-hidden="true"></i>
    //                                     <div>Khóa học</div>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                         <i class="fa fa-trash delete-archive-product" title="Xóa" aria-hidden="true" data-toggle="modal" data-target="#deleteOrArchiveModal" data-product-id="${item.id}" data-product-name="${item.moodle_name}" data-product-type="folder"></i>
    //                     </div>
    //                 </div>
    //                 <div data-current="${item.id}"
    //                     class="tree-item-contain contain-category-${item.id} tree-item-move ui-sortable"
    //                     style="width: calc(100% - 4%); display: none;">
    //                 </div>
    //             </div>`;
    //     });

    //     $('.tree-items').html(html); // Thay thế '#category-list' bằng id của container của bạn

    //     $('.above-block').off('click', '.add-child-product');
    //     $('.above-block').off('click', '.add-child-course');

    //     $('.above-block').on('click', '.add-child-product', function(e) {
    //         e.stopPropagation(); // Ngừng sự kiện "bong bóng" để không ảnh hưởng đến các sự kiện khác
    //         const categoryId = $(this).data('category-id');
    //         const parentId = $(this).data('parent-id');
    //         console.log("Thêm sản phẩm mới vào danh mục với ID: " + categoryId + ", parent: " + parentId);

    //         addProductToCategory(categoryId, parentId);
    //     });

    //     $('.above-block').on('click', '.add-child-course', function(e) {
    //         e.stopPropagation(); // Ngừng sự kiện "bong bóng"
    //         const categoryId = $(this).data('category-id');
    //         const parentId = $(this).data('parent-id');
    //         console.log("Thêm khóa học mới vào danh mục với ID: " + categoryId + ", parent: " + parentId);

    //         addCourseToCategory(categoryId, parentId);
    //     });

    //     if (categories.length > 0) {
    //         // Tự động click vào item đầu tiên
    //         $('.view-product-children.active').click();
    //     }
    // }

    function renderCategoryList(categories, clickedCategoryId = null) {
        let html = '';
        categories.forEach(function(item, index) {
            // Đảm bảo item đầu tiên luôn có class 'active' và nếu có clickedCategoryId, thêm active vào
            let mainClass = (index === 0 || item.id === clickedCategoryId) ? 'view-product-children active' : 'view-course-children';
            
            html += `
                <div class="tree-item-cover cover-category-${item.id} ui-sortable-handle">
                    <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="${item.id}">
                    <div class="level-${index + 1} tree-item ${mainClass}" data-type="${item.moodle_type}" data-category-id="${item.id}" style="width: calc(100% - 4%);">
                        <div class="left-item">
                            <i class="fas fa-arrows-alt drag-handle"></i>
                            <i class="fa btn-expand-collapse fa-chevron-right btn-collapse" title="Mở rộng" data-category-id="${item.id}" aria-hidden="true"></i>
                            <i class="fa fa-archive" aria-hidden="true"></i>
                            <div class="product-name-w100 product-name-${item.id}" data-category-id="${item.id}">${item.moodle_name}</div>
                        </div>
                        <div class="right-item">
                            <div class="btn-group">
                                <i class="fa fa-plus" title="Thêm sản phẩm con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
                                <div class="dropdown-menu">
                                    <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại sản phẩm</b></h5>
                                    <div class="dropdown-item add-child-product" data-child-level="2" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-archive" aria-hidden="true"></i>
                                        <div>Sản phẩm</div>
                                    </div>
                                    <div class="dropdown-item add-child-course" data-child-level="2" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <i class="fa fa-book" aria-hidden="true"></i>
                                        <div>Khóa học</div>
                                    </div>
                                </div>
                            </div>
                            <i class="fa fa-trash delete-archive-product" title="Xóa" aria-hidden="true" data-toggle="modal" data-target="#deleteOrArchiveModal" data-product-id="${item.id}" data-product-name="${item.moodle_name}" data-product-type="folder"></i>
                        </div>
                    </div>
                    <div data-current="${item.id}"
                        class="tree-item-contain contain-category-${item.id} tree-item-move ui-sortable 123"
                        style="width: calc(100% - 4%); display: none;">
                    </div>
                </div>`;
        });

        $('.tree-items').html(html);

        // Sự kiện click cho các nút thêm sản phẩm và khóa học
        $('.above-block').off('click', '.add-child-product');
        $('.above-block').off('click', '.add-child-course');

        $('.above-block').on('click', '.add-child-product', function(e) {
            e.stopPropagation();
            const categoryId = $(this).data('category-id');
            const parentId = $(this).data('parent-id');
            addProductToCategory(categoryId, parentId);
        });

        $('.above-block').on('click', '.add-child-course', function(e) {
            e.stopPropagation();
            const categoryId = $(this).data('category-id');
            const parentId = $(this).data('parent-id');
            addCourseToCategory(categoryId, parentId);
        });

        // Nếu clickedCategoryId có giá trị, thêm class 'active' vào phần tử vừa click
        if (clickedCategoryId) {
            $(`.cover-category-${clickedCategoryId} .view-product-children`).addClass('active');
        }

        // Tự động click vào item có class active (vừa được chọn hoặc là item đầu tiên)
        $('.view-product-children.active').click();
    }

    function addProductToCategory(categoryId, parentId) {
        console.log("Thêm sản phẩm vào danh mục " + categoryId + " ở parent " + parentId);
        const clickedCategoryId = parentId;
        $.ajax({
            url: "/api/lms/category/create",
            type: "POST",
            data: {
                parent_category_lms: categoryId,
                parent_id: parentId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response) {
                    loadCategoryList(clickedCategoryId);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    function addCourseToCategory(categoryId, parentId) {
        // Lưu lại `parentId` để active sau khi load lại danh sách
        const clickedCategoryId = parentId;

        $.ajax({
            url: "/api/lms/course/create",
            type: "POST",
            data: {
                parent_id: parentId,
                category_lms: categoryId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response) {
                    // Gọi lại `loadCategoryList` và truyền ID cần active
                    loadCategoryList(clickedCategoryId);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
</script>
