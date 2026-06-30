@php
        $user = Auth::user();

        $query = DB::table('pec_refferal_forms')
                     ->where('gender', 'ma');
        if($user->hasRole('pec-user'))
        {
          $query->where('user_id', $user->id);
        }             
        $ma_gender_count =  $query->count();     
          

        $query = DB::table('pec_refferal_forms')
                    ->where('gender', 'fa');
        if($user->hasRole('pec-user'))
        {
          $query->where('user_id', $user->id);
        }             
        $fa_gender_count =  $query->count();            
                    

        $query = DB::table('pec_refferal_forms')
                    ->where('gender', 'mc');
        if($user->hasRole('pec-user'))
        {
          $query->where('user_id', $user->id);
        }             
        $mc_gender_count =  $query->count();          
                    

        $query = DB::table('pec_refferal_forms')
                    ->where('gender', 'fc');
        if($user->hasRole('pec-user'))
        {
          $query->where('user_id', $user->id);
        }             
        $fc_gender_count =  $query->count();           
                    
        
        $referral = DB::table('pec_form_categories')
        ->where('parent_slug', 'referrals')
                    ->get()
                    ->toArray();


        $query = DB::table('pec_refferal_forms')
                    ->where('refer_by', 39);
        if($user->hasRole('pec-user'))
        {
          $query->where('user_id', $user->id);
        }             
        $phc_staff_count =  $query->count();                    

        
        $query = DB::table('pec_refferal_forms')
                    ->where('refer_by', 40);
        if($user->hasRole('pec-user'))
        {
          $query->where('user_id', $user->id);
        }             
        $cat_motivator_count =  $query->count();

        $query = DB::table('pec_refferal_forms')
                    ->where('refer_by', 41);
        if($user->hasRole('pec-user'))
        {
          $query->where('user_id', $user->id);
        }             
        $local_community_count =  $query->count();                                             
@endphp

