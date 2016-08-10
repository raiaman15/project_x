@extends('layouts.app')

@section('head')
<style type="text/css">
  #why_us
  {
    padding-bottom:50px;
  }
  .g-recaptcha {
      transform:scale(0.70);
  }
</style>
@stop

@section('content')
<!--Navbar-->
<nav class="navbar navbar-dark unique-color">
  <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#collapseEx2">
    <i class="fa fa-bars"></i>
  </button>
  <div class="container">
    <big>
      <div class="collapse navbar-toggleable-xs" id="collapseEx2">
        <a class="navbar-brand" href="http://nehruplace-store.in"><big>PROJECT_X</big></a>
        <ul class="nav navbar-nav">
          <li class="nav-item">
            <a class="nav-link" id="study_link" href="{{ url('/study') }}">STUDY</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="tutor_link" href="{{ url('/tutor') }}">TUTOR</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" id="why_us_link" href="{{ url('/why_us') }}">WHY US</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="contact_us_link" href="{{ url('/contact_us') }}">CONTACT US</a>
          </li>
        </ul>
        <form class="form-inline">
          <button title="EDIT PROFILE" type="button" id="add_more_info" class="btn unique-color" data-toggle="modal" data-target="#myModal" style="width:30px;height:30px;line-height:20px;border-radius: 50%;text-align:center;padding:5px 0px 5px 0px;margin:5px 5px 5px 5px;">
            <small><i class="fa fa-pencil" aria-hidden="true"></i></small>
          </button>
          <a title="LOGOUT" class="btn unique-color white-text" style="width:30px;height:30px;line-height:20px;border-radius: 50%;text-align:center;padding:5px 0px 5px 0px;margin:5px 5px 5px 5px;" href="/logout">
            <small><i class="fa fa-sign-out" aria-hidden="true"></i></small>
          </a>    
        </form>
      </div>
    </big>
  </div>
</nav>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-horizontal" role="form" method="POST" action="{{ url('/add_more_info') }}">
        {{ csrf_field() }}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Kindly fill the following additional details.</h4>
        </div>
        <div class="modal-body">
          <div class="md-form">
            <i class="fa fa-calendar prefix"></i>
            <input type="text" id="user_DOB" class="form-control" name="DOB" value="{{ Auth::user()->DOB }}">
            <label for="user_DOB">Date of birth (DD/MM/YYYY)</label>
          </div>
          <div class="md-form">
            <i class="fa fa-globe prefix"></i>
            <input type="text" id="user_country" class="form-control" name="country" value="{{ Auth::user()->country }}">
            <label for="user_country">Your country</label>
          </div>
          <div class="md-form">
            <i class="fa fa-phone prefix"></i>
            <input type="text" id="user_contact" class="form-control" name="contact" value="{{ Auth::user()->contact }}">
            <label for="user_contact">Your contact number </label>
          </div>
          <div class="md-form">
            <i class="fa fa-university prefix"></i>
            <input type="text" id="user_university" class="form-control" name="university" value="{{ Auth::user()->university }}">
            <label for="user_university">Your university </label>
          </div>
          <div class="md-form">
            <i class="fa fa-book prefix"></i>
            <input type="text" id="user_course" class="form-control" name="course" value="{{ Auth::user()->course }}">
            <label for="user_course">Your course </label>
          </div>
          <div class="md-form">
            <i class="fa fa-hand-o-right prefix"></i>
            <input type="text" id="user_referred_by" class="form-control" name="referred_by" value="{{ Auth::user()->referred_by }}">
            <label for="user_referred_by">Where did you heard about us?</label>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- WHY US -->
<div class="container" id="why_us">
  <div class="row" align="center">
    <div class="card-group">
      <div class="card">
          <div class="card-block">
              <h4 class="card-title">Our Aim</h4>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          </div>
      </div>
    </div>
    <div class="card-group">
      <div class="card">
          <div class="card-block">
              <h4 class="card-title">Pros</h4>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          </div>
      </div>
      <div class="card">
          <div class="card-block">
              <h4 class="card-title">Cons</h4>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          </div>
      </div>
    </div>
    <div class="card-group">
      <div class="card">
          <div class="card-block">
              <h4 class="card-title">Services we offer</h4>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
          </div>
      </div>
    </div>
  </div>
</div>

<nav class="navbar navbar-fixed-bottom navbar-dark unique-color" style="min-height:40px;max-height:40px;">
  <div class="container">
    <p style="width:100%;text-align:center;color:white;font-size:10pt;" class="text-fluid">© {{ date("Y") }} Infroid. All rights reserved.</p>
  </div>
</nav>
@stop

@section('script')
<!-- SCRIPTS -->
<script>
{{ ((empty(Auth::user()->DOB))||(empty(Auth::user()->country))||(empty(Auth::user()->contact))||(empty(Auth::user()->university))||(empty(Auth::user()->course))||(empty(Auth::user()->referred_by))) ? $show=1 : $show=0 }}
@if ($show === 1)
    $("#add_more_info").trigger("click");
@endif
</script>
@stop