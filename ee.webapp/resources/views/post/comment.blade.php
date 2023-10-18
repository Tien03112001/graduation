<div class="container mt-5">
    <div class="row  d-flex justify-content-center">
        <div class="col-md-8">
            <div class="headings d-flex justify-content-between align-items-center mb-3">
                <h5>Unread comments({{count($post->comments)}})</h5>
            </div>
            @foreach($post->comments as $comment)
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="user d-flex flex-row align-items-center">
                            <img src="https://i.imgur.com/hczKIze.jpg" width="30" class="user-img rounded-circle mr-2">
                            <span><small class="font-weight-bold text-primary">{{$comment->author}}</small> <small class="font-weight-bold">{!! $comment->content !!}</small></span>
                        </div>
                        <small>{{$comment->created_at->diffForHumans()}}</small>
                    </div>
                    <div class="action d-flex justify-content-between mt-2 align-items-center">
                        <div class="reply px-4">
                            <small>Remove</small>
                            <span class="dots"></span>
                            <small>Reply</small>
                            <span class="dots"></span>
                            <small>Translate</small>
                        </div>
                        <div class="icons align-items-center">
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-check-circle-o check-icon"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
