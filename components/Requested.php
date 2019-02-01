<?php

namespace Cleanse\Twitch\Components;

use Cms\Classes\ComponentBase;

class Requested extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'            => 'Requested Confirmation Component',
            'description'     => 'Acknowledges channel request.'
        ];
    }

    public function onRun()
    {
        $this->addCss('/plugins/cleanse/twitch/assets/css/cleanse-twitch.css');
    }
}
