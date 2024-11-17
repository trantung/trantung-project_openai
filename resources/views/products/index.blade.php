@extends('layouts.app')

@section('content')
<link href="{{ URL::asset('css/products.css') }}"rel="stylesheet">

<div id="main-content">
    <input type="hidden" id="currentUserEmail" value="{{ $currentEmail }}">
    <div class="container-fluid">
        <div class="row">
            <div id="region-main" class="content-col col-md-12">
                <div id="page-content">
                    <span class="notifications" id="user-notifications"></span>
                    <div role="main">
                        <span id="maincontent"></span>
                        <div class="container">
                            <h2>Danh sách sản phẩm</h2>
                            <div class="message-product hidden">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <div id="content_message">Cập nhật sản phẩm thành công!</div>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                            <div class="product-page">
                                @include('products.left.product_left_part', [
                                'name1' => 'Tên sản phẩm',
                                'products' => $products
                                ])
                                @include('products.right.product_right_part')
                            </div>
                            <div id="test"></div>
                            <input type="hidden" id="product_id" value="0">
                            <input type="hidden" id="product_children" value="[]">
                            <input type="hidden" id="mode_change_course_parent" value="0">
                            <input type="hidden" id="mode_change_product_parent" value="0">
                        </div>
                        <div class="modal fade" id="deleteOrArchiveModal" tabindex="-1" aria-labelledby="deleteOrArchiveModalLabel" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="yui_3_17_2_1_1731588442249_357">
                                <div class="modal-content" id="yui_3_17_2_1_1731588442249_356">
                                    <div class="modal-header" id="yui_3_17_2_1_1731588442249_355">
                                        <h5 class="modal-title" id="deleteOrArchiveModalLabel">Xóa sản phẩm</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="yui_3_17_2_1_1731588442249_354">
                                            <span aria-hidden="true" id="yui_3_17_2_1_1731588442249_358">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div style="text-align: center">
                                            Bạn muốn xóa sản phẩm <br>"<b id="modal_name_product">Sản phẩm 120</b>" ?
                                        </div>
                                    </div>
                                    <div class="modal-footer" id="yui_3_17_2_1_1731588442249_374">
                                        <button type="button" class="btn btn-primary delete-product" data-product-id="" data-product-type="">Xóa</button>
                                        <!-- <button type="button" class="btn btn-primary archive-product" data-product-id="" data-product-type="">Lưu trữ</button> -->
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="yui_3_17_2_1_1731588442249_373">Hủy bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('js/products/products.js') }}"></script>
<!-- <script>
    $(document).ready(function() {
        loadCategoryList();
        $('#add_product').on('click', function(e) {
            e.preventDefault();
            const dataType = $(this).data('type');
            if(dataType == 'category'){
                createCategory()
            }
            
        });
    });

    function createCategory(){
        const currentUser = localStorage.getItem('currentUser');
        $.ajax({
            url: "/api/lms/category/create",
            type: "POST",
            data: {
                currentUser: currentUser
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response) {
                    loadCategoryList();
                }
            },
            error: function(xhr, status, error) {
            }
        });
    }

    function loadCategoryList(clickedCategoryId = null) {
        $.ajax({
            url: '/api/lms/category/list',
            method: 'GET',
            success: function(response) {
                localStorage.setItem('currentUser', $('#currentUserEmail').val());
                renderCategoryList(response, clickedCategoryId);
            },
            error: function(error) {
                console.error('Lỗi khi tải danh sách category:', error);
            }
        });
    }

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

        if (clickedCategoryId === null && categories.length > 0) {
            clickedCategoryId = categories[0].id;
            const clickedCategoryType = categories[0].moodle_type; 

            $('#setting_product')
                .attr('data-type', 'category')
                .attr('data-category-id', clickedCategoryId);

            console.log("Category ID đầu tiên:", clickedCategoryId);
            console.log("Category Type đầu tiên:", clickedCategoryType);
        } else {
            $('#setting_product')
                .attr('data-type', 'category')
                .attr('data-category-id', clickedCategoryId);
            console.log("Clicked Category ID:", clickedCategoryId);
        }

    }

    function addProductToCategory(categoryId, parentId) {
        console.log("Thêm sản phẩm vào danh mục " + categoryId + " ở parent " + parentId);
        const clickedCategoryId = parentId;
        const currentUser = localStorage.getItem('currentUser');
        $.ajax({
            url: "/api/lms/category/create",
            type: "POST",
            data: {
                parent_category_lms: categoryId,
                parent_id: parentId,
                currentUser: currentUser
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
        const currentUser = localStorage.getItem('currentUser');
        $.ajax({
            url: "/api/lms/course/create",
            type: "POST",
            data: {
                parent_id: parentId,
                category_lms: categoryId,
                currentUser: currentUser
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
</script> -->
@endsection

