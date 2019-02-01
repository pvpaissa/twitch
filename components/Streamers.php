<?php

namespace Cleanse\Twitch\Components;

use Cms\Classes\ComponentBase;
use Cleanse\Twitch\Models\Streamer;

class Streamers extends ComponentBase
{
    /**
     * @var Collection A collection of streamers to display
     */
    public $streamers;

    public function componentDetails()
    {
        return [
            'name'            => 'List Streamers',
            'description'     => 'Grabs the streamers in your database.'
        ];
    }

    public function onRun()
    {
        $this->addCss('/plugins/cleanse/twitch/assets/css/cleanse-twitch.css');

        $this->streamers = $this->page['streamers'] = $this->loadStreamers();
    }

    public function loadStreamers()
    {
        return Streamer::where('live', 1)
            ->orderBy('viewers', 'desc')
            ->get();
    }
}
