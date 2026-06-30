// import './bootstrap';

$(document).ready(function(){



$('.delete-rec-btn').on('click',function(event){
    event.preventDefault();
    // console.log('working');
    var name = $(this).attr('data-name');
    var msg = $(this).attr('data-msg');
    var url = $(this).attr('data-url');

    console.log(name);
    $('.modal-title').html(name);
    $('.modal-body').html(msg);
    $('#delete-form').attr('action', url);

    $('#deleteModel').modal({show:true});

    // $('.modal-body').load(dataURL,function(){
    //     $('#myModal').modal({show:true});
    // });
});


$(".select_province").click(function () {

    var cities_url  = $(this).attr("data-url");
    var pid = $(this).attr("data-pid");
    console.log($(this).val());

    $('#pid-'+pid).html('<i class="fa fa-spin fa-spinner"></i>');


    if ($(this).is(":checked"))
        {

            $.ajax({
                url: cities_url,
                method: 'get',
                data: {
                    province_id: $(this).val(),
                },
                success: function(result){
                    console.log(result);
                    $('#pid-'+pid).html(result);
                }});

        } else
        {
            $('#pid-'+pid).html('');
        }

});


        // $(".select_city").click(function () {
        $( "#area_selection" ).on( "click", ".select_tehsil", function() {
            var centers_url  = $(this).attr("data-url");
            var cid = $(this).attr("data-cid");
            // console.log($(this).val());
            // return;

            $('#cid-'+cid).html('<i class="fa fa-spin fa-spinner"></i>');

            if ($(this).is(":checked"))
                {

                    $.ajax({
                        url: centers_url,
                        method: 'get',
                        data: {
                           tehsil_id: $(this).val(),
                        },
                        success: function(result){
                           console.log(result);
                           // $('#select_city').html(result);
                           // $('#select_city').prop("disabled", false);
                           $('#cid-'+cid).html(result);
                        }});

                } else
                {
                    $('#cid-'+cid).html('');
                }



        });


        $("#user_activity_location_old_rec").change(function () {
            var centers_url  = $(this).attr("data-url");
            $('#loader').html('<i class="fa fa-spin fa-spinner"></i>');
            $.ajax({
                url: centers_url,
                method: 'get',
                data: {
                    activity_location_id: $(this).val(),
                },
                success: function(result){
                    $('#load_user_center_old_rec').removeClass('d-none');
                    $('.load-activity-location-center-old-rec').html(result);
                    $('#loader').html('');

                }});

        });

        $(".load-activity-location-center-old-rec").change(function () {

            $('#user_entry_date_old_rec').removeClass('d-none');

        });

        $("#user_entry_date_old_rec").change(function () {

            $('#add_old_rec_form').removeClass('d-none');

        });

        $("#user_activity_location").change(function () {
            var centers_url  = $(this).attr("data-url");
            $('#loader').html('<i class="fa fa-spin fa-spinner"></i>');
            $.ajax({
                url: centers_url,
                method: 'get',
                data: {
                    activity_location_id: $(this).val(),
                },
                success: function(result){
                    $('#load_user_center').removeClass('d-none');
                    $('.load-activity-location-center').html(result);
                    $('#loader').html('');

                }});

        });

        $(".load-activity-location-center").change(function () {

            $('#user_entry_date').removeClass('d-none');

        });

        $("#user_entry_date").change(function () {

            $('#set_center').removeClass('d-none');

        });


        $('.datepicker').datepicker();




        $('#add_referral').submit(function()
        {

            const field1 = document.getElementById('cnic_number').value;
            const field2 = document.getElementById('phone').value;

            if (!field1 && !field2) {
                alert('Please fill at least one of the fields: CNIC or Phone.');
                return false;
            }



            $("input[type='submit']", this)
            .val("Please Wait...")
            .attr('disabled', 'disabled');

            return true;
        });



        $(".user_filter_id").change(function () {

            var centers_url  = $(this).attr("data-url");
            var user_id = $(this).val();
            console.log(centers_url);
            if($(this).val() == '')
            {
                $('#activity_location_filter_id').attr('disabled', true);
                $('#center_filter_id').attr('disabled', true);
                return;
            }

            $.ajax({
                url: centers_url,
                method: 'get',
                data: {
                    user_id : $(this).val(),
                },
                success: function(result){
                    console.log(result);
                    $('#center_filter_id').html(result);
                    $('#activity_location_filter_id').attr('disabled', false);
                    $('#center_filter_id').attr('disabled', false);
                }});

        });

        $(".activity_location_filter_id").change(function () {

            var centers_url  = $(this).attr("data-url");
            var user_id = $('.user_filter_id').val();
            console.log(centers_url);


            $.ajax({
                url: centers_url,
                method: 'get',
                data: {
                    user_id : user_id,
                    activity_location_id : $(this).val(),
                },
                success: function(result){
                    console.log(result);
                    $('#center_filter_id').html(result);
                    $('#activity_location_filter_id').attr('disabled', false);
                    $('#center_filter_id').attr('disabled', false);
                }});

        });

        $("#start_from_date_filter").change(function () {
            $('#searh_month_filter').val('');
            $('#year_qauterly_filter').val('');
            $('#searh_qauterly_filter').val('');
        });

        $("#start_to_date_filter").change(function () {
            $('#searh_month_filter').val('');
            $('#year_qauterly_filter').val('');
            $('#searh_qauterly_filter').val('');
        });

        $("#searh_month_filter").change(function () {
            $('#start_from_date_filter').val('');
            $('#start_to_date_filter').val('');
            $('#year_qauterly_filter').val('');
            $('#searh_qauterly_filter').val('');
        });

        $("#year_qauterly_filter").change(function () {
            $('#start_from_date_filter').val('');
            $('#start_to_date_filter').val('');
            $('#searh_month_filter').val('');
        });

        $("#searh_qauterly_filter").change(function () {
            $('#start_from_date_filter').val('');
            $('#start_to_date_filter').val('');
            $('#searh_month_filter').val('');
        });





});


