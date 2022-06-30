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
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',

        'direccion',
        'docnumber',
        'ivacondition_id',
        'doctype_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ivacondition() 
    {
        return $this->belongsTo(Ivacondition::class);
    }

    public function doctype() 
    {
        return $this->belongsTo(Doctype::class);
    }

    public function modelofact()
    {
        return $this->belongsTo(Modelofact::class);
    }

    public function is_enable_afip()
    {
        if(!$this->ivacondition){ return false; }
        if(!$this->doctype){ return false; }
        if(!$this->docnumber){ return false; }
        if(!$this->direccion){ return false; }

        return true;
    }
}
