<html>
<head>
    <title>Shark App</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container">

<nav class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ URL::to('sharks') }}">shark Alert</a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::to('sharks') }}">View All sharks</a></li>
        <li><a href="{{ URL::to('sharks/create') }}">Create a shark</a>
    </ul>
</nav>
@php
//   print_r($cataract);
@endphp

{{$gender = $cataract->gender}}
<h1>Create a shark</h1>

<!-- if there are creation errors, they will show here -->
<form action="{{ route('cataracts.update',$cataract->cataract_ID) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="record_id" value="{{$cataract->cataract_ID}}" />
     <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="first_name" class="form-control" value="{{$cataract->first_name}}">
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" class="form-control" value="{{$cataract->last_name}}">
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Gender :</label>
                <select class="form-control" name="gender" id="gender">
                  <option value="">Select Gender</option>
                  <option value="Male Adult" {{ ( $gender == 'Male Adult') ? 'selected' : '' }}>Male Adult</option>
                  <option value="Female Adult" {{ ( $gender == 'Female Adult') ? 'selected' : '' }}>Female Adult</option>
                  <option value="Male Child" {{ ( $gender == 'Male Child') ? 'selected' : '' }}>Male Child</option>
                  <option value="Femal Child"  {{ ( $gender == 'Femal Child') ? 'selected' : '' }}>Femal Child</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Age :</label>
                <input type="number" name="age" class="form-control" value="{{$cataract->age}}">
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Mobile No :</label>
                <input type="text" name="mobile_number" class="form-control" value="{{$cataract->mobile_number}}">
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label>Cat. Book Number :</label>
                <input type="text" name="cat_book_number" class="form-control" value="{{$cataract->cat_book_number}}">
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label>Diabetic :</label>
                <select class="form-control" name="diabetic" id="diabetic">
                  <option value="">Select Diabetic Condition</option>
                  <option value="Diabetic">Diabetic</option>
                  <option value="Non Diabetic">Non Diabetic</option>

                </select>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

</form>

</div>
</body>
</html>
