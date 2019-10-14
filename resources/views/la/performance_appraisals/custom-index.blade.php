@extends("la.layouts.app")

@section("contentheader_title", "Performance Appraisals")
@section("contentheader_description", "Performance Appraisals listing")
@section("section", "Performance Appraisals")
@section("sub_section", "Listing")
@section("htmlheader_title", "")

@section("headerElems")
@la_access("Performance_Appraisals", "create")
	<!-- <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Performance Appraisal</button> -->
@endla_access
@endsection

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

@if(session('success'))
	<div class="alert alert-success">
        <ul class="list-unstyled">
    		<li>{{session('success')}}</li>
    	</ul>
    </div>	
@endif

<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body"> 
	 <label>Appraisal Period </label>  {!! Form::select('evaluationItems', $evaluationItems, null ) !!} 
	  
    	<form id="frm-example" action="#" method="POST">
        	<input type="hidden" id='hdnIds' name="hdnIds">
        	{{--@if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')--}}
        	 @if(  ( Entrust::hasRole('SUPER_ADMIN') &&   Entrust::hasRole('HR_MANAGER'))) 
        		<div class="pull-right"><input type="submit" id="approve" class="btn btn-success" value="Complete"></div><br><br>
             @endif 
           
            
            	<table id="example1" class="table table-bordered">
            		<thead>
            		<tr class="success">
            		   
            		 	@foreach( $listing_cols_data_table as $col )
                		 	@if( ucfirst($col) == "Id")
                		 		<th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
                		 	@else
                				<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
                			@endif
            			@endforeach
            			@if($show_actions)
            				<th>Actions</th>
            			@endif
            		</tr>
            		</thead>
            		<tbody></tbody>
    			</table>
    	 </form>
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

 
@endsection
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
var _token = $('input[name="_token"]').val();
$(document).ready(function (){   
	 
	 var table = 	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url('/performance_appraisal_dt_ajax/'.$evaluationId)}}",
        'type': 'GET',
        "order": [[ 1, "asc" ]],
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		 @if(  (!Entrust::hasRole('SUPER_ADMIN') &&  !Entrust::hasRole('HR_MANAGER'))) 
			columnDefs: [ { orderable: false, targets: [0] }, {visible:false, targets:[0]}],
		 @else 	columnDefs: [ { orderable: false, targets: [0] }],
		 @endif 
		//columnDefs: [ { orderable: false, targets: [0] }, {visible:false, targets:[7]}],
	});
   	$("#performance_appraisal-add-form").validate({
    });
 


// Handle click on "Select all" control
$('#example-select-all').on('click', function(){
   // Check/uncheck all checkboxes in the table
   var rows = table.rows({ 'search': 'applied' }).nodes();
   $('input[type="checkbox"]', rows).prop('checked', this.checked);
});

// Handle click on checkbox to set state of "Select all" control
$('#example1 tbody').on('change', 'input[type="checkbox"]', function(){
   // If checkbox is not checked
   if(!this.checked){
      var el = $('#example-select-all').get(0);
      // If "Select all" control is checked and has 'indeterminate' property
      if(el && el.checked && ('indeterminate' in el)){
         // Set visual state of "Select all" control 
         // as 'indeterminate'
         el.indeterminate = true;
      }
   }
});

//Handle drop down evalution period value change
$( "select[name='evaluationItems']" ).change(function() {
  // Check input( $( this ).val() ) for validity here
   var optionSelected = $("option:selected", this).value;
   var valueSelected = this.value;
   // table.ajax.reload();
   var link = "{{ url('/performance_appraisal_dt_ajax/')}}";
  
   table.ajax.url(link+"/"+valueSelected).load();
});

 
$('#approve').on('click', function(e){ 
	e.preventDefault();
	var form = this;
    var len = $('input.record:checked').length;
    if(len > 0) {
        var ids = "";
     // Iterate over all checkboxes in the table
    table.$('input[type="checkbox"]').each(function(){  
       // If checkbox doesn't exist in DOM
       if($.contains(document, this)){ 
          // If checkbox is checked
          if(this.checked){ 
           //   ids =  $('#hdnIds').val();
              ids += this.value + ",";
              
             // Create a hidden element 
             /*  $(form).append(
                $('<input>')
                   .attr('type', 'text')
                   .attr('name', this.name)
                   .val(this.value)
             );   */
          }
       } 
    });
    ids = ids.replace(/,\s*$/, "");
    $('#hdnIds').val(ids);
    $.ajaxSetup(
    		{
    			headers:
    		    {
    		        'X-CSRF-Token': $('input[name="_token"]').val()
    		    }
    		});
    $.ajax({
            type: "POST",
            url: "{{ url('/performance_appraisal_update_status_ajax') }}",
            data: {ids: ids},
            success: function( msg ) {
            	$('#example-select-all').prop("checked", false);
            	table.ajax.reload();
            	alert('Profile has been successfully completed.');
                console.log(msg);
                
            }
   });
  } 
  else {
	 	bootbox.alert("Please select at least one record.");	
		e.preventDefault();
  }
 });

 
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
</script>
@endpush
