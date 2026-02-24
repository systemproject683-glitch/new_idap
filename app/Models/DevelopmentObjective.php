<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DevelopmentObjective extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'objective',
        'action_plan',
        'budget_requirement',
        'target_period',
        'support_required',
        'status',
        'is_admin_created',
        'file_path',
        'file_name',
        'max_files',
    ];

    protected $casts = [
        'status' => 'string',
        'is_admin_created' => 'boolean',
    ];

    /**
     * Get the user that owns the development objective.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the files for the development objective.
     */
    public function files()
    {
        return $this->hasMany(DevelopmentObjectiveFile::class);
    }

    /**
     * Get predefined objectives with their action plans
     */
    public static function getPredefinedObjectives()
    {
        return [
            // No predefined objectives - only admin-created objectives
        ];
    }

    /**
     * Get admin-created objectives available to all faculty
     */
    public static function getAdminObjectives()
    {
        return self::where('is_admin_created', true)
            ->whereNull('user_id')
            ->get();
    }

    /**
     * Get objectives available for a specific user (admin + personal)
     */
    public static function getAvailableObjectivesForUser($userId)
    {
        $adminObjectives = self::getAdminObjectives();
        $userObjectives = self::where('user_id', $userId)->get();
        
        return [
            'admin' => $adminObjectives,
            'personal' => $userObjectives,
        ];
    }
}
