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
        'title',
        'action_plan',
        'number_of_hours',
        'budget_requirement',
        'target_period',
        'target_date_from',
        'target_date_to',
        'support_required',
        'status',
        'is_admin_created',
        'file_path',
        'file_name',
        'max_files',
        'lnd_type',
        'lnd_title',
        'lnd_period_date',
        'lnd_hours',
        'lnd_proof_completion',
    ];

    protected $casts = [
        'status' => 'string',
        'is_admin_created' => 'boolean',
    ];

    protected static function booting(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (static::max('id') ?? 0) + 1;
            }
        });
    }

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
            // Graduate Studies
            'Graduate Studies – Master'       => 'Pursue a Master\'s degree program to advance academic credentials and research capabilities.',
            'Graduate Studies – Doctorate'     => 'Pursue a Doctorate degree program for advanced scholarly research and academic expertise.',
            'Graduate Studies – Post-Doctor'   => 'Conduct Post-Doctoral research to further specialise and pioneer contributions in the field.',
            // Professional Development
            'ASEAN Engineer/Architect'        => 'Pursue professional engineering or architectural excellence and credentials through ASEAN standards and recognition programs.',
            'Faculty & Staff Exchange Program' => 'Participate in faculty and staff exchange programs to foster cross-institution collaboration and professional growth.',
            'Industry Immersion Program'      => 'Engage in industry immersion activities to gain applied, real-world experience and strengthen professional practice.',
            'Membership in International Organization & Networks' => 'Participate in international professional organizations and networks to expand collaborations, visibility, and global engagement.',
            'Professorial Chair'              => 'Pursue appointment or recognition as a professorial chair to advance academic leadership and scholarly impact.',
            'Conduct Researches & Extension Activities' => 'Conduct research projects and participate in extension activities to contribute to knowledge advancement and community service.',
            // Training & Seminars
            'Paper Presentation – Local'       => 'Present research papers at local conferences and academic forums to disseminate findings and gain peer feedback.',
            'Paper Presentation – International' => 'Present research papers at international conferences to showcase work on a global stage and enhance academic visibility.',
            'Training/Seminar – Local'        => 'Participate in local training and seminar programs to enhance professional skills and knowledge.',
            'Training/Seminar – International' => 'Attend international training and seminar programs to broaden expertise and global networking opportunities.',
            // Skills & Certification
            'Skills Proficiency Certification – Local'        => 'Obtain local professional certifications and credentials to validate technical competencies and industry standards.',
            'Skills Proficiency Certification – International' => 'Achieve international professional certifications and credentials to demonstrate global competency and recognition.',
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
