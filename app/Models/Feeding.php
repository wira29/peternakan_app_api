<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feeding extends Model
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasUuids, SoftDeletes;

    protected $guard_name = 'api';

    protected $table = 'feeding';

    protected $fillable = [
        'cage_id',
        'feed_location_id',
        'qty',
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

    public function feedLocation()
    {
        return $this->belongsTo(FeedLocation::class);
    }

    public function cage()
    {
        return $this->belongsTo(Cage::class);
    }

    public function increaseFeedStock()
    {
        $this->feedLocation->increaseStock($this->qty);
    }
    public function decreaseFeedStock()
    {
        $this->feedLocation->decreaseStock($this->qty);
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
