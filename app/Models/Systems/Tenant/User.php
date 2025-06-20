<?php

namespace App\Models\Systems\Tenant;

use Illuminate\Support\Carbon;

use Illuminate\Notifications\Notifiable;
use App\Jobs\Email\Auth\SendVerifyEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Email\Auth\SendResetPasswordJob;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, ShouldQueue
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ["name", "email", "roles_id", "password", "active"];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ["password", "remember_token"];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "email_verified_at" => "datetime",
    ];

    // /**
    //  * Send the password reset notification.
    //  *
    //  * @param  string  $token
    //  * @return void
    //  */
    // public function sendPasswordResetNotification($token)
    // {
    //     SendResetPasswordJob::dispatch(
    //         [$this->email],
    //         [
    //             "name" => $this->name,
    //             "email" => $this->email,
    //             "users_id" => $this->id,
    //             "token" => $token,
    //         ]
    //     );
    // }

    public function sendEmailVerificationNotification()
    {
        SendVerifyEmailJob::dispatch(
            [$this->email],
            [
                "user_id" => $this->id,
                "name" => $this->name,
                "users_id" => $this->id,
                "email" => $this->email,
            ]
        );
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes["password"] = bcrypt($value);
    }

    public function setImportedAtAttribute($value)
    {
        if ($value == "") {
            $this->attributes["imported_at"] = null;
        }
    }

    public function setDateBirthAttribute($value)
    {
        $this->attributes["date_birth"] = Carbon::createFromFormat("d/m/Y", $value)->format("Y-m-d");
    }

    public function getAccessEndAttribute()
    {
        $accessEndAt = $this->subscription("default")->ends_at;

        return Carbon::make($accessEndAt)->format("d/m/Y à\s H:i:s");
    }

    public function cities()
    {
        return $this->hasOne("App\Models\Citie", "id", "cities_id");
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, "roles_id");
    }

    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class, "roles_x_users", "users_id", "roles_id");
    }

    public function hasPermission($permission)
    {
        return $this->roles()
            ->whereHas("permissions", function ($query) use ($permission) {
                $query->where("name", $permission);
            })
            ->exists();
    }
}
