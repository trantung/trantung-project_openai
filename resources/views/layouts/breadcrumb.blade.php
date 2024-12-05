<div id="page-header" class="nobutton">
    <div class="inner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb">
                        <nav aria-label="Thanh điều hướng">
                            <ol class="breadcrumb">
                                @foreach ($breadcrumbs as $breadcrumb)
                                    <li class="breadcrumb-item">
                                        @if (!empty($breadcrumb['url']))
                                            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['text'] }}</a>
                                        @else
                                            {{ $breadcrumb['text'] }}
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>