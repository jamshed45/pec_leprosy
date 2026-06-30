<style type="">
  table.pdf-tabel-styling { background: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; border-spacing: 0; font-size: 10px; width: 100% }
  table.pdf-tabel-styling th { border: #ededed 1px solid !important; background: #00bcd614; }
  table.pdf-tabel-styling th.sub-heading { border: #ededed 1px solid !important; background: #c9e2e5; text-align: left; }
  table.pdf-tabel-styling td { border: #ededed 1px solid !important; }
  table.pdf-tabel-styling td, table.pdf-tabel-styling th {
      padding: 0.25rem;  }

.dash-heading {  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;  margin: 10px;  }

.info-box { display: block; background: #f2d7d7; width: 98%; margin-bottom: 20px;
      padding: 1%; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 10px; padding-bottom:10px; }
.info-box h5  {  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 15px; margin: 0 0 10px 0;  }
.info-box h5 small{ float: right; }
.info-box .col-lg-4 { margin-right: 10px; display: inline-block; font-size: 12px; }

.alp-logo-container {
    display: table;
    width: 100%;
    height: 100vh;
    text-align: center;
    margin-bottom: 20px;
}

.alp-logo-container > span,
.alp-logo-container > img {
    display: table-cell;
    vertical-align: middle;
}
</style>

<div class="alp-logo-container">
    <img src="{{ asset('dist/img/logo.png') }}" width="100" alt="logo" class="alp-logo">
    <span> AID TO LEPROSY PATIENTS </span>
</div>

@if($search_field_info['search_status'])

    <div class="info-box">
        <h5 >Filters : <small style="font-size: 8px;">{{ date('l jS \of F Y h:i A') }}</small></h5>
            @if($search_field_info['filter_cat_slug'])
            <div class="col-lg-4">
                <b>Category : </b> {{ ucwords(str_replace("-"," ",$search_field_info['filter_cat_slug'])) }}
            </div>
            @endif
            @if($search_field_info['user_id'])
            <div class="col-lg-4">
                <b>User : </b> {{ $search_field_info['user_name'] }}
            </div>
            @endif
            @if($search_field_info['activity_location_id'])
            <div class="col-lg-4">
                <b>Activity location : </b> {{ $search_field_info['activity_name'] }}
            </div>
            @endif
            @if($search_field_info['center_id'])
            <div class="col-lg-4">
                <b>Center : </b> {{ $search_field_info['center_name'] }}
            </div>
            @endif
            @if($search_field_info['filter_date'])
            <div class="col-lg-4">
                <b>Date : </b> {{ $search_field_info['filter_date'] }}
            </div>
            @endif
    </div>

@endif

@if($patient_entries)

    @foreach ($form_categories as $category)
    @if($search_field_info['filter_cat_slug'] && $category['slug'] != $search_field_info['filter_cat_slug'])
        @continue
    @endif
            <h5 class="dash-heading">{{ $category['category_name'] }} </h5>
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

        <table class="pdf-tabel-styling">
            <thead>
            <tr>
                <th class="tg-p81p" rowspan="2" style="text-align: left">Activities Done</th>
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
                <th class="tg-hu93" style="text-align: left">Total</th>
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
                <th class="sub-heading">{{ $item->category_name }}</th>
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
                <th class="sub-heading">{{ $item->category_name }}</th>
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

    @endforeach

@endif
