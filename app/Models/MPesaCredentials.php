<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MPesaCredentials extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mpesa_credentials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'short_code', 'operating_short_code', 'short_code_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'app_user_name', 'app_user_password',
        'consumer_key', 'consumer_secret', 'pass_key'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['encoded_keys'];

    /**
     * Get the parent project.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Return the Base64 Encoded Consumer Key and Secret.
     *
     * @return string
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    public function getEncodedKeysAttribute(): string
    {
        return base64_encode(
            $this->consumer_key . ':' . $this->consumer_secret
        );
    }
}
