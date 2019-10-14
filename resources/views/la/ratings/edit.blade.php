@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url('/ratings') }}">Rating</a> :
@endsection
@section("contentheader_description", $rating->$view_col)
@section("section", "Ratings")
@section("section_url", url('/ratings'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Ratings Edit : ".$rating->$view_col)

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::model($rating, ['route' => ['ratings.update', $rating->id ], 'method'=>'PUT', 'id' => 'rating-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'rating')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url('/ratings') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
	$("#rating-edit-form").validate({
		
	});
});
</script>
@endpush
