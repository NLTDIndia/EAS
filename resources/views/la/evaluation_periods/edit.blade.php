@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url('/evaluation_periods') }}">Evaluation Period</a> :
@endsection
@section("contentheader_description", $evaluation_period->$view_col)
@section("section", "Evaluation Periods")
@section("section_url", url('/evaluation_periods'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Evaluation Periods Edit : ".$evaluation_period->$view_col)

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul class="list-unstyled">
            @foreach ($errors->all() as $error)
               <li>{!! html_entity_decode($error) !!}</li>
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
				{!! Form::model($evaluation_period, ['route' => ['evaluation_periods.update', $evaluation_period->id ], 'method'=>'PUT', 'id' => 'evaluation_period-edit-form']) !!}
					
					 <span class="col-md-offset-9" style="padding-top:10px;color:red;"> **All fields are mandatory</span>
					{{--@la_form($module)--}}
					@la_input($module, 'evaluation_period')
					@la_input($module, 'status')
					<div class="form-group">
						<label for="start_date">Start date :</label>
						<div class="input-group date" id="startDate">
							<input class="form-control" autocomplete="off" placeholder="Enter Start date" required="1" name="start_date" type="text" value="{{$startDate}}">
							<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
						</div>
					</div>
					<label id="start_date-error" class="error" for="start_date"></label>
				   <div class="form-group">
						<label for="end_date">End date :</label>
						<div class="input-group date" id="endDate">
							<input class="form-control" autocomplete="off" placeholder="Enter End date" required="1" name="end_date" type="text" value="{{$endDate}}">
							<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
						</div>
					</div>
				    <label id="end_date-error" class="error" for="end_date"></label>
					
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success', 'id' => 'btnUpdate']) !!} <button class="btn btn-default pull-right"><a href="{{ url('/evaluation_periods') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$('#btnUpdate').click(function(e){
    e.preventDefault();
    if($("#evaluation_period-edit-form").valid() ) {
     var radioValue  = $("input[name='status']:checked").val();
     
     if(radioValue == 'Completed')	{   
         bootbox.confirm({
        	    message: "Are you sure to change the status to <b>Completed</b>?",
        	    buttons: {
        	        confirm: {
        	            label: 'Yes',
        	            className: 'btn-success'
        	        },
        	        cancel: {
        	            label: 'No',
        	            className: 'btn-danger'
        	        }
        	    },
        	    callback: function (result) {
            	    if(result)
            	    	 $("#evaluation_period-edit-form").submit();
        	    }
        	});
    	 }	
     else { $("#evaluation_period-edit-form").submit(); }
  	 }
});

 $(function () {
	$('#endDate, #startDate').datetimepicker({
	    ignoreReadonly: true ,
	    format: 'DD/MM/YYYY'
	  });

	$("#startDate").on("dp.change", function (e) {
        $("#endDate").data("DateTimePicker").minDate(e.date);
        $("input[name=end_date]").val('');
    });
	$("#endDate").on("dp.change", function (e) {
        $("#startDate").data("DateTimePicker").maxDate(e.date);
   });
 });
	 
</script>
@endpush
