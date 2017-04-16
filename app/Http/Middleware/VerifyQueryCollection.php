<?php

namespace App\Http\Middleware;

use App\Exceptions\Query\QueryException;
use App\Http\Libraries\QueryCollection;
use Closure;

class VerifyQueryCollection
{
    /**
     * Middleware to Handle an incoming request to create a general array of properties
     * that allows the user of the method to filter, sort, paginate.
     *
     * Using this for reference: http://blog.mwaysolutions.com/2014/06/05/10-best-practices-for-better-restful-api/
     *
     * Includes in the request an standard array with properties
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     * @throws QueryException if operation fail
     */
    public function handle($request, Closure $next)
    {

        //Sets the properties to query the collection
        $bounds = $sort = $paginate = $id = null;

        /**
         * Unique Key from Route (ID parameter)
         * GET <ID>/cars?fields=manufacturer,model,id,color
         */
        if ($request->route('id') !== null) {
            $id = $request->route('id');
        }

        /*
         *  Paging: Use limit and offset. It is flexible for the user and common in leading databases.
         *  The default should be limit=20 and offset=0
         *  GET /cars?offset=0&limit=50
         */
        if ($request->has('offset') || $request->has('limit')) {
            $paginate = QueryCollection::getPagination($request->offset, $request->limit);
        }

        /*
         *  Sorting: Allow ascending and descending sorting over multiple fields.
         *  GET ?sort=-manufactorer,+model
         */
        if ($request->has('sort')) {
            $sort_request = str_replace(' ', '+', $request->sort);
            $sort = QueryCollection::getSort($sort_request);
        }

        /*
         * Field selection: Display just a few attributes in a list
         * This will also reduce the network traffic and speed up the usage of the API.
         * Give the API consumer the ability to choose returned fields.         
         * GET /cars?fields=+manufacturer,+model,+color (to show only some fields)
         * GET /cars?fields=-manufacturer,-model,-color (to remove only some fields)
         *
         * Combinations of show and hide are not allowed by mongo.
         * 
         */
        if ($request->has('fields')) {
            $field_request = str_replace(' ', '+', $request->fields);
            $fields = QueryCollection::getFields($field_request);
        }

        /*
         *  Filtering: Filters the result with the current properties
         *  Use a unique query parameter for all fields or a query language for filtering.
         *  As this requieres extra options the params should be an array of JSON
         *  GET /cars?filter=[{"property": "id", "value" : 50, "operator": ">="},
         *      {"property": "user_id", "value" : [1, 5, 10, 14], "operator": "in"}] 
         */
        if ($request->has('filter')) {
            $filter = QueryCollection::getFilters($request->filter);
        }

        /*
         * Sends the output to the controller
        */
        $query = QueryCollection::getQuery($id, $paginate, $sort, $fields, $filter);
        $request->merge(['query' => $query]);

        return $next($request);
    }
}
