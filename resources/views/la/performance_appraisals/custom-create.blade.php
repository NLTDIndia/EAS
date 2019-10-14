@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url('/performance_appraisals') }}">Performance Appraisal</a> 
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

 
@la_access("Performance_Appraisals", "create")
<div id="page-content" class="profile2">
	<div class="bg-primary clearfix">
			<div class="col-md-11">
    			<div class="row">
    				<div class="col-md-8">
    					 <label class="name" style="text-transform:capitalize"> <b>Employee Name : </b>  {{ $employeeName }}  </label>
    					 <label class="name" style="text-transform:capitalize"> Appraisal Period :  {{ $appraisalPeriod }} ( From {{$evaluationStartAt}} to  {{$evaluationEndAt}}) </label>
    					 <div class="row stats"></div>
    					 <p class="desc"> </p>
    				</div>
    				<div class="col-md-4">
    					 <label class="name" style="text-transform:capitalize"> Department :   {{ $deptName }} </label> 
						 <label class="name" style="text-transform:capitalize"> Manager : {{ $managerName }} </label>
    				</div>
    			</div>
			</div>
			<div class="col-md-1"></div> 
			<div class="col-md-1 actions"></div>
 	</div>
 </div>	
<?php $i = 0;?>
{!! Form::open(['action' => 'LA\Performance_AppraisalsController@store', 'id' => 'performance_appraisal-add-form']) !!}
<div class="box">
<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		  @if(trim($tempData['Section_1_title']) != '')
			<li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-goals-jobs" data-target="#tab-goals-jobs"><i class="fa fa-bars"></i>{{$tempData['Section_1_title']}}</a></li>
		 @endif
		 @if(trim($tempData['Section_2_title']) != '')
			<li class="disabled"><a role="tab"   href="javascript:void(0);" data-target="#tab-core-competencies"><i class="fa fa-clock-o"></i>{{$tempData['Section_2_title']}}</a></li>
		 @endif
		 @if(trim($tempData['Section_3_title']) != '')
			<li class="disabled"><a role="tab"  href="javascript:void(0);" data-target="#tab-job-competencies"><i class="fa fa-clock-o"></i>{{$tempData['Section_3_title']}}</a></li>
		 @endif
		 @if(trim($tempData['Section_4_title']) != '' && $evaluationStatus == 'Final-Review')
			  <li class="disabled"><a role="tab"   href="javascript:void(0);" data-target="#tab-overall-rating"><i class="fa fa-clock-o"></i>{{$tempData['Section_4_title']}}</a></li>  
		 @endif
	</ul>
	<div class="col-md-offset-10" style="padding-top:10px;color:red;"> **All fields are mandatory</div> 
	<div class="tab-content tab-validate">
	 @if(trim($tempData['Section_1_title']) != '')
		<div role="tabpanel" class="tab-pane active fade in p20 bg-white" id="tab-goals-jobs"  style="padding-top:0px">
			<div class="tab-content">
        	<div class="box box-solid">
				<div class="box-body">
					<div class="row">
                    	<div class="form-group" style="display:block">
                        	<div class="col-md-6"><input type="hidden" name= "start_at" id="start_at" value ='{{$evaluationStartAt}}'></div>
                            <div class="col-md-6"><input type="hidden" name= "end_at" id="end_at" value ='{{$evaluationEndAt}} '></div>
                        </div>
                                   </div> 
    				<h4 style="background-color:#48b0f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;border-radius: 4px;color:#fff"> {{$tempData['Section_1_title']}}</h4>
        			<div class="media">
                   		<div class="media-body">
                    		<div class="clearfix">
                            	<p style="padding-left:13px;"> {{$tempData['Section_1_description']}}  </p>                    
            
                            </div>
                            <div class="row" style="display:none"> 
                					 	<div class="form-group">
                    						 <div>
                    							@la_input($module, 'section_1_title',$tempData['Section_1_title'])
                    							@la_input($module, 'section_1_description',$tempData['Section_1_description'])
                    							@la_input($module, 'section_2_title',$tempData['Section_2_title'])
                    							@la_input($module, 'section_2_description',$tempData['Section_2_description'])
                    							@la_input($module, 'section_3_title',$tempData['Section_3_title'])
                    							@la_input($module, 'section_3_description',$tempData['Section_3_description'])
                    							@la_input($module, 'section_4_title',$tempData['Section_4_title'])
                    							@la_input($module, 'section_4_description',$tempData['Section_4_description'])
                    							@for ($i= 1; $i <= 10; $i++)
                        							@la_input($module, "weightage_$i", $tempData["Weightage_$i"]) 
                    					 			@la_input($module, "manager_only_$i", $tempData["Manager_only_$i"]) 
     											@endfor
     											@for ($i= 11; $i <= 20; $i++)
                        							@la_input($module, "goal_$i", $tempData["Goal_$i"]) 
                    				                @la_input($module, "objective_$i", $tempData["Objective_$i"]) 
                    					 			@la_input($module, "weightage_$i", $tempData["Weightage_$i"]) 
                    					 			@la_input($module, "manager_only_$i", $tempData["Manager_only_$i"]) 
     											@endfor
                					 
                    						</div>
                						</div>
                					</div> 
                        </div>
       				</div>
  <?php  $leadsWeightage = 0; ?>
  @for ($i= 1; $i <= 10; $i++)
  	@if(( ( trim($tempData["Goal_$i"]) != 'N/A' &&  trim($tempData["Goal_$i"]) != '' ) &&  $tempData["Manager_only_$i"] == "No" ) || ( trim($tempData["Manager_only_$i"]) == "Yes" && $memberCount > 0 ))
          <?php if ($memberCount > 0 && $tempData["Manager_only_$i"]  == "Yes" ) 
                    $leadsWeightage += $tempData["Weightage_$i"];
          ?>  
          <div class="col-md-10">
                           <div class="box box-primary">
                           		<div class="box-header with-border">
                                	<h3 class="box-title"> Goal  <?php echo $i;?></h3>
                                </div>
                                 
                				 
                                <!-- /.box-header -->
                           		<div class="box-body">
                                    <div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                    				<?php $params = [] ;?>	
                    				@la_input($module, "goal_$i", $tempData["Goal_$i"],  '' , 'form-control',  $params,  'true') 
                    				@la_input($module, "objective_$i", $tempData["Objective_$i"],  '' , 'form-control',  $params,  'true') 
                    				@la_input($module, "weightage_$i", $tempData["Weightage_$i"],  '' , 'form-control',  $params,  'true') 
                    				@la_input($module, "measurement_$i", '',  '' , 'form-control required',  '',  '') 
                				 	@if($evaluationStatus == 'Final-Review' ||  $evaluationStatus == 'Completed')
                 						@la_input($module, "comments_by_appraisee_$i", '',  '' , 'form-control required',  '',  '')  
                						@la_input($module, "rating_by_appraisee_$i")
                						<label id="rating_by_appraisee_<?php echo $i;?>-error" class="error" for="rating_by_appraisee_<?php echo $i;?>"></label>
                					@endif
                					
                					 
                					
                				    @if( $action != "create")
                    					@la_input($module, "comments_by_appraiser_$i", '',  '' , 'form-control required',  '',  '') 
                    					@la_input($module, "rating_by_appraiser_$i")
                					@endif
                							</div> 
                    	   </div>  <!-- /.box-body -->
                    	   </div>
                    	   </div>
                    	   @else 
                    	   <div class="row" style="display:none"> 
                    	   @la_input($module, "goal_$i", $tempData["Goal_$i"],  '' , 'form-control',  $params,  'true') 
                    	   @la_input($module, "objective_$i", $tempData["Objective_$i"],  '' , 'form-control',  $params,  'true') 
                    	   </div>
                    	   
   @endif 
   
 @endfor      				
    {!! Form::submit( 'Save', ['class'=>'btn btn-success col-md-offset-9']) !!}
		</div>	</div>
			</div>
		</div><!--  End of Tab1 -->
		
		@endif
		@if(trim($tempData['Section_2_title']) != '')
			<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-core-competencies">
			 
			</div> <!--  End of tab2 -->
		@endif
		@if(trim($tempData['Section_3_title']) != '')	
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-job-competencies">
			 
		</div> <!-- End of tab3 -->
		@endif
		@if(trim($tempData['Section_4_title']) != '')
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-overall-rating">
			 
		</div><!--  End of Tab3 -->
		@endif
		
    	{!! Form::close() !!}               
 </div>		
 
 </div>
    	
 			 
