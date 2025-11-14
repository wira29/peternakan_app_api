<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialTransactionDetail extends Model
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasUuids, SoftDeletes;

    protected $guard_name = 'api';

    protected $fillable = [
        'material_transaction_id',
        'material_id',
        'qty',
        'price',
        'total',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];  
    public function materialTransaction()
    {
        return $this->belongsTo(MaterialTransaction::class, 'material_transaction_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function calculateTotal()
    {
        $this->total = $this->price * $this->qty;
        \Log::info('Calculated total for Material Transaction Detail ID ' . $this->id . ': ' . $this->total . ' from qty ' . $this->qty . ' and price per unit ' . $this->price_per_unit);
        $this->save();
    }
    public function increaseMaterialStock()
    {
        $this->material->increaseStock($this->qty);
        \Log::info('Increased material stock for material ID: ' . $this->material_id . ' by ' . $this->qty);
    }
    public function decreaseMaterialStock()
    {
        $this->material->decreaseStock($this->qty);
        \Log::info('Decreased material stock for material ID: ' . $this->material_id . ' by ' . $this->qty);
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
