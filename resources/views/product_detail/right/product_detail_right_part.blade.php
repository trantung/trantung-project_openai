<div class="right-part">
    <div style="text-align: right">
        <a class="wrapper_settings" id="setting_quiz_list_question" href="javascript:void(0);" style="display: none;">
            <i class="fa fa-list-ul" title="Xem danh mục" aria-hidden="true"></i>
        </a>
        <a class="wrapper_settings" id="setting_course_sections" href="javascript:void(0);">
            <i class="fa fa-cog" title="Cài đặt" aria-hidden="true" id="yui_3_17_2_1_1729820980771_44"></i>
        </a>
        <a class="wrapper_settings" id="setting_dynamic_form" href="javascript:void(0);" style="display: none;">
            <i class="fa fa-cog" title="Cài đặt" aria-hidden="true"></i>
        </a>
    </div>
    <div class="row-flex-center-side section-quiz-part">
        <div class="left-side-content mt-2">
            <!-- <div class="normal-item " style="align-items: flex-start;">
                <i class="mt-1 fa fa-list-ol" aria-hidden="true"></i>
                <div>Pre class - Ex1</div>
            </div>
            <div class="normal-item " style="align-items: flex-start;">
                <i class="mt-1 fa fa-list-ol" aria-hidden="true"></i>
                <div>Pre class - Ex2</div>
            </div>
            <div class="normal-item  dimmed " style="align-items: flex-start;">
                <i class="mt-1 fa fa-file" aria-hidden="true"></i>
                <div>
                    Lesson 1 - Key<br>
                    Không hiện hữu trừ khi:<br>
                    The activity <strong>Pre class - Ex1</strong> is marked complete<br>
                    The activity <strong>Pre class - Ex2</strong> is marked complete<br>
                    The activity <strong>Post class - Ex1</strong> is marked complete<br>
                    The activity <strong>Post class - Ex2</strong> is marked complete<br>
                    The activity <strong>Post class - Ex3</strong> is marked complete<br>
                </div>
            </div> -->
        </div>
        <div class="right-side-content mt-2 hide">
            @include('product_detail.right.form_folder')
            @include('product_detail.right.form_quiz')

            <input type="hidden" id="hidden_quiz" name="hidden_quiz" value="45960">
            <input type="hidden" id="hidden_parent_selected" name="hidden_parent_selected" value="10457">
            <input type="hidden" id="hidden_interactive_book" name="hidden_interactive_book" value="">
            <input type="hidden" id="hidden_interactive_book" name="hidden_interactive_book" value="">
            <input type="hidden" id="mode_change_interactive_book_parent" value="0">
            <input type="hidden" id="mode_change_course_section_parent" value="0">
        </div>
        <div class="right-side-content-list-question mt-2 hide">
            <form id="form-quiz-list-question"></form>
        </div>
    </div>
    <div class="wrapper-dynamic-form" id="wrapper-dynamic-resource-form"></div>
    <div class="wrapper-dynamic-form" id="wrapper-dynamic-video-form"></div>
    <div class="wrapper-dynamic-form" id="wrapper-dynamic-assign-form"></div>
    <div class="wrapper-dynamic-form" id="wrapper-dynamic-url-form"></div>
    <div class="wrapper-dynamic-form" id="wrapper-dynamic-scorm-form"></div>
    <div id="wrapper-general" class="wrapper-general">
        <div class="modl-prvw">
            <a class="prvw-bttn" href="javascript:void(0);" data-on="click:togglePreview" data-html="previewBtnText"><i class="fa fa-arrows-alt"></i></a>
            <figure class="prvw-frme" data-html="embedFrame"></figure>
        </div>
        <div id="wrapper-dynamic-general-form" class="form-wrap wrapper-dynamic-form"></div>
    </div>
    <div class="group-button-save text-center mt-4 mb-3">
        <input type="hidden" name="mode_save" id="id_mode_save" value="quiz">
        <input type="hidden" id="popup_hidden_ishvp" name="popup_hidden_ishvp" value="">
        <input type="hidden" id="popup_hidden_cmid" name="popup_hidden_cmid" value="">
        <input type="hidden" id="popup_hidden_quiz_id" name="popup_hidden_quiz_id" value="">
        <input type="hidden" id="popup_hidden_pathQuiz_quiz_id" name="popup_hidden_pathQuiz_quiz_id" value="https://english.ican.vn/classroom/local/omo_management/quiz/">
        <input type="hidden" id="page_type" value="product">
        <input type="hidden" id="h_quiz_section_add" value="">

        <button type="button" name="btn-save" class="btn-save btn btn-primary">Lưu</button>
        <button type="button" name="btn-save-version" class="btn-save-version btn btn-secondary">Lưu phiên bản</button>
    </div>


    {{-- @include('product_detail.right.form_video') --}}
    @include('product_detail.right.form_quiz')
</div>