$("#province_hf_id").change(function () {
    var tehsils_url  = $(this).attr("data-url");
    console.log(tehsils_url);

            $.ajax({
                url: tehsils_url,
                method: 'get',
                data: {
                    province_id: $(this).val(),
                },
                success: function(result){
                    console.log(result);
                    $('#tehsil_id').html(result);
                    $('#tehsil_id').attr('disabled', false);
                }});
});

$("#operation_private").change(function () {

    if ($(this).prop('checked'))
        {
            $('#select_health_facility_referal').attr('disabled', true);
            $('#select_health_facility_referal').attr('required', false);
            $('#doctor_id').attr('disabled', true);
            $('#doctor_id').attr('required', false);

        }
        else
        {
            $('#select_health_facility_referal').attr('disabled', false);
            $('#select_health_facility_referal').attr('required', true);
            $('#doctor_id').attr('disabled', false);
            $('#doctor_id').attr('required', true);
        }


});

$("#select_health_facility_referal").change(function () {
    var doctors_url  = $(this).attr("data-url");
    if($(this).val() == '')
    {
        $('#doctor_id').attr('disabled', true);
        $('#doctor_id').html('');
        return;
    }

            $.ajax({
                url: doctors_url,
                method: 'get',
                data: {
                    health_facility_id: $(this).val(),
                },
                success: function(result){
                    console.log(result);
                    if(result != '')
                        {
                            $('#doctor_id').html(result);
                            $('#doctor_id').attr('disabled', false);
                        }
                        else
                        {
                            $('#doctor_id').html('<option value="">No Doctor for this Facility</option>');
                            $('#doctor_id').attr('disabled', true);
                        }
                }});
});


$("#select_health_facility").change(function () {
    var cities_url  = $(this).attr("data-url");
    console.log(cities_url);

            $.ajax({
                url: cities_url,
                method: 'get',
                data: {
                    province_id: $(this).val(),
                },
                success: function(result){
                    console.log(result);
                    $('#tehsil_id').html(result);
                    $('#tehsil_id').attr('disabled', false);
                }});
});


$("#select_referrals").change(function () {

   var referral =  $(this).find(':selected').attr("data-slug");
   console.log(referral);
   if( referral == 'cataract-surgery')
   {

    $('#diabetic-container').removeClass('d-none');
    $('#health-facility-container').removeClass('d-none');
    $('#diabetic').attr('required', true);
    $('#diabetic_value').attr('required', true);
    $('#select_health_facility_referal').attr('required', true);
    // $('#doctor_id').attr('required', true);
    $('#cataract-container').removeClass('d-none');

   }
   else if(referral == 'glaucoma-surgery')
   {
    $('#diabetic-container').addClass('d-none');
    $('#health-facility-container').removeClass('d-none');
    $('#select_health_facility_referal').attr('required', true);
    // $('#doctor_id').attr('required', true);
   }
   else if(referral == '')
   {
    $('#diabetic-container').addClass('d-none');
    $('#health-facility-container').addClass('d-none');
   }
   else
   {
    $('#diabetic-container').addClass('d-none');
    $('#health-facility-container').removeClass('d-none');
    $('#cataract-container').addClass('d-none');
    $('#diabetic').attr('required', false);
    $('#diabetic_value').attr('required', false);
    $('#select_health_facility_referal').attr('required', false);
    // $('#doctor_id').attr('required', false);
   }

});

$("#select_gender").change(function () {
    var gender =  $(this).val();
    if( gender == 'ma' || gender == 'fa')
   {
     $('#age_limit_alert').html('Minimum Age Limit is 15 Year');
     $('#age_limit_alert').removeClass('d-none');
     $('#age_limit').attr('disabled', false);
     $('#age_limit').val('');
     $('#age_limit').prop('min',15);
     $('#age_limit').removeAttr('max');

   }
   else if(gender == '')
   {
     $('#age_limit_alert').html('Select Gender to add age');
     $('#age_limit').attr('disabled', true);
     $('#age_limit').val('');
   }
   else
   {
     $('#age_limit_alert').html('Maximum Age Limit is 15 Year');
     $('#age_limit_alert').removeClass('d-none');
     $('#age_limit').attr('disabled', false);
     $('#age_limit').val('');
     $('#age_limit').prop('max',15);
     $('#age_limit').removeAttr('min');
   }

});



// $("#add_referral").click(function (e) {
// $( "#add_referral" ).submit(function( e ) {
    $("form#add_referral").on("submit", function (e) {
        var cnic_number =  $('#cnic_number').val();
        var contact_number =  $('#contact_number').val();

        if( (cnic_number != '') || (contact_number != '') )
        {
            $('#form_error').html('');
        }
        else
        {
            $('#form_error').html('<div class="alert alert-warning" role="alert">Enter CNIC no or Contact no before submitting!</div>');
            e.preventDefault();
            return;
        }

});


$(".select-center").click(function () {
    $('#center-info-container').removeClass('d-none');
});


$(".user_status").change(function () {
    var user_id  = $(this).attr("data-userid");
    var user_status_url  = $(this).attr("data-userstatusurl");
    console.log(user_status_url);

    if ($(this).is(":checked"))
        {
            user_status = 1;
        }
        else
        {
            user_status = 0;
        }

            $.ajax({
                url: user_status_url,
                method: 'get',
                data: {
                    user_id: user_id,
                    user_status: user_status,
                },
                success: function(result){
                    console.log(result);
                    $('#tehsil_id').html(result);
                    $('#tehsil_id').attr('disabled', false);
                }});
});


$("#dashboard_daily_date").change(function () {

    var dailydateurl  = $(this).attr("data-dailydateurl");
    var date = $(this).val();

    $('#get_daily_stats').show();
    $('#get_daily_stats').attr('href', dailydateurl+date);

});

$("#dashboard_monthly_date").change(function () {

    var monthlydateurl  = $(this).attr("data-monthlydateurl");
    var date = $(this).val();

    $('#get_monthly_stats').show();
    $('#get_monthly_stats').attr('href', monthlydateurl+date);

});

$("#dashboard_yearly_date").change(function () {

    var yearlydateurl  = $(this).attr("data-yearlydateurl");
    var date = $(this).val();

    $('#get_yearly_stats').show();
    $('#get_yearly_stats').attr('href', yearlydateurl+date);

});


$("#select_atp_date").change(function () {

    var apt_date  = $(this).val();
    var get_apt_dates_url  = $(this).attr("data-aptdateurl");
    var user_id  = $(this).attr("data-userid");

    console.log(apt_date);
    $.ajax({
        url: get_apt_dates_url,
        method: 'get',
        data: {
            apt_date: apt_date,
            user_id: user_id,
        },
        success: function(result){
            $('#load-atp-reserved-date-container').html(result);
        }});

});


$(".apt_submit").click(function () {

    var url  = $(this).attr("data-url");
    var apt_id = $(this).attr("data-id");
    var apt_submit = $(this).attr("data-aptsubmit");

    $('#apt-loader-'+apt_id).html('<i class="fa fa-spin fa-spinner"></i>');

        $.ajax({
            url: url,
            method: 'get',
            data: {
                apt_id: apt_id,
                apt_submit: apt_submit,
            },
            success: function(result){

                setTimeout(function(){
                    window.location.reload();
                    }, 2000);

            }});


});


