<?php

/**
 * Constituency Class
 *
 * @package    ConstituencyController
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Permissions;
use App\Models\Admin\Admins;
use Illuminate\Validation\Rule;
use App\Libraries\FileSystem;
use App\Http\Controllers\Admin\AppController;
use App\Models\Admin\Menu;

class MenuController extends AppController
{
    function __construct()
    {
        parent::__construct();
    }

    function index(Request $request)
    {
        if (!Permissions::hasPermission('menu', 'listing')) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        $where = [];
        if ($request->get('search')) {
            $search = $request->get('search');
            $search = '%' . $search . '%';
            $where['(menu.id LIKE ? or menu.key LIKE ? or menu.value LIKE ?)'] = [$search, $search, $search];
        }

        if ($request->get('created_on')) {
            $createdOn = $request->get('created_on');
            if (isset($createdOn[0]) && !empty($createdOn[0]))
                $where['menu.created >= ?'] = [
                    date('Y-m-d 00:00:00', strtotime($createdOn[0]))
                ];
            if (isset($createdOn[1]) && !empty($createdOn[1]))
                $where['menu.created <= ?'] = [
                    date('Y-m-d 23:59:59', strtotime($createdOn[1]))
                ];
        }

        // if($request->get('admins'))
        // {
        // 	$admins = $request->get('admins');
        // 	$admins = $admins ? implode(',', $admins) : 0;
        // 	$where[] = 'menu.created_by IN ('.$admins.')';
        // }

        $listing = Menu::getListing($request, $where);
        if ($request->ajax()) {
            $html = view(
                "admin/menu/listingLoop",
                [
                    'listing' => $listing,
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
            return view(
                "admin/menu/index",
                [
                    'listing' => $listing,
                ]
            );
        }
    }


    public function add(Request $request)
    {
        if (!Permissions::hasPermission('menu', 'create')) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            $errors = [];

            // No validation errors, so proceed with saving the data
            foreach ($data['menuItems'] as $menuItem)
            {
                if (isset($menuItem['id']) && !empty($menuItem['id'])) {
                    // Update existing menu item
                    $payload['key'] = $menuItem['title'];
                    $payload['value'] = $menuItem['link'];
                    $payload['slug'] = $menuItem['slug'];
                    $payload['mega_menu'] = isset($menuItem['megaMenu']) && $menuItem['megaMenu'] ? json_encode($menuItem['megaMenu']) : '[]';
                    Menu::where('id', $menuItem['id'])->delete();
                    Menu::create($payload);
                } else {
                    // Create a new menu item
                    $record['key'] = $menuItem['title'];
                    $record['value'] = $menuItem['link'];
                    $record['slug'] = $menuItem['slug'];
                    $record['mega_menu'] = isset($menuItem['megaMenu']) && $menuItem['megaMenu'] ? json_encode($menuItem['megaMenu']) : '[]';
                    Menu::create($record);
                }
            }

            // if(isset($data['mega_menu_title']) && $data['mega_menu_title'])
            // {
            //     Menu::where('slug', 'mega_menu_title')->delete();
            //     $record['key'] = 'Mega Menu Title';
            //     $record['value'] = $data['mega_menu_title'];
            //     $record['slug'] = 'mega_menu_title';
            //     Menu::create($record);
            // }

            // if(isset($data['enable_mega_menu']) && $data['enable_mega_menu'] !== '' && $data['enable_mega_menu'] !== null)
            // {
            //     Menu::where('slug', 'enable_mega_menu')->delete();
            //     $record['key'] = 'Enable/Disable Mega Menu';
            //     $record['value'] = $data['enable_mega_menu'] ? 1 : 0;
            //     $record['slug'] = 'enable_mega_menu';
            //     Menu::create($record);
            // }

            // for($i = 1; $i <= 4; $i++)
            // {
            //     if(isset($data['mega_menu_title' . $i]) && $data['mega_menu_title' . $i])
            //     {
            //         Menu::where('slug', 'mega_menu_title' . $i)->delete();
            //         $record['key'] = 'Mega Menu Title' . $i;
            //         $record['value'] = $data['mega_menu_title' . $i];
            //         $record['slug'] = 'mega_menu_title' . $i;
            //         Menu::create($record);
            //     }

            //     if(isset($data['enable_mega_menu' . $i]) && $data['enable_mega_menu' . $i] !== '' && $data['enable_mega_menu' . $i] !== null)
            //     {
            //         Menu::where('slug', 'enable_mega_menu' . $i)->delete();
            //         $record['key'] = 'Enable/Disable Mega Menu' . $i;
            //         $record['value'] = $data['enable_mega_menu' . $i] ? 1 : 0;
            //         $record['slug'] = 'enable_mega_menu' . $i;
            //         Menu::create($record);
            //     }
            // }



            return response()->json([
                'status' => true,
                'message' => 'Header menu saved successfully'
            ]);
        }

        return view("admin/menu/add");
    }


    public function addFooterMenu(Request $request)
    {
        if (!Permissions::hasPermission('menu', 'create')) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        if ($request->isMethod('post')) {
            $data = $request->all();

            $validationErrors = [];
            $isValid = true;
            // Loop through each footer item and validate individually
            foreach ($data['footerItems'] as $index => $footerItem) {
                $validator = Validator::make($footerItem, [
                    'title' => [
                        'required'
                    ],
                    'link' => 'required',
                ], [
                    'title.required' => 'The title field is required.',
                    'title.regex' => 'The title cannot start with whitespace.',
                    'title.unique' => 'The title must be unique.',
                    'link.required' => 'The link field is required.',
                    'link.regex' => 'The link cannot start with whitespace.',
                ]);

                if ($validator->fails()) {
                    $validationErrors["footerItems.$index"] = $validator->errors()->toArray();
                    $isValid = false;
                }
            }

            if ($isValid) {
                // Loop through each footer item
                // dd($data['footerItems']);
                Menu::where('slug', 'LIKE', 'footer')->delete();
                foreach ($data['footerItems'] as $footerItem) {
                    // Create a new footer item
                    $record['key'] = $footerItem['title'];
                    $record['value'] = $footerItem['link'];
                    $record['slug'] = $footerItem['slug'];
                    // dd($record);
                    Menu::create($record);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Footer menu saved successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Enter fields correctly',
                    'errors' => $validationErrors
                ], 422);
            }
        }
    }





    function edit(Request $request, $id)
    {
        if (!Permissions::hasPermission('menu', 'update')) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        $Menu = Menu::get($id);
        if ($Menu) {
            if ($request->isMethod('post')) {
                $data = $request->toArray();
                $validator = Validator::make(
                    $request->toArray(),
                    [
                        'title' => ['required', 'regex:/^\S.*$/', Rule::unique('menu', 'key')->ignore($Menu)],
                        'link' => ['required', 'regex:/^\S.*$/'],
                    ]
                );
                if (!$validator->fails()) {
                    unset($data['_token']);
                    $payload['key'] = $data['title'];
                    $payload['value'] = $data['link'];
                    if (Menu::modify($id, $payload)) {
                        $request->session()->flash('success', 'Footer menu inforamtion updated.');
                        return redirect()->route('admin.Menu.view', ['id' => $id]);
                    } else {
                        $request->session()->flash('error', 'Information could not be save. Please try again.');
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                } else {
                    $request->session()->flash('error', 'Please provide valid inputs.');
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }
            return view("admin/menu/edit", [
                'page' => $Menu
            ]);
        } else {
            abort(404);
        }
    }

    public function getMenuItems()
    {
        $menuItems = Menu::select('id', 'key', 'value', 'slug', 'mega_menu as megaMenu')->get();
        foreach($menuItems as $k => $v)
        {
            $menuItems[$k]->megaMenu = $menuItems[$k]->megaMenu ? json_decode($menuItems[$k]->megaMenu) : [];
        }
        return response()->json([
            'menuItems' => $menuItems
        ]);
    }


    function delete(Request $request, $id)
    {
        if (!Permissions::hasPermission('menu', 'delete')) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        $Menu = Menu::find($id);
        if ($Menu->delete()) {
            $request->session()->flash('success', 'Footer menu deleted successfully.');
            return redirect()->route('admin.Menu');
        } else {
            $request->session()->flash('error', 'Record could not be delete.');
            return redirect()->route('admin.Menu');
        }
    }

    function bulkActions(Request $request, $action)
    {
        if (($action != 'delete' && !Permissions::hasPermission('menu', 'update')) || ($action == 'delete' && !Permissions::hasPermission('menu', 'delete'))) {
            $request->session()->flash('error', 'Permission denied.');
            return redirect()->route('admin.dashboard');
        }

        $ids = $request->get('ids');
        if (is_array($ids) && !empty($ids)) {
            switch ($action) {
                case 'delete':
                    Menu::removeAll($ids);
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

    public function deleteMenuItem($id)
    {
        $menuItem = Menu::find($id);

        if (!$menuItem) {
            return response()->json(['status' => false, 'message' => 'Menu item not found.'], 404);
        }
        $menuItem->delete();
        return response()->json(['status' => true, 'message' => 'Menu item deleted successfully.']);
    }
}
