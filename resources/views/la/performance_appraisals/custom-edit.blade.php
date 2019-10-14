@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url('/performance_appraisals') }}">Performance Appraisal</a>
@endsection
{{--@section("contentheader_description", $performance_appraisal->$view_col)--}}
@section("section", "Performance Appraisals")
@section("section_url", url('/performance_appraisals'))
@section("sub_section", "Edit")
@section("htmlheader_title", "Performance Appraisals Edit ")

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
        <li>Please fill all the fields.</li>
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
	<div id="page-content" class="profile2">
		<div class="bg-primary clearfix">
			<div class="col-md-11">
    			<div class="row">
    				<div class="col-md-8">
    					 <label class="name" style="text-transform:capitalize"> <b>Employee Name : </b>  {{ $employeeName }}  </label>
    					 <label class="name" style="text-transform:capitalize"> Appraisal Period :  {{ $appraisalPeriod }} ( {{$startDate }} - {{ $endDate }} )</label>
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
	<?php 
	  
	 
	if($evaluationStatus != 'Final-Review' && $evaluationStatus != 'Completed'  )
	    $nextSteps =  ($currentStep + 1); 
    else 
        $nextSteps =  ($currentStep);
 
        if(($currentStep == '2' || $currentStep == '7'   ) && ($evaluationStatus == 'Closed' || $evaluationStatus == 'Goal-Setting' || $evaluationStatus == 'Mid-Year-Revision') )  
            $nextSteps =  ($currentStep + 2); 
          
	$steps1 = '';
	$steps2 = '';
	$steps3 = '';
	$steps4 = '';
	
	$data1 = '';
	$data2 = '';
	$data3 = '';
	$data4 = ''; 
	
	$submit = 'disabled';
	
	if ($currentStep == "10" ||  $currentStep == "15" ||  $currentStep == "20"  ||  $currentStep == "25" )
	{
	    $steps1 = 'active';
	    $steps2 = 'disabled';
	    $steps3 = 'disabled';
	    $steps4 = 'disabled';
	    $data1 = '';
	    $data2 = '';
	    $data3 = '';
	    $data4 = '';
	     
	}
	
	if ($currentStep == "1" || $currentStep == "6" ||  $currentStep == "11" ||  $currentStep == "16" ||  $currentStep == "21" ||  $currentStep == "26")
	{
	    $steps1 = '';
	    $steps2 = 'active';
	    $steps3 = 'disabled';
	    $steps4 = 'disabled';
	    $data1 = 'data-toggle="tab"';
	    $data2 = 'data-toggle="tab"';
	    
	}
	else if(($currentStep == "2" || $currentStep == "7"  || $currentStep == "9" || $currentStep == "12" ||  $currentStep == "17" ||  $currentStep == "22" ||  $currentStep == "27" )&& ($evaluationStatus == 'Closed' || $evaluationStatus == 'Goal-Setting' || $evaluationStatus == 'Completed' || $evaluationStatus == 'Mid-Year-Revision'|| $evaluationStatus == 'Final-Review'))
	{
	   
	    $steps1 = '';
	    $steps2 = '';
	    $steps3 = 'active';
	    $steps4 = 'disabled';
	    
	    $data1 = 'data-toggle="tab"';
	    $data2 = 'data-toggle="tab"';
	    $data3 = 'data-toggle="tab"';
	    //$submit = '';
	}
	else if($currentStep == "3" || $currentStep == "8" || $currentStep == "13"  || $currentStep == "18" ||  $currentStep == "23" ||  $currentStep == "28" )  
	{
	    $steps1 = '';
	    $steps2 = '';
	    $steps3 = '';
	  
	    
	    $data1 = 'data-toggle="tab"';
	    $data2 = 'data-toggle="tab"';
	    $data3 = 'data-toggle="tab"';
	    
	    if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed') {
	        $steps4 = 'active';
	        $data4 = 'data-toggle="tab"';
	    }
	    else {
	        $steps3 = 'active';
	        $steps4 = 'disabled';
	        $submit = '';
	    }
	   
	}
	else if(($currentStep == "4" || $currentStep == "9" || $currentStep == "14" || $currentStep == "19" || $currentStep == "24" || $currentStep == "29"  || $currentStep == "30" )  && ($evaluationStatus == 'Goal-Setting' || $evaluationStatus == 'Mid-Year-Revision'|| $evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed' ))
	{
	    $steps1 = '';
	    $steps2 = '';
	    $steps3 = '';
	    $submit = '';
	    $data1 = 'data-toggle="tab"';
	    $data2 = 'data-toggle="tab"';
	    $data3 = 'data-toggle="tab"';
	    
	    if(($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed') && $performance_appraisal->status != "7") {
	        $steps4 = 'active';
	        $data4 = 'data-toggle="tab"';
	    }
	    else {
			$data4 = '';
	        $steps4 = 'disabled';
	        $steps3 = 'active';
	        $data3 = 'data-toggle="tab"';
	    }
	}
	else if($currentStep == "5")
	{
	    $steps1 = 'active';
	    $steps2 = 'disabled';
	    $steps3 = 'disabled';
	    $steps4 = 'disabled';
	  
	}  
	
	
	?>
	<?php $i = 0; $j=0; $k=0;?>
 {!! Form::model($performance_appraisal, ['route' => ['performance_appraisals.update', $performance_appraisal->id ], 'method'=>'PUT', 'id' => 'performance_appraisal-edit-form']) !!}
 	<input class="form-control" id ="steps" name="steps" type="hidden" value="<?php echo $nextSteps?>">
    <input class="form-control" id ="status" name="status" type="hidden" value="<?php echo $currentStatus?>">
 <div class="box">
	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		 @if(trim($performance_appraisal->section_1_title) != '')
		 	<li class="<?php echo $steps1 ; ?>"><a class="<?php echo $steps1 ; ?>"  role="tab" <?php echo $data1;?> href="#tab-goals-jobs" data-target="#tab-goals-jobs"><i class="fa fa-bars"></i>{{$performance_appraisal->section_1_title}}</a></li>
		 @endif
		 @if(trim($performance_appraisal->section_2_title) != '')
		 	<li class="<?php echo $steps2 ; ?>"><a class="<?php echo $steps2 ; ?>"  role="tab" <?php echo $data2;?> href="<?php echo ($steps2 == 'disabled') ? 'javascript:void(0);': '#tab-core-competencies';?>"  data-target="#tab-core-competencies"><i class="fa fa-clock-o"></i>{{$performance_appraisal->section_2_title}}</a></li>
		 @endif
		 @if(trim($performance_appraisal->section_3_title) != '')
		 	<li class="<?php echo $steps3 ; ?>"><a  class="<?php echo $steps3 ; ?>" role="tab" <?php echo $data3;?>  href="<?php echo ($steps3 == 'disabled') ? 'javascript:void(0);': '#tab-job-competenciess';?>"  data-target="#tab-job-competencies"><i class="fa fa-clock-o"></i>{{$performance_appraisal->section_3_title}}</a></li>
		 @endif
		 @if(trim($performance_appraisal->section_4_title) != '' && ($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')  )
			<li class="<?php echo $steps4 ; ?>"><a class="<?php echo $steps4 ; ?>" role="tab" <?php echo $data4 ;?> href="<?php echo ($steps4 == 'disabled') ? 'javascript:void(0);': '#tab-overall-rating';?>"   data-target="#tab-overall-rating"><i class="fa fa-clock-o"></i>{{$performance_appraisal->section_4_title}}</a></li>
		 @endif
	</ul>
    <div class="col-md-offset-10" style="padding-top:10px;color:red;"> **All fields are mandatory</div> 
    <div class="tab-content tab-validate">
         @if(trim($performance_appraisal->section_1_title) != ''  && $steps1 != 'disabled')
        		<div role="tabpanel" class="tab-pane fade in p20 <?php echo $steps1 ; ?>" id="tab-goals-jobs"  style="padding-top:0px">
        			<div class="tab-content">
        				 <div class="box box-solid">
                            	<div class="box-body">
                                	<h4 style="background-color:#48b0f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;border-radius: 4px;color:#fff">{{$performance_appraisal->section_1_title}}</h4>
                                    <div class="media">
                                   		<div class="media-body">
                                    		<div class="clearfix">
                                            	<p style="padding-left:13px;">{{$performance_appraisal->section_1_description}}</p>                    
                                                <div  style="display:none">
                                                     <div  class="col-md-2"><label for="goal_1"><b>Evaluation Period :</b></label></div>
                                                     <div class="col-md-6"><label for="goal_1"><b><?php echo $performance_appraisal->evaluation_period;?></b></label></div>
                                               </div>
                                            </div>
                                         </div>
                                    </div>
                                    <?php $j=0 ; 
                                    for ($i= 1; $i <= 10; $i++) {  
                                        if(( ( $performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                                     ?>
                                             <div class="col-md-10">
                                           		<div class="box box-primary">
                                               		<div class="box-header with-border">
                                                    	<h3 class="box-title">Goal {{ ++$j }} </h3>
                                                    </div> <!-- /.box-header -->
                                               		<div class="box-body" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                                                        
                                        					  <?php $params = [] ;?>	
                                        					   
                                        					    @la_input($module, "goal_$i", '',  '' , 'form-control',  $params,  'true') 
                                                				@la_input($module, "objective_$i", '',  '' , 'form-control',  $params,  'true') 
                                                				
                                                				@if( $employeeId == Auth::user()->context_id ||  ($employeeId != Auth::user()->context_id && $currentStatus >= 4) ) 
                						    						 <div class="form-group">
                														<label for="">Weightage :</label>
                														<div class="htmlbox"><?php echo $performance_appraisal->{'weightage_'.$i}; ?></div>
                						    						</div>	
                						    					@elseif( $employeeId != Auth::user()->context_id && $currentStatus < 4)
                						    						@la_input($module, "weightage_$i")
                						    					@endif
                						    					 
                						    					@if( $employeeId == Auth::user()->context_id && $currentStatus == 0)  
                						    						@la_input($module, "measurement_$i", '',  '' , 'form-control required',  '',  '')  
                						    					@elseif( $employeeId == Auth::user()->context_id && $currentStatus > 0)  
                						    						<div class="form-group">
        																<label for="">Measurement :</label>
        																<div class="htmlbox"><?php echo $performance_appraisal->{'measurement_'.$i}; ?></div>  
        						    								</div>
                						    					@endif
                						    					
                                        					    @if( $employeeId != Auth::user()->context_id && $currentStatus < 4)
                                                					
                                                				    @la_input($module, "measurement_$i", '',  '' , 'form-control required',  '',  '')  	
                                        				        @elseif( $employeeId != Auth::user()->context_id && $currentStatus > 4)
                						    						<div class="form-group">
        																<label for="">Measurement:</label>
        																<div class="htmlbox"><?php echo $performance_appraisal->{'measurement_'.$i}; ?></div>  
        						    								</div>
                						    					@endif
                                                				@if( $employeeId == Auth::user()->context_id && ( $evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed'))
                                                					@la_input($module, "comments_by_appraisee_$i", '',  '' , 'form-control required',  '',  '')  
                                                					@la_input($module, "rating_by_appraisee_$i")
                                                					<label id="rating_by_appraisee_<?php echo $i;?>-error" class="error" for="rating_by_appraisee_<?php echo $i;?>"></label>
                                            					@endif
                                    				 	 
                                        					   @if( $employeeId != Auth::user()->context_id && ( $evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed'))
                                            					   <div class="form-group">
                    													<label for="">Comments by Appraisee :</label>
                    														<div class="htmlbox"> <?php echo $performance_appraisal->{'comments_by_appraisee_'.$i};?>  </div>
                    						    				   </div>	
                    						    				   <div class="form-group">
                    						    						@la_input($module, "rating_by_appraisee_$i", '',  '' , 'form-control',  $params, 'true') 
                    						    						<label id="rating_by_appraisee_<?php echo $i;?>-error" class="error" for="rating_by_appraisee_<?php echo $i;?>"></label>
                    											</div>
                    						    				 
                                            					@la_input($module, "comments_by_appraiser_$i", '',  '' , 'form-control required',  '',  '')  
                                            					@la_input($module, "rating_by_appraiser_$i")
                                            					<label id="rating_by_appraiser_<?php echo $i;?>-error" class="error" for="rating_by_appraiser_<?php echo $i;?>"></label>
                                            				@endif
                                               		</div>  <!-- /.box-body -->
                                    	   		</div> <!-- /.box box-primary -->
                                    		</div> <!-- /.col-md-10 -->
                                   <?php    	
                                     }
                                    }
                                    ?>
                                    
   				 </div><!--  End of box-body -->
   				 <?php 
   							$stat = $currentStep;
   							if($stat == "1")
   							    $stat = "1";
   							else if($stat == "5")
   							    $stat = "6";
   							else if($stat == "10")
   							    $stat = "11";
   							else if($stat == "15")
   							    $stat = "16";  
   						    else if($stat == "20")
   						        $stat = "21";  
				            else if($stat == "25")
				                $stat = "26";
   							?>
   					 
   				 
   			  <input class="btn btn-success col-md-offset-9" name="btn1" id="btn1"  type="button" value="<?php echo $buttonText; ?>" onclick="fnsub(<?php echo $stat;?>, 1, 10)"/><br><br>
  		</div><!--  End of box box-solid-->
   </div><!--  End of tab-content -->
  </div><!--  End of tabpanel -->
 @endif 
 @if(trim($performance_appraisal->section_2_title) != ''  && $steps2 != 'disabled')
<!--  Start of Core Competencies -->
    
    <div role="tabpanel" class="tab-pane fade in p20 bg-white <?php echo $steps2 ; ?>" id="tab-core-competencies"  style="padding-top:0px">
    	<div class="tab-content">
        	 <div class="box box-solid">
       			<div class="box-body">
                	<h4 style="background-color:#48b0f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;border-radius: 4px;color:#fff">{{$performance_appraisal->section_2_title}}</h4>
                    <div class="media">
                   		<div class="media-body">
                    		<div class="clearfix">
                            	<p style="padding-left:13px;">
                            		{{$performance_appraisal->section_2_description}}
                                </p>                    
            
                            </div>
                        </div>
                    </div>
                   <?php 
                        $j=0 ; 
                        for ($i= 11; $i <= 17; $i++) {  
                            if(( ( $performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                    ?>
                         <div class="col-md-10">
                              <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Goal {{ ++$j }} </h3>
                                    </div><!-- /.box-header -->
                                    <div class="box-body" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                                                        
                                        					  <?php $params = [] ;?>	
                                        					   
                                        					    @la_input($module, "goal_$i", '',  '' , 'form-control',  $params,  'true') 
                                                				@la_input($module, "objective_$i", '',  '' , 'form-control',  $params,  'true') 
                                                				
                                                				 
                                        					   
                                                				@if( $employeeId == Auth::user()->context_id && ( $evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed'))
                                                					@la_input($module, "comments_by_appraisee_$i", '',  '' , 'form-control required',  '',  '')  
                                                					@la_input($module, "rating_by_appraisee_$i")
                                                					<label id="rating_by_appraisee_<?php echo $i;?>-error" class="error" for="rating_by_appraisee_<?php echo $i;?>"></label>
                                            					@endif
                                    				 	 
                                        					   @if( $employeeId != Auth::user()->context_id && ( $evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed'))
                                            					   <div class="form-group">
                    													<label for="">Comments by Appraisee :</label>
                    														<div class="htmlbox"> <?php echo $performance_appraisal->{'comments_by_appraisee_'.$i};?>  </div>
                    						    				   </div>	
                    						    				   <div class="form-group">
                    						    						@la_input($module, "rating_by_appraisee_$i", '',  '' , 'form-control',  $params, 'true') 
                    						    						<label id="rating_by_appraisee_<?php echo $i;?>-error" class="error" for="rating_by_appraisee_<?php echo $i;?>"></label>
                    											</div>
                    						    				 
                                            					@la_input($module, "comments_by_appraiser_$i", '',  '' , 'form-control required',  '',  '')  
                                            					@la_input($module, "rating_by_appraiser_$i")
                                            					<label id="rating_by_appraiser_<?php echo $i;?>-error" class="error" for="rating_by_appraiser_<?php echo $i;?>"></label>
                                            				@endif
                                               		</div>  <!-- /.box-body -->
                              </div>
                         </div>
                             <?php   
                                }
                            }
                             
                             ?>
                  
                  
                </div><!--  End of box-body -->
                
                    <?php 
                    $stat = $currentStep;
   							if($stat == "1")
   							    $stat = "2";
   							
   							else if($stat == "6")
   							    $stat = "7";
   							else if($stat == "11")
   							    $stat = "12";
						    else if($stat == "16")
						        $stat = "17";
					        else if($stat == "21")
					            $stat = "22";
				            else if($stat == "26")
				                $stat = "27";
   							?>
   							
   					  <input class="btn btn-success col-md-offset-9" name="btn2" id="btn2"  type="button" value="<?php echo $buttonText; ?>" onclick="fnsub(<?php echo $stat;?>, 1, 17)"/><br><br>  
    </div><!--  End of box box-solid-->
   </div><!--  End of tab-content -->
  </div><!--  End of tabpanel -->
 @endif 
 
 @if(trim($performance_appraisal->section_3_title) != ''  && $steps3 != 'disabled'  )
<!--  Start of Job Competencies -->
    
    <div role="tabpanel" class="tab-pane fade in p20 bg-white <?php echo $steps3 ; ?>" id="tab-job-competencies"  style="padding-top:0px">
    	<div class="tab-content">
        	 <div class="box box-solid">
       			<div class="box-body">
                	<h4 style="background-color:#48b0f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;border-radius: 4px;color:#fff">{{$performance_appraisal->section_3_title}}</h4>
                    <div class="media">
                   		<div class="media-body">
                    		<div class="clearfix">
                            	<p style="padding-left:13px;">
                            		{{$performance_appraisal->section_3_description}}
                                </p>                    
            
                            </div>
                        </div>
                    </div>
                   <?php 
                        $j=0 ; 
                        for ($i= 18; $i <= 20; $i++) {  
                            if(( ( $performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                    ?>
                         <div class="col-md-10">
                              <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Goal {{ ++$j }} </h3>
                                    </div><!-- /.box-header -->
                                    <div class="box-body" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                                                        
                                        					  <?php $params = [] ;?>	
                                        					   
                                        					    @la_input($module, "goal_$i", '',  '' , 'form-control',  $params,  'true') 
                                                				@la_input($module, "objective_$i", '',  '' , 'form-control',  $params,  'true') 
                                                				 
                						    					 
                						    					 
                                                				@if( $employeeId == Auth::user()->context_id && ( $evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed'))
                                                					@la_input($module, "comments_by_appraisee_$i", '',  '' , 'form-control required',  '',  '')  
                                                					@la_input($module, "rating_by_appraisee_$i")
                                                					<label id="rating_by_appraisee_<?php echo $i;?>-error" class="error" for="rating_by_appraisee_<?php echo $i;?>"></label>
                                            					@endif
                                    				 	 
                                        					   @if( $employeeId != Auth::user()->context_id && ( $evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed'))
                                            					   <div class="form-group">
                    													<label for="">Comments by Appraisee :</label>
                    														<div class="htmlbox"> <?php echo $performance_appraisal->{'comments_by_appraisee_'.$i};?>  </div>
                    						    				   </div>	
                    						    				   <div class="form-group">
                    						    						@la_input($module, "rating_by_appraisee_$i", '',  '' , 'form-control',  $params, 'true') 
                    						    						<label id="rating_by_appraisee_<?php echo $i;?>-error" class="error" for="rating_by_appraisee_<?php echo $i;?>"></label>
                    											</div>
                    						    				 
                                            					@la_input($module, "comments_by_appraiser_$i", '',  '' , 'form-control required',  '',  '')  
                                            					@la_input($module, "rating_by_appraiser_$i")
                                            					<label id="rating_by_appraiser_<?php echo $i;?>-error" class="error" for="rating_by_appraiser_<?php echo $i;?>"></label>
                                            				@endif
                                               		</div>  <!-- /.box-body -->
                              </div>
                         </div>
                             <?php   
                                }
                            }
                             
                             ?>
                  
                  
                </div><!--  End of box-body -->
                
                    <?php 
                    $stat = $currentStep;
                    if ($stat == "2")
                        $stat = "3";
                        else if($stat == "7")
                            $stat = "8";
                            else if($stat == "12")
                                $stat = "13";
                               // else if($stat == "13")
                                //    $stat = "14";
                                    else if($stat == "17")
                                        $stat = "18";
                                        else if($stat == "18")
                                            $stat = "19";
                                            else if($stat == "22")
                                                $stat = "23";
                                                else if($stat == "27")
                                                    $stat = "28";
                                                    ?>
   					   
   					      <div class='row'>
   					  	   @if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
       					       <input class="btn btn-success  col-md-offset-8" name="btn3" id="btn3"  type="button" value="<?php echo $buttonText; ?>" onclick="fnsub(<?php echo $stat;?>, 1, 20)"/><br><br>
       					   @else
       					       <input class="btn btn-success  col-md-offset-8" name="btn3" id="btn3"  type="button" value="SAVE" onclick="fnsub(<?php echo $stat;?>, 1, 20)"/>
       					   @endif
       					  
           					  @if($evaluationStatus != 'Final-Review' && $evaluationStatus != 'Completed'  && ($currentStep == 2 || $currentStep == 3 || $currentStep == 4 || $currentStep == 7 || $currentStep == 8 || $currentStep == 12 || $currentStep == 13 || $currentStep == 14))  
           					  <?php if($stat == "7")
   							    $stat = "9"; ?>
           					  <input id ="complete1" class="complete btn btn-success <?php echo $submit;?>" type="submit" value="<?php echo $submitButtonText;?>">
           					  @endif
       					  </div>
       					  <br><br>
   						 
   							
   					  
    </div><!--  End of box box-solid-->
   </div><!--  End of tab-content -->
  </div><!--  End of tabpanel -->
 @endif 
 
    
 
      @if(trim($performance_appraisal->section_4_title) != ''  && $steps4 != 'disabled' )
<!--  Start of Overall Rating -->

    <div role="tabpanel" class="tab-pane fade in p20 bg-white <?php echo $steps4; ?>" id="tab-overall-rating"  style="padding-top:0px">
    	<div class="tab-content">
    	 @if(($evaluationStatus == "Final-Review"   || $evaluationStatus == 'Completed' ||  $evaluationStatus == 'Goal-Setting' ) ) 
        	<div class="box box-solid">
        		<div class="box-body">
    				<h4 style="background-color:#48b0f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;border-radius: 4px;color:#fff">{{$performance_appraisal->section_4_title}}</h4>
        			<div class="media">
                   		<div class="media-body">
                    		<div class="clearfix">
                            	<p style="padding-left:13px;">{{$performance_appraisal->section_4_description}}
                             </div>
                        </div>
       				</div>
                        <div class="col-md-10">
                        	<div class="box box-primary">
                           		<div class="box-header with-border">
                                	<!--  <h3 class="box-title">Goal </h3>-->
                                </div>
                                 
                                <!-- /.box-header -->
                           		<div class="box-body">
                                    <div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                    					<div class="row">
                    					 	<div class="form-group">
                        						<div class="col-md-3"><label for="goal_1"><b>{{$performance_appraisal->section_1_title}}</b></label></div>
                        						<!-- <div class="col-md-10">Job Knowledge</div> -->
                    						</div>
                    					</div> 
                    				 </div>		 
        							<table class="table table-bordered">
                                    	<tbody>
                                    		<tr>
                                              <th style="width: 10px">#</th>
                                              <th style="width: 10px">Goal</th>
                                              <th style="width: 30px">Weightage</th>
                                              @if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                                              <th style="width: 30px">Rating by Appraisee</th>
                                              @endif
                                              @if( $employeeId != Auth::user()->id)
                                                  @if($evaluationStatus == 'Final-Review'|| $evaluationStatus == 'Completed')
                                                  	<th style="width: 30px">Rating by Appraiser</th>
                                                  @endif	
                                              @endif
                                             
                                            </tr>
                                            <?php $y = 0; 
                                            for ($i= 1; $i <= 10; $i++) {  
                                                
                                                 if(( ($performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                                                ?>
                                                <tr>
                                                  <td>{{ ++$y}}</td>
                                                  <td><?php echo $performance_appraisal->{'goal_'.$i};?></td>
                                                  <td><?php echo $performance_appraisal->{'weightage_'.$i};?></td>
                                                  @if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                                                      	<td><?php echo $performance_appraisal->{'rating_by_appraisee_'.$i};?></td>
                                                      @endif	
                                                  @if( $employeeId != Auth::user()->id)
                                                     @if($evaluationStatus == 'Final-Review'|| $evaluationStatus == 'Completed')
                                                  		<td><?php echo $performance_appraisal->{'rating_by_appraiser_'.$i};?></td>
                                                  	@endif	
                                                  @endif
                                                 
                                                 
                                                </tr>
                                            <?php } ?><!-- End if -->
                                          
                                           <?php } ?> <!-- End for -->
                                             
                                      </tbody>
                                    </table>
                   				</div>
                                        <div class="box-body">
                                            <div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                            					<div class="row">
                            					 	<div class="form-group">
                                						<div class="col-md-3"><label for="goal_1"><b>{{$performance_appraisal->section_2_title}}</b></label></div>
                                						<!-- <div class="col-md-10">Job Knowledge</div> -->
                            						</div>
                            					</div> 
                            				</div>		 
                            				<table class="table table-bordered">
                                        		<tbody>
                                        			<tr>
                                                      <th style="width: 10px">#</th>
                                                      <th style="width: 10px">Goal</th> 
                                                      <th style="width: 30px">Weightage</th>
                                                      @if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                                                      <th style="width: 30px">Rating by Appraisee</th>
                                                      @endif
                                                      @if( $employeeId != Auth::user()->id)
                                                      	 @if($evaluationStatus == 'Final-Review'|| $evaluationStatus == 'Completed')
                                                      	 	 <th style="width: 30px">Rating by Appraiser</th>
                                                      	 @endif
                                                      @endif
                                                    </tr>
                                                      <?php $y = 0; 
                                                        for ($i= 11; $i <= 17; $i++) {  
                                                            
                                                             if(( ($performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                                                            ?>
                                                            <tr>
                                                              <td>{{ ++$y}}</td>
                                                              <td><?php echo $performance_appraisal->{'goal_'.$i};?></td>
                                                              <td><?php echo $performance_appraisal->{'weightage_'.$i};?></td>
                                                              @if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                                                                  	<td><?php echo $performance_appraisal->{'rating_by_appraisee_'.$i};?></td>
                                                                  @endif	
                                                              @if( $employeeId != Auth::user()->id)
                                                                 @if($evaluationStatus == 'Final-Review'|| $evaluationStatus == 'Completed')
                                                              		<td><?php echo $performance_appraisal->{'rating_by_appraiser_'.$i};?></td>
                                                              	@endif	
                                                              @endif
                                                            </tr>
                                                        <?php } ?><!-- End if -->
                                                      
                                                       <?php } ?> <!-- End for -->
                                             
                                                   
                                       			</tbody>
                                       		</table>
                              			</div> <!-- /.box-body -->
                              			
                   				        <div class="box-body">
                                            <div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                            					<div class="row">
                            					 	<div class="form-group">
                                						<div class="col-md-3"><label for="goal_1"><b>{{$performance_appraisal->section_3_title}}</b></label></div>
                                						<!-- <div class="col-md-10">Job Knowledge</div> -->
                            						</div>
                            					</div> 
                            				</div>		 
                            				<table class="table table-bordered">
                                        		<tbody>
                                        			<tr>
                                                      <th style="width: 10px">#</th>
                                                      <th style="width: 10px">Goal</th> 
                                                      <th style="width: 30px">Weightage</th>
                                                      @if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                                                      <th style="width: 30px">Rating by Appraisee</th>
                                                      @endif
                                                      @if( $employeeId != Auth::user()->id)
                                                      	 @if($evaluationStatus == 'Final-Review'|| $evaluationStatus == 'Completed')
                                                      	 	 <th style="width: 30px">Rating by Appraiser</th>
                                                      	 @endif
                                                      @endif
                                                    </tr>
                                                      <?php $y = 0; 
                                                        for ($i= 18; $i <= 20; $i++) {  
                                                            
                                                             if(( ($performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                                                            ?>
                                                            <tr>
                                                              <td>{{ ++$y}}</td>
                                                              <td><?php echo $performance_appraisal->{'goal_'.$i};?></td>
                                                              <td><?php echo $performance_appraisal->{'weightage_'.$i};?></td>
                                                              @if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                                                                  	<td><?php echo $performance_appraisal->{'rating_by_appraisee_'.$i};?></td>
                                                                  @endif	
                                                              @if( $employeeId != Auth::user()->id)
                                                                 @if($evaluationStatus == 'Final-Review'|| $evaluationStatus == 'Completed')
                                                              		<td><?php echo $performance_appraisal->{'rating_by_appraiser_'.$i};?></td>
                                                              	@endif	
                                                              @endif
                                                            </tr>
                                                        <?php } ?><!-- End if -->
                                                      
                                                       <?php } ?> <!-- End for -->
                                              
                                       			</tbody>
                                       		</table>
                              			</div> <!-- /.box-body -->
                              			
                                        <div class="box-body">
                                        	 <!--<div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                            					 <div class="row">
                            					 	<div class="form-group">
                                						 <div class="col-md-3"><label for="goal_1"><b>Overall Comments & Rating</b></label></div>  
                                						  <div class="col-md-10">Job Knowledge</div> 
                            						</div>
                            					 </div> 
                            					
                        						
                        					</div>	 -->	 
                            				@if( $employeeId == Auth::user()->context_id  &&   ($evaluationStatus == "Final-Review"   || $evaluationStatus == 'Completed' )) 
                                					@la_input($module, 'overall_comments_by_appraisee')
                                					<div class="form-group">
                            							<label for="overall_rating_by_appraisee">Overall Rating By Appraisee :</label>
            						 					<input id="overall_rating_by_appraisee" class="form-control valid" readonly ="true" placeholder="Enter Overall Rating By Appraisee" data-rule-minlength="1" required="1" name="overall_rating_by_appraisee" type="text" value="<?php echo $overallRatingByAppraisee?>" aria-required="true" aria-invalid="false">
            						                </div>
                        					@endif
                        					@if( $employeeId != Auth::user()->context_id  &&   ($evaluationStatus == "Final-Review"   || $evaluationStatus == 'Completed' ))
                        							 <div class="form-group">
                        							 	<label for="overall_comments_by_appraiser">Overall Comments by Appraisee :</label>
                        							 	<textarea class="form-control valid" readonly ="true"  placeholder="Enter Overall Comments by Appraisee" required="1" cols="30" rows="3" name="overall_comments_by_appraisee" aria-required="true" aria-invalid="false"><?php echo $performance_appraisal->overall_comments_by_appraisee ;?></textarea>
                        							 </div>
                            							 
                            							<div class="form-group">
                            								<label for="overall_rating_by_appraisee">Overall Rating By Appraisee :</label>
            						 						<input id="overall_rating_by_appraisee" class="form-control valid" readonly ="true" placeholder="Enter Overall Rating By Appraisee" data-rule-minlength="1" required="1" name="overall_rating_by_appraisee" type="text" value="<?php echo $overallRatingByAppraisee?>" aria-required="true" aria-invalid="false">
            						               		 </div>
                            					     @la_input($module, 'overall_comments_by_appraiser')
                        							 <div class="form-group">
                            							<label for="overall_rating_by_appraiser">Overall Rating By Appraiser :</label>
            						 					<input id="overall_rating_by_appraiser" class="form-control valid" readonly ="true" placeholder="Enter Overall Rating By Appraiser" data-rule-minlength="1" required="1" name="overall_rating_by_appraiser" type="text" value="<?php echo $overallRatingByAppraiser?>" aria-required="true" aria-invalid="false">
            						                </div>
                            				 @endif
            				 	       </div><!-- /.box-body -->
                                 </div><!-- /.box-body -->
     		        		</div>
          				</div>	 
    				</div>
                    <?php 
                            $stat = $currentStep;
   							if($stat == "3")
   							$stat = "4";
   							else if($stat == "8")
   							    $stat = "9";
						    else if($stat == "13")
						        $stat = "14";
					        else if($stat == "18")
					            $stat = "19";
				            else if($stat == "23")
				                $stat = "24";
				            else if($stat == "28")
				                $stat = "29";
				            else if($stat == "30")
				                    $stat = "30";
                    ?></div>
                    <div>
   					  <input class="btn btn-success col-md-offset-8" name="btn2" id="btn2"  type="button" value="Save" onclick="fnsub(<?php echo $stat;?>, '1', '20')"/>
   					  <input id ="complete" class="complete btn btn-success <?php echo $submit;?>" type="submit" value="<?php echo $submitButtonText;?>">
    				</div>
    			
    		</div>
    		@endif
        </div> 		
	</div> 
	@endif
    <!--  End of Overall Rating -->
<div class="modal" tabindex="-1" role="dialog" id="myconfirmation">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Employee Performance Appraisal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -26px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure to submit?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="formSubmit">  <?php echo $submitButtonText;?></button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
  

 
  
 {!! Form::close() !!}
 		 

@endsection

@push('scripts')
<script>
$(".complete").click(function(event ) {
	if(checkWeightageTotal()) {
    	var i;
    	for (i = 1; i<= 20; i++) {
    		var field = "rating_by_appraiser_"+i;
    	    var $radio = $('input:radio[name='+field+']');
    	    $radio.addClass("required");
    	}  
    	event.preventDefault();
    	if($("#performance_appraisal-edit-form").valid()) 
    	$('#myconfirmation').modal('show');
	} 
	else {
		
	}	
}); 
$("#formSubmit").click(function(event ) {
	if(checkWeightageTotal() && $("#performance_appraisal-edit-form").valid()) {
        var status = <?php  echo $nextStatus;?>;
       
        $('#myconfirmation').modal('hide');
    	 
    	if(parseInt($("#steps").val()) != 30) {
    	var steps = parseInt($("#steps").val())+1;
    	$("#steps").val(steps);
    	}
       
    	$("input[name='status']").val(status);  
        $("#performance_appraisal-edit-form").submit();
	}    else {
		  $('#myconfirmation').modal('hide');
		}	
		
}); 
	 
</script>
<script type="text/javascript">
$(function() {
	$('#performance_appraisal-edit-form').validate({
	 
        ignore: [],
        errorPlacement: function() {},
        submitHandler: function(form) {
        	if ($(form).valid()) 
                form.submit(); 
            return false; // prevent normal form posting
        },
        invalidHandler: function(e, validator) {
        	/* for (var i=0;i<validator.errorList.length;i++){
                console.log(validator.errorList[i]);
            } */
            var id = '';
            var errorCount = 0;       
            setTimeout(function() {
                $('.nav-tabs a small.required').remove();
                var validatePane = $('.tab-content.tab-validate .tab-pane:has(input.error, textarea.error)').each(function() {
                      id = $(this).attr('id'); 
                      if(errorCount == 0 )   {     
                     	 bootbox.alert("Please fill all required fields."); 
                     	 errorCount++;
                      }
                    $('.nav-tabs').find('a[href^="#' + id + '"]').append(' <small class="required">***</small>');
                   
                });
            });   
           
      
        },
    });
});

function fnsub(steps, from, to) {
	var steps = <?php echo $stat?>;
	if(checkWeightageTotal()) {
    	var i;
    	for (i = from; i<= to; i++) {
    		var field = "rating_by_appraisee_"+i;
    	    var $radio = $('input:radio[name='+field+']');
    	    $radio.addClass("required");
    	}  
    	   $("#steps").val(steps);
    	   $("#performance_appraisal-edit-form").submit();   
	}
}


function checkWeightageTotal() { 
	var j, fieldCount;
	fieldCount = 0;
	var totalWeightage = 0;
	//validating Weightage total. Section 1 total weightage should be equal to 100
	for (j=1; j<=10; j++) {
		if($("input[name=weightage_"+j+"]").length > 0) {
     		totalWeightage += parseInt($("input[name=weightage_"+j+"]").val());
     		fieldCount++;
		}
	}
	if (totalWeightage != 100  && fieldCount > 0) {
    	bootbox.alert("Please make sure the total weightage is equal to 100 in the Goals & Job Results section.");
    	$("input[name=weightage_1]").focus();
        return false;
	}
	return true;
}


jQuery.validator.addClassRules("required", {
	  required: true,
	  normalizer: function(value) {
	    return $.trim(value);
	  }
	});
  
</script>
@endpush
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
.fixed-side-content ul {
	    padding:0px;
}
.fixed-side-content ul li {
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
 