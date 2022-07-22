<?php
	include_once('../../config/config.inc.php');
	include_once('../../init.php');

	include_once(_PS_MODULE_DIR_.'possearchproducts'.DIRECTORY_SEPARATOR.'PosSearch.php');
	$product_link = new Link();	
	$query = Tools::replaceAccentedChars(urldecode(Tools::getValue('s')));
	$category_query = Tools::replaceAccentedChars(urldecode(Tools::getValue('id_category')));
	$resultsPerPage = Tools::replaceAccentedChars(urldecode(Tools::getValue('resultsPerPage')));
	if(!$resultsPerPage || $resultsPerPage <= 1) $resultsPerPage = 10;
	$searchResults = PosSearch::find((int)(Tools::getValue('id_lang')), $query, $category_query , 1, $resultsPerPage, 'position', 'desc', true);
	foreach ($searchResults as &$product) :
	    $product['product_link'] = $product_link->getProductLink($product['id_product'], $product['prewrite'], $product['crewrite']);
	    $cproduct_id = Product::getCover($product['id_product']);
	    if (sizeof($cproduct_id) > 0) {
	        $cproduct_image = new Image($cproduct_id['id_image']);
	        $cproductimg_url = _PS_BASE_URL_ . _THEME_PROD_DIR_ . $cproduct_image->getExistingImgPath() . '-small_default' . '.jpg';
	    }
	    $product['ajaxsearchimage'] = $cproductimg_url;
	endforeach;

	$searchResults1 = array(
		'products' => $searchResults
		);
	die(Tools::jsonEncode($searchResults1));
