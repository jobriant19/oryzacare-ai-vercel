<?php

namespace App\Console\Commands;

use App\Services\ImageStore;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Throwable;

class MigrasiFotoKeDatabase extends Command
{
    protected $signature = 'padi:migrasi-foto';

    protected $description = 'Salin foto di public/uploads/analisis ke tabel analysis_images';

    public function handle(ImageStore $images): int
    {
        $files = glob(public_path('uploads/analisis/*')) ?: [];
        $gagal = 0;

        $this->withProgressBar($files, function (string $path) use ($images, &$gagal) {
            try {
                $images->put($path, basename($path), File::mimeType($path));
            } catch (Throwable $e) {
                $gagal++;
                $this->newLine();
                $this->warn(basename($path).': '.$e->getMessage());
            }
        });

        $this->newLine(2);
        $this->info(count($files) - $gagal.' foto berhasil disalin, '.$gagal.' gagal.');

        return $gagal ? self::FAILURE : self::SUCCESS;
    }
}
