<?php

namespace App\Models;

use app\Enums\FemaleCondition;
use App\Enums\GoatOriginEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Goat extends Model
{
    use HasApiTokens, HasFactory, HasRoles, HasUuids, Notifiable, SoftDeletes;

    protected $guard_name = 'api';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

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
        'deleted_by',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'origin' => GoatOriginEnum::class,
            'female_condition' => FemaleCondition::class,
        ];
    }

    public function breed(){
        return $this->belongsTo(Breed::class);
    }

    public function cage(){
        return $this->belongsTo(Cage::class);
    }

    public function father(){
        return $this->belongsTo(Goat::class, 'father_id','code');
    }

    public function mother(){
        return $this->belongsTo(Goat::class, 'mother_id','code');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->select(['id', 'name']);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->select(['id', 'name']);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by')->select(['id', 'name']);
    }
}
