<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id', 'uuid', 'api_key',
        'name', 'description', 'status'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['available_services'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'available_services' => 'array',
    ];

    /**
     * Model boot method.
     *
     * Generate the project's uuid.
     * @throws Exception
     */
    public static function boot()
    {
        // Call parent boot method.
        parent::boot();

        // When the model is being created.
        self::creating(function ($model) {
            $model->uuid = mt_rand(10000000, 99999999);
            $model->api_key = bin2hex(random_bytes(16));
        });
    }

    /**
     * Get the team owning the project.
     *
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the M-Pesa Credentials associated with this account.
     *
     * @return HasOne
     */
    public function mpesaCredentials(): HasOne
    {
        return $this->hasOne(MPesaCredentials::class);
    }

    /**
     * Get the project's subscriptions.
     *
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)
            ->orderBy('expires_at', 'desc');
    }

    /**
     * Return the services available(subscribed) for this project.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    public function getAvailableServicesAttribute()
    {
        return $this->subscriptions
            ->map(fn ($subscription) => $subscription->tier->service)
            ->unique('name');
    }

    /**
     * Scope a query to only include active subscriptions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithActiveSubscriptions($query)
    {
        return $query->with([
            'subscriptions' => fn ($query) => $query->active()
        ]);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
