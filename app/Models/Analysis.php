<?php

namespace App\Models;

use App\Services\ImageStore;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
    use HasUuids;

    protected $table = 'analyses';

    protected $fillable = ['image', 'original_name', 'label', 'confidence', 'probabilities'];

    protected function casts(): array
    {
        return [
            'confidence'    => 'float',
            'probabilities' => 'array',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        return app(ImageStore::class)->url($this->image);
    }

    public function getImagePathAttribute(): string
    {
        return public_path('uploads/analisis/'.$this->image);
    }

    public function getInfoAttribute(): array
    {
        if ($this->label === 'Healthy') {
            return ['name' => 'Padi Sehat', 'latin' => '-', 'tone' => 'green', 'risk' => '-', 'summary' => '', 'condition' => '', 'symptoms' => [], 'actions' => [], 'prevention' => ''];
        }
        return config('padi.diseases.'.$this->label, [
            'name' => $this->label, 'latin' => '-', 'tone' => 'amber', 'risk' => '-',
            'summary' => '', 'condition' => '', 'symptoms' => [], 'actions' => [], 'prevention' => '',
        ]);
    }

    /** @return array{key:string,label:string} */
    public function getLevelAttribute(): array
    {
        if ($this->confidence >= config('padi.high_confidence')) {
            return ['key' => 'high', 'label' => 'Tinggi'];
        }
        if ($this->confidence >= config('padi.low_confidence')) {
            return ['key' => 'mid', 'label' => 'Sedang'];
        }
        return ['key' => 'low', 'label' => 'Rendah'];
    }

    public function getWaktuAttribute(): string
    {
        return $this->created_at->copy()->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i').' WIB';
    }
}
