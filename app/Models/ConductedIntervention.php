<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConductedIntervention extends Model
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

    protected $table = 'conducted_interventions';

    protected $fillable = [
        'user_id',
        'type_of_lnd',
        'title',
        'date_conducted',
        'duration',
        'leaving_service_provided',
        'target_number_of_participants',
        'actual_number_of_participants',
        'completion_rate',
        'proof_of_documentation',
    ];

    /**
     * Get the user that owns the conducted intervention.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
