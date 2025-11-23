<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlendTransaction extends Model
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasUuids, SoftDeletes;

    protected $guard_name = 'api';
    protected $fillable = [
        'feed_id',
        'qty',
        'date',
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

    public function materials()
    {
        return $this->hasMany(BlendTransactionDetail::class, 'blend_transaction_id');
    }
    public function feed()
    {
        return $this->belongsTo(Feed::class, 'feed_id');
    }

    public function increaseFeedStock(){
        $this->feed->increaseStock($this->qty);
        \Log::info('Increase feed stock for feed ID: ' . $this->feed_id . ' by ' . $this->qty);
        
    }

    public function rollbackFeedStock()
    {
        $this->feed->decreaseStock($this->qty);
        \Log::info('Rollback feed stock for feed ID: ' . $this->feed_id . ' by ' . $this->qty);
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
