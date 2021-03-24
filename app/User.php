<?php

namespace App;

use App\AESCrypt;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\UserTrait;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use HasRoles;
    use UserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile_number','u_status','remember_token'
    ];

    protected $appends = ['allPermissions', 'profilelink', 'avatarlink', 'isme'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*public function getnameAttribute($value)
    {
       return AESCrypt::decryptString($value);
    }
    public function getemailAttribute($value)
    {
       return AESCrypt::decryptString($value);
    }*/
    /*public function getmobileNumberAttribute($value)
    {
       return AESCrypt::decryptString($value);
    }*/
    public function getAllpermissionsAttribute()
    {   $res = [];
        $allPermissions = $this->getAllPermissions();
        foreach($allPermissions as $p)
        {
            $res[] = $p->name;
        }
        return $res;
    }
	public function emailverify($email) {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return false;
        }
        return $user;
    }
    public function validateUser($id) {
        $user = User::where('id', $id)->where('u_status', 1)->first();
        if (!$user) {
            return false;
        }
        return $user;
    }

    public function getUImageAttribute() {
        $link = '';
        if (!empty($this->attributes['u_image'])) {
            $link = str_replace(url('/public/upload/') . "/", "", getPhotoURL(config("constants.UPLOAD_USERS_FOLDER"), $this->attributes['id'], $this->attributes['u_image']));
        }
        return $link;
    }

    public function getAuthPassword() {
        return $this->password;
    }

    public function getEmailForPasswordReset() {
        return $this->email;
    }


}
