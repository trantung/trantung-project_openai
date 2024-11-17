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
                <div class="results-search">
                    <!-- <div class="s-tree-item-cover s-cover-category-208" data-level="1" data-cover-category="208">
                        <div class="s-level-1 s-tree-item " data-child-level="1" data-category-id="208" style="width: calc(100% - 0%)">
                            <div class="s-left-item">
                                <i class="fa fa-archive" aria-hidden="true" data-child-level="1" data-category-id="208"></i>
                                <div class="s-expand-folder s-box-name" data-child-level="1" data-category-id="208" id="s-folder-208">
                                    Sản phẩm 120
                                </div>
                            </div>
                            <div class="s-right-item"></div>
                            <div class="s-tree-item-contain s-tree-item-contain-1 s-contain-category-208" style="width: calc(100% - 0%)"></div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="mt-4 mb-2" style="text-align: right">
            <button class="btn-secondary" data-type="category" id="add_product"><i class="fa fa-plus" aria-hidden="true"></i> Sản phẩm </button>
        </div>
        <div class="tree-items ui-sortable">
            @include('products.left.product_tree_item')
        </div>
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
