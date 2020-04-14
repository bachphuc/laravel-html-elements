@extends($layout) 
@section('content')
@php
    $isShowCreateButton = isset($isShowCreateButton)? $isShowCreateButton : true;
    $createUrl = isset($createModelUrl) && !empty($createModelUrl) ? $createModelUrl : (isset($rootRoute) && !empty($rootRoute) ? route($rootRoute . '.create') : route('admin.'. $models.'.create'));

@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" data-background-color="purple">
                    <h4 class="title">{{str_replace('-', ' ' , ucfirst($models))}}</h4>
                    <div class="row">
                        @if($isShowCreateButton || isset($renderActionButtons))
                        <div class="col-md-8 action-buttons-panel">
                            <button class="btn btn-trans" onclick="toggleActionsPanel(this)">Show Actions <i class="material-icons">&#xE313;</i></button>
                            @if(isset($isShowCreateButton))
                            <a class="btn btn-success" href="{{$createUrl}}">Create new {{str_replace('\\', ' ', str_replace('_', ' ', ucfirst($model)))}}</a>
                            @endif
                            @if(isset($renderActionButtons))
                                {!! $renderActionButtons() !!}
                            @endif
                        </div>
                        @endif
                        @if(!empty($searchField))
                        <div class="col-md-4">
                            <div class="search-form">
                                <form method="GET" action="">
                                    <input type="text" placeholder="Search..." name="keyword" value="{{request()->query('keyword')}}" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                                    <button type="submit" class="search-icon"><i class="material-icons">&#xE8B6;</i></button>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                {!! $table->render() !!}
            </div>
        </div>
    </div>
</div>

<script>
    function toggleActionsPanel(ele){
        $(ele).closest('.action-buttons-panel').toggleClass('active');
    }
</script>
@if(isset($actionScript) && !empty($actionScript))
@include($actionScript)
@endif

@endsection