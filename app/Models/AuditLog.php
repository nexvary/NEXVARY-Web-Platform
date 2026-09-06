<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'method',
        'path',
        'ip_hash',
        'user_agent_hash',
        'status_code',
        'created_at',
    ];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }
}
