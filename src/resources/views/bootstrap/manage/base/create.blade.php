@extends($layout)
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
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
                    <h4 class="title">@lang('elements::lang.create_new_item', ['name' => strtolower($modelName ? $modelName : $model)])</h4>
                    @if(isset($sub_title) && !empty($sub_title))<p class="category">{{$sub_title}}</p>@endif
				</div>
				<div class="card-content card-body">
                    {!! $form->render() !!}
				</div>
			</div>
		</div>
	</div>
</div>

@endsection