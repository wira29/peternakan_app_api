<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;


class MilkingHistory extends Model
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasUuids, SoftDeletes;

    protected $guard_name = 'api';

    protected $fillable = [
        'goat_code',
        'milked_at',
        'qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function goat()
    {
        return $this->belongsTo(Goat::class, 'goat_code');
    }

    public function milkStock()
    {
        $locationId = $this->goat->location_id;
        $milkStock = MilkStock::where('location_id', $locationId)->first();
        return $milkStock;
    }

    public function adjustMilkStock($newQty, $oldQty)
    {
        $qtyDifference = $newQty - $oldQty;
        if ($qtyDifference > 0) {
            $this->milkStock()->increaseStock($qtyDifference);
        } elseif ($qtyDifference < 0) {
            $this->milkStock()->decreaseStock(abs($qtyDifference));
        }
    }

    public function increaseMilkStock()
    {
        $this->milkStock()->increaseStock($this->qty);
    }

    public function decreaseMilkStock()
    {
        $this->milkStock()->decreaseStock($this->qty);
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
