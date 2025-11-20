<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FeedLocation;
use App\Models\Feed;

class FeedPurchaseDetail extends Model
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasUuids, SoftDeletes;

    protected $table = 'feed_purchase_details';
    protected $guard_name = 'api';

    protected $fillable = [
        'feed_purchase_id',
        'feed_location_id',
        'qty',
        'price_per_unit',
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

    public function feed()
    {
        return $this->belongsTo(Feed::class, 'feed_id');
    }
    public function feedLocation()
    {
        return $this->belongsTo(FeedLocation::class, 'feed_location_id');
    }

    public function decreaseFeedStock(){
        $this->feedLocation->decreaseStock($this->qty);
    }

    public function increaseFeedStock(){
        $this->feedLocation->increaseStock($this->qty);
    }

    public function calculateTotal(){
        $this->total = $this->price_per_unit * $this->qty;
        \Log::info('Calculated total for Feed Sale Detail ID ' . $this->id . ': ' . $this->total . ' from qty ' . $this->qty . ' and price per unit ' . $this->price_per_unit);
        $this->save();
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
