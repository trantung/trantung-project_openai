@extends('layouts.app')

@section('content')
<link href="{{ URL::asset('css/courseDetail.css') }}" rel="stylesheet">

<div id="main-content">
    <input type="hidden" id="currentUserEmail" value="{{ $currentEmail }}">
    <input type="hidden" id="courseId" value="{{ $courseData->id }}">
    <input type="hidden" id="courseMoodleId" value="{{ $courseData->moodle_id }}">
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
                                    <i class="fa fa-long-arrow-left" ></i>
                                    {{ $courseData->moodle_name }}
                                </a>
                            </h2>
                            <div class="message-product hidden">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <div id="content_message">Cập nhật sản phẩm thành công!</div>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span >×</span>
                                    </button>
                                </div>
                            </div>
                            <div class="course-detail-page">
                                @include('product_detail.left.product_detail_left_part', [
                                    'name1' => 'Tên thư mục',
                                    'products' => $products
                                ])
                                @include('product_detail.right.product_detail_right_part')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="popup_question_type" tabindex="-1" style="display: none;" >
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    THÊM CÂU HỎI MỚI
                </h5>
                
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span >×</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="popup_content">
                    <div class="popup_content_box popup_content_box_1 shadow">
                        <div class="text_question_type">
                            1. Multiple choice
                        </div>
                        <div class="button_question_type">
                                <button class="btn btn-secondary" data-link="https://english.ican.vn/classroom/local/omo_management/quiz/upsert_question_bank.php?course=206&amp;section=2&amp;is_course_detail=1&amp;edit-view=1&amp;qtype=multichoice">Moodle</button>
                        </div>
                    </div>
                    
                    <input type="hidden" class="question-sub-quiz-id" value="53058">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" data-backdrop="static" id="popup_upsert_question" tabindex="-1" style="/* display: none; */" >
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Chỉnh sửa câu hỏi
                </h5>
                <button type="button" style="font-size: 3em;" class="close btn-close-edit-md" data-dismiss="modal" aria-label="Close" id="yui_3_17_2_1_1729853647785_60">
                    <span >×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="popup_content">
                    <iframe id="edit_view_qs" width="100%" height="232" src="https://english.ican.vn/classroom/local/omo_management/quiz/upsert_question_bank.php?course=171&amp;section=10457&amp;id=238507&amp;quiz_id=31113&amp;qtype=gapselect&amp;quiz_section_custom_id=10018&amp;quiz_slots_custom_id=44198&amp;edit-view=1&amp;is_course_detail=1" allowfullscreen="" data-gtm-yt-inspected-6="true"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modal-availability" tabindex="-1" role="dialog" style="display: none;" aria-labelledby="modal-availability-label" aria-modal="true" style="padding-right: 17px; display: block;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-lesson-event-label">Chọn tài nguyên cần hoàn thành</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span >×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="availability-items">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-choose-activity" data-cmid="">Xác nhận</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="fileDuplicatePopup" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-xl" role="document" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Tệp tồn tại
                </h5>
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span >×</span>
                    </button>
                </div>
            </div>
            <div class="modal-body text-center">
                <p>Đã có một tập tin là logoAI.jpg</p>
                <div class="fp-dlg-buttons">
                    <button class="fp-dlg-butoverwrite btn btn-primary mb-1">Ghi đè</button>
                    <button class="fp-dlg-butrename btn btn-primary mb-1">Đặt tên thành "logoAI (1).jpg"</button>
                    <button class="fp-dlg-butcancel btn btn-secondary mb-1">Huỷ bỏ</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal moodle-has-zindex popup_modal_icon_action_delete" data-region="modal-container" role="dialog" tabindex="-1" style="z-index: 1052;">
    <div class="modal-dialog modal-dialog-scrollable" role="document" data-region="modal" aria-labelledby="5-modal-title" tabindex="0">
        <div class="modal-content">
            <div class="modal-header " data-region="header">
                <h5 id="5-modal-title" class="modal-title" data-region="title">Xóa thư mục</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" data-region="body">Bạn có thực sự muốn xoá mục này "<b class="this_item_name"></b>"? <br> Lưu ý: Xoá xong không thể khôi phục lại được</div>
            <div class="modal-footer" data-region="footer">
                <button type="button" class="btn btn-primary delete-this-item" data-action="save">Xác nhận</button>
                <button type="button" class="btn btn-secondary" data-action="cancel" data-dismiss="modal">Huỷ bỏ</button>
            </div>
        </div>
    </div>
</div>

