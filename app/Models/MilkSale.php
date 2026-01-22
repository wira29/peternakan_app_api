<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;


class MilkSale extends Model
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasUuids, SoftDeletes;

    protected $guard_name = 'api';

    protected $fillable = [
        'location_id',
        'sale_date',
        'qty',
        'price_per_liter',
        'total',
        'remark',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($milkSale) {
            $milkSale->sumTotal();
        });
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function sumTotal()
    {
        $this->total = $this->qty * $this->price_per_liter;
    }

    public function milkStock()
    {
        return $this->belongsTo(MilkStock::class);
    }

    public function reduceMilkStock(string $locationId)
    {
        $milkStock = MilkStock::where('location_id', $locationId)->first();
        if ($milkStock) {
            $milkStock->decreaseStock($this->qty);
        } else {
            throw new \Exception('Milk stock record not found.');
        }
    }

    public function restoreMilkStock()
    {
        $milkStock = MilkStock::where('location_id', $this->location_id)->first();
        if ($milkStock) {
            $milkStock->increaseStock($this->qty);
        } else {
            throw new \Exception('Milk stock record not found.');
        }
    }

    public function adjustMilkStock($oldQty, $newQty)
    {
        $milkStock = MilkStock::first();
        if ($milkStock) {
            if ($newQty > $oldQty) {
                $difference = $newQty - $oldQty;
                $milkStock->decreaseStock($difference);
            } elseif ($newQty < $oldQty) {
                $difference = $oldQty - $newQty;
                $milkStock->increaseStock($difference);
            }
        } else {
            throw new \Exception('Milk stock record not found.');
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
