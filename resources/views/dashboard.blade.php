<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                     <p><a href="{{ route('training.index') }}" class="btn btn-primary">Training</a></p>
                </div>
                <div class="p-6 text-gray-900">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="mb-0">Your Content</h1>
                        <a href="{{ route('chat.form') }}" class="btn btn-primary">Add new</a>
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
                                <th id='columnname' class='manage-column column-columnname' scope='col'>Name</th>
                                <th id='columnname' class='manage-column column-columnname' scope='col'>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle">1</td>
                                <td class="align-middle"><a href="{{ route('chat.detail') }}">Content 1</a></td>
                                <td class="align-middle">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="" type="button" class="btn btn-secondary">Edit</a>
                                        <a href="" type="button" class="btn btn-danger">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