<div class="modal moodle-has-zindex" id="filetypesModal" data-region="modal-container" role="dialog" tabindex="-1" style="z-index: 1052;">
    <div class="modal-dialog modal-dialog-scrollable" role="document" data-region="modal" aria-labelledby="1-modal-title" tabindex="0">
        <div class="modal-content">
            <div class="modal-header " data-region="header">
                <h5 id="1-modal-title" class="modal-title" data-region="title">Các loại tập tin được chấp nhận</h5>
                <button type="button" class="close" data-action="hide" aria-label="Đóng">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" data-region="body" style="">
                <div data-filetypesbrowserbody="id_assignsubmission_file_filetypes_mrzUV2ZD7SQLotA" role="tree">
                    <div data-filetypesbrowserkey="*" role="treeitem" aria-expanded="false">
                        <label>
                            <input data-filetypesbrowserkey="*" type="checkbox" checked="">
                            <strong data-filetypesname="*">All file types</strong>
                            <small class="muted" data-filetypesextensions="*"></small>
                        </label>
                        <ul class="unstyled list-unstyled" role="group"></ul>
                        <hr style="clear:both">
                    </div>
                    <div data-filetypesbrowserkey="html_audio" role="treeitem" aria-expanded="false">
                        <label>
                            <input data-filetypesbrowserkey="html_audio" type="checkbox">
                            <strong data-filetypesname="html_audio">Audio files natively supported by browsers</strong>
                            <small class="muted" data-filetypesextensions="html_audio">
                                .aac .flac .mp3 .m4a .oga .ogg .wav
                            </small>
                        </label>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifexpanded" class="float-right float-right"><a href="#">Mở rộng</a></small>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifcollapsed" class="float-right float-right"><a href="#">Rút gọn</a></small>
                        <ul class="unstyled list-unstyled" role="group">
                            <li data-filetypesbrowserkey=".aac" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".aac" type="checkbox">
                                    <span data-filetypesname=".aac">Tệp âm thanh (AAC)</span>
                                    <small class="muted" data-filetypesextensions=".aac">
                                        .aac
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".flac" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".flac" type="checkbox">
                                    <span data-filetypesname=".flac">Tệp âm thanh (FLAC)</span>
                                    <small class="muted" data-filetypesextensions=".flac">
                                        .flac
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".m4a" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".m4a" type="checkbox">
                                    <span data-filetypesname=".m4a">Tệp âm thanh (M4A)</span>
                                    <small class="muted" data-filetypesextensions=".m4a">
                                        .m4a
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mp3" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mp3" type="checkbox">
                                    <span data-filetypesname=".mp3">Tệp âm thanh (MP3)</span>
                                    <small class="muted" data-filetypesextensions=".mp3">
                                        .mp3
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".oga" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".oga" type="checkbox">
                                    <span data-filetypesname=".oga">Tệp âm thanh (OGA)</span>
                                    <small class="muted" data-filetypesextensions=".oga">
                                        .oga
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ogg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ogg" type="checkbox">
                                    <span data-filetypesname=".ogg">Tệp âm thanh (OGG)</span>
                                    <small class="muted" data-filetypesextensions=".ogg">
                                        .ogg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".wav" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".wav" type="checkbox">
                                    <span data-filetypesname=".wav">Tệp âm thanh (WAV)</span>
                                    <small class="muted" data-filetypesextensions=".wav">
                                        .wav
                                    </small>
                                </label>
                            </li>
                        </ul>
                        <hr style="clear:both">
                    </div>
                    <div data-filetypesbrowserkey="web_image" role="treeitem" aria-expanded="false">
                        <label>
                            <input data-filetypesbrowserkey="web_image" type="checkbox">
                            <strong data-filetypesname="web_image">Các file ảnh dùng trên mạng</strong>
                            <small class="muted" data-filetypesextensions="web_image">
                                .gif .jpe .jpeg .jpg .png .svg .svgz
                            </small>
                        </label>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifexpanded" class="float-right float-right"><a href="#">Mở rộng</a></small>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifcollapsed" class="float-right float-right"><a href="#">Rút gọn</a></small>
                        <ul class="unstyled list-unstyled" role="group">
                            <li data-filetypesbrowserkey=".gif" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".gif" type="checkbox">
                                    <span data-filetypesname=".gif">Ảnh (GIF)</span>
                                    <small class="muted" data-filetypesextensions=".gif">
                                        .gif
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jpe" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jpe" type="checkbox">
                                    <span data-filetypesname=".jpe">Ảnh (JPEG)</span>
                                    <small class="muted" data-filetypesextensions=".jpe">
                                        .jpe
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jpeg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jpeg" type="checkbox">
                                    <span data-filetypesname=".jpeg">Ảnh (JPEG)</span>
                                    <small class="muted" data-filetypesextensions=".jpeg">
                                        .jpeg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jpg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jpg" type="checkbox">
                                    <span data-filetypesname=".jpg">Ảnh (JPEG)</span>
                                    <small class="muted" data-filetypesextensions=".jpg">
                                        .jpg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".png" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".png" type="checkbox">
                                    <span data-filetypesname=".png">Ảnh (PNG)</span>
                                    <small class="muted" data-filetypesextensions=".png">
                                        .png
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".svg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".svg" type="checkbox">
                                    <span data-filetypesname=".svg">Ảnh (SVG+XML)</span>
                                    <small class="muted" data-filetypesextensions=".svg">
                                        .svg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".svgz" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".svgz" type="checkbox">
                                    <span data-filetypesname=".svgz">Ảnh (SVG+XML)</span>
                                    <small class="muted" data-filetypesextensions=".svgz">
                                        .svgz
                                    </small>
                                </label>
                            </li>
                        </ul>
                        <hr style="clear:both">
                    </div>
                    <div data-filetypesbrowserkey="spreadsheet" role="treeitem" aria-expanded="false">
                        <label>
                            <input data-filetypesbrowserkey="spreadsheet" type="checkbox">
                            <strong data-filetypesname="spreadsheet">Các file bảng tính</strong>
                            <small class="muted" data-filetypesextensions="spreadsheet">
                                .csv .gsheet .ods .ots .xls .xlsx .xlsm
                            </small>
                        </label>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifexpanded" class="float-right float-right"><a href="#">Mở rộng</a></small>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifcollapsed" class="float-right float-right"><a href="#">Rút gọn</a></small>
                        <ul class="unstyled list-unstyled" role="group">
                            <li data-filetypesbrowserkey=".gsheet" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".gsheet" type="checkbox">
                                    <span data-filetypesname=".gsheet">application/vnd.google-apps.spreadsheet</span>
                                    <small class="muted" data-filetypesextensions=".gsheet">
                                        .gsheet
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xls" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xls" type="checkbox">
                                    <span data-filetypesname=".xls">Bảng tính Excel</span>
                                    <small class="muted" data-filetypesextensions=".xls">
                                        .xls
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xlsx" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xlsx" type="checkbox">
                                    <span data-filetypesname=".xlsx">Bảng tính Excel</span>
                                    <small class="muted" data-filetypesextensions=".xlsx">
                                        .xlsx
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".csv" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".csv" type="checkbox">
                                    <span data-filetypesname=".csv">Các giá trị được tách bởi dấu phẩy</span>
                                    <small class="muted" data-filetypesextensions=".csv">
                                        .csv
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xlsm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xlsm" type="checkbox">
                                    <span data-filetypesname=".xlsm">Excel 2007 macro-enabled workbook</span>
                                    <small class="muted" data-filetypesextensions=".xlsm">
                                        .xlsm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ods" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ods" type="checkbox">
                                    <span data-filetypesname=".ods">OpenDocument Spreadsheet</span>
                                    <small class="muted" data-filetypesextensions=".ods">
                                        .ods
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ots" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ots" type="checkbox">
                                    <span data-filetypesname=".ots">OpenDocument Spreadsheet template</span>
                                    <small class="muted" data-filetypesextensions=".ots">
                                        .ots
                                    </small>
                                </label>
                            </li>
                        </ul>
                        <hr style="clear:both">
                    </div>
                    <div data-filetypesbrowserkey="web_audio" role="treeitem" aria-expanded="false">
                        <label>
                            <input data-filetypesbrowserkey="web_audio" type="checkbox">
                            <strong data-filetypesname="web_audio">Các file ghi tiếng dùng trên mạng</strong>
                            <small class="muted" data-filetypesextensions="web_audio">
                                .aac .flac .mp3 .m4a .oga .ogg .ra .wav
                            </small>
                        </label>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifexpanded" class="float-right float-right"><a href="#">Mở rộng</a></small>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifcollapsed" class="float-right float-right"><a href="#">Rút gọn</a></small>
                        <ul class="unstyled list-unstyled" role="group">
                            <li data-filetypesbrowserkey=".aac" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".aac" type="checkbox">
                                    <span data-filetypesname=".aac">Tệp âm thanh (AAC)</span>
                                    <small class="muted" data-filetypesextensions=".aac">
                                        .aac
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".flac" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".flac" type="checkbox">
                                    <span data-filetypesname=".flac">Tệp âm thanh (FLAC)</span>
                                    <small class="muted" data-filetypesextensions=".flac">
                                        .flac
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".m4a" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".m4a" type="checkbox">
                                    <span data-filetypesname=".m4a">Tệp âm thanh (M4A)</span>
                                    <small class="muted" data-filetypesextensions=".m4a">
                                        .m4a
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mp3" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mp3" type="checkbox">
                                    <span data-filetypesname=".mp3">Tệp âm thanh (MP3)</span>
                                    <small class="muted" data-filetypesextensions=".mp3">
                                        .mp3
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".oga" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".oga" type="checkbox">
                                    <span data-filetypesname=".oga">Tệp âm thanh (OGA)</span>
                                    <small class="muted" data-filetypesextensions=".oga">
                                        .oga
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ogg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ogg" type="checkbox">
                                    <span data-filetypesname=".ogg">Tệp âm thanh (OGG)</span>
                                    <small class="muted" data-filetypesextensions=".ogg">
                                        .ogg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ra" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ra" type="checkbox">
                                    <span data-filetypesname=".ra">Tệp âm thanh (RA)</span>
                                    <small class="muted" data-filetypesextensions=".ra">
                                        .ra
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".wav" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".wav" type="checkbox">
                                    <span data-filetypesname=".wav">Tệp âm thanh (WAV)</span>
                                    <small class="muted" data-filetypesextensions=".wav">
                                        .wav
                                    </small>
                                </label>
                            </li>
                        </ul>
                        <hr style="clear:both">
                    </div>
                    <div data-filetypesbrowserkey="image" role="treeitem" aria-expanded="false">
                        <label>
                            <input data-filetypesbrowserkey="image" type="checkbox">
                            <strong data-filetypesname="image">Các file hình ảnh</strong>
                            <small class="muted" data-filetypesextensions="image">
                                .ai .bmp .gdraw .gif .ico .jpe .jpeg .jpg .pct .pic .pict .png .svg .svgz .tif .tiff
                            </small>
                        </label>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifexpanded" class="float-right float-right"><a href="#">Mở rộng</a></small>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifcollapsed" class="float-right float-right"><a href="#">Rút gọn</a></small>
                        <ul class="unstyled list-unstyled" role="group">
                            <li data-filetypesbrowserkey=".bmp" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".bmp" type="checkbox">
                                    <span data-filetypesname=".bmp">Ảnh (BMP)</span>
                                    <small class="muted" data-filetypesextensions=".bmp">
                                        .bmp
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".gif" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".gif" type="checkbox">
                                    <span data-filetypesname=".gif">Ảnh (GIF)</span>
                                    <small class="muted" data-filetypesextensions=".gif">
                                        .gif
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jpe" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jpe" type="checkbox">
                                    <span data-filetypesname=".jpe">Ảnh (JPEG)</span>
                                    <small class="muted" data-filetypesextensions=".jpe">
                                        .jpe
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jpeg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jpeg" type="checkbox">
                                    <span data-filetypesname=".jpeg">Ảnh (JPEG)</span>
                                    <small class="muted" data-filetypesextensions=".jpeg">
                                        .jpeg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jpg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jpg" type="checkbox">
                                    <span data-filetypesname=".jpg">Ảnh (JPEG)</span>
                                    <small class="muted" data-filetypesextensions=".jpg">
                                        .jpg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".pct" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".pct" type="checkbox">
                                    <span data-filetypesname=".pct">Ảnh (PICT)</span>
                                    <small class="muted" data-filetypesextensions=".pct">
                                        .pct
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".pic" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".pic" type="checkbox">
                                    <span data-filetypesname=".pic">Ảnh (PICT)</span>
                                    <small class="muted" data-filetypesextensions=".pic">
                                        .pic
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".pict" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".pict" type="checkbox">
                                    <span data-filetypesname=".pict">Ảnh (PICT)</span>
                                    <small class="muted" data-filetypesextensions=".pict">
                                        .pict
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".png" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".png" type="checkbox">
                                    <span data-filetypesname=".png">Ảnh (PNG)</span>
                                    <small class="muted" data-filetypesextensions=".png">
                                        .png
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ai" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ai" type="checkbox">
                                    <span data-filetypesname=".ai">Ảnh (POSTSCRIPT)</span>
                                    <small class="muted" data-filetypesextensions=".ai">
                                        .ai
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".svg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".svg" type="checkbox">
                                    <span data-filetypesname=".svg">Ảnh (SVG+XML)</span>
                                    <small class="muted" data-filetypesextensions=".svg">
                                        .svg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".svgz" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".svgz" type="checkbox">
                                    <span data-filetypesname=".svgz">Ảnh (SVG+XML)</span>
                                    <small class="muted" data-filetypesextensions=".svgz">
                                        .svgz
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".tif" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".tif" type="checkbox">
                                    <span data-filetypesname=".tif">Ảnh (TIFF)</span>
                                    <small class="muted" data-filetypesextensions=".tif">
                                        .tif
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".tiff" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".tiff" type="checkbox">
                                    <span data-filetypesname=".tiff">Ảnh (TIFF)</span>
                                    <small class="muted" data-filetypesextensions=".tiff">
                                        .tiff
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".gdraw" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".gdraw" type="checkbox">
                                    <span data-filetypesname=".gdraw">application/vnd.google-apps.drawing</span>
                                    <small class="muted" data-filetypesextensions=".gdraw">
                                        .gdraw
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ico" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ico" type="checkbox">
                                    <span data-filetypesname=".ico">Biểu tượng Windows</span>
                                    <small class="muted" data-filetypesextensions=".ico">
                                        .ico
                                    </small>
                                </label>
                            </li>
                        </ul>
                        <hr style="clear:both">
                    </div>
                    <div data-filetypesbrowserkey="web_file" role="treeitem" aria-expanded="false">
                        <label>
                            <input data-filetypesbrowserkey="web_file" type="checkbox">
                            <strong data-filetypesname="web_file">Các file trên mạng</strong>
                            <small class="muted" data-filetypesextensions="web_file">
                                .css .html .xhtml .htm .js .scss
                            </small>
                        </label>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifexpanded" class="float-right float-right"><a href="#">Mở rộng</a></small>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifcollapsed" class="float-right float-right"><a href="#">Rút gọn</a></small>
                        <ul class="unstyled list-unstyled" role="group">
                            <li data-filetypesbrowserkey=".css" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".css" type="checkbox">
                                    <span data-filetypesname=".css">Cascading Style-Sheet</span>
                                    <small class="muted" data-filetypesextensions=".css">
                                        .css
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".js" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".js" type="checkbox">
                                    <span data-filetypesname=".js">Nguồn JavaScript</span>
                                    <small class="muted" data-filetypesextensions=".js">
                                        .js
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".html" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".html" type="checkbox">
                                    <span data-filetypesname=".html">Tài liệu HTML</span>
                                    <small class="muted" data-filetypesextensions=".html">
                                        .html
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".htm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".htm" type="checkbox">
                                    <span data-filetypesname=".htm">Tài liệu HTML</span>
                                    <small class="muted" data-filetypesextensions=".htm">
                                        .htm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xhtml" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xhtml" type="checkbox">
                                    <span data-filetypesname=".xhtml">Tài liệu XHTML</span>
                                    <small class="muted" data-filetypesextensions=".xhtml">
                                        .xhtml
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".scss" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".scss" type="checkbox">
                                    <span data-filetypesname=".scss">text/x-scss</span>
                                    <small class="muted" data-filetypesextensions=".scss">
                                        .scss
                                    </small>
                                </label>
                            </li>
                        </ul>
                        <hr style="clear:both">
                    </div>
                    <div data-filetypesbrowserkey="presentation" role="treeitem" aria-expanded="false">
                        <label>
                            <input data-filetypesbrowserkey="presentation" type="checkbox">
                            <strong data-filetypesname="presentation">Các file trình chiếu</strong>
                            <small class="muted" data-filetypesextensions="presentation">
                                .gslides .odp .otp .pps .ppt .pptx .pptm .potx .potm .ppam .ppsx .ppsm .pub .sxi .sti
                            </small>
                        </label>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifexpanded" class="float-right float-right"><a href="#">Mở rộng</a></small>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifcollapsed" class="float-right float-right"><a href="#">Rút gọn</a></small>
                        <ul class="unstyled list-unstyled" role="group">
                            <li data-filetypesbrowserkey=".gslides" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".gslides" type="checkbox">
                                    <span data-filetypesname=".gslides">application/vnd.google-apps.presentation</span>
                                    <small class="muted" data-filetypesextensions=".gslides">
                                        .gslides
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ppam" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ppam" type="checkbox">
                                    <span data-filetypesname=".ppam">application/vnd.ms-powerpoint.addin.macroEnabled.12</span>
                                    <small class="muted" data-filetypesextensions=".ppam">
                                        .ppam
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".pptm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".pptm" type="checkbox">
                                    <span data-filetypesname=".pptm">application/vnd.ms-powerpoint.presentation.macroEnabled.12</span>
                                    <small class="muted" data-filetypesextensions=".pptm">
                                        .pptm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ppsm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ppsm" type="checkbox">
                                    <span data-filetypesname=".ppsm">application/vnd.ms-powerpoint.slideshow.macroEnabled.12</span>
                                    <small class="muted" data-filetypesextensions=".ppsm">
                                        .ppsm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".potm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".potm" type="checkbox">
                                    <span data-filetypesname=".potm">application/vnd.ms-powerpoint.template.macroEnabled.12</span>
                                    <small class="muted" data-filetypesextensions=".potm">
                                        .potm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".odp" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".odp" type="checkbox">
                                    <span data-filetypesname=".odp">application/vnd.oasis.opendocument.presentation</span>
                                    <small class="muted" data-filetypesextensions=".odp">
                                        .odp
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".otp" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".otp" type="checkbox">
                                    <span data-filetypesname=".otp">application/vnd.oasis.opendocument.presentation-template</span>
                                    <small class="muted" data-filetypesextensions=".otp">
                                        .otp
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".potx" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".potx" type="checkbox">
                                    <span data-filetypesname=".potx">application/vnd.openxmlformats-officedocument.presentationml.template</span>
                                    <small class="muted" data-filetypesextensions=".potx">
                                        .potx
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".sxi" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".sxi" type="checkbox">
                                    <span data-filetypesname=".sxi">application/vnd.sun.xml.impress</span>
                                    <small class="muted" data-filetypesextensions=".sxi">
                                        .sxi
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".sti" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".sti" type="checkbox">
                                    <span data-filetypesname=".sti">application/vnd.sun.xml.impress.template</span>
                                    <small class="muted" data-filetypesextensions=".sti">
                                        .sti
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".pps" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".pps" type="checkbox">
                                    <span data-filetypesname=".pps">Bản trình bày Powepoint</span>
                                    <small class="muted" data-filetypesextensions=".pps">
                                        .pps
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ppt" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ppt" type="checkbox">
                                    <span data-filetypesname=".ppt">Bản trình bày Powepoint</span>
                                    <small class="muted" data-filetypesextensions=".ppt">
                                        .ppt
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".pptx" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".pptx" type="checkbox">
                                    <span data-filetypesname=".pptx">Bản trình bày Powerpoint</span>
                                    <small class="muted" data-filetypesextensions=".pptx">
                                        .pptx
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ppsx" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ppsx" type="checkbox">
                                    <span data-filetypesname=".ppsx">Bản trình chiếu Powerpoint</span>
                                    <small class="muted" data-filetypesextensions=".ppsx">
                                        .ppsx
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".pub" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".pub" type="checkbox">
                                    <span data-filetypesname=".pub">Publisher document</span>
                                    <small class="muted" data-filetypesextensions=".pub">
                                        .pub
                                    </small>
                                </label>
                            </li>
                        </ul>
                        <hr style="clear:both">
                    </div>
                    <div data-filetypesbrowserkey="video" role="treeitem" aria-expanded="false">
                        <label>
                            <input data-filetypesbrowserkey="video" type="checkbox">
                            <strong data-filetypesname="video">Các file video</strong>
                            <small class="muted" data-filetypesextensions="video">
                                .3gp .avi .dv .dif .flv .f4v .fmp4 .mov .movie .mp4 .m4v .mpeg .mpe .mpg .ogv .qt .rmvb .rv .swf .swfl .ts .webm .wmv .asf
                            </small>
                        </label>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifexpanded" class="float-right float-right"><a href="#">Mở rộng</a></small>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifcollapsed" class="float-right float-right"><a href="#">Rút gọn</a></small>
                        <ul class="unstyled list-unstyled" role="group">
                            <li data-filetypesbrowserkey=".3gp" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".3gp" type="checkbox">
                                    <span data-filetypesname=".3gp">File video (3GP)</span>
                                    <small class="muted" data-filetypesextensions=".3gp">
                                        .3gp
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".asf" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".asf" type="checkbox">
                                    <span data-filetypesname=".asf">File video (ASF)</span>
                                    <small class="muted" data-filetypesextensions=".asf">
                                        .asf
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".avi" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".avi" type="checkbox">
                                    <span data-filetypesname=".avi">File video (AVI)</span>
                                    <small class="muted" data-filetypesextensions=".avi">
                                        .avi
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".dif" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".dif" type="checkbox">
                                    <span data-filetypesname=".dif">File video (DIF)</span>
                                    <small class="muted" data-filetypesextensions=".dif">
                                        .dif
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".dv" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".dv" type="checkbox">
                                    <span data-filetypesname=".dv">File video (DV)</span>
                                    <small class="muted" data-filetypesextensions=".dv">
                                        .dv
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".f4v" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".f4v" type="checkbox">
                                    <span data-filetypesname=".f4v">File video (F4V)</span>
                                    <small class="muted" data-filetypesextensions=".f4v">
                                        .f4v
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".flv" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".flv" type="checkbox">
                                    <span data-filetypesname=".flv">File video (FLV)</span>
                                    <small class="muted" data-filetypesextensions=".flv">
                                        .flv
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".fmp4" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".fmp4" type="checkbox">
                                    <span data-filetypesname=".fmp4">File video (FMP4)</span>
                                    <small class="muted" data-filetypesextensions=".fmp4">
                                        .fmp4
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".m4v" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".m4v" type="checkbox">
                                    <span data-filetypesname=".m4v">File video (M4V)</span>
                                    <small class="muted" data-filetypesextensions=".m4v">
                                        .m4v
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mov" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mov" type="checkbox">
                                    <span data-filetypesname=".mov">File video (MOV)</span>
                                    <small class="muted" data-filetypesextensions=".mov">
                                        .mov
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".movie" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".movie" type="checkbox">
                                    <span data-filetypesname=".movie">File video (MOVIE)</span>
                                    <small class="muted" data-filetypesextensions=".movie">
                                        .movie
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mp4" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mp4" type="checkbox">
                                    <span data-filetypesname=".mp4">File video (MP4)</span>
                                    <small class="muted" data-filetypesextensions=".mp4">
                                        .mp4
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".m4v" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".m4v" type="checkbox">
                                    <span data-filetypesname=".m4v">File video (M4V)</span>
                                    <small class="muted" data-filetypesextensions=".m4v">
                                        .m4v
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mpeg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mpeg" type="checkbox">
                                    <span data-filetypesname=".mpeg">File video (MPEG)</span>
                                    <small class="muted" data-filetypesextensions=".mpeg">
                                        .mpeg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mpe" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mpe" type="checkbox">
                                    <span data-filetypesname=".mpe">File video (MPE)</span>
                                    <small class="muted" data-filetypesextensions=".mpe">
                                        .mpe
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mpg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mpg" type="checkbox">
                                    <span data-filetypesname=".mpg">File video (MPG)</span>
                                    <small class="muted" data-filetypesextensions=".mpg">
                                        .mpg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ogv" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ogv" type="checkbox">
                                    <span data-filetypesname=".ogv">File video (OGV)</span>
                                    <small class="muted" data-filetypesextensions=".ogv">
                                        .ogv
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".qt" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".qt" type="checkbox">
                                    <span data-filetypesname=".qt">File video (QT)</span>
                                    <small class="muted" data-filetypesextensions=".qt">
                                        .qt
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".rmvb" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".rmvb" type="checkbox">
                                    <span data-filetypesname=".rmvb">File video (RMVB)</span>
                                    <small class="muted" data-filetypesextensions=".rmvb">
                                        .rmvb
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".rv" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".rv" type="checkbox">
                                    <span data-filetypesname=".rv">File video (RV)</span>
                                    <small class="muted" data-filetypesextensions=".rv">
                                        .rv
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".swf" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".swf" type="checkbox">
                                    <span data-filetypesname=".swf">File video (SWF)</span>
                                    <small class="muted" data-filetypesextensions=".swf">
                                        .swf
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".swfl" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".swfl" type="checkbox">
                                    <span data-filetypesname=".swfl">File video (SWFL)</span>
                                    <small class="muted" data-filetypesextensions=".swfl">
                                        .swfl
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ts" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ts" type="checkbox">
                                    <span data-filetypesname=".ts">File video (TS)</span>
                                    <small class="muted" data-filetypesextensions=".ts">
                                        .ts
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".webm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".webm" type="checkbox">
                                    <span data-filetypesname=".webm">File video (WEBM)</span>
                                    <small class="muted" data-filetypesextensions=".webm">
                                        .webm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".wmv" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".wmv" type="checkbox">
                                    <span data-filetypesname=".wmv">File video (WMV)</span>
                                    <small class="muted" data-filetypesextensions=".wmv">
                                        .wmv
                                    </small>
                                </label>
                            </li>
                        </ul>
                        <hr style="clear:both">
                    </div>
                    <div data-filetypesbrowserkey="" role="treeitem" aria-expanded="true">
                        <label>
                            <strong>Other files</strong>
                        </label>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifexpanded" class="float-right float-right"><a href="#">Mở rộng</a></small>
                        <small aria-hidden="true" data-filetypesbrowserfeature="hideifcollapsed" class="float-right float-right"><a href="#">Rút gọn</a></small>
                        <ul class="unstyled list-unstyled" role="group">
                            <li data-filetypesbrowserkey=".isf" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".isf" type="checkbox">
                                    <span data-filetypesname=".isf">application/inspiration</span>
                                    <small class="muted" data-filetypesextensions=".isf">
                                        .isf
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ist" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ist" type="checkbox">
                                    <span data-filetypesname=".ist">application/inspiration.template</span>
                                    <small class="muted" data-filetypesextensions=".ist">
                                        .ist
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jar" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jar" type="checkbox">
                                    <span data-filetypesname=".jar">application/java-archive</span>
                                    <small class="muted" data-filetypesextensions=".jar">
                                        .jar
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mw" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mw" type="checkbox">
                                    <span data-filetypesname=".mw">application/maple</span>
                                    <small class="muted" data-filetypesextensions=".mw">
                                        .mw
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mws" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mws" type="checkbox">
                                    <span data-filetypesname=".mws">application/maple</span>
                                    <small class="muted" data-filetypesextensions=".mws">
                                        .mws
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".accdb" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".accdb" type="checkbox">
                                    <span data-filetypesname=".accdb">application/msaccess</span>
                                    <small class="muted" data-filetypesextensions=".accdb">
                                        .accdb
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".dmg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".dmg" type="checkbox">
                                    <span data-filetypesname=".dmg">application/octet-stream</span>
                                    <small class="muted" data-filetypesextensions=".dmg">
                                        .dmg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ps" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ps" type="checkbox">
                                    <span data-filetypesname=".ps">application/postscript</span>
                                    <small class="muted" data-filetypesextensions=".ps">
                                        .ps
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".eps" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".eps" type="checkbox">
                                    <span data-filetypesname=".eps">application/postscript</span>
                                    <small class="muted" data-filetypesextensions=".eps">
                                        .eps
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".smi" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".smi" type="checkbox">
                                    <span data-filetypesname=".smi">application/smil</span>
                                    <small class="muted" data-filetypesextensions=".smi">
                                        .smi
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".smil" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".smil" type="checkbox">
                                    <span data-filetypesname=".smil">application/smil</span>
                                    <small class="muted" data-filetypesextensions=".smil">
                                        .smil
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xdp" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xdp" type="checkbox">
                                    <span data-filetypesname=".xdp">application/vnd.adobe.xdp+xml</span>
                                    <small class="muted" data-filetypesextensions=".xdp">
                                        .xdp
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xfdf" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xfdf" type="checkbox">
                                    <span data-filetypesname=".xfdf">application/vnd.adobe.xfdf</span>
                                    <small class="muted" data-filetypesextensions=".xfdf">
                                        .xfdf
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".fdf" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".fdf" type="checkbox">
                                    <span data-filetypesname=".fdf">application/vnd.fdf</span>
                                    <small class="muted" data-filetypesextensions=".fdf">
                                        .fdf
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mpr" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mpr" type="checkbox">
                                    <span data-filetypesname=".mpr">application/vnd.moodle.profiling</span>
                                    <small class="muted" data-filetypesextensions=".mpr">
                                        .mpr
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xlam" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xlam" type="checkbox">
                                    <span data-filetypesname=".xlam">application/vnd.ms-excel.addin.macroEnabled.12</span>
                                    <small class="muted" data-filetypesextensions=".xlam">
                                        .xlam
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xlsb" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xlsb" type="checkbox">
                                    <span data-filetypesname=".xlsb">application/vnd.ms-excel.sheet.binary.macroEnabled.12</span>
                                    <small class="muted" data-filetypesextensions=".xlsb">
                                        .xlsb
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xltm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xltm" type="checkbox">
                                    <span data-filetypesname=".xltm">application/vnd.ms-excel.template.macroEnabled.12</span>
                                    <small class="muted" data-filetypesextensions=".xltm">
                                        .xltm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".docm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".docm" type="checkbox">
                                    <span data-filetypesname=".docm">application/vnd.ms-word.document.macroEnabled.12</span>
                                    <small class="muted" data-filetypesextensions=".docm">
                                        .docm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".dotm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".dotm" type="checkbox">
                                    <span data-filetypesname=".dotm">application/vnd.ms-word.template.macroEnabled.12</span>
                                    <small class="muted" data-filetypesextensions=".dotm">
                                        .dotm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".odc" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".odc" type="checkbox">
                                    <span data-filetypesname=".odc">application/vnd.oasis.opendocument.chart</span>
                                    <small class="muted" data-filetypesextensions=".odc">
                                        .odc
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".odb" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".odb" type="checkbox">
                                    <span data-filetypesname=".odb">application/vnd.oasis.opendocument.database</span>
                                    <small class="muted" data-filetypesextensions=".odb">
                                        .odb
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".odf" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".odf" type="checkbox">
                                    <span data-filetypesname=".odf">application/vnd.oasis.opendocument.formula</span>
                                    <small class="muted" data-filetypesextensions=".odf">
                                        .odf
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".odg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".odg" type="checkbox">
                                    <span data-filetypesname=".odg">application/vnd.oasis.opendocument.graphics</span>
                                    <small class="muted" data-filetypesextensions=".odg">
                                        .odg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".otg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".otg" type="checkbox">
                                    <span data-filetypesname=".otg">application/vnd.oasis.opendocument.graphics-template</span>
                                    <small class="muted" data-filetypesextensions=".otg">
                                        .otg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".odi" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".odi" type="checkbox">
                                    <span data-filetypesname=".odi">application/vnd.oasis.opendocument.image</span>
                                    <small class="muted" data-filetypesextensions=".odi">
                                        .odi
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".odm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".odm" type="checkbox">
                                    <span data-filetypesname=".odm">application/vnd.oasis.opendocument.text-master</span>
                                    <small class="muted" data-filetypesextensions=".odm">
                                        .odm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".dotx" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".dotx" type="checkbox">
                                    <span data-filetypesname=".dotx">application/vnd.openxmlformats-officedocument.wordprocessingml.template</span>
                                    <small class="muted" data-filetypesextensions=".dotx">
                                        .dotx
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".sxc" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".sxc" type="checkbox">
                                    <span data-filetypesname=".sxc">application/vnd.sun.xml.calc</span>
                                    <small class="muted" data-filetypesextensions=".sxc">
                                        .sxc
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".stc" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".stc" type="checkbox">
                                    <span data-filetypesname=".stc">application/vnd.sun.xml.calc.template</span>
                                    <small class="muted" data-filetypesextensions=".stc">
                                        .stc
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".sxd" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".sxd" type="checkbox">
                                    <span data-filetypesname=".sxd">application/vnd.sun.xml.draw</span>
                                    <small class="muted" data-filetypesextensions=".sxd">
                                        .sxd
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".std" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".std" type="checkbox">
                                    <span data-filetypesname=".std">application/vnd.sun.xml.draw.template</span>
                                    <small class="muted" data-filetypesextensions=".std">
                                        .std
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".sxm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".sxm" type="checkbox">
                                    <span data-filetypesname=".sxm">application/vnd.sun.xml.math</span>
                                    <small class="muted" data-filetypesextensions=".sxm">
                                        .sxm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".sxw" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".sxw" type="checkbox">
                                    <span data-filetypesname=".sxw">application/vnd.sun.xml.writer</span>
                                    <small class="muted" data-filetypesextensions=".sxw">
                                        .sxw
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".sxg" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".sxg" type="checkbox">
                                    <span data-filetypesname=".sxg">application/vnd.sun.xml.writer.global</span>
                                    <small class="muted" data-filetypesextensions=".sxg">
                                        .sxg
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".stw" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".stw" type="checkbox">
                                    <span data-filetypesname=".stw">application/vnd.sun.xml.writer.template</span>
                                    <small class="muted" data-filetypesextensions=".stw">
                                        .stw
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xfd" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xfd" type="checkbox">
                                    <span data-filetypesname=".xfd">application/vnd.xfdl</span>
                                    <small class="muted" data-filetypesextensions=".xfd">
                                        .xfd
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".cs" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".cs" type="checkbox">
                                    <span data-filetypesname=".cs">application/x-csh</span>
                                    <small class="muted" data-filetypesextensions=".cs">
                                        .cs
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".dcr" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".dcr" type="checkbox">
                                    <span data-filetypesname=".dcr">application/x-director</span>
                                    <small class="muted" data-filetypesextensions=".dcr">
                                        .dcr
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".dir" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".dir" type="checkbox">
                                    <span data-filetypesname=".dir">application/x-director</span>
                                    <small class="muted" data-filetypesextensions=".dir">
                                        .dir
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".dxr" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".dxr" type="checkbox">
                                    <span data-filetypesname=".dxr">application/x-director</span>
                                    <small class="muted" data-filetypesextensions=".dxr">
                                        .dxr
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".swa" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".swa" type="checkbox">
                                    <span data-filetypesname=".swa">application/x-director</span>
                                    <small class="muted" data-filetypesextensions=".swa">
                                        .swa
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jnlp" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jnlp" type="checkbox">
                                    <span data-filetypesname=".jnlp">application/x-java-jnlp-file</span>
                                    <small class="muted" data-filetypesextensions=".jnlp">
                                        .jnlp
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".latex" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".latex" type="checkbox">
                                    <span data-filetypesname=".latex">application/x-latex</span>
                                    <small class="muted" data-filetypesextensions=".latex">
                                        .latex
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mdb" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mdb" type="checkbox">
                                    <span data-filetypesname=".mdb">application/x-msaccess</span>
                                    <small class="muted" data-filetypesextensions=".mdb">
                                        .mdb
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".sh" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".sh" type="checkbox">
                                    <span data-filetypesname=".sh">application/x-sh</span>
                                    <small class="muted" data-filetypesextensions=".sh">
                                        .sh
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".nbk" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".nbk" type="checkbox">
                                    <span data-filetypesname=".nbk">application/x-smarttech-notebook</span>
                                    <small class="muted" data-filetypesextensions=".nbk">
                                        .nbk
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".notebook" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".notebook" type="checkbox">
                                    <span data-filetypesname=".notebook">application/x-smarttech-notebook</span>
                                    <small class="muted" data-filetypesextensions=".notebook">
                                        .notebook
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".gallery" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".gallery" type="checkbox">
                                    <span data-filetypesname=".gallery">application/x-smarttech-notebook</span>
                                    <small class="muted" data-filetypesextensions=".gallery">
                                        .gallery
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".galleryitem" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".galleryitem" type="checkbox">
                                    <span data-filetypesname=".galleryitem">application/x-smarttech-notebook</span>
                                    <small class="muted" data-filetypesextensions=".galleryitem">
                                        .galleryitem
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".gallerycollection" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".gallerycollection" type="checkbox">
                                    <span data-filetypesname=".gallerycollection">application/x-smarttech-notebook</span>
                                    <small class="muted" data-filetypesextensions=".gallerycollection">
                                        .gallerycollection
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xbk" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xbk" type="checkbox">
                                    <span data-filetypesname=".xbk">application/x-smarttech-notebook</span>
                                    <small class="muted" data-filetypesextensions=".xbk">
                                        .xbk
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".tex" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".tex" type="checkbox">
                                    <span data-filetypesname=".tex">application/x-tex</span>
                                    <small class="muted" data-filetypesextensions=".tex">
                                        .tex
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".texi" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".texi" type="checkbox">
                                    <span data-filetypesname=".texi">application/x-texinfo</span>
                                    <small class="muted" data-filetypesextensions=".texi">
                                        .texi
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".texinfo" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".texinfo" type="checkbox">
                                    <span data-filetypesname=".texinfo">application/x-texinfo</span>
                                    <small class="muted" data-filetypesextensions=".texinfo">
                                        .texinfo
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xml" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xml" type="checkbox">
                                    <span data-filetypesname=".xml">application/xml</span>
                                    <small class="muted" data-filetypesextensions=".xml">
                                        .xml
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mbz" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mbz" type="checkbox">
                                    <span data-filetypesname=".mbz">Bản sao lưu Moodle</span>
                                    <small class="muted" data-filetypesextensions=".mbz">
                                        .mbz
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".json" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".json" type="checkbox">
                                    <span data-filetypesname=".json">JSON text</span>
                                    <small class="muted" data-filetypesextensions=".json">
                                        .json
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".h5p" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".h5p" type="checkbox">
                                    <span data-filetypesname=".h5p">Lưu trữ (H5P)</span>
                                    <small class="muted" data-filetypesextensions=".h5p">
                                        .h5p
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xltx" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xltx" type="checkbox">
                                    <span data-filetypesname=".xltx">Mẫu Excel</span>
                                    <small class="muted" data-filetypesextensions=".xltx">
                                        .xltx
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mht" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mht" type="checkbox">
                                    <span data-filetypesname=".mht">message/rfc822</span>
                                    <small class="muted" data-filetypesextensions=".mht">
                                        .mht
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".mhtml" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".mhtml" type="checkbox">
                                    <span data-filetypesname=".mhtml">message/rfc822</span>
                                    <small class="muted" data-filetypesextensions=".mhtml">
                                        .mhtml
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".cct" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".cct" type="checkbox">
                                    <span data-filetypesname=".cct">shockwave/director</span>
                                    <small class="muted" data-filetypesextensions=".cct">
                                        .cct
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".ics" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".ics" type="checkbox">
                                    <span data-filetypesname=".ics">text/calendar</span>
                                    <small class="muted" data-filetypesextensions=".ics">
                                        .ics
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".rtx" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".rtx" type="checkbox">
                                    <span data-filetypesname=".rtx">text/richtext</span>
                                    <small class="muted" data-filetypesextensions=".rtx">
                                        .rtx
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".tsv" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".tsv" type="checkbox">
                                    <span data-filetypesname=".tsv">text/tab-separated-values</span>
                                    <small class="muted" data-filetypesextensions=".tsv">
                                        .tsv
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".htc" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".htc" type="checkbox">
                                    <span data-filetypesname=".htc">text/x-component</span>
                                    <small class="muted" data-filetypesextensions=".htc">
                                        .htc
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".xsl" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".xsl" type="checkbox">
                                    <span data-filetypesname=".xsl">text/xml</span>
                                    <small class="muted" data-filetypesextensions=".xsl">
                                        .xsl
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".sqt" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".sqt" type="checkbox">
                                    <span data-filetypesname=".sqt">text/xml</span>
                                    <small class="muted" data-filetypesextensions=".sqt">
                                        .sqt
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jmx" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jmx" type="checkbox">
                                    <span data-filetypesname=".jmx">text/xml</span>
                                    <small class="muted" data-filetypesextensions=".jmx">
                                        .jmx
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jcb" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jcb" type="checkbox">
                                    <span data-filetypesname=".jcb">text/xml</span>
                                    <small class="muted" data-filetypesextensions=".jcb">
                                        .jcb
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jcl" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jcl" type="checkbox">
                                    <span data-filetypesname=".jcl">text/xml</span>
                                    <small class="muted" data-filetypesextensions=".jcl">
                                        .jcl
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jcw" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jcw" type="checkbox">
                                    <span data-filetypesname=".jcw">text/xml</span>
                                    <small class="muted" data-filetypesextensions=".jcw">
                                        .jcw
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".rhb" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".rhb" type="checkbox">
                                    <span data-filetypesname=".rhb">text/xml</span>
                                    <small class="muted" data-filetypesextensions=".rhb">
                                        .rhb
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jmt" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jmt" type="checkbox">
                                    <span data-filetypesname=".jmt">text/xml</span>
                                    <small class="muted" data-filetypesextensions=".jmt">
                                        .jmt
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".jqz" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".jqz" type="checkbox">
                                    <span data-filetypesname=".jqz">text/xml</span>
                                    <small class="muted" data-filetypesextensions=".jqz">
                                        .jqz
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".txt" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".txt" type="checkbox">
                                    <span data-filetypesname=".txt">Tệp văn bản</span>
                                    <small class="muted" data-filetypesextensions=".txt">
                                        .txt
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".m" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".m" type="checkbox">
                                    <span data-filetypesname=".m">Tệp văn bản</span>
                                    <small class="muted" data-filetypesextensions=".m">
                                        .m
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".applescript" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".applescript" type="checkbox">
                                    <span data-filetypesname=".applescript">Tệp văn bản</span>
                                    <small class="muted" data-filetypesextensions=".applescript">
                                        .applescript
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".asc" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".asc" type="checkbox">
                                    <span data-filetypesname=".asc">Tệp văn bản</span>
                                    <small class="muted" data-filetypesextensions=".asc">
                                        .asc
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".php" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".php" type="checkbox">
                                    <span data-filetypesname=".php">Tệp văn bản</span>
                                    <small class="muted" data-filetypesextensions=".php">
                                        .php
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".asm" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".asm" type="checkbox">
                                    <span data-filetypesname=".asm">Tệp văn bản</span>
                                    <small class="muted" data-filetypesextensions=".asm">
                                        .asm
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".java" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".java" type="checkbox">
                                    <span data-filetypesname=".java">Tệp văn bản</span>
                                    <small class="muted" data-filetypesextensions=".java">
                                        .java
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".c" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".c" type="checkbox">
                                    <span data-filetypesname=".c">Tệp văn bản</span>
                                    <small class="muted" data-filetypesextensions=".c">
                                        .c
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".hpp" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".hpp" type="checkbox">
                                    <span data-filetypesname=".hpp">Tệp văn bản</span>
                                    <small class="muted" data-filetypesextensions=".hpp">
                                        .hpp
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".h" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".h" type="checkbox">
                                    <span data-filetypesname=".h">Tệp văn bản</span>
                                    <small class="muted" data-filetypesextensions=".h">
                                        .h
                                    </small>
                                </label>
                            </li>
                            <li data-filetypesbrowserkey=".cpp" style="margin-left: 2em" role="treeitem">
                                <label>
                                    <input data-filetypesbrowserkey=".cpp" type="checkbox">
                                    <span data-filetypesname=".cpp">Tệp văn bản</span>
                                    <small class="muted" data-filetypesextensions=".cpp">
                                        .cpp
                                    </small>
                                </label>
                            </li>
                        </ul>
                        <hr style="clear:both">
                    </div>
                </div>
            </div>
            <div class="modal-footer" data-region="footer">
                <button type="button" class="btn btn-primary" data-action="save">Lưu những thay đổi</button>
                <button type="button" class="btn btn-secondary" data-action="cancel">Huỷ bỏ</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" data-backdrop="static" id="popup_select_file" tabindex="-1" style="/* display: none; */" >
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                   Bộ chọn tệp
                </h5>
                <button type="button" style="font-size: 3em;" class="close btn-close-edit-md" data-dismiss="modal" aria-label="Close" id="yui_3_17_2_1_1729853647785_60">
                    <span >×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="moodle-dialogue-bd yui3-widget-bd" style="height: 0px;" id="yui_3_17_2_1_1732606171600_1980">
                    <div class="container repository_contentbank" id="filepicker-674579d000d90"
                        aria-labelledby="fp-dialog-label_674579d000d90">
                        <div tabindex="0" class="file-picker fp-generallayout row" role="dialog" aria-live="assertive"
                            id="yui_3_17_2_1_1732606171600_1979">
                            <div class="fp-repo-area col-md-3 pr-2 nav nav-pills flex-column" role="tablist"
                                id="yui_3_17_2_1_1732606171600_2762">


                                <div class="fp-repo nav-item first odd active" role="tab" aria-selected="true" tabindex="0"
                                    id="fp-repo-674579d000d90-2">
                                    <a href="#" class="nav-link active" tabindex="-1" aria-selected="false"
                                        id="yui_3_17_2_1_1732606171600_2761"><img class="fp-repo-icon" alt=" " width="16" height="16"
                                            src="https://english.ican.vn/classroom/theme/image.php/mb2cg/repository_contentbank/1706779039/icon">&nbsp;<span
                                            class="fp-repo-name" id="yui_3_17_2_1_1732606171600_2760">Ngân hàng nội dung</span></a>
                                </div>
                                <div class="fp-repo nav-item even" role="tab" aria-selected="false" tabindex="-1"
                                    id="fp-repo-674579d000d90-3">
                                    <a href="#" class="nav-link" tabindex="-1" aria-selected="false"
                                        id="yui_3_17_2_1_1732606171600_2809"><img class="fp-repo-icon" alt=" " width="16" height="16"
                                            src="https://english.ican.vn/classroom/theme/image.php/mb2cg/repository_local/1706779039/icon">&nbsp;<span
                                            class="fp-repo-name" id="yui_3_17_2_1_1732606171600_2903">Server files</span></a>
                                </div>
                                <div class="fp-repo nav-item odd" role="tab" aria-selected="false" tabindex="-1"
                                    id="fp-repo-674579d000d90-4">
                                    <a href="#" class="nav-link" tabindex="-1" aria-selected="false"><img class="fp-repo-icon" alt=" "
                                            width="16" height="16"
                                            src="https://english.ican.vn/classroom/theme/image.php/mb2cg/repository_recent/1706779039/icon">&nbsp;<span
                                            class="fp-repo-name">Recent files</span></a>
                                </div>
                                <div class="fp-repo nav-item even" role="tab" aria-selected="false" tabindex="-1"
                                    id="fp-repo-674579d000d90-5">
                                    <a href="#" class="nav-link" tabindex="-1" aria-selected="false"><img class="fp-repo-icon" alt=" "
                                            width="16" height="16"
                                            src="https://english.ican.vn/classroom/theme/image.php/mb2cg/repository_upload/1706779039/icon">&nbsp;<span
                                            class="fp-repo-name">Tải lên một tài liệu</span></a>
                                </div>
                                <div class="fp-repo nav-item odd" role="tab" aria-selected="false" tabindex="-1"
                                    id="fp-repo-674579d000d90-6">
                                    <a href="#" class="nav-link" tabindex="-1" aria-selected="false"><img class="fp-repo-icon" alt=" "
                                            width="16" height="16"
                                            src="https://english.ican.vn/classroom/theme/image.php/mb2cg/repository_url/1706779039/icon">&nbsp;<span
                                            class="fp-repo-name">URL downloader</span></a>
                                </div>
                                <div class="fp-repo nav-item even" role="tab" aria-selected="false" tabindex="-1"
                                    id="fp-repo-674579d000d90-7">
                                    <a href="#" class="nav-link" tabindex="-1" aria-selected="false"><img class="fp-repo-icon" alt=" "
                                            width="16" height="16"
                                            src="https://english.ican.vn/classroom/theme/image.php/mb2cg/repository_user/1706779039/icon">&nbsp;<span
                                            class="fp-repo-name">memek</span></a>
                                </div>
                                <div class="fp-repo nav-item odd" role="tab" aria-selected="false" tabindex="-1"
                                    id="fp-repo-674579d000d90-8">
                                    <a href="#" class="nav-link" tabindex="-1" aria-selected="false"><img class="fp-repo-icon" alt=" "
                                            width="16" height="16"
                                            src="https://english.ican.vn/classroom/theme/image.php/mb2cg/repository_wikimedia/1706779039/icon">&nbsp;<span
                                            class="fp-repo-name">Wikimedia</span></a>
                                </div>
                            </div>
                            <div class="col-md-9 p-0" id="yui_3_17_2_1_1732606171600_1978">
                                <div class="fp-repo-items" tabindex="0" id="yui_3_17_2_1_1732606171600_1977">
                                    <div class="fp-navbar bg-faded card mb-0 clearfix icon-no-spacing"
                                        id="yui_3_17_2_1_1732606171600_2206">
                                        <div id="yui_3_17_2_1_1732606171600_2205">
                                            <div class="fp-toolbar" id="yui_3_17_2_1_1732606171600_1722">
                                                <div class="fp-tb-back disabled">
                                                    <a href="#" class="btn btn-secondary btn-sm">Trở về</a>
                                                </div>
                                                <div class="fp-tb-search enabled">
                                                    <form method="POST" id="fp-tb-search-674579d000d90">
                                                        <div class="fp-def-search form-group">
                                                            <label class="sr-only" for="reposearch">Tìm kiếm kho</label>
                                                            <input type="search" class="form-control" id="reposearch" name="s"
                                                                placeholder="Tìm kiếm">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="fp-tb-refresh enabled" id="yui_3_17_2_1_1732606171600_1729">
                                                    <a title="Làm mới" class="btn btn-secondary btn-sm" href="#">
                                                        <i class="icon fa fa-refresh fa-fw " aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                <div class="fp-tb-logout disabled" id="yui_3_17_2_1_1732606171600_1724">
                                                    <a title="Thoát" class="btn btn-secondary btn-sm" href="#">
                                                        <i class="icon fa fa-sign-out fa-fw " aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                <div class="fp-tb-manage enabled" id="yui_3_17_2_1_1732606171600_1738">
                                                    <a title="Quản lí" class="btn btn-secondary btn-sm" href="#">
                                                        <i class="icon fa fa-cog fa-fw " aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                <div class="fp-tb-help disabled" id="yui_3_17_2_1_1732606171600_1744">
                                                    <a title="Trợ giúp" class="btn btn-secondary btn-sm" href="#">
                                                        <i class="icon fa fa-question-circle text-info fa-fw " aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                <div class="fp-tb-message disabled"></div>
                                                <a id="fp-tb-manage-674579d000d90-link" target="_blank"
                                                    href="https://english.ican.vn/classroom/contentbank/index.php?contextid=108647"
                                                    style="display: none;"></a><a id="fp-tb-help-674579d000d90-link" target="_blank"
                                                    href="null" style="display: none;"></a>
                                            </div>
                                            <div class="fp-viewbar btn-group float-sm-right enabled"
                                                id="yui_3_17_2_1_1732606171600_2204">
                                                <a role="button" title="Hiển thị thư mục với biểu tượng tệp"
                                                    class="fp-vb-icons btn btn-secondary btn-sm checked" href="#" aria-disabled="false"
                                                    tabindex="" id="yui_3_17_2_1_1732606171600_2443">
                                                    <i class="icon fa fa-th fa-fw " aria-hidden="true"
                                                        id="yui_3_17_2_1_1732606171600_2699"></i>
                                                </a>
                                                <a role="button" title="Hiển thi thư mục với chi tiết tệp"
                                                    class="fp-vb-details btn btn-secondary btn-sm" href="#" aria-disabled="false"
                                                    tabindex="" id="yui_3_17_2_1_1732606171600_2203">
                                                    <i class="icon fa fa-list fa-fw " aria-hidden="true"
                                                        id="yui_3_17_2_1_1732606171600_2469"></i>
                                                </a>
                                                <a role="button" title="Hiển thị thư mục như cây tập tin"
                                                    class="fp-vb-tree btn btn-secondary btn-sm" href="#" aria-disabled="false"
                                                    tabindex="" id="yui_3_17_2_1_1732606171600_2405">
                                                    <i class="icon fa fa-folder fa-fw " aria-hidden="true"
                                                        id="yui_3_17_2_1_1732606171600_2404"></i>
                                                </a>
                                            </div>
                                            <div class="fp-clear-left"></div>
                                        </div>
                                        <div class="fp-pathbar"><span class="fp-path-folder first odd"
                                                id="yui_3_17_2_1_1732606171600_2980"><a class="fp-path-folder-name aalink" href="#">Hệ
                                                    thống</a></span><span class="fp-path-folder even"
                                                id="yui_3_17_2_1_1732606171600_2984"><a class="fp-path-folder-name aalink" href="#">Sản
                                                    phẩm 120</a></span><span class="fp-path-folder last odd"
                                                id="yui_3_17_2_1_1732606171600_2988"><a class="fp-path-folder-name aalink" href="#">Sản
                                                    phẩm 122</a></span></div>
                                    </div>
                                    <div class="fp-content card" id="yui_3_17_2_1_1732606171600_2069" tabindex="0">
                                        <div class="fp-content-error">
                                            <div class="fp-error nofilesavailable">Không có tệp</div>
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
</div>

<script src="{{ URL::asset('js/product_detail/product_detail.js') }}"></script>
<script src="{{ URL::asset('js/activity/video.js') }}"></script>
<script src="{{ URL::asset('js/activity/quiz.js') }}"></script>
@endsection
