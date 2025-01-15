@extends('layouts.app')

@section('content')
<link href="{{ URL::asset('css/classes.css') }}"rel="stylesheet">
<div id="main-content">
    <input type="hidden" id="currentUserEmail" value="{{ $currentEmail }}">
    <div class="container-fluid">
        <div class="row">
            <div id="region-main" class="content-col col-md-12">
                <div id="page-content">
                    <span class="notifications" id="user-notifications"></span>
                    <div role="main">
                        <span id="maincontent"></span>
                        <div class="">
                            <h2>Danh sách lớp học</h2>
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
                            <form action="{{ route('class.search') }}" class="form-inline mb-2 search-form" method="get">
                                <!-- Select for Class Filter -->
                                <select id="filter-class" name="class" style="width: 20%;" class="select2-hidden-accessible" aria-hidden="true">
                                    <option value="" disabled selected>Nhập tên lớp</option>
                                    @foreach($classesAll as $class)
                                        <option value="{{ $class->id }}" 
                                            @if(request('class') == $class->id) selected @endif>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Select for Status -->
                                <select id="status" name="status" class="form-control ml-2 mr-sm-2">
                                    <option value="" @if(request('status') == '') selected @endif>Chọn trạng thái lớp học</option>
                                    <option value="0" @if(request('status') == 0) selected @endif>Đang vận hành</option>
                                    <option value="1" @if(request('status') == 1) selected @endif>Đã kết thúc khóa học</option>
                                </select>

                                <!-- Select for Course Filter -->
                                <select id="filter-course" name="course" style="width: 20%;" class="select2-hidden-accessible" aria-hidden="true">
                                    <option value="" disabled selected>Nhập tên khóa học</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" 
                                            @if(request('course') == $course->id) selected @endif>
                                            {{ $course->moodle_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Buttons -->
                                <button type="submit" class="btn btn-primary ml-2 btn-search">Tìm kiếm</button>
                                <button type="button" class="btn btn-secondary ml-2 btn-clear">Xóa tìm kiếm</button>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="box-total-item">
                                    Tổng số lớp: {{ count($classesAll) }}
                                </div>
                            </div>
                            <div class="col-6 text-right mb-2">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#importDataClassModal" class="btn btn-primary ml-2">+ Import</a>
                                <a href="{{ route('class.add') }}" class="btn btn-primary">+ Lớp</a>
                            </div>
                        </div>
                        <div class="wrapper-table-scroll">
                            <table class="table-scroll table-class">
                                <thead>
                                    <tr>
                                        <th><div>STT</div></th>
                                        <th><div>Tên lớp</div></th>
                                        <th><div>Sĩ số</div></th>
                                        <th><div>Trạng thái</div></th>
                                        <th><div>Khóa học</div></th>
                                        <th><div>Danh sách học sinh</div></th>
                                        <th><div>Danh sách giáo viên</div></th>
                                        <th><div>Lịch học</div></th>
                                        <th><div>Hành động</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classes as $index => $class)
                                        <tr id="class-{{ $class->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-left">{{ $class->name }}</td>
                                            <td>1</td>
                                            <td>
                                                @if($class->status == 0)
                                                    Đang vận hành
                                                @else
                                                    Đã kết thúc khóa học
                                                @endif
                                            </td>
                                            <td class="text-left">{{ $class->course_name ?? 'N/A' }}</td>
                                            <td class="text-center" id="yui_3_17_2_1_1733230172002_53">
                                                <a href="{{ route('class.edit', ['id' => $class->id]) }}#tab_student" 
                                                title="Danh sách học sinh" 
                                                id="yui_3_17_2_1_1733230172002_52">
                                                    <i class="fas fa fa-lg fa-list" id="yui_3_17_2_1_1733230172002_51"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('class.edit', ['id' => $class->id]) }}#tab_teacher" 
                                                title="Danh sách giáo viên">
                                                    <i class="fas fa fa-lg fa-list"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" 
                                                title="Lịch học">
                                                    <i class="fas fa fa-lg fa-list"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <!-- <a href="#" 
                                                title="Phân quyền học liệu">
                                                    <i class="fas fa fa-lg fa-eye"></i>
                                                </a>
                                                <a href="#" 
                                                title="Thêm học sinh">
                                                    <i class="fas fa fa-lg fa-user-plus text-info"></i>
                                                </a> -->
                                                <a href="{{ route('class.edit', ['id' => $class->id]) }}" 
                                                title="Chỉnh sửa lớp học">
                                                    <i class="fas fa fa-lg fa-edit text-success"></i>
                                                </a>
                                                <a href="javascript:void(0)" 
                                                    class="item-delete btnDeleteClass" 
                                                    data-class-id="{{ $class->id }}" 
                                                    data-class-name="{{ $class->name }}" 
                                                    onclick="showModalDeleteClass(this)"
                                                    title="Xóa lớp học">
                                                    <i class="fas fa fa-lg fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                        <div class="pagination mt-4 justify-content-center">
                            {{ $classes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal moodle-has-zindex deleteClassModal" data-region="modal-container" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable" role="document" data-region="modal" aria-labelledby="0-modal-title" tabindex="0">
        <div class="modal-content">
            <div class="modal-header " data-region="header">
                <h5 id="0-modal-title" class="modal-title" data-region="title">Xóa lớp học</h5>
                <button type="button" class="close" data-action="hide" data-dismiss="modal" aria-label="Đóng">
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-center" data-region="body">
                <form action="{{ route('class.delete') }}" id="deleteClassForm" method="post">
                    @csrf
                    <input type="hidden" name="class_id" id="class_id">
                    Bạn có chắc chắn xoá "<b class="this_item_name">test</b>" khỏi hệ thống? <br>
                    <i>Lưu ý: Lớp học đã xóa không thể khôi phục</i>
                </form>
            </div>
            <div class="modal-footer justify-content-center" data-region="footer">
                <button type="button" class="btn btn-primary btn-action-delete-class" data-action="save">Xoá</button>
                <button type="button" class="btn btn-secondary" data-action="cancel" data-dismiss="modal">Huỷ bỏ</button>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="importDataClassModal" tabindex="-1" aria-labelledby="importDataClassModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importDataClassModalLabel">Import File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="fileUpload">Chọn file CSV để import</label>
                    <form action="{{ route('class.import') }}" id="formUploadFileData" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="file" class="form-control-file" name="file" id="fileUpload" accept=".csv">
                    </form>
                    <small class="form-text text-muted">Chỉ chấp nhận file .csv</small>
                </div>
                <div class="mt-3">
                    <h6>Hướng dẫn:</h6>
                    <ul class="small text-muted">
                        <li>File CSV phải có các cột: Tên, Email, Số điện thoại</li>
                        <li>Dữ liệu phải được phân tách bằng dấu phẩy (,)</li>
                        <li>Kích thước file tối đa: 5MB</li>
                    </ul>
                </div>
                <div id="preview" class="mt-3" style="display:none;">
                    <h6>Preview dữ liệu:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Class Code</th>
                                    <th>Class Name</th>
                                    <th>Class Year</th>
                                    <th>Teacher Email</th>
                                </tr>
                            </thead>
                            <tbody id="previewData">
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mt-2">
                        <span id="totalRows">0</span> dòng dữ liệu được tìm thấy
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center">
                <button type="button" class="btn btn-primary btn-upload-file-class mr-2" data-teacher-id="">Xác nhận</button>
                <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Hủy bỏ</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('js/classes/classes.js') }}"></script>
