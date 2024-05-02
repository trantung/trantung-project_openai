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
                        <div class="w-screen h-screen flex px-2 py-4">
                            <div class="flex flex-col w-[40%] h-screen justify-start mr-4 pb-6 text-[16px]">
                                <div class="flex flex-2">
                                    <input type="text" placeholder="Fill a name" name="name" id="name" style="border-radius: .5rem;border-color: rgb(206 212 218);" class="form-control">
                                </div>
                                <div class="flex flex-1 mt-2">
                                    <textarea placeholder="Fill a question" id="question" class="flex-1 p-2 bg-white border-[1.5px] border-[#ced4da] rounded-lg resize-none focus:outline-none"></textarea>
                                </div>
                                <div class="flex flex-1 mt-2">
                                    <textarea placeholder="Fill a answer(For LCAT)" class="flex-1 p-2 bg-white border-[1.5px] border-[#ced4da] rounded-lg resize-none focus:outline-none"></textarea>
                                </div>
                                <div class="flex mt-2 flex-2 text-[12px]">
                                    <textarea placeholder="Description question(For LCAT)" class="flex-1 p-2 bg-white border-[1.5px] border-[#ced4da] rounded-lg resize-none focus:outline-none"></textarea>
                                </div>
                                <div class="flex mt-2 flex-2 text-[12px]">
                                    <textarea placeholder="Condition answer(For LCAT)" class="flex-1 p-2 bg-white border-[1.5px] border-[#ced4da] rounded-lg resize-none focus:outline-none"></textarea>
                                </div>
                                <div class="flex mt-2">
                                    <button class="mantine-UnstyledButton-root mantine-Button-root bg-primary mantine-dimeg5" type="button" id="chatGpt" data-button="true">
                                        <div class="mantine-1wpc1xj mantine-Button-inner">
                                            <span class="mantine-1ryt1ht mantine-Button-label">Chat GPT</span>
                                        </div>
                                    </button><button class="mantine-UnstyledButton-root mantine-Button-root ml-10 bg-primary mantine-dimeg5" type="button" data-button="true">
                                        <div class="mantine-1wpc1xj mantine-Button-inner">
                                            <span class="mantine-1ryt1ht mantine-Button-label">LCAT AI</span>
                                        </div>
                                    </button><button class="mantine-UnstyledButton-root mantine-Button-root ml-10 bg-primary mantine-dimeg5" type="button" data-button="true">
                                        <div class="mantine-1wpc1xj mantine-Button-inner">
                                            <span class="mantine-1ryt1ht mantine-Button-label">Reset</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <div class="flex-1 justify-center text-black">
                                <div id="chatMessages" class="msgs_cont border-[1.5px] w-[60%] rounded-lg overflow-auto border-[#ced4da] h-[100%] px-2 py-2 text-[16px]">
                                    <ul id="list_cont">
                                        <li class="rchat">Hello!, Te puedo ayudar hoy?</li>
                                        <li class="schat">hi</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
        $('#chatGpt').click(function() {
            $.ajax({
                url: "{{ route('chat.chat') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    question: $('#question').val(),
                    type: 'removeselect_searchtext'
                },
                success: function(response) {
                    console.log(response);
                    const botMessageDiv = document.createElement('pre');
                    botMessageDiv.insertAdjacentHTML('beforeend', `${response}`);
                    document.getElementById('chatMessages').appendChild(botMessageDiv);
                    // let users = response.users;
                    // $('#removeselect').empty();
                    // if (users.length > 0) {
                    //     var optgroup = $('<optgroup label="Users (' + response.countUsers + ')">');
                    //     response.users.forEach(function(user) {
                    //         optgroup.append('<option value="' + user.id + '">' + user.name + ' (' + user.email + ')</option>');
                    //     });
                    //     $('#removeselect').append(optgroup);
                    // } else {
                    //     var searchText = $("#removeselect_searchtext").val();
                    //     var label = "No users match '" + searchText + "' to show";
                    //     $('#removeselect').append('<optgroup label="' + label + '"><option disabled>&nbsp;</option></optgroup>');
                    // }
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            let bottom = document.querySelector(".bottom");
            let input = document.querySelector("#txt");
            let sendbtn = document.querySelector(".uil-message");
            let ul = document.querySelector("#list_cont");
            bottom.addEventListener("click", () => {
                input.focus();
            });
            input.addEventListener("input", () => {
                if (input.value.length > 0) {
                    sendbtn.style.background = "#11ba91";
                } else {
                    sendbtn.style.background = "transparent";
                }
            });

            function ChatGPT() {
                if (
                    input.value !== "" &&
                    input.value !== null &&
                    input.value.length > 0 &&
                    input.value.trim() !== ""
                ) {
                    sendbtn.style.background = "transparent";
                    let typingAnimationDiv = document.createElement("div");
                    typingAnimationDiv.className = "typing-animation";
                    for (var i = 0; i < 3; i++) {
                        var dotSpan = document.createElement("span");
                        dotSpan.className = "dot";
                        typingAnimationDiv.appendChild(dotSpan);
                    }
                    let li2 = document.createElement("li");
                    li2.className = "rchat";
                    li2.appendChild(typingAnimationDiv);
                    let li = document.createElement("li");
                    li.className = "schat";
                    li.textContent = input.value;
                    ul.appendChild(li);
                    setTimeout(() => {
                        ul.appendChild(li2);
                        $(".msgs_cont").scrollTop($(".msgs_cont")[0].scrollHeight);
                    }, 500);
                    sendbtn.disabled = true;
                    $(".msgs_cont").scrollTop($(".msgs_cont")[0].scrollHeight);
                    fetch(
                            `https://WellinformedHeavyBootstrapping.yasirmecom.repl.co/ask?question=users new question :, ${input.value}`
                        )
                        .then((res) => res.text())
                        .then((data) => {
                            let i = 0;
                            const intervalId = setInterval(() => {
                                if (i < data.length) {
                                    li2.textContent += data[i];
                                    $(".msgs_cont").scrollTop($(".msgs_cont")[0].scrollHeight);
                                    i++;
                                } else {
                                    clearInterval(intervalId);
                                    sendbtn.disabled = false;
                                }
                            }, 20);
                        })
                        .catch((error) => {
                            li2.textContent = `Not working`;
                            ul.appendChild(li2);
                            $(".msgs_cont").scrollTop($(".msgs_cont")[0].scrollHeight);
                        });
                }
                input.value = "";
            }
            sendbtn.addEventListener("click", ChatGPT);
        });
    </script>
</x-app-layout>