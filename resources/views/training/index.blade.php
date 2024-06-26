<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Training') }}
        </h2>
    </x-slot>
 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="mb-0">Your models</h1>
                        <a href="{{ route('training.form') }}" class="btn btn-primary">Add new</a>
                    </div>
                    <hr />
                    @if(Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>
                    @endif
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th id='columnname' class='manage-column column-columnname' scope='col'>ID</th>
                                <th id='columnname' class='manage-column column-columnname' scope='col'>Model Name</th>
                                <th id='columnname' class='manage-column column-columnname' scope='col'>Created Date</th>
                                <th id='columnname' class='manage-column column-columnname' scope='col'>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $value)
                            <tr>
                                <td class="align-middle">{{ $value->id }}</td>
                                <td class="align-middle">{{ $value->model_name }}</td>
                                <td class="align-middle">{{ $value->created_at }}</td>
                                <td class="align-middle">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('training.detail', ['id'=>$value->id]) }}" type="button" class="btn btn-secondary">Detail</a>
                                        <a href="{{ route('training.delete', ['id'=>$value->id]) }}" type="button" class="btn btn-danger">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="5">User not found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="col-md-12">
                    {{ $data -> links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>