<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class VaccineHistory extends Model
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasUuids, SoftDeletes;

    protected $guard_name = 'api';

    protected $fillable = [
        'name',
        'vaccine_id',
        'goat_code',
        'date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }

    public function goat()
    {
        return $this->belongsTo(Goat::class, 'goat_code', 'code');
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
