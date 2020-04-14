@extends($layout)
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10">
			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif
			<div class="card">
				<div class="card-header" data-background-color="purple">
                    <h4 class="title">@lang('lang.edit_item', ['name' => $modelName ? $modelName : $model])</h4>
                    <p class="category"><a href="{{$self->resolveItemUrl($item, 'edit')}}">#{{$item->id}} {{$self->resolveItemAction($item, 'title')}}</a> </p>
				</div>
				<div class="card-content card-body">
					{!! $form->render() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection