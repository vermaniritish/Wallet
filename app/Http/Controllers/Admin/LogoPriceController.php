<?php

/**
 * Pages Class
 *
 * @package    PagesController 
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Permissions;
use App\Models\Admin\Admins;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Admin\AppController;
use App\Models\Admin\AdminAuth;
use App\Models\Admin\LogoPrices;
use App\Models\Admin\Settings;

class LogoPriceController extends AppController
{
	function __construct()
	{
		parent::__construct();
	}

    function index(Request $request)
    {
    	if(!Permissions::hasPermission('logo_prices', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}
		$filters = $this->filters($request);
		$logoPositions = Settings::get('logo_positions');
		return view(
			"admin/logoPrices/index", 
			[
				'admins' => $filters['admins'],
				'logoPositions' => $logoPositions
			]
		);
    }

    function filters(Request $request)
    {
		$admins = [];
		$adminIds = LogoPrices::distinct()->whereNotNull('created_by')->pluck('created_by')->toArray();
		if($adminIds)
		{
	    	$admins = Admins::getAll(
	    		[
	    			'admins.id',
	    			'admins.first_name',
	    			'admins.last_name',
	    			'admins.status',
	    		],
	    		[
	    			'admins.id in ('.implode(',', $adminIds).')'
	    		],
	    		'concat(admins.first_name, admins.last_name) desc'
	    	);
	    }
    	return [
	    	'admins' => $admins
    	];
    }

    function add(Request $request)
    {
    	if(!Permissions::hasPermission('logo_prices', 'update'))
    	{
    		$request->session()->flash('error', 'Permission denied.');
    		return redirect()->route('admin.dashboard');
    	}
		$data = $request->toArray();
		LogoPrices::where('option', $data['type'])->delete();
		$insertData = [];
        if (isset($data[$data['type']])) 
		{
			foreach ($data[$data['type']] as $row) 
			{
				foreach ($row['prices'] as $position => $price) 
				{
					$insertData[] = [
						'from_quantity' => $row['from_quantity'],
						'to_quantity' => $row['to_quantity'],
						'position' => $position,
						'option' => $data['type'],
						'price' => $price,
						'created_by' => AdminAuth::getLoginId(),
						'created' => date('Y-m-d H:i:s'),
						'modified' => date('Y-m-d H:i:s'),
					];
				}
			}
		}
		LogoPrices::insert($insertData);
		$request->session()->flash('success', trans('LOGO_PRICE_CREATED'));
		return redirect()->route('admin.logoPrice');
    }

	public function getLogoPrices()
	{
		$logoPrices = LogoPrices::select('id', 'position', 'option', 'from_quantity', 'to_quantity', 'price')->get();
		$logoPositions = json_decode((string)Settings::get('logo_positions'), true);
		$formattedLogoPositions = [];
		foreach ($logoPositions as $position) {
			$formattedKey = strtolower(str_replace([' ', '/'], '-', $position));
			$formattedLogoPositions[$position] = $formattedKey;
		}
		return response()->json(
			[
				'status' => true,
				'data' => $logoPrices,
				'logoPositions' => $formattedLogoPositions
			]
		);
	}

}
