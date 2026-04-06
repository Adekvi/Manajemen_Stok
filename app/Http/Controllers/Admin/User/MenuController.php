<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\HakAkses\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::orderBy('group_order')
            ->orderBy('id', 'asc');

        if ($request->has('search') && $request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $menus = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            $isEmpty = $menus->isEmpty();
            $table = $isEmpty
                ? ''
                : view('admin.master.menu.table', compact('menus'))->render();

            return response()->json([
                'html' => $table,
                'empty' => $isEmpty
            ]);
        }

        return view('admin.master.menu.view', compact('menus'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'route' => 'required|unique:menus,route',
    //         'icon' => 'nullable',
    //         'group' => 'nullable',
    //         'order' => 'nullable|integer',
    //         'group_order' => 'nullable|integer',
    //         'role' => 'required',
    //     ]);

    //     Menu::create([
    //         'name' => $request->name,
    //         'route' => $request->route,
    //         'icon' => $request->icon,
    //         'group' => $request->group,
    //         'group_order' => $request->group_order ?? 0,
    //         'order' => $request->order ?? 0,
    //         'role' => $request->role,
    //         'is_active' => $request->is_active ?? 1,
    //     ]);

    //     return redirect()->route('admin.menu')->with('success', 'Menu berhasil ditambahkan');
    // }

    // public function show($id)
    // {
    //     $menu = Menu::findOrFail($id);
    //     return response()->json($menu);
    // }

    // public function update(Request $request, $id)
    // {
    //     $menu = Menu::findOrFail($id);

    //     $menu->update($request->all());

    //     return redirect()->route('admin.menu')->with('success', 'Menu berhasil diupdate');
    // }

    // public function destroy($id)
    // {
    //     Menu::destroy($id);
    //     return back()->with('success', 'Menu dihapus');
    // }
}
