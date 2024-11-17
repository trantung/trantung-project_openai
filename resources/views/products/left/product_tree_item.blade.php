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
