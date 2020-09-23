@extends($layout)
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10">
            @if(isset($component))
            {!! $component->render() !!}
            @endif
		</div>
	</div>
</div>
@endsection