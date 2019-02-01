<?php

namespace Cleanse\Twitch\Components;

use Exception;
use Flash;
use Redirect;
use Cms\Classes\ComponentBase;
use Cleanse\Twitch\Models\Request as TwitchRequest;
use Cleanse\Twitch\Models\Streamer;

class Request extends ComponentBase
{
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
    }

    public function onRequestTwitch()
    {
        //Bot Detection(?), still work in 2019??!
        $honeypot = post('agree');

        if ($honeypot) {
            return Redirect::to('/streams'); //Return bot back home.
        }

        if (empty(post('name')) || empty(post('message'))) {
            Flash::error('Please fill in the form in its entirety.');

            return Redirect::to('/stream/request')->withInput();
        }

        $request = ['name' => post('name'), 'message' => post('message')];

        try {
            return $this->streamerRequest($request);
        }
        catch (Exception $ex) {
            Flash::error($ex->getMessage());
        }
    }

    private function streamerRequest($streamer)
    {
        $streamerCheck = Streamer::where('display_name', $streamer['name'])
            ->first();

        if (isset($streamerCheck)) {
            $oops = 'plovato@gmail.com';
            Flash::warning('Your channel is already added. If you are not seeing it, please report it to '.$oops);

            return Redirect::to('/stream/request')->withInput();
        }

        return $this->addNewRequest($streamer);
    }

    private function addNewRequest($streamer)
    {
        $request = new TwitchRequest;

        $request->name = $streamer['name'];
        $request->message = $streamer['message'];

        $request->save();

        Flash::success($streamer['name'] . ' requested.');

        return Redirect::to('/stream/requested');
    }
}
