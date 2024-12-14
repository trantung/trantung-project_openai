@extends('layouts.app')

@section('content')
<div id="main-content">
    <input type="hidden" id="currentUserEmail" value="{{ $currentEmail }}">
    <div class="container-fluid">
        <div class="row">
            <div id="region-main" class="content-col col-md-12">
                <div id="page-content">
                    <span class="notifications" id="user-notifications"></span>
                    <div role="main">
                        <span id="maincontent"></span>
                        <div id="box-content">
                            <h3>Assign role '{{ $role_name }}'</h3>
                            <div class="message-class">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <div id="content_message">{{ session('success') }}</div>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <div id="content_message">{{ session('error') }}</div>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12 table-responsive table-mobile container">
                                        <form action="{{ route('role.assignUser') }}" id="assignform" method="post">
                                            @csrf
                                            <input type="hidden" name="role_id" value="{{ $role_id }}">
                                            <table class="admintable w-100 roleassigntable generaltable" id="assigningrole" summary="">
                                                <tbody>
                                                    <try>
                                                        <td id="existingcell">
                                                            <p>
                                                                <label for="removeselect">Existing users</label>
                                                            </p>
                                                            <div class="userselector" id="removeselect_wrapper">
                                                                <select name="removeselect[]" id="removeselect" multiple="multiple" size="20" class="form-control no-overflow" data-gtm-form-interact-field-id="0">
                                                                    @if($countAssignedUsers > 0)    
                                                                        @if($statusAssignedUsers)
                                                                        <optgroup label="Users ({{ $countAssignedUsers }})">
                                                                            @foreach($assignedUsers as $assignedUser)
                                                                            <option value="{{ $assignedUser->id }}">{{ $assignedUser->name }} ({{ $assignedUser->email }})</option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                        @else
                                                                        <optgroup label="Too many users ({{ $countAssignedUsers }}) to show">
                                                                            <option disabled="disabled">&nbsp;</option>
                                                                        </optgroup>
                                                                        <optgroup label="Please use the search">
                                                                            <option disabled="disabled">&nbsp;</option>
                                                                        </optgroup>
                                                                        @endif
                                                                    @else
                                                                    <optgroup label="None">
                                                                        <option disabled="disabled">&nbsp;</option>
                                                                    </optgroup>
                                                                    @endif
                                                                </select>
                                                                <div class="form-inline" style="margin-top: 20px;">
                                                                    <label for="removeselect_searchtext">Search</label><input type="text" name="removeselect_searchtext" id="removeselect_searchtext" size="15" value="" class="form-control"><input type="button" value="Clear" class="btn btn-secondary mx-1" id="removeselect_clearbutton" disabled="">
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td id="buttonscell">
                                                            <div id="addcontrols">
                                                                <input name="add" id="add" type="submit" value="◄&nbsp;Add" title="Add" class="btn btn-secondary" disabled=""><br>
                                                            </div>
                                                            <div id="removecontrols">
                                                                <input name="remove" id="remove" type="submit" value="Remove&nbsp;►" title="Remove" class="btn btn-secondary" disabled="">
                                                            </div>
                                                        </td>
                                                        <td id="potentialcell">
                                                            <p>
                                                                <label for="addselect">Potential members</label>
                                                            </p>
                                                            <div class="userselector" id="addselect_wrapper">
                                                                <select name="addselect[]" id="addselect" multiple="multiple" size="20" class="form-control no-overflow">
                                                                    @if($status)
                                                                    <optgroup label="Potential users ({{ $countUsers }})">
                                                                        @foreach($users as $user)
                                                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                    @else
                                                                    <optgroup label="Too many users ({{ $countUsers }}) to show" id="yui_3_17_2_1_1713867067351_123">
                                                                        <option disabled="disabled">&nbsp;</option>
                                                                    </optgroup>
                                                                    <optgroup label="Please use the search">
                                                                        <option disabled="disabled">&nbsp;</option>
                                                                    </optgroup>
                                                                    @endif
                                                                
                                                                    <!-- <optgroup label="Potential users (3)">
                                                                        <option value="7">Teacher 1 (teacher1@gmail.com)</option>
                                                                        <option value="8">Teacher 2 (teacher2@gmail.com)</option>
                                                                        <option value="2">Admin User (trantung196@gmail.com)</option>
                                                                    </optgroup> -->
                                                                </select>
                                                                <div class="form-inline" style="margin-top: 20px;">
                                                                    <label for="addselect_searchtext">Search</label><input type="text" name="addselect_searchtext" id="addselect_searchtext" size="15" value="" class="form-control"><input type="button" value="Clear" class="btn btn-secondary mx-1" id="addselect_clearbutton" disabled="">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" id="backcell">
                                                            <a style="width: auto;background-color: #f0f0f0;border-color: #eaeaea;color: #000;margin-top: 50px;" href="{{ route('role.index') }}" class="btn lgx-btn btn-block" href="">Close</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                    <div class="col-md-12">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <!---->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $('#addselect').change(function() {
        $('#add').prop('disabled', false);
    });
    $('#removeselect').change(function() {
        $('#remove').prop('disabled', false);
    });

    var typingTimer;
    var doneTypingInterval = 1000;

    $("#addselect_searchtext").on("input", function() {
        clearTimeout(typingTimer);

        typingTimer = setTimeout(doneAddTyping, doneTypingInterval);
    });

    $("#removeselect_searchtext").on("input", function() {
        clearTimeout(typingTimer);

        typingTimer = setTimeout(doneRemoveTyping, doneTypingInterval);
    });

    function doneAddTyping() {
        $('#addselect_clearbutton').prop('disabled', false);
        $.ajax({
            url: "/api/role/assigns/search",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                text: $("#addselect_searchtext").val(),
                roleId: "{{ $role_id }}",
                type: 'addselect_searchtext'
            },
            success: function(response) {
                let users = response.users;
                $('#addselect').empty();
                if (response.status && users.length > 0) {
                    var optgroup = $('<optgroup label="Potential users (' + response.countUsers + ')">');
                    response.users.forEach(function(user) {
                        optgroup.append('<option value="' + user.id + '">' + user.name + ' (' + user.email + ')</option>');
                    });
                    $('#addselect').append(optgroup);
                } else {
                    var searchText = $("#addselect_searchtext").val();
                    var label = "No users match '" + searchText + "' to show";
                    $('#addselect').append('<optgroup label="' + label + '"><option disabled>&nbsp;</option></optgroup>');
                }
            }
        });
    }

    function doneRemoveTyping() {
        $('#removeselect_clearbutton').prop('disabled', false);
        $.ajax({
            url: "/api/role/assigns/search",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                text: $("#removeselect_searchtext").val(),
                roleId: "{{ $role_id }}",
                type: 'removeselect_searchtext'
            },
            success: function(response) {
                let users = response.users;
                $('#removeselect').empty();
                if (users.length > 0) {
                    var optgroup = $('<optgroup label="Users (' + response.countUsers + ')">');
                    response.users.forEach(function(user) {
                        optgroup.append('<option value="' + user.id + '">' + user.name + ' (' + user.email + ')</option>');
                    });
                    $('#removeselect').append(optgroup);
                } else {
                    var searchText = $("#removeselect_searchtext").val();
                    var label = "No users match '" + searchText + "' to show";
                    $('#removeselect').append('<optgroup label="' + label + '"><option disabled>&nbsp;</option></optgroup>');
                }
            }
        });
    }

    $('#removeselect_clearbutton').click(function() {
        $('#removeselect_clearbutton').prop('disabled', true);
        $('#removeselect_searchtext').val('');
        $.ajax({
            url: "/api/role/assigns/search",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                text: '',
                roleId: "{{ $role_id }}",
                type: 'removeselect_searchtext'
            },
            success: function(response) {
                let users = response.users;
                $('#removeselect').empty();
                if (users.length > 0) {
                    var optgroup = $('<optgroup label="Users (' + response.countUsers + ')">');
                    response.users.forEach(function(user) {
                        optgroup.append('<option value="' + user.id + '">' + user.name + ' (' + user.email + ')</option>');
                    });
                    $('#removeselect').append(optgroup);
                } else {
                    var searchText = $("#removeselect_searchtext").val();
                    var label = "No users match '" + searchText + "' to show";
                    $('#removeselect').append('<optgroup label="' + label + '"><option disabled>&nbsp;</option></optgroup>');
                }
            }
        });
    });

    $('#addselect_clearbutton').click(function() {
        $('#addselect_clearbutton').prop('disabled', true);
        $('#addselect_searchtext').val('');
        $.ajax({
            url: "/api/role/assigns/search",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                text: '',
                roleId: "{{ $role_id }}",
                type: 'addselect_searchtext'
            },
            success: function(response) {
                let users = response.users;
                $('#addselect').empty();
                if (users.length > 0) {
                    var optgroup = $('<optgroup label="Potential users (' + response.countUsers + ')">');
                    response.users.forEach(function(user) {
                        optgroup.append('<option value="' + user.id + '">' + user.name + ' (' + user.email + ')</option>');
                    });
                    $('#addselect').append(optgroup);
                } else {
                    var searchText = $("#addselect_searchtext").val();
                    var label = "No users match '" + searchText + "' to show";
                    $('#addselect').append('<optgroup label="' + label + '"><option disabled>&nbsp;</option></optgroup>');
                }
            }
        });
    });

</script>
@endsection