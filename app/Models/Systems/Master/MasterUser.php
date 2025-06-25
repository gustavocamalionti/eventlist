<?php

namespace App\Models\Systems\Master;

use Illuminate\Support\Carbon;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Jobs\Systems\Master\Modules\Auth\Email\MasterJobSendVerifyEmail;
use App\Jobs\Systems\Master\Modules\Auth\Email\MasterJobSendResetPassword;

class MasterUser extends Authenticatable implements MustVerifyEmail, ShouldQueue
{
    use HasFactory, Notifiable;
    protected $table = "users";
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
        MasterJobSendVerifyEmail::dispatch(
            [$this->email],
            [
                "user_id" => $this->id,
                "name" => $this->name,
                "users_id" => $this->id,
                "email" => $this->email,
            ]
        );
    }

    public function sendPasswordResetNotification($token)
    {
        // Aqui você pode disparar um job ou usar sua própria lógica
        MasterJobSendResetPassword::dispatch(
            [$this->email],
            [
                "token" => $token,
                "user_id" => $this->id,
                "name" => $this->name,
                "email" => $this->email,
            ]
        );
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

    public function hasPermission($permission)
    {
        return $this->roles()
            ->whereHas("permissions", function ($query) use ($permission) {
                $query->where("name", $permission);
            })
            ->exists();
    }
}
