<link href="{{ asset('training_index/training-form.css') }}" rel="stylesheet" />
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Content') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p><a href="{{ route('dashboard') }}" class="btn btn-primary">Go Back</a></p>
                    <div id="__next">
                        <div class="Toastify"></div>
                        <div class="w-screen h-screen flex px-2 py-4">
                            <div class="flex flex-col w-[40%] h-screen justify-start mr-4 pb-6 text-[16px]">
                                <div class="flex flex-1">
                                    <textarea placeholder="Fill a question"
                                        class="flex-1 p-2 bg-white border-[1.5px] border-[#ced4da] rounded-lg resize-none focus:outline-none"></textarea>
                                </div>
                                <div class="flex flex-1 mt-2">
                                    <textarea placeholder="Fill a answer(For LCAT)"
                                        class="flex-1 p-2 bg-white border-[1.5px] border-[#ced4da] rounded-lg resize-none focus:outline-none"></textarea>
                                </div>
                                <div class="flex mt-2 flex-2 text-[12px]">
                                    <textarea placeholder="Description question(For LCAT)"
                                        class="flex-1 p-2 bg-white border-[1.5px] border-[#ced4da] rounded-lg resize-none focus:outline-none"></textarea>
                                </div>
                                <div class="flex mt-2 flex-2 text-[12px]">
                                    <textarea placeholder="Condition answer(For LCAT)"
                                        class="flex-1 p-2 bg-white border-[1.5px] border-[#ced4da] rounded-lg resize-none focus:outline-none"></textarea>
                                </div>
                                <div class="flex mt-2">
                                    <button
                                        class="mantine-UnstyledButton-root mantine-Button-root bg-primary mantine-dimeg5"
                                        type="button" data-button="true">
                                        <div class="mantine-1wpc1xj mantine-Button-inner">
                                            <span class="mantine-1ryt1ht mantine-Button-label">Chat GPT</span>
                                        </div>
                                    </button><button
                                        class="mantine-UnstyledButton-root mantine-Button-root ml-10 bg-primary mantine-dimeg5"
                                        type="button" data-button="true">
                                        <div class="mantine-1wpc1xj mantine-Button-inner">
                                            <span class="mantine-1ryt1ht mantine-Button-label">LCAT AI</span>
                                        </div>
                                    </button><button
                                        class="mantine-UnstyledButton-root mantine-Button-root ml-10 bg-primary mantine-dimeg5"
                                        type="button" data-button="true">
                                        <div class="mantine-1wpc1xj mantine-Button-inner">
                                            <span class="mantine-1ryt1ht mantine-Button-label">Reset</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <div class="flex-1 justify-center text-black">
                                <div
                                    class="border-[1.5px] w-[60%] rounded-lg overflow-auto border-[#ced4da] h-[100%] px-2 py-2 text-[16px]">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>