@endsection	
@endla_access
@push('scripts')
<script>
$(function() {
	  var i;
	  for (i = 1; i<= 10; i++) {
		var field = "rating_by_appraisee_"+i;
	    var $radio = $('input:radio[name='+field+']');
	    $radio.addClass("required");
	  }  
	$('#performance_appraisal-add-form').validate({
	    ignore: [],
        errorPlacement: function() {},
        submitHandler: function(form) {
        	if ($(form).valid()) 
                form.submit(); 
            return false; // prevent normal form posting
        },
        invalidHandler: function() {
            setTimeout(function() {
                $('.nav-tabs a small.required').remove();
                var validatePane = $('.tab-content.tab-validate .tab-pane:has(input.error, textarea.error)').each(function() {
                    var id = $(this).attr('id');
                    $('.nav-tabs').find('a[href^="#' + id + '"]').append(' <small class="required">***</small>');
                });
            });            
        },
    });

    var leadsWeightage   = <?php echo $leadsWeightage;?>;
    var goal_1_weightage = $( 'input[name=weightage_1]' ).val();
    $( "input[name$='weightage_1']").val( goal_1_weightage - leadsWeightage);
    $("#htmlbox_weightage_1").text( goal_1_weightage - leadsWeightage);
        
});

 
jQuery.validator.addClassRules("required", {
	  required: true,
	  normalizer: function(value) {
	    return $.trim(value);
	  }
	});

 
</script>
<style>
 .fixed-side-content {
    position: fixed;
    top: 50%;
    right: 0px;
    background: #007bffc7;
    height: auto;
    padding: 1em 1em;
    z-index: 999;
}
.fixed-side-content ul{
	    padding:0px;
	  }
.fixed-side-content ul li{
	    list-style:none;
		color:#fff;
		font-size: 14px;
}
.fixed-side-content ul h5 {
    color: #fff;
} 
input.error {
    border-color: #f00 !important;
}

small.required {
    color:#f00;
}
</style>
@endpush
