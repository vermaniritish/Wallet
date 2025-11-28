<?php
namespace App\Models\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\API\Admins;
use App\Libraries\FileSystem;
use App\Libraries\General;
use App\Models\Admin\Settings;

class ApiAuth extends Admins
{

    protected $hidden = ['token', 'password'];
    protected $table = 'admins';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**** ONLY USE FOR MAIN TALBLES NO NEED TO USE FOR RELATION TABLES OR DROPDOWNS OR SMALL SECTIONS ***/
    use SoftDeletes;

    /**
    * Get resize images
    *
    * @return array
    */
    public function getImageAttribute($value)
    {
        return $value ? FileSystem::getAllSizeImages($value) : null;
    }

    /**
    * To get single row by conditions
    * @param $where
    * @param $orderBy
    */
    public static function getRow($where = [], $orderBy = 'admins.id desc')
    {
        $record = ApiAuth::orderByRaw($orderBy);

        foreach($where as $query => $values)
        {
            if(is_array($values))
                $record->whereRaw($query, $values);
            elseif(!is_numeric($query))
                $record->where($query, $values);
            else
                $record->whereRaw($values);
        }
        
        $record = $record->limit(1)->first();

        return $record;
    }

    /**
    * To make admin login
    * @param Request $request
    */
    public static function attemptLogin(Request $request)
    {
    	//Get user
    	$user = ApiAuth::where('email', 'LIKE', $request->get('email'))
    		->where('status', 1)
    		->first();
        if($user)
        {
            if(Hash::check( $request->get('password'), $user->password))
            {
            	return $user;
            }
        }

        return null;
    }

    public static function makeLoginSession(Request $request, $user)
    {
        //Make login
        if($admin)
        {
            if(Settings::get('client_multi_device_logins'))
            {
                if($request->bearerToken())
                {
                    UsersTokens::where( 'token', trim($request->bearerToken()) )->where('user_id', $admin->id)->delete();
                }

                if($request->get('device_id'))
                {
                    UsersTokens::where('device_id', $request->get('device_id'))->where('user_id', $admin->id)->delete();
                }
            }
            else
            {
                UsersTokens::where('user_id', $admin->id)->delete();
            }

            $expireMins = Settings::get('session_expires_in_minutes');
            $token = General::hash(64);
            $token = UsersTokens::create([
                'user_id' => $admin->id,
                'token' => $token,
                'device_id' => $request->get('device_id') ? $request->get('device_id') : null,
                'device_type' => $request->get('device_type') ? $request->get('device_type') : 'web',
                'device_name' => $request->get('device_name') ? $request->get('device_name') : null,
                'fcm_token' => $request->get('device_name') ? $request->get('device_name') : null,
                'expire_on' => date('Y-m-d H:i:s', strtotime("+{$expireMins} minutes"))
            ]);

            if(!empty($token))
            {
                Admins::modify($admin->id, ['last_login' => date('Y-m-d H:i:s')]);
                
                $admin->access = [
                    'id' => $token->id,
                    'token' => $token->token
                ];

                return $admin;
            }
        }

        return null;
    }

    /**
    * To update token
    * @param $id
    */
    public static function updateToken()
    {
        $request = request();
        if($request->bearerToken())
        {
            $expireMins = Settings::get('session_expires_in_minutes');
            $userToken = UsersTokens::where('token', $request->bearerToken())
                ->where('expire_on', '>', date('Y-m-d H:i:s'))
                ->first();
            $userToken->expire_on = date('Y-m-d H:i:s', strtotime("+{$expireMins} minutes"));
            return $userToken->save();
        }
        return null;
    }

    public static function isLogin()
    {
        $request = request();
        $userId = UsersTokens::getUserId($request->bearerToken());
        return $userId ? true : false;
    }

    public static function getLoginId()
    {
        $request = request();
        return UsersTokens::getUserId($request->bearerToken());
    }

    public static function getLoginUserName()
    {
        $request = request();
        $userId = UsersTokens::getUserId($request->bearerToken());
        if($userId)
        {
            $record = Admins::select(['first_name', 'last_name'])
                ->where('id', $userId)
                ->first();
            return $record ? $record->first_name . ' ' . $record->last_name : "";
        }
        else
        {
            return "";
        }
    }

    public static function getLoginImage()
    {
        $request = request();
        $userId = UsersTokens::getUserId($request->bearerToken());
        if($userId)
        {
            $record = Admins::select(['image'])
                ->where('id', $userId)
                ->first();
            return $record && $record->image && isset($record->image['small']) && $record->image['small'] ? $record->image['small'] : (isset($record->image['original']) && $record->image['original'] ? $record->image['original'] : null);
        }
        else
        {
            return "";
        }
    }

    public static function getLoginUser()
    {
        $request = request();
        $userId = UsersTokens::getUserId($request->bearerToken());
        $user = null;
        if($userId)
        {
            $token = UsersTokens::get($request->bearerToken());
            if($token && $token->user)
            {
                $user = $token->user;
                $user->access = [
                    'id' => $token->id,
                    'token' => $token->token
                ];
            }
        }

        return $user ? $user : null;
    }

    public static function logout()
    {
        $request = request();
        if($request->bearerToken())
        {
            UsersTokens::where('token', $request->bearerToken())->delete();
        }
    }
}