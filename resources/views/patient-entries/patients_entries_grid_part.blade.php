@if($patient_entries)

@php
    // print_r($search_field_info);
    $filter_user_id = isset($search_field_info['user_id'])?$search_field_info['user_id']:'';
    $filter_activity_location_id = isset($search_field_info['activity_location_id'])?$search_field_info['activity_location_id']:'';
    $filter_center_id = isset($search_field_info['center_id'])?$search_field_info['center_id']:'';
    $filter_tehsil_id = isset($search_field_info['tehsil_id'])?$search_field_info['tehsil_id']:'';
    $filter_district_id = isset($search_field_info['district_id'])?$search_field_info['district_id']:'';
    $filter_division_id = isset($search_field_info['division_id'])?$search_field_info['division_id']:'';
    $filter_province_id = isset($search_field_info['province_id'])?$search_field_info['province_id']:'';
    $filter_from_date = isset($search_field_info['from_date'])?$search_field_info['from_date']:'';
    $filter_to_date = isset($search_field_info['to_date'])?$search_field_info['to_date']:'';
    $filter_searh_month = isset($search_field_info['searh_month'])?$search_field_info['searh_month']:'';
    $filter_year_qauterly = isset($search_field_info['searh_year_qauterly'])?$search_field_info['searh_year_qauterly']:'';
    $filter_searh_qauterly = isset($search_field_info['searh_qauterly'])?$search_field_info['searh_qauterly']:'';


@endphp




<div class="col-lg-12">

    <a href="{{ URL::to('patients-entries/all-patients-entries-admin-report-pdf?user_id=') }}{{ $filter_user_id }}&activity_location_id={{ $filter_activity_location_id }}&center_id={{ $filter_center_id }}&tehsil_id={{ $filter_tehsil_id }}&district_id={{ $filter_district_id }}&division_id={{ $filter_division_id }}&province_id={{ $filter_province_id }}&filter_from_date={{$filter_from_date }}&filter_to_date={{ $filter_to_date }}&filter_searh_month={{ $filter_searh_month }}&filter_year_qauterly={{ $filter_year_qauterly }}&filter_searh_qauterly={{ $filter_searh_qauterly }}" class="btn btn-success mb-3">Download PDF</a>

<div class="info-box">

    @foreach ($form_categories as $category)
    <div class="row">
        <div class="col-lg-12 mb-2">
            <h5 class="dash-heading-ent">{{ $category['category_name'] }} </h5>
            @php
                $form_sub_cat = DB::table('pec_form_categories')
                        ->where('parent_slug', $category['slug'])
                        ->get()
                        ->toArray();

            $total_array = array();

            $ma_total = 0;
            $fa_total = 0;
            $mc_total = 0;
            $fc_total = 0;

            foreach($form_sub_cat as $sub_cat)
            {
                if (array_key_exists($sub_cat->slug,$patient_entries))
                {
                    $ma_total = $ma_total + $patient_entries[$sub_cat->slug]['ma'];
                    $fa_total = $fa_total + $patient_entries[$sub_cat->slug]['fa'];
                    $mc_total = $mc_total + $patient_entries[$sub_cat->slug]['mc'];
                    $fc_total = $fc_total + $patient_entries[$sub_cat->slug]['fc'];
                }
            }

        @endphp

        <table class="table dashboard_entries_table">
            <thead>
            <tr>
                <th class="tg-p81p" rowspan="2">Activities Done</th>
            </tr>
            <tr>
                <th class="tg-u38u" width="50">MA</th>
                <th class="tg-u38u" width="50">FA</th>
                <th class="tg-u38u" width="50">MC</th>
                <th class="tg-u38u" width="50">FC</th>
                <th class="tg-u38u" width="50">Total</th>
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

            @php

            if(isset($patient_entries[$item->slug]['ma']))
                {
                if($patient_entries[$item->slug]['ma'] != 0)
                {
                    $ma = $patient_entries[$item->slug]['ma'];

                    $total_array[$item->slug]['ma'] = $patient_entries[$item->slug]['ma'];

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

                if(isset($patient_entries[$item->slug]['fa']) && $patient_entries[$item->slug]['fa'])
                {
                if($patient_entries[$item->slug]['fa'] != 0)
                {
                    $fa = $patient_entries[$item->slug]['fa'];

                    $total_array[$item->slug]['fa'] = $patient_entries[$item->slug]['fa'];
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

                if(isset($patient_entries[$item->slug]['mc']) && $patient_entries[$item->slug]['mc'])
                {
                if($patient_entries[$item->slug]['mc'] != 0)
                {
                    $mc = $patient_entries[$item->slug]['mc'];

                    // $total_array[$item->slug] = array(
                    //   'mc' => $patient_entries[$item->slug]->mc,
                    // );
                    $total_array[$item->slug]['mc'] = $patient_entries[$item->slug]['mc'];
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

                if(isset($patient_entries[$item->slug]['fc']) && $patient_entries[$item->slug]['fc'])
                {
                if($patient_entries[$item->slug]['fc'] != 0)
                {
                    $fc = $patient_entries[$item->slug]['fc'];

                    // $total_array[$item->slug] = array(
                    //   'fc' => $patient_entries[$item->slug]->fc,
                    // );
                    $total_array[$item->slug]['fc'] = $patient_entries[$item->slug]['fc'];

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

            if(isset($patient_entries[$item->slug]))
                {
            @endphp

            <tr>
                <td class="tg-zvdi">{{ $item->category_name }}</td>
                <td class="tg-tkru">
                {{ ($patient_entries[$item->slug]['ma'])?$patient_entries[$item->slug]['ma']:'-' }}
                </td>
                <td class="tg-tkru">
                {{ ($patient_entries[$item->slug]['fa'])?$patient_entries[$item->slug]['fa']:'-' }}
                </td>
                <td class="tg-tkru">
                {{ ($patient_entries[$item->slug]['mc'])?$patient_entries[$item->slug]['mc']:'-' }}
                </td>
                <td class="tg-tkru">
                {{ ($patient_entries[$item->slug]['fc'])?$patient_entries[$item->slug]['fc']:'-' }}
                </td>
                <td class="tg-tkru"><b>
                @php $total = (int)$ma + (int)$fa + (int)$mc + (int)$fc @endphp {{ $total != 0 ? $total:'-' }}</b></td>
            </tr>
            @php
                } else {
            @endphp
            <input type="hidden" name="patient_entry[{{ $item->slug }}][entry_existing_id]" value="new" {{ $item->entry_status == 'disable'?'disabled':''; }}>
            <tr>
                <td class="tg-zvdi">{{ $item->category_name }}</td>
                <td class="tg-tkru">
                -
                </td>
                <td class="tg-tkru">
                -
                </td>
                <td class="tg-tkru">
                -
                </td>
                <td class="tg-tkru">
                -
                </td>
                <td class="tg-tkru"><b>
                @php $total = (int)$ma + (int)$fa + (int)$mc + (int)$fc @endphp {{ $total != 0 ? $total:'-' }}</b></td>
            </tr>
            @php
                }
            @endphp

            @endforeach

            </tbody>
            </table>

        <!-- if there are creation errors, they will show here -->


        </div>
    </div>
    @endforeach

</div>
</div>
@else
    @if($search_field_info['search_status'])
        <div class="col-lg-12">
            <div class="info-box">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <b>No Entries</b>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
