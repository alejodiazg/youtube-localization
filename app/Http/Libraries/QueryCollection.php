<?php

namespace App\Http\Libraries;

use App\Exceptions\Query\FieldException;
use App\Exceptions\Query\FilterException;
use App\Exceptions\Query\PaginationException;
use App\Exceptions\Query\SortException;

class QueryCollection
{

    /*
    *  Paging: Use limit and offset. It is flexible for the user and common in leading databases.
    *  @throws PaginationException if both offset and limit are not set
    *
    *  GET /cars?offset=0&limit=20
    */
    public static function getPagination($offset = null, $limit = null)
    {

        //Only valid params are processed
        if (isset($offset) && isset($limit)) {

            if ((int)($offset) >= 0 && (int)($limit) >= 0) {
                return ['offset' => (int)($offset), 'limit' => (int)($limit)];
            }
        }
        throw new PaginationException('');
    }

    /*
    *  Sorting: Allow ascending and descending sorting over multiple fields.
    *  @return array with sort properties or throws an exception
    *  @throws SortException if not follows the +fiel1,-field2
    *  
    *  GET ?sort=-manufacturer,model,-id,class
    */
    public static function getSort(string $request)
    {

        $request = explode(',', $request);
        $sort = [];
        $order = $field = null;
        $valid = true;

        //Process only valid input
        foreach ($request as $item) {

            $order = substr($item, 0, 1);
            $field = substr($item, 1);

            //Check if valid +item or -item otherwise returns error
            if ($order && $field && ($order === '+' || $order === '-') && strlen($field) > 0) {
                array_push($sort, [($order === '+') ? 'asc' : 'desc', $field]);
            } else {
                $valid = false;
                break;
            }
        }
        if ($valid) {
            return $sort;
        }
        throw new SortException('');

    }

    public static function getBounds(string $request)
    {
        $bounds = json_decode($request, true);

        if (!$request['ne'] || !$request['sw']){

        } else {
            return $request;
        }
    }

    public static function getFrom(string $request)
    {

    }

    public static function getTo(string $request)
    {

    }
    /*
     * Groups the information
     * @return array
    */
    public static function getQuery(string $id = null, array $paginate = null, array $sort = null, array $bounds = null, string $from = null , string $to)
    {

        $query = [
            'id' => $id,
            'pagination' => $paginate,
            'sort' => $sort,
            'bounds' => $bounds,
            'from' => $from,
            'to' => $to
        ];

        return $query;
    }
}