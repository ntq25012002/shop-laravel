<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
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

    public function roles() {
        return $this->belongsToMany(Roles::class,'user_role','user_id','role_id');
    }

    public function loadList($params = [],$userIds = []) {
        $query = $this->where('status',1)->orderBy('id','desc');
        if(!empty($params)) {
            $query = $query->where($params);
        }
        if(!empty($userIds)) {
            $query = $query->whereIn('id',$userIds);
        }
        $list = $query->get();
        return $list;
    }

    public function saveNew($params) {
        $query = $this->create($params);
        return $query;
    }
    
    public function loadOne($id) {
        $query = $this->find($id);
        return $query;
    }

    public function saveUpdate($id, $params) {
        $query = $this->where('id', $id)->update($params);
        return $query;
    }

    public function remove($id, $params) {
        $query = $this->where('id', $id)->update($params);
    }

    public function removeMany($ids,$params) {
        $query = $this->whereIn('id',$ids)->update($params);
    }
}