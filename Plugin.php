<?php

namespace Cleanse\Twitch;

use Backend;
use Controller;
use Event;
use Queue;
use System\Classes\PluginBase;
use Cleanse\Twitch\Models\Streamer;
use Cleanse\Twitch\Classes\UpdateStreams;

/**
 * Twitch Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about the Twitch.tv Plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Twitch',
            'description' => 'Add Twitch.tv streamer statuses to PvPaissa.',
            'author'      => 'Paul Lovato',
            'icon'        => 'icon-video-camera'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Cleanse\Twitch\Components\Streamers'       => 'cleanseTwitchStreamers',
            'Cleanse\Twitch\Components\Request'         => 'cleanseTwitchRequest',
            'Cleanse\Twitch\Components\Requested'         => 'cleanseTwitchRequested',

            //Not needed?
            'Cleanse\Twitch\Components\StreamersMini'   => 'cleanseTwitchMini',
            'Cleanse\Twitch\Components\StreamersList'   => 'cleanseTwitchList',

            //Make obsolete
            'Cleanse\Twitch\Components\StreamersUpdate' => 'cleanseTwitchStreamersUpdate',
        ];
    }

    public function registerPermissions()
    {
        return [
            'cleanse.twitch.access_streamers' => [
                'tab'   => 'Twitch',
                'label' => 'Manage Streamers'
            ]
        ];
    }

    public function registerNavigation()
    {
        return [
            'twitch' => [
                'label'       => 'Twitch',
                'url'         => Backend::url('cleanse/twitch/streamers'),
                'icon'        => 'facetime-video',
                'iconSvg'     => 'plugins/cleanse/twitch/assets/images/twitch.svg',
                'permissions' => ['cleanse.twitch.*'],
                'order'       => 31,

                'sideMenu' => [
                    'new_streamer' => [
                        'label'       => 'New Streamer',
                        'icon'        => 'icon-plus',
                        'url'         => Backend::url('cleanse/twitch/streamers/create'),
                        'permissions' => ['cleanse.twitch.access_streamers']
                    ],
                    'streamersmini' => [
                        'label'       => 'Streamers',
                        'icon'        => 'icon-copy',
                        'url'         => Backend::url('cleanse/twitch/streamers'),
                        'permissions' => ['cleanse.twitch.access_streamers']
                    ]
                ]
            ]
        ];
    }

    public function boot()
    {
        /**
         * Detects a new streamer addition.
         * Will update the row slug to have an id.
         * Create better naming scheme.
         */
        Event::listen('cleanse.twitch.streamer', function () {
            $streams = new UpdateStreams;
            $streams->updateIds();
        });

        /**
         * Lists the streamer in the search results.
         */
        Event::listen('offline.sitesearch.query', function ($query) {

            $items = Streamer::where('name', 'like', "%${query}%")
                ->get();

            $results = $items->map(function ($item) use ($query) {

                $relevance = mb_stripos($item->name, $query) !== false ? 2 : 1;

                return [
                    'title'     => $item->name,
                    'text'      => $item->status,
                    'url'       => 'https://www.twitch.tv/' . $item->name,
                    'relevance' => $relevance,
                ];
            });

            return [
                'provider' => 'Twitch',
                'results'  => $results,
            ];
        });
    }

    /**
     * @param string $schedule
     * Used to update streams polled.
     */
    public function registerSchedule($schedule)
    {
//        $schedule->call(function () {
//            $update = new UpdateStreams;
//            $streams = $update->getList();
//
//            foreach ($streams as $streamers) {
//                Queue::push('\Cleanse\Twitch\Classes\Jobs\GetStreams', ['streamers' => $streamers]);
//            }
//        })->everyFiveMinutes();
    }
}
