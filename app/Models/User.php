<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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

     protected $appends = [
        'profile_img'
     ];
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'profile_image',
        'description',
    ];
    public function getProfileImgAttribute()
    {
        return !empty($this->profile_image)?asset('uploads/'.$this->profile_image):asset('uploads/dummyimage.png');
    }
    /**
     * Get the user that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class,'role','id');
    }
}
