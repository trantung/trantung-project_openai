<link href="{{ asset('training_index/training-form.css') }}" rel="stylesheet">
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Model Training Form') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="mb-0">Add Model</h1>
                    <hr />
                    @if (session()->has('error'))
                    <div>
                        {{session('error')}}
                    </div>
                    @endif
                    <p><a href="{{ route('training.index') }}" id="goback" class="btn btn-primary">Go Back</a></p>
 
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Base model:</label>
                            <div class="col-sm-8">
                                <select name="base_model" id="base_model" class="form-control">
                                    <option value="">Default</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Model name:</label>
                            <div class="col-sm-8">
                                <input type="text" required="" class="form-control model_name" id="model_name" name="model_name" placeholder="Model name">
                            </div>
                        </div>
                        <div class="form-group form-group-width model_config">
                            <label for="vote-icon">Model config
                                <button type="button" class="btn btn-primary btn-sm add_quickly">Add quickly</button>
                            </label>
                            <label for="counter-input" class="label">Character count: <span id="counter-display" class="tag is-success">0</span></label>
                            <div class="main_config">
                                <div class="p-form-cg pt-form-panel model_content" id="model_content1">
                                    <h4>Config 1</h4>
                                    <div class="modelMessagesData row">
                                        <div class="modelMessageForm col-10">
                                            <div class="radio">Question
                                                <div class="input-group p-has-icon">
                                                    <textarea name="question_model[]" style="height: 35px;" id="radio-other" placeholder="question" class="form-control"></textarea>
                                                    <!-- <input type="text" id="radio-other" required name="question_model[]" placeholder="question" class="form-control"> -->
                                                </div>
                                            </div>
                                            <div class="radio">Answer
                                                <div class="input-group p-has-icon">
                                                    <textarea name="answer_model[]" style="height: 35px;" id="radio-other" placeholder="answer" class="form-control"></textarea>
                                                    <!-- <input type="text" id="radio-other" required name="answer_model[]" placeholder="answer" class="form-control"> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modelMessageAction col-2">
                                            <button onclick="createQuestionButton()" type="button" class="neko-button sc-iBdoyZ fQgLnz primary has-icon create_question rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" focusable="false" class="sc-eDLKEg ZTVGq neko-icon" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" style="margin: 0px auto;">
                                                    <path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" onclick="clickRemoveBtn(this)" class="neko-button sc-iBdoyZ fQgLnz primary has-icon remove_question rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" focusable="false" class="sc-eDLKEg ZTVGq neko-icon" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" style="margin: 0px auto;">
                                                    <path fill="currentColor" d="M9 3v1H4v2h1v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6h1V4h-5V3H9m0 5h2v9H9V8m4 0h2v9h-2V8Z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <label for="counter-input-bottom" class="label">Character count: <span id="counter-display-bottom" class="tag is-success">0</span></label>
                            <input type="button" name="save_model" id="save_model" class="btn btn-primary btn-sm" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal loadModal"></div>
    <div id="modalAlert" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <div class="icon-box">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <h4>Great!</h4>
                    <p>Your file has been uploaded successfully.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="{{ asset('training_index/training-form.js') }}"></script>

<script>
    $('#modalAlert').click(function() {
        $('#modalAlert').modal('hide');
        window.location.href = "{{ route('training.index') }}";
    })
    $('.modal-confirm .close').click(function() {
        $('#modalAlert').modal('hide');
        window.location.href = "{{ route('training.index') }}";
    })
    $('#save_model').click(function() {
        var questions = [];
        var answers = [];
        var isValid = true;
        var formData = new FormData();

        $('.model_content').each(function() {
            var question = $(this).find('textarea[name="question_model[]"]').val();
            var answer = $(this).find('textarea[name="answer_model[]"]').val();

            if (!question.trim() || !answer.trim()) {
                isValid = false;
                return false;
            }
            questions.push(question);
            answers.push(answer);
        });
        if ($('#model_name').val() == '') {
            alert("Invalid model name");
            $("#model_name").focus();
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
            return;
        }
        if (!isValid || questions.length < 10 || answers.length < 10) {
            alert('Please fill in all the configs you create and there must be at least 10 configs');
            return;
        }
        console.log(questions);
        $('body').toggleClass('loading');
        $.ajax({
            url: "{{route('training.store')}}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                model_name: $('#model_name').val(),
                base_model: $('#base_model').val(),
                questions: questions,
                answers: answers,
                userName: "{{ Auth::user()->name; }}"
            },
            success: function(response) {
                console.log(response);
                
                if (response.code == 200) {
                    $('#modalAlert .icon-box').html('<i class="fa-solid fa-check"></i>');
                    $('#modalAlert .modal-body h4').text('Great!');
                    $('#modalAlert .modal-body p').text('Your file has been uploaded successfully.');
                    $('#modalAlert .modal-header').css('background', '#47c9a2');
                    // getDataModelAI(currentPage,  updatePaginationCallbackDataModelAI);
                } else {
                    $('#modalAlert .icon-box').html('<i class="fa-solid fa-xmark"></i>');
                    $('#modalAlert .modal-body h4').text('Ooops!');
                    $('#modalAlert .modal-body p').text('Something went wrong. File was not uploaded. ' + response.data.messages);
                    $('#modalAlert .modal-header').css('background', '#e85e6c');
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while saving changes');
            },
            complete: function() {
                $('#model_name').val('');
                $('body').toggleClass('loading');
                $('#modalAlert').modal('show');
            }
        });
    });
</script>
