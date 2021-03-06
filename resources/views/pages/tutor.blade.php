@extends('layouts.app')

@section('head')
<style type="text/css">
  #tutor
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
        <a class="navbar-brand" href="{{ config('app.url') }}"><big>{{ config('app.app_name') }}</big></a>
        <ul class="nav navbar-nav pull-right">
          <li class="nav-item">
            <a class="nav-link" id="study_link" href="{{ url('/study') }}">LEARNER</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" id="tutor_link" href="{{ url('/tutor') }}">FACILITATOR</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="why_us_link" href="{{ url('/why_us') }}">WHY US</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="contact_us_link" href="{{ url('/contact_us') }}">CONTACT US</a>
          </li>
          <li class="nav-item">
            <a class="nav-link"> </a>
          </li>
          @if (Auth::guest())
          <li class="nav-item">
              <a title="SIGN UP" class="nav-link" href="/register">
                <i class="fa fa-user-plus" aria-hidden="true"></i> SIGN UP
              </a>
          </li>
          <li class="nav-item">
              <a title="SIGN IN" class="nav-link" href="/login">
                <i class="fa fa-sign-in" aria-hidden="true"></i> SIGN IN
              </a>
          </li>
        @else
          
          <li class="nav-item">
              <a title="EDIT PROFILE" type="button" id="add_more_info" class="nav-link" data-toggle="modal" data-target="#myModal">
                <i class="fa fa-user" aria-hidden="true"></i> PROFILE
              </a>
          </li>
          <li class="nav-item">
              <a title="SIGN OUT" class="nav-link" href="/logout">
                <i class="fa fa-sign-out" aria-hidden="true"></i> SIGN OUT
              </a>
          </li>
        @endif
        </ul>
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
            <input type="text" id="user_DOB" class="form-control" name="DOB" value="{{ Auth::user()->DOB }}" autocomplete="off">
            <label for="user_DOB">Date of birth (DD/MM/YYYY)</label>
          </div>
          <div class="md-form">
            <i class="fa fa-globe prefix"></i>
            <input type="text" id="user_country" class="form-control" name="country" value="{{ Auth::user()->country }}" autocomplete="off">
            <label for="user_country">Your country</label>
          </div>
          <div class="md-form">
            <i class="fa fa-phone prefix"></i>
            <input type="text" id="user_contact" class="form-control" name="contact" value="{{ Auth::user()->contact }}" autocomplete="off">
            <label for="user_contact">Your contact number </label>
          </div>
          <div class="md-form">
            <i class="fa fa-university prefix"></i>
            <input type="text" id="user_university" class="form-control" name="university" value="{{ Auth::user()->university }}" autocomplete="off">
            <label for="user_university">Your university </label>
          </div>
          <div class="md-form">
            <i class="fa fa-graduation-cap prefix"></i>
            <input type="text" id="user_course" class="form-control" name="course" value="{{ Auth::user()->course }}" autocomplete="off">
            <label for="user_course">Your course </label>
          </div>
          <div class="md-form">
            <i class="fa fa-hand-o-right prefix"></i>
            <input type="text" id="user_referred_by" class="form-control" name="referred_by" value="{{ Auth::user()->referred_by }}" autocomplete="off">
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

