@if($search_field_info['search_status'])
<div class="col-lg-12">

  
    <a href="{{ URL::to('referrals-reports/all-referrals-report-pdf?user_id=') }}{{ $user->id }}&cat_slug={{ $search_field_info['referral_id'] }}&activity_location_id={{ $search_field_info['activity_location_id'] }}&center_id={{ $search_field_info['center_id'] }}&searh_month={{ $search_field_info['searh_month'] }}&filter_date={{ $search_field_info['filter_date'] }}&year_qauterly={{ $search_field_info['searh_year_qauterly'] }}&searh_qauterly={{ $search_field_info['searh_qauterly'] }}" class="btn btn-success mb-3">Download PDF</a> 
 

    <div class="info-box">


<div class="table-responsive">
  <table class="table dashboard_entries_table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Refferal</th>
        <th scope="col">Date</th>
        <th scope="col">LOTs Name</th>
        <th scope="col">Patient Info</th>
        <th scope="col">Refer To</th>
        <th scope="col">Remarks</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($all_refferals as $refferal)
      {{-- @php
        echo "<pre>";
        print_r($refferal);
        echo "</pre>";
      @endphp --}}
      <tr>
        <th scope="row">{{ $loop->iteration }} {{ $refferal->rfid }}</th>

        <td> {{ ucwords(str_replace("-"," ",$refferal->patient_type)) }}
            @if($refferal->patient_type == 'cataract-surgery')
                @if (isset($sub_filter_info['refferal_surgery']))
                    <br><b>Surgery :</b>
                    @if($refferal->cataract_op_slug == 'cataract-right-eye')
                    RT
                    @elseif($refferal->cataract_op_slug == 'cataract-left-eye')
                    LT
                    @else
                    BT
                    @endif
                @endif
                @if (isset($sub_filter_info['refferal_diabetic']))
                    <br><b>Diabetic :</b> 
                    @if($refferal->diabetic == 1 )
                    Yes
                    @else
                    No 
                    @endif
                @endif
                @if (isset($sub_filter_info['refferal_diabetic']))    
                    <br><b>Value :</b> {{ $refferal->diabetic_value }}
                @endif
            @endif
        </td>

        <td>@php echo date('j M, Y', strtotime($refferal->created_at)) @endphp</td>

          <td>{{ $refferal->user_name }}<br>
            @if (isset($sub_filter_info['activity_name']))
               <b>Location :</b> {{ $refferal->activity_name }} <br>
            @endif
            @if (isset($sub_filter_info['center_name']))   
               <b>Center :</b> {{ $refferal->center_name }}
            @endif    
            </td>


          <td>
            <b>Name :</b> {{ $refferal->full_name }}
            @if (isset($sub_filter_info['relation_name']))
              <b>{{ $refferal->relation }}</b> {{ $refferal->relative_name }} 
            @endif
            <br>
            @if (isset($sub_filter_info['age']))
              <b>Age :</b> {{ $refferal->age }} Y<br>
            @endif
            @if (isset($sub_filter_info['gender']))
              <b>Gender :</b> {{ $refferal->gender }}<br>
            @endif
            @if (isset($sub_filter_info['contact_no']))
              <b>Contact No :</b> {{ $refferal->contact_number }}<br>
            @endif
            @if (isset($sub_filter_info['cnic']))
              <b>CNIC :</b> {{ $refferal->cnic_number }}
            @endif
          </td>

            <td>
  
                @if($refferal->private == '')
                    @if (isset($sub_filter_info['health_facility']))
                        <b>Health Facility :</b> {{ $refferal->health_facility_name }}<br>
                    @endif
                    @if (isset($sub_filter_info['doctor']))
                        <b>Doctor :</b> {{ $refferal->doc_name }}
                    @endif
                @else
                    @if (isset($sub_filter_info['health_facility']))
                    <b>Health Facility :</b>  Private
                    @endif
                @endif
            </td>


            <td>{{ $refferal->remark }}</td>
        
      </tr>
      
      @endforeach

    </tbody>
  </table>
</div>

    </div>
</div>

@endif


<script type="">
  $(document).ready(function(){

    $("#filter_date").change(function(){
      $("#searh_month_filter").val('');
      $("#year_qauterly_filter").val('');
      $("#searh_qauterly_filter").val('');
    });

    $("#searh_month_filter").change(function(){
      $("#filter_date").val('');
      $("#year_qauterly_filter").val('');
      $("#searh_qauterly_filter").val('');
    });

    $("#year_qauterly_filter").change(function(){
      $("#filter_date").val('');
      $("#searh_month_filter").val('');
    });

    $("#searh_qauterly_filter").change(function(){
      $("#filter_date").val('');
      $("#searh_month_filter").val('');
    });


  });
  </script>