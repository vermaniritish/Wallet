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
use App\Http\Controllers\Admin\AppController;
use App\Models\Admin\Settings;
use App\Models\Admin\Sliders;

class SlidersController extends AppController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        if(!Permissions::hasPermission('sliders', 'listing')) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        $where = [];
        if($request->get('search')) {
            $search = $request->get('search');
            $search = '%' . $search . '%';
            $where['(
				sliders.id LIKE ? or
				sliders.label LIKE ? or
				sliders.heading LIKE ? or
				sliders.sub_heading LIKE ? or
				sliders.button_title LIKE ?)'] = [$search, $search,$search,$search,$search];
        }

        if($request->get('created_on')) {
            $createdOn = $request->get('created_on');
            if(isset($createdOn[0]) && !empty($createdOn[0])) {
                $where['sliders.created >= ?'] = [
                    date('Y-m-d 00:00:00', strtotime($createdOn[0]))
                ];
            }
            if(isset($createdOn[1]) && !empty($createdOn[1])) {
                $where['sliders.created <= ?'] = [
                    date('Y-m-d 23:59:59', strtotime($createdOn[1]))
                ];
            }
        }

        if($request->get('button_status')) {
            switch ($request->get('button_status')) {
                case 1:
                    $where['sliders.button_status'] = 1;
                    break;
                case 0:
                    $where['sliders.button_status'] = 0;
                    break;
            }
        }

        if($request->get('admins')) {
            $admins = $request->get('admins');
            $admins = $admins ? implode(',', $admins) : 0;
            $where[] = 'sliders.created_by IN ('.$admins.')';
        }

        $listing = Sliders::getListing($request, $where);


        if($request->ajax()) {
            $html = view(
                "admin/sliders/listingLoop",
                [
                    'listing' => $listing
                ]
            )->render();

            return Response()->json([
                'status' => 'success',
                'html' => $html,
                'page' => $listing->currentPage(),
                'counter' => $listing->perPage(),
                'count' => $listing->total(),
                'pagination_counter' => $listing->currentPage() * $listing->perPage()
            ], 200);
        } else {
            $filters = $this->filters($request);
            return view(
                "admin/sliders/index",
                [
                    'listing' => $listing,
                    'admins' => $filters['admins']
                ]
            );
        }
    }

    public function filters(Request $request)
    {
        $admins = [];
        $adminIds = Sliders::distinct()->whereNotNull('created_by')->pluck('created_by')->toArray();
        if($adminIds) {
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

    public function add(Request $request)
    {
        if(!Permissions::hasPermission('sliders', 'create')) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        if($request->isMethod('post')) {
            $data = $request->toArray();
            unset($data['_token']);
            $validator = Validator::make(
                $request->toArray(),
                [
                    'label' => 'nullable|string|max:255',
                    'heading' => 'nullable|string',
                    'sub_heading' => 'nullable|string',
                    'button_title' => 'required|string|max:255',
                    'button_url' => 'required',
                    'image' => ['required'],
                ],
            );
            if(!$validator->fails()) {
                $data['status'] = 1;
                $page = Sliders::create($data);
                if($page) {
                    $request->session()->flash('success', 'Slider created successfully.');
                    return redirect()->route('admin.sliders');
                } else {
                    $request->session()->flash('error', 'Slider could not be save. Please try again.');
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            } else {
                $request->session()->flash('error', 'Please provide valid inputs.');
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        return view("admin/sliders/add", [
                ]);
    }

    public function view(Request $request, $id)
    {
        if(!Permissions::hasPermission('sliders', 'listing')) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        $page = Sliders::get($id);
        if($page) {
            return view("admin/sliders/view", [
                'page' => $page
            ]);
        } else {
            abort(404);
        }
    }

    public function edit(Request $request, $id)
    {
        if(!Permissions::hasPermission('sliders', 'update')) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        $page = Sliders::get($id);

        if($page) {
            if($request->isMethod('post')) {
                $data = $request->toArray();
                $data['button_status'] = isset($data['button_status']) && $data['button_status'] ? $data['button_status'] : 0;
                $validator = Validator::make(
                    $request->toArray(),
                    [
                        'label' => 'required|string|max:255',
                        'heading' => 'nullable|string',
                        'sub_heading' => 'nullable|string',
                        'button_title' => 'required|string|max:255',
                        'button_url' => 'required',
                        'image' => ['nullable'],
                    ],
                );
                if(!$validator->fails()) {
                    if(!isset($data['image']) || !$data['image'])
                    {
                        if(!$page->image)
                        {
                            $request->session()->flash('error', 'Slider image is required.');
                            return redirect()->back()->withErrors($validator)->withInput();
                        }
                        unset($data['image']);
                    }
                    unset($data['_token']);
                    if(Sliders::modify($id, $data)) {
                        $request->session()->flash('success', 'Slider updated successfully.');
                        return redirect()->route('admin.sliders');
                    } else {
                        $request->session()->flash('error', 'Slider could not be save. Please try again.');
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                } else {
                    $request->session()->flash('error', 'Please provide valid inputs.');
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }

            return view("admin/sliders/edit", [
                'page' => $page
            ]);
        } else {
            abort(404);
        }
    }

    public function delete(Request $request, $id)
    {
        if(!Permissions::hasPermission('sliders', 'delete')) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        $admin = Sliders::find($id);
        if($admin->delete()) {
            $request->session()->flash('success', 'Slider deleted successfully.');
            return redirect()->route('admin.sliders');
        } else {
            $request->session()->flash('error', 'Slider could not be delete.');
            return redirect()->route('admin.sliders');
        }
    }

    public function bulkActions(Request $request, $action)
    {
        if(($action != 'delete' && !Permissions::hasPermission('sliders', 'update')) || ($action == 'delete' && !Permissions::hasPermission('sliders', 'delete'))) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        $ids = $request->get('ids');
        if(is_array($ids) && !empty($ids)) {
            switch ($action) {
                case 'active':
                    Sliders::modifyAll($ids, [
                        'status' => 1
                    ]);
                    $message = count($ids) . ' records has been published.';
                    break;
                case 'inactive':
                    Sliders::modifyAll($ids, [
                        'status' => 0
                    ]);
                    $message = count($ids) . ' records has been unpublished.';
                    break;
                case 'delete':
                    Sliders::removeAll($ids);
                    $message = count($ids) . ' records has been deleted.';
                    break;
            }

            $request->session()->flash('success', $message);

            return Response()->json([
                'status' => 'success',
                'message' => $message,
            ], 200);
        } else {
            return Response()->json([
                'status' => 'error',
                'message' => 'Please select atleast one record.',
            ], 200);
        }
    }

}
