<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedLocation extends Model
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasUuids, SoftDeletes;

    protected $guard_name = 'api';

    protected $fillable = [
        'feed_id',
        'location_id',
        'name',
        'stock',
        'unit',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function increaseStock(int $qty)
    {
        $this->increment('stock',$qty);
        $this->save();
        \Log::info('Increased feed stock for feed location ID: ' . $this->id . ' by ' . $qty . ' to ' . $this->stock);
    }

    public function decreaseStock(int $qty)
    {
        $this->decrement('stock',$qty);
        $this->save();
        \Log::info('Decreased feed stock for feed location ID: ' . $this->id . ' by ' . $qty . ' to ' . $this->stock);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->select(['id', 'name']);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->select(['id', 'name']);;
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by')->select(['id', 'name']);
    }

}