<!-- TUTOR -->
<div class="container" id="tutor">
  <div class="row">
    <div class="card-group">
      <blockquote id="help_message_provide_assistance" class="blockquote bq-danger" style="display:none;margin-bottom:0;">
        <p id="message_provide_assistance_title" class="bq-title"></p>
        <p id="message_provide_assistance"></p>
      </blockquote>
    </div>
    <div class="card-group">
      <div id="provide_assistance_card" class="card card-block">
        <h3 class="card-title" align="center">Provide Assistance</h3>
        <p class="card-text">
          <form id="provide_assistance" class="form-horizontal" role="form" method="POST" action="{{ url('/provide_assistance') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="md-form">
              <i class="fa fa-user prefix"></i>
              <input type="text" id="p_assistance_fullname" name="p_assistance_fullname" class="form-control" value="{{ Auth::user()->name }}" disabled>
              <label for="p_assistance_fullname" class="disabled">Your name</label>
            </div>
            <div class="md-form">
              <i class="fa fa-envelope prefix"></i>
              <input type="email" id="p_assistance_email" name="p_assistance_email" class="form-control validate" value="{{ Auth::user()->email }}" disabled>
              <label for="p_assistance_email" data-error="incorrect format" data-success="corrent format" aria-describedby="emailHelp" class="disabled">Your email</label>
            </div>
            <div class="md-form">
              <i class="fa fa-book prefix"></i>
              <input type="text" id="p_assistance_subject" name="p_assistance_subject" class="form-control validate" placeholder="SUBJECT NAME" required="required" autocomplete="off">
              <label for="p_assistance_subject">Subject in which you provide assistance</label>
            </div>
            <div class="md-form">
              <i class="fa fa-graduation-cap prefix"></i>
              <input type="text" id="p_assistance_subject_course" name="p_assistance_subject_course" class="form-control validate" placeholder="SUBJECT COURSE NAME" required="required" autocomplete="off">
              <label for="p_assistance_subject_course">Course name for the above subject</label>
            </div>
            <div class="md-form">
              <i class="fa fa-university prefix"></i>
              <input type="text" id="p_assistance_subject_university" name="p_assistance_subject_university" class="form-control validate" placeholder="COURSE UNIVERSITY NAME" required="required" autocomplete="off">
              <label for="p_assistance_subject_university">University name for the above course</label>
            </div>
            <div class="md-form">
              <i class="fa fa-pencil prefix"></i>
              <textarea type="text" id="p_assistance_description" name="p_assistance_description" class="md-textarea" autocomplete="off"></textarea>
              <label for="p_assistance_description">What is your qualification?</label>
            </div>
            <div class="md-form" style="padding-bottom:20px;">
              <i class="fa fa-file-text prefix"></i>
              <input type="file" name="p_assistance_document[]" id="p_assistance_document" multiple>
              <label for="p_assistance_document" style="margin-top:10px;">
                <small>Upload your <b>resume</b> and <b>highest degrees certificate</b> (pdf/doc/docx/jpg/png format only)</small>
                <div id="file_upload_status" style="display:none;"><i class="fa fa-upload fa-2x" aria-hidden="true"></i><span id="file_upload_percentage"></span></div>
              </label>
            </div>
            <br/>
            <div class="md-form" align="center">
              <button type="submit" class="btn unique-color">ENROLL</button>
            </div>
          </form>
        </p>
      </div>
      @if (count($provided_assistances) >= 1)
        <ul class="card list-group">
          <li class="list-group-item" align="center"><h3 class="card-title" align="center">Your Assistances</h3></li>
          @foreach ($provided_assistances as $provided_assistance)
            <li class="list-group-item" align="justify">
              @if ($provided_assistance->admin_approved)
                <span class="pull-xs-right"><a href="{{ url('/provide_assistance_detail') }}" class="btn btn-primary-outline btn-sm" style="width:30px;height:30px;line-height:10px;border-radius: 50%;text-align:center;padding:5px 0px 5px 0px;margin:5px 5px 5px 5px;"><i class="fa fa-external-link-square fa-lg" aria-hidden="true"></i></a></span>
                @if ($provided_assistance->earn_approved)
                  <span class="pull-xs-right"><a href="#" class="btn btn-primary-outline btn-sm" style="padding-top:0;padding-bottom:0;">EARN <i class="fa fa-paypal" aria-hidden="true"></i></a></span>
                @else
                  <span class="pull-xs-right"><a href="#" class="btn btn-primary-outline btn-sm disabled" style="padding-top:0;padding-bottom:0;">EARN <i class="fa fa-paypal" aria-hidden="true"></i></a></span>
                @endif
              @else
                <span class="pull-xs-right"><a href="#" class="btn btn-danger-outline btn-sm disabled" style="width:30px;height:30px;line-height:10px;border-radius: 50%;text-align:center;padding:5px 0px 5px 0px;margin:5px 5px 5px 5px;"><i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i></a></span>
              @endif
              <h5 class="list-group-item-heading">{{ $provided_assistance->subject }}</h5>
              <br/>
              <small>{{ substr($provided_assistance->description, 0, 200) }}...</small>
              <br/><br/>
              @if ($provided_assistance->files !== "")
                <?php $files = explode("|", $provided_assistance->files);$count=0;?>
                <h6 class="list-group-item-heading">
                  <a href="#" target="_blank">
                    <small><small>SYLLABUS</small></small>
                    <i class="fa fa-file-text fa-lg" aria-hidden="true"></i>
                  </a>
                  @foreach ($files as $file)
                    @if ($file !== "")
                      <?php $filepart = explode(":", $file);$count++;?>
                      <a href="/download/{{ $filepart[0] }}" target="_blank"><small><small>DOC{{$count}} </small></small><i class="fa {{ $filepart[1] }} fa-lg" aria-hidden="true"></i></a>
                    @endif
                  @endforeach
                </h6>
              @endif
              <span class="label btn-secondary-outline label-pill pull-xs" style="min-width:100%;">{{ $provided_assistance->status }} </span>
            </li>
          @endforeach
          @if($provided_assistances->links())
            <li class="list-group-item" align="center"><nav>{{ $provided_assistances->links() }}</nav></li>
          @endif
        </ul>
      @else
      <div class="card">
        <div class="card-block">
          <h4 class="card-title">About Provide Assistance</h4><hr/>
          <p class="card-text" align="justify">This is a premium service in which we assign a well educated trainer to assist our student clients with a particular subject. The assistance could be foundation strengthening, doubt clearance, exam preparation, homework or assignment assistance, etc. The trainers is bound to value client's time and thus try to take less time and deliver quality assistance.</p>
        </div>
        <div class="card-footer default-color-dark white-text" style="border-radius:0;">
            <p class="card-text"><small>Earn $16 to $40 per assistance based on client's requirement.</small></p>
        </div>
      </div>
      @endif
    </div>
    <div class="card-group">
      <div class="card">
        <div class="card-block">
          <h4 class="card-title">Course Guide</h4><hr/>
          <p class="card-text" align="justify">Our course guides will keep suggesting various details related to client's course. They might suggest client which extra knowledge they should have based on your course. They might also suggest various short term courses based on client's main course to further enhance their knowledge.</p>
          <p class="card-text" align="right"><small class="text-muted">Earn $40 for one client.<br/><a href="#" class="btn btn-primary-outline btn-sm">APPLY</a></small></p>
        </div>
      </div>
      <div class="card">
        <div class="card-block">
          <h4 class="card-title">Career Guide</h4><hr/>
          <p class="card-text" align="justify">Our career guides will keep suggesting various details related to client's career. They might suggest client which extra skills they should have based on their current course or career. They might also suggest various short term or long term courses based on client's main career to further enhance their skills.</p>
          <p class="card-text" align="right"><small class="text-muted">Earn $40 for one client.<br/><a href="#" class="btn btn-primary-outline btn-sm">APPLY</a></small></p>
        </div>
      </div>
      <div class="card">
        <div class="card-block">
          <h4 class="card-title">Job Guide</h4><hr/>
          <p class="card-text" align="justify">Our job guides will keep suggesting various details related to client's job. They might suggest client which extra certifications they should have based on their career or current job. They might also suggest various short term or long term or certification courses based on client's job profile to enhance their productivity.</p>
          <p class="card-text" align="right"><small class="text-muted">Earn $40 for one client.<br/><a href="#" class="btn btn-primary-outline btn-sm">APPLY</a></small></p>
        </div>
      </div>
    </div>
    <div class="card-group">
      <div class="card">
        <div class="card-block">
          <h4 class="card-title">G M A T Guide</h4><hr/>
          <p class="card-text" align="justify">Our course guides will keep suggesting you various details related to your course. They might suggest you which extra knowledge you should have based on your course. They might also suggest various short term courses based on your main course to further enhance your knowledge.</p>
          <p class="card-text" align="right"><small class="text-muted">Pay $40 for your course.<br/><a href="#" class="btn btn-primary-outline btn-sm">APPLY</a></small></p>
        </div>
      </div>
      <div class="card">
        <div class="card-block">
          <h4 class="card-title">G A T E Guide</h4><hr/>
          <p class="card-text" align="justify">Our career guides will keep suggesting you various details related to your career. They might suggest you which extra skills you should have based on your current course or career. They might also suggest various short term or long term courses based on your main career to further enhance your skills.</p>
          <p class="card-text" align="right"><small class="text-muted">Pay $40 for your career.<br/><a href="#" class="btn btn-primary-outline btn-sm">APPLY</a></small></p>
        </div>
      </div>
      <div class="card">
        <div class="card-block">
          <h4 class="card-title">C A T Guide</h4><hr/>
          <p class="card-text" align="justify">Our job guides will keep suggesting you various details related to your job. They might suggest you which extra certifications you should have based on your career or current job. They might also suggest various short term or long term or certification courses based on your job profile to enhance your productivity.</p>
          <p class="card-text" align="right"><small class="text-muted">Pay $40 for your job.<br/><a href="#" class="btn btn-primary-outline btn-sm">APPLY</a></small></p>
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
    $('#help_message_provide_assistance').show( "fast");
    $('#help_message_provide_assistance').removeClass("bq-success");
    $('#help_message_provide_assistance').addClass("bq-danger");
    $('#message_provide_assistance').html("You need to complete your profile details in order to use the Provide Assistance service");
    $('#provide_assistance_card').hide( "fast");