<script>
    function showModalDeleteClass(button) {
        const classId = $(button).attr('data-class-id');
        const className = $(button).attr('data-class-name');
        
        $('.deleteClassModal .this_item_name').text(className);

        $('.deleteClassModal #class_id').val(classId);

        $('.deleteClassModal').modal('show');
    }

    $(document).ready(function() {
        let csvData = [];

        // Xử lý khi chọn file
        $('#fileUpload').change(function(e) {
            const file = e.target.files[0];
            
            // Kiểm tra định dạng file
            if (file.type !== 'text/csv') {
                alert('Vui lòng chọn file CSV');
                this.value = '';
                return;
            }

            // Kiểm tra kích thước file
            if (file.size > 5 * 1024 * 1024) { // 5MB
                alert('File không được vượt quá 5MB');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                // Đọc dữ liệu từ file
                const csv = event.target.result;
                const lines = csv.split('\n');
                
                // Reset dữ liệu cũ
                csvData = [];
                $('#previewData').empty();
                
                // Bỏ qua dòng header và xử lý từng dòng
                for(let i = 1; i < lines.length; i++) {
                    if(lines[i].trim() === '') continue;
                    
                    const columns = lines[i].split(',');
                    if(columns.length >= 3) {
                        const row = {
                            course_code: columns[0].trim(),
                            course_name: columns[1].trim(),
                            class_code: columns[2].trim(),
                            class_name: columns[3].trim(),
                            class_year: columns[4].trim(),
                            teachers: columns[5]
                        };
                        
                        csvData.push(row);
                        
                        // Thêm dòng vào bảng preview
                        if(i <= 5) { // Chỉ hiện 5 dòng đầu
                            $('#previewData').append(`
                                <tr>
                                    <td>${row.course_code}</td>
                                    <td>${row.course_name}</td>
                                    <td>${row.class_code}</td>
                                    <td>${row.class_name}</td>
                                    <td>${row.class_year}</td>
                                    <td>${row.teachers}</td>
                                </tr>
                            `);
                        }
                    }
                }
                
                // Cập nhật tổng số dòng và hiển thị preview
                $('#totalRows').text(csvData.length);
                $('#preview').show();
            };
            
            reader.readAsText(file);
        });

        // Xử lý khi click nút Xác nhận
        $('.btn-upload-file-class').click(function() {
            if(csvData.length === 0) {
                alert('Vui lòng chọn file CSV trước khi xác nhận');
                return;
            }

            $('#formUploadFileData').submit();
        });

        // Reset form khi đóng modal
        $('#importDataClassModal').on('hidden.bs.modal', function() {
            $('#fileUpload').val('');
            $('#preview').hide();
            $('#previewData').empty();
            csvData = [];
        });
    });
</script>
@endsection