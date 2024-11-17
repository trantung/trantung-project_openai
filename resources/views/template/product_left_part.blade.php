<div class="left-part">
    <div class="above-block-toggle hide">
        <img src="{{ asset('images/sidebar-closed-pagetree.svg') }}" title="Hiển thị cây sản phẩm" class="icon-pagetree hide">
    </div>
    <div class="above-block">
        <div class="mb-2" style="text-align: right">
            <i class="fa fa-arrow-left arrow-toogle" title="Ẩn cây sản phẩm" aria-hidden="true"></i>
        </div>
        <div class="row mb-2 box-search">
            <div class="col-8 mb-2" style="padding-right:0;">{{ $name1 }}</div>
            <div class="col-12">
                <input class="input-search" style="width: 100%;" type="text" name="search-product-name" placeholder="Nhập tên/ Mã sản phẩm">
            </div>
        </div>
        @if(Request::is('product/detail*'))
            <div class="mt-4 mb-2" style="text-align: right">
                <button class="btn-secondary" data-type="section" data-course-id="{{$courseData->id}}" id="add_product"><i class="fa fa-plus" aria-hidden="true"></i> Sản phẩm </button>
            </div>
            <div class="tree-items ui-sortable">
                @include('template.product_detail.product_detail_tree_item')
            </div>
        @endif
        @if(Request::is('products*'))
            <div class="mt-4 mb-2" style="text-align: right">
                <button class="btn-secondary" data-type="category" id="add_product"><i class="fa fa-plus" aria-hidden="true"></i> Sản phẩm </button>
            </div>
            <div class="tree-items ui-sortable">
                @include('template.product.product_tree_item')
            </div>
        @endif
        {{-- <div class="tree-items ui-sortable">
            @foreach($products as $product)
                @if(Request::is('product/detail*'))
                    @include('template.product_detail.product_detail_tree_item', ['item' => $product, 'level' => 1])
                @endif
            @endforeach
        </div> --}}
    </div>
    {{-- @if(Request::is('products*'))
        <div class="below-block">
            <div class="tree-item-cover cover-category-1">
                <div class="level-1 tree-item view-product-children-1" data-category-id="1">
                    <div class="left-item">
                        <i class="fa btn-expand-collapse fa-chevron-down btn-collapse" title="Thu gọn" data-category-id="1" aria-hidden="true"></i>
                        <i class="fa fa-university" aria-hidden="true"></i>
                        <div>Ngân hàng khóa học</div>
                    </div>
                    <div class="right-item">
                        <i class="fa fa-plus add-child-course" data-child-level="2" data-category-id="1" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="tree-item-contain tree-item-contain-1 contain-category-1 ui-sortable">
                    <div class="tree-item-cover cover-course-182 ui-sortable-handle">
                        <div class="level-2 tree-item view-course-children view-course-children-182" data-course-id="182">
                            <div class="left-item">
                                <i class="fas fa-arrows-alt drag-handle"></i>
                                <i class="fa fa-book" aria-hidden="true"></i>
                                <div class="course-name-w100 course-name-182" data-course-id="182">Sản phẩm 77</div>
                            </div>
                            <div class="right-item">
                                <a href="https://english.ican.vn/classroom/local/omo_management/course/detail.php?course_id=182">
                                    <i class="fa fa-sitemap" title="Xem cấu trúc" aria-hidden="true"></i>
                                </a>
                                <i class="fa fa-trash " title="Xóa" data-toggle="modal" data-target="#deleteModal" data-product-id="182" data-product-name="Sản phẩm 77" data-product-type="course" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#add_product').on('click', function(e) {
            e.preventDefault();
            const dataType = $(this).data('type');
            if(dataType == 'section'){
                const couseId = $(this).data('course-id');
                createSection(couseId)
            }

            if(dataType == 'category'){
                createCategory()
            }
            
        });
    });

    function createCategory(){
        $.ajax({
            url: "/api/lms/category/create",
            type: "POST",
            // dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response) {
                    loadCategoryList();
                }
            },
            error: function(xhr, status, error) {
                // console.error('Error:', error);
                // alert('Failed to create category');
            }
        });
    }

    function createSection(couseId){
        $.ajax({
            url: "/api/lms/section/create",
            type: "POST",
            data: {
                couseId: couseId
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
</script>
