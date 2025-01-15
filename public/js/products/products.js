$(document).ready(function() {
    loadCategoryList();
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

    $('#add_product').on('click', function(e) {
        e.preventDefault();
        const dataType = $(this).data('type');
        if(dataType == 'category'){
            createCategory(1)
        }
        
    });

    function createCategory(level){
        const currentUser = localStorage.getItem('currentUser');
        $.ajax({
            url: "/api/lms/category/create",
            type: "POST",
            data: {
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
            $('.view-course-children.active').click();
        }
    }

    function renderCategoryList(categories, clickedCategoryId = null) {
        let html = '';
        categories.forEach(function(item, index) {
            // Đảm bảo item đầu tiên luôn có class 'active' và nếu có clickedCategoryId, thêm active vào
            let viewClass = item.moodle_type === 'course' ? 'course' : 'product';
            let mainClass = (index === 0 || item.id === clickedCategoryId) ? 'view-'+viewClass+'-children active' : 'view-'+viewClass+'-children';
            var icon = item.moodle_type === 'course' ? 'fa-book' : 'fa-archive'; // Chọn icon
            var level = item.level;
            html += `
                <div class="tree-item-cover cover-category-${item.id} ui-sortable-handle">
                    <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="${item.id}">
                    <div class="level-${level} tree-item ${mainClass}" data-level=${level} data-type="${item.moodle_type}" data-category-id="${item.id}" style="width: calc(100% - 4%);">
                        <div class="left-item">
                            <!-- <i class="fas fa-arrows-alt drag-handle"></i> -->
                            ${item.moodle_type !== 'course' ? `
                                <i class="fa btn-expand-collapse fa-chevron-right btn-collapse" title="Mở rộng" data-category-id="${item.id}" aria-hidden="true"></i>
                            ` : ''}
                            <i class="fa ${icon}" aria-hidden="true"></i>
                            <div class="product-name-w100 product-name-${item.id}" data-category-id="${item.id}">${item.moodle_name}</div>
                        </div>
                        <div class="right-item">
                            ${item.moodle_type !== 'course' ? `
                                <div class="btn-group">
                                    <i class="fa fa-plus" title="Thêm sản phẩm con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
                                    <div class="dropdown-menu">
                                        <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại sản phẩm</b></h5>
                                        <div class="dropdown-item add-child-product" data-child-level="${level + 1}" data-current-level="${level}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <i class="fa fa-archive" aria-hidden="true"></i>
                                            <div>Sản phẩm</div>
                                        </div>
                                        <div class="dropdown-item add-child-course" data-child-level="${level + 1}" data-current-level="${level}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <i class="fa fa-book" aria-hidden="true"></i>
                                            <div>Khóa học</div>
                                        </div>
                                    </div>
                                </div>
                            ` : `
                                <a href="/product/detail/${item.id}">
                                    <i class="fa fa-sitemap" title="Xem cấu trúc" aria-hidden="true"></i>
                                </a>
                            `}
                            <i class="fa fa-trash delete-archive-product" title="Xóa" aria-hidden="true" data-toggle="modal" data-target="#deleteOrArchiveModal" data-product-id="${item.id}" data-product-name="${item.moodle_name}" data-product-type="${item.moodle_type}"></i>
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
            const level = $(this).data('child-level');
            const currentLevel = $(this).data('current-level');
            const parentId = $(this).data('parent-id');

            for (let i = currentLevel; i <= 10; i++) {
                localStorage.removeItem(`level-${i}`);
            }
    
            localStorage.setItem(`level-${currentLevel}`, parentId);

            addProductToCategory(categoryId, parentId, level);
        });

        $('.above-block').on('click', '.add-child-course', function(e) {
            e.stopPropagation();
            const categoryId = $(this).data('category-id');
            const level = $(this).data('child-level');
            const currentLevel = $(this).data('current-level');
            const parentId = $(this).data('parent-id');

            for (let i = currentLevel; i <= 10; i++) {
                localStorage.removeItem(`level-${i}`);
            }
    
            localStorage.setItem(`level-${currentLevel}`, parentId);

            addCourseToCategory(categoryId, parentId, level);
        });

        $('.tree-items').off('click', '.delete-archive-product').on('click', '.delete-archive-product', function (e) {
            e.stopPropagation();
            // Lấy thông tin từ data-attribute của nút đã click
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const productType = $(this).data('product-type');
        
            // Cập nhật thông tin vào modal
            $('#modal_name_product').text(productName);
        
            // Gán ID và loại sản phẩm vào các nút delete và archive trong modal
            $('.delete-product').attr('data-product-id', productId);
            $('.delete-product').attr('data-product-type', productType);
            $('.archive-product').attr('data-product-id', productId);
            $('.archive-product').attr('data-product-type', productType);
        
            // Hiển thị modal
            $('#deleteOrArchiveModal').modal('show');
        });

        // Nếu clickedCategoryId có giá trị, thêm class 'active' vào phần tử vừa click
        // if (clickedCategoryId) {
        //     $(`.cover-category-${clickedCategoryId} .view-product-children`).addClass('active');
        // }

        // $('.view-product-children.active').click();

        autoClickLevels();

        // if (clickedCategoryId === null && categories.length > 0) {
        //     clickedCategoryId = categories[0].id;
        //     const clickedCategoryType = categories[0].moodle_type; 

        //     $('#setting_product')
        //         .attr('data-type', 'category')
        //         .attr('data-category-id', clickedCategoryId);

        //     console.log("Category ID đầu tiên:", clickedCategoryId);
        //     console.log("Category Type đầu tiên:", clickedCategoryType);
        // } else {
        //     $('#setting_product')
        //         .attr('data-type', 'category')
        //         .attr('data-category-id', clickedCategoryId);
        //     console.log("Clicked Category ID:", clickedCategoryId);
        // }

    }

    function addProductToCategory(categoryId, parentId, level) {
        console.log("Thêm sản phẩm vào danh mục " + categoryId + " ở parent " + parentId);
        const clickedCategoryId = parentId;
        const currentUser = localStorage.getItem('currentUser');
        $.ajax({
            url: "/api/lms/category/create",
            type: "POST",
            data: {
                parent_category_lms: categoryId,
                parent_id: parentId,
                currentUser: currentUser,
                level: level
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

    function addCourseToCategory(categoryId, parentId, level) {
        // Lưu lại `parentId` để active sau khi load lại danh sách
        const clickedCategoryId = parentId;
        const currentUser = localStorage.getItem('currentUser');
        $.ajax({
            url: "/api/lms/course/create",
            type: "POST",
            data: {
                parent_id: parentId,
                category_lms: categoryId,
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
                    loadCategoryList(clickedCategoryId);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    $('.arrow-toogle').click(function() {
        // 1. Chính nó sẽ thêm class hide
        $(this).addClass('hide');

        // 2. div class left-part width: 5%
        $('.left-part').css('width', '5%');

        // 3. class="above-block" và class="below-block" thêm class hide
        $('.above-block, .below-block').addClass('hide');

        // 4. class="above-block-toggle" và icon-pagetree remove class hide
        $('.above-block-toggle, .icon-pagetree').removeClass('hide');

        $('.right-part').attr('style', 'max-width: 100%;');
    });

    // Khi click vào icon-pagetree
    $('.icon-pagetree').click(function() {
        // Quay về trạng thái ban đầu

        // 1. Hiển thị lại arrow-toogle
        $('.arrow-toogle').removeClass('hide');

        // 2. left-part quay về width ban đầu (có thể thay thế bằng giá trị bạn muốn)
        $('.left-part').removeAttr('style');

        // 3. Hiển thị lại above-block và below-block
        $('.above-block, .below-block').removeClass('hide');

        // 4. Ẩn lại above-block-toggle và icon-pagetree
        $('.above-block-toggle, .icon-pagetree').addClass('hide');

        $('.right-part').attr('style', '');
    });

    $('#setting_product').on('click', function() {
        $('.right-side-content').toggleClass('hide');
    });
    
    // $('.btn-group').on('click', '.fa-plus', function(e) {
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

    $('.view-course-children').click(function(e) {
        // Ngăn chặn hành vi mặc định và sự kiện click bong bóng
        // e.preventDefault();
        // e.stopPropagation();

        // $(".dropdown-menu").removeClass("show");

        var $this = $(this);

        // Thay đổi trạng thái active cho item hiện tại
        // $('.tree-item').removeClass('item-active'); // Xóa class active ở tất cả các item
        // $this.addClass('item-active'); // Thêm class active vào item hiện tại
        $('.tree-item').removeClass('active'); // Xóa class active ở tất cả các item
        $this.addClass('active');

        $('#info_course').removeClass('hide');

        // Đảm bảo #info_product luôn bị ẩn
        $('#info_product').addClass('hide');
    });

    // Bắt sự kiện click trên nút btn-collapse trong khối below-block
    $('.below-block .btn-collapse').click(function(e) {
        // Ngăn chặn hành vi mặc định và sự kiện click bong bóng
        e.preventDefault();
        e.stopPropagation();

        // $(".dropdown-menu").removeClass("show");

        var $this = $(this);
        var categoryId = $this.data('category-id');

        // Xác định khối hiện tại (below-block)
        var $currentBlock = $this.closest('.below-block');

        // Toggle trạng thái mở/đóng của item hiện tại
        var $containElement = $currentBlock.find('.contain-category-' + categoryId);
        $containElement.slideToggle();

        // Đổi icon collapse/expand cho item hiện tại
        $this.toggleClass('fa-chevron-down fa-chevron-right');

        // Thay đổi trạng thái active cho item hiện tại, chỉ trong khối hiện tại
        // $currentBlock.find('.tree-item').removeClass('item-active');
        // $this.closest('.tree-item').addClass('item-active'); // thêm class active cho tree-item chứa btn-collapse
        $currentBlock.find('.tree-item').removeClass('active');
        $this.closest('.tree-item').addClass('active');
    });

    // Chỉ set item đầu tiên active trong mỗi khối (above-block và below-block)
    // $('.above-block .tree-item').first().addClass('item-active');
    $('.above-block .tree-item').first().addClass('active');
    // $('.below-block .tree-item').first().addClass('item-active');

    function showProductTypeDialog(button) {
        // Xóa hộp thoại cũ nếu có
        const oldDialog = document.querySelector('.product-type-dialog');
        if (oldDialog) oldDialog.remove();

        const dialog = document.createElement('div');
        dialog.className = 'product-type-dialog';
        dialog.innerHTML = `
            <div class="dialog-content">
                <h3>Chọn loại sản phẩm</h3>
                <button class="btn btn-primary" data-type="product">Sản phẩm</button>
                <button class="btn btn-primary" data-type="course">Khóa học</button>
            </div>
        `;

        // Tính toán vị trí của hộp thoại
        const rect = button.getBoundingClientRect();
        console.log(rect);
        dialog.style.position = 'absolute';
        dialog.style.left = `${rect.left}px`; // Sử dụng rect.left để căn chỉnh sang trái
        dialog.style.top = `${rect.bottom + window.scrollY}px`; // Đặt hộp thoại ở dưới nút, thêm window.scrollY để điều chỉnh vị trí khi cuộn

        document.body.append(dialog);
        // console.log('Dialog added to body:', dialog);

        dialog.addEventListener('click', function(event) {
            const target = event.target;
            if (target.tagName === 'BUTTON') {
                const productType = target.getAttribute('data-type');
                console.log('Thêm mục con loại:', productType);
                // Gọi API để thêm mục con và cập nhật giao diện
                // Ví dụ: addSubItem(button.closest('.product-item'), productType);

                dialog.remove();
            }
            event.stopPropagation(); // Ngăn sự kiện lan ra document
        });
    }

    $('.above-block').on('click', '.tree-item', function(e) {
        // Ngăn chặn hành vi mặc định và sự kiện click bong bóng
        // e.preventDefault();
        e.stopPropagation();
        // $(".dropdown-menu").removeClass("show");
        const $addSubItemButton = $(e.target).closest('.fa-plus');
        const $trashItemButton = $(e.target).closest('.fa-trash');
        const $viewItemButton = $(e.target).closest('.fa-sitemap');
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

        // **1. Xóa các `localStorage` không cần thiết**
        // Loại bỏ tất cả các cấp lớn hơn và nhỏ hơn `currentLevel`
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
        if(dataType == 'category'){
            $('#info_product').removeClass('hide');
            $('#info_course').addClass('hide');
            getDetailCategory(categoryId);
        }
        if(dataType == 'course'){
            $('#info_product').addClass('hide');
            $('#info_course').removeClass('hide');
            getDetailCourse(categoryId);
        }
        // Toggle class hide cho #info_product
    });

    function getDetailCategory(parent_id){
        $.ajax({
            url: "/api/lms/category/detail",
            type: "POST",
            data: {
                parent_id: parent_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                renderCategoryDetail(response.data, parent_id);
                renderHtmlRightPart(response.data, parent_id);
                renderFormCategory(response.main, parent_id, response.selectedParentId, response.isSubCategory, response.categoryParentData);
            },
            error: function(xhr, status, error) {
                // console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    function getDetailCourse(course_id){
        $.ajax({
            url: "/api/lms/course/detail",
            type: "POST",
            data: {
                course_id: course_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                renderHtmlRightPart(response.data, course_id);
                renderFormCourse(response.main, course_id, response.selectedParentId, response.categoryData)
            },
            error: function(xhr, status, error) {
                // console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    function renderCategoryDetail(response, parent_id) {
        if (response.length > 0) {
            var parentElement = $('.contain-category-' + parent_id); // Lấy phần tử cha
            parentElement.empty(); // Xóa các phần tử con cũ
            // console.log(parentElement);
            $('#setting_product')
                .attr('data-type', 'category')
                .attr('data-category-id', parent_id);
    
            response.forEach(function(item, index) {
                // Kiểm tra loại item (course hoặc không)
                var icon = item.moodle_type === 'course' ? 'fa-book' : 'fa-archive'; // Chọn icon
                var viewClass = item.moodle_type === 'course' ? 'course' : 'product'; // Chọn icon
                var rightItemHtml = '';

                let iconArrow = '';
                let containCategory = '';

                let level = item.level;
    
                if (item.moodle_type === 'course') {
                    // Nếu là course, thêm icon sitemap
                    rightItemHtml = `
                        <a href="/product/detail/${item.id}">
                            <i class="fa fa-sitemap" title="Xem cấu trúc" aria-hidden="true"></i>
                        </a>
                        <i class="fa fa-trash delete-archive-product" title="Xóa" aria-hidden="true" data-toggle="modal" data-target="#deleteOrArchiveModal" data-product-id="${item.id}" data-product-name="${item.moodle_name}" data-product-type="${item.moodle_type}"></i>
                    `;
                } else {
                    iconArrow = `<i class="fa btn-expand-collapse fa-chevron-right btn-collapse" title="Mở rộng" data-category-id='${item.id}' aria-hidden="true"></i>`;
                    
                    containCategory = `<div data-current="${item.id}"
                        class="tree-item-contain contain-category-${item.id} tree-item-move ui-sortable 123"
                        style="width: calc(100% - 4%); display: none;">
                    </div>`
                    
                    // Nếu không phải là course, thêm div btn-group
                    rightItemHtml = `
                        <div class="btn-group">
                            <i class="fa fa-plus" title="Thêm sản phẩm con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
                            <div class="dropdown-menu">
                                <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại sản phẩm</b></h5>
                                <div class="dropdown-item add-child-product" data-child-level="${level + 1}" data-current-level="${level}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-archive" aria-hidden="true"></i>
                                    <div>Sản phẩm</div>
                                </div>
                                <div class="dropdown-item add-child-course" data-child-level="${level + 1}" data-current-level="${level}" data-category-id="${item.moodle_id}" data-parent-id="${item.id}">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-book" aria-hidden="true"></i>
                                    <div>Khóa học</div>
                                </div>
                            </div>
                        </div>
                        <i class="fa fa-trash delete-archive-product" title="Xóa" aria-hidden="true" data-toggle="modal" data-target="#deleteOrArchiveModal" data-product-id="${item.id}" data-product-name="${item.moodle_name}" data-product-type="${item.moodle_type}"></i>
                    `;
                }
    
                // Tạo HTML cho từng item
                var newItem = `
                    <div class="tree-item-cover cover-category-${item.id} ui-sortable-handle">
                        <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="${item.id}">
                        <div class="level-${level} tree-item view-${viewClass}-children view-course-children-${item.id}" data-level=${level} data-type="${item.moodle_type}" data-category-id="${item.id}" style="width: calc(100% - 4%);">
                            <div class="left-item">
                                <!-- <i class="fas fa-arrows-alt drag-handle"></i> -->
                                ${iconArrow}
                                <i class="fa ${icon}" aria-hidden="true"></i>
                                <div class="product-name-w100 product-name-${item.id}" data-category-id="${item.id}">${item.moodle_name}</div>
                            </div>
                            <div class="right-item">
                                ${rightItemHtml}
                            </div>
                        </div>
                    </div>
                    ${containCategory}
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

    function renderFormCategory(response, parent_id, selectedParentId, isSubCategory, categoryParentData) {
        var parentElement = $('#info_product');
        parentElement.empty();
    
        // Kiểm tra nếu là SubCategory thì hiển thị select Thư mục cha
        var parentSelectHTML = '';
        if (isSubCategory == 'true') {
            parentSelectHTML = `
                <div class="mb-2 product_parent-box">
                    <label>Thư mục cha</label>
                    <select id="product_parent" name="product_parent" style="width: 100%;">
                        ${categoryParentData.map(category => {
                            // Kiểm tra nếu category id bằng với selectedParentId thì thêm selected
                            const isSelected = category.id === selectedParentId ? 'selected' : '';
                            return `<option value="${category.id}" ${isSelected}>${category.moodle_name}</option>`;
                        }).join('')}
                    </select>
                </div>
            `;
        }
    
        // Tạo nội dung HTML mới
        var newItem = `
            <div class="product-name">
                <h4 id="label_product_name">${response.moodle_name}</h4>
            </div>
            <div class="mb-2">
                <label>Tên sản phẩm</label>
                <input type="text" id="product_name" name="product_name" value="${response.moodle_name || 'Nhập tên sản phẩm'}">
            </div>
            <div class="mb-2">
                <label>Mã sản phẩm</label>
                <input type="text" id="product_code" value="${response.idnumber || ''}" name="product_code" placeholder="Nhập mã sản phẩm">
            </div>
            ${parentSelectHTML}
            <div class="mb-2">
                <label>Mô tả sản phẩm</label>
                <textarea id="product_description" name="product_description" placeholder="Nhập mô tả" rows="3">${response.description || ''}</textarea>
            </div>
            <div class="mb-2">
                <label>Người tạo</label>
                <input type="text" id="product_created_by" value="${response.creator || ''}" disabled="">
            </div>
            <div class="mb-2">
                <label>Ngày tạo</label>
                <input type="text" id="product_created_at" value="${response.created_at}" disabled="">
            </div>
            <div class="mb-2">
                <label>Người cập nhật</label>
                <input type="text" id="product_updated_by" value="${response.modifier || ''}" disabled="">
            </div>
            <div class="mb-2">
                <label>Ngày cập nhật</label>
                <input type="text" id="product_updated_at" value="${response.updated_at}" disabled="">
            </div>
            <div class="mt-4" style="text-align: center">
                <a href="#" class="btn btn-primary btn-save-product">Cập nhật</a>
            </div>
        `;
    
        // Thêm nội dung vào parentElement
        parentElement.append(newItem); 

        $('.btn-save-product').on('click', function(event) {
            event.preventDefault();
            updateCategory(response.id); // Gọi hàm updateCategory để xử lý cập nhật
        });
    }

    function updateCategory(id) {
        const currentUser = localStorage.getItem('currentUser');
        var productData = {
            id: id,
            name: $('#product_name').val(),
            code: $('#product_code').val(),
            parent_id: $('#product_parent').val(),
            description: $('#product_description').val(),
            currentUser: currentUser
        };
    
        $.ajax({
            url: '/api/lms/category/update',
            method: 'POST',
            data: productData,
            success: function(response) {
                loadCategoryList($('#product_parent').val());
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

    function renderFormCourse(response, course_id, selectedParentId, categoryData) {
        var parentElement = $('#info_course');
        parentElement.empty();
    
        var parentSelectHTML = '';
        parentSelectHTML = `
            <div class="mb-2 product_parent-box">
                <label>Thư mục cha</label>
                <select id="course_parent" name="course_parent" style="width: 100%;">
                    ${categoryData.map(category => {
                        // Kiểm tra nếu category id bằng với selectedParentId thì thêm selected
                        const isSelected = category.id === selectedParentId ? 'selected' : '';
                        return `<option value="${category.id}" ${isSelected}>${category.moodle_name}</option>`;
                    }).join('')}
                </select>
            </div>
        `;

        const startDate = response.startdate ? response.startdate.split(" ")[0] : "";
        const startTime = response.startdate ? response.startdate.split(" ")[1] : "00:00";
        const endDate = response.enddate ? response.enddate.split(" ")[0] : "";
        const endTime = response.enddate ? response.enddate.split(" ")[1] : "00:00";

        const startHour = startTime.split(":")[0];
        const startMinute = startTime.split(":")[1];

        // Tạo phần giờ và phút cho ngày kết thúc
        const endHour = endTime.split(":")[0];
        const endMinute = endTime.split(":")[1];

        var newItem = `
            <div class="course-name">
                <h4 id="label_course_name">${response.moodle_name}</h4>
            </div>
            <div class="mb-2">
                <label>Tên sản phẩm</label>
                <input type="text" id="course_fullname" name="course_fullname" value="${response.moodle_name || ''}" placeholder="Nhập tên sản phẩm">
            </div>
            <div class="mb-2">
                <label>Mã sản phẩm</label>
                <input type="text" id="course_code" value="${response.idnumber || ''}" name="course_code" placeholder="Nhập mã sản phẩm">
            </div>
            ${parentSelectHTML}
            <div class="mb-2">
                <label>Phiên bản</label>
                <input type="text" id="course_version" name="course_version" value="" disabled="">
            </div>
            <div class="mb-2">
                <label>Tên rút gọn</label>
                <input type="text" id="course_shortname" name="course_shortname" value="${response.shortname || ''}" placeholder="Nhập tên rút gọn của sản phẩm">
            </div>
            <div class="mb-2">
                <label>Hình ảnh sản phẩm</label>
                <div style="text-align: right">
                    <i class="btn-edit-image fa fa-edit" data-type="course" title="Tải ảnh mới" aria-hidden="true"></i>
                </div>
                <div style="text-align: center">
                    <img id="course_image" 
                        class="${response.overviewfiles && response.overviewfiles.length > 0 ? '' : 'hidden'}"
                        style="max-width: 200px; height: auto; border: 1px solid #dadada; border-radius: 4px;" 
                        src="${response.overviewfiles && response.overviewfiles.length > 0 ? response.overviewfiles[0].fileurl : ''}">
                </div>
                <div class="course-upload-image hidden"></div>
            </div>
            <div class="mb-2">
                <label>Mô tả sản phẩm</label>
                <textarea id="course_summary" name="course_summary" placeholder="Nhập mô tả" rows="3">${response.summary || ''}</textarea>
            </div>
            <div class="mb-2">
                <label>Trạng thái</label>
                <select id="course_released" name="course_released" style="width: 100%;">
                    <option ${response.visible == 0 ? 'selected' : ''} value="0">Dừng phát hành</option>
                    <option ${response.visible == 1 ? 'selected' : ''} value="1">Đang phát hành</option>
                </select>
            </div>
            <div class="mb-2">
                <label>Ngày bắt đầu khoá học</label>
                <div style="display: flex;">
                    <input type="date" id="course_startdate" class="search-date form-control  search-date-startdate" value="${startDate}">
                    <select class="ml-2" id="course_starthour" name="course_starthour">
                        ${[...Array(24).keys()].map(hour => {
                            const hourValue = hour < 10 ? '0' + hour : hour;
                            const selected = (hourValue == startHour) ? 'selected' : '';
                            return `<option value="${hourValue}" ${selected}>${hourValue}</option>`;
                        }).join('')}
                    </select>
                    <div class="ml-1 mr-1">:</div>
                    <select id="course_startminute" name="course_startminute">
                        ${[...Array(60).keys()].map(minute => {
                            const minuteValue = minute < 10 ? '0' + minute : minute;
                            const selected = (minuteValue == startMinute) ? 'selected' : '';
                            return `<option value="${minuteValue}" ${selected}>${minuteValue}</option>`;
                        }).join('')}
                    </select>
                </div>
            </div>
            <div class="mb-2">
                <label>Ngày kết thúc khóa học</label>
                <div style="display: flex;">
                    <input type="date" id="course_enddate" class="search-date form-control  search-date-enddate" value="${endDate}">
                    <select class="ml-2" id="course_endhour" name="course_endhour">
                        ${[...Array(24).keys()].map(hour => {
                            const hourValue = hour < 10 ? '0' + hour : hour;
                            const selected = (hourValue == endHour) ? 'selected' : '';
                            return `<option value="${hourValue}" ${selected}>${hourValue}</option>`;
                        }).join('')}
                    </select>
                    <div class="ml-1 mr-1">:</div>
                    <select id="course_endminute" name="course_endminute">
                        ${[...Array(60).keys()].map(minute => {
                            const minuteValue = minute < 10 ? '0' + minute : minute;
                            const selected = (minuteValue == endMinute) ? 'selected' : '';
                            return `<option value="${minuteValue}" ${selected}>${minuteValue}</option>`;
                        }).join('')}
                    </select>
                </div>
            </div>
            <div class="mb-2">
                <label>Định dạng</label>
                <select id="course_format" name="course_format" style="width: 100%;">
                    <option ${response.format == 'singleactivity' ? 'selected' : ''} value="singleactivity">Định dạng hoạt động đơn lẻ</option>
                    <option ${response.format == 'social' ? 'selected' : ''} value="social">Định dạng kiểu diễn đàn cộng đồng</option>
                    <option ${response.format == 'topics' ? 'selected' : ''} value="topics">Định dạng chủ đề</option>
                    <option ${response.format == 'weeks' ? 'selected' : ''} value="weeks">Định dạng theo tuần</option>
                </select>
            </div>
            <div class="mb-2">
                <label>Bắt buộc ngôn ngữ</label>
                <select id="course_lang" name="course_lang" style="width: 100%;">
                    <option value="">Không bắt buộc</option>
                    <option ${response.lang == 'en' ? 'selected' : ''} value="en">English ‎(en)‎</option>
                    <option ${response.lang == 'vi' ? 'selected' : ''} value="vi">Tiếng Việt ‎(vi)‎</option>
                </select>
            </div>
            <div class="mb-2">
                <label>Dung lượng tối đa được tải lên</label>
                <select id="course_maxbytes" name="course_maxbytes" style="width: 100%;">
                    <option value="0">Hệ thống giới hạn đăng tải (250MB)</option>
                    <option value="262144000">250MB</option>
                    <option value="104857600">100MB</option>
                    <option value="52428800">50MB</option>
                    <option value="20971520">20MB</option>
                    <option value="10485760">10MB</option>
                    <option value="5242880">5MB</option>
                    <option value="2097152">2MB</option>
                    <option value="1048576">1MB</option>
                    <option value="512000">500KB</option>
                    <option value="102400">100KB</option>
                    <option value="51200">50KB</option>
                    <option value="10240">10KB</option>
                </select>
            </div>
            <div class="mt-4" style="text-align: center">
                <a class="btn btn-primary btn-save-course">Cập nhật</a>
            </div>
        `;
    
        // Thêm nội dung vào parentElement
        parentElement.append(newItem); 

        $('.btn-save-course').on('click', function(event) {
            event.preventDefault();
            updateCourse(response.id);
        });
    }

    // function updateCourse(id) {
    //     const currentUser = localStorage.getItem('currentUser');
    //     var course_start_date = $('#course_startdate').val() + ' ' + $('#course_starthour').val() + ':' + $('#course_startminute').val();
    //     var course_end_date = $('#course_enddate').val() + ' ' + $('#course_endhour').val() + ':' + $('#course_endminute').val();
    //     var productData = {
    //         id: id,
    //         fullname: $('#course_fullname').val(),
    //         idNumber: $('#course_code').val(),
    //         shortname: $('#course_shortname').val(),
    //         summary: $('#course_summary').val(),
    //         visible: $('#course_released').val(),
    //         parent_id: $('#course_parent').val(),
    //         startdate: course_start_date,
    //         enddate: course_end_date,
    //         format: $('#course_format').val(),
    //         lang: $('#course_lang').val(),
    //         maxbytes: $('#course_maxbytes').val(),
    //         currentUser: currentUser
    //     };
    
    //     $.ajax({
    //         url: '/api/lms/course/update',
    //         method: 'POST',
    //         data: productData,
    //         success: function(response) {
    //             loadCategoryList();
    //             if (response.success) {
    //                 $('.message-product .alert').addClass('alert-success');
    //                 $('.message-product .alert').removeClass('alert-danger');
    //                 $('.message-product .alert #content_message').text(response.success);
    //             } else {
    //                 $('.message-product .alert').removeClass('alert-success');
    //                 $('.message-product .alert').addClass('alert-danger');
    //                 $('.message-product .alert #content_message').text(response.error);
    //             }
    //             $('.message-product').removeClass('hidden');
    //             $('html, body').animate({ scrollTop: 0 }, 'fast');
    //         },
    //         error: function(xhr, status, error) {
    //             console.log(error);
    //         }
    //     });
    // }

    function updateCourse(id) {
        const currentUser = localStorage.getItem('currentUser');
        var course_start_date = $('#course_startdate').val() + ' ' + $('#course_starthour').val() + ':' + $('#course_startminute').val();
        var course_end_date = $('#course_enddate').val() + ' ' + $('#course_endhour').val() + ':' + $('#course_endminute').val();
    
        // Tạo FormData để chứa dữ liệu và file
        let formData = new FormData();
        formData.append('id', id);
        formData.append('fullname', $('#course_fullname').val());
        formData.append('idNumber', $('#course_code').val());
        formData.append('shortname', $('#course_shortname').val());
        formData.append('summary', $('#course_summary').val());
        formData.append('visible', $('#course_released').val());
        formData.append('parent_id', $('#course_parent').val());
        formData.append('startdate', course_start_date);
        formData.append('enddate', course_end_date);
        formData.append('format', $('#course_format').val());
        formData.append('lang', $('#course_lang').val());
        formData.append('maxbytes', $('#course_maxbytes').val());
        formData.append('currentUser', currentUser);

        let courseImage = $('#image_course')[0]?.files[0];
        if (courseImage) {
            formData.append('courseImage', courseImage);
        }
    
        $.ajax({
            url: '/api/lms/course/update',
            method: 'POST',
            data: formData,
            processData: false, // Không xử lý dữ liệu
            contentType: false, // Không thiết lập kiểu content-type mặc định
            success: function(response) {
                loadCategoryList($('#course_parent').val());
                $('.message-product').removeClass('hidden');
                if (response.success) {
                    $('.message-product .alert').addClass('alert-success');
                    $('.message-product .alert').removeClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.success);
                } else {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                }
                $('html, body').animate({ scrollTop: 0 }, 'fast');
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }
    
    // Sự kiện click vào nút "Xóa" trong modal
    $('.delete-product').on('click', function () {
        // Lấy thông tin từ data-attribute của nút
        const productId = $(this).attr('data-product-id');
        const productType = $(this).attr('data-product-type');

        if(productType == 'category'){
            deleteCategory(productId)
        }
        
        if(productType == 'course'){
            deleteCourse(productId)
        }
    });

    function deleteCategory(productId){
        $.ajax({
            url: '/api/lms/category/delete',
            type: 'POST',
            data: {
                id: productId,
            },
            success: function (response) {
                loadCategoryList();
                $('.message-product').removeClass('hidden');
                if (response.success) {
                    $('.message-product .alert').addClass('alert-success');
                    $('.message-product .alert').removeClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.success);
                } else {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                }

                $('#deleteOrArchiveModal').modal('hide');
                $('html, body').animate({ scrollTop: 0 }, 'fast');
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }

    function deleteCourse(productId){
        $.ajax({
            url: '/api/lms/course/delete',
            type: 'POST',
            data: {
                id: productId,
            },
            success: function (response) {
                loadCategoryList();
                $('.message-product').removeClass('hidden');
                if (response.success) {
                    $('.message-product .alert').addClass('alert-success');
                    $('.message-product .alert').removeClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.success);
                } else {
                    $('.message-product .alert').removeClass('alert-success');
                    $('.message-product .alert').addClass('alert-danger');
                    $('.message-product .alert #content_message').text(response.error);
                }

                $('#deleteOrArchiveModal').modal('hide');
                $('html, body').animate({ scrollTop: 0 }, 'fast');
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }


    $('.input-search').on('input', debounce(function() {
        const searchTerm = this.value.trim();
        console.log(searchTerm);
        var parentElement = $('.results-search');
        parentElement.empty();
        if (searchTerm.length >= 1) {
            fetchProducts(searchTerm);
        }
    }, 300));

    function fetchProducts(searchTerm) {
        $.ajax({
            url: '/api/products/search',
            type: 'POST',
            data: {
                searchTerm: searchTerm,
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
            var icon = item.moodle_type === 'course' ? 'fa-book' : 'fa-archive'; // Chọn icon
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
            url: '/api/products/search',
            type: 'POST',
            data: {
                id: categoryId,
            },
            success: function (response) {
                console.log(response);
                // updateProductList(response);
                renderCategoryList(response);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });
    
});
