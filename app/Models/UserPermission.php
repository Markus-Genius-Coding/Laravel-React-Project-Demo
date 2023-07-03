<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string user_id
 * @property string permission_owner
 * @property int role_id
 * @property string created_at
 * @property string updated_at
 */
class UserPermission extends Model
{
    use HasFactory;

}
