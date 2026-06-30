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

          @include('patient-entries/patients_entries_grid_part') 


        </div>     
      </div>


  </div>

@endsection
