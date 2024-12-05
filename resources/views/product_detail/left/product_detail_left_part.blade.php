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
            <button class="btn-secondary" data-type="section" data-course-id="{{$courseData->id}}" id="add_product"><i class="fa fa-plus" aria-hidden="true"></i> Sản phẩm </button>
        </div>
        <div class="tree-items ui-sortable">
            @include('product_detail.left.product_detail_tree_item')
        </div>
    </div>
</div>
