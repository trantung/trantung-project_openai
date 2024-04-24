<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Detail') }}
        </h2>
    </x-slot>
 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="mb-0">Data Detail</h1>
                    <hr />
                    <p><a href="{{ route('training.index') }}" class="btn btn-primary">Go Back</a></p>
                    <form method="POST">
                        @csrf
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Model Name</label>
                                <input type="text" required name="model_name" class="form-control" placeholder="Name" value="{{$embedding->model_name}}">
                            </div>
                        </div>
                        @foreach ($content as $value)
                            @php
                                $userContent = '';
                                $assistantContent = '';
                                if(!empty($value->messages)){
                                    foreach ($value->messages as $message) {
                                        if ($message->role === 'user' && !empty($message->content)) {
                                            $userContent = $message->content;
                                        } elseif ($message->role === 'assistant' && !empty($message->content)) {
                                            $assistantContent = $message->content;
                                        }
                                    }  
                            @endphp
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label class="form-label">Question</label>
                                            <textarea name="question_model" placeholder="question" class="form-control">{{$userContent}}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label class="form-label">Answer</label>
                                            <textarea name="answer_model" placeholder="answer" class="form-control">{{$assistantContent}}</textarea>
                                        </div>
                                    </div>
                            @php
                                }
                            @endphp
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>