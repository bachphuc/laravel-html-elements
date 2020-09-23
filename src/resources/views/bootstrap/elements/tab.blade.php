@if(isset($elements) && !empty($elements))
<div>
    @if(isset($tabItems) && !empty($tabItems))
    <div class="nav-tabs-navigation">
        <div class="nav-tabs-wrapper">
            <ul class="nav nav-tabs" data-tabs="tabs">
            @foreach($tabItems as $ele)
            {!! $ele->render() !!}
            @endforeach      
            </ul>
        </div>
    </div>  
    @endif


    <div class="tab-content">
        @foreach($elements as $ele)
        {!! $ele->render() !!}
        @endforeach      
    </div>  

</div>
@endif
