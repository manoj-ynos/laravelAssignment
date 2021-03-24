<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDeviceToken extends Model {

    protected $fillable = [
        "udt_u_id",
        "udt_token",
        "udt_device"
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = "udt_id";
    protected $table = 'user_device_tokens';

}