@endif
  var form = document.getElementById('provide_assistance');
  var request = new XMLHttpRequest();
  form.addEventListener('submit', function(e){
      e.preventDefault();
      var formdata = new FormData(form);
      request.open('post', '/provide_assistance');
      request.addEventListener("load", provideTransferComplete);
      request.onprogress = function (e) {
          if (e.lengthComputable) {
              $('#file_upload_percentage').html((e.loaded/e.total)*100 + "%");
          }
      }
      request.onloadstart = function (e) {
          $('#file_upload_status').show("fast");
      }
      request.onloadend = function (e) {
          $('#file_upload_status').hide("fast");
      }
      request.send(formdata);
  });
  function provideTransferComplete(data){
      var response = JSON.parse(data.currentTarget.response);
      if(response.success){
          $('#help_message_provide_assistance').show( "fast");
          $('#help_message_provide_assistance').removeClass("bq-danger");
          $('#help_message_provide_assistance').addClass("bq-success");
          $('#message_provide_assistance_title').html("<i class=\"fa fa-check-circle-o\" aria-hidden=\"true\"></i> SUBMISSION SUCCESFUL");
          $('#message_provide_assistance').html(response.message);
          $('#provide_assistance')[0].reset();
          $('#provide_assistance_card').hide( "fast");
      }
      else {
          $('#help_message_provide_assistance').show( "fast");
          $('#help_message_provide_assistance').removeClass("bq-success");
          $('#help_message_provide_assistance').addClass("bq-danger");
          $('#message_provide_assistance_title').html("<i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> SUBMISSION FAILED");
          var message = JSON.parse(response.message);
          var out="";
          jQuery.each(message, function(i, val) {
            out += val + '<br/>';
          });
          $('#message_provide_assistance').html(out);
      }
  }
  $(function()
  {
    $( "#user_country" ).autocomplete({
      source: "autocomplete/country",
      minLength: 2,
      select: function(event, ui) {
        $('#user_country').val(ui.item.value);
      }
    });
    $( "#user_university" ).autocomplete({
      source: "autocomplete/university",
      minLength: 2,
      select: function(event, ui) {
        $('#user_university').val(ui.item.value);
      }
    });
    $( "#user_course" ).autocomplete({
      source: "autocomplete/course",
      minLength: 2,
      select: function(event, ui) {
        $('#user_course').val(ui.item.value);
      }
    });
    $( "#p_assistance_subject" ).autocomplete({
      source: "autocomplete/subject",
      minLength: 2,
      select: function(event, ui) {
        $('#p_assistance_subject').val(ui.item.value);
      }
    });
    $( "#p_assistance_subject_course" ).autocomplete({
      source: "autocomplete/course",
      minLength: 2,
      select: function(event, ui) {
        $('#p_assistance_subject_course').val(ui.item.value);
      }
    });
    $( "#p_assistance_subject_university" ).autocomplete({
      source: "autocomplete/university",
      minLength: 2,
      select: function(event, ui) {
        $('#p_assistance_subject_university').val(ui.item.value);
      }
    });
  });
</script>
@stop
