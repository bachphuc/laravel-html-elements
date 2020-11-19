@extends($layout)
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-{{is_modal_request() ? '12' : '10'}}">
			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif
			<div class="card {{count($forms) > 1 ? 'card-nav-tabs' : ''}}">
				<div class="card-header" data-background-color="purple">
                    <h4 class="title">@lang('lang.edit_item', ['name' => $modelName ? $modelName : $model])</h4>
					<p class="category"><a href="{{$self->resolveItemUrl($item, 'edit')}}">#{{$item->id}} {{$self->resolveItemAction($item, 'title')}}</a> </p>
					
					@if(count($forms) > 1)
					<div class="nav-tabs-navigation" style="margin-top: 32px;">
                        <div class="nav-tabs-wrapper">
                            <ul class="nav nav-tabs" data-tabs="tabs">
								@foreach($forms as $key => $form)
                                <li class="{{$key === 0 ? 'active' : ''}}">
                                    <a href="#{{$form['key']}}" data-toggle="tab">
                                        <i class="material-icons">{{isset($form['icon']) && !empty($form['icon']) ? $form['icon'] : 'info'}}</i>
                                        {{$form['title']}}
                                    <div class="ripple-container"></div></a>
								</li>
								@endforeach
                            </ul>
                        </div>
					</div>
					@endif
				</div>
				<div class="card-content card-body">
					@if(count($forms) == 1)
					{!! $forms[0]['form']->render() !!}
					@else
					<div class="tab-content">
						@foreach($forms as $key => $form)
                        <div class="tab-pane {{$key === 0 ? 'active' : ''}}" id="{{$form['key']}}">
                            {!! $form['form']->render() !!}
						</div>
						@endforeach
                    </div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection