<?php

namespace App;

use DateTime;

/**
 * @property string    _id
 * @property timestamp taken_on
 * @property string    url
 * @property object    location
 **/
class User extends \Moloquent
{
	protected $table = 'videos';

    function __construct()
    {
        parent::__construct();
    }

     
    /* Sets the location from where the video was taken
     * longitude: logitude for the location
     * latitude: latitude for the location
     */
    function public setLocation(float $longitude , float $latitude)
    {
    	if (!$longitude || !$latitude) {
    		return false;
    	}

        $location = [
            'type' => 'Point' ,
            'coordinates' => [$longitude, $latitude]
        ];

        $this->location = $location;
    }

    /* Sets the time when the video was taken
     * time: string indicating the time
    */
    function public setTakenOn (string $takenOn)
    {
    	if (!$takenOn) {
    		return false;
    	}

    	$time = UTCDateTime::create(strtotime($takenOn) * 1000);
    	if (!$time) {
    		return false;
    	}

    	$this->taken_on = $time;
    }

    function public setURL (string $videoUrl)
    {
    	if (!$videoUrl)
    	{
    		return false;
    	}

    	$this->url = $videoUrl;
    }

}