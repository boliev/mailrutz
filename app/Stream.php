<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    const SERVICE_NAME_TWITCH = 'twitch';
    const SERVICE_NAME_YOUTUBE = 'youtube';

    public $timestamps = false;
}
