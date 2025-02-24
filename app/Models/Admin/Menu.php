<?php

namespace App\Models\Admin;

use App\Models\AppModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\FileSystem;
use Illuminate\Support\Str;
use App\Libraries\General;

class Menu extends AppModel
{
    protected $table = 'menu';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public static function getListing(Request $request, $where = [])
    {
        $orderBy = $request->get('sort') ? $request->get('sort') : 'menu.id';
        $direction = $request->get('direction') ? $request->get('direction') : 'desc';
        $page = $request->get('page') ? $request->get('page') : 1;
        $limit = self::$paginationLimit;
        $offset = ($page - 1) * $limit;

        $listing = Menu::select([
            'menu.*',
        ])
        ->orderBy($orderBy, $direction);
        if (!empty($where)) {
            foreach ($where as $query => $values) {
                if (is_array($values))
                    $listing->whereRaw($query, $values);
                elseif (!is_numeric($query))
                    $listing->where($query, $values);
                else
                    $listing->whereRaw($values);
            }
        }

        // Put offset and limit in case of pagination
        if ($page !== null && $page !== "" && $limit !== null && $limit !== "") {
            $listing->offset($offset);
            $listing->limit($limit);
        }

        $listing = $listing->paginate($limit);

        return $listing;
    }

    /**
     * To get all records
     * @param $where
     * @param $orderBy
     * @param $limit
     */
    public static function getAll($select = [], $where = [], $orderBy = 'menu.id desc', $limit = null)
    {
        $listing = Menu::orderByRaw($orderBy);

        if (!empty($select)) {
            $listing->select($select);
        } else {
            $listing->select([
                'menu.*'
            ]);
        }

        if (!empty($where)) {
            foreach ($where as $query => $values) {
                if (is_array($values))
                    $listing->whereRaw($query, $values);
                elseif (!is_numeric($query))
                    $listing->where($query, $values);
                else
                    $listing->whereRaw($values);
            }
        }

        if ($limit !== null && $limit !== "") {
            $listing->limit($limit);
        }

        $listing->orderByRaw($orderBy);

        $listing = $listing->get();

        return $listing;
    }

    /**
     * To get single record by id
     * @param $id
     */
    public static function get($id)
    {
        $record = Menu::where('id', $id)
            ->first();

        return $record;
    }



    /**
     * To insert
     * @param $where
     * @param $orderBy
     */
    public static function create($data)
    {
        $Menu = new Menu();

        // Populate the properties from the provided data
        foreach ($data as $k => $v) {
            $Menu->{$k} = $v;
        }
        if ($Menu->save()) {
            return $Menu;
        } else {
            return null;
        }
    }


    /**
     * To update
     * @param $id
     * @param $where
     */
    public static function modify($id, $data)
    {
        $Menu = Menu::find($id);
        if (!$Menu) {
            return null;
        }
        foreach ($data as $k => $v) {
            $Menu->{$k} = $v;
        }
        if ($Menu->save()) {
            return $Menu;
        } else {
            return null;
        }
    }



    /**
     * To delete
     * @param $id
     */
    public static function remove($id)
    {
        $Menu = Menu::find($id);
        return $Menu->delete();
    }

    /**
     * To delete all
     * @param $id
     * @param $where
     */
    public static function removeAll($ids)
    {
        if (!empty($ids)) {
            return Menu::whereIn('menu.id', $ids)
                ->delete();
        } else {
            return null;
        }
    }

}
