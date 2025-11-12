<?php
namespace App\Http\Controllers;
use App\Models\Certificate;
use App\Models\Trainee;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
class CertificateController extends Controller
{
    public function generate($traineeId)
    {
        $trainee = Trainee::findOrFail($traineeId);
        if ($trainee->progress < 100) abort(403,'Progreso insuficiente');
        // create certificate record
        $certificate = Certificate::firstOrCreate(['trainee_id' => $trainee->id]);
        // ensure trainee has certificate_code and date
        $trainee->certificate_code = $certificate->code;
        $trainee->certificate_issued_at = $certificate->issued_at;
        $trainee->save();
        $verifyUrl = url('/verify/' . $certificate->code);
        $qrImage = base64_encode(QrCode::format('png')->size(150)->generate($verifyUrl));
        $pdf = Pdf::loadView('certificate.pdf', [
            'trainee' => $trainee,
            'certificate' => $certificate,
            'qrImage' => $qrImage,
            'verifyUrl' => $verifyUrl,
        ]);
        return $pdf->download('certificate_'.$trainee->id.'.pdf');
    }

    public function verify($code)
    {
        $certificate = Certificate::where('code', $code)->first();
        if (!$certificate) abort(404,'Certificado no encontrado');
        return view('certificate.verify', ['certificate'=>$certificate, 'trainee'=>$certificate->trainee]);
    }
}
