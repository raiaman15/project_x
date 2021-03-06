<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Storage;
use Validator;
use App\User;
use App\Token;
use App\Seek_assistance;
use App\Provide_assistance;
use App\Subject;
use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function display_study()
    {
        if (Auth::check()) {
            $seek_assistances = Seek_assistance::where('email', Auth::user()->email)->orderBy('created_at', 'desc')->paginate(2);
            return view('pages.study')->with('seeked_assistances',$seek_assistances);
        }
        else
            return view('pages.welcome');
    }

    public function display_tutor()
    {
        if (Auth::check()) {
            $provide_assistances = Provide_assistance::where('email', Auth::user()->email)->orderBy('created_at', 'desc')->paginate(2);
            return view('pages.tutor')->with('provided_assistances',$provide_assistances);
        }
        else
            return view('pages.welcome');
    }

    public function display_why_us()
    {
        if (Auth::check()) {
            return view('pages.why_us');
        }
        else
            return view('pages.welcome');
    }

    public function display_contact_us()
    {
        if (Auth::check()) {
            return view('pages.contact_us');
        }
        else
            return view('pages.welcome');
    }

    public function add_more_info(Request $request)
    {
        if (Auth::check()) {
            $user = User::where('email', Auth::user()->email)->first();
            if(!empty($request->DOB)){$user->DOB = $request->DOB;}
            if(!empty($request->country)){$user->country = strtoupper($request->country);}
            if(!empty($request->contact)){$user->contact = $request->contact;}
            if(!empty($request->university)){$user->university = strtoupper($request->university);}
            if(!empty($request->course)){$user->course = strtoupper($request->course);}
            if(!empty($request->referred_by)){$user->referred_by = $request->referred_by;}
            $user->save();
            return redirect('/study');
        }
        else
            return view('pages.welcome');
    }

    /**
     * Get a validator for an incoming contact send mail request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_send_mail(array $data)
    {
        return Validator::make($data, [
            'new_token_description' => 'required|min:50|max:1000',
            'g-recaptcha-response' => 'required|captcha',
        ]);
    }

    public function contact_send_mail(Request $request)
    {
        if (Auth::check()) {
            $validator = $this->validator_send_mail($request->all());
            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                );
            }
            //saving the message in DB
            $token = new Token;
            $token->name=Auth::user()->name;
            $token->email=Auth::user()->email;
            $token->description=$request->input('new_token_description');
            $token->save();
            
            return redirect('/contact_us');
        }
        else
            return view('pages.welcome');
    }

    protected function validator_seek_assistance(array $data)
    {
        return Validator::make($data, [
            'assistance_subject' => 'required',
            'assistance_description' => 'required|min:10|max:1000',
            'assistance_document.*' => 'mimes:pdf,docx,doc,jpeg,png|max:5000',
        ],
        [
            'assistance_subject.required' => 'Please fill SUBJECT NAME.',
            'assistance_description.required' => 'Please fill How may we assist you?',
            'assistance_description.min' => 'Kindly elaborate How may we assist you? Minimum 10 charecters required.',
            'assistance_description.max' => 'Kindly shorten How may we assist you? Maximum 1000 charecters allowed.',
            'assistance_document.*.mimes' => 'File format not supported, use only pdf/doc/docx/jpg/png files',
            'assistance_document.*.max' => 'Each file must be less than 5 MB.',
        ]);
    }

    public function seek_assistance(Request $request)
    {   
        $documents = $request->file('assistance_document');
        //return \Response::json(array('success' => false , 'message' => "{\"assistance_document\":[\" ".count($documents)." \"]}"));
        if(count($documents)>5) //MAX 5 file upload
            {return \Response::json(array('success' => false , 'message' => "{\"assistance_document\":[\"Too many files. Maximum 5 files allowed.\"]}"));}
        $validator = $this->validator_seek_assistance($request->all());
        if ($validator->fails()) {
            return \Response::json(array('success' => false , 'message' => $validator->messages()->toJson()));
        }
        $sub = Provide_assistance::where('university', Auth::User()->university)
                    ->where('course', Auth::User()->course)
                    ->where('subject', $request->input('assistance_subject'))
                    ->where('admin_approved', true)
                    ->first();
        if(!$sub)
        {
            return \Response::json(array('success' => false , 'message' => "{\"assistance_subject\":[\"We currently do not have a tutor for the subject you specified. If you know a tutor of this subject, kindly <a href=".url('/contact_us').">contact us.</a>\"]}"));
        }
        $seek = new Seek_assistance;
        $seek->name=Auth::user()->name;
        $seek->email=Auth::user()->email;
        $seek->subject=strtoupper($request->input('assistance_subject'));
        $seek->description=$request->input('assistance_description');
        $seek->country=Auth::user()->country;
        $seek->university=Auth::user()->university;
        $seek->course=Auth::user()->course;
        $seek->save();
        $seek1 = Seek_assistance::where('email', Auth::user()->email)->get()->last();
        $count=0;
        $files="";
        if((!empty($documents))&&((count($documents))>0)):
            foreach($documents as $document):
                if((!empty($document))&&(!is_null($document)))
                {
                    $count++;
                    $filename='s_'.($seek1->id).$count.'.'.$document->guessClientExtension();
                    $icon="";
                    if(($document->guessClientExtension()=='jpeg')||($document->guessClientExtension()=='png'))
                        {$icon="fa-file-image-o";}
                    elseif(($document->guessClientExtension()=='doc')||($document->guessClientExtension()=='docx'))
                        {$icon="fa-file-word-o";}
                    elseif(($document->guessClientExtension()=='pdf'))
                        {$icon="fa-file-pdf-o";}
                    else
                        {$icon="fa-file-o";}
                    $files = $files.'|'.$filename.':'.$icon;
                    Storage::delete($filename);
                    Storage::put($filename, file_get_contents($document));
                }   
            endforeach;
        endif;
        $seek1->files=$files;
        $seek1->save();
        
        return \Response::json(array('success' => true , 'message' => "Request is successfully submitted. Pay the service fee to start recieving assistance."));
    }

    public function seek_assistance_detail()
    {
        if (Auth::check()) {
            $seek_assistances = Seek_assistance::where('email', Auth::user()->email)->paginate(3);
            return view('pages.study')->with('seeked_assistances',$seek_assistances);
        }
        else
            return view('pages.welcome');
    }

    protected function validator_provide_assistance(array $data)
    {
        return Validator::make($data, [
            'p_assistance_subject' => 'required',
            'p_assistance_subject_course' => 'required',
            'p_assistance_subject_university' => 'required',
            'p_assistance_description' => 'required|min:10|max:1000',
            'p_assistance_document.*' => 'required|mimes:pdf,docx,doc,jpeg,png|max:5000',
        ],
        [
            'p_assistance_subject.required' => 'Please fill SUBJECT NAME.',
            'p_assistance_subject_course.required' => 'Please fill SUBJECT COURSE NAME.',
            'p_assistance_subject_university.required' => 'Please fill COURSE UNIVERSITY NAME.',
            'p_assistance_description.required' => 'Please fill What is your qualification?',
            'p_assistance_description.min' => 'Kindly elaborate What is your qualification? Minimum 10 charecters required.',
            'p_assistance_description.max' => 'Kindly shorten What is your qualification? Maximum 1000 charecters allowed.',
            'p_assistance_document.*.required' => 'Resume and Highest degree cetificate is mandatory. Please upload them.',
            'p_assistance_document.*.mimes' => 'File format not supported, use only pdf/doc/docx/jpg/png files.',
            'p_assistance_document.*.max' => 'File size must be less than 5 MB.',
        ]);
    }

    public function provide_assistance(Request $request)
    {
        $documents = $request->file('p_assistance_document');
        if(count($documents)>5) //MAX 5 file upload
            {return \Response::json(array('success' => false , 'message' => "{\"p_assistance_document\":[\"Too many files. Maximum 5 files allowed.\"]}"));}
        $validator = $this->validator_provide_assistance($request->all());
        if ($validator->fails()) {
            return \Response::json(array('success' => false , 'message' => $validator->messages()->toJson()));
        }
        $provide = new Provide_assistance;
        $provide->name=Auth::user()->name;
        $provide->email=Auth::user()->email;
        $provide->subject=strtoupper($request->input('p_assistance_subject'));
        $provide->description=$request->input('p_assistance_description');
        $provide->country=Auth::user()->country;
        $provide->university=strtoupper($request->input('p_assistance_subject_university'));
        $provide->course=strtoupper($request->input('p_assistance_subject_course'));
        $provide->save();
        $provide1 = Provide_assistance::where('email', Auth::user()->email)->get()->last();
        $count=0;
        $files="";
        if((!empty($documents))&&((count($documents))>0)):
            foreach($documents as $document):
                if((!empty($document))&&(!is_null($document)))
                {
                    $count++;
                    $filename='p_'.($provide1->id).$count.'.'.$document->guessClientExtension();
                    $icon="";
                    if(($document->guessClientExtension()=='jpeg')||($document->guessClientExtension()=='png'))
                        {$icon="fa-file-image-o";}
                    elseif(($document->guessClientExtension()=='doc')||($document->guessClientExtension()=='docx'))
                        {$icon="fa-file-word-o";}
                    elseif(($document->guessClientExtension()=='pdf'))
                        {$icon="fa-file-pdf-o";}
                    else
                        {$icon="fa-file-o";}
                    $files = $files.'|'.$filename.':'.$icon;
                    Storage::delete($filename);
                    Storage::put($filename, file_get_contents($document));
                }   
            endforeach;
        endif;
        $provide1->files=$files;
        $provide1->save();
        
        return \Response::json(array('success' => true , 'message' => "Request is successfully submitted. Please wait until we verify and approve your request."));
    }

    public function provide_assistance_detail()
    {
        if (Auth::check()) {
            $provide_assistances = Provide_assistance::where('email', Auth::user()->email)->paginate(3);
            return view('pages.tutor')->with('provided_assistances',$provide_assistances);
        }
        else
            return view('pages.welcome');
    }

    public function download($filename){
        $mime = Storage::mimeType($filename);
        $file = Storage::get($filename);
        return (\Response($file, 200))->header('Content-Type', $mime);
    }

    public function save_tutor_feedback($id, $tutor_rating){
        $seek_assistance = Seek_assistance::where('id', $id)->first();
        if($tutor_rating==0)
        {
            $seek_assistance->feedback_provided=false;
            $seek_assistance->tutor_feedback=$tutor_rating; //an integer
            $seek_assistance->status="TUTOR ASSIGNED : ".$seek_assistance->tutor_email;
            $seek_assistance->save();
        }
        else
        {
            $seek_assistance->feedback_provided=true;
            $seek_assistance->tutor_feedback=$tutor_rating; //an integer
            $seek_assistance->status="GENERATING TUTOR PAYMENT";
            $seek_assistance->save();
        }
        return redirect('study');
    }

    public function autocomplete_country(Request $request){
        $term = $request->input('term');
        $data=DB::table("countries")->distinct()->select("name")->where("name", "LIKE", $term."%")->groupBy("name")->take(5)->get();
        if($data)
        {
            foreach ($data as $value) {
            $return_array[] = array("value" => $value->name);
            }
        }
        return \Response::json($return_array);
    }

    public function autocomplete_university(Request $request){
        $term = $request->input('term');
        $data=DB::table("universities")->distinct()->select("name")->where("name", "LIKE", $term."%")->groupBy("name")->take(5)->get();
        if($data)
        {
            foreach ($data as $value) {
            $return_array[] = array("value" => $value->name);
            }
        }
        return \Response::json($return_array);
    }

    public function autocomplete_course(Request $request){
        $term = $request->input('term');
        $data=DB::table("courses")->distinct()->select("name")->where("name", "LIKE", $term."%")->groupBy("name")->take(5)->get();
        if($data)
        {
            foreach ($data as $value) {
            $return_array[] = array("value" => $value->name);
            }
        }
        return \Response::json($return_array);
    }

    public function autocomplete_subject(Request $request){
        $term = $request->input('term');
        $data=DB::table("subjects")->distinct()->select("name")->where("name", "LIKE", $term."%")->groupBy("name")->take(5)->get();
        if($data)
        {
            foreach ($data as $value) {
            $return_array[] = array("value" => $value->name);
            }
        }
        return \Response::json($return_array);
    }

    public function download_syllabus($university, $course, $subject){
        $subject = Subject::where('university', $university)->where('course', $course)->where('name', $subject)->first();
        if(!$subject->syllabus)
            {dd("Syllabus is currently not available for this subject. Kindly provide your syllabus to your tutor so that we can add it here.");}
        $mime = Storage::mimeType($subject->syllabus);
        $file = Storage::get($subject->syllabus);
        return (\Response($file, 200))->header('Content-Type', $mime);
    }
}
