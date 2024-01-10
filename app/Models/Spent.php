<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spent extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->spent_at = $model->spent_at ?: now(); // set to current date if not provided
        });
    }

       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'description',
        'spent_at',
        'value'
    ];

    protected $hidden = [
        'user_id'
    ];

    protected $casts = [
        'spent_at' => 'date:Y-m-d H:i',
        'created_at' => 'date:Y-m-d H:i',
        'updated_at' => 'date:Y-m-d H:i',
    ];

    /**
     * Format the date attribute as 'dd/mm/yyyy'.
     *
     * @return string|null
     */
    public function getFormattedSpentAt()
    {
        $spentAt = $this->attributes['spent_at'];

        if ($spentAt) {
            $carbonDate = $spentAt instanceof Carbon ? $spentAt : Carbon::parse($spentAt);
            return $carbonDate->format('d/m/Y H:i');
        }

        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
