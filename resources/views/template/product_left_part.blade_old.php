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
        <div class="mt-4 mb-2" style="text-align: right">
            <button class="btn-secondary" id="add_product"><i class="fa fa-plus" aria-hidden="true"></i> Sản phẩm </button>
        </div>
        <div class="tree-items ui-sortable">
            <div class="tree-item-cover cover-category-177 ui-sortable-handle">
                <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="177">
                <div class="level-1 tree-item view-product-children view-product-children-177 active" data-category-id="177">
                    <div class="left-item">
                        <i class="fas fa-arrows-alt drag-handle"></i>
                        <i class="fa btn-expand-collapse fa-chevron-down btn-collapse" title="Thu gọn" data-category-id="177" aria-hidden="true"></i>
                        <i class="fa fa-archive" aria-hidden="true"></i>
                        <div class="product-name-w100 product-name-177" data-category-id="177">IELTS Tutoring - Introduction</div>
                    </div>
                    <div class="right-item">
                        <div class="btn-group">
                            <i class="fa fa-plus" title="Thêm sản phẩm con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
                            <div class="dropdown-menu" style="will-change: transform;">
                                <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại sản phẩm</b></h5>
                                <div class="dropdown-item add-child-product" data-child-level="2" data-category-id="177">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-archive" aria-hidden="true"></i>
                                    <div>Sản phẩm</div>
                                </div>
                                <div class="dropdown-item add-child-course" data-child-level="2" data-category-id="177">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-book" aria-hidden="true"></i>
                                    <div>Khóa học</div>
                                </div>
                            </div>
                        </div>
                        <i class="fa fa-trash delete-archive-product" title="Xóa" aria-hidden="true" data-toggle="modal" data-target="#deleteOrArchiveModal" data-product-id="177" data-product-name="IELTS Tutoring - Introduction" data-product-type="product"></i>
                    </div>
                </div>
                <div data-current="177" class="tree-item-contain tree-item-contain-1 contain-category-177 tree-item-move ui-sortable">
                    <div class="tree-item-cover tree-item-course cover-course-171">
                        <div class="level-2 tree-item view-course-children view-course-children-171" style="width: 95%;" data-course-id="171">
                            <div class="left-item">
                                <i class="fas fa-arrows-alt drag-handle"></i>
                                <i class="fa fa-book" aria-hidden="true"></i>
                                <div class="course-name-w100  course-name-171" data-course-id="171">IELTS Tutoring - Introduction</div>
                            </div>
                            <div class="right-item">
                                <a href="{{ route('course.detail', ['course_id' => 171]) }}">
                                    <i class="fa fa-sitemap" title="Xem cấu trúc" aria-hidden="true"></i>
                                </a>
                                <i class="fa fa-trash" title="Xóa" data-toggle="modal" data-target="#deleteOrArchiveModal" data-product-id="171" data-product-name="IELTS Tutoring - Introduction" data-product-type="course" aria-hidden="true"></i>
                            </div>
                        </div>
                        <input type="hidden" class="slot-cover-category slot-cover-category-177" name="cover_category[slot]" value="171">
                    </div>
                </div>
            </div>
            <div class="tree-item-cover cover-category-203 ui-sortable-handle">
                <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="203">
                <div class="level-1 tree-item view-product-children view-product-children-203" data-category-id="203">
                    <div class="left-item">
                        <i class="fas fa-arrows-alt drag-handle"></i>
                        <i class="fa btn-expand-collapse fa-chevron-right btn-collapse" title="Thu gọn" data-category-id="203" aria-hidden="true"></i>
                        <i class="fa fa-archive" aria-hidden="true"></i>
                        <div class="product-name-w100 product-name-203" data-category-id="203">Sản phẩm 114</div>
                    </div>
                    <div class="right-item">
                        <div class="btn-group">
                            <i class="fa fa-plus" title="Thêm sản phẩm con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
                            <div class="dropdown-menu">
                                <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại sản phẩm</b></h5>
                                <div class="dropdown-item add-child-product" data-child-level="2" data-category-id="203">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-archive" aria-hidden="true"></i>
                                    <div>Sản phẩm</div>
                                </div>
                                <div class="dropdown-item add-child-course" data-child-level="2" data-category-id="203">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <i class="fa fa-book" aria-hidden="true"></i>
                                    <div>Khóa học</div>
                                </div>
                            </div>
                        </div>
                        <i class="fa fa-trash delete-archive-product" title="Xóa" aria-hidden="true" data-toggle="modal" data-target="#deleteOrArchiveModal" data-product-id="203" data-product-name="Sản phẩm 114" data-product-type="product"></i>
                    </div>
                </div>
                <div data-current="203" class="tree-item-contain tree-item-contain-1 contain-category-203 tree-item-move ui-sortable" style="display: none;">
                    <div class="tree-item-cover cover-category-204">
                        <div class="level-2 tree-item view-product-children view-product-children-204" style="width: 95%;" data-category-id="204">
                            <div class="left-item">
                                <i class="fas fa-arrows-alt drag-handle"></i>
                                <i class="fa btn-expand-collapse fa-chevron-right btn-collapse" title="Thu gọn" data-category-id="204" aria-hidden="true"></i>
                                <i class="fa fa-archive" aria-hidden="true"></i>
                                <div class="product-name-w100 product-name-204" data-category-id="204">Sản phẩm 115</div>
                            </div>
                            <div class="right-item">
                                <div class="btn-group"><i class="fa fa-plus" title="Thêm sản phẩm con" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                    <div class="dropdown-menu">
                                        <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại sản phẩm</b></h5>
                                        <div class="dropdown-item add-child-product" data-child-level="3" data-category-id="204"><i class="fa fa-plus" aria-hidden="true"></i><i class="fa fa-archive" aria-hidden="true"></i>
                                            <div>Sản phẩm</div>
                                        </div>
                                        <div class="dropdown-item add-child-course" data-child-level="3" data-category-id="204"><i class="fa fa-plus" aria-hidden="true"></i><i class="fa fa-book" aria-hidden="true"></i>
                                            <div>Khóa học</div>
                                        </div>
                                    </div>
                                </div>
                                <i class="fa fa-trash delete-archive-product" title="Xóa" aria-hidden="true" data-toggle="modal" data-target="#deleteOrArchiveModal" data-product-id="204" data-product-name="Sản phẩm 115" data-product-type="product"></i>
                            </div>
                        </div>
                        <input type="hidden" class="slot-cover-category slot-cover-category-203" name="cover_category[slot]" value="204">
                        <div data-current="204" class="tree-item-move tree-item-contain tree-item-contain-2 contain-category-204 ui-sortable" style="width: 95%;display: none;">
                            <div class="tree-item-cover tree-item-course cover-course-204">
                                <div class="level-3 tree-item view-course-children view-course-children-204" style="width: 94.5%;" data-course-id="204">
                                    <div class="left-item">
                                        <i class="fas fa-arrows-alt drag-handle"></i>
                                        <i class="fa fa-book" aria-hidden="true"></i>
                                        <div class="course-name-w100 blur course-name-204" data-course-id="204">Sản phẩm 116</div>
                                    </div>
                                    <div class="right-item">
                                        <a href="https://english.ican.vn/classroom/local/omo_management/course/clone_course.php?course_id=204">
                                            <i class="fa fa-clipboard" title="Chuyển dữ liệu khóa học" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('course.detail', ['course_id' => 171]) }}">
                                            <i class="fa fa-sitemap" title="Xem cấu trúc" aria-hidden="true"></i>
                                        </a>
                                        <i class="fa fa-trash" title="Xóa" data-toggle="modal" data-target="#deleteOrArchiveModal" data-product-id="204" data-product-name="Sản phẩm 116" data-product-type="course" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <input type="hidden" class="slot-cover-category slot-cover-category-204" name="cover_category[slot]" value="204">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
</div>
