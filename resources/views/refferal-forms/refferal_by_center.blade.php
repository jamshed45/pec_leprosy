@extends('layout')

@section('content')
<?php 
$user = Auth::user();
// die();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1>Refferal Patients by User</h1>
      <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Refferal Patients</li>
        <li><i class="fa fa-angle-right"></i> Patient's List by user</li>
      </ol>
    </div>
   
    <div class="content">
        <div class="row">
          {{-- @php
            print_r($centers);
            die();
          @endphp --}}
          <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
            <form action="{{ route('refferal-patients-by-center') }}">
              @method('POST')
              @csrf
            <div class="row">  
              <div class="col-md-3">  
                <div class="form-group">
                    <label for="first_name">Select Center :</label>
                    <select class="form-control" name="center_id" id="center_id" required>
                        <option value="">Select User</option>
                        @foreach ($centers as $item)
                            <option value="{{ $item->center_id }}"  {{ ($search_field_info['center_id'] == $item->center_id )?'selected':'' }}>{{ $item->center_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
              </div>
              <div class="col-md-3">  
                <div class="form-group">
                    <label for="first_name">From/Today Date :</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $search_field_info['from_date'] }}" />
                </div>
              </div>
              <div class="col-md-3">  
                <div class="form-group">
                    <label for="first_name">To Date :</label>
                    <input type="date" name="to_date"  class="form-control" value="{{ $search_field_info['to_date'] }}" />
                </div>
              </div>
              <div class="col-md-3">  
                <div class="form-group">
                    <label for="first_name">Month :</label>
                    <input type="month" name="searh_month" class="form-control" value="{{ $search_field_info['searh_month'] }}" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                      <a href="{{ URL::current() }}" class="btn btn-primary">Clear Fields</a> <button type="submit" class="btn btn-primary">Search Refferal</button>
              </div>
            </div>
            </form>
          </div>

          @if($search_field_info['search_status'])

          <div class="col-lg-12 col-xs-12">
            <div class="info-box"> <span class="info-box-icon bg-aqua"><i class="icon-briefcase"></i> <i class="icon-user"></i></span>
              <div class="info-box-content"> <span class="info-box-number">{{ $patient_count }}</span> <span class="info-box-text">Refferal Paitents Count</span> </div>
              <!-- /.info-box-content --> 
            </div>
            <!-- /.info-box --> 
          </div>

          <div class="col-lg-12">
            


            <div class="card card-outline">
              <div class="card-header bg-blue">
                <h5 class="text-white m-b-0">Refferal Patient's List</h5>
              </div>
              <div class="card-body">
                @if($patients != '')
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <td>No</td>
                        @if (!$user->hasRole('pec-user'))
                        <td>Lot's Name</td>
                        @endif
                        <td>Date & Record ID</td>
                        <td>Name</td>
                        <td>Information</td>
                        <td>Cat. Book Number</td>
                        <td>Actions</td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($patients as $patient ) 
                    
                    <tr>
                        <td>{{ $loop->iteration }}
                           
                        </td>
                        @if (!$user->hasRole('pec-user'))
                        @php
                          $user_name = DB::table('users')->where('id', $patient->user_id)->get()->toArray();
                        @endphp
                        <td><b>{{ $user_name[0]->name }}</b></td>
                        @endif
                        <td><?=date('j M, Y', strtotime($patient->entry_at)); ?>
                          <hr>
                          <b>Record ID :</b> <?=$patient->refferal_forms_id; ?>
                        </td>
                        <td>
                          <b>Name : </b><br>{{ $patient->full_name  }} <b>{{ $patient->relation  }}</b> {{ $patient->relative_name  }}
                          <hr class="my-1">
                          <b>Age : </b> {{ $patient->age  }}
                          <hr class="my-1">
                          <b>Gender : </b> 
                          @if ($patient->gender == 'ma')
                            Male Adult
                          @elseif ($patient->gender == 'fa')
                            Female Adult 
                          @elseif ($patient->gender == 'mc')
                            Male Child
                          @else
                            Female Child
                          @endif
                          <hr class="my-1">
                          <b>CNIC : </b> {{ $patient->cnic_number  }}
                          <hr class="my-1">
                          <b>Contact No : </b> {{ $patient->contact_number  }}
                        </small>                        
                        </td>
                        <td>
                          <b>Refer From : </b><br>
                          @php
                          $refer_by =  DB::table('pec_form_categories')
                          ->select('category_name')
                          ->where('pec_form_cat_id', $patient->refer_by)
                          ->first();
                          echo $refer_by->category_name; 
                          @endphp
                          <hr class="my-1">
                          <b>Referral : </b><br>
                          @php
                          $referral_by =  DB::table('pec_form_categories')
                                ->select('category_name')
                                ->where('pec_form_cat_id', $patient->referrals)
                                ->first();
                          echo $referral_by->category_name;

                          $operated_by_check = DB::table('pec_refferal_form_meta')
                                ->where('refferal_forms_id', $patient->refferal_forms_id)
                                ->where('operated_by', 1)
                                // ->select('pec_refferal_form_meta.private')
                                ->first();
               
                          
                          $operated_by = DB::table('pec_refferal_form_meta')
                          ->join('doctors', 'pec_refferal_form_meta.doctor_id', '=', 'doctors.doctor_id')
                          ->join('health_facilities', 'pec_refferal_form_meta.health_facility_id', '=', 'health_facilities.health_facility_id')
                          ->where('refferal_forms_id', $patient->refferal_forms_id)
                          ->where('operated_by', 1)
                          ->select('pec_refferal_form_meta.*', 'doctors.doctor_name', 'health_facilities.health_facility_name')
                          ->first();                                  
                          

                          $refer_to_check = DB::table('pec_refferal_form_meta')
                                ->where('refferal_forms_id', $patient->refferal_forms_id)
                                ->where('refer_to', 1)
                                ->select('pec_refferal_form_meta.private')
                                ->first();


                          $refer_to_array = DB::table('pec_refferal_form_meta')
                                ->join('doctors', 'pec_refferal_form_meta.doctor_id', '=', 'doctors.doctor_id')
                                ->join('health_facilities', 'pec_refferal_form_meta.health_facility_id', '=', 'health_facilities.health_facility_id')
                                ->where('refferal_forms_id', $patient->refferal_forms_id)
                                ->where('refer_to', 1)
                                ->select('pec_refferal_form_meta.*', 'doctors.doctor_name', 'health_facilities.health_facility_name')
                                ->first();
                          
                          @endphp

                          @if ( $refer_to_check->private != 1)   
                            <hr class="my-1">
                            <b>Refer To</b>
                            <hr class="my-1">
                            <small>
                            <b>Health Facility : </b>
                          @if (isset($refer_to_array->health_facility_name))
                              {{ $refer_to_array->health_facility_name }}<br>
                          @endif    
                            <b>Dotor : </b>
                            @if (isset($refer_to_array->doctor_name))
                              {{ $refer_to_array->doctor_name }}
                              @endif    
                            </small>
                          @else
                            <hr class="my-1">
                            <b>Refer To : </b><br>Private
                          @endif
                    
                          @if($operated_by_check)
                          
                            @if ($operated_by_check->private != 1)
                              <hr class="my-1">
                              <b>Operated By</b>
                              <hr class="my-1">
                              <small>
                              <b>Health Facility : </b>
                                {{ $operated_by->health_facility_name  }}<br>
                              <b>Dotor : </b>
                                {{ $operated_by->doctor_name  }}  
                              </small>
                            @else  
                              <hr class="my-1">
                              <b>Operated By :</b>
                              Private 
                            @endif
                            <hr class="my-1">
                            <b>Operation Date :</b>
                            {{ $operated_by_check->operation_date }} 
                          @endif
            
                    
                        </td>
                        <td>{{ $patient->cat_book_number }}</td>
       
                        <!-- we will also add show, edit, and delete buttons -->
                        <td>
                          <a  href="{{ route('refferal-forms.edit',$patient->refferal_forms_id) }}">Update Patient information</a><br>
                          <hr class="my-1">
                          @if(!$operated_by_check)
                            <a  href="{{ URL::to('refferal-forms/operated-by/'.$patient->refferal_forms_id) }}">Add Operation Information</a>
                          @else
                            <a  href="{{ URL::to('refferal-forms/update-perated-by/'.$patient->refferal_forms_id) }}">Update Operation Information</a>
                          @endif
                        </td>
                    </tr>
            
      
                @endforeach
                  </tbody>
       
                </table>
                </div>
                @else
                No Refferal for this User
                @endif


              </div>
            </div>
          </div>

          @endif
        </div>     
      </div>


  </div>

@endsection
