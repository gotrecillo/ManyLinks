<?php

namespace ManyLinks\Models;

use \Illuminate\Notifications\DatabaseNotificationCollection;
use \Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Laravel\Passport\HasApiTokens;

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
