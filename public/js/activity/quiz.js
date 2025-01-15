$(document).ready(function() {

    // $('.expand-quiz').click(function(e) {
    // $('.above-block').on('click', '.tree-item .expand-quiz', function(e) {
    //     // switch dynamic form & manual form
    //     $('.wrapper-dynamic-form').html('');
    //     $('#setting_dynamic_form').hide();
    //     $('.section-quiz-part').show();
    //     $('#setting_course_sections').show();
    //     $('.tree-items .tree-item-cover > .tree-item').removeClass('active');
    //     $('#id_mode_save').val('quiz');
    //     $('#hidden_quiz').val($(this).data('quiz-id'));
    //     $('#hidden_parent_selected').val($(this).data('parent'));
    //     $('#mode_change_quiz_parent').val(0);
    //     $('input[name="parent_change"]').val(0);
    //     process_expand_quiz($(this), 1);
    // });

    function process_expand_quiz(_this) {
        _this.closest('.tree-item').addClass('active');
        // Load child data
        let quiz_id = _this.data('quiz-id');
        let parent = _this.data('parent');
        get_quiz_sections(quiz_id, parent);
    }

    function get_quiz_sections(quiz_id, parent) {
        // showLoading();
        console.log(456);
        var url = 'https://fake-json-api.mock.beeceptor.com/users';
        // var url = 'ajax/get_quiz_sections.php';
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            data: {
                'course_id': '171',
                'quiz_id': quiz_id,
                'parent': parent
            },
        }).done(function(response) {
            response = {
                "sub_quiz": [
                    {
                        "subquiz_id": "31113",
                        "subquiz_time_limit": "0",
                        "subquiz_cmid": "42501",
                        "quiz_section": [
                            {
                                "quiz_section_id": "10018",
                                "quiz_section_name": "Mục 1",
                                "quiz_section_max_mark_display": "1.00",
                                "quiz_section_total_mark": "1.00",
                                "question_default": {
                                    "id": "274024",
                                    "slot": "1",
                                    "quiz_slots_custom_id": "44197"
                                },
                                "question": [
                                    {
                                        "question_qtype_label": "Select missing words",
                                        "cmid": "42501",
                                        "question_id": "238507",
                                        "question_name": "Exercise 1",
                                        "qtype": "question",
                                        "question_qtype": "gapselect",
                                        "src_icon": "https://english.ican.vn/classroom/theme/image.php/mb2cg/qtype_gapselect/1706779039/icon",
                                        "slot": "2",
                                        "slot_display": 1,
                                        "quiz_slots_custom_id": "44198",
                                        "mark": "1.00",
                                        "htmlView": "<div id=\"question-6604287-1\" class=\"que gapselect deferredfeedback notyetanswered\"><div class=\"content\"><div class=\"formulation clearfix\"><h4 class=\"accesshide\">Đoạn văn câu hỏi</h4><input type=\"hidden\" name=\"q6604287:1_:sequencecheck\" value=\"1\" /><div class=\"qtext\"><p dir=\"ltr\" style=\"text-align: left;\"><span><strong>&nbsp;Exercise 1:&nbsp;<span id=\"docs-internal-guid-7a2720e6-7fff-8ede-79e7-523ad96066dd\" style=\"\">Watch a video about present simple - spelling and answer the MCQ.</span></strong></span></p><p dir=\"ltr\" style=\"text-align: left;\">&nbsp;&nbsp;<div class=\"mediaplugin mediaplugin_videojs d-block\"><div style=\"max-width:400px;\"><video controls=\"true\" data-setup-lazy=\"{&quot;language&quot;: &quot;vi&quot;, &quot;fluid&quot;: true}\" id=\"id_videojs_671b6eeb84bbb_2\" class=\"video-js\" title=\"Intro_U1L4_Preclass_Ex1.mp4\"><source src=\"https://english.ican.vn/classroom/pluginfile.php/60360/question/questiontext/6604287/1/238507/Intro_U1L4_Preclass_Ex1.mp4\" type=\"video/mp4\" /><a href=\"https://english.ican.vn/classroom/pluginfile.php/60360/question/questiontext/6604287/1/238507/Intro_U1L4_Preclass_Ex1.mp4\" class=\"_blanktarget\">https://english.ican.vn/classroom/pluginfile.php/60360/question/questiontext/6604287/1/238507/Intro_U1L4_Preclass_Ex1.mp4</a></video></div></div>&nbsp;&nbsp;</p><p dir=\"ltr\" style=\"text-align: left;\"><img src=\"https://english.ican.vn/classroom/pluginfile.php/60360/question/questiontext/6604287/1/238507/U1L4.PNG\" alt=\"\" width=\"887\" height=\"206\" role=\"presentation\" class=\"img-fluid atto_image_button_text-bottom\"></p><p dir=\"ltr\" style=\"text-align: left;\"><span><span><span style=\"\"><span style=\"\"><span id=\"docs-internal-guid-574490e6-7fff-6123-ca90-ef801de07614\" style=\"\"></span></span></span></span></span></p><p dir=\"ltr\" style=\"\"><span id=\"docs-internal-guid-cd6f30a3-7fff-36b8-dfb1-83eb6f219caf\"></span></p><p dir=\"ltr\" style=\"\"><span>1. How many rules are there in the video? <span class=\"control group1\"><select id=\"q6604287_1_p1\" class=\"select custom-select custom-select place1\" name=\"q6604287:1_p1\"><option value=\"\">&nbsp;</option><option value=\"1\">A</option><option value=\"2\">B</option><option value=\"3\">C</option></select> </span></span></p><div class=\"editor-indent\" style=\"margin-left: 30px;\"><p dir=\"ltr\" style=\"\"><span style=\"font-size: 1rem; font-weight: inherit;\">A. 3</span><br><span style=\"font-size: 1rem; font-weight: inherit;\">B. 4&nbsp;</span><br><span style=\"font-size: 1rem; font-weight: inherit;\">C. 5</span></p></div><p dir=\"ltr\" style=\"\"><span>2.&nbsp;</span><span>What is rule 1?&nbsp;&nbsp;<span class=\"control group1\"><select id=\"q6604287_1_p2\" class=\"select custom-select custom-select place2\" name=\"q6604287:1_p2\"><option value=\"\">&nbsp;</option><option value=\"1\">A</option><option value=\"2\">B</option><option value=\"3\">C</option></select> </span></span></p><div class=\"editor-indent\" style=\"margin-left: 30px;\"><p dir=\"ltr\"><span style=\"font-size: 1rem; font-weight: inherit;\">A. Most verbs, we add 's'</span><br><span style=\"font-size: 1rem; font-weight: inherit;\">B. Most verbs, we add 'es'</span><br><span style=\"font-size: 1rem; font-weight: inherit;\">C. Most verbs, we add 'ies'</span></p></div><p dir=\"ltr\"><span><span id=\"docs-internal-guid-1f00d98a-7fff-8fe4-a17c-ef555d45c4a4\"></span></span></p><p dir=\"ltr\"><span id=\"docs-internal-guid-3595bc41-7fff-4924-e2d2-2e11cee19f48\"></span></p><p dir=\"ltr\">3. In rule 2, verbs ending with a consonant 'y', we…..?&nbsp;&nbsp;<span class=\"control group1\"><select id=\"q6604287_1_p3\" class=\"select custom-select custom-select place3\" name=\"q6604287:1_p3\"><option value=\"\">&nbsp;</option><option value=\"1\">A</option><option value=\"2\">B</option><option value=\"3\">C</option></select> </span><div class=\"editor-indent\" style=\"margin-left: 30px;\"><p dir=\"ltr\"><span style=\"font-size: 0.9375rem; font-weight: inherit;\">A. erase 'y', and add 'es'.</span><br><span style=\"font-size: 0.9375rem; font-weight: inherit;\">B. erase 'y', and add 'ies'.</span><br><span style=\"font-size: 0.9375rem; font-weight: inherit;\">C. erase 'y', and add 's'.</span></p></div><p dir=\"ltr\">4. Verbs ending with 's, z, ch, sh, x', we…..?&nbsp; <span class=\"control group1\"><select id=\"q6604287_1_p4\" class=\"select custom-select custom-select place4\" name=\"q6604287:1_p4\"><option value=\"\">&nbsp;</option><option value=\"1\">A</option><option value=\"2\">B</option><option value=\"3\">C</option></select> </span><div class=\"editor-indent\" style=\"margin-left: 30px;\"><span style=\"font-size: 0.9375rem; font-weight: inherit;\">A. add 's'</span><br><span style=\"font-size: 0.9375rem; font-weight: inherit;\">B. add 'ies'</span><br><span style=\"font-size: 0.9375rem; font-weight: inherit;\">C. add 'es'</span><br></div><p dir=\"ltr\"><span><span style=\"font-size: 0.9375rem;\">5. Verbs ending with 'o', we…..?&nbsp;&nbsp;<span class=\"control group1\"><select id=\"q6604287_1_p5\" class=\"select custom-select custom-select place5\" name=\"q6604287:1_p5\"><option value=\"\">&nbsp;</option><option value=\"1\">A</option><option value=\"2\">B</option><option value=\"3\">C</option></select> </span></span></span></p><div class=\"editor-indent\" style=\"margin-left: 30px;\"><p dir=\"ltr\"><span style=\"font-size: 0.9375rem; font-weight: inherit;\">A. add 's'</span><br><span style=\"font-size: 0.9375rem; font-weight: inherit;\">B. add 'es'</span><br><span style=\"font-size: 0.9375rem; font-weight: inherit;\">C. add 'ies'</span></p></div></div></div></div></div>",
                                        "isHvp": false,
                                        "hvp_id": null,
                                        "hvp_machine_name": null,
                                        "tag": [
                                            "IELTS-Introduction",
                                            "Exercise",
                                            "MCQ",
                                            "Writing",
                                            "Dailyroutine"
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ],
                "course": 171,
                "section": 10457,
                "is_edit": 0,
                "arr_count_question": {
                    "10018": {
                        "id": "10018",
                        "name": "Mục 1",
                        "count_question": "1"
                    }
                }
            }
            console.log(response)
            if(response.sub_quiz){
                render_html_quiz_section(quiz_id, response, response.is_edit);
            }

            // // Load list question
            load_list_question();
        });

        // Show button list question
        $('#setting_quiz_list_question').show();

        // // Get infomation activity by course
        get_setting_module(quiz_id, parent);
        $('#id_mode_save').val('quiz');
        // hideLoading();
    }

    $(document).on('click', '#setting_quiz_list_question', function (e) {
        // hide setting
        if(!$('.right-side-content').hasClass("hide")){
            $('.right-side-content').addClass("hide");
        }
        if($('#setting_course_sections').hasClass("color-active")){
            $('#setting_course_sections').removeClass('color-active');
        }

        if ($('#form-quiz-list-question').is(":hidden")) {
            // load list question
            load_list_question();

            // show right-side-content
            if($('.right-side-content-list-question').hasClass("hide")){
                $('.right-side-content-list-question').removeClass("hide");
                $('#form-quiz-list-question').removeClass("hide");
            }
            $(this).addClass('color-active');
        } else {
            // hide right-side-content
            $('#form-quiz-list-question').addClass("hide");
            $('.right-side-content-list-question').addClass("hide");
            $(this).removeClass('color-active');
        }

        $('#id_mode_save').val('quiz');
    });

    $(document).on('click', '#setting_course_sections', function (e) {
        // Default clear all notice message
        // clearNoticeMessage();

        // hide list question
        if(!$('.right-side-content-list-question').hasClass("hide")){
            $('.right-side-content-list-question').addClass("hide");
        }
        if($('#setting_quiz_list_question').hasClass("color-active")){
            $('#setting_quiz_list_question').removeClass('color-active');
        }

        if ($('.tree-items > .tree-item-cover').length == 0) {
            // Display notification message
            Notification.addNotification({
                message: "Hãy tạo mới một thư mục",
                type: "error"
            });

            // Scroll top page
            // scrollTopPage();

            // auto close notify message
            // autoCloseNotifyMessage();
        } else {
            if($('.right-side-content').hasClass("hide")){
                $('.right-side-content').removeClass("hide");
                $(this).addClass('color-active');
            }else{
                $('.right-side-content').addClass("hide");
                $(this).removeClass('color-active');
            }
        }

        //$('#id_mode_save').val('course_sections');
    });

    function load_list_question() {
        // list quiz section
        var list_question = '<div class="content-list-question">';
        list_question += '<h4 class="text-center">' + $('#form-quiz-setting #quiz_name').val() + '</h4>';
        list_question += '<div><b>Danh mục câu hỏi</b></div>';
        $('#quiz_detail_form .quiz-section .btn-delete-quiz-section').each(function(idx, quiz_section) {
            var quiz_section_id = $(quiz_section).data('quiz-section-id');
            var quiz_section_name = $('#quiz_section_name_display_' + quiz_section_id).html();
            list_question += '<hr>';
            list_question += '<div><b>' + quiz_section_name + '</b></div>';
            list_question += '<div class="questions-square row-flex-align-left-side">';

            // list question
            $('#quiz_section_' + quiz_section_id + ' .question-item').each(function(idx, question) {
                var id = $(question).data('id');
                var slot_display = $(question).data('slot_display');
                list_question += '<a href="javascript:void(0);"><div data-question-id="' + id + '" class="question-square">' + slot_display + '</div></a>';
            });

            list_question += '</div>';
        });
        list_question += '</div>';
        $('#form-quiz-list-question').html(list_question);
    }

    function render_html_quiz_section(quiz_parent, response, is_edit = 1) {
        var sub_quiz = response.sub_quiz;
        var course = response.course;
        var section = response.section;
        var arr_count_question = response.arr_count_question;
        let html = '';
        html += '<div class="quiz-add">';
        html += '<div class="text-right mb-3"><a href="javascript:void(0);" class="btn-act-view"><i class="fa fa-arrows-alt fa-2x" title="Xem nội dung" aria-hidden="true"></i></a> </div>';
        html += (!is_edit) ? '' : '<div class="text-right mb-3"><a href="javascript:void(0);" class="btn btn-secondary btn-add-subquiz"><i class="fa fa-plus fa-1x" aria-hidden="true"></i><b>  Timer</b></a> </div>';
        html += '<form id="quiz_detail_form" method="post">';
        html += ' <div class="part-body row">';
        html += '<div class="quiz-container main-site col-12">';
        sub_quiz.forEach((sub_quiz_val) => {
            let sub_quiz_id = sub_quiz_val['subquiz_id'];
            html += '<div class="sub-quiz sub-quiz-'+sub_quiz_id+'">';
            html += '<div class="sub-quiz-header row-flex-align-both-side">';
            html += '<div class="left-actions">';
            html += (!is_edit) ? '' : '<div class="quiz-action btn-moving"><i class="fa fa-arrows" aria-hidden="true"></i></div>';
            html += '<div class="quiz-action">';
            html += '<a class="show-subquiz-content show-subquiz-content-'+sub_quiz_id+' display-none" data-subquiz-id="'+sub_quiz_id+'" href="javascript:void(0);"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>';
            html += '<a class="hide-subquiz-content hide-subquiz-content-'+sub_quiz_id+'" data-subquiz-id="'+sub_quiz_id+'" href="javascript:void(0);"><i class="fa fa-chevron-down icon-chevron-sub-quiz" aria-hidden="true"></i></a>';
            html += '</div>';
            html += '<div>';
            html += 'Thời gian: <input class="input-time" type="number" name="sub_quiz['+sub_quiz_id+'][timelimit]" value="'+sub_quiz_val['subquiz_time_limit']+'" style="width: 70px;"/> phút';
            html += '</div>';
            html += '</div>';
            html += '<div class="right-actions row" style="margin-right: 5px">';
            html += (!is_edit) ? '' : '<div class="quiz-action col-6" style="padding-right: 12px;"><a href="javascript:void(0);" class="btn-add-quiz-section" data-subquiz-id="'+sub_quiz_id+'"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
            html += (!is_edit) ? '' : '<div class="quiz-action col-6 action-delete-quiz" data-subquiz-id="'+sub_quiz_id+'" data-cmid="'+sub_quiz_val['subquiz_cmid']+'"><a href="javascript:void(0);" class="btn-delete-sub-quiz" data-subquiz-id="'+sub_quiz_id+'" data-cmid="'+sub_quiz_val['subquiz_cmid']+'"><i class="fa fa-trash" title="Xoá" aria-hidden="true"></i></a></div>';
            html += '</div>';
            html += '</div>';

            // subquiz
            html += '<div class="sub-quiz-body">';
            html += '<div class="quiz-sections quiz-sections-'+sub_quiz_id+'" style="min-height: 62px" data-subquiz-id="'+sub_quiz_id+'">';
            let quiz_sections = sub_quiz_val['quiz_section'];
            quiz_sections.forEach((quiz_section_val) => {
                let quiz_section_id = quiz_section_val['quiz_section_id'];
                html += '<div data-subquiz-id-old="'+sub_quiz_id+'" class="quiz-section quiz-section-'+quiz_section_id+'">';
                html += '<div class="quiz-section-header row-flex-align-both-side">';

                html += '<div class="left-actions">';
                html += (!is_edit) ? '' : '<div class="quiz-action btn-moving"><i class="fa fa-arrows" aria-hidden="true"></i></div>';
                html += '<div class="quiz-action">';
                html += '<a class="show-section-content show-section-content-'+quiz_section_id+' display-none" href="javascript:void(0);" data-quiz-section-id="'+quiz_section_id+'"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>';
                html += '<a class="hide-section-content hide-section-content-'+quiz_section_id+'" href="javascript:void(0);" data-quiz-section-id="'+quiz_section_id+'"><i class="fa fa-chevron-down icon-chevron-section" aria-hidden="true"></i></a>';
                html += '</div>';
                html += '<div>';
                html += '<span id="quiz_section_name_display_'+quiz_section_id+'">'+quiz_section_val['quiz_section_name']+'   </span>';
                html += '<input type="text" class="display-none section-name-'+quiz_section_id+'" value="'+quiz_section_val['quiz_section_name']+'" />';
                html += '<input type="hidden" class="input-name send-section-name-'+quiz_section_id+'" name="sub_quiz['+sub_quiz_id+'][section]['+quiz_section_id+'][name]" value="'+quiz_section_val['quiz_section_name']+'" />';
                html += '<a href="javascript:void(0);" data-quiz-section-id="'+quiz_section_id+'" class="edit-quiz-section-name ml-2 edit-quiz-section-name-'+quiz_section_id+'"><i class="fa fa-edit edit2" title="Chỉnh sửa tên" aria-hidden="true"></i></a>';
                html += '<a href="javascript:void(0);" data-quiz-section-id="'+quiz_section_id+'" class="display-none save-edit-quiz-section-name save-edit-quiz-section-name-'+quiz_section_id+'"><i class="fa-solid fa-floppy-disk"></i></a>';
                html += '<a href="javascript:void(0);" data-quiz-section-id="'+quiz_section_id+'" class="display-none cancel-edit-quiz-section-name cancel-edit-quiz-section-name-'+quiz_section_id+'"><i class="fa fa-times" aria-hidden="true"></i></a>';
                html += '</div>';
                html += '</div>';

                html += '<div class="right-actions row-flex-align-both-side">';
                html += '<div style="margin: 0 8px;">';
                if (is_edit) {
                    html += 'Hệ số: <input type="number" class="sub_quiz_time mr-3" name="sub_quiz['+sub_quiz_id+'][section]['+quiz_section_id+'][max_mark_display]" value="'+quiz_section_val['quiz_section_max_mark_display']+'" style="width: 80px;"/>';
                } else {
                    html += 'Hệ số: <span class="mr-3">'+quiz_section_val['quiz_section_max_mark_display']+'</span><input type="hidden" class="sub_quiz_time mr-3" name="sub_quiz['+sub_quiz_id+'][section]['+quiz_section_id+'][max_mark_display]" value="'+quiz_section_val['quiz_section_max_mark_display']+'" style="width: 80px;"/>';
                }
                html += 'Điểm: <span class="total-mark-section">'+quiz_section_val['quiz_section_total_mark']+'</span>';
                html += (!is_edit) ? '' : '<a href="javascript:void(0);" class="btn-add-question ml-3" data-quiz-section-id="'+quiz_section_id+'" data-section="129" data-subquiz-id="'+sub_quiz_id+'"><i class="fa fa-plus" title="Thêm câu hỏi" aria-hidden="true"></i></a>';
                html += '</div>';
                html += (!is_edit) ? '' : '<div class="quiz-action action-delete-quiz-section" data-quiz-section-id="'+quiz_section_id+'"><a href="javascript:void(0);" class="btn-delete-quiz-section" data-quiz-section-id="'+quiz_section_id+'" data-subquiz-id="'+sub_quiz_id+'"><i class="fa fa-trash" title="Xoá" aria-hidden="true"></i></a></div>';
                html += '</div>';
                html += '</div>';
                if(arr_count_question[quiz_section_id].count_question == 0){
                    html += '<div style="color: red; text-align: left; margin-left: 40px;" class="warning-quiz-section-empty warning-quiz-section-empty-'+quiz_section_id+'">Chưa có câu hỏi trong mục này</div>';
                }
                html += '<div class="quiz-section-body" style="min-height: 20px; padding-bottom: 10px;"  id="quiz_section_'+quiz_section_id+'" data-quiz-section-id="'+quiz_section_id+'">';

                //question
                if (quiz_section_val['question']){
                    let questions = quiz_section_val['question'];
                    questions.forEach((question_val) => {
                        let question_id = question_val.question_id;
                        let slot = question_val.slot;
                        let quiz_slots_custom_id = question_val.quiz_slots_custom_id;
                        let slot_display = question_val.slot_display;
                        let cmid = question_val.cmid;
                        html += '<div class="question-item row-flex-align-both-side" data-id="'+question_id+'" data-slot_display="'+slot_display+'">';
                        html += '<div class="mode-edit left-actions" style="display: none;"> ';
                        html += (!is_edit) ? '' : '<div class="quiz-action btn-moving"><i class="fa fa-arrows" aria-hidden="true"></i></div>';
                        html += '<div style="margin-left: 8px;">'+question_val.slot_display+'</div>';
                        html += '<div style="margin: 0px 16px;">';
                        html += '<i title="question icon" aria-hidden="true">';
                        html += '<img class="icon icon" title="' + question_val.question_qtype_label + '" alt="' + question_val.question_qtype_label + '" src="'+question_val.src_icon+'" id=""> </i>';
                        html += '</div>';
                        html += '<div style="text-align: left">';
                        html += '<div class="question-name question-name-'+question_id+'">'+question_val.question_name+'</div>';
                        html += '<div class="question-tag question-tag-'+question_id+'">Tag: ';
                        if (question_val.tag) {
                            let tags = question_val.tag;
                            tags.forEach((tag) => {
                                html += '<span style="border-radius: 10px" class="badge badge-pill badge-secondary mr-2">'+tag+'</span>';
                            })
                        }
                        html += '</div></div>';
                        html += '</div>';
                        html += '<div class="mode-edit right-actions row-flex-align-both-side" style="display: none;">';
                        if (is_edit) {
                            html += '<div style="margin: 0 8px;"> Điểm ';
                            html += '<input class="i-grade-qs mark-qs-'+question_id+'" min="0" data-section-id="'+quiz_section_id+'" data-slots-custom-id="'+quiz_slots_custom_id+'" data-question-id="'+question_id+'" type="number" name="markQuestion['+sub_quiz_id+']['+question_id+']" value="'+question_val.mark+'" style="width: 70px;"/>';
                            html += '</div>';
                        } else {
                            html += '<div style="margin: 0 8px;"> Điểm: <span>'+question_val.mark+'</span>';
                            html += '<input class="i-grade-qs mark-qs-'+question_id+'" min="0" data-section-id="'+quiz_section_id+'" data-slots-custom-id="'+quiz_slots_custom_id+'" data-question-id="'+question_id+'" type="hidden" name="markQuestion['+sub_quiz_id+']['+question_id+']" value="'+question_val.mark+'" style="width: 70px;"/>';
                            html += '</div>';
                        }
                        html += '<div class="quiz-action">';

                        if (!question_val.isHvp){
                            html += '<a class="btn-edit-question" data-ishvp="0" data-quiz="'+quiz_parent+'" data-id="'+question_id+'" data-cmid="'+cmid+'" data-url="'+'https://english.ican.vn/classroom'+'/local/omo_management/quiz/upsert_question_bank.php?course='+course+'&section='+section+'&id='+question_id+'&quiz_id='+sub_quiz_id+'&qtype='+question_val.question_qtype+'&quiz_section_custom_id='+quiz_section_id+'&quiz_slots_custom_id='+quiz_slots_custom_id+'&edit-view=1&is_course_detail=1" >';
                            html += '<i class="fa fa-edit edit3" aria-hidden="true"></i>';
                            html += '</a>';
                        }else {
                            html += '<a class="btn-edit-question" data-ishvp="1" data-quiz="'+quiz_parent+'" data-id="'+question_id+'" data-cmid="'+cmid+'" data-url="'+'https://english.ican.vn/classroom'+'/local/omo_management/quiz/upsert_hvp_bank.php?id='+question_val.hvp_id+'&course='+course+'&section='+section+'&id='+question_id+'&quiz_id='+sub_quiz_id+'&machine_name='+question_val.hvp_machine_name+'&quiz_section_custom_id='+quiz_section_id+'&quiz_slots_custom_id='+question_val.quiz_slots_custom_id+'&edit-view=1&is_course_detail=1" >';
                            html += '<i class="fa fa-edit edit3" aria-hidden="true"></i>';
                            html += '</a>';
                        }
                        html += '</div>';

                        let isHvp = question_val.isHvp ? "1" : "0";
                        html += (!is_edit) ? '' : '<div class="quiz-action"><a class="btn-del-qs" data-ishvp="'+isHvp+'" data-quiz_slots_custom_id="'+question_val.quiz_slots_custom_id+'" data-cmid="'+cmid+'" data-sub-quiz-id ="'+sub_quiz_id+'" data-question-id ="'+question_id+'" href="javascript:void(0);"><i class="fa fa-trash" title="Xoá" aria-hidden="true"></i></a></div>';
                        html += '</div>';

                        // hidden
                        html += '<input type="hidden" name="question['+question_val.slot+'][instance_id]" value="'+question_id+'" />';
                        html += '<input type="hidden" name="question['+slot+'][slot]" value="'+slot+'" />';
                        html += '<input type="hidden" class="h_sub_quiz_id" name="question['+slot+'][sub_quiz_id]" value="'+sub_quiz_id+'" />';
                        html += '<input type="hidden" class="h_quiz_section_custom_id" name="question['+slot+'][quiz_section_custom_id]" value="'+quiz_section_id+'" />';
                        html += '<input type="hidden" class="h_quiz_slots_custom_id" name="question['+slot+'][quiz_slots_custom_id]" value="'+quiz_slots_custom_id+'" />';
                        html += '<input type="hidden" name="question['+slot+'][type]" value="'+question_val.qtype+'" />';
                        html += '<input type="hidden" name="question['+slot+'][maxmark]" value="'+question_val.mark+'" />';

                        if (question_val.question_qtype == 'ddimageortext') {
                            html += '<input type="hidden" class="ddimageortext-view" id="ddimageortext_view_'+question_id+'" data-places='+JSON.stringify(question_val.id_places)+' data-view= "'+question_val.id_view+'" />';
                        }

                        // question view
                        html += '<div class="question-item-view w-100 mode-view row-flex-align-both-side mt-3 mb-3">';
                        html += '<div class="text-white qs-view-header text-left mt-3 ml-3">';
                        html += '<div class="slot-view-'+question_id+'">Câu hỏi '+slot_display+'</div>';
                        html += '<div> Điểm : <span class="grade-view-'+question_id+'">'+question_val.mark+'</span> </div>';
                        html += '</div>';
                        html += '<div class="quiz-action quiz-action-view" style="margin-top: 40px">';

                        if (!question_val.isHvp){
                            html += '<a class="btn-edit-question" data-ishvp="0" data-quiz="'+quiz_parent+'" data-id="'+question_id+'" data-cmid="'+cmid+'" data-url="'+'https://english.ican.vn/classroom'+'/local/omo_management/quiz/upsert_question_bank.php?edit-view=1&course='+course+'&section='+section+'&id='+question_id+'&quiz_id='+sub_quiz_id+'&qtype='+question_val.question_qtype+'&quiz_section_custom_id='+quiz_section_id+'&quiz_slots_custom_id='+quiz_slots_custom_id+'&edit-view=1&is_course_detail=1" href="javascript:void(0)" >';
                            html += '<i class="fa fa-edit edit3" aria-hidden="true"></i> </a>';
                        }else {
                            html += '<a class="btn-edit-question" data-ishvp="1" data-quiz="'+quiz_parent+'" data-id="'+question_id+'" data-cmid="'+cmid+'" data-url="'+'https://english.ican.vn/classroom'+'/local/omo_management/quiz/upsert_hvp_bank.php?edit-view=1&course='+course+'&section='+section+'&id='+question_id+'&quiz_id='+sub_quiz_id+'&machine_name='+question_val.hvp_machine_name+'&quiz_section_custom_id='+quiz_section_id+'&quiz_slots_custom_id='+quiz_slots_custom_id+'&edit-view=1&is_course_detail=1" href="javascript:void(0)" >';
                            html += '<i class="fa fa-edit edit3" aria-hidden="true"></i> </a>';
                        }

                        html += '<a class="btn-del-qs" data-ishvp="'+isHvp+'" data-quiz_slots_custom_id="'+quiz_slots_custom_id+'" data-cmid="'+cmid+'" data-sub-quiz-id ="'+sub_quiz_id+'" data-question-id ="'+question_id+'" href="javascript:void(0);"><i class="fa fa-trash" title="Xoá" aria-hidden="true"></i></a>';
                        html += '</div>';
                        html += '<div class="ml-3 mr-3 w-100 qs-view-content text-left" style="width: 70% !important;">';
                        html += '<div class="panel panel-default">';
                        html += '<div class="panel-body body-view-html-'+question_id+'" id="id-body-view-html-'+question_id+'">';
                        html += question_val.htmlView;
                        html += '</div> </div> </div> </div> </div>';
                    })
                }
                if (quiz_section_val['question_default']){
                    let question_default_val = quiz_section_val['question_default'];
                    let slot = question_default_val.slot;
                    html += '<input type="hidden" name="question_default_hidden['+slot+'][subquiz_id]" value="'+sub_quiz_id+'" />';
                    html += '<input type="hidden" name="question_default_hidden['+slot+'][id_hidden]" value="'+question_default_val.id+'" />';
                    html += '<input type="hidden" name="question_default_hidden['+slot+'][quiz_section_id]" value="'+quiz_section_id+'" />';
                    html += '<input type="hidden" name="question_default_hidden['+slot+'][quiz_slots_custom_id]" value="'+question_default_val.quiz_slots_custom_id+'" />';
                }
                html += '</div> </div>';
            })


            html += '</div>';
            html += '<input type="hidden" name="sub_quiz['+sub_quiz_id+'][id]" value="'+sub_quiz_id+'" />';
            html += '</div>';
            html += '</div>';

        })

        html += '</div>';
        html += '</div>';

        html += '<input type="hidden" id="change_slot" name="change_slot" value="0" />';
        html += '<input type="hidden" id="cm_sub_quiz_delete" name="cm_sub_quiz_delete" value="" />';
        html += '<input type="hidden" id="sub_quiz_delete" name="sub_quiz_delete" value="" />';
        html += '<input type="hidden" id="change_mark" name="change_mark" value="" />';
        html += '<input type="hidden" id="h_qs_id_edit" name="h_qs_id_edit" value="" />';
        html += '<input type="hidden" id="popup_hidden_ishvp" name="popup_hidden_ishvp" value="" />';
        html += '<input type="hidden" id="popup_hidden_cmid" name="popup_hidden_cmid" value="" />';
        html += '<input type="hidden" id="delete_hvp" name="delete_hvp" value="" />';

        html += '</form>';
        html += '</div>';

        // $('.right-part').attr('style', 'width: 80%;');
        $('.right-part .left-side-content').html(html);

        $('.ddimageortext-view').each(function(index, element) {
            let view = $(this).data('view');
            let places = $(this).data('places');
            require(['qtype_ddimageortext/question_custom'], function(dragdropimage) {
                dragdropimage.init(view, 1 , places);
            });
        });

        // initialize the drag and drop
        if (is_edit) {
            initSortableQuiz();
            initSortableSubQuiz();
            initSortableQuestion();
        }

        setTimeout(function() {
            $('.quiz-container').find('.mode-edit').attr('style', '');
            $('.quiz-container').find('.mode-view').attr('style', 'display: none;');
        }, 500);

    }

    function initSortableQuiz() {
        $(".quiz-container").sortable({
            opacity: 0.7,
            cursor: "move",
            update: function( event, ui ) {
                $("#change_slot").val(1);
            }
        });

        $(".quiz-container").disableSelection();
    }

    function checkQuizSectionEmpty(quiz_section_id) {
        $('.warning-quiz-section-empty-'+quiz_section_id).remove();
        if($('#quiz_section_'+quiz_section_id).find('div.question-item').length == 0){
            let html_warning = '<div style="color: red; text-align: left; margin-left: 40px;" class="warning-quiz-section-empty warning-quiz-section-empty-'+quiz_section_id+'">Chưa có câu hỏi trong mục này</div>';
            $('.quiz-section-'+quiz_section_id+' .quiz-section-header').after(html_warning);
        }
    }

    function initSortableSubQuiz() {
        $(".quiz-sections").sortable({
            connectWith: ".quiz-sections",
            opacity: 0.7,
            cursor: "move",
            update: function( event, ui ) {
                let target = event.target;
                let subquizId = $(target).data('subquiz-id');
                if (ui.item) {
                    let item = ui.item[0];
                    let subquizIdOld =  $(item).data('subquiz-id-old');
                    let nameReplacfe = 'sub_quiz['+subquizId+']';

                    $(item).attr('data-subquiz-id-old', subquizId);

                    // update name
                    let nameNew = $(item).find('.input-name').attr('name').replace('sub_quiz['+subquizIdOld+']', nameReplacfe);
                    $(item).find('.input-name').attr('name', nameNew);

                    // update btn delete
                    $(item).find('.btn-delete-quiz-section').attr('data-subquiz-id', subquizId);

                    //update input time
                    let nameNewTime = $(item).find('.sub_quiz_time').attr('name').replace('sub_quiz['+subquizIdOld+']', nameReplacfe);
                    $(item).find('.sub_quiz_time').attr('name', nameNewTime);

                    // update input hidden
                    $(item).find('.h_sub_quiz_id').val(subquizId);
                    $("#change_slot").val(1);
                }
            }
        });

        $(".quiz-sections").disableSelection();
    }

    function initSortableQuestion() {
        $(".quiz-section-body").sortable({
            connectWith: ".quiz-section-body",
            opacity: 0.7,
            cursor: "move",
            update: function( event, ui ) {
                let target = event.target;
                let quizSectionId = $(target).data('quiz-section-id');
                let subquizId = $(target).parents('.quiz-sections').data('subquiz-id');

                if (ui.item) {
                    let item = ui.item[0];
                    $(item).find('.h_quiz_section_custom_id').val(quizSectionId);
                    $(item).find('.h_sub_quiz_id').val(subquizId);
                    $("#change_slot").val(1);
                    $(item).find('.i-grade-qs').attr('data-section-id', quizSectionId);

                    let nameNew = 'markQuestion['+subquizId+']['+$(item).find('.i-grade-qs').data('question-id') +']';
                    $(item).find('.i-grade-qs').attr('name', nameNew);
                }
                checkQuizSectionEmpty(quizSectionId);
            },
            stop: function( event, ui ) {
                let target = event.target;
                let quizSectionId = $(target).data('quiz-section-id');
                let mark = $(target).find('.i-grade-qs').val();

                if (ui.item) {
                    let item = ui.item[0];
                    let quizSectionIdNew = $(item).find('.i-grade-qs').attr('data-section-id');
                    let mark = $(item).find('.i-grade-qs').val();
                    updateGradeQuestion(quizSectionIdNew, mark, 1);
                    updateGradeQuestion(quizSectionId, mark , 0);
                }

            },
        });

        $(".quiz-section-body").disableSelection();
    }

    function updateGradeQuestion(sectionId , mark = 0, type = 1){
        let elm = $('.quiz-section-'+sectionId);
        let markValsSection = $('.i-grade-qs').map(function() {
            if ($(this).data('section-id') == sectionId) {
                return this.value;
            }
        }).get();

        let sumMark = markValsSection.reduce((pv,cv)=>{
            return pv + (parseFloat(cv) || 0);
        },0);
        let sumFormat = sumMark;
        let total = elm.find('.total-mark-section').html();
        if (mark) {
            if (type) {
                sumFormat = parseFloat(mark) + parseFloat(total);
            }else if(total > 0){
                sumFormat = parseFloat(total) - parseFloat(mark);
            }
        }
        elm.find('.total-mark-section').html(sumFormat.toFixed(2));
    }

    function get_setting_module(instance, parent = 0, type = 'quiz'){
        var url = 'https://fake-json-api.mock.beeceptor.com/users';
        // var url = ''ajax/get_setting_'+type+'.php'';
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                'instance': instance,
                'course_id': '171',
                'type': type
            },
        }).done(function(response) {
            response = {
                "html": "<div class=\"quiz-settings\"><h4 class=\"text-center\">Pre class - Ex1</h4><div class=\"row\"> <div class=\"col-12\"> <h5><b>Thông tin chung</b></h5></div> </div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Tên bài quiz</h4></div><div class=\"col-7\"><input class=\"w-100\" type=\"text\" id=\"quiz_name\" name=\"quiz_name\" value=\"Pre class - Ex1\"/></div></div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Thư mục cha</h4></div><div class=\"col-7 quiz-parent-box\"><select class=\"w-100\" name=\"quiz-parent\" id=\"quiz-parent\"><option value='10456'>Session 1 - Unit 1 - Lesson 1: Daily life</option><option value='10457'>Session 1 - Unit 1 - Lesson 4: Daily life</option><option value='10458'>Talk To Me: Personal information</option><option value='10459'>Session 2 - Unit 1 - Lesson 2: Daily life</option><option value='10460'>Session 2 - Unit 1 - Lesson 3: Daily life</option><option value='10461'>Talk To Me: Daily activity </option><option value='10462'>Session 3 - Unit 2 - Lesson 5 - House and home</option><option value='10463'>Session 3 - Unit 2 - Lesson 8 - House and home</option><option value='10464'>Talk To Me: House and home</option><option value='10465'>Session 4 - Unit 2 - Lesson 6 - House and home</option><option value='10466'>Session 4 - Unit 2 - Lesson 7 - House and home</option><option value='10467'>Talk To Me: Clothes </option><option value='10468'>Session 5 - Review Unit 1+2 - Lesson 9.1</option><option value='10469'>Session 6 - Review Unit 1+2 - Lesson 9.2</option><option value='10470'>MOCK TEST 1</option><option value='10471'>Talk To Me: Review 1</option><option value='10472'>Session 7 - Unit 3 - Lesson 10 - Hobbies, leisures and entertainment</option><option value='10473'>Session 7 - Unit 3 - Lesson 13 - Hobbies, leisures and entertainment</option><option value='10474'>Talk To Me: Hobbies, leisures and entertainment</option><option value='10475'>Session 8 - Unit 3 - Lesson 11 - Hobbies, leisures and entertainment</option><option value='10476'>Session 8 - Unit 3 - Lesson 12 - Hobbies, leisures and entertainment</option><option value='10477'>Talk To Me: School/ Work</option><option value='10478'>Session 9 - Unit 4 - Lesson 14 - Food</option><option value='10479'>Session 9 - Unit 4 - Lesson 17 - Food</option><option value='10480'>Talk To Me: Food and drink </option><option value='10481'>Session 10 - Unit 4 - Lesson 16 - Food</option><option value='10482'>Session 10 - Unit 4 - Lesson 15 - Food</option><option value='10483'>Talk To Me: Different places to go on holiday</option><option value='10484'>Session 11 - Review Unit 3 + 4 - Lesson 18</option><option value='10485'>Session 12 - Review Unit 3 + 4 - Lesson 18.2</option><option value='10486'>MOCK TEST 2</option><option value='10487'>Talk To Me: Review 2</option><option value='10488'>Session 13 - Unit 5 - Lesson 19 - Transport and places in town</option><option value='10489'>Session 13 - Unit 5 - Lesson 22 - Transport and places in town</option><option value='10490'>Talk To Me: Transport</option><option value='10491'>Session 14 - Unit 5 - Lesson 20 - Transport and places in town</option><option value='10492'>Session 14 - Unit 5 - Lesson 21 - Transport and places in town</option><option value='10493'>Talk To Me: Sports </option><option value='10494'>Session 15 - Unit 6 - Lesson 23 - Health and medicine</option><option value='10495'>Session 15 - Unit 6 - Lesson 26 - Health and medicine</option><option value='10496'>Talk To Me: Internet </option><option value='10497'>Session 16 - Unit 6 - Lesson 24 - Health and medicine</option><option value='10498'>Session 16 - Unit 6 - Lesson 25 - Health and medicine</option><option value='10499'>Talk To Me: Past acitvities</option><option value='10500'>Session 17 - Review Unit 5 + 6 - Lesson 27.1</option><option value='10501'>Session 18 - Review Unit 5 + 6 - Lesson 27.2</option><option value='10502'>MOCK TEST 3</option><option value='10503'>Talk To Me: Review 3</option><option value='10504'>Session 19 - Unit 7 - Lesson 28 - Language</option><option value='10505'>Session 19 - Unit 7 - Lesson 31 - Language</option><option value='10506'>Talk To Me: Study a new language </option><option value='10507'>Session 20 - Unit 7 - Lesson 29 - Language</option><option value='10508'>Session 20 - Unit 7 - Lesson 30 - Language</option><option value='10509'>Talk To Me: The future </option><option value='10510'>Session 21 - Unit 8 - Lesson 32 - Science and technology</option><option value='10511'>Session 21 - Unit 8 - Lesson 35 - Science and technology</option><option value='10512'>Talk To Me: Technology</option><option value='10513'>Session 22 - Unit 8 - Lesson 33 - Science and technology</option><option value='10514'>Session 22 - Unit 8 - Lesson 34 - Science and technology</option><option value='10515'>Talk To Me: Different types of television programme</option><option value='10516'>Session 23 - Review Unit 7 + 8 - Lesson 36.1</option><option value='10517'>Session 24 - Review Unit 7 + 8 - Lesson 36.2</option><option value='10518'>FINAL TEST</option><option value='10519'>Talk To Me: Review 4</option></select></div></div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Loại tài nguyên</h4></div><div class=\"col-7\"><select class=\"w-100\" disabled name=\"quiz_type\" id=\"quiz_type\"><option selected  value='quiz'>Quiz</option></select> </div></div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Loại học liệu</h4></div><div class=\"col-7\"><select class=\"w-100\" name=\"quiz_settings_type\" id=\"quiz_settings_type\"><option  selected  value='1'>Pre Class</option><option   value='2'>In Class</option><option   value='3'>Post Class</option><option   value='4'>Mock Test</option><option   value='5'>Loại học liệu mới</option></select> </div></div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Trạng thái</h4></div><div class=\"col-7\"><select class=\"w-100\" name=\"quiz_status\" id=\"quiz_status\"><option   value='0'>Ẩn</option><option  selected  value='1'>Hiện</option></select> </div></div><div class=\"row mb-2\"> <div class=\"col-12\"> <h5><b>Điểm</b></h5></div> </div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Thang điểm</h4></div><div class=\"col-7\"><input class=\"i-grade-quiz w-100\" min=\"0\" type=\"number\" name=\"gradeQuiz\" value=\"10.00\"/></div></div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Số lần làm bài</h4></div><div class=\"col-7\"><select class=\"w-100\" name=\"attempts\" id=\"attempts\" ><option  selected  value='0'>Không giới hạn</option><option   value='1'>1</option><option   value='2'>2</option><option   value='3'>3</option><option   value='4'>4</option><option   value='5'>5</option><option   value='6'>6</option><option   value='7'>7</option><option   value='8'>8</option><option   value='9'>9</option><option   value='10'>10</option></select> </div></div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Cách tính điểm</h4></div><div class=\"col-7\"><select class=\"w-100\" name=\"grademethod\" id=\"grademethod\" ><option  selected  value='1'>Lần cao nhất</option><option   value='2'>Điểm trung bình</option><option   value='3'>Thử nghiệm lần đầu</option><option   value='4'>Kiểm tra lần cuối</option></select> </div></div><div class=\"row mb-2\"> <div class=\"col-12\"> <h5><b>Hành vi câu hỏi</b></h5></div> </div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Thay đổi vị trí đáp án trong các câu hỏi</h4></div><div class=\"col-7\"><select class=\"w-100\" name=\"shuffleanswers\" id=\"shuffleanswers\" ><option   value='0'>Không</option><option  selected  value='1'>Có</option></select> </div></div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Hành vi của các câu hỏi như thế nào</h4></div><div class=\"col-7\"><select class=\"w-100\" name=\"preferredbehaviour\" id=\"preferredbehaviour\" ><option   value='adaptive'>Adaptive mode</option><option   value='adaptivenopenalty'>Adaptive mode (no penalties)</option><option  selected  value='deferredfeedback'>Deferred feedback</option><option   value='deferredcbm'>Deferred feedback with CBM</option><option   value='immediatefeedback'>Immediate feedback</option><option   value='immediatecbm'>Immediate feedback with CBM</option><option   value='interactive'>Interactive with multiple tries</option></select> </div></div><div class=\"row mb-2\"> <div class=\"col-12\"> <h5><b>Xem lại các tùy chọn</b></h5></div> </div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Sau khi nộp bài</h4></div><div class=\"col-7\"><div class=\"form-check\">\n                      <input class=\"form-check-input\" name=\"attemptimmediatelyopen\" style=\"width: auto\" type=\"checkbox\" checked id=\"id_attemptimmediatelyopen\" value=\"1\">\n                      <label class=\"form-check-label\" for=\"id_attemptimmediatelyopen\">Bài làm</label>\n                    </div><div class=\"form-check\">\n                      <input class=\"form-check-input\" type=\"checkbox\" style=\"width: auto\" name=\"correctnessimmediatelyopen\" checked id=\"id_correctnessimmediatelyopen\" value=\"1\">\n                      <label class=\"form-check-label\" for=\"id_correctnessimmediatelyopen\">Nếu đúng</label>\n                    </div><div class=\"form-check\">\n                      <input class=\"form-check-input\" type=\"checkbox\" style=\"width: auto\" name=\"marksimmediatelyopen\" checked id=\"id_marksimmediatelyopen\" value=\"1\">\n                      <label class=\"form-check-label\" for=\"id_marksimmediatelyopen\">Điểm</label>\n                    </div><div class=\"form-check\">\n                      <input class=\"form-check-input\" type=\"checkbox\" style=\"width: auto\" name=\"rightanswerimmediatelyopen\" checked id=\"id_rightanswerimmediatelyopen\" value=\"1\">\n                      <label class=\"form-check-label\" for=\"id_rightanswerimmediatelyopen\">Câu trả lời đúng</label>\n                    </div><div class=\"form-check\">\n                      <input class=\"form-check-input\" type=\"checkbox\" style=\"width: auto\" name=\"generalfeedbackimmediatelyopen\"  checked id=\"id_generalfeedbackimmediatelyopen\" value=\"1\">\n                      <label class=\"form-check-label\" for=\"id_generalfeedbackimmediatelyopen\"> Phản hồi chung</label>\n                    </div></div></div><div class=\"row mb-2\"> <div class=\"col-12\"> <h5><b>Không cho phép truy cập</b></h5></div> </div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Activity completion</h5></div><div class=\"col-7\"><a href=\"javascript:void(0);\" class=\"btn btn-secondary\" data-toggle=\"modal\" data-target=\"#modal-availability\" data-cmid=\"57749\" data-instance=\"45960\" data-type=\"quiz\">Chọn</a></div></div><div class=\"row mb-2\"> <div class=\"col-12\"> <h5><b>Hoạt động hoàn thành</b></h5></div> </div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Completion tracking</h5></div><div class=\"col-7\"><select class=\"w-100\" name=\"completion\" id=\"quiz_completion\" ><option   value='0'>Không chỉ rõ việc hoàn thành hoạt động</option><option  selected  value='2'>Khi các điều kiện được thỏa mãn, đánh dấu hoạt động như là đã hoàn thành</option></select> <div class=\"form-check form-check-completionview\" style=\"\">\n                      <input class=\"form-check-input\" type=\"checkbox\" style=\"width: auto\" name=\"completionview\" checked id=\"quiz_completionview\" value=\"1\">\n                      <label class=\"form-check-label\" for=\"completionview\">Học viên phải xem hoạt động này để hoàn thành nó</label>\n                    </div><div class=\"form-check form-check-completionpass\" style=\"\">\n                      <input class=\"form-check-input\" type=\"checkbox\" style=\"width: auto\" name=\"completionpass\"  id=\"quiz_completionpass\" value=\"1\">\n                      <label class=\"form-check-label\" for=\"quiz_completionpass\">Yêu cầu điểm qua môn</label>\n                    </div></div></div><div class=\"row mb-2 grade-box\" style=\"display: none;\"><div class=\"col-5\"><h5>Điểm để qua</h5></div><div class=\"col-7\"><input class=\"w-100\" min=\"0\" type=\"number\" id=\"quiz_grade_pass\" name=\"gradePass\" value=\"0.00\"/></div></div><div class=\"row mb-2\"> <div class=\"col-12\"> <h5><b>Lịch sử</b></h5></div> </div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Người tạo</h5></div><div class=\"col-7\"><input disabled class=\"i-grade-quiz w-100\" type=\"text\" name=\"created_by\" value=\"Đào Loan\"/></div></div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Ngày tạo</h5></div><div class=\"col-7\"><input disabled class=\"i-grade-quiz w-100\" type=\"text\" name=\"created_at\" value=\"2023-08-02 13:58:35\"/></div></div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Người cập nhật</h5></div><div class=\"col-7\"><input disabled class=\"i-grade-quiz w-100\" type=\"text\" name=\"created_by\" value=\"VHT Nhung\"/></div></div><div class=\"row mb-2\"><div class=\"col-5\"><h5>Ngày cập nhật</h5></div><div class=\"col-7\"><input disabled class=\"i-grade-quiz w-100\" type=\"text\" name=\"created_at\" value=\"2023-08-15 09:27:39\"/></div></div><input type=\"hidden\" name=\"availability_cmid\" id=\"availability-cmid-57749\" class=\"cmid-must-complete\" value=\"\"><input type=\"hidden\" name=\"availability_folder\" id=\"availability-folder-57749\" class=\"folfer-must-complete\" value=\"\"><input type=\"hidden\" name=\"change_availability\" id=\"change-availability-57749\" value=\"0\"><input type=\"hidden\" name=\"activity_cmid\" id=\"activity-cmid\" value=\"57749\"></div>"
            }
            if (response.html) {
                $('.form-setting').hide();
                $('#form-'+type+'-setting').html(response.html);
                $('#form-'+type+'-setting').show();
                if (parent) {
                    $('#'+type+'-parent').val(parent);
                }
                $('#id_mode_save').val(type);
            }
        });
    }

    $(document).on('click', '.btn-edit-question', function (e){
        $('#popup_hidden_quiz_id').val($(this).data('quiz'));
        let height = $(window).height()  - 150;
        $('#h_qs_id_edit').val($(this).data('id'));
        let url = $(this).data('url');
        console.log( document.getElementById('edit_view_qs').contentDocument)
        document.getElementById('edit_view_qs').contentDocument.location.reload(true);
        $('#edit_view_qs').attr('src', url);
        if (height) {
            $('#edit_view_qs').attr('height', height);
        }
        $('#popup_hidden_ishvp').val($(this).data('ishvp'));
        $('#popup_hidden_cmid').val($(this).data('cmid'));
        $('#popup_upsert_question').modal();
    });

    $(document).on('click', '.icon-chevron-sub-quiz', function (e){
        $(this).closest('.sub-quiz').find('.sub-quiz-body').toggleClass('hide');
        if ($(this).closest('.sub-quiz').find('.sub-quiz-body').hasClass('hide')) {
            var attr_class = $(this).attr('class').replace('fa-chevron-down', 'fa-chevron-up');
            $(this).attr('title', 'Mở rộng');
        } else {
            var attr_class = $(this).attr('class').replace('fa-chevron-up', 'fa-chevron-down');
            $(this).attr('title', 'Thu gọn');
        }
        $(this).attr('class', attr_class);
    });
    $(document).on('click', '.icon-chevron-section', function (e){
        $(this).closest('.sub-quiz-body').find('.quiz-section-body').toggleClass('hide');
        if ($(this).closest('.sub-quiz-body').find('.quiz-section-body').hasClass('hide')) {
            var attr_class = $(this).attr('class').replace('fa-chevron-down', 'fa-chevron-up');
            $(this).attr('title', 'Mở rộng');
        } else {
            var attr_class = $(this).attr('class').replace('fa-chevron-up', 'fa-chevron-down');
            $(this).attr('title', 'Thu gọn');
        }
        $(this).attr('class', attr_class);
    });

    $(document).on('click', '.edit-quiz-section-name', function (e) {
        var quiz_section_id = $(this).data('quiz-section-id');
        $('.section-name-'+quiz_section_id+', .save-edit-quiz-section-name-'+quiz_section_id+', .cancel-edit-quiz-section-name-'+quiz_section_id).removeClass('display-none');
        $('#quiz_section_name_display_'+quiz_section_id+', .edit-quiz-section-name-'+quiz_section_id).addClass('display-none');
    });
    $(document).on('click', '.cancel-edit-quiz-section-name', function (e) {
        var quiz_section_id = $(this).data('quiz-section-id');
        $('.section-name-'+quiz_section_id).val($('#quiz_section_name_display_'+quiz_section_id).text());
        $('.section-name-'+quiz_section_id+', .save-edit-quiz-section-name-'+quiz_section_id+', .cancel-edit-quiz-section-name-'+quiz_section_id).addClass('display-none');
        $('#quiz_section_name_display_'+quiz_section_id+', .edit-quiz-section-name-'+quiz_section_id).removeClass('display-none');
    });

    $(document).on('click', '.save-edit-quiz-section-name', function (e) {
        var quiz_section_id = $(this).data('quiz-section-id');
        var new_name = $('.section-name-'+quiz_section_id).val();
        $.ajax({
            url: 'https://english.ican.vn/classroom/local/omo_management/quiz/ajax/update_name_quiz_and_section.php',
            type: 'POST',
            dataType: 'json',
            data: {
                name_type: 'section',
                id_rename: quiz_section_id,
                new_name: new_name,
            },
        }).done(function(response) {
            if(response.result){
                $('#quiz_section_name_display_'+quiz_section_id).text(response.new_name);
                $('.send-section-name-'+quiz_section_id).val(response.new_name);
                $('.section-name-'+quiz_section_id+', .save-edit-quiz-section-name-'+quiz_section_id+', .cancel-edit-quiz-section-name-'+quiz_section_id).addClass('display-none');
                $('#quiz_section_name_display_'+quiz_section_id+', .edit-quiz-section-name-'+quiz_section_id).removeClass('display-none');
            }
        });

    });
    $(document).on('click', '.btn-act-view', function (e){
        if ($(this).hasClass('color-active')) {
            $('.quiz-container').find('.mode-edit').attr('style', '');
            $('.quiz-container').find('.mode-view').attr('style', 'display: none;');
            if($('.above-block-toggle, .icon-pagetree').hasClass('hide')){
                $('.course-detail-page .right-part').attr('style', '');
            }else{
                $('.course-detail-page .right-part').attr('style', 'max-width: 100%;');
            }
            console.log(123)
            // $('.course-detail-page .right-part').attr('style', '');
            $('.course-detail-page .quiz-container .qs-view-content').attr('style', '');
            $(this).removeClass('color-active');
        }else {
            $(this).addClass('color-active');
            $('.quiz-container').find('.mode-edit').attr('style', 'display: none;');
            $('.quiz-container').find('.mode-view').attr('style', '');
            if($('.above-block-toggle, .icon-pagetree').hasClass('hide')){
                $('.course-detail-page .right-part').attr('style', 'width: 60%;');
            }else{
                $('.course-detail-page .right-part').attr('style', 'max-width: 100%;');
            }
            $('.course-detail-page .quiz-container .qs-view-content').attr('style', 'width: 70% !important;');
        }
    })
});
