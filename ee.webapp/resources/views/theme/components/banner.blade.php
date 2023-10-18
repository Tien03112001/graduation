<div class="hero-wrap">
    <div class="overlay"></div>
    <div class="circle-bg"></div>
    <div class="circle-bg-2"></div>
    <div class="container-fluid">
        <div class="row no-gutters d-flex slider-text align-items-center justify-content-center"
             data-scrollax-parent="true">
            <div class="col-md-6 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
                <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span
                            class="mr-2"><a href="/">Home</a></span>
                    @isset($breadcrumbs))
                    @foreach($breadcrumbs as $b)
                        <span class="mr-2"><a href="{{$b->full_path}}">{{$b->name}}</a></span>
                    @endforeach
                    @endisset
                    <span>{{$page->slug}}</span></p>
                <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
                    {{$page->name}}
                </h1>
            </div>
        </div>
    </div>
</div>