$(document).ready(function() {
    let index = 0;
    let rubricScoreIdsDelete = [];

    $('.add-rubric-score').on('click', function () {
        index++;
        let row = `<div class="mb-2 row score-row">
            <div class="col-2"></div>
            <div class="col-4">
                <input type="text" name="rubric_scores[create][${index}][lms_score]" value="" class="js-required form-control" placeholder="lms_score" required>
            </div>
            <div><span> - </span></div>
            <div class="col-4">
                <input type="text" name="rubric_scores[create][${index}][rule_score]" value="" class="js-required form-control" placeholder="rule_score" required>
            </div>
            <div class="col-1 btn-score-delete">
                <i class="fas fa fa-lg fa-trash text-danger"></i>
            </div>
        </div>`;

        $('.rubric-score').append(row);
    });

    //delete rubric-score
    $(document).on('click','.btn-score-delete', function () {
        let id = $(this).parent().find('.rubric_score_id').val();
        if (id) {
            rubricScoreIdsDelete.push(id)
        }
        $('#rubric_score_ids_delete').val(rubricScoreIdsDelete);
        $(this).parent().remove();
    });


    //setting ems-type
    $('.setting-ems-type').click(function () {
        let rubricTemplateId = $(this).data('id');
        $('#rubric_template_name').text($(this).data('name'))
        $.ajax({
            url: '/rubric-templates/ajax/ems-types-and-api-moodles',
            method: 'GET',
            data: { rubric_template_id: rubricTemplateId },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('.ems-type').html(response.data);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    //save setting
    $('.ems-type-save').click(function () {
        let selectedEmsValues = [];
        let selectedApiMoodleValues = [];
        let formData = new FormData();
        $('.api_ems_id:checked').each(function() {
            selectedEmsValues.push($(this).val());
        });

        $('.api_moodle_id:checked').each(function() {
            selectedApiMoodleValues.push($(this).val());
        });


        $('#api_ems_ids').val(selectedEmsValues)
        $('#api_moodle_ids').val(selectedApiMoodleValues)
        formData.append('api_ems_ids', $('#api_ems_ids').val());
        formData.append('api_moodle_ids', $('#api_moodle_ids').val());
        formData.append('rubric_template_id', $('#rubric_template_popup_id').val());

        $.ajax({
            url: '/rubric-templates/ajax/update-rubric-template-id-in-api-ems-and-api-moodles',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    $('.alert-success').attr('style', 'display: block !important;');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
});
