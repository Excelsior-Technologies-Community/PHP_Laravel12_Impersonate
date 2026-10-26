<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImpersonationLog extends Model
{
    protected $fillable = [
        'admin_id',
        'user_id',
        'ticket_id',
        'reason',
        'access_mode',
        'duration_minutes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isReadOnly(): bool
    {
        return $this->access_mode === 'read_only';
    }

    public function isFullAccess(): bool
    {
        return $this->access_mode === 'full_access';
    }

    public function getDurationAttribute()
    {
        if (!$this->created_at || !$this->updated_at) {
            return 'N/A';
        }

        $seconds = $this->created_at->diffInSeconds($this->updated_at);

        if ($seconds < 60) {
            return $seconds . ' seconds';
        }

        $minutes = intdiv($seconds, 60);
        $remainingSeconds = $seconds % 60;

        if ($minutes < 60) {
            return $minutes . ' min ' . $remainingSeconds . ' sec';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return $hours . ' hr ' . $remainingMinutes . ' min';
    }
}
