<style type="">
  @page { margin: 0px; }
  body { margin: 20px 50px 10px 50px; }

  table.pdf-tabel-styling { background: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; border-spacing: 0; font-size: 10px; width: 100% }
  table.pdf-tabel-styling th { border: #ededed 1px solid !important; background: #00bcd614; }
  table.pdf-tabel-styling td { border: #ededed 1px solid !important; }
  table.pdf-tabel-styling td, table.pdf-tabel-styling th {
      padding: 0.25rem;  }
  
  .dash-heading {  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;  margin: 10px;  }    
  
  .info-box { display: block; background: #f2d7d7; width: 98%; 
      padding: 1%; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 10px; line-height: 20px;  }
  .info-box h5  {  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 15px; margin: 0;  }
  .info-box h5 span{ font-size: 15px; font-weight: 400;  }
  .info-box .col-lg-4 { margin-right: 10px; display: inline-block; font-size: 12px; }
  .info-box .info-box-half { width: 49%; display: inline-block}
  .info-box .info-right-half { text-align: right; }
  
  .lep-info { width: 98%; margin-bottom: 10px;
      padding: 1%; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-size: 20px; padding-bottom:10px;  }
  .lep-info img { display: inline; }
  .lep-info span { display: inline; margin-left: 20px; padding-top: 30px; vertical-align: top } 

  table.locations {  } 

  table.locations { background: #a7ccff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; border-spacing: 0; font-size: 12px; width: 100%; margin-bottom:30px; }
  table.locations th { border: #ededed 1px solid !important; background: #00bcd614; }
  table.locations td { border: #ededed 1px solid !important; }
  table.locations td, table.locations th {
      padding: 0.25rem;  }

      .sign-container {
    float: left;
    background-color: azure;
    border-radius: 15px;
    border: 3px solid #def2f2;
    margin-top: 30px;
}

</style>

<table class="lep-info">
  <tr>
    <td>
      <img src="{{ url('img') }}/logo.jpg" width="85" height="85" />
    </td>
    <td>
      <span>Rawalpindi Leprosy Hospital</span>
    </td>
  </tr>
</table>

<div class="info-box">
  <div class="info-box-half">
    <h5 >Lots' Name : <span>{{ $user->name }}</span></h5>
  </div>
  <div class="info-box-half info-right-half">
    <h5 >Month : <span>{{ date('F-Y', strtotime($monthly_detail['entry_month'])) }}</span></h5>
  </div>
</div>

<table class="locations">
  <tr>
    <td><b>Provice : </b>{{ $area_info->province_name }}</td>
    <td><b>Division : </b>{{ $area_info->division_name }}</td>
    <td><b>District : </b>{{ $area_info->district_name }}</td>
  </tr>
</table>

<table class="pdf-tabel-styling"   >
  <thead class="thead-light">
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Description</th>
      <th scope="col">Amount</th>
    </tr>
  </thead>
  <tbody>

  @php

  
  $ta_detail =  json_decode($monthly_detail['ta_detail'], true);


    $total_TA = 0;
    @endphp  
    @foreach ($ta_detail['date'] as $key => $val)
      <tr>
        <th>{{ $val }}</th>
        <td>{{ $ta_detail['description'][$key] }}</td>
        <td>{{ $ta_detail['amount'][$key] }}
        @php
            $total_TA += $ta_detail['amount'][$key];
        @endphp
        </td>
      </tr>
    @endforeach
    

    <tr>
      <th colspan=1></th>
      <th class="bg-dark text-white">Total</th>
      <th class="bg-success text-white">Rs {{ $total_TA }}/-</th>
    </tr>
  </tbody>
</table>

<div class="sign-container">
  <img src="{{ $monthly_detail->signature }}" class="border" width="200" />
</div>

      


