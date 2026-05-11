<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposedIntervention extends Model
{
    use HasFactory;

    protected static function booting(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (static::max('id') ?? 0) + 1;
            }
        });
    }

    protected $table = 'proposed_interventions';

    protected $fillable = [
        'user_id',
        'title',
        'objectives',
        'budget',
        'expected_number_of_participants',
        'dates',
        'person_responsible',
        'target_participants',
    ];

    /**
     * Get the user that owns the proposed intervention.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
