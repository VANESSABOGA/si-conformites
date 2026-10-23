<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// app/Models/User.php
use Spatie\Permission\Traits\HasRoles;


/**
 * Utilisateur — table "users" PARTAGÉE avec le SI-RH.
 *
 * Même classe (App\Models\User) que dans le SI-RH : indispensable pour que
 * les rôles Spatie (model_has_roles.model_type) et les notifications
 * (notifications.notifiable_type) soient communs aux deux applications.
 * Les comptes sont créés et gérés dans le SI-RH.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // 'roles',
    ];

        /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'id', 'uuid');
    }

    /**
     * Adresse utilisée par le canal "mail" des notifications :
     * email du compte, à défaut email professionnel de l'agent.
     */
    public function routeNotificationForMail($notification = null): ?string
    {
        foreach ([$this->email, $this->agent?->contacts?->email_pro, $this->agent?->email] as $email) {
            if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        }

        return null;
    }
}
