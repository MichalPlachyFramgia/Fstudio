<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message_id', 'success', 'failed','icon', 'title', 'content', 'type', 'url', 'response'
    ];
}
