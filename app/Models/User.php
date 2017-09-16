<?php

namespace ManyLinks\Models;

use \Illuminate\Notifications\DatabaseNotificationCollection;
use \Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Laravel\Passport\HasApiTokens;

/**
 * ManyLinks\Models\User
 *
 * @mixin \Eloquent
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property \Carbon\Carbon|null $created_at
 * @property string $email
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\ManyLinks\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ManyLinks\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ManyLinks\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ManyLinks\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ManyLinks\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ManyLinks\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ManyLinks\Models\User whereUpdatedAt($value)
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'confirmed',
        'confirmation_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'confirmed' => 'boolean',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public static function tokenByEmail($email)
    {
        return static::whereEmail($email)
            ->first()
            ->createToken('password-granted')
            ->accessToken;
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }
}
