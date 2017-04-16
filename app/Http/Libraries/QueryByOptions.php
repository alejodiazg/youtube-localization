<?php declare(strict_types = 1);
namespace App\Http\Libraries;

use DateTime;

/**
 * Serves as a decorator classes for models, allowing them to use its methods
 * Only to be used with Moloquent Models
 */
trait QueryByOptionsTrait
{

    /** This function allows to filter a task given a query array with properties like: filter, sort, fields, */
    public static function findByOptions(array $query)
    {

        $model = null;

        //ID
        if (!empty($query['id'])) {
            $model = self::find($query['id']);

        } else {

            //Bounds
            if (!empty($query['bounds'])) {
                $geoWithin = [
                    'type' => 'Polygon',
                    'coordinates' => [

                    ],
                ];
                
                if ($model) {

                } else {

                }
            }

            //Pagination
            if (!empty($query['pagination'])) {
                if ($model) {
                    $model = $model->skip($query['pagination']['offset'])->take($query['pagination']['limit']);
                } else {
                    $model = self::skip($query['pagination']['offset'])->take($query['pagination']['limit']);
                }
            }

            //From
            if (!empty($query['from'])) {
                $time = UTCDateTime::create($query['from'] * 1000);
                if ($model) {
                    $model = $model->where();
                } else {

                }
            }

            if (!empty($query['to'])) {
                  $time = UTCDateTime::create($query['from'] * 1000);
                if ($model) {

                } else {

                }
            }

            //No conditions established
            if (!$model) {
                $model = self::all();
            } else {
                $model = $model->get();
            }

        }

        return $model;
    }

}