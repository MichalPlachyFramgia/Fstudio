<link href="/assets/css/login.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="/assets/js/login.js"></script>

<div class="pen-title">
  <h1>Login</h1>
</div>
<div class="container">
  <div class="card"></div>
  <div class="card">
    <h1 class="title">Login</h1>
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
      {!! csrf_field() !!}
      <div class="input-container"><br>
        <input type="email" id="Username" class="form-control" autocomplete="off"  name="email">
        <label for="Username">Username</label>
        <div class="bar"></div>
        <br>
      </div>
      <div class="input-container"><br>
        <input type="password" id="Password" required="required" autocomplete="off"  class="form-control" name="password">
        <label for="Password">Password</label>
        <div class="bar"></div>
      </div>
      <div class="button-container">
        <button type="submit" ><span>Go</span></button>
      </div>
    </form>
  </div>
</div>
