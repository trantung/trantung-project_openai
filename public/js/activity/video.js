$(document).ready(function() {
    const wrapperRightPart = '.course-detail-page .right-part';
    const wrapperLeftContent = wrapperRightPart + ' .wrapper-dynamic-form .left-side-content';
    const wrapperRightContent = wrapperRightPart + ' .wrapper-dynamic-form .right-side-content';
    function generateVideoFormHTML() {
        let html = '';
        html += '<form data-random-ids="1" autocomplete="off" action="https://english.ican.vn/classroom/lib/ajax/service.php" method="post" accept-charset="utf-8" id="mform1_5CAlUbEVW9HrPSN" class="mform" data-boost-form-errors-enhanced="1">';
        html += `
            <div style="display: none;"><input name="scoes" type="hidden" value="[{&quot;title&quot;:&quot;&quot;,&quot;launch&quot;:&quot;&quot;,&quot;id&quot;:&quot;23923&quot;,&quot;server&quot;:&quot;0&quot;,&quot;child&quot;:{},&quot;iconRemove&quot;:&quot;<i class=\&quot;fa fa-minus-circle\&quot; aria-hidden=\&quot;true\&quot;><\/i>&quot;}]" id="id_scoes_JLhn7tNGoOCSuJd">
                <input name="course_id" type="hidden" value="203" id="id_course_id_yjZd5H9YBaP63QN">
                <input name="cmid" type="hidden" value="65477" id="id_cmid_vasb7OeXjfWpY1B">
                <input name="parent" type="hidden" value="11524" id="id_parent_8DkPHc1hiikP8iF">
                <input name="type" type="hidden" value="video" id="id_type_c9o1Ho2YijK4buc">
                <input name="instance" type="hidden" value="6811" id="id_instance_rescJXTeO0EKAfW">
                <input name="dynamic_form_custom" type="hidden" value="1" id="id_dynamic_form_custom_IuSLUEnbEiJuf21">
                <input name="sesskey" type="hidden" value="oeKZF5YCKb" id="id_sesskey_WOMRFAgNQG1bNBF">
                <input name="_qf__course_video_form" type="hidden" value="1" id="id___course_video_form_mi1I33K8ALnSfjM">
            </div>

            <div class="left-side-content mt-2">
                <div class="text-right mb-3">
                    <a href="javascript:void(0);" class="btn-act-view btn-play-video">
                        <i class="fa fa-arrows-alt fa-2x" title="Xem nội dung" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="box-head">
                        <h4>Mục lục bài giảng</h4>
                    </div>
                    <div class="text-right mt-3">
                        <a href="javascript:void(0);" class="btn btn-secondary btn-add-video">
                            <i class="fa fa-plus" title="Xem nội dung" aria-hidden="true"></i> Video
                        </a>
                    </div>
                </div>
                <div class="video-list">
                    <div class="video-item">
                        <div class="title-path-container">
                            <div class="title-container">
                                <div id="fitem_video_scoes[0][title]" class="form-group row  fitem   ">
                                    <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                        <label class="d-inline word-break " for="video_scoes[0][title]">
                                            Tiêu đề
                                        </label>
                                        <div class="form-label-addon d-flex align-items-center align-self-start">
                                        </div>
                                    </div>
                                    <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                        <input type="text" class="form-control " name="video_scoes[0][title]" id="video_scoes[0][title]" value="" data-name="title" data-video-soces-id="23923">
                                        <div class="form-control-feedback invalid-feedback" id="video_scoes[0][title]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="path-container">
                                <div id="fitem_video_scoes[0][launch]" class="form-group row  fitem   ">
                                    <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                        <label class="d-inline word-break " for="video_scoes[0][launch]">
                                            Video
                                        </label>
                                        <div class="form-label-addon d-flex align-items-center align-self-start">
                                        </div>
                                    </div>
                                    <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                        <input type="text" class="form-control " name="video_scoes[0][launch]" id="video_scoes[0][launch]" value="" data-name="launch">
                                        <div class="form-control-feedback invalid-feedback" id="video_scoes[0][launch]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="delete-container" style="display:none;">
                            <a href="#" style="margin-right: 10px;" class="btn-delete-video delete-center" data-video-soces-id="">
                                <i class="fa fa-minus-circle" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="server-container">
                            <div id="fitem_video_scoes[0][server]" class="form-group row  fitem   ">
                                <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                    <label class="d-inline word-break " for="video_scoes[0][server]">
                                        Server
                                    </label>
                                    <div class="form-label-addon d-flex align-items-center align-self-start">
                                    </div>
                                </div>
                                <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                                    <select class="custom-select" name="video_scoes[0][server]" id="video_scoes[0][server]" data-name="server">
                                        <option value="0" selected="">-- Server --</option>
                                        <option value="1">Server 1</option>
                                        <option value="2">Server 2</option>
                                        <option value="3">Server 3</option>
                                        <option value="4">Server 4</option>
                                        <option value="5">Server 5</option>
                                    </select>
                                    <div class="form-control-feedback invalid-feedback" id="video_scoes[0][server]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="video-play mt-3 d-none">
                    <div class="hm-player-wr clearfix">
                        <div class="hm-player-header clearfix">
                            <div class="struct-menu on">
                                <div class="struct-menu-title">
                                    <i class="fa fa-bars"></i>
                                    <span>&nbsp;&nbsp;Mục lục bài giảng</span>
                                </div>
                                <ul class="playlist scorm-detail-playlist">
                                    <li class="" data-id="23923" data-uid="1827" data-complete="0" data-cmid="65477" data-vid="6811">
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="hm-player clearfix">
                            <div class="hm-video-overlay"></div>
                            <a data-toggle="unmute" class="muted-switch clearfix">
                                <span class="muted-switch-icon"></span> 
                                <span class="muted-switch-text">Nhấn để bật tiếng</span>
                            </a>
                            <video id="hocmaiplayer" class="video-js vjs-default-skin vjs-big-play-centered player-rp2 vjs-16-9" autoplay="" controls="">
                                <p class="vjs-no-js">
                                    Để xem video bạn vui lòng bật Javascript, và sử dụng trình duyệt <a href="http://videojs.com/html5-video-support/" target="_blank">hỗ trợ HTML5</a>
                                </p>
                            </video>
                            <script type="text/javascript">
                                $html5Playlist = [];
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-side-content mt-2 hide">
                <div class="wrapper-label-name">
                    <h4>Video</h4>
                </div>
                <div class="box-head">
                    <h4>Thông tin chung</h4>
                </div>
                <div class="mb-2">
                    <div id="fitem_id_name_v6jjOUl3X7LVAAr" class="form-group row  fitem   ">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

                            <label class="d-inline word-break " for="id_name_v6jjOUl3X7LVAAr">
                                Tên video
                            </label>

                            <div class="form-label-addon d-flex align-items-center align-self-start">
                                <div class="text-danger" title="Bắt buộc">
                                    <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Bắt buộc" aria-label="Bắt buộc"></i>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                            <input type="text" class="form-control " name="name" id="id_name_v6jjOUl3X7LVAAr" value="Video 6811">
                            <div class="form-control-feedback invalid-feedback" id="id_error_name_v6jjOUl3X7LVAAr">

                            </div>
                        </div>
                    </div>
                </div>
                <div id="fitem_id_introeditor_hIgEy4V9hqtnNGh" class="form-group row  fitem   ">
                    <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

                        <label class="d-inline word-break " for="id_introeditor_hIgEy4V9hqtnNGh">
                            Mô tả
                        </label>

                        <div class="form-label-addon d-flex align-items-center align-self-start">

                        </div>
                    </div>
                    <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="editor">
                        <div>
                            <div class="editor_atto_wrap">
                                <div class="editor_atto">
                                    <div class="editor_atto_toolbar" role="toolbar" aria-live="off" aria-labelledby="yui_3_17_2_1_1729861707900_631" aria-activedescendant="yui_3_17_2_1_1729861707900_681">
                                        <div class="atto_group collapse_group">
                                            <button type="button" class="atto_collapse_button" tabindex="0" title="Nút xem thêm">
                                                <i class="icon fa fa-level-down fa-fw" title="Show/hide advanced buttons" aria-label="Show/hide advanced buttons"></i>
                                            </button>
                                        </div>
                                        <div class="atto_group style1_group">
                                            <button type="button" class="atto_title_button atto_hasmenu" id="atto_title_menubutton"ndex="-1" title="Paragraph styles" aria-haspopup="true" aria-controls="atto_title_menubutton">
                                                <span class="editor_atto_menu_icon">
                                                    <i class="icon fa fa-font fa-fw" title="Paragraph styles" aria-label="Paragraph styles"></i>
                                                </span>
                                                <span class="editor_atto_menu_expand">
                                                    <i class="icon fa fa-caret-down fa-fw" aria-hidden="true"></i>
                                                </span>
                                            </button>
                                            <button type="button" class="atto_bold_button" tabindex="-1" title="Đậm [Ctrl + b]">
                                                <i class="icon fa fa-bold fa-fw" title="Đậm" aria-label="Đậm"></i>
                                            </button>
                                            <button type="button" class="atto_italic_button" tabindex="-1" title="Nghiêng [Ctrl + i]">
                                                <i class="icon fa fa-italic fa-fw" title="Nghiêng" aria-label="Nghiêng"></i>
                                            </button>
                                            <button type="button" class="atto_morebackcolors_button atto_hasmenu" id="atto_morebackcolors_menubutton"ndex="-1" title="More font background colors" aria-haspopup="true" aria-controls="atto_morebackcolors_menubutton">
                                                <span class="editor_atto_menu_icon">
                                                    <i class="icon fa-regular fa-lightbulb fa-fw" title="More font background colors" aria-label="More font background colors"></i>
                                                </span>
                                                <span class="editor_atto_menu_expand">
                                                    <i class="icon fa fa-caret-down fa-fw" aria-hidden="true"></i>
                                                </span>
                                                <input type="color" id="backColorPicker" class="color-input" />
                                            </button>
                                            <button type="button" class="atto_morefontcolors_button atto_hasmenu" id="atto_morefontcolors_menubutton"ndex="-1" title="More font colors" aria-haspopup="true" aria-controls="atto_morefontcolors_menubutton">
                                                <span class="editor_atto_menu_icon">
                                                    <i class="icon fa fa-paint-brush fa-fw" aria-label="More font colors"></i>
                                                </span>
                                                <span class="editor_atto_menu_expand">
                                                    <i class="icon fa fa-caret-down fa-fw" aria-hidden="true"></i>
                                                </span>
                                                <input type="color" id="fontColorPicker" class="color-input" />
                                            </button>
                                        </div>
                                        <div class="atto_group list_group">
                                            <button type="button" class="atto_unorderedlist_button_insertUnorderedList" tabindex="-1" title="Danh sách không theo thứ tự">
                                                <i class="icon fa fa-list-ul fa-fw" title="Danh sách không theo thứ tự" aria-label="Danh sách không theo thứ tự"></i>
                                            </button>
                                            <button type="button" class="atto_orderedlist_button_insertOrderedList" tabindex="-1" title="Danh sách có thứ tự">
                                                <i class="icon fa fa-list-ol fa-fw" title="Danh sách có thứ tự" aria-label="Danh sách có thứ tự"></i>
                                            </button>
                                            <button type="button" class="atto_indent_button_outdent" tabindex="-1" title="Thò ra đầu dòng">
                                                <i class="icon fa fa-outdent fa-fw" title="Thò ra đầu dòng" aria-label="Thò ra đầu dòng"></i>
                                            </button>
                                            <button type="button" class="atto_indent_button_indent" tabindex="-1" title="Lui vào đầu dòng">
                                                <i class="icon fa fa-indent fa-fw" title="Lui vào đầu dòng" aria-label="Lui vào đầu dòng"></i>
                                            </button>
                                        </div>
                                        <div class="atto_group links_group">
                                            <button type="button" class="atto_link_button" tabindex="-1" title="Link [Ctrl + k]">
                                                <i class="icon fa fa-link fa-fw" title="Link" aria-label="Link"></i>
                                            </button>
                                            <button type="button" class="atto_link_button_unlink" tabindex="-1" title="Unlink">
                                                <i class="icon fa fa-chain-broken fa-fw" title="Unlink" aria-label="Unlink"></i>
                                            </button>
                                        </div>
                                        <div class="atto_group files_group">
                                            <button type="button" class="atto_emojipicker_button" tabindex="-1" title="Emoji picker">
                                                <i class="icon fa-regular fa-face-smile fa-fw" title="Emoji picker" aria-label="Emoji picker"></i>
                                            </button>
                                            <button type="button" class="atto_image_button" tabindex="-1" title="Insert or edit image">
                                                <i class="icon fa-regular fa-image fa-fw" title="Insert or edit image" aria-label="Insert or edit image"></i>
                                            </button>
                                            <button type="button" class="atto_media_button" tabindex="-1" title="Insert or edit an audio/video file">
                                                <i class="icon fa-regular fa-file-video fa-fw" title="Insert or edit an audio/video file" aria-label="Insert or edit an audio/video file"></i>
                                            </button>
                                            <button type="button" class="atto_recordrtc_button_audio" tabindex="-1" title="Record audio">
                                                <i class="icon fa-solid fa-microphone fa-fw" title="Record audio" aria-label="Record audio"></i>
                                            </button>
                                            <button type="button" class="atto_recordrtc_button_video" tabindex="-1" title="Record video">
                                                <i class="icon fa-solid fa-video fa-fw" title="Record video" aria-label="Record video"></i>
                                            </button>
                                            <button type="button" class="atto_managefiles_button" tabindex="-1" title="Manage files">
                                                <i class="icon fa-regular fa-file fa-fw" title="Manage files" aria-label="Manage files"></i>
                                            </button>
                                            <button type="button" class="atto_h5p_button" tabindex="-1" title="Insert H5P">
                                                <img class="icon" src="https://english.ican.vn/classroom/theme/image.php/mb2cg/atto_h5p/1706779039/icon" alt="Insert H5P" title="Insert H5P">
                                            </button>
                                        </div>
                                        <div class="toolbarbreak"></div>
                                        <div class="atto_group style2_group" hidden="hidden" style="display: none;">
                                            <button type="button" class="atto_underline_button_underline" tabindex="-1" title="Underline [Ctrl + u]">
                                                <i class="icon fa fa-underline fa-fw" title="Underline" aria-label="Underline"></i>
                                            </button>
                                            <button type="button" class="atto_strike_button" tabindex="-1" title="Strike through">
                                                <i class="icon fa fa-strikethrough fa-fw" title="Strike through" aria-label="Strike through"></i>
                                            </button>
                                            <button type="button" class="atto_subscript_button_subscript" tabindex="-1" title="Subscript">
                                                <i class="icon fa fa-subscript fa-fw" title="Subscript" aria-label="Subscript"></i>
                                            </button>
                                            <button type="button" class="atto_superscript_button_superscript" tabindex="-1" title="Superscript">
                                                <i class="icon fa fa-superscript fa-fw" title="Superscript" aria-label="Superscript"></i>
                                            </button>
                                        </div>
                                        <div class="atto_group align_group" hidden="hidden" style="display: none;">
                                            <button type="button" class="atto_align_button_justifyLeft" tabindex="-1" title="Left align">
                                                <i class="icon fa fa-align-left fa-fw" title="Left align" aria-label="Left align"></i>
                                            </button>
                                            <button type="button" class="atto_align_button_justifyCenter" tabindex="-1" title="Center">
                                                <i class="icon fa fa-align-center fa-fw" title="Center" aria-label="Center"></i>
                                            </button>
                                            <button type="button" class="atto_align_button_justifyRight" tabindex="-1" title="Right align">
                                                <i class="icon fa fa-align-right fa-fw" title="Right align" aria-label="Right align"></i>
                                            </button>
                                        </div>
                                        <div class="atto_group insert_group" hidden="hidden" style="display: none;">
                                            <button type="button" class="atto_charmap_button" tabindex="-1" title="Insert character">
                                                <i class="icon fa-regular fa-pen-to-square fa-fw" title="Insert character" aria-label="Insert character"></i>
                                            </button>
                                            <button type="button" class="atto_table_button" tabindex="-1" title="Table">
                                                <i class="icon fa fa-table fa-fw" title="Table" aria-label="Table"></i>
                                            </button>
                                            <button type="button" class="atto_clear_button_removeFormat" tabindex="-1" title="Clear formatting">
                                                <i class="icon fa fa-i-cursor fa-fw" title="Clear formatting" aria-label="Clear formatting"></i>
                                            </button>
                                        </div>
                                        <div class="atto_group undo_group" hidden="hidden" style="display: none;">
                                            <button type="button" class="atto_undo_button_undo" tabindex="-1" title="Undo [Ctrl + z]" disabled="disabled">
                                                <i class="icon fa fa-undo fa-fw" title="Undo" aria-label="Undo"></i>
                                            </button>
                                            <button type="button" class="atto_undo_button_redo" tabindex="-1" title="Redo [Ctrl + y]" disabled="disabled">
                                                <i class="icon fa fa-repeat fa-fw" title="Redo" aria-label="Redo"></i>
                                            </button>
                                        </div>
                                        <div class="atto_group accessibility_group" hidden="hidden" style="display: none;">
                                            <button type="button" class="atto_accessibilitychecker_button" tabindex="-1" title="Accessibility checker">
                                                <i class="icon fa fa-universal-access fa-fw" title="Accessibility checker" aria-label="Accessibility checker"></i>
                                            </button>
                                            <button type="button" class="atto_accessibilityhelper_button" tabindex="-1" title="Screenreader helper">
                                                <i class="icon fa fa-braille fa-fw" title="Screenreader helper" aria-label="Screenreader helper"></i>
                                            </button>
                                        </div>
                                        <div class="atto_group other_group" hidden="hidden" style="display: none;">
                                            <button type="button" class="atto_html_button" tabindex="-1" title="HTML">
                                                <i class="icon fa fa-code fa-fw" title="HTML" aria-label="HTML"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="editor_atto_content_wrap">
                                        <div id="id_introeditor_hIgEy4V9hqtnNGheditable" contenteditable="true" role="textbox" spellcheck="true" aria-live="off" class="editor_atto_content form-control" aria-labelledby="yui_3_17_2_1_1729861707900_631" style="min-height: 208px; height: 208px;">
                                            <p dir="ltr" style="text-align: left;"><br></p>
                                        </div>
                                    </div>
                                </div><textarea id="id_introeditor_hIgEy4V9hqtnNGh" name="introeditor[text]" class="form-control" rows="10" cols="80" spellcheck="true" hidden="hidden" style="display: none;"></textarea>
                            </div>
                            <div>
                                <input name="introeditor[format]" id="menuintroeditorformat" type="hidden" value="1">
                            </div><input type="hidden" name="introeditor[itemid]" value="287761455"><noscript>
                                <div><object type='text/html' data='https://english.ican.vn/classroom/repository/draftfiles_manager.php?action=browse&amp;env=editor&amp;itemid=287761455&amp;subdirs=1&amp;maxbytes=0&amp;areamaxbytes=-1&amp;maxfiles=-1&amp;ctx_id=108200&amp;course=203&amp;sesskey=oeKZF5YCKb' height='160' width='600' style='border:1px solid #000'></object></div>
                            </noscript>
                        </div>
                        <div class="form-control-feedback invalid-feedback" id="id_error_introeditor_hIgEy4V9hqtnNGh">

                        </div>
                    </div>
                </div>
                <div id="fitem_id_instance_parent_DMOSDilCAhAGVEY" class="form-group row  fitem   ">
                    <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

                        <label class="d-inline word-break " for="id_instance_parent_DMOSDilCAhAGVEY">
                            Thư mục cha
                        </label>

                        <div class="form-label-addon d-flex align-items-center align-self-start">

                        </div>
                    </div>
                    <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                        <select class="custom-select
                            
                            " name="instance_parent" id="id_instance_parent_DMOSDilCAhAGVEY">
                            <option value="11523">Thư mục 1</option>
                            <option value="11524" selected="">Thư mục 2</option>
                            <option value="11525">Thư mục 3</option>
                        </select>
                        <div class="form-control-feedback invalid-feedback" id="id_error_instance_parent_DMOSDilCAhAGVEY">

                        </div>
                    </div>
                </div>
                <div id="fitem_id_instance_status_DgCkxd8Hc3XWMi9" class="form-group row  fitem   ">
                    <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

                        <label class="d-inline word-break " for="id_instance_status_DgCkxd8Hc3XWMi9">
                            Trạng thái
                        </label>

                        <div class="form-label-addon d-flex align-items-center align-self-start">

                        </div>
                    </div>
                    <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                        <select class="custom-select
                            
                            " name="instance_status" id="id_instance_status_DgCkxd8Hc3XWMi9">
                            <option value="1" selected="">Hiện</option>
                            <option value="0">Ẩn</option>
                        </select>
                        <div class="form-control-feedback invalid-feedback" id="id_error_instance_status_DgCkxd8Hc3XWMi9">

                        </div>
                    </div>
                </div>
                <div class="box-head">
                    <h4>Hoạt động hoàn thành</h4>
                </div>
                <div class="mb-1 select-check-complete">
                    <div id="fitem_id_completion_tby083WMPxjJEjG" class="form-group row  fitem   ">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

                            <label class="d-inline word-break " for="id_completion_tby083WMPxjJEjG">
                                Completion tracking
                            </label>

                            <div class="form-label-addon d-flex align-items-center align-self-start">

                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                            <select class="custom-select
                            
                            " name="completion" id="id_completion_tby083WMPxjJEjG">
                                <option value="2" selected="">Khi các điều kiện được thỏa mãn, đánh dấu hoạt động như là đã hoàn thành</option>
                                <option value="0">Không chỉ rõ việc hoàn thành hoạt động</option>
                            </select>
                            <div class="form-control-feedback invalid-feedback" id="id_error_completion_tby083WMPxjJEjG">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-1 select-check-complete">
                    <div class="form-group row  fitem  ">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-9 checkbox">
                            <div class="form-check d-flex">
                                <input type="checkbox" name="checkbox_complete" class="form-check-input " value="1" id="id_checkbox_complete_T5jljiRwqZk6Ea8" checked="">
                                <label for="id_checkbox_complete_T5jljiRwqZk6Ea8">
                                    Học viên phải xem hoạt động này để hoàn thành nó
                                </label>
                                <div class="ml-2 d-flex align-items-center align-self-start">

                                </div>
                            </div>
                            <div class="form-control-feedback invalid-feedback" id="id_error_checkbox_complete_T5jljiRwqZk6Ea8">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-head">
                    <h4>Lịch sử</h4>
                </div>
                <div class="mb-1">
                    <div id="fitem_id_created_by_qXviRq2DeSObLkU" class="form-group row  fitem   ">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

                            <label class="d-inline word-break " for="id_created_by_qXviRq2DeSObLkU">
                                Người tạo
                            </label>

                            <div class="form-label-addon d-flex align-items-center align-self-start">

                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                            <input type="text" class="form-control " name="created_by" id="id_created_by_qXviRq2DeSObLkU" value="Nguyễn Duy Anh" disabled="1">
                            <div class="form-control-feedback invalid-feedback" id="id_error_created_by_qXviRq2DeSObLkU">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-1">
                    <div id="fitem_id_created_at_B5PCBUxSRr0dhe1" class="form-group row  fitem   ">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

                            <label class="d-inline word-break " for="id_created_at_B5PCBUxSRr0dhe1">
                                Ngày tạo
                            </label>

                            <div class="form-label-addon d-flex align-items-center align-self-start">

                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                            <input type="text" class="form-control " name="created_at" id="id_created_at_B5PCBUxSRr0dhe1" value="2024-10-24 20:53:45" disabled="1">
                            <div class="form-control-feedback invalid-feedback" id="id_error_created_at_B5PCBUxSRr0dhe1">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-1">
                    <div id="fitem_id_updated_by_9snBzJJANql4CCM" class="form-group row  fitem   ">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

                            <label class="d-inline word-break " for="id_updated_by_9snBzJJANql4CCM">
                                Người cập nhật
                            </label>

                            <div class="form-label-addon d-flex align-items-center align-self-start">

                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                            <input type="text" class="form-control " name="updated_by" id="id_updated_by_9snBzJJANql4CCM" value="" disabled="1">
                            <div class="form-control-feedback invalid-feedback" id="id_error_updated_by_9snBzJJANql4CCM">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-1">
                    <div id="fitem_id_updated_at_bK8bSKl5sXudULX" class="form-group row  fitem   ">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">

                            <label class="d-inline word-break " for="id_updated_at_bK8bSKl5sXudULX">
                                Ngày cập nhật
                            </label>

                            <div class="form-label-addon d-flex align-items-center align-self-start">

                            </div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                            <input type="text" class="form-control " name="updated_at" id="id_updated_at_bK8bSKl5sXudULX" value="" disabled="1">
                            <div class="form-control-feedback invalid-feedback" id="id_error_updated_at_bK8bSKl5sXudULX">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-side-content mt-2 hide"><input type="submit" name="btn-save-dynamic-form-video" class="btn-save-dynamic-form-video btn btn-primary" style="display: none;" value="Lưu"></div>
            </div>
            <div class="fdescription required">Có các mục bắt buộc trong biểu mẫu này được đánh dấu <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Phải có" aria-label="Phải có"></i>.</div>
        `;
        html += '</form>';
        return html;
    }

    $('.expand-video').click(function(e) {
        $('.wrapper-dynamic-form').html('');

        $('.section-quiz-part').hide();

        $('.wrapper_settings').hide();

        $('#setting_dynamic_form').show();

        $('#wrapper-dynamic-video-form').html(generateVideoFormHTML());
    });

    $(document).on('click', '.btn-play-video', function() {
        $('.video-list').toggleClass('d-none'); // Bỏ lớp d-none ở video list
        $('.video-play').toggleClass('d-none'); // Thêm lớp d-none vào video play
    });

    $(document).on('click', '#setting_dynamic_form', function(){
        if($(wrapperRightContent).hasClass("hide")){
            $(wrapperRightPart + ' .wrapper_settings').hide();
            $(wrapperRightContent).removeClass("hide");
            $(this).addClass('color-active');
            $(this).show();

            var wl = '50';
            var wr = '50';
            if ($(wrapperRightPart + ' .wrapper-dynamic-form [name="hidden_form_type"]').val() == 'resource' ||
                    $(wrapperRightPart + ' .wrapper-dynamic-form [name="hidden_form_type"]').val() == 'scorm') {
                wl = '42';
                wr = '58';
            }

            $(wrapperLeftContent).attr('style', 'width: ' + wl + '%');
            $(wrapperRightContent).attr('style', 'width: ' + wr + '%');
        }else{
            $(wrapperRightContent).addClass("hide");
            $(this).removeClass('color-active');

            $(wrapperLeftContent).removeAttr('style');
            $(wrapperRightContent).removeAttr('style');
        }
    });

    $(document).on('click', '.collapse_group .atto_collapse_button', function(){
        $(this).toggleClass('highlight');
        if($(this).hasClass("highlight")){
            $('#wrapper-dynamic-video-form .style2_group').css('display', '');
            $('#wrapper-dynamic-video-form .align_group').css('display', '');
            $('#wrapper-dynamic-video-form .insert_group').css('display', '');
            $('#wrapper-dynamic-video-form .undo_group').css('display', '');
            $('#wrapper-dynamic-video-form .accessibility_group').css('display', '');
            $('#wrapper-dynamic-video-form .other_group').css('display', '');

            $('#wrapper-dynamic-video-form .style2_group').removeAttr('hidden');
            $('#wrapper-dynamic-video-form .align_group').removeAttr('hidden');
            $('#wrapper-dynamic-video-form .insert_group').removeAttr('hidden');
            $('#wrapper-dynamic-video-form .undo_group').removeAttr('hidden');
            $('#wrapper-dynamic-video-form .accessibility_group').removeAttr('hidden');
            $('#wrapper-dynamic-video-form .other_group').removeAttr('hidden');
        }else{
            $('#wrapper-dynamic-video-form .style2_group').hide();
            $('#wrapper-dynamic-video-form .align_group').hide();
            $('#wrapper-dynamic-video-form .insert_group').hide();
            $('#wrapper-dynamic-video-form .undo_group').hide();
            $('#wrapper-dynamic-video-form .accessibility_group').hide();
            $('#wrapper-dynamic-video-form .other_group').hide();

            $('#wrapper-dynamic-video-form .style2_group').attr('hidden', 'hidden');
            $('#wrapper-dynamic-video-form .align_group').attr('hidden', 'hidden');
            $('#wrapper-dynamic-video-form .insert_group').attr('hidden', 'hidden');
            $('#wrapper-dynamic-video-form .undo_group').attr('hidden', 'hidden');
            $('#wrapper-dynamic-video-form .accessibility_group').attr('hidden', 'hidden');
            $('#wrapper-dynamic-video-form .other_group').attr('hidden', 'hidden');
        }
    });
    $(document).on('click', '.atto_bold_button', function(){
        // Đặt con trỏ vào editor trước khi áp dụng in đậm
        $('.editor_atto_content').focus();

        // Sử dụng execCommand để in đậm văn bản
        document.execCommand('bold');
    });

    $(document).on('click', '.atto_italic_button', function(){
        // Đặt con trỏ vào editor trước khi áp dụng in đậm
        $('.editor_atto_content').focus();

        // Sử dụng execCommand để in đậm văn bản
        document.execCommand('italic');
    });

    $(document).on('click', '.atto_unorderedlist_button_insertUnorderedList', function(){
        // Đặt con trỏ vào editor trước khi áp dụng in đậm
        $('.editor_atto_content').focus();

        // Sử dụng execCommand để in đậm văn bản
        document.execCommand('insertUnorderedList');
    });

    $(document).on('click', '.atto_orderedlist_button_insertOrderedList', function(){
        // Đặt con trỏ vào editor trước khi áp dụng in đậm
        $('.editor_atto_content').focus();

        // Sử dụng execCommand để in đậm văn bản
        document.execCommand('insertOrderedList');
    });

    $(document).on('click', '.atto_indent_button_outdent', function(){
        // Đặt con trỏ vào editor trước khi áp dụng in đậm
        $('.editor_atto_content').focus();

        // Sử dụng execCommand để in đậm văn bản
        document.execCommand('outdent');
    });

    $(document).on('click', '.atto_indent_button_indent', function(){
        // Đặt con trỏ vào editor trước khi áp dụng in đậm
        $('.editor_atto_content').focus();

        // Sử dụng execCommand để in đậm văn bản
        document.execCommand('indent');
    });

    $(document).on('click', '.atto_emojipicker_button', function(event){
        event.stopPropagation();
        $('#emojiPopup').css('display', 'flex');
    });
    
    // Chèn emoji vào nội dung khi nhấn vào emoji trong hộp chọn
    $(document).on('click', '#emojiPicker .emoji', function(){
        const emoji = $(this).text();
        $('.editor_atto_content').focus();
        document.execCommand('insertText', false, emoji);
    });

    // Áp dụng màu khi người dùng chọn từ color picker
    $(document).on('input', '#fontColorPicker', function(event) {
        const selectedColor = $(this).val();
        console.log(selectedColor);
        document.execCommand('foreColor', false, selectedColor);
    });

    $(document).on('input', '#backColorPicker', function(event) {
        const selectedColor = $(this).val();
        console.log(selectedColor);
        document.execCommand('backColor', false, selectedColor); 
        // document.execCommand('foreColor', false, selectedColor);
    });

    $(document).on('click', '.atto_link_button_unlink', function(event) {
        document.execCommand('unlink', false, null); // Gỡ liên kết
    });

    $(document).on('click', '.atto_underline_button_underline', function(event) {
        document.execCommand('underline'); // Gỡ liên kết
    });

    $(document).on('click', '.atto_strike_button', function(event) {
        document.execCommand('strikeThrough'); // Gỡ liên kết
    });

    $(document).on('click', '.atto_subscript_button_subscript', function(event) {
        document.execCommand('subscript'); // Gỡ liên kết
    });

    $(document).on('click', '.atto_superscript_button_superscript', function(event) {
        document.execCommand('superscript'); // Gỡ liên kết
    });

    $(document).on('click', '.atto_align_button_justifyLeft', function(event) {
        document.execCommand('justifyLeft'); // Gỡ liên kết
    });

    $(document).on('click', '.atto_align_button_justifyCenter', function(event) {
        document.execCommand('justifyCenter'); // Gỡ liên kết
    });

    $(document).on('click', '.atto_align_button_justifyRight', function(event) {
        document.execCommand('justifyRight'); // Gỡ liên kết
    });

    $(document).on('click', '.atto_h5p_button', function(event) {
        $('#h5p-popup').css('display', 'flex');
        // document.execCommand('foreColor', false, selectedColor);
    });

    // $(document).on('click', '.atto_link_button', function(event) {
    //     // $('#link-popup').css('display', 'flex');
    //     const url = prompt("Nhập URL của liên kết:", "https://"); // Yêu cầu người dùng nhập URL
    //     if (url) {
    //         // Đặt con trỏ vào editor trước khi áp dụng liên kết
    //         $('.editor_atto_content').focus();
            
    //         // Tạo liên kết
    //         document.execCommand('createLink', false, url); 

    //         // Lấy phần tử liên kết vừa tạo
    //         const selection = window.getSelection();
    //         if (selection.rangeCount > 0) {
    //             const range = selection.getRangeAt(0);
    //             const link = range.startContainer.parentNode; // Giả định rằng liên kết vừa tạo nằm ngay trong phần tử cha
    //             console.log(range.startContainer.textContent, link);

    //             // Kiểm tra xem phần tử cha có phải là thẻ <a> không
    //             if (link.tagName === 'A') {
    //                 link.setAttribute('target', '_blank'); // Thêm target="_blank"
    //             }
    //         }
    //     }
    //     // document.execCommand('foreColor', false, selectedColor);
    // });

    let savedRange = null; // Biến toàn cục để lưu trữ phạm vi đã chọn

    $(document).on('click', '.atto_link_button', function(event) {
        // Hiện popup link
        $('#link-popup').css('display', 'flex');
    
        // Lấy phần tử đang được chọn và lưu vào biến toàn cục để sử dụng sau
        const selection = window.getSelection();
        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            savedRange = selection.getRangeAt(0); 
            // Lưu phần tử đã chọn
            const selectedText = range.toString(); 
            $('#textSelectedLink').val(selectedText); // Lưu văn bản đã chọn vào input ẩn
        }
    });

    $('.create-link').on('click', function() {
        const url = $('#_atto_link_urlentry').val(); // Lấy URL từ input
        const openInNewWindow = $('#_atto_link_openinnewwindow').is(':checked'); // Kiểm tra checkbox "Open in new window"
        if (url && savedRange) { // Kiểm tra nếu có URL và phạm vi đã lưu
            $('.editor_atto_content').focus();
            
            // Tạo thẻ <a> với URL
            const link = document.createElement('a');
            link.href = url; // Đặt thuộc tính href
            link.textContent = $('#textSelectedLink').val() || 'Link'; // Lấy văn bản từ input hoặc sử dụng 'Link' làm mặc định
            link.target = openInNewWindow ? '_blank' : '_self'; // Đặt target nếu cần

            // Kiểm tra xem có thể bao bọc nội dung đã chọn không
            if (savedRange.startContainer.parentNode) {
                // Bọc nội dung đã chọn trong thẻ <a>
                savedRange.surroundContents(link);

                // Đặt con trỏ vào sau liên kết vừa tạo
                savedRange.setStartAfter(link);
                savedRange.collapse(true);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(savedRange);

                // Đóng popup sau khi tạo liên kết
                closePopupLink();
                savedRange = null; // Đặt lại savedRange sau khi sử dụng
            }
        } else {
            alert("Vui lòng nhập URL hợp lệ.");
        }
    });


    let videoIndex = 1; // Khởi tạo chỉ số video

    $(document).on('click', '.btn-add-video', function(event) {
        $('.delete-container').css('display', '');

        const videoItem = `
            <div class="video-item">
                <div class="title-path-container">
                    <div class="title-container">
                        <div id="fitem_video_scoes[${videoIndex}][title]" class="form-group row fitem">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break" for="video_scoes[${videoIndex}][title]">Tiêu đề</label>
                                <div class="form-label-addon d-flex align-items-center align-self-start"></div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control" name="video_scoes[${videoIndex}][title]" id="video_scoes[${videoIndex}][title]" data-name="title">
                                <div class="form-control-feedback invalid-feedback" id="video_scoes[${videoIndex}][title]"></div>
                            </div>
                        </div>
                    </div>
                    <div class="path-container">
                        <div id="fitem_video_scoes[${videoIndex}][launch]" class="form-group row fitem">
                            <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                                <label class="d-inline word-break" for="video_scoes[${videoIndex}][launch]">Video</label>
                                <div class="form-label-addon d-flex align-items-center align-self-start"></div>
                            </div>
                            <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
                                <input type="text" class="form-control" name="video_scoes[${videoIndex}][launch]" id="video_scoes[${videoIndex}][launch]" data-name="launch">
                                <div class="form-control-feedback invalid-feedback" id="video_scoes[${videoIndex}][launch]"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="delete-container">
                    <a href="#" style="margin-right: 10px;" class="btn-delete-video delete-center" data-video-soces-id="">
                        <i class="fa fa-minus-circle" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="server-container">
                    <div id="fitem_video_scoes[${videoIndex}][server]" class="form-group row fitem">
                        <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0">
                            <label class="d-inline word-break" for="video_scoes[${videoIndex}][server]">Server</label>
                            <div class="form-label-addon d-flex align-items-center align-self-start"></div>
                        </div>
                        <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select">
                            <select class="custom-select" name="video_scoes[${videoIndex}][server]" id="video_scoes[${videoIndex}][server]" data-name="server">
                                <option value="0" selected>-- Server --</option>
                                <option value="1">Server 1</option>
                                <option value="2">Server 2</option>
                                <option value="3">Server 3</option>
                                <option value="4">Server 4</option>
                                <option value="5">Server 5</option>
                            </select>
                            <div class="form-control-feedback invalid-feedback" id="video_scoes[${videoIndex}][server]"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Thêm video-item vào video-list
        $('.video-list').append(videoItem);

        // Tăng chỉ số video cho phần tử tiếp theo
        videoIndex++;
    });

    $(document).on('click', '.btn-delete-video', function(e) {
        e.preventDefault();
        
        // Xóa video-item chứa delete được nhấp
        $(this).closest('.video-item').remove();
    
        // Kiểm tra số lượng video-item còn lại
        if ($('.video-item').length === 1) {
            // Ẩn delete-container nếu chỉ còn 1 video-item
            $('.video-item .delete-container').css('display', 'none');
        }
    });

});