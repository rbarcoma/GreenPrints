<?php

namespace App\Http\Controllers;

use App\Models\ItemCategoryModel;
use App\Models\ItemModel;
use App\Models\MenuModel;
use App\Models\RoleModel;
use App\Models\StockModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


       $data = StockModel::with('item:id,item_name')
                ->select('item_id', 'type', DB::raw('SUM(quantity) as total'))
                ->groupBy('item_id', 'type')
                ->orderBy('item_id')
                ->get();
       $items = $data->pluck('item.item_name')->unique()->values(); 
       $stockIn = [];
       $stockOut = [];
       foreach ($items as $itemName) {
            $stockIn[] = $data->where('item.item_name', $itemName)
                            ->where('type', 'Stock In')
                            ->sum('total');
            $stockOut[] = $data->where('item.item_name', $itemName)
                                ->where('type', 'stock Out')
                                ->sum('total');
        }
            
        $stocks = StockModel::select(
             DB::raw('DATE(created_at) as date'),
                'type',
                DB::raw('SUM(quantity) as total')
            )
            ->groupBy('date', 'type')
            ->orderBy('date', 'asc')
            ->get();

        $dates = $stocks->pluck('date')->unique()->values();
        $stockInData = [];
        $stockOutData = [];
        foreach ($dates as $date) {
            $in = $stocks->where('date', $date)->where('type', 'Stock In')->sum('total');
            $out = $stocks->where('date', $date)->where('type', 'Stock Out')->sum('total');

            $stockInData[] = $in;
            $stockOutData[] = $out;
        }


        $itemsCount = ItemModel::count();
        $userCount = User::count();
        $itemCategoryCount = ItemCategoryModel::count();
        $roleCount = RoleModel::count();
        $menuCount = MenuModel::count();



        return view('home', compact('items', 'stockIn','stockOut','dates', 'stockInData', 'stockOutData','itemsCount','userCount','itemCategoryCount','roleCount','menuCount'));
    }




}
