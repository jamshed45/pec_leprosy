@extends('layout')
  
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Patient Entry Complete Report</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Patient Entry</li>
        <li><i class="fa fa-angle-right"></i> Complete Report</li>
      </ol>
    </div>
    

    
    <div class="content patients-entries-report">
        <div class="row">
            @foreach ($form_categories as $category)  
                <div class="col-lg-12 mb-2">
                    <div class="card card-outline">
                    <div class="card-header bg-blue">
                        <h5 class="text-white m-b-0">{{ $category['category_name'] }} </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $form_sub_cat = DB::table('pec_form_categories')
                                    ->where('parent_slug', $category['slug'])
                                    ->get()
                                    ->toArray();
                            // print_r($form_sub_cat);        
                        @endphp

                                      <!-- Nav tabs -->
              <ul class="nav nav-tabs customtab2" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#{{ $category['slug']}}-clinics" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">CLINICS</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#{{ $category['slug']}}-profile7" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">HOME & FIELD VISIT</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#{{ $category['slug']}}-messages7" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">SCHOOL</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#{{ $category['slug']}}-total" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">TOTAL</span></a> </li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane active" id="{{ $category['slug']}}-clinics" role="tabpanel">
 

                  @php
                  $total_array = array();

                  $clinics_ma_total = 0;
                  $clinics_fa_total = 0;
                  $clinics_mc_total = 0;
                  $clinics_fc_total = 0;
   
                  foreach($patient_entries as $key => $entry)
                    {
                      $entry = json_decode( json_encode($entry), true);
                      $clinics_ma_total = $clinics_ma_total + $entry['clinics_ma'];
                      $clinics_fa_total = $clinics_fa_total + $entry['clinics_fa'];
                      $clinics_mc_total = $clinics_mc_total + $entry['clinics_mc'];
                      $clinics_fc_total = $clinics_fc_total + $entry['clinics_fc'];
                      
                    }
                  
                @endphp
                  
                <table class="table tg">
                  <thead>
                    <tr>
                      <th class="tg-p81p" rowspan="2">Activities Done</th>
                      <th class="tg-jldi" colspan="5">Clinics</th>
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
                      <td class="tg-hu93">{{ $clinics_ma_total != 0 ? $clinics_ma_total:'-' }}</td>
                      <td class="tg-hu93">{{ $clinics_fa_total != 0 ? $clinics_fa_total:'-' }}</td>
                      <td class="tg-hu93">{{ $clinics_mc_total != 0 ? $clinics_mc_total:'-' }}</td>
                      <td class="tg-hu93">{{ $clinics_fc_total != 0 ? $clinics_fc_total:'-' }}</td>
                      <td class="tg-hu93">@php $total = $clinics_ma_total + $clinics_fa_total + $clinics_mc_total + $clinics_fc_total @endphp {{ $total != 0 ? $total:'-' }}</td>
                    </tr>
             
                    @foreach ($form_sub_cat as $item)
                    {{-- {{ dd($patient_entries[$item->slug]) }} --}}
                    {{-- {{dd($item)}} --}}
                    
                    @php
                    // echo $patient_entries['new-patients']['clinics_ma'];
                    // echo "<pre>";
                    // print_r($patient_entries['new-patients']);
                    // echo "</pre>";
    
                    if(isset($patient_entries[$item->slug]['clinics_ma']))
                      {
                        
                        if($patient_entries[$item->slug]['clinics_ma'] != 0)
                        {
                           
                          $clinics_ma = $patient_entries[$item->slug]['clinics_ma'];

                          $total_array[$item->slug]['ma'] = $patient_entries[$item->slug]['clinics_ma'];
                          
                        }
                        else 
                        {
                          $clinics_ma = '';
                        }
                      }
                      else 
                      {
                        $clinics_ma = '';
                      }

                      if(isset($patient_entries[$item->slug]->clinics_fa) && $patient_entries[$item->slug]->clinics_fa)
                      {
                        if($patient_entries[$item->slug]->clinics_fa != 0)
                        {
                          $clinics_fa = $patient_entries[$item->slug]->clinics_fa;

                          // $total_array[$item->slug] = array(
                          //   'clinics_fa' => $patient_entries[$item->slug]->clinics_fa,
                          // );
                          $total_array[$item->slug]['fa'] = $patient_entries[$item->slug]->clinics_fa;
                        }
                        else 
                        {
                          $clinics_fa = '';
                        }
                      }
                      else 
                      {
                        $clinics_fa = '';
                      }
              
                      if(isset($patient_entries[$item->slug]->clinics_mc) && $patient_entries[$item->slug]->clinics_mc)
                      {
                        if($patient_entries[$item->slug]->clinics_mc != 0)
                        {
                          $clinics_mc = $patient_entries[$item->slug]->clinics_mc;

                          // $total_array[$item->slug] = array(
                          //   'clinics_mc' => $patient_entries[$item->slug]->clinics_mc,
                          // );
                          $total_array[$item->slug]['mc'] = $patient_entries[$item->slug]->clinics_mc;
                        }
                        else 
                        {
                          $clinics_mc = '';
                        }
                      }
                      else 
                      {
                        $clinics_mc = '';
                      }

                      if(isset($patient_entries[$item->slug]->clinics_fc) && $patient_entries[$item->slug]->clinics_fc)
                      {
                        if($patient_entries[$item->slug]->clinics_fc != 0)
                        {
                          $clinics_fc = $patient_entries[$item->slug]->clinics_fc;

                          // $total_array[$item->slug] = array(
                          //   'clinics_fc' => $patient_entries[$item->slug]->clinics_fc,
                          // );
                          $total_array[$item->slug]['fc'] = $patient_entries[$item->slug]->clinics_fc;

                        }
                        else 
                        {
                          $clinics_fc = '';
                        }
                      }
                      else 
                      {
                        $clinics_fc = '';
                      }
                    @endphp
                    <tr>
                      <td class="tg-zvdi">{{ $item->category_name }}</td>
                      {{-- {{ isset($patient_entries[$item->slug]->clinics_ma']) ?$patient_entries[$item->slug]->clinics_ma']:''; }} --}}
                      <td class="tg-tkru">{{ ($clinics_ma)?$clinics_ma:'-'; }}</td>
                      <td class="tg-tkru">{{ $clinics_fa?$clinics_fa:'-'; }}</td>
                      <td class="tg-tkru">{{ $clinics_mc?$clinics_mc:'-'; }}</td>
                      <td class="tg-tkru">{{ $clinics_fc?$clinics_fc:'-'; }}</td>
                      <td class="tg-tkru"><b>
                        @php $total = (int)$clinics_ma + (int)$clinics_fa + (int)$clinics_mc + (int)$clinics_fc @endphp {{ $total != 0 ? $total:'-' }}</b></td>
                    </tr>
                    @endforeach


                  </tbody>
                  </table>

                
                </div>
                <div class="tab-pane  p-20" id="{{ $category['slug']}}-profile7" role="tabpanel">

                  @php

                  $home_field_ma_total = 0;
                  $home_field_fa_total = 0;
                  $home_field_mc_total = 0;
                  $home_field_fc_total = 0;
                  
                  foreach($patient_entries as $key => $entry)
                    {
                      $entry = json_decode( json_encode($entry), true);
                      $home_field_ma_total = $home_field_ma_total + $entry['home_field_ma'];
                      $home_field_fa_total = $home_field_fa_total + $entry['home_field_fa'];
                      $home_field_mc_total = $home_field_mc_total + $entry['home_field_mc'];
                      $home_field_fc_total = $home_field_fc_total + $entry['home_field_fc'];
                      
                    }
                  
                  
                  @endphp
                  
                  
                  <table class="table tg">
                  <thead>
                    <tr>
                      <th class="tg-p81p" rowspan="2">Activities Done</th>
                      <th class="tg-jldi" colspan="5">Clinics</th>
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
                      <td class="tg-hu93">{{ $home_field_ma_total != 0 ? $home_field_ma_total:'-' }}</td>
                      <td class="tg-hu93">{{ $home_field_fa_total != 0 ? $home_field_fa_total:'-' }}</td>
                      <td class="tg-hu93">{{ $home_field_mc_total != 0 ? $home_field_mc_total:'-' }}</td>
                      <td class="tg-hu93">{{ $home_field_fc_total != 0 ? $home_field_fc_total:'-' }}</td>
                      <td class="tg-hu93">@php $total = $home_field_ma_total + $home_field_fa_total + $home_field_mc_total + $home_field_fc_total @endphp {{ $total != 0 ? $total:'-' }}</td>
                    </tr>
                    @foreach ($form_sub_cat as $item)
                    {{-- {{ dd($patient_entries[$item->slug]) }} --}}
                    {{-- {{dd($item)}} --}}
                    @php
                      if(isset($patient_entries[$item->slug]->home_field_ma) && $patient_entries[$item->slug]->home_field_ma)
                      {
                        if($patient_entries[$item->slug]->home_field_ma != 0)
                        {
                          $home_field_ma = $patient_entries[$item->slug]->home_field_ma;
                          $total_array[$item->slug]['ma'] = $total_array[$item->slug]['ma'] + $patient_entries[$item->slug]->home_field_ma;
                        }
                        else 
                        {
                          $home_field_ma = '';
                        }
                      }
                      else 
                      {
                        $home_field_ma = '';
                      }
                  
                      if(isset($patient_entries[$item->slug]->home_field_fa) && $patient_entries[$item->slug]->home_field_fa)
                      {
                        if($patient_entries[$item->slug]->home_field_fa != 0)
                        {
                          $home_field_fa = $patient_entries[$item->slug]->home_field_fa;

                          $total_array[$item->slug]['fa'] = $total_array[$item->slug]['fa'] + $patient_entries[$item->slug]->home_field_fa;
                        }
                        else 
                        {
                          $home_field_fa = '';
                        }
                      }
                      else 
                      {
                        $home_field_fa = '';
                      }
           
                      if(isset($patient_entries[$item->slug]->home_field_mc) && $patient_entries[$item->slug]->home_field_mc)
                      {
                        if($patient_entries[$item->slug]->home_field_mc != 0)
                        {
                          $home_field_mc = $patient_entries[$item->slug]->home_field_mc;

                          $total_array[$item->slug]['mc'] = $total_array[$item->slug]['mc'] + $patient_entries[$item->slug]->home_field_mc;
                        }
                        else 
                        {
                          $home_field_mc = '';
                        }
                      }
                      else 
                      {
                        $home_field_mc = '';
                      }
                  
                      if(isset($patient_entries[$item->slug]->home_field_fc) && $patient_entries[$item->slug]->home_field_fc)
                      {
                        if($patient_entries[$item->slug]->home_field_fc != 0)
                        {
                          $home_field_fc = $patient_entries[$item->slug]->home_field_fc;

                          $total_array[$item->slug]['fc'] = $total_array[$item->slug]['fc'] + $patient_entries[$item->slug]->home_field_fc;
                        }
                        else 
                        {
                          $home_field_fc = '';
                        }
                      }
                      else 
                      {
                        $home_field_fc = '';
                      }
                    @endphp
                    <tr>
                      <td class="tg-zvdi">{{ $item->category_name }}</td>
                      {{-- {{ isset($patient_entries[$item->slug]->home_field_ma']) ?$patient_entries[$item->slug]->home_field_ma']:''; }} --}}
                      <td class="tg-tkru">{{ ($home_field_ma)?$home_field_ma:'-'; }}</td>
                      <td class="tg-tkru">{{ ($home_field_fa)?$home_field_fa:'-'; }}</td>
                      <td class="tg-tkru">{{ ($home_field_mc)?$home_field_mc:'-'; }}</td>
                      <td class="tg-tkru">{{ ($home_field_fc)?$home_field_fc:'-'; }}</td>
                      <td class="tg-tkru"><b>
                        @php $total = (int)$home_field_ma + (int)$home_field_fa + (int)$home_field_mc + (int)$home_field_fc @endphp {{ $total != 0 ? $total:'-' }}</b></td>
                      
                    </tr>
                    @endforeach
                  
                  
                  </tbody>
                  </table>
    

                </div>

                <div class="tab-pane p-20" id="{{ $category['slug']}}-messages7" role="tabpanel">

                  @php

                  $school_ma_total = 0;
                  $school_fa_total = 0;
                  $school_mc_total = 0;
                  $school_fc_total = 0;
                  
                  foreach($patient_entries as $key => $entry)
                    {
                      $entry = json_decode( json_encode($entry), true);
                      $school_ma_total = $school_ma_total + $entry['school_ma'];
                      $school_fa_total = $school_fa_total + $entry['school_fa'];
                      $school_mc_total = $school_mc_total + $entry['school_mc'];
                      $school_fc_total = $school_fc_total + $entry['school_fc'];
                      
                    }
                  
                  
                  @endphp
                  
                  
                  <table class="table tg">
                  <thead>
                    <tr>
                      <th class="tg-p81p" rowspan="2">Activities Done</th>
                      <th class="tg-jldi" colspan="5">Clinics</th>
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
                      <td class="tg-hu93">{{ $school_ma_total != 0 ? $school_ma_total:'-' }}</td>
                      <td class="tg-hu93">{{ $school_fa_total != 0 ? $school_fa_total:'-' }}</td>
                      <td class="tg-hu93">{{ $school_mc_total != 0 ? $school_mc_total:'-' }}</td>
                      <td class="tg-hu93">{{ $school_fc_total != 0 ? $school_fc_total:'-' }}</td>
                      <td class="tg-hu93">
                        @php $total = $school_ma_total + $school_fa_total + $school_mc_total + $school_fc_total @endphp {{ $total != 0 ? $total:'-' }}
                      </td>
                    </tr>
                    @foreach ($form_sub_cat as $item)
                    {{-- {{ dd($patient_entries[$item->slug]) }} --}}
                    {{-- {{dd($item)}} --}}
                    @php
           
                      if(isset($patient_entries[$item->slug]->school_ma) && $patient_entries[$item->slug]->school_ma)
                      {
                        if($patient_entries[$item->slug]->school_ma != 0)
                        {
                          $school_ma = $patient_entries[$item->slug]->school_ma;

                          $total_array[$item->slug]['ma'] = $total_array[$item->slug]['ma'] + $patient_entries[$item->slug]->school_ma;
                        }
                        else 
                        {
                          $school_ma = '';
                        }
                      }
                      else 
                      {
                        $school_ma = '';
                      }
                  
                      if(isset($patient_entries[$item->slug]->school_fa) && $patient_entries[$item->slug]->school_fa)
                      {
                        if($patient_entries[$item->slug]->school_fa != 0)
                        {
                          $school_fa = $patient_entries[$item->slug]->school_fa;

                          $total_array[$item->slug]['fa'] = $total_array[$item->slug]['fa'] + $patient_entries[$item->slug]->school_fa;
                        }
                        else 
                        {
                          $school_fa = '';
                        }
                      }
                      else 
                      {
                        $school_fa = '';
                      }
                  
                      if(isset($patient_entries[$item->slug]->school_mc) && $patient_entries[$item->slug]->school_mc)
                      {
                        if($patient_entries[$item->slug]->school_mc != 0)
                        {
                          $school_mc = $patient_entries[$item->slug]->school_mc;

                          $total_array[$item->slug]['mc'] = $total_array[$item->slug]['mc'] + $patient_entries[$item->slug]->school_mc;
                        }
                        else 
                        {
                          $school_mc = '';
                        }
                      }
                      else 
                      {
                        $school_mc = '';
                      }
                  
                      if(isset($patient_entries[$item->slug]->school_fc) && $patient_entries[$item->slug]->school_fc)
                      {
                        if($patient_entries[$item->slug]->school_fc != 0)
                        {
                          $school_fc = $patient_entries[$item->slug]->school_fc;
                          echo "<pre>";
                          echo "<br>";
                          echo "--------------------------------";
                          echo "<br>";
                          echo $item->slug;
                          print_r($total_array);
                          echo "</pre>";
                          // die();
                          $total_array[$item->slug]['fc'] = $total_array[$item->slug]['fc'] + $patient_entries[$item->slug]->school_fc;
                        }
                        else 
                        {
                          $school_fc = '';
                        }
                      }
                      else 
                      {
                        $school_fc = '';
                      }
                    @endphp
                    <tr>
                      <td class="tg-zvdi">{{ $item->category_name }}</td>
                      {{-- {{ isset($patient_entries[$item->slug]->school_ma']) ?$patient_entries[$item->slug]->school_ma']:''; }} --}}
                      <td class="tg-tkru">{{ ($school_ma)?$school_ma:'-'; }}</td>
                      <td class="tg-tkru">{{ ($school_fa)?$school_fa:'-'; }}</td>
                      <td class="tg-tkru">{{ ($school_mc)?$school_mc:'-'; }}</td>
                      <td class="tg-tkru">{{ ($school_fc)?$school_fc:'-'; }}</td>
                      <td class="tg-tkru"><b>
                        @php $total = (int)$school_ma + (int)$school_fa + (int)$school_mc + (int)$school_fc @endphp {{ $total != 0 ? $total:'-' }}</b></td>
                    </tr>
                    @endforeach
                  
                  
                  </tbody>
                  </table>

                </div>


                <div class="tab-pane p-20" id="{{ $category['slug']}}-total" role="tabpanel">

                  @php
                            
                  $ma_total = 0;
                  $fa_total = 0;
                  $mc_total = 0;
                  $fc_total = 0;
                  
                  foreach($patient_entries as $key => $entry)
                    {
                      if(isset($total_array[$key]['ma']))
                        {
                          $ma_total = $ma_total + $total_array[$key]['ma'];
                        }
                      
                      if(isset($total_array[$key]['fa']))
                        {  
                          $fa_total = $fa_total + $total_array[$key]['fa'];
                        }
                      
                      if(isset($total_array[$key]['mc']))
                        {
                          $mc_total = $mc_total + $total_array[$key]['mc'];
                        }
                      
                      if(isset($total_array[$key]['fc']))
                        {  
                          $fc_total = $fc_total + $total_array[$key]['fc'];
                        }  
           

                    }
                  
                  
                  @endphp
                  
                  
                  <table class="table tg">
                  <thead>
                    <tr>
                      <th class="tg-p81p" rowspan="2">Activities Done</th>
                      <th class="tg-jldi" colspan="5">Clinics</th>
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
     
                    <tr>
                      <td class="tg-zvdi">{{ $item->category_name }}</td>
                      {{-- {{ isset($patient_entries[$item->slug]->school_ma']) ?$patient_entries[$item->slug]->school_ma']:''; }} --}}
                      <td class="tg-tkru">{{ (isset($total_array[$item->slug]['ma']))?$total_array[$item->slug]['ma']:'-' }}</td>
                      <td class="tg-tkru">{{ (isset($total_array[$item->slug]['fa']))?$total_array[$item->slug]['fa']:'-'  }}</td>
                      <td class="tg-tkru">{{ (isset($total_array[$item->slug]['mc']))?$total_array[$item->slug]['mc']:'-'  }}</td>
                      <td class="tg-tkru">{{ (isset($total_array[$item->slug]['fc']))?$total_array[$item->slug]['fc']:'-'  }}</td>

                      <td class="tg-tkru"><b>
                        @php $total = isset($total_array[$item->slug]['ma']) + isset($total_array[$item->slug]['fa']) + isset($total_array[$item->slug]['mc']) + isset($total_array[$item->slug]['fc']) @endphp {{ $total != 0 ? $total:'-' }}</b></td>
                    </tr>
                    @endforeach
                  
                  
                  </tbody>
                  </table>

                </div>



              
              </div>
              <!-- if there are creation errors, they will show here -->
                        
                    </div>
                    </div>
                </div>
            @endforeach
        </div>     
      </div>
  </div>

@endsection