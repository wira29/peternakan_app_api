<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;


class MilkStock extends Model
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasUuids, SoftDeletes;

    protected $guard_name = 'api';


    protected $fillable = [
        'location_id',
        'qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function increaseStock($amount)
    {
        $this->qty += $amount;
        $this->save();
    }

    public function decreaseStock($amount)
    {
        if ($this->qty >= $amount) {
            $this->qty -= $amount;
            $this->save();
        } else {
            throw new \Exception("Stok susu tidak mencukupi. Tersedia: {$this->qty}, Diminta: {$amount}.");
        }
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
