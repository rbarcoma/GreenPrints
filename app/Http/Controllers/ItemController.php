<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
   public function ItemIndex()
   {
     return view('item.item');
   }


   public function createItem()
   {
    
   }
}
