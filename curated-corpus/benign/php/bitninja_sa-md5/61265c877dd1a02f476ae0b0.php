<?php
use Illuminate\Database\Capsule\Manager as Capsule;
function hook_getProductGroups($vars){
    $getProductGroups = Capsule::table('tblproductgroups')
        ->where('tblproductgroups.hidden', '0')
        ->select('id', 'name')
        ->orderBy('order', 'asc')
        ->get();
   $encodedata = json_encode($getProductGroups);
   $decodedata = json_decode($encodedata, true);
   return array("productgroups" => $decodedata);
}
add_hook("ClientAreaPage", 1, "hook_getProductGroups");
