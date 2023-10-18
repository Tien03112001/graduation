<!-- Breadcrumb -->
@isset($list)
    <div class="breadcrumb-area overlay-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-left ">
                    <nav class="" role="navigation" aria-label="breadcrumbs">
                        <ul class="breadcrumb-list">
                            @foreach($list as $breadcrumb)
                                @if(isset($breadcrumb['href']))
                                    <li>
                                        <a href="{{$breadcrumb['href']}}"
                                           title="{{$breadcrumb['title']}}">{{$breadcrumb['title']}}</a>
                                    </li>
                                @else
                                    <li><span>{{$breadcrumb['title']}}</span></li>
                                @endif
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endisset
<!-- End breadcrumb -->