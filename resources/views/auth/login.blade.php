<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Niche Admin - Powerful Bootstrap 4 Dashboard and Admin Template</title>
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />

<!-- v4.0.0-alpha.6 -->
<link rel="stylesheet" href="dist/bootstrap/css/bootstrap.min.css">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

<!-- Theme style -->
<link rel="stylesheet" href="dist/css/style.css">
<link rel="stylesheet" href="dist/css/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="dist/css/et-line-font/et-line-font.css">
<link rel="stylesheet" href="dist/css/themify-icons/themify-icons.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>
<body class="hold-transition login-page">
<div class="login-box">


    @if (Session::has('error'))        
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

  <div class="login-box-body">
    <h3 class="login-box-msg">Sign In</h3>
    <form method="GET" action="{{ route('login.userLoginProcess') }}">
      @csrf
      {{-- @method('PUT') --}}
      <div class="form-group mb-3">
          <input type="text" placeholder="Username" id="username" class="form-control" name="username" required
              autofocus>
          @if ($errors->has('username'))
          <span class="text-danger">{{ $errors->first('username') }}</span>
          @endif
      </div>
      <div class="form-group mb-3">
          <input type="password" placeholder="Password" id="password" class="form-control" name="password" required>
          @if ($errors->has('password'))
          <span class="text-danger">{{ $errors->first('password') }}</span>
          @endif
      </div>
      <div class="form-group mb-3">
          <div class="checkbox">
              <label>
                  <input type="checkbox" name="remember"> Remember Me
              </label>
          </div>
      </div>
      <div class="d-grid mx-auto">
          <button type="submit" class="btn btn-dark btn-block">Signin</button>
      </div>
  </form>

    <!-- /.social-auth-links -->

  </div>
  <!-- /.login-box-body --> 
</div>
<!-- /.login-box --> 

<!-- jQuery 3 --> 
<script src="dist/js/jquery.min.js"></script> 

<!-- v4.0.0-alpha.6 --> 
<script src="dist/bootstrap/js/bootstrap.min.js"></script> 

<!-- template --> 
<script src="dist/js/niche.js"></script>
</body>
</html>