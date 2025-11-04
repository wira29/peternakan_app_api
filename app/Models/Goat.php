<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use app\Enums\GoatOrigin;
use app\Enums\FemaleCondition;
use App\Models\User;

class Goat extends Model
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasUuids, SoftDeletes;

    protected $guard_name = 'api';

    protected $fillable = [
        'code',
        'breed_id',
        'cage_id',
        'father_id',
        'mother_id',
        'origin',
        'color',
        'gender',
        'date',
        'price',
        'female_condition',
        'is_breeder',
        'is_qurbani',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'origin' => GoatOrigin::class,
            'female_condition' => FemaleCondition::class,
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