<div class="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-outline mb-3">
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <b class="bg-info text-white d-block p-2 mb-2">Patient Type</b>
              @foreach ($referral as $item)
                  <small class="pl-2">{{ $item->category_name }} :<b class="float-right mr-1">
                   @php
                     $query = DB::table('pec_refferal_form_meta')
                    ->join('pec_refferal_forms', 'pec_refferal_forms.refferal_forms_id', '=', 'pec_refferal_form_meta.refferal_forms_id')
                    ->where('patient_type', $item->slug)
                    ->where('refer_to', 1);
                  if($user->hasRole('pec-user'))
                  {
                    $query->where('user_id', $user->id);
                  }             
                  $cat_count =  $query->count();  
                   echo $cat_count;
                   @endphp
                   </b>
                   </small><br> 
              @endforeach
            </div>
            <div class="col-md-4">
              <b class="bg-info text-white d-block p-2 mb-2">Gender</b>
              <small class="pl-2">Male Adults : <b class="float-right mr-1">{{ $ma_gender_count }}</b></small><br>
              <small class="pl-2">Female Adults : <b class="float-right mr-1">{{ $fa_gender_count }}</b></small><br>
              <small class="pl-2">Male Childs : <b class="float-right mr-1">{{ $mc_gender_count }}</b></small><br>
              <small class="pl-2">Female Childs : <b class="float-right mr-1">{{ $fc_gender_count }}</b></small><br>
            </div>
            <div class="col-md-4">
              <b class="bg-info text-white d-block p-2 mb-2">Refer By</b>
              <small class="pl-2">PHC Staff : <b class="float-right mr-1">{{ $phc_staff_count }}</b></small><br>
              <small class="pl-2">Cat. Motivator : <b class="float-right mr-1">{{ $cat_motivator_count }}</b></small><br>
              <small class="pl-2">Local Community : <b class="float-right mr-1">{{ $local_community_count }}</b></small><br><br>
              <b class="bg-info text-white d-block p-2 mb-2">Refer To</b>
              <small class="pl-2">Private :<b class="float-right mr-1">
                @php
                  $query = DB::table('pec_refferal_form_meta')
                ->join('pec_refferal_forms', 'pec_refferal_forms.refferal_forms_id', '=', 'pec_refferal_form_meta.refferal_forms_id')
                ->where('private', 1)
                ->where('refer_to', 1);
                if($user->hasRole('pec-user'))
                  {
                    $query->where('user_id', $user->id);
                  }             
                  $private =  $query->count();
                echo $private;
                @endphp</b>
              </small><br>
              <small class="pl-2">Health Facilities :<b class="float-right mr-1">
                @php
                $query = DB::table('pec_refferal_form_meta')
              ->join('pec_refferal_forms', 'pec_refferal_forms.refferal_forms_id', '=', 'pec_refferal_form_meta.refferal_forms_id')
              ->where('health_facility_id', '!=', NULL)
              ->where('refer_to', 1);
              if($user->hasRole('pec-user'))
                  {
                    $query->where('user_id', $user->id);
                  }             
                  $health_facilities =  $query->count();
                echo $health_facilities;
              @endphp </b> 
              </small><br>
              <small class="pl-2">Doctors :<b class="float-right mr-1">
                @php
                $cat_count = DB::table('pec_refferal_form_meta')
              ->join('pec_refferal_forms', 'pec_refferal_forms.refferal_forms_id', '=', 'pec_refferal_form_meta.refferal_forms_id')
              ->where('doctor_id', '!=', NULL)
              ->where('refer_to', 1);
              if($user->hasRole('pec-user'))
                  {
                    $query->where('user_id', $user->id);
                  }             
                  $health_facilities =  $query->count();
                echo $health_facilities;
              @endphp</b>
              </small>
            </div>
            <div class="col-md-4">
              <b class="bg-info text-white d-block p-2 mb-2">Cataract Surgery</b>
              <small class="pl-2">Right Eye :<b class="float-right mr-1">
                @php
                  $query = DB::table('pec_refferal_form_meta')
                ->join('pec_refferal_forms', 'pec_refferal_forms.refferal_forms_id', '=', 'pec_refferal_form_meta.refferal_forms_id')
                ->where('cataract_op_slug', 'cataract-right-eye')
                ->where('refer_to', 1);
                if($user->hasRole('pec-user'))
                  {
                    $query->where('user_id', $user->id);
                  }             
                  $right_eye =  $query->count();
                echo $right_eye;
                @endphp</b>
              </small><br>
              <small class="pl-2">left Eye :<b class="float-right mr-1">
                @php
                  $query = DB::table('pec_refferal_form_meta')
                ->join('pec_refferal_forms', 'pec_refferal_forms.refferal_forms_id', '=', 'pec_refferal_form_meta.refferal_forms_id')
                ->where('cataract_op_slug', 'cataract-left-eye')
                ->where('refer_to', 1);
                if($user->hasRole('pec-user'))
                  {
                    $query->where('user_id', $user->id);
                  }             
                  $left_eye =  $query->count();
                echo $left_eye;
                @endphp</b>
              </small><br>
              <small class="pl-2">Both Eyes :<b class="float-right mr-1">
                @php
                  $query = DB::table('pec_refferal_form_meta')
                ->join('pec_refferal_forms', 'pec_refferal_forms.refferal_forms_id', '=', 'pec_refferal_form_meta.refferal_forms_id')
                ->where('cataract_op_slug', 'cataract-both-eyes')
                ->where('refer_to', 1);
                if($user->hasRole('pec-user'))
                  {
                    $query->where('user_id', $user->id);
                  }             
                  $both_eyes =  $query->count();
                echo $both_eyes;
                @endphp</b>
              </small>
            </div>
            <div class="col-md-4">
              <b class="bg-info text-white d-block p-2 mb-2">Diabetic Status</b>
              <small class="pl-2">Diabetic :<b class="float-right mr-1">
                @php
                $cat_count = DB::table('pec_refferal_form_meta')
              ->join('pec_refferal_forms', 'pec_refferal_forms.refferal_forms_id', '=', 'pec_refferal_form_meta.refferal_forms_id')
              ->where('diabetic', '!=', NULL)
              ->where('refer_to', 1)
              ->count();
              echo $cat_count;
              @endphp</b>
              </small><br>
              <small class="pl-2">Non Diabetic :<b class="float-right mr-1">
                @php
                $cat_count = DB::table('pec_refferal_form_meta')
              ->join('pec_refferal_forms', 'pec_refferal_forms.refferal_forms_id', '=', 'pec_refferal_form_meta.refferal_forms_id')
              ->where('diabetic', '=', NULL)
              ->where('refer_to', 1)
              ->count();
              echo $cat_count;
              @endphp</b>
              </small>
            </div>    
            <div class="col-md-12">
              <hr>
            </div>
          </div>
          

        </div>
      </div>  
    </div>
  </div>
</div>  