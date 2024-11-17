<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICANCONNECT - Quản lý Sản Phẩm</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" integrity="sha512-XMVd28F1oH/O71fzwBnV7HucLxVwtxf26XV8P4wPk26EDxuGZ91N8bsOttmnomcCD3CS5ZMRL50H0GgOHvegtg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="full-layout-100 format-site  path-local path-local-omo_management path-local-omo_management-product chrome dir-ltr lang-vi yui-skin-sam yui3-skin-sam english-ican-vn--classroom pagelayout-admin course-1 context-1 theme-lfw header-light2 default-login loggedin noguestuser editfw hide-sidebars coursetheme- custom_id_a59e006be59d custom_id_5e37f615d176 custom_id_5b1649004a21 mv5f0d87962d8b1 isadminuser nosidebar-case jsenabled dndsupported">
    @include('layouts.header')
    <div class="mb2notices"></div>
    @include('layouts.navigation')

    <div class="content-wrapper">
        @include('layouts.breadcrumb')
        @yield('content')
    </div>
    @include('layouts.footer')

    @include('template.popup.emoji')

    @include('template.popup.h5p')

    @include('template.popup.link')

    @include('template.popup.delete-confirm')

    <div class="theme-links closed">
        <a href="#" class="toggle-open" title="Cài đặt" data-width="350"><i class="icon1 fa fa-sliders"></i></a>
        <ul>
            <li><a href="https://english.ican.vn/classroom/admin/category.php?category=theme_mb2cg" title="All settings"><i class="fa fa-cogs"></i> All settings</a></li>
            <li><a href="https://english.ican.vn/classroom/admin/settings.php?section=theme_mb2cg_settingsgeneral" title="General"><i class="fa fa-dashboard"></i> General</a></li>
            <li><a href="https://english.ican.vn/classroom/admin/settings.php?section=theme_mb2cg_settingsfeatures" title="Features"><i class="fa fa-dashboard"></i> Features</a></li>
            <li><a href="https://english.ican.vn/classroom/admin/settings.php?section=theme_mb2cg_settingsfonts" title="Fonts"><i class="fa fa-font"></i> Fonts</a></li>
            <li><a href="https://english.ican.vn/classroom/admin/settings.php?section=theme_mb2cg_settingsnav" title="Menus"><i class="fa fa-navicon"></i> Menus</a></li>
            <li><a href="https://english.ican.vn/classroom/admin/settings.php?section=theme_mb2cg_settingssocial" title="Social"><i class="fa fa-share-alt"></i> Social</a></li>
            <li><a href="https://english.ican.vn/classroom/admin/settings.php?section=theme_mb2cg_settingsstyle" title="Style"><i class="fa fa-paint-brush"></i> Style</a></li>
            <li><a href="https://english.ican.vn/classroom/admin/settings.php?section=theme_mb2cg_settingstypography" title="Typography"><i class="fa fa-text-height"></i> Typography</a></li>
            <li class="custom-link"><a href="https://english.ican.vn/classroom/admin/search.php" class="siteadmin-link"><i class="fa fa-sitemap"></i> Quản trị hệ thống</a></li>
            <li class="custom-link"><a href="https://english.ican.vn/classroom/admin/purgecaches.php?confirm=1&amp;sesskey=OcPVKC4cMP&amp;returnurl=%2Flocal%2Fomo_management%2Fcourse%2Fdetail.php%3Fcourse_id%3D171%26amp%3Bcategory_id%3D0" class="link-purgecaches" title="Dọn dẹp tất cả bộ nhớ đệm"><i class="fa fa-cog"></i> Dọn dẹp tất cả bộ nhớ đệm</a></li>
            <li class="custom-link"><a href="https://mb2themes.com/docs/cognitio" target="_blank" class="link-doc" title="Documentation"><i class="fa fa-info-circle"></i> Documentation</a></li>
            <li class="custom-link"><a href="https://themeforest.net/user/marbol2/portfolio" target="_blank" class="link-more" title="More Themes"><i class="fa fa-shopping-basket"></i> More Themes</a></li>
        </ul>
    </div>

    <a class="theme-scrolltt active" href="#">
        <i class="fa fa-chevron-up" data-scrollspeed="400"></i>
    </a>

    <script src="{{ URL::asset('js/app.js') }}"></script>
    <!-- <script src="{{ URL::asset('js/products.js') }}"></script>
    <script src="{{ URL::asset('js/product_detail.js') }}"></script> -->
    <!-- <script src="{{ URL::asset('js/activity/video.js') }}"></script>
    <script src="{{ URL::asset('js/activity/quiz.js') }}"></script> -->
</body>

</html>
