<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Trainee;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\MagicLinkMail;
class TraineeController extends Controller {
    public function index(){ return view('welcome'); }
    public function store(Request $r){
        $data = $r->validate(['name'=>'required','email'=>'required|email|unique:trainees,email','github'=>'nullable','motivation'=>'nullable']);
        $data['progress']=0; $data['certificate_code']=null; $data['certificate_issued_at']=null;
        $t = Trainee::create($data);
        // send magic link
        $token = Str::random(40);
        session(['magic_token_'.$t->id => $token]);
        Mail::to($t->email)->send(new MagicLinkMail($t,$token));
        return redirect()->route('home')->with('success','Registro OK. Revisa Mailhog para acceder con el Magic Link.');
    }
}
