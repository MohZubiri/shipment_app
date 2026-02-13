<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = [
        'system_name',
        'logo_path',
        'backup_emails',
    ];

    protected $appends = ['logo_url'];

    protected $casts = [
        'backup_emails' => 'array',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }

        return Storage::disk('public')->url($this->logo_path);
    }
}
