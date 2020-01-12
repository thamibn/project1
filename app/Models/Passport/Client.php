<?php

namespace App\Models\Passport;

use Illuminate\Support\Str;
use Laravel\Passport\Client as OAuthClient;

/**
 * App\Models\Passport\Client
 *
 * @property string $id
 * @property string|null $user_id
 * @property string $name
 * @property string $secret
 * @property string $redirect
 * @property bool $personal_access_client
 * @property bool $password_client
 * @property bool $revoked
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\AuthCode[] $authCodes
 * @property-read int|null $auth_codes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client wherePasswordClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client wherePersonalAccessClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client whereRedirect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Passport\Client whereUserId($value)
 * @mixin \Eloquent
 */
class Client extends OAuthClient
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid()->toString();
        });
    }
}
