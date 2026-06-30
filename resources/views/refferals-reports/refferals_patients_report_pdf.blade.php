<style type="">
  table.pdf-tabel-styling { background: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; border-spacing: 0; font-size: 10px; width: 100% }
  table.pdf-tabel-styling th { border: #ededed 1px solid !important; background: #00bcd614; }
  table.pdf-tabel-styling td { border: #ededed 1px solid !important; }
table.pdf-tabel-styling td, table.pdf-tabel-styling th {
    padding: 0.25rem;  }

.dash-heading {  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;  margin: 10px;  }    

.info-box { display: block; background: #f7fbff; width: 98%; border-radius: 5px; margin-bottom: 30px;
    padding: 1%; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 10px; padding-bottom:10px; }
.info-box h5  {  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 15px; margin: 0 0 10px 0;  }
.info-box h5 small{ float: right; }
.info-box .col-lg-4 { margin-right: 10px; display: inline-block; font-size: 12px; }       
</style>

<table class="pdf-tabel-styling">
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
        <th scope="row">{{ $loop->iteration }}</th>

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
    