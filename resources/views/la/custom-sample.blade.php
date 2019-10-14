@extends("la.layouts.app")

@section("contentheader_title", "Performance Appraisals")
@section("contentheader_description", "Performance Appraisals listing")
@section("section", "Performance Appraisals")
@section("sub_section", "Listing")
@section("htmlheader_title", "Performance Appraisals Listing")

@section("headerElems")
 
@endsection

@section("main-content")

 <div class="container" style="margin-top: 20px;">

    <div class="panel panel-primary">
        <div class="panel-heading">
            Bootstrap Tab + jQuery Validade
        </div>
        <div class="panel-body">
            <form action="" class="form-horizontal" id="validate">
                <ul class="nav nav-tabs nav-justified nav-inline">
                    <li class="active"><a href="#primary" data-toggle="tab">Contact Information</a></li>
                    <li><a href="#secondary" data-toggle="tab">Address Information</a></li>
                </ul>


                <div class="tab-content tab-validate" style="margin-top:20px;">
                    <div class="tab-pane active" id="primary">
                        <div class="form-group">
                            <label for="name" class="control-label col-md-2">Name</label> 
                            <div class="col-md-10">
                                <input type="text" name="name" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label col-md-2">E-mail</label> 
                            <div class="col-md-10">
                                <input type="email" name="email" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="secondary">
                        <div class="form-group">
                            <label for="zipcode" class="control-label col-md-2">Zip Code</label> 
                            <div class="col-md-10">
                                <input type="text" name="zipcode" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="control-label col-md-2">Address</label> 
                            <div class="col-md-10">
                                <input type="text" name="address" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="control-label col-md-2">City</label> 
                            <div class="col-md-10">
                                <input type="text" name="city" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-2 pull-right">
                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>
 
@push('styles')
<style>
input.error {
    border-color: #f00 !important;
}

small.required {
    color:#f00;
}
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function() {
    
    $('#validate').validate({
        ignore: [],
        errorPlacement: function() {},
        submitHandler: function() {
            alert('Successfully saved!');
        },
        invalidHandler: function() {
            setTimeout(function() {
                $('.nav-tabs a small.required').remove();
                var validatePane = $('.tab-content.tab-validate .tab-pane:has(input.error)').each(function() {
                    var id = $(this).attr('id');
                    $('.nav-tabs').find('a[href^="#' + id + '"]').append(' <small class="required">***</small>');
                });
            });            
        },
        rules: {
            name: 'required',
           
            zipcode: 'required',
            address: 'required',
            city: 'required'
        }
    });
    
});
var _token = $('input[name="_token"]').val();
$(document).ready(function (){   
 
   	$("#performance_appraisal-add-form").validate({
    });
 
 
}); 
</script>
@endpush
