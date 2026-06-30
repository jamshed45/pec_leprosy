@if($search_field_info['search_status'])
<div class="col-lg-12">
    <div class="info-box">


<div class="table-responsive">
  <table class="table dashboard_entries_table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        @if (isset($sub_filter_info['refferal_column']))
        <th scope="col">Refferal</th>
        @endif
        <th scope="col">Date</th>
        @if (isset($sub_filter_info['lots_column']))
        <th scope="col">LOTs Name</th>
        @endif
        @if (isset($sub_filter_info['patient_column']))
          <th scope="col">Patient Info</th>
        @endif
        @if (isset($sub_filter_info['referto_column']))
        <th scope="col">Refer To</th>
        @endif
        @if (isset($sub_filter_info['remarks_column']))
            <th scope="col">Remarks</th>
        @endif
        
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
        <th scope="row">{{ $loop->iteration }}</th>
        @if (isset($sub_filter_info['refferal_column']))
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
        @endif
        <td>@php echo date('j M, Y', strtotime($refferal->created_at)) @endphp</td>
        @if (isset($sub_filter_info['lots_column']))
          <td>{{ $refferal->user_name }}<br>
            @if (isset($sub_filter_info['activity_name']))
               <b>Location :</b> {{ $refferal->activity_name }} <br>
            @endif
            @if (isset($sub_filter_info['center_name']))   
               <b>Center :</b> {{ $refferal->center_name }}
            @endif    
            </td>
        @endif
        @if (isset($sub_filter_info['patient_column']))
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
        @endif
        @if (isset($sub_filter_info['referto_column']))
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
        @endif

        @if (isset($sub_filter_info['remarks_column']))
            <td>{{ $refferal->remark }}</td>
        @endif
        
      </tr>
      
      @endforeach

    </tbody>
  </table>
</div>

    </div>
</div>

@endif