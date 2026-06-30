@extends('layout')

@section('content')
  @php
    if(!session()->get('center_info'))
    {

      @endphp  
        <script>window.location = "{{ URL::to('/dashboard') }}"</script>
      @php    
      die();  
    }
  @endphp
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>PEC {{ ucwords(str_replace("-"," ",$form_link_slug)) }} Entries</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> PEC</li>
        <li><i class="fa fa-angle-right"></i> {{ ucwords(str_replace("-"," ",$form_link_slug)) }}</li>
      </ol>
    </div>
    
    <div class="col-lg-12">
      @include('user_center_section')     
    </div>
    
    <div class="col-md-12">
      @if (Session::has('success'))
        <div class="alert alert-info">{{ Session::get('success') }}</div>
      @endif
    </div>
    
    @if (!session()->get('center_info'))
      @php
        $from_disable = 'pointer-events: none; opacity: 0.4;';
      @endphp
    @else
      @php
         $from_disable = '';
      @endphp      
    @endif
    
    @php
      
      $user = Auth::user();

      $referral_key = DB::table('pec_form_categories')
                    ->where('parent_slug', $form_link_slug)
                    ->pluck('slug')
                    ->toArray();



    if(session()->has('center_info'))
      {
          $center_session = session()->get('center_info');
          // print_r($center_session);
          $patient_entries = DB::table('pec_center_patients_record')
              ->where('entry_at', '=' , $center_session['entry_date'])
              ->where('user_id', '=',  $user->id)
              ->where('province_id', '=',  $center_session['province_id'])
              ->where('tehsil_id', '=',  $center_session['tehsil_id'])
              ->where('center_id', '=',  $center_session['center_id'])
              ->whereIn('patient_type',  $referral_key)
              ->get()
              ->keyBy('patient_type')
              ->toArray();
              
      // echo "<pre>";              
      // print_r($patient_entries);
      // echo "</pre>";
      // die();
      }
      else
      {
        $patient_entries = array();
      }

    $form_sub_cat = DB::table('pec_form_categories')
                    ->where('parent_slug', $form_link_slug)
                    ->orderBy('order_by', 'ASC')
                    ->get()
                    ->keyBy('slug')
                    ->toArray();
          
    @endphp
    <div class="content" style="{{ $from_disable }}">

        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">{{ ucwords(str_replace("-"," ",$form_link_slug)) }} - <b class="text-white">{{ $center_session['activity_location_name'] }}</b></h5>
              </div>
              <div class="card-body">
                <form action="{{ route('patient-entries.store') }}" method="POST">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="form_link_slug" value="{{ $form_link_slug }}">

                  @php
                  $total_array = array();

                  $ma_total = 0;
                  $fa_total = 0;
                  $mc_total = 0;
                  $fc_total = 0;
   
                  foreach($patient_entries as $key => $entry)
                    {
                      $entry = json_decode( json_encode($entry), true);
                      
                      if($form_sub_cat[''.$entry['patient_type'].'']->add_in_total == 'enable')
                        {
                          $ma_total = $ma_total + $entry['ma'];
                          $fa_total = $fa_total + $entry['fa'];
                          $mc_total = $mc_total + $entry['mc'];
                          $fc_total = $fc_total + $entry['fc'];
                        }
                      
                    }
                    $total_nv_dv_ma = 0;
                    $total_nv_dv_fa = 0;
                    $total_nv_dv_mc = 0;
                    $total_nv_dv_fc = 0;
                @endphp
                  
                <table class="table tg">
                  <thead>
                    <tr>
                      <th class="tg-p81p" rowspan="2">Activities Done</th>
                      <th class="tg-jldi" colspan="5">{{ $center_session['activity_location_name'] }}</th>
                    </tr>
                    <tr>
                      <th class="tg-u38u">MA</th>
                      <th class="tg-u38u">FA</th>
                      <th class="tg-u38u">MC</th>
                      <th class="tg-u38u">FC</th>
                      <th class="tg-u38u">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th class="tg-hu93">Total</th>
                      <td class="tg-hu93">{{ $ma_total != 0 ? $ma_total:'-' }}</td>
                      <td class="tg-hu93">{{ $fa_total != 0 ? $fa_total:'-' }}</td>
                      <td class="tg-hu93">{{ $mc_total != 0 ? $mc_total:'-' }}</td>
                      <td class="tg-hu93">{{ $fc_total != 0 ? $fc_total:'-' }}</td>
                      <td class="tg-hu93">@php $total = $ma_total + $fa_total + $mc_total + $fc_total @endphp {{ $total != 0 ? $total:'-' }}</td>
                    </tr>
             
                    @foreach ($form_sub_cat as $item)
                    {{-- {{ dd($patient_entries[$item->slug]) }} --}}
                    {{-- {{dd($item)}} --}}
   
                    @php
       
                      if(isset($patient_entries[$item->slug]->ma) && $patient_entries[$item->slug]->ma)
                      {
                        if($patient_entries[$item->slug]->ma != 0)
                        {
                          $ma = $patient_entries[$item->slug]->ma;

                          $total_array[$item->slug]['ma'] = $patient_entries[$item->slug]->ma;
                          
                        }
                        else 
                        {
                          $ma = '';
                        }
                      }
                      else 
                      {
                        $ma = '';
                      }

                      if(isset($patient_entries[$item->slug]->fa) && $patient_entries[$item->slug]->fa)
                      {
                        if($patient_entries[$item->slug]->fa != 0)
                        {
                          $fa = $patient_entries[$item->slug]->fa;

                          // $total_array[$item->slug] = array(
                          //   'clinics_fa' => $patient_entries[$item->slug]->clinics_fa,
                          // );
                          $total_array[$item->slug]['fa'] = $patient_entries[$item->slug]->fa;
                        }
                        else 
                        {
                          $fa = '';
                        }
                      }
                      else 
                      {
                        $fa = '';
                      }
              
                      if(isset($patient_entries[$item->slug]->mc) && $patient_entries[$item->slug]->mc)
                      {
                        if($patient_entries[$item->slug]->mc != 0)
                        {
                          $mc = $patient_entries[$item->slug]->mc;

                          // $total_array[$item->slug] = array(
                          //   'clinics_mc' => $patient_entries[$item->slug]->clinics_mc,
                          // );
                          $total_array[$item->slug]['mc'] = $patient_entries[$item->slug]->mc;
                        }
                        else 
                        {
                          $mc = '';
                        }
                      }
                      else 
                      {
                        $mc = '';
                      }

                      if(isset($patient_entries[$item->slug]->fc) && $patient_entries[$item->slug]->fc)
                      {
                        if($patient_entries[$item->slug]->fc != 0)
                        {
                          $fc = $patient_entries[$item->slug]->fc;

                          // $total_array[$item->slug] = array(
                          //   'clinics_fc' => $patient_entries[$item->slug]->clinics_fc,
                          // );
                          $total_array[$item->slug]['fc'] = $patient_entries[$item->slug]->fc;

                        }
                        else 
                        {
                          $fc = '';
                        }
                      }
                      else 
                      {
                        $fc = '';
                      }
                      if( $item->slug == 'presbyopia-nv' || $item->slug == 'distance-vision-dv' )
                      {
                        $total_nv_dv_ma += (int)$ma;
                        $total_nv_dv_fa += (int)$fa;
                        $total_nv_dv_mc += (int)$mc;
                        $total_nv_dv_fc += (int)$fc;
                      }
              
                    @endphp
                    @if($item->slug != 'refractions-total-nv-dv')
                    <tr>
                      <td class="tg-zvdi">{{ $item->category_name }}</td>
                      {{-- {{ isset($patient_entries[$item->slug]->clinics_ma']) ?$patient_entries[$item->slug]->clinics_ma']:''; }} --}}
                      <td class="tg-tkru"><input type="number" min="0" name="patient_entry[{{ $item->slug }}][ma]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="{{ $ma }}" placeholder="-" /></td>
                      <td class="tg-tkru"><input type="number" min="0" name="patient_entry[{{ $item->slug }}][fa]" {{ $item->entry_status == 'disable'?'disabled':''; }}  value="{{ $fa }}" placeholder="-" /></td>
                      <td class="tg-tkru"><input type="number" min="0" name="patient_entry[{{ $item->slug }}][mc]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="{{ $mc }}" placeholder="-" /></td>
                      <td class="tg-tkru"><input type="number" min="0" name="patient_entry[{{ $item->slug }}][fc]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="{{ $fc }}" placeholder="-" /></td>
                      <td class="tg-tkru"><b>
                        @php $total = (int)$ma + (int)$fa + (int)$mc + (int)$fc @endphp {{ $total != 0 ? $total:'-' }}</b></td>
                    </tr>
                    @else
                    <tr>
                      <td class="tg-zvdi">{{ $item->category_name }}</td>
                      {{-- {{ isset($patient_entries[$item->slug]->clinics_ma']) ?$patient_entries[$item->slug]->clinics_ma']:''; }} --}}
                      <td class="tg-tkru"><input type="number" min="0" name="patient_entry[{{ $item->slug }}][ma]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="{{ $total_nv_dv_ma != 0 ? $total_nv_dv_ma:'-' }}" placeholder="-" /></td>
                      <td class="tg-tkru"><input type="number" min="0" name="patient_entry[{{ $item->slug }}][fa]" {{ $item->entry_status == 'disable'?'disabled':''; }}  value="{{ $total_nv_dv_fa != 0 ? $total_nv_dv_fa:'-' }}" placeholder="-" /></td>
                      <td class="tg-tkru"><input type="number" min="0" name="patient_entry[{{ $item->slug }}][mc]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="{{ $total_nv_dv_mc != 0 ? $total_nv_dv_mc:'-' }}" placeholder="-" /></td>
                      <td class="tg-tkru"><input type="number" min="0" name="patient_entry[{{ $item->slug }}][fc]" {{ $item->entry_status == 'disable'?'disabled':''; }} value="{{ $total_nv_dv_fc != 0 ? $total_nv_dv_fc:'-' }}" placeholder="-" /></td>
                      <td class="tg-tkru"><b>
                        @php $total = (int)$total_nv_dv_ma + (int)$total_nv_dv_fa + (int)$total_nv_dv_mc + (int)$total_nv_dv_fc @endphp {{ $total != 0 ? $total:'-' }}</b></td>
                    </tr>
                    @endif
        
                    @if($item->slug == 'person-with-disability')
                      <tr>
                        <td class="tg-tkru" colspan="7"><textarea name="disability_remarks" class="form-control" placeholder="remarks">{{ (isset($patient_entries[$item->slug]->remarks))?$patient_entries[$item->slug]->remarks:'' }}</textarea></td>
                      </tr>
                    @endif
                    
                    @endforeach


                  </tbody>
                  </table>

              @if ( $form_link_slug != 'referrals' && $form_link_slug != 'refer-by' && $form_link_slug != 'post-op' && $form_link_slug != 'confirmed-cat-surgeries' && $form_link_slug != 'confirmed-glaucoma-surgeries' && $form_link_slug != 'confirmed-trichiasis-surgeries' && $form_link_slug != 'blood-sugar') 
                <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-block btn-primary py-4"><b class="text-white h5">Add {{ ucwords(str_replace("-"," ",$form_link_slug)) }} Entry</b></button> 
                        </div>
                </div>
              @endif 
              </form>
       
              </div>
            </div>
          </div>
        </div>     
      </div>
  </div>


@endsection