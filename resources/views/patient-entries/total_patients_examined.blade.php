@extends('layout')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>PEC Entries</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> PEC</li>
        <li><i class="fa fa-angle-right"></i> Entry</li>
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
      // echo "<pre>";
      // print_r($patient_entries);
      // echo "</pre>";
    @endphp
    <div class="content" style="{{ $from_disable }}">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Total Pts. Examination (including Lep.pts)</h5>
              </div>
              <div class="card-body">
                <form action="{{ route('patient-entries.store') }}" method="POST">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <!-- Nav tabs -->
              <ul class="nav nav-tabs customtab2" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#clinics" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">CLINICS</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile7" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">HOME & FIELD VISIT</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#messages7" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">SCHOOL</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#total" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">TOTAL</span></a> </li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane active" id="clinics" role="tabpanel">
                  <div class="pad-20">
                    <h4>CLINICS</h4>
                  </div>

                  @php

                  $clinics_new_patient_ma = isset(($patient_entries['new_patient']['clinics_ma']))?$patient_entries['new_patient']['clinics_ma']:0;
                  $clinics_new_patient_fa = isset($patient_entries['new_patient']['clinics_fa'])?$patient_entries['new_patient']['clinics_fa']:0;
                  $clinics_new_patient_mc = isset($patient_entries['new_patient']['clinics_mc'])?$patient_entries['new_patient']['clinics_mc']:0;
                  $clinics_new_patient_fc = isset($patient_entries['new_patient']['clinics_fc'])?$patient_entries['new_patient']['clinics_fc']:0;

                  $clinics_total_new_patients = $clinics_new_patient_ma+$clinics_new_patient_fa+$clinics_new_patient_mc+$clinics_new_patient_fc;

                  $clinics_old_patient_ma = isset($patient_entries['old_patient']['clinics_ma'])?$patient_entries['old_patient']['clinics_ma']:0;
                  $clinics_old_patient_fa = isset($patient_entries['old_patient']['clinics_fa'])?$patient_entries['old_patient']['clinics_fa']:0;
                  $clinics_old_patient_mc = isset($patient_entries['old_patient']['clinics_mc'])?$patient_entries['old_patient']['clinics_mc']:0;
                  $clinics_old_patient_fc = isset($patient_entries['old_patient']['clinics_fc'])?$patient_entries['old_patient']['clinics_fc']:0;

                  $clinics_total_old_patients = $clinics_old_patient_ma+$clinics_old_patient_fa+$clinics_old_patient_mc+$clinics_old_patient_fc;

                  $clinics_leprosy_patient_ma = isset($patient_entries['leprosy_patient']['clinics_ma'])?$patient_entries['leprosy_patient']['clinics_ma']:0;
                  $clinics_leprosy_patient_fa = isset($patient_entries['leprosy_patient']['clinics_fa'])?$patient_entries['leprosy_patient']['clinics_fa']:0;
                  $clinics_leprosy_patient_mc = isset($patient_entries['leprosy_patient']['clinics_mc'])?$patient_entries['leprosy_patient']['clinics_mc']:0;
                  $clinics_leprosy_patient_fc = isset($patient_entries['leprosy_patient']['clinics_fc'])?$patient_entries['leprosy_patient']['clinics_fc']:0;

                  $clinics_total_leprosy_patients = $clinics_leprosy_patient_ma+$clinics_leprosy_patient_fa+$clinics_leprosy_patient_mc+$clinics_leprosy_patient_fc;

                  $clinics_cataract_one_eye_ma = isset($patient_entries['cataract_one_eye']['clinics_ma'])?$patient_entries['cataract_one_eye']['clinics_ma']:0;
                  $clinics_cataract_one_eye_fa = isset($patient_entries['cataract_one_eye']['clinics_fa'])?$patient_entries['cataract_one_eye']['clinics_fa']:0;
                  $clinics_cataract_one_eye_mc = isset($patient_entries['cataract_one_eye']['clinics_mc'])?$patient_entries['cataract_one_eye']['clinics_mc']:0;
                  $clinics_cataract_one_eye_fc = isset($patient_entries['cataract_one_eye']['clinics_fc'])?$patient_entries['cataract_one_eye']['clinics_fc']:0;

                  $clinics_total_cataract_one_eye_patients = $clinics_cataract_one_eye_ma+$clinics_cataract_one_eye_fa+$clinics_cataract_one_eye_mc+$clinics_cataract_one_eye_fc;

                  $clinics_cataract_both_eye_ma = isset($patient_entries['cataract_both_eye']['clinics_ma'])?$patient_entries['cataract_both_eye']['clinics_ma']:0;
                  $clinics_cataract_both_eye_fa = isset($patient_entries['cataract_both_eye']['clinics_fa'])?$patient_entries['cataract_both_eye']['clinics_fa']:0;
                  $clinics_cataract_both_eye_mc = isset($patient_entries['cataract_both_eye']['clinics_mc'])?$patient_entries['cataract_both_eye']['clinics_mc']:0;
                  $clinics_cataract_both_eye_fc = isset($patient_entries['cataract_both_eye']['clinics_fc'])?$patient_entries['cataract_both_eye']['clinics_fc']:0;

                  $clinics_total_cataract_both_eye_patients = $clinics_cataract_both_eye_ma+$clinics_cataract_both_eye_fa+$clinics_cataract_both_eye_mc+$clinics_cataract_both_eye_fc;

                  $clinics_glaucoma_patient_ma = isset($patient_entries['glaucoma_patient']['clinics_ma'])?$patient_entries['glaucoma_patient']['clinics_ma']:0;
                  $clinics_glaucoma_patient_fa = isset($patient_entries['glaucoma_patient']['clinics_fa'])?$patient_entries['glaucoma_patient']['clinics_fa']:0;
                  $clinics_glaucoma_patient_mc = isset($patient_entries['glaucoma_patient']['clinics_mc'])?$patient_entries['glaucoma_patient']['clinics_mc']:0;
                  $clinics_glaucoma_patient_fc = isset($patient_entries['glaucoma_patient']['clinics_fc'])?$patient_entries['glaucoma_patient']['clinics_fc']:0;

                  $clinics_total_glaucoma_patients = $clinics_glaucoma_patient_ma+$clinics_glaucoma_patient_fa+$clinics_glaucoma_patient_mc+$clinics_glaucoma_patient_fc;

                  $clinics_patient_examined_ma = $clinics_new_patient_ma+$clinics_old_patient_ma+$clinics_leprosy_patient_ma+$clinics_cataract_one_eye_ma+$clinics_cataract_both_eye_ma+$clinics_glaucoma_patient_ma;

                  $clinics_patient_examined_fa = $clinics_new_patient_fa+$clinics_old_patient_fa+$clinics_leprosy_patient_fa+$clinics_cataract_one_eye_fa+$clinics_cataract_both_eye_fa+$clinics_glaucoma_patient_fa;

                  $clinics_patient_examined_mc = $clinics_new_patient_mc+$clinics_old_patient_mc+$clinics_leprosy_patient_mc+$clinics_cataract_one_eye_mc+$clinics_cataract_both_eye_mc+$clinics_glaucoma_patient_mc;

                  $clinics_patient_examined_fc = $clinics_new_patient_fc+$clinics_old_patient_fc+$clinics_leprosy_patient_fc+$clinics_cataract_one_eye_fc+$clinics_cataract_both_eye_fc+$clinics_glaucoma_patient_fc;


                  $clinics_total_examined = $clinics_patient_examined_ma+$clinics_patient_examined_fa+$clinics_patient_examined_mc+$clinics_patient_examined_fc;

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
                      <td class="tg-hu93">{{ $clinics_patient_examined_ma }}</td>
                      <td class="tg-hu93">{{ $clinics_patient_examined_fa }}</td>
                      <td class="tg-hu93">{{ $clinics_patient_examined_mc }}</td>
                      <td class="tg-hu93">{{ $clinics_patient_examined_fc }}</td>
                      <td class="tg-hu93">{{ $clinics_total_examined }}</td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">New Patients</td>

                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][clinics_ma]" value="{{ $clinics_new_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][clinics_fa]" value="{{ $clinics_new_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][clinics_mc]" value="{{ $clinics_new_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][clinics_fc]" value="{{ $clinics_new_patient_fc }}" /></td>
                      <td class="tg-tkru"><b>{{ $clinics_total_new_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Old Patients</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][clinics_ma]" value="{{ $clinics_old_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][clinics_fa]" value="{{ $clinics_old_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][clinics_mc]" value="{{ $clinics_old_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][clinics_fc]" value="{{ $clinics_old_patient_fc }}" /></td>
                      <td class="tg-tkru"><b>{{ $clinics_total_old_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Leprosy Patients</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][clinics_ma]" value="{{ $clinics_leprosy_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][clinics_fa]" value="{{ $clinics_leprosy_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][clinics_mc]" value="{{ $clinics_leprosy_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][clinics_fc]" value="{{  $clinics_leprosy_patient_fc }}" /></td>
                      <td class="tg-tkru"><b>{{ $clinics_total_leprosy_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Cataract (One Eye)</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][clinics_ma]" value="{{ $clinics_cataract_one_eye_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][clinics_fa]" value="{{ $clinics_cataract_one_eye_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][clinics_mc]" value="{{ $clinics_cataract_one_eye_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][clinics_fc]" value="{{ $clinics_cataract_one_eye_fc }}" /></td>
                      <td  class="tg-tkru"><b>{{ $clinics_total_cataract_one_eye_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Cataract (Both Eyes)</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][clinics_ma]" value="{{ $clinics_cataract_both_eye_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][clinics_fa]" value="{{ $clinics_cataract_both_eye_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][clinics_mc]" value="{{ $clinics_cataract_both_eye_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][clinics_fc]" value="{{ $clinics_cataract_both_eye_fc }}" /></td>
                      <td  class="tg-tkru"><b>{{ $clinics_total_cataract_both_eye_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Glaucoma Patients</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][clinics_ma]" value="{{ $clinics_glaucoma_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][clinics_fa]" value="{{ $clinics_glaucoma_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][clinics_mc]" value="{{ $clinics_glaucoma_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][clinics_fc]" value="{{ $clinics_glaucoma_patient_fc}}" /></td>
                      <td  class="tg-tkru"><b>{{ $clinics_total_glaucoma_patients }}</b></td>
                    </tr>

                  </tbody>
                  </table>

                
                </div>
                <div class="tab-pane  p-20" id="profile7" role="tabpanel">
                  <div class="pad-20">
                    <h4>HOME & FIELD VISIT</h4>
                  </div>
                

                  @php

                  $home_field_new_patient_ma = isset(($patient_entries['new_patient']['home_field_ma']))?$patient_entries['new_patient']['home_field_ma']:0;
                  $home_field_new_patient_fa = isset($patient_entries['new_patient']['home_field_fa'])?$patient_entries['new_patient']['home_field_fa']:0;
                  $home_field_new_patient_mc = isset($patient_entries['new_patient']['home_field_mc'])?$patient_entries['new_patient']['home_field_mc']:0;
                  $home_field_new_patient_fc = isset($patient_entries['new_patient']['home_field_fc'])?$patient_entries['new_patient']['home_field_fc']:0;

                  $home_field_total_new_patients = $home_field_new_patient_ma+$home_field_new_patient_fa+$home_field_new_patient_mc+$home_field_new_patient_fc;

                  $home_field_old_patient_ma = isset($patient_entries['old_patient']['home_field_ma'])?$patient_entries['old_patient']['home_field_ma']:0;
                  $home_field_old_patient_fa = isset($patient_entries['old_patient']['home_field_fa'])?$patient_entries['old_patient']['home_field_fa']:0;
                  $home_field_old_patient_mc = isset($patient_entries['old_patient']['home_field_mc'])?$patient_entries['old_patient']['home_field_mc']:0;
                  $home_field_old_patient_fc = isset($patient_entries['old_patient']['home_field_fc'])?$patient_entries['old_patient']['home_field_fc']:0;

                  $home_field_total_old_patients = $home_field_old_patient_ma+$home_field_old_patient_fa+$home_field_old_patient_mc+$home_field_old_patient_fc;

                  $home_field_leprosy_patient_ma = isset($patient_entries['leprosy_patient']['home_field_ma'])?$patient_entries['leprosy_patient']['home_field_ma']:0;
                  $home_field_leprosy_patient_fa = isset($patient_entries['leprosy_patient']['home_field_fa'])?$patient_entries['leprosy_patient']['home_field_fa']:0;
                  $home_field_leprosy_patient_mc = isset($patient_entries['leprosy_patient']['home_field_mc'])?$patient_entries['leprosy_patient']['home_field_mc']:0;
                  $home_field_leprosy_patient_fc = isset($patient_entries['leprosy_patient']['home_field_fc'])?$patient_entries['leprosy_patient']['home_field_fc']:0;

                  $home_field_total_leprosy_patients = $home_field_leprosy_patient_ma+$home_field_leprosy_patient_fa+$home_field_leprosy_patient_mc+$home_field_leprosy_patient_fc;

                  $home_field_cataract_one_eye_ma = isset($patient_entries['cataract_one_eye']['home_field_ma'])?$patient_entries['cataract_one_eye']['home_field_ma']:0;
                  $home_field_cataract_one_eye_fa = isset($patient_entries['cataract_one_eye']['home_field_fa'])?$patient_entries['cataract_one_eye']['home_field_fa']:0;
                  $home_field_cataract_one_eye_mc = isset($patient_entries['cataract_one_eye']['home_field_mc'])?$patient_entries['cataract_one_eye']['home_field_mc']:0;
                  $home_field_cataract_one_eye_fc = isset($patient_entries['cataract_one_eye']['home_field_fc'])?$patient_entries['cataract_one_eye']['home_field_fc']:0;

                  $home_field_total_cataract_one_eye_patients = $home_field_cataract_one_eye_ma+$home_field_cataract_one_eye_fa+$home_field_cataract_one_eye_mc+$home_field_cataract_one_eye_fc;

                  $home_field_cataract_both_eye_ma = isset($patient_entries['cataract_both_eye']['home_field_ma'])?$patient_entries['cataract_both_eye']['home_field_ma']:0;
                  $home_field_cataract_both_eye_fa = isset($patient_entries['cataract_both_eye']['home_field_fa'])?$patient_entries['cataract_both_eye']['home_field_fa']:0;
                  $home_field_cataract_both_eye_mc = isset($patient_entries['cataract_both_eye']['home_field_mc'])?$patient_entries['cataract_both_eye']['home_field_mc']:0;
                  $home_field_cataract_both_eye_fc = isset($patient_entries['cataract_both_eye']['home_field_fc'])?$patient_entries['cataract_both_eye']['home_field_fc']:0;

                  $home_field_total_cataract_both_eye_patients = $home_field_cataract_both_eye_ma+$home_field_cataract_both_eye_fa+$home_field_cataract_both_eye_mc+$home_field_cataract_both_eye_fc;

                  $home_field_glaucoma_patient_ma = isset($patient_entries['glaucoma_patient']['home_field_ma'])?$patient_entries['glaucoma_patient']['home_field_ma']:0;
                  $home_field_glaucoma_patient_fa = isset($patient_entries['glaucoma_patient']['home_field_fa'])?$patient_entries['glaucoma_patient']['home_field_fa']:0;
                  $home_field_glaucoma_patient_mc = isset($patient_entries['glaucoma_patient']['home_field_mc'])?$patient_entries['glaucoma_patient']['home_field_mc']:0;
                  $home_field_glaucoma_patient_fc = isset($patient_entries['glaucoma_patient']['home_field_fc'])?$patient_entries['glaucoma_patient']['home_field_fc']:0;

                  $home_field_total_glaucoma_patients = $home_field_glaucoma_patient_ma+$home_field_glaucoma_patient_fa+$home_field_glaucoma_patient_mc+$home_field_glaucoma_patient_fc;

                  $home_field_patient_examined_ma = $home_field_new_patient_ma+$home_field_old_patient_ma+$home_field_leprosy_patient_ma+$home_field_cataract_one_eye_ma+$home_field_cataract_both_eye_ma+$home_field_glaucoma_patient_ma;

                  $home_field_patient_examined_fa = $home_field_new_patient_fa+$home_field_old_patient_fa+$home_field_leprosy_patient_fa+$home_field_cataract_one_eye_fa+$home_field_cataract_both_eye_fa+$home_field_glaucoma_patient_fa;

                  $home_field_patient_examined_mc = $home_field_new_patient_mc+$home_field_old_patient_mc+$home_field_leprosy_patient_mc+$home_field_cataract_one_eye_mc+$home_field_cataract_both_eye_mc+$home_field_glaucoma_patient_mc;

                  $home_field_patient_examined_fc = $home_field_new_patient_fc+$home_field_old_patient_fc+$home_field_leprosy_patient_fc+$home_field_cataract_one_eye_fc+$home_field_cataract_both_eye_fc+$home_field_glaucoma_patient_fc;


                  $home_field_total_examined = $home_field_patient_examined_ma+$home_field_patient_examined_fa+$home_field_patient_examined_mc+$home_field_patient_examined_fc;

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
                      <td class="tg-hu93">{{ $home_field_patient_examined_ma }}</td>
                      <td class="tg-hu93">{{ $home_field_patient_examined_fa }}</td>
                      <td class="tg-hu93">{{ $home_field_patient_examined_mc }}</td>
                      <td class="tg-hu93">{{ $home_field_patient_examined_fc }}</td>
                      <td class="tg-hu93">{{ $home_field_total_examined }}</td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">New Patients</td>

                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][home_field_ma]" value="{{ $home_field_new_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][home_field_fa]" value="{{ $home_field_new_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][home_field_mc]" value="{{ $home_field_new_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][home_field_fc]" value="{{ $home_field_new_patient_fc }}" /></td>
                      <td class="tg-tkru"><b>{{ $home_field_total_new_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Old Patients</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][home_field_ma]" value="{{ $home_field_old_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][home_field_fa]" value="{{ $home_field_old_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][home_field_mc]" value="{{ $home_field_old_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][home_field_fc]" value="{{ $home_field_old_patient_fc }}" /></td>
                      <td class="tg-tkru"><b>{{ $home_field_total_old_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Leprosy Patients</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][home_field_ma]" value="{{ $home_field_leprosy_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][home_field_fa]" value="{{ $home_field_leprosy_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][home_field_mc]" value="{{ $home_field_leprosy_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][home_field_fc]" value="{{  $home_field_leprosy_patient_fc }}" /></td>
                      <td class="tg-tkru"><b>{{ $home_field_total_leprosy_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Cataract (One Eye)</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][home_field_ma]" value="{{ $home_field_cataract_one_eye_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][home_field_fa]" value="{{ $home_field_cataract_one_eye_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][home_field_mc]" value="{{ $home_field_cataract_one_eye_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][home_field_fc]" value="{{ $home_field_cataract_one_eye_fc }}" /></td>
                      <td  class="tg-tkru"><b>{{ $home_field_total_cataract_one_eye_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Cataract (Both Eyes)</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][home_field_ma]" value="{{ $home_field_cataract_both_eye_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][home_field_fa]" value="{{ $home_field_cataract_both_eye_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][home_field_mc]" value="{{ $home_field_cataract_both_eye_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][home_field_fc]" value="{{ $home_field_cataract_both_eye_fc }}" /></td>
                      <td  class="tg-tkru"><b>{{ $home_field_total_cataract_both_eye_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Glaucoma Patients</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][home_field_ma]" value="{{ $home_field_glaucoma_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][home_field_fa]" value="{{ $home_field_glaucoma_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][home_field_mc]" value="{{ $home_field_glaucoma_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][home_field_fc]" value="{{ $home_field_glaucoma_patient_fc}}" /></td>
                      <td  class="tg-tkru"><b>{{ $home_field_total_glaucoma_patients }}</b></td>
                    </tr>

                  </tbody>
                  </table>
                
    

             


                </div>
                <div class="tab-pane p-20" id="messages7" role="tabpanel">
                  <div class="pad-20">
                    <h4>SCHOOL</h4>
                  </div>

                  @php

                  $school_new_patient_ma = isset(($patient_entries['new_patient']['school_ma']))?$patient_entries['new_patient']['school_ma']:0;
                  $school_new_patient_fa = isset($patient_entries['new_patient']['school_fa'])?$patient_entries['new_patient']['school_fa']:0;
                  $school_new_patient_mc = isset($patient_entries['new_patient']['school_mc'])?$patient_entries['new_patient']['school_mc']:0;
                  $school_new_patient_fc = isset($patient_entries['new_patient']['school_fc'])?$patient_entries['new_patient']['school_fc']:0;

                  $school_total_new_patients = $school_new_patient_ma+$school_new_patient_fa+$school_new_patient_mc+$school_new_patient_fc;

                  $school_old_patient_ma = isset($patient_entries['old_patient']['school_ma'])?$patient_entries['old_patient']['school_ma']:0;
                  $school_old_patient_fa = isset($patient_entries['old_patient']['school_fa'])?$patient_entries['old_patient']['school_fa']:0;
                  $school_old_patient_mc = isset($patient_entries['old_patient']['school_mc'])?$patient_entries['old_patient']['school_mc']:0;
                  $school_old_patient_fc = isset($patient_entries['old_patient']['school_fc'])?$patient_entries['old_patient']['school_fc']:0;

                  $school_total_old_patients = $school_old_patient_ma+$school_old_patient_fa+$school_old_patient_mc+$school_old_patient_fc;

                  $school_leprosy_patient_ma = isset($patient_entries['leprosy_patient']['school_ma'])?$patient_entries['leprosy_patient']['school_ma']:0;
                  $school_leprosy_patient_fa = isset($patient_entries['leprosy_patient']['school_fa'])?$patient_entries['leprosy_patient']['school_fa']:0;
                  $school_leprosy_patient_mc = isset($patient_entries['leprosy_patient']['school_mc'])?$patient_entries['leprosy_patient']['school_mc']:0;
                  $school_leprosy_patient_fc = isset($patient_entries['leprosy_patient']['school_fc'])?$patient_entries['leprosy_patient']['school_fc']:0;

                  $school_total_leprosy_patients = $school_leprosy_patient_ma+$school_leprosy_patient_fa+$school_leprosy_patient_mc+$school_leprosy_patient_fc;

                  $school_cataract_one_eye_ma = isset($patient_entries['cataract_one_eye']['school_ma'])?$patient_entries['cataract_one_eye']['school_ma']:0;
                  $school_cataract_one_eye_fa = isset($patient_entries['cataract_one_eye']['school_fa'])?$patient_entries['cataract_one_eye']['school_fa']:0;
                  $school_cataract_one_eye_mc = isset($patient_entries['cataract_one_eye']['school_mc'])?$patient_entries['cataract_one_eye']['school_mc']:0;
                  $school_cataract_one_eye_fc = isset($patient_entries['cataract_one_eye']['school_fc'])?$patient_entries['cataract_one_eye']['school_fc']:0;

                  $school_total_cataract_one_eye_patients = $school_cataract_one_eye_ma+$school_cataract_one_eye_fa+$school_cataract_one_eye_mc+$school_cataract_one_eye_fc;

                  $school_cataract_both_eye_ma = isset($patient_entries['cataract_both_eye']['school_ma'])?$patient_entries['cataract_both_eye']['school_ma']:0;
                  $school_cataract_both_eye_fa = isset($patient_entries['cataract_both_eye']['school_fa'])?$patient_entries['cataract_both_eye']['school_fa']:0;
                  $school_cataract_both_eye_mc = isset($patient_entries['cataract_both_eye']['school_mc'])?$patient_entries['cataract_both_eye']['school_mc']:0;
                  $school_cataract_both_eye_fc = isset($patient_entries['cataract_both_eye']['school_fc'])?$patient_entries['cataract_both_eye']['school_fc']:0;

                  $school_total_cataract_both_eye_patients = $school_cataract_both_eye_ma+$school_cataract_both_eye_fa+$school_cataract_both_eye_mc+$school_cataract_both_eye_fc;

                  $school_glaucoma_patient_ma = isset($patient_entries['glaucoma_patient']['school_ma'])?$patient_entries['glaucoma_patient']['school_ma']:0;
                  $school_glaucoma_patient_fa = isset($patient_entries['glaucoma_patient']['school_fa'])?$patient_entries['glaucoma_patient']['school_fa']:0;
                  $school_glaucoma_patient_mc = isset($patient_entries['glaucoma_patient']['school_mc'])?$patient_entries['glaucoma_patient']['school_mc']:0;
                  $school_glaucoma_patient_fc = isset($patient_entries['glaucoma_patient']['school_fc'])?$patient_entries['glaucoma_patient']['school_fc']:0;

                  $school_total_glaucoma_patients = $school_glaucoma_patient_ma+$school_glaucoma_patient_fa+$school_glaucoma_patient_mc+$school_glaucoma_patient_fc;

                  $school_patient_examined_ma = $school_new_patient_ma+$school_old_patient_ma+$school_leprosy_patient_ma+$school_cataract_one_eye_ma+$school_cataract_both_eye_ma+$school_glaucoma_patient_ma;

                  $school_patient_examined_fa = $school_new_patient_fa+$school_old_patient_fa+$school_leprosy_patient_fa+$school_cataract_one_eye_fa+$school_cataract_both_eye_fa+$school_glaucoma_patient_fa;

                  $school_patient_examined_mc = $school_new_patient_mc+$school_old_patient_mc+$school_leprosy_patient_mc+$school_cataract_one_eye_mc+$school_cataract_both_eye_mc+$school_glaucoma_patient_mc;

                  $school_patient_examined_fc = $school_new_patient_fc+$school_old_patient_fc+$school_leprosy_patient_fc+$school_cataract_one_eye_fc+$school_cataract_both_eye_fc+$school_glaucoma_patient_fc;


                  $school_total_examined = $school_patient_examined_ma+$school_patient_examined_fa+$school_patient_examined_mc+$school_patient_examined_fc;

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
                      <td class="tg-hu93">{{ $school_patient_examined_ma }}</td>
                      <td class="tg-hu93">{{ $school_patient_examined_fa }}</td>
                      <td class="tg-hu93">{{ $school_patient_examined_mc }}</td>
                      <td class="tg-hu93">{{ $school_patient_examined_fc }}</td>
                      <td class="tg-hu93">{{ $school_total_examined }}</td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">New Patients</td>

                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][school_ma]" value="{{ $school_new_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][school_fa]" value="{{ $school_new_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][school_mc]" value="{{ $school_new_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[new_patient][school_fc]" value="{{ $school_new_patient_fc }}" /></td>
                      <td class="tg-tkru"><b>{{ $school_total_new_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Old Patients</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][school_ma]" value="{{ $school_old_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][school_fa]" value="{{ $school_old_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][school_mc]" value="{{ $school_old_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[old_patient][school_fc]" value="{{ $school_old_patient_fc }}" /></td>
                      <td class="tg-tkru"><b>{{ $school_total_old_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Leprosy Patients</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][school_ma]" value="{{ $school_leprosy_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][school_fa]" value="{{ $school_leprosy_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][school_mc]" value="{{ $school_leprosy_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][school_fc]" value="{{  $school_leprosy_patient_fc }}" /></td>
                      <td class="tg-tkru"><b>{{ $school_total_leprosy_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Cataract (One Eye)</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][school_ma]" value="{{ $school_cataract_one_eye_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][school_fa]" value="{{ $school_cataract_one_eye_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][school_mc]" value="{{ $school_cataract_one_eye_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][school_fc]" value="{{ $school_cataract_one_eye_fc }}" /></td>
                      <td  class="tg-tkru"><b>{{ $school_total_cataract_one_eye_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Cataract (Both Eyes)</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][school_ma]" value="{{ $school_cataract_both_eye_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][school_fa]" value="{{ $school_cataract_both_eye_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][school_mc]" value="{{ $school_cataract_both_eye_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][school_fc]" value="{{ $school_cataract_both_eye_fc }}" /></td>
                      <td  class="tg-tkru"><b>{{ $school_total_cataract_both_eye_patients }}</b></td>
                    </tr>
                    <tr>
                      <td class="tg-zvdi">Glaucoma Patients</td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][school_ma]" value="{{ $school_glaucoma_patient_ma }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][school_fa]" value="{{ $school_glaucoma_patient_fa }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][school_mc]" value="{{ $school_glaucoma_patient_mc }}" /></td>
                      <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][school_fc]" value="{{ $school_glaucoma_patient_fc}}" /></td>
                      <td  class="tg-tkru"><b>{{ $school_total_glaucoma_patients }}</b></td>
                    </tr>

                  </tbody>
                  </table>

                </div>



                <div class="tab-pane p-20" id="total" role="tabpanel">
                  <div class="pad-20">
                    <h4>TOTAL</h4>
                  </div>

                  @php

                    $new_patient_total_ma = $clinics_new_patient_ma+$home_field_new_patient_ma+$school_new_patient_ma;
                    $new_patient_total_fa = $clinics_new_patient_fa+$home_field_new_patient_fa+$school_new_patient_fa;
                    $new_patient_total_mc = $clinics_new_patient_mc+$home_field_new_patient_mc+$school_new_patient_mc;
                    $new_patient_total_fc = $clinics_new_patient_fc+$home_field_new_patient_fc+$school_new_patient_fc;

                    $school_old_patient_totle_ma =  $clinics_new_patient_ma+$home_field_old_patient_ma+$school_old_patient_ma;
                    $school_old_patient_totle_fa =  $clinics_new_patient_fa+$home_field_old_patient_fa+$school_old_patient_fa;
                    $school_old_patient_totle_mc =  $clinics_new_patient_mc+$home_field_old_patient_mc+$school_old_patient_mc;
                    $school_old_patient_totle_fc =  $clinics_new_patient_fc+$home_field_old_patient_fc+$school_old_patient_fc;
                  
                  @endphp

                  {{-- <table class="table tg">
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
                        <td class="tg-hu93">{{ $school_patient_examined_ma }}</td>
                        <td class="tg-hu93">{{ $school_patient_examined_fa }}</td>
                        <td class="tg-hu93">{{ $school_patient_examined_mc }}</td>
                        <td class="tg-hu93">{{ $school_patient_examined_fc }}</td>
                        <td class="tg-hu93">{{ $school_total_examined }}</td>
                      </tr>
                      <tr>
                        <td class="tg-zvdi">New Patients</td>
  
                        <td class="tg-tkru">{{ $new_patient_total_ma }}</td>
                        <td class="tg-tkru">{{ $new_patient_total_fa }}</td>
                        <td class="tg-tkru">{{ $new_patient_total_mc }}</td>
                        <td class="tg-tkru">{{ $new_patient_total_fc }}</td>
                        <td class="tg-tkru"><b>{{ $school_total_new_patients }}</b></td>
                      </tr>
                      <tr>
                        <td class="tg-zvdi">Old Patients</td>
                        <td class="tg-tkru">{{ $school_old_patient_totle_ma }}</td>
                        <td class="tg-tkru">{{ $school_old_patient_totle_fa }}</td>
                        <td class="tg-tkru">{{ $school_old_patient_totle_mc }}</td>
                        <td class="tg-tkru">{{ $school_old_patient_totle_fc }}</td>
                        <td class="tg-tkru"><b>{{ $school_total_old_patients }}</b></td>
                      </tr>
                      <tr>
                        <td class="tg-zvdi">Leprosy Patients</td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][school_ma]" value="{{ $school_leprosy_patient_ma }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][school_fa]" value="{{ $school_leprosy_patient_fa }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][school_mc]" value="{{ $school_leprosy_patient_mc }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[leprosy_patient][school_fc]" value="{{  $school_leprosy_patient_fc }}" /></td>
                        <td class="tg-tkru"><b>{{ $school_total_leprosy_patients }}</b></td>
                      </tr>
                      <tr>
                        <td class="tg-zvdi">Cataract (One Eye)</td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][school_ma]" value="{{ $school_cataract_one_eye_ma }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][school_fa]" value="{{ $school_cataract_one_eye_fa }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][school_mc]" value="{{ $school_cataract_one_eye_mc }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[cataract_one_eye][school_fc]" value="{{ $school_cataract_one_eye_fc }}" /></td>
                        <td  class="tg-tkru"><b>{{ $school_total_cataract_one_eye_patients }}</b></td>
                      </tr>
                      <tr>
                        <td class="tg-zvdi">Cataract (Both Eyes)</td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][school_ma]" value="{{ $school_cataract_both_eye_ma }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][school_fa]" value="{{ $school_cataract_both_eye_fa }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][school_mc]" value="{{ $school_cataract_both_eye_mc }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[cataract_both_eye][school_fc]" value="{{ $school_cataract_both_eye_fc }}" /></td>
                        <td  class="tg-tkru"><b>{{ $school_total_cataract_both_eye_patients }}</b></td>
                      </tr>
                      <tr>
                        <td class="tg-zvdi">Glaucoma Patients</td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][school_ma]" value="{{ $school_glaucoma_patient_ma }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][school_fa]" value="{{ $school_glaucoma_patient_fa }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][school_mc]" value="{{ $school_glaucoma_patient_mc }}" /></td>
                        <td class="tg-tkru"><input type="number" name="patient_entry[glaucoma_patient][school_fc]" value="{{ $school_glaucoma_patient_fc}}" /></td>
                        <td  class="tg-tkru"><b>{{ $school_total_glaucoma_patients }}</b></td>
                      </tr>
  
                    </tbody>
                    </table> --}}

                </div>

              </div>
              <!-- if there are creation errors, they will show here -->
              
              <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                              <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
              </div>
                  
              </form>

              </div>
            </div>
          </div>
        </div>     
      </div>
  </div>


@endsection