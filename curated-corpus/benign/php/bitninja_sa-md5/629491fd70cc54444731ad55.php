<?php
/*
* 2011-2013 LeoTheme
*
*/
// get number of category
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
include_once(dirname(__FILE__).'/leocustomajax.php');

//process category
$listCat = Tools::getValue("cat_list");
$leoProInfo = Tools::getValue("pro_info");
$leoProAdd = Tools::getValue("pro_add");
$leoProCdown = Tools::getValue("pro_cdown");
$leoProColor = Tools::getValue("pro_color");

$result = array();
$leoProductInfo = new Leocustomajax();

if($listCat){
    $listCat = explode(",", $listCat);
    $listCat = array_unique($listCat);
    $listCat = implode(",", $listCat);
    
    $sql = 'SELECT COUNT(cp.`id_product`) AS total, cp.`id_category`
					FROM `'._DB_PREFIX_.'product` p
					'.Shop::addSqlAssociation('product', 'p').'
					LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON p.`id_product` = cp.`id_product`
					WHERE cp.`id_category` IN ('.$listCat.')'.
					' AND product_shop.`visibility` IN ("both", "catalog")'.
					' AND product_shop.`active` = 1'.
                                        ' GROUP BY cp.`id_category`';
    $cat = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    if($cat) $result["cat"] = $cat;
}

if($leoProCdown){
    $leoProCdown = explode(",", $leoProCdown);
    $leoProCdown = array_unique($leoProCdown);
    $leoProCdown = implode(",", $leoProCdown);
    $result["pro_cdown"] = $leoProductInfo->hookProductCdown($leoProCdown);
}

if($leoProColor){
    $leoProColor = explode(",", $leoProColor);
    $leoProColor = array_unique($leoProColor);
    $leoProColor = implode(",", $leoProColor);
    $result["pro_color"] = $leoProductInfo->hookProductColor($leoProColor);
}


if($leoProInfo){
    $leoProInfo = explode(",", $leoProInfo);
    $leoProInfo = array_unique($leoProInfo);
    $leoProInfo = implode(",", $leoProInfo);
    
    //$leocustomajax = new Leocustomajax();
    $result["pro_info"] = $leoProductInfo->hookProductMoreImg($leoProInfo);
}
if($leoProAdd){
    $leoProAdd = explode(",", $leoProAdd);
    $leoProAdd = array_unique($leoProAdd);
    $leoProAdd = implode(",", $leoProAdd);
    
    $result["pro_add"] = $leoProductInfo->hookProductOneImg($leoProAdd);
}

if($result && ($listCat || $leoProInfo ||$leoProAdd ||$leoProCdown ||$leoProColor))
    die(Tools::jsonEncode($result));