<?php

namespace ApiMultipurpose\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'type',
        'created_by',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_user');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
