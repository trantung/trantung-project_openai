<link href="{{ asset('training_index/training-form.css') }}" rel="stylesheet" />
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Content') }}
        </h2>
    </x-slot>
    <style>
        .msgs_cont .rchat {
            float: left;
            clear: both;
            margin-bottom: 5px;
            background: linear-gradient(to top right, #11ba91, #0a6e55);
            padding-left: 12px;
            border-bottom-left-radius: 20px;
        }

        .msgs_cont li {
            padding: 10px;
            max-width: 70%;
            margin: 10px 0;
            background: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);
            color: #fff;
            list-style-type: none;
            border-radius: 10px;
            color: #f2f2f2;
            word-wrap: break-word;
            overflow: hidden;
            position: relative;
        }

        .msgs_cont .schat {
            float: right;
            clear: both;
            margin-bottom: 5px;
            border-bottom-right-radius: 20px;
        }
    </style>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p><a href="{{ route('dashboard') }}" class="btn btn-primary">Go Back</a></p>
                    <div id="__next">
                        <div class="Toastify"></div>
                        <div class=" h-screen flex px-2 py-4">
                            <div class="flex flex-col w-[40%] h-screen justify-start mr-4 pb-6 text-[16px]">
                                <div class="flex flex-2">
                                    <select name="category" id="category" style="border-radius: .5rem;border-color: rgb(206 212 218);" class="form-control">
                                        <option value="">Default</option>
                                        <option value="1">Cuc 1</option>
                                        <option value="2">Cuc 2</option>
                                        <option value="3">Cuc 3</option>
                                        <option value="4">Cuc 4</option>
                                    </select>
                                </div>
                                <div class="flex flex-2 mt-2">
                                    <input type="text" placeholder="Fill a name" name="name" id="name" style="border-radius: .5rem;border-color: rgb(206 212 218);" class="form-control">
                                </div>
                                <div class="flex flex-2 mt-2">
                                    <input type="text" placeholder="Fill a topic" name="topic" id="topic" style="border-radius: .5rem;border-color: rgb(206 212 218);" class="form-control">
                                </div>
                                <div class="flex flex-1 mt-2">
                                    <textarea placeholder="Fill a question" id="question" class="flex-1 p-2 bg-white border-[1.5px] border-[#ced4da] rounded-lg resize-none focus:outline-none"></textarea>
                                </div>
                                <div class="flex flex-1 mt-2">
                                    <textarea id="answerAI" placeholder="Fill a answer(For LCAT)" class="flex-1 p-2 bg-white border-[1.5px] border-[#ced4da] rounded-lg resize-none focus:outline-none"></textarea>
                                </div>
                                <div class="flex mt-2">
                                    <button class="mantine-UnstyledButton-root mantine-Button-root bg-primary mantine-dimeg5" type="button" id="chatGpt" data-button="true">
                                        <div class="mantine-1wpc1xj mantine-Button-inner">
                                            <span class="mantine-1ryt1ht mantine-Button-label">Chat GPT</span>
                                        </div>
                                    </button>
                                    <!-- <button class="mantine-UnstyledButton-root mantine-Button-root ml-10 bg-primary mantine-dimeg5" type="button" data-button="true">
                                        <div class="mantine-1wpc1xj mantine-Button-inner">
                                            <span class="mantine-1ryt1ht mantine-Button-label">Update</span>
                                        </div>
                                    </button> -->
                                </div>
                            </div>
                            <div class="flex-1 justify-center text-black">
                                <div id="chatMessages" class="msgs_cont border-[1.5px] w-[100%] rounded-lg overflow-auto border-[#ced4da] h-[100%] px-2 py-2 text-[16px]">
                                    <label for="">Category: 
                                        @if($question->category_id == 1)
                                        Cuc 1
                                        @elseif($question->category_id == 2)
                                        Cuc 2
                                        @elseif($question->category_id == 3)
                                        Cuc 3
                                        @elseif($question->category_id == 4)
                                        Cuc 4
                                        @else
                                        Default
                                        @endif
                                    </label>    
                                    <p>Name:</p>
                                    <label for="">{{ $question->name }}</label>
                                    <p>Topic:</p>
                                    <label for="">{{ $question->topic }}</label>
                                    <p>Question:</p>
                                    <label for="">{{ $question->question }}</label>
                                    <p>Answer:</p>
                                    <pre style="white-space: pre-wrap;">{{ $question->answer }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal loadModal"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
        $('#chatGpt').click(async function() {
            if ($('#name').val() == '') {
                alert("Invalid name");
                $("#name").focus();
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
                return;
            }
            if ($('#question').val() == '') {
                alert("Invalid question");
                $("#question").focus();
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
                return;
            }
            $('body').toggleClass('loading');
            if ($('#category').val() == '') {
                $.ajax({
                    url: "{{ route('chat.chat') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        title: $('#name').val(),
                        question: $('#question').val(),
                        type: 'detail'
                    },
                    success: function(response) {
                        console.log(response);

                        // $('#answerAI').val(botResponse);
                        $('#answerAI').val(response);
                    },
                    complete: function() {
                        $('body').toggleClass('loading');
                    }
                });
            } else {
                try {
                    const botResponse = await getBotResponseFromChatApiLaravel($('#question').val());
                    $('body').toggleClass('loading');
                    $('#answerAI').val(botResponse);
                } catch (error) {
                    console.error('Error when loading data:', error);
                    throw error;
                }
            }
        });

        async function getBotResponseFromChatApiLaravel(question) {
            try {
                const response = await $.ajax({
                    url: "http://ai.microgem.io.vn/api/openai/test/introduction",
                    method: 'POST',
                    contentType: 'application/json', // Chỉ định kiểu dữ liệu
                    data: JSON.stringify({ question: question }), // Chuyển đổi thành chuỗi JSON
                });
                return response.data;
            } catch (error) {
                console.error('Error when loading data:', error);
                throw error;
            }
        }

    </script>
</x-app-layout>