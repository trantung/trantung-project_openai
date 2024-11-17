@extends('layouts.app')

@section('content')
<link href="{{ URL::asset('css/courseDetail.css') }}" rel="stylesheet">

<div id="main-content">
    <div class="container-fluid">
        <div class="row">
            <div id="region-main" class="content-col col-md-12">
                <div id="page-content">
                    <span class="notifications" id="user-notifications"></span>
                    <div role="main">
                        <span id="maincontent"></span>
                        <div class="container boxLoad">
                            <h2>
                                <a href="{{ route('course.index') }}">
                                    <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                                    IELTS Tutoring - Introduction 1
                                </a>
                            </h2>
                            <div class="course-detail-page">
                                <div class="left-part">
                                    <i class="fa fa-arrow-left arrow-toogle" title="Ẩn cây thư mục" aria-hidden="true"></i>
                                    <img src="{{ asset('images/sidebar-closed-pagetree.svg') }}" title="Hiển thị cây thư mục" class="icon-pagetree hide">
                                    <div class="above-block-toggle hide"></div>
                                    <div class="above-block">
                                        <div class="row mb-2 box-search">
                                            <div class="col-8 mb-2" style="padding-right:0;">Tên thư mục</div>
                                            <div class="col-12">
                                                <input style="width: 100%;" type="text" class="input-search" name="search-folder-name" placeholder="Nhập tên thư mục">
                                            </div>
                                        </div>
                                        <div class="mt-4 mb-3" style="text-align: right">
                                            <button class="btn-secondary" id="add_parent_folder"><i class="fa fa-plus" aria-hidden="true"></i> Thư mục</button>
                                        </div>
                                        <div class="tree-items ui-sortable">
                                            <div class="tree-item-cover cover-category-10456 ui-sortable-handle" data-level="1" data-cover-category="10456">
                                                <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="12802">
                                                <div class="level-1 tree-item active" data-category-id="10456" style="width: 100%">
                                                    <div class="left-item">
                                                        <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
                                                        <i class="fa btn-expand-collapse btn-collapse fa-chevron-down" title="Thu gọn" aria-hidden="true" data-type="course_sections" data-child-level="1" data-category-id="10456"></i>
                                                        <i class="fa fa-folder-open fa-folder-color expand-folder" aria-hidden="true" data-type="course_sections" data-child-level="1" data-category-id="10456"></i>
                                                        <div class="expand-folder box-name" data-type="course_sections" data-child-level="1" data-category-id="10456" id="folder-10456" data-path="10456">Session 1 - Unit 1 - Lesson 1: Daily life</div>
                                                    </div>
                                                    <div class="right-item">
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
                                                    </div>
                                                </div>
                                                <div data-current="10456" class="tree-item-contain tree-item-contain-1 contain-category-10456 ui-sortable" style="width: 100%">
                                                    <div class="tree-item-cover cover-category-45932" data-level="3" data-cover-category="45932">
                                                        <div class="item-quiz level-2 tree-item  " style="width: calc(100% - 4%)">
                                                            <div class="left-item"><i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i><i class="fa fa-list-ol expand-folder" aria-hidden="true" data-type="quiz" data-child-level="3" data-category-id="45932" data-instance="45932" data-cmid="57721" data-quiz-id="45932"></i>
                                                                <div class="expand-quiz box-name" data-parent="10456" data-child-level="3" data-category-id="45932" data-instance="45932" data-cmid="57721" data-type="quiz" data-quiz-id="45932" id="quiz-45932" data-path="10456/45932">Pre class - Ex1</div>
                                                            </div>
                                                            <div class="right-item">
                                                                <div class="btn-group">
                                                                    <i class="fa fa-play icon-quiz-action-play-temp play-temp" data-type="quiz" data-href="#" title="Làm bài" aria-hidden="true"></i>
                                                                    <i class="fa fa-trash icon-quiz-action-delete" title="Xoá" aria-hidden="true" data-category-id="45932" data-instance="45932" data-cmid="57721" data-type="quiz" data-child-level="3" data-name="Pre class - Ex1"></i>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" class="slot-cover-category slot-cover-category-10456" name="cover_category[slot]" value="45932">
                                                        </div>
                                                    </div>
                                                    <div class="tree-item-cover cover-category-45933" data-level="3" data-cover-category="45933">
                                                        <div class="item-quiz level-2 tree-item  " style="width: calc(100% - 4%)">
                                                            <div class="left-item">
                                                                <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
                                                                <i class="fa fa-list-ol expand-folder" aria-hidden="true" data-type="quiz" data-child-level="3" data-category-id="45933" data-instance="45933" data-cmid="57722" data-quiz-id="45933"></i>
                                                                <div class="expand-quiz box-name" data-parent="10456" data-child-level="3" data-category-id="45933" data-instance="45933" data-cmid="57722" data-type="quiz" data-quiz-id="45933" id="quiz-45933" data-path="10456/45933">Pre class - Ex 2</div>
                                                            </div>
                                                            <div class="right-item">
                                                                <div class="btn-group">
                                                                    <i class="fa fa-play icon-quiz-action-play-temp play-temp" data-type="quiz" data-href="#" title="Làm bài" aria-hidden="true"></i>
                                                                    <i class="fa fa-trash icon-quiz-action-delete" title="Xoá" aria-hidden="true" data-category-id="45933" data-instance="45933" data-cmid="57722" data-type="quiz" data-child-level="3" data-name="Pre class - Ex 2"></i>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" class="slot-cover-category slot-cover-category-10456" name="cover_category[slot]" value="45933">
                                                        </div>
                                                    </div>
                                                    <div class="tree-item-cover cover-category-3271" data-level="3" data-cover-category="3271">
                                                        <div class="item-resource level-2 tree-item   dimmed " style="width: calc(100% - 4%)">
                                                            <div class="left-item">
                                                                <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
                                                                <i class="fa fa-file expand-folder" aria-hidden="true" data-type="resource" data-child-level="3" data-category-id="3271" data-instance="3271" data-cmid="42480" data-resource-id="3271"></i>
                                                                <div class="expand-resource box-name" data-parent="10456" data-child-level="3" data-category-id="3271" data-instance="3271" data-cmid="42480" data-type="resource" data-resource-id="3271" id="resource-3271" data-path="10456/3271">Lesson 1 - Key</div>
                                                            </div>
                                                            <div class="right-item">
                                                                <div class="btn-group">
                                                                    <i class="fa fa-play icon-resource-action-play-temp play-temp" data-type="resource" data-href="#" title="Làm bài" aria-hidden="true"></i>
                                                                    <i class="fa fa-trash icon-resource-action-delete" title="Xoá" aria-hidden="true" data-category-id="3271" data-instance="3271" data-cmid="42480" data-type="resource" data-child-level="3" data-name="Lesson 1 - Key"></i>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" class="slot-cover-category slot-cover-category-10456" name="cover_category[slot]" value="3271">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tree-item-cover cover-category-11524" data-level="1" data-cover-category="11524">
                                                <div class="level-1 tree-item" data-category-id="11524" style="width: calc(100% - 0%);">
                                                    <div class="left-item">
                                                        <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
                                                        <i class="fa btn-expand-collapse btn-collapse fa-chevron-right" title="Thu gọn" aria-hidden="true" data-child-level="1" data-type="course_sections" data-category-id="11524"></i>
                                                        <i class="fa fa-folder-open fa-folder-color expand-folder" aria-hidden="true" data-type="course_sections" data-path="11524" data-child-level="1" data-category-id="11524"></i>
                                                        <div class="expand-folder box-name" data-type="course_sections" data-path="11524" data-child-level="1" data-category-id="11524" id="folder-11524">Thư mục 2</div>
                                                    </div>
                                                    <div class="right-item"><i class="icon_is_lesson is_lesson_11524 fa-bookmark-o fa" data-id="19792" title="Bài học" data-category-id="11524" aria-hidden="true"></i>
                                                        <div class="btn-group"><i class="fa fa-plus icon-dropdown-menu" title="Thêm thư mục con" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                            <div class="dropdown-menu" style="will-change: transform;">
                                                                <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại thư mục</b></h5>
                                                                <div class="dropdown-item add-child-folder" data-child-level="1" data-type="course_sections" data-category-id="11524">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    <i class="fa fa-folder-open fa-folder-color" aria-hidden="true"></i>
                                                                    <div>Thư mục</div>
                                                                </div>
                                                                <div class="dropdown-item add-child-quiz" data-child-level="1" data-type="quiz" data-category-id="11524">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                                                    <div>Quiz</div>
                                                                </div>
                                                                <div class="dropdown-item add-child-video" data-child-level="1" data-type="video" data-category-id="11524">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                                                                    <div>Video</div>
                                                                </div>
                                                                <div class="dropdown-item add-child-resource" data-child-level="1" data-type="resource" data-category-id="11524">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    <i class="fa fa-file" aria-hidden="true"></i>
                                                                    <div>File</div>
                                                                </div>
                                                                <div class="dropdown-item add-interactive-book" data-child-level="1" data-type="interactive_book" data-category-id="11524">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    <i class="fa fa-list-ol" aria-hidden="true"></i>
                                                                    <div>Interactive Book</div>
                                                                </div>
                                                                <div class="dropdown-item add-child-assign" data-child-level="1" data-type="assign" data-category-id="11524">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    <i class="fa fa-file" aria-hidden="true"></i>
                                                                    <div>Assign</div>
                                                                </div>
                                                                <div class="dropdown-item add-child-url" data-child-level="1" data-type="url" data-category-id="11524">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    <i class="fa fa-link" aria-hidden="true"></i>
                                                                    <div>URL</div>
                                                                </div>
                                                                <div class="dropdown-item add-child-scorm" data-child-level="1" data-type="scorm" data-category-id="11524">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                                                    <div>Scorm</div>
                                                                </div>
                                                            </div>
                                                        </div><i class="fa fa-trash icon-action-delete" title="Xoá" aria-hidden="true" data-type="course_sections" data-child-level="1" data-category-id="11524" data-name="Thư mục 2"></i>
                                                    </div>
                                                </div>
                                                <input type="hidden" class="slot-cover-category slot-cover-category-0" name="cover_category[slot]" value="11524">
                                                <div data-current="11524" class="tree-item-contain tree-item-contain-1 contain-category-11524 ui-sortable" style="display: none;width: 100%;">
                                                    <div class="tree-item-cover cover-category-11525" data-level="2" data-cover-category="11525">
                                                        <div class="level-2 tree-item" data-category-id="11525" style="width: calc(100% - 4%);">
                                                            <div class="left-item">
                                                                <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
                                                                <i class="fa btn-expand-collapse btn-collapse fa-chevron-right" title="Thu gọn" aria-hidden="true" data-child-level="2" data-type="course_sections" data-category-id="11525"></i>
                                                                <i class="fa fa-folder-open fa-folder-color expand-folder" aria-hidden="true" data-type="course_sections" data-path="11524/11525" data-child-level="2" data-category-id="11525"></i>
                                                                <div class="expand-folder box-name" data-type="course_sections" data-path="11524/11525" data-child-level="2" data-category-id="11525" id="folder-11525">Thư mục 3</div>
                                                            </div>
                                                            <div class="right-item"><i class="icon_is_lesson is_lesson_11525 fa-bookmark-o fa" data-id="19793" title="Bài học" data-category-id="11525" aria-hidden="true"></i>
                                                                <div class="btn-group"><i class="fa fa-plus icon-dropdown-menu" title="Thêm thư mục con" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                                    <div class="dropdown-menu">
                                                                        <h5 style="border-bottom: 1px solid #dadada" class="dropdown-header"><b>Chọn loại thư mục</b></h5>
                                                                        <div class="dropdown-item add-child-folder" data-child-level="2" data-type="course_sections" data-category-id="11525">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                            <i class="fa fa-folder-open fa-folder-color" aria-hidden="true"></i>
                                                                            <div>Thư mục</div>
                                                                        </div>
                                                                        <div class="dropdown-item add-child-quiz" data-child-level="2" data-type="quiz" data-category-id="11525">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                            <i class="fa fa-list-ol" aria-hidden="true"></i>
                                                                            <div>Quiz</div>
                                                                        </div>
                                                                        <div class="dropdown-item add-child-video" data-child-level="2" data-type="video" data-category-id="11525">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                            <i class="fa fa-video-camera" aria-hidden="true"></i>
                                                                            <div>Video</div>
                                                                        </div>
                                                                        <div class="dropdown-item add-child-resource" data-child-level="2" data-type="resource" data-category-id="11525">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                            <i class="fa fa-file" aria-hidden="true"></i>
                                                                            <div>File</div>
                                                                        </div>
                                                                        <div class="dropdown-item add-interactive-book" data-child-level="2" data-type="interactive_book" data-category-id="11525">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                            <i class="fa fa-list-ol" aria-hidden="true"></i>
                                                                            <div>Interactive Book</div>
                                                                        </div>
                                                                        <div class="dropdown-item add-child-assign" data-child-level="2" data-type="assign" data-category-id="11525">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                            <i class="fa fa-file" aria-hidden="true"></i>
                                                                            <div>Assign</div>
                                                                        </div>
                                                                        <div class="dropdown-item add-child-url" data-child-level="2" data-type="url" data-category-id="11525">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                            <i class="fa fa-link" aria-hidden="true"></i>
                                                                            <div>URL</div>
                                                                        </div>
                                                                        <div class="dropdown-item add-child-scorm" data-child-level="2" data-type="scorm" data-category-id="11525">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                            <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                                                            <div>Scorm</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <i class="fa fa-trash icon-action-delete" title="Xoá" aria-hidden="true" data-type="course_sections" data-child-level="2" data-category-id="11525" data-name="Thư mục 3"></i>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" class="slot-cover-category slot-cover-category-11524" name="cover_category[slot]" value="11525">
                                                        <div data-current="11525" class="tree-item-contain tree-item-contain-2 contain-category-11525 ui-sortable" style="display: none;width: calc(100% - 4%)">
                                                            <div class="tree-item-cover cover-category-52852" data-level="4" data-cover-category="52852">
                                                                <div class="item-quiz level-3 tree-item  " style="width: calc(100% - 4%)">
                                                                    <div class="left-item">
                                                                        <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
                                                                        <i class="fa fa-list-ol expand-folder" aria-hidden="true" data-type="quiz" data-child-level="4" data-category-id="52852" data-instance="52852" data-cmid="65445" data-quiz-id="52852"></i>
                                                                        <div class="expand-quiz box-name" data-parent="11525" data-child-level="4" data-category-id="52852" data-instance="52852" data-cmid="65445" data-type="quiz" data-quiz-id="52852" id="quiz-52852" data-path="11524/11525/52852">Đề thi 15026</div>
                                                                    </div>
                                                                    <div class="right-item">
                                                                        <div class="btn-group">
                                                                            <i class="fa fa-play icon-quiz-action-play-temp play-temp" data-type="quiz" data-href="#" title="Làm bài" aria-hidden="true"></i>
                                                                            <i class="fa fa-trash icon-quiz-action-delete" title="Xoá" aria-hidden="true" data-category-id="52852" data-instance="52852" data-cmid="65445" data-type="quiz" data-child-level="4" data-name="Đề thi 15026"></i>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" class="slot-cover-category slot-cover-category-11525" name="cover_category[slot]" value="52852">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tree-item-cover cover-category-52854" data-level="3" data-cover-category="52854">
                                                        <div class="item-quiz level-2 tree-item" style="width: calc(100% - 4%)">
                                                            <div class="left-item">
                                                                <i class="fa fa-arrows" title="Di chuyển" aria-hidden="true"></i>
                                                                <i class="fa fa-list-ol expand-folder" aria-hidden="true" data-type="quiz" data-child-level="3" data-category-id="52854" data-instance="52854" data-cmid="65447" data-quiz-id="52854"></i>
                                                                <div class="expand-quiz box-name" data-parent="11524" data-child-level="3" data-category-id="52854" data-instance="52854" data-cmid="65447" data-type="quiz" data-quiz-id="52854" id="quiz-52854" data-path="11524/52854">Đề thi 15027</div>
                                                            </div>
                                                            <div class="right-item">
                                                                <div class="btn-group">
                                                                    <i class="fa fa-play icon-quiz-action-play-temp play-temp" data-type="quiz" data-href="#" title="Làm bài" aria-hidden="true"></i>
                                                                    <i class="fa fa-trash icon-quiz-action-delete" title="Xoá" aria-hidden="true" data-category-id="52854" data-instance="52854" data-cmid="65447" data-type="quiz" data-child-level="3" data-name="Đề thi 15027"></i>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" class="slot-cover-category slot-cover-category-11524" name="cover_category[slot]" value="52854">
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" class="slot-parent-category" name="parent_category[slot]" value="11524">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="right-part">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
