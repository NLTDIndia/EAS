@extends("la.layouts.app")

@section("contentheader_title", "Evaluation Periods")
@section("contentheader_description", "")
@section("section", "Evaluation Periods")
@section("sub_section", "Listing")
@section("htmlheader_title", "Evaluation Periods Listing")

@section("headerElems")
@la_access("Evaluation_Periods", "create")
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal" id="addEvaluationPeriod">Add Evaluation Period</button>
@endla_access
@endsection

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

@if(session('success'))
	<div class="alert alert-success">
        <ul class="list-unstyled">
    		<li>{{session('success')}}</li>
    	</ul>
    </div>	
@endif

<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="example1" class="table table-bordered">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
			@endforeach
			@if($show_actions)
			<th>Actions</th>
			@endif
		</tr>
		</thead>
		<tbody>
			
		</tbody>
		</table>
	</div>
</div>
<div class="modal fade" id="confirm" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			 <div class="modal-body">
				<div class="box-body">
                    Are you sure to delete this record?
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
    			<button type="button" data-dismiss="modal" class="btn">Cancel</button>
			</div>
		 </div>
	</div>
</div>
@la_access("Evaluation_Periods", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Evaluation Period</h4>
			</div>
			<span class="col-md-offset-8" style="padding-top:10px;color:red;"> **All fields are mandatory</span>
			{!! Form::open(['action' => 'LA\Evaluation_PeriodsController@store', 'id' => 'evaluation_period-add-form']) !!}
			<div class="modal-body"> 
				<div class="box-body">
				   
                    @la_input($module, 'evaluation_period')
					@la_input($module, 'status')
					<div class="form-group">
						<label for="start_date">Start date :</label>
						<div class="input-group date" id="startDate">
							<input class="form-control" autocomplete="off" placeholder="Enter Start date" required="1" name="start_date" type="text" value="">
							<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
						</div>
					</div>
					<label id="start_date-error" class="error" for="start_date"></label>
				   <div class="form-group">
						<label for="end_date">End date :</label>
						<div class="input-group date" id="endDate">
							<input class="form-control" autocomplete="off" placeholder="Enter End date" required="1" name="end_date" type="text" value="">
							<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
						</div>
					</div>
				    <label id="end_date-error" class="error" for="end_date"></label>
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success', 'id' => 'btnCreate']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endla_access

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url('/evaluation_period_dt_ajax') }}",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	 
});

$("#addEvaluationPeriod").click(function(e){
	 var validator = $( "#evaluation_period-add-form" ).validate();
	 validator.resetForm(); 
	 $( 'input[name=evaluation_period], input[name=start_date], input[name=end_date]').removeClass( "error" );
	 $( 'input[name=evaluation_period], input[name=start_date], input[name=end_date]').val("");
	
 });

$('#btnCreate').click(function(e){
    e.preventDefault();
    if($("#evaluation_period-add-form").valid() ) {
     var radioValue  = $("input[name='status']:checked").val();
     
     if(radioValue == 'Completed')	{   
         bootbox.confirm({
        	    message: "Are you sure to set the status to <b>Completed</b>?",
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
            	    	 $("#evaluation_period-add-form").submit();
        	    }
        	});
    	 }	
     else { $("#evaluation_period-add-form").submit(); }
  	 }
});


$(document).ready(function() {
	 $('#example1').on('click', '.btn-delete', function(e) { 
	  var $form = $(this).closest('form');
	 //   e.preventDefault();
	   
	  $('#confirm').modal({
	      backdrop: 'static',
	      keyboard: false
	  })
	  .on('click', '#delete', function(e) {
	      $form.trigger('submit');
	    }); 
	});

	 
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
