<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public const AGE_HIDDEN = 'hidden';

    public const AGE_ONLY_MONTH_DAY = 'month_day';

    public const AGE_FULL = 'full';

    public const AVATAR_TYPE_SVG = 'svg';

    public const AVATAR_TYPE_URL = 'url';

    protected $fillable = [
        'first_name',
        'last_name',
        'organization_id',
        'name_for_avatar',
        'email',
        'email_verified_at',
        'locale',
        'timezone',
        'born_at',
        'age_preferences',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'born_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return Attribute<string,never>
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (is_null($attributes['first_name'])) {
                    return $attributes['email'];
                }

                $completeName = $attributes['first_name'];

                if (! is_null($attributes['last_name'])) {
                    $completeName = $completeName . ' ' . $attributes['last_name'];
                }

                return $completeName;
            }
        );
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (is_null($attributes['born_at'])) {
                    return null;
                }

                $date = Carbon::parse($attributes['born_at']);

                if ($attributes['age_preferences'] === self::AGE_HIDDEN) {
                    return null;
                }

                if ($attributes['age_preferences'] === self::AGE_ONLY_MONTH_DAY) {
                    return $date->isoFormat(trans('format.month_day'));
                }

                return $date->isoFormat(trans('format.year_month_day')) . ' (' . $date->age . ')';
            }
        );
    }

    /**
     * @return Attribute<string,never>
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $type = self::AVATAR_TYPE_SVG;
                $multiavatar = new MultiAvatar;
                $avatar = $multiavatar($this->name_for_avatar, null, null);

                // if ($this->file) {
                //     $type = self::AVATAR_TYPE_URL;
                //     $content = 'https://ucarecdn.com/' . $this->file->uuid . '/-/scale_crop/300x300/smart/-/format/auto/-/quality/smart_retina/';
                // }

                return [
                    'type' => $type,
                    'content' => $avatar,
                ];
            }
        );
    }
}
