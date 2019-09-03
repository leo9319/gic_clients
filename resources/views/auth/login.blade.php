<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>GIC Clients | Login</title>
  
  
  
      <link rel="stylesheet" href="css/form_style.css">

  
</head>

<body>

  <div class="page">
  <div class="container">
    <div class="left">
      <div class="login">
          <img src="img/GIC-Logo.png" height="80">
      </div>
      <div class="eula">
        <p>By logging in you agree to the ridiculously long terms that you didn't bother to read</p>
        <a href="{{ route('password.request') }}" id="forgot-password">Forgot Your Password?</a>
      </div>

    </div>
    <div class="right">
      <svg viewBox="0 0 320 300">
        <defs>
          <linearGradient
                          inkscape:collect="always"
                          id="linearGradient"
                          x1="13"
                          y1="193.49992"
                          x2="307"
                          y2="193.49992"
                          gradientUnits="userSpaceOnUse">
            <stop
                  style="stop-color:#ff00ff;"
                  offset="0"
                  id="stop876" />
            <stop
                  style="stop-color:#ff0000;"
                  offset="1"
                  id="stop878" />
          </linearGradient>
        </defs>
        <path d="m 40,120.00016 239.99984,-3.2e-4 c 0,0 24.99263,0.79932 25.00016,35.00016 0.008,34.20084 -25.00016,35 -25.00016,35 h -239.99984 c 0,-0.0205 -25,4.01348 -25,38.5 0,34.48652 25,38.5 25,38.5 h 215 c 0,0 20,-0.99604 20,-25 0,-24.00396 -20,-25 -20,-25 h -190 c 0,0 -20,1.71033 -20,25 0,24.00396 20,25 20,25 h 168.57143" />
      </svg>
      <div class="form">
        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <label for="email">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
            <label for="password">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>
            <input type="submit" id="submit" value="Submit">
        </form>
      </div>
    </div>
  </div>
</div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js'></script>

  

    <script  src="js/index.js"></script>




</body>

</html>
