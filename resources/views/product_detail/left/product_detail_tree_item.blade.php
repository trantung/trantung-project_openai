{{-- <div class="tree-item-cover cover-category-{{ $item['id'] }}" data-level="1" data-cover-category="{{ $item['id'] }}">
    <div class="level-1 tree-item" data-category-id="{{ $item['id'] }}" style="width: calc(100% - 0%);">
        <div class="left-item">
            <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
            <i class="fa btn-expand-collapse btn-collapse fa-chevron-right" title="Thu gọn" aria-hidden="true" data-child-level="1" data-type="course_sections" data-category-id="{{ $item['id'] }}"></i>

            @if ($item['type'] == 'folder')
                <i class="fa fa-folder-open fa-folder-color expand-folder" aria-hidden="true" data-type="course_sections" data-path="{{ $item['id'] }}" data-child-level="1" data-category-id="{{ $item['id'] }}"></i>
                <div class="expand-folder box-name" data-type="course_sections" data-path="{{ $item['id'] }}" data-child-level="1" data-category-id="{{ $item['id'] }}" id="folder-{{ $item['id'] }}">{{ $item['name'] }}</div>
            @else
                <i class="fa fa-file fa-folder-color" aria-hidden="true"></i>
                <div class="expand-folder box-name" id="file-{{ $item['id'] }}">{{ $item['name'] }}</div>
            @endif
        </div>
        <div class="right-item">
            <div class="btn-group">
                <i class="fa fa-plus icon-dropdown-menu" title="Thêm thư mục con" aria-hidden="true"></i>
                <i class="fa fa-trash icon-action-delete" title="Xoá" aria-hidden="true" data-type="{{ $item['type'] }}" data-category-id="{{ $item['id'] }}" data-name="{{ $item['name'] }}"></i>
            </div>
        </div>
    </div>

    @if (isset($item['children']) && !empty($item['children']))
        <div data-current="{{ $item['id'] }}" class="tree-item-contain tree-item-contain-1 contain-category-{{ $item['id'] }} ui-sortable" style="display: none;width: 100%;">
            @foreach ($item['children'] as $child)
                <div class="tree-item-cover cover-category-{{ $child['id'] }}" data-level="2" data-cover-category="{{ $child['id'] }}">
                    <div class="level-2 tree-item" data-category-id="{{ $child['id'] }}" style="width: calc(100% - 4%);">
                        <div class="left-item">
                            <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
                            <i class="fa btn-expand-collapse btn-collapse fa-chevron-right" title="Thu gọn" aria-hidden="true" data-child-level="1" data-type="course_sections" data-category-id="{{ $item['id'] }}"></i>
                            @if ($child['type'] == 'folder')
                                <i class="fa fa-folder-open fa-folder-color expand-folder" aria-hidden="true" data-type="course_sections" data-path="{{ $item['id'] }}/{{ $child['id'] }}" data-child-level="2" data-category-id="{{ $child['id'] }}"></i>
                                <div class="expand-folder box-name" data-type="course_sections" data-path="{{ $item['id'] }}/{{ $child['id'] }}" data-child-level="2" data-category-id="{{ $child['id'] }}" id="folder-{{ $child['id'] }}">{{ $child['name'] }}</div>
                            @else
                                <i class="fa fa-file fa-folder-color" aria-hidden="true"></i>
                                <div class="expand-folder box-name" id="file-{{ $child['id'] }}">{{ $child['name'] }}</div>
                            @endif
                        </div>
                        <div class="right-item">
                            <i class="fa fa-trash icon-action-delete" title="Xoá" aria-hidden="true" data-type="{{ $child['type'] }}" data-category-id="{{ $child['id'] }}" data-name="{{ $child['name'] }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div> --}}

{{-- <div class="tree-item-cover cover-category-{{ $item['id'] }}" data-level="{{ $level }}" data-cover-category="{{ $item['id'] }}">
    <div class="item-{{ $item['type'] }}  level-{{ $level }} {{ $level == 1 && $loop->first ? 'active' : '' }} {{ isset($item['status']) ? $item['status'] : '' }} tree-item"
        data-category-id="{{ $item['id'] }}"
        data-item-type="{{ $item['type'] }}"
        style="width: calc(100% - {{ ($level - 1) * 4 }}%);">
        <div class="left-item">
            <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
            @if ($item['type'] == 'folder')
                <i class="fa btn-expand-collapse btn-collapse {{ ($level == 1 && $loop->first) || (isset($loop->parent) && $loop->parent->first && $loop->first) ? 'fa-chevron-down' : 'fa-chevron-right' }}"
                title="{{ ($level == 1 && $loop->first) || (isset($loop->parent) && $loop->parent->first && $loop->first) ? 'Thu gọn' : 'Mở rộng' }}" aria-hidden="true" data-child-level="1" data-type="course_sections" data-category-id="{{ $item['id'] }}"></i>
                <i class="fa fa-folder-open fa-folder-color expand-folder" aria-hidden="true" data-type="course_sections" data-path="{{ $item['id'] }}" data-child-level="{{ $level }}" data-category-id="{{ $item['id'] }}"></i>
                <div class="expand-folder box-name" data-type="course_sections" data-path="{{ $item['id'] }}" data-child-level="{{ $level }}" data-category-id="{{ $item['id'] }}" id="folder-{{ $item['id'] }}">{{ $item['name'] }}</div>
            @endif

            @if ($item['type'] == 'quiz')
                <i class="fa fa-list-ol expand-folder" aria-hidden="true" data-type="course_sections" data-path="{{ $item['id'] }}" data-child-level="{{ $level }}" data-category-id="{{ $item['id'] }}"></i>
                <div class="expand-{{ $item['type'] }} box-name" data-type="course_sections"
                    data-path="{{ $item['id'] }}"
                    data-child-level="{{ $level }}"
                    data-category-id="{{ $item['id'] }}"
                    data-cmid="{{ $item['id'] }}"
                    data-type="{{ $item['type'] }}"
                    data-quiz-id="{{ $item['id'] }}"
                    data-instance="{{ $item['id'] }}"
                    data-parent="10457"
                    data-path="10457/45960"
                    id="{{ $item['type'] }}-{{ $item['id'] }}">
                    {{ $item['name'] }}
                </div>
            @endif

            @if ($item['type'] == 'resource')
                <i class="fa fa-file expand-folder" aria-hidden="true"></i>
                <div class="expand-folder box-name" id="file-{{ $item['id'] }}">{{ $item['name'] }}</div>
            @endif

            @if ($item['type'] == 'video')
                <i class="fa fa-video-camera expand-folder" aria-hidden="true" data-type="video" data-child-level="3" data-category-id="6811" data-instance="6811" data-cmid="65477" data-video-id="6811"></i>
                <div class="expand-{{ $item['type'] }} box-name" 
                    data-path="{{ $item['id'] }}"
                    data-child-level="{{ $level }}"
                    data-category-id="{{ $item['id'] }}"
                    data-cmid="{{ $item['id'] }}"
                    data-type="{{ $item['type'] }}"
                    data-quiz-id="{{ $item['id'] }}"
                    data-instance="{{ $item['id'] }}"
                    data-parent="10457"
                    data-path="10457/45960"
                    id="{{ $item['type'] }}-{{ $item['id'] }}">
                    {{ $item['name'] }}
                </div>
            @endif
        </div>
        <div class="right-item">
            @if ($item['type'] == 'folder')
            <i class="icon_is_lesson is_lesson_10456 fa-bookmark fa" title="Bài học" data-id="12802" data-category-id="10456" aria-hidden="true"></i>
            <div class="btn-group">
                <i class="fa fa-plus icon-dropdown-menu" title="Thêm thư mục con" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></i>
                <div class="dropdown-menu">
                    <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại thư mục</b></h5>
                    <div class="dropdown-item add-child-folder" data-type="course_sections" data-child-level="1" data-category-id="10456">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <i class="fa fa-folder-open fa-folder-color" aria-hidden="true"></i>
                        <div>Thư mục</div>
                    </div>
                    <div class="dropdown-item add-child-quiz" data-type="quiz" data-child-level="1" data-category-id="10456">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <i class="fa fa-list-ol" aria-hidden="true"></i>
                        <div>Quiz</div>
                    </div>
                    <div class="dropdown-item add-child-video" data-type="video" data-child-level="1" data-category-id="10456">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                        <div>Video</div>
                    </div>
                    <div class="dropdown-item add-child-resource" data-cmid="" data-type="resource" data-child-level="1" data-category-id="10456">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <i class="fa fa-file" aria-hidden="true"></i>
                        <div>File</div>
                    </div>
                    <div class="dropdown-item add-interactive-book" data-type="interactive_book" data-child-level="1" data-category-id="10456">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <i class="fa fa-list-ol" aria-hidden="true"></i>
                        <div>Interactive Book</div>
                    </div>
                    <div class="dropdown-item add-child-assign" data-cmid="" data-type="assign" data-child-level="1" data-category-id="10456">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <i class="fa fa-file" aria-hidden="true"></i>
                        <div>Bài tập</div>
                    </div>
                    <div class="dropdown-item add-child-url" data-cmid="" data-type="url" data-child-level="1" data-category-id="10456">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <i class="fa fa-link" aria-hidden="true"></i>
                        <div>URL</div>
                    </div>
                    <div class="dropdown-item add-child-scorm" data-cmid="" data-type="scorm" data-child-level="1" data-category-id="10456">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                        <div>Scorm</div>
                    </div>
                </div>
            </div>
            <i class="fa fa-trash icon-action-delete" title="Xóa" aria-hidden="true" data-type="course_sections" data-child-level="1" data-category-id="10456" data-name="Session 1 - Unit 1 - Lesson 1: Daily life"></i>
            @else
            <div class="btn-group">
                <i class="fa fa-play icon-quiz-action-play-temp play-temp" data-type="quiz" data-href="#" title="Làm bài" aria-hidden="true"></i>
                <i class="fa fa-trash icon-quiz-action-delete" title="Xoá" aria-hidden="true" data-category-id="45960" data-instance="45960" data-cmid="57749" data-type="quiz" data-child-level="3" data-name="Pre class - Ex1"></i>
            </div>
            @endif
        </div>
    </div>

    @if (isset($item['children']) && !empty($item['children']))
        <div data-current="{{ $item['id'] }}" class="tree-item-contain tree-item-contain-{{ $level }} contain-category-{{ $item['id'] }} ui-sortable" style="{{ !($level == 1 && $loop->first) && !(isset($loop->parent) && $loop->parent->first && $loop->first) ? 'display: none;' : '' }}width: 100%;">
            @foreach ($item['children'] as $child)
                @include('template.product_detail.product_detail_tree_item', ['item' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>--}}
