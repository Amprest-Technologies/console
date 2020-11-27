<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SenderID extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sender_ids';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'code'
    ];

    /**
     * Get the parent project.
     *
     * @return BelongsTo
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
