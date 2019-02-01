<?php

namespace Cleanse\Twitch\Models;

use Event;
use Model;

/**
 *  Class Request
 * This is the model class for table "cleanse_twitch_requests"
 *
 * @property integer $id
 * @property string $name
 * @property string $message
 */
class Request extends Model
{
    public $table = 'cleanse_twitch_requests';

    public function beforeCreate()
    {
        $this->name = strtolower($this->name);
    }
}
