<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'points';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'register',
        'latitude',
        'longitude',
        'photo',
        'user_id'
    ];
}
