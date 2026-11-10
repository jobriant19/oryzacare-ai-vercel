<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use RuntimeException;
use Throwable;

class PadiClassifier
{
    /**
     * Menjalankan python/predict.py dan mengembalikan array:
     * ['label' => string, 'confidence' => float, 'probabilities' => array]
     */
    public function predict(string $imagePath): array
    {
        $data = config('padi.classifier_url')
            ? $this->viaApi($imagePath)
            : $this->viaPython($imagePath);

        if (isset($data['error'])) {
            throw new RuntimeException($data['error']);
        }

        if (! isset($data['label'], $data['confidence'], $data['probabilities'])) {
            throw new RuntimeException('Format hasil prediksi tidak dikenali.');
        }

        return $data;
    }

    /** Lokal (Laragon): jalankan python/predict.py lewat proses. */
    private function viaPython(string $imagePath): array
    {
        try {
            $result = Process::timeout((int) config('padi.timeout'))
                ->env($this->environment())
                ->run([
                    config('padi.python'),
                    config('padi.script'),
                    $imagePath,
                    config('padi.model'),
                ]);
        } catch (Throwable $e) {
            throw new RuntimeException('Python tidak dapat dijalankan. Periksa PADI_PYTHON di file .env. ('.$e->getMessage().')');
        }

        $lines = array_values(array_filter(array_map('trim', explode("\n", $result->output()))));
        $json  = $lines ? end($lines) : '';
        $data  = json_decode($json, true);

        if (! is_array($data)) {
            $detail = trim($result->errorOutput()) ?: 'Tidak ada keluaran dari python.';
            throw new RuntimeException('Proses prediksi gagal: '.mb_substr($detail, 0, 300));
        }

        return $data;
    }

    /** Production (Vercel): kirim byte gambar ke fungsi Python (proyek Vercel terpisah). */
    private function viaApi(string $imagePath): array
    {
        try {
            $response = Http::timeout((int) config('padi.timeout'))
                ->withHeaders(['X-Api-Token' => (string) config('padi.classifier_token')])
                ->withBody(file_get_contents($imagePath), 'application/octet-stream')
                ->post(config('padi.classifier_url'));
        } catch (Throwable $e) {
            throw new RuntimeException('Layanan prediksi tidak dapat dihubungi. ('.$e->getMessage().')');
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new RuntimeException('Proses prediksi gagal: respons tidak valid (HTTP '.$response->status().').');
        }

        return $data;
    }

    /** Lingkungan minimal agar Python berjalan normal di Windows (artisan serve membuang SystemRoot). */
    private function environment(): array
    {
        $root = getenv('SystemRoot') ?: ($_SERVER['SystemRoot'] ?? 'C:\\Windows');
        $temp = getenv('TEMP') ?: sys_get_temp_dir();

        return [
            'SystemRoot'       => $root,
            'windir'           => getenv('windir') ?: $root,
            'SystemDrive'      => getenv('SystemDrive') ?: substr($root, 0, 2),
            'TEMP'             => $temp,
            'TMP'              => getenv('TMP') ?: $temp,
            'PATH'             => getenv('PATH') ?: $root.'\\System32;'.$root,
            'PYTHONIOENCODING' => 'utf-8',
            'PYTHONWARNINGS'   => 'ignore',
        ];
    }
}
