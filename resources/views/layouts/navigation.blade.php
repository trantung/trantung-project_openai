<div id="main-navigation">
    <div class="main-navigation-inner">
        <div class="container-fluid menu-container">
            <div class="row pc-menu">
                <div class="col-md-12">
                    <a class="menu-bar show-menu" href="#" title="Menu"><i
                            class="fa fa-bars"></i>Menu</a>
                    <ul class="main-menu theme-ddmenu clearfix sf-js-enabled desk-menu" data-animtype="1"
                        data-animspeed="150" style="touch-action: pan-y;">
                        <li class="home-item"><a href="#"><i
                                    style="font-size:25px;" class="fa fa-home"></i></a></li>
                        <li class="mycourses dropdown"><a href="#" title="Khoá của tôi"
                                class="sf-with-ul">Khoá của tôi <span class="mycourses-num">({{ $courses->count() }})</span><span
                                    class="mobile-arrow"></span></a>
                            <ul style="display: none;">
                                @if(isset($courses) && $courses->count() > 0)
                                        @foreach($courses as $course)
                                                <li class="visible1">
                                                        <a href="{{ env('URL_LMS') . '/course/view.php?id=' . $course->moodle_id }}" target="_blank">{{ $course->moodle_name }}</a>
                                                </li>
                                        @endforeach
                                @else
                                        <li>No courses available</li>
                                @endif
                                <!-- <li class="visible1 student"><a
                                        href="#"
                                        title="🏫IELTS Classroom - Foundation">🏫IELTS Classroom - Foundation</a>
                                </li>
                                <li class="visible1 coursecreator"><a
                                        href="#"
                                        title="TALK TO ME - INTENSIVE">TALK TO ME - INTENSIVE</a></li>
                                <li class="visible1 coursecreator"><a
                                        href="#"
                                        title="Prepare Classroom - Beginners">Prepare Classroom - Beginners</a>
                                </li>
                                <li class="visible1 editingteacher"><a
                                        href="#"
                                        title="Prepare Classroom - Elementary">Prepare Classroom - Elementary</a>
                                </li>
                                <li class="visible1 contentcreator"><a
                                        href="#"
                                        title="Prepare Classroom -  Pre-Intermediate">Prepare Classroom -
                                        Pre-Intermediate</a></li>
                                <li class="visible1 contentcreator"><a
                                        href="#"
                                        title="Prepare Classroom - Intermediate ">Prepare Classroom - Intermediate
                                    </a></li>
                                <li class="visible1 student"><a
                                        href="#"
                                        title="KHÓA HỌC DÙNG ĐỂ TEST">KHÓA HỌC DÙNG ĐỂ TEST</a></li>
                                <li class="visible1 coursecreator"><a
                                        href="#"
                                        title="Easy SPEAK - Level 1">Easy SPEAK - Level 1</a></li>
                                <li class="visible1 coursecreator"><a
                                        href="#"
                                        title="IELTS Tutoring - Introduction">IELTS Tutoring - Introduction</a>
                                </li>
                                <li class="visible1 coursecreator"><a
                                        href="#"
                                        title="IELTS Tutoring - Intensive">IELTS Tutoring - Intensive</a></li>
                                <li class="visible1 coursecreator"><a
                                        href="#"
                                        title="IELTS Tutoring - Preparation">IELTS Tutoring - Preparation</a></li>
                                <li class="visible1 coursecreator"><a
                                        href="#"
                                        title="IELTS Tutoring - Foundation">IELTS Tutoring - Foundation</a></li>
                                <li class="visible1 coursecreator"><a
                                        href="#"
                                        title="📋 IELTS Classroom - Intensive (3B)">📋 IELTS Classroom - Intensive
                                        (3B)</a></li> -->
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#cm_submenu_1" class="sf-with-ul" data-toggle=""
                                title="Quản lí vận hành">Quản lí vận hành<span class="mobile-arrow"></span></a>
                            <ul class="dropdown-list" style="display: none;">
                                <li class=""><a title="Thời khoá biểu" class=""
                                        href="#">Thời
                                        khoá biểu</a></li>
                                <li class=""><a title="QLVH sản phẩm" class=""
                                        href="#">QLVH
                                        sản phẩm</a></li>
                                <li class=""><a title="Danh sách công việc" class=""
                                        href="#">Danh
                                        sách công việc</a></li>
                                <li class="">
                                        <a title="Danh sách lớp" class="" href="{{ route('class.index') }}">
                                                Danh sách lớp
                                        </a>
                                </li>
                                <li class=""><a title="Danh sách học sinh" class=""
                                        href="#">Danh
                                        sách học sinh</a></li>
                                <li class="">
                                        <a title="Danh sách giáo viên" class="" href="{{ route('teacher.index') }}">
                                                Danh sách giáo viên
                                        </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#cm_submenu_2" class="sf-with-ul" data-toggle=""
                                title="Quản lí nội dung">Quản lí nội dung<span class="mobile-arrow"></span></a>
                            <ul class="dropdown-list" style="display: none;">
                                <li class=""><a title="Quản lí sản phẩm" class=""
                                        href="{{ route('course.index') }}">Quản
                                        lí sản phẩm</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#cm_submenu_3" class="sf-with-ul" data-toggle=""
                                title="Hệ thống">Hệ thống<span class="mobile-arrow"></span></a>
                            <ul class="dropdown-list" style="display: none;">
                                <li class=""><a title="Danh sách trường" class=""
                                        href="#">Danh
                                        sách trường</a></li>
                                <li class=""><a title="Danh sách thành viên" class=""
                                        href="#">Danh
                                        sách thành viên</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#cm_submenu_4" class="sf-with-ul" data-toggle=""
                                title="Báo cáo">Báo cáo<span class="mobile-arrow"></span></a>
                            <ul class="dropdown-list" style="display: none;">
                                <li class=""><a title="Báo cáo điểm danh giáo viên" class=""
                                        href="#">Báo
                                        cáo điểm danh giáo viên</a></li>
                                <li class=""><a title="Báo cáo tình hình học tập" class=""
                                        href="#">Báo
                                        cáo tình hình học tập</a></li>
                            </ul>
                        </li>
                        <li class="search-item"><a href="#" title="Tìm kiếm"><i
                                    class="fa fa-search"></i></a></li>
                        <li class="bookmarks-item dropdown"><a href="#" title="Bookmarks"
                                class="sf-with-ul"><span class="text">Bookmarks</span><i
                                    class="fa fa-bookmark"></i><span class="mobile-arrow"></span></a>
                            <ul class="theme-bookmarks dropdown-list" style="display: none;">
                                <li class="theme-bookmarks-add"><a href="#" class="theme-bookmarks-form"
                                        title="Đánh dấu trang này"
                                        data-url="/local/omo_management/product/index.php"
                                        data-mb2bktitle="Danh sách sản phẩm" data-toggle="modal"
                                        data-target="#theme-bookmarks-modal">Đánh dấu trang này</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="theme-searchform"><a href="#" class="search-close"><i
                    class="icon2 fa-solid fa-xmark"></i></a>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <form id="theme-search" action="#">
                            <input id="theme-searchbox" type="text" value=""
                                placeholder="Tìm kiếm khoá học" name="search"><button type="submit"><i
                                    class="fa fa-search"></i></button></form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
