<div class="card">
    <div class="card-header" data-background-color="purple">
        @if(isset($title))<h4 class="title">{{$title}}</h4>@endif
        @if(isset($subTitle))<p class="category">{{$subTitle}}</p>@endif
    </div>
    <div class="card-content card-body">
        @if(isset($elements) && !empty($elements))
        @foreach($elements as $ele)
        {!! $ele->render() !!}
        @endforeach      
        @endif
    </div>
</div>