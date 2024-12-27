$(document).ready(function() {


    let index = 0;
    let rubricScoreIdsDelete = [];

    $('.add-rubric-score').on('click', function () {
        index++;
        let row = `<div class="mb-2 row score-row">
            <div class="col-2"></div>
            <div class="col-4">
                <input type="text" name="rubric_score[create][${index}][lms_score]" value="" class="js-required form-control" placeholder="lms_score" required>
            </div>
            <div><span> - </span></div>
            <div class="col-4">
                <input type="text" name="rubric_score[create][${index}][rule_score]" value="" class="js-required form-control" placeholder="rule_score" required>
            </div>
            <div class="col-1 btn-score-delete">
                <i class="fas fa fa-lg fa-trash text-danger"></i>
            </div>
        </div>`;

        $('.rubric-score').append(row);
    });

    $(document).on('click','.btn-score-delete', function () {
        let id = $(this).parent().find('.rubric_score_id').val();
        if (id) {
            rubricScoreIdsDelete.push(id)
        }
        $('#rubric_score_ids_delete').val(rubricScoreIdsDelete);
        $(this).parent().remove();
    });
});
