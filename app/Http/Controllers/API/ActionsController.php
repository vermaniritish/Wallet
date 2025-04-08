<?php

/**
 * Actions Class
 *
 * @package    ActionsController
 
 
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Settings;
use App\Models\Admin\Blogs;
use App\Models\Admin\Permissions;
use App\Models\Admin\AdminAuth;
use App\Libraries\General;
use App\Libraries\FileSystem;
use App\Models\Admin\Actions;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\BaseController;

class ActionsController extends BaseController
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	* To Upload File
	* @param Request $request
	*/
    function uploadFile(Request $request)
    {
    	$data = $request->toArray();
    	$validator = Validator::make(
            $request->toArray(),
            [
                'path' => 'required',
                'file_type' => 'required',
                'file' => 'required',
            ]
        );

    	if(!$validator->fails())
	    {
	    	if($request->file('file')->isValid())
	    	{
	    		$file = null;
	    		if($data['file_type'] == 'image')
	    		{
		    		$file = FileSystem::uploadImage(
	    				$request->file('file'),
	    				$data['path']
	    			);

	    			if($file)
	    			{
	    				$originalName = FileSystem::getFileNameFromPath($file);

	    				if(isset($data['resize_large']) && $data['resize_large'])
	    				{
	    					FileSystem::resizeImage($file, $originalName, $data['resize_large']);
	    				}
	    				
	    				if(isset($data['resize_small']) && $data['resize_small'])
	    				{
	    					FileSystem::resizeImage($file, 'S-' . $originalName, $data['resize_small']);
	    				}
					}
		    	}
		    	else
		    	{
		    		$file = FileSystem::uploadFile(
	    				$request->file('file'),
	    				$data['path']
	    			);
		    	}

    			if($file)
    			{
    				$names = explode('/', $file);
					return Response()->json([
				    	'status' => 'success',
				    	'message' => 'File uploaded successfully.',
				    	'url' => url($file),
				    	'name' => end($names),
				    	'path' => $file
				    ]);
    				
    			}
    			else
    			{
    				return Response()->json([
				    	'status' => 'error',
				    	'message' => 'File could not be upload.'
				    ]);		
    			}
	    	}
	    	else
	    	{
	    		return Response()->json([
		    	'status' => 'error',
		    	'message' => 'File could not be uploaded.'
		    ]);	
	    	}
	   	}
	    else
	    {
	    	return Response()->json([
		    	'status' => 'error',
		    	'message' => 'File could not be uploaded due to missing parameters.'
		    ]);	
	    }
    }

    /**
	* To Remove File
	* @param Request $request
	*/
    function removeFile(Request $request)
    {
    	$data = $request->toArray();

    	$validator = Validator::make(
            $request->toArray(),
            [
                'file' => 'required',
            ]
        );

    	if(!$validator->fails())
	    {
	    	if(isset($data['relation']) && $data['relation'])
	    	{
	    		$relation = explode('.', $data['relation']);
	    		if(count($relation) > 1 && $relation[0] == 'settings')
	    		{
	    			// In case of settings table
	    			if(Settings::put($relation[1], ""))
					{
						FileSystem::deleteFile($data['file']);
						return Response()->json([
					    	'status' => 'success',
					    	'message' => 'File removed successfully.'
					    ]);
					}
					else
					{
						return Response()->json([
					    	'status' => 'error',
					    	'message' => 'File could not be removed.'
					    ]);  		
					}
	    		}
	    		else if(count($relation) > 1 && isset($data['id']) && $data['id'])
	    		{
	    			// In case of other tables
	    			$record = DB::table($relation[0])
			            ->select([
			            	$relation[1]
			            ])
			            ->where('id', $data['id'])
			            ->limit(1)
			            ->first();

			        if($record && $record->{$relation[1]})
			        {
			        	$file = $record->{$relation[1]};
			        	$multiple = json_decode($file, true);
						$allFiles = $multiple && is_array($multiple) ? $multiple : ($file ? [$file] : null);
						
						$index = array_search($data['file'], $allFiles);
						if($index !== false && isset($allFiles[$index]) && $allFiles[$index])
						{
							unset($allFiles[$index]);
							$allFiles = array_values($allFiles);
							$allFiles = !empty($allFiles) ? json_encode($allFiles) : "";
							$updated  = DB::table($relation[0])
								->where('id', $data['id'])
								->update([
									"{$relation[1]}" => $allFiles
								]);
							if($updated)
							{
								FileSystem::deleteFile($data['file']);
								return Response()->json([
							    	'status' => 'success',
							    	'message' => 'File removed successfully.'
							    ]);
							}
							else
							{
								return Response()->json([
							    	'status' => 'error',
							    	'message' => 'File could not be removed.'
							    ]);  		
							}
						}
						else
						{
							return Response()->json([
						    	'status' => 'error',
						    	'message' => 'File could not be removed.'
						    ]);  
						}
			        }
			    }
			    else
			    {
			 		return Response()->json([
				    	'status' => 'error',
				    	'message' => 'Relation is missing or invalid.'
				    ]);   	
			    }
	    	}
	    	elseif(FileSystem::deleteFile($data['file']))
    		{
    			return Response()->json([
			    	'status' => 'success',
			    	'message' => 'File removed successfully.'
			    ]);
    		}
    		else
    		{
	    		return Response()->json([
			    	'status' => 'error',
			    	'message' => 'File could not be removed.'
			    ]);
	    	}
	    }
	    else
	    {
	    	return Response()->json([
		    	'status' => 'error',
		    	'message' => 'File parameter is missing.'
		    ]);
	    }
    }

    /**
	* To Upload File
	* @param Request $request
	* @param $table
	* @param $field
	* @param $id
	*/
    function switchUpdate(Request $request, $table, $field, $id)
    {
    	$data = $request->toArray();

    	$validator = Validator::make(
            $request->toArray(),
            [
                'flag' => 'required'
            ]
        );

    	if(!$validator->fails())
	    {
	    	$updated  = DB::table($table)
					->where('id', $id)
					->update([
						"{$field}" => $request->get('flag')
					]);
	    	if($updated)
	    	{
	    		return Response()->json([
			    	'status' => 'success',
			    	'message' => 'Record updated successfully.'
			    ]);	
	    	}
	    	else
	    	{
	    		return Response()->json([
			    	'status' => 'error',
			    	'message' => 'Record could not be update.'
			    ]);		
	    	}
	    	
	    }
	    else
	    {
	    	return Response()->json([
		    	'status' => 'error',
		    	'message' => 'Record could not be update.'
		    ]);
	    }
    }

	
}
