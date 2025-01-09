<div id="main-navigation">
    <div class="main-navigation-inner">
        <div class="container-fluid menu-container">
            <div class="row pc-menu">
                <div class="col-md-12">
                    <a class="menu-bar show-menu" href="#" title="Menu">
                        <i class="fa fa-bars"></i> Menu
                    </a>
                    <ul class="main-menu theme-ddmenu clearfix sf-js-enabled desk-menu" data-animtype="1" data-animspeed="150" style="touch-action: pan-y;">
                        <!-- Home -->
                        <li class="home-item">
                            <a href="#"><i style="font-size:25px;" class="fa fa-home"></i></a>
                        </li>

                        <!-- Dynamic Menus -->
                        @foreach($menus as $menu)
                            <li class="dropdown">
                                <a href="#" title="{{ $menu['title'] }}" class="sf-with-ul">
                                    {{ $menu['title'] }}
                                    <span class="mobile-arrow"></span>
                                </a>
                                <ul class="dropdown-list">
                                    @foreach($menu['items'] as $item)
                                        <li>
                                            @if(isset($item['url']))
                                                <a href="{{ $item['url'] }}" target="_blank">{{ $item['name'] }}</a>
                                            @elseif(isset($item['route']))
                                                <a href="{{ route($item['route']) }}">{{ $item['name'] }}</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach

                        <!-- Search -->
                        <!-- <li class="search-item">
                            <a href="#" title="Tìm kiếm"><i class="fa fa-search"></i></a>
                        </li> -->

                        <!-- Bookmarks -->
                        <!-- <li class="bookmarks-item dropdown">
                            <a href="#" title="Bookmarks" class="sf-with-ul">
                                <span class="text">Bookmarks</span>
                                <i class="fa fa-bookmark"></i>
                                <span class="mobile-arrow"></span>
                            </a>
                            <ul class="theme-bookmarks dropdown-list">
                                <li class="theme-bookmarks-add">
                                    <a href="#" class="theme-bookmarks-form" title="Đánh dấu trang này">
                                        Đánh dấu trang này
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                    </ul>

                </div>
            </div>
        </div>
        <!-- <div class="theme-searchform">
            <a href="#" class="search-close">
                <i class="icon2 fa-solid fa-xmark"></i>
            </a>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <form id="theme-search" action="#">
                            <input id="theme-searchbox" type="text" placeholder="Tìm kiếm khoá học" name="search">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</div>
