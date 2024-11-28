<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    /**
     * Gerar um certificado detalhado.
     */
    public function generate(Request $request)
    {
        // Log de início da geração do certificado
        Log::info('Início da geração de certificado', [
            'user_name' => $request->input('user_name'),
            'event_name' => $request->input('event_name')
        ]);

        // Validação dos dados enviados
        $validated = $request->validate([
            'user_name' => 'required|string',
            'event_name' => 'required|string',
        ]);

        try {
            // Gerar um ID único para o certificado
            $certificate_id = Str::uuid()->toString();

            // Criar o certificado e salvar no banco de dados
            $certificate = new Certificate();
            $certificate->certificate_id = $certificate_id;
            $certificate->user_name = $validated['user_name'];
            $certificate->event_name = $validated['event_name'];
            $certificate->is_valid = true;  // Inicialmente válido
            $certificate->save();

            // Log de sucesso na criação do certificado
            Log::info('Certificado criado com sucesso', [
                'certificate_id' => $certificate_id,
                'user_name' => $validated['user_name'],
                'event_name' => $validated['event_name']
            ]);

            // Gerar o PDF com as informações do certificado
            $pdf = Pdf::loadView('certificates.template', [
                'user_name' => $validated['user_name'],
                'event_name' => $validated['event_name'],
                'certificate_id' => $certificate_id,
            ]);

            // Retornar o PDF como download
            return response()->streamDownload(
                fn () => print($pdf->output()),
                "certificado_{$validated['event_name']}.pdf"
            );

        } catch (\Exception $e) {
            // Log de erro
            Log::error('Erro ao gerar certificado', [
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erro ao gerar o certificado: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Validar certificado.
     */
    public function validateCertificate($certificate_id)
    {
        Log::info('Início da validação de certificado', [
            'certificate_id' => $certificate_id
        ]);

        // Verificar no banco se o certificado existe
        $certificate = Certificate::where('certificate_id', $certificate_id)->first();

        if (!$certificate) {
            Log::info('Certificado não encontrado', [
                'certificate_id' => $certificate_id
            ]);
            return response()->json([
                'message' => 'Certificado não emitido pelo nosso sistema.',
            ], 404);
        }

        if (!$certificate->is_valid) {
            Log::info('Certificado encontrado, mas está revogado', [
                'certificate_id' => $certificate_id
            ]);
            return response()->json([
                'message' => 'Certificado revogado.',
            ], 400);
        }

        Log::info('Certificado validado com sucesso', [
            'certificate_id' => $certificate_id,
            'event_name' => $certificate->event_name,
            'user_name' => $certificate->user_name
        ]);

        return response()->json([
            'message' => 'Certificado válido.',
            'data' => [
                'event_name' => $certificate->event_name,
                'event_date' => $certificate->event_date,
                'user_name' => $certificate->user_name,
                'certificate_id' => $certificate->certificate_id,
            ],
        ]);
    }

    /**
     * Revogar certificado.
     */
    public function revokeCertificate($certificate_id)
    {
        $certificate = Certificate::where('certificate_id', $certificate_id)->first();

        if (!$certificate) {
            Log::info('Certificado não encontrado para revogação', [
                'certificate_id' => $certificate_id
            ]);
            return response()->json(['message' => 'Certificado não encontrado.'], 404);
        }

        $certificate->is_valid = false;
        $certificate->save();

        // Log da revogação do certificado
        Log::info('Certificado revogado com sucesso', [
            'certificate_id' => $certificate_id
        ]);

        return response()->json(['message' => 'Certificado revogado com sucesso.']);
    }
}
