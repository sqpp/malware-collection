<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: shop.pdf_output.php 1821 2009-06-24 12:18:48Z soeren_nb $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
mm_showMyFileName( __FILE__ );

$showpage = vmGet( $_REQUEST, 'showpage');
$flypage = vmGet( $_REQUEST, 'flypage');
$product_id = vmGet( $_REQUEST, 'product_id');
$category_id = vmGet( $_REQUEST, 'category_id');
// Page Navigation Parameters
$my_page= explode ( '.', $showpage  );

$pagename = $my_page[1];
$limit = intval( $vm_mainframe->getUserStateFromRequest( "viewlistlimit{$showpage}", 'limit', $mosConfig_list_limit ) );
$limitstart = intval( $vm_mainframe->getUserStateFromRequest( "view{$keyword}{$category_id}{$pagename}limitstart", 'limitstart', 0 )) ;


/* Who cares for Safe Mode ? Not me! */
if (@file_exists( "/usr/bin/htmldoc" )) {
	
	$allowed_pdf_pages = array('shop.product_details', 'shop.browse');
	if( in_array( $showpage, $allowed_pdf_pages )) exit;
	
	$load_page = escapeshellarg($mosConfig_live_site . "/index2.php?option=com_virtuemart&page=$showpage&flypage=$flypage&product_id=$product_id&category_id=$category_id&pop=1&hide_js=1&output=pdf");
	header( "Content-Type: application/pdf" );
	header( "Content-Disposition: inline; filename=\"pdf-store.pdf\"" );
	flush();
	//following line for Linux only - windows may need the path as well...
	passthru( "/usr/bin/htmldoc --no-localfiles --quiet -t pdf14 --jpeg --webpage --header t.D --footer ./. --size letter --left 0.5in $load_page" );
	exit;
} 
else {
    freePDF( $showpage, $flypage, $product_id, $category_id, $limitstart, $limit );
}
function repairImageLinks( $html ) {
	
	if( PSHOP_IMG_RESIZE_ENABLE == '1' ) {
		$images = array();
		if (preg_match_all("/<img[^>]*>/", $html, $images) > 0) {
		  $i = 0;
		  foreach ($images as $image) {
			if ( is_array( $image ) ) {
			  foreach( $image as $src) {
				  preg_match("'src=\"[^\"]*\"'si", $src, $matches);
				  $source = str_replace ("src=\"", "", $matches[0]);
				  $source = str_replace ("\"", "", $source);
				  $fileNamePos = strpos($source, "filename=");
				  if ( $fileNamePos > 0 ) {
					$firstAmpersand = strpos( $source, "&" );
					$fileName = substr( $source, $fileNamePos+9, $firstAmpersand - $fileNamePos-9 );
					$extension = strrchr( $fileName, "." );
					$fileNameNoExt = str_replace( $extension, "", $fileName );
                   // $newSource = IMAGEURL . "product/resized/".$fileNameNoExt."_".PSHOP_IMG_WIDTH."x".PSHOP_IMG_HEIGHT.$extension;
                     $newSource = IMAGEURL . "product/resized/".$fileNameNoExt.$extension;
				  }
				  else
					$newSource= $source;
				  $html = str_replace( $source, $newSource, $html );
			  }
			}
		  }
		}
	}
	return $html;

}
function freePDF( $showpage, $flypage, $product_id, $category_id, $limitstart, $limit ) {
	global $db, $sess, $auth, $my, $perm, $VM_LANG, $mosConfig_live_site, $mosConfig_sitename, $mosConfig_offset, $mosConfig_hideCreateDate, $mosConfig_hideAuthor, 
	$mosConfig_hideModifyDate,$mm_action_url, $database, $mainframe, $mosConfig_absolute_path, $vendor_full_image, $vendor_name, $limitstart, $limit,
	$vm_mainframe, $keyword, $cur_template;
	
	while( @ob_end_clean() );
	error_reporting( 0 );
	ini_set( "allow_url_fopen", "1" );
	
	switch( $showpage ) {  
		case "shop.product_details":
		  $_REQUEST['flypage'] = "shop.flypage_lite_pdf";
		  $_REQUEST['product_id'] = $product_id;

		  ob_start();
		  include( PAGEPATH . $showpage . '.php' );
		  $html .= ob_get_contents();
		  ob_end_clean();

		  $html = repairImageLinks( $html );
		  break;
		
		case "shop.browse":
		  // vmInputFilter is needed for the browse page
		  if( !isset( $vmInputFilter ) || !isset( $GLOBALS['vmInputFilter'] ) ) {
		  	$GLOBALS['vmInputFilter'] = $vmInputFilter = vmInputFilter::getInstance();
		  }

		  $_REQUEST['category_id'] = $category_id;

		  ob_start();
		  include( PAGEPATH . $showpage . '.php' );
		  $html .= ob_get_contents();
		  ob_end_clean();

		  $html = repairImageLinks( $html );
		  break;
	}
	
	$logo = IMAGEPATH . "vendor/$vendor_full_image";
	$logourl = IMAGEURL . "vendor/$vendor_full_image";
	
	if (version_compare( phpversion(), '5.0' ) < 0 || extension_loaded('domxml') || !file_exists(CLASSPATH."pdf/dompdf/dompdf_config.inc.php")) {
		
		define('FPDF_FONTPATH', CLASSPATH.'pdf/font/');
		define( 'RELATIVE_PATH', CLASSPATH.'pdf/' );
		require( CLASSPATH.'pdf/html2fpdf.php');
		require( CLASSPATH.'pdf/html2fpdf_site.php');
		
		$pdf = new PDF();
		
		$pdf->AddPage();
		$pdf->SetFont('Arial','',11);
		$pdf->InitLogo($logo);
		$pdf->PutTitle($mosConfig_sitename);
		$pdf->PutAuthor( $vendor_name );
        $html = str_replace ("&amp;", "&", $html);
		$pdf->WriteHTML($html);

		$pdf->Output();
	} elseif( file_exists(CLASSPATH."pdf/dompdf/dompdf_config.inc.php")) {
		// In this part you can use the dompdf library (http://www.digitaljunkies.ca/dompdf/)
		// Just extract the dompdf archive to /classes/pdf/dompdf
        //require_once( CLASSPATH . "pdf/dompdf/dompdf_config.inc.php" );
        //require_once( CLASSPATH . "pdf/dompdf/load_font.php" );

        //require_once( CLASSPATH . "pdf/dompdf/dompdf.php" );
        //define('DOMPDF_FONTPATH', CLASSPATH.'pdf/dompdf/lib/fonts/');
        //define( 'RELATIVE_PATH', CLASSPATH.'pdf/dompdf/' );
		$image_details = getimagesize($logo);
		$footer = '<script type="text/php">

if ( isset($pdf) ) {

  // Open the object: all drawing commands will
  // go to the object instead of the current page
  $footer = $pdf->open_object();

  $w = $pdf->get_width();
  $h = $pdf->get_height();

  // Draw a line along the bottom
  $y = $h - 2 * 12 - 24;
  $pdf->line(16, $y, $w - 16, $y, "grey", 1);

  // Add a logo
  $img_w = 2 * 72; // 2 inches, in points
  $img_h = 1 * 72; // 1 inch, in points -- change these as required
  $pdf->image("'.$logourl.'", "'.$image_details[2].'", ($w - $img_w) / 2.0, $y - $img_h, $img_w, $img_h);

  // Close the object (stop capture)
  $pdf->close_object();
  // Add the object to every page. You can
  // also specify "odd" or "even"
  $pdf->add_object($footer, "all");

}
</script>';
		
		$website = 	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>'. $mainframe->getHead().'
			<link rel="stylesheet" href="templates/'. $cur_template .'/css/template_css.css" type="text/css" />
			<link rel="stylesheet" href="'. VM_THEMEURL .'theme.css" type="text/css" />
			<link rel="shortcut icon" href="'. $mosConfig_live_site .'/images/favicon.ico" />
			<meta http-equiv="Content-Type" content="text/html; '. _ISO.'" />
			<meta name="robots" content="noindex, nofollow" />
		</head>
		<body class="contentpane">
			' . $html .'
			' . $footer .'
		</body>
	</html>';


    $website = str_replace ("resized%2F", "", $website);
    $website = str_replace ("&amp;", "&", $website);
    $website = str_replace ("#", "", $website);

		require_once( CLASSPATH."pdf/dompdf/dompdf_config.inc.php");
		$dompdf = new DOMPDF();

		$dompdf->load_html($website);
		$dompdf->render();
       // die( htmlspecialchars($website));
       //YOU CAN EITHER UNCOMMENT THE FOLLOWING LINES AND COMMENT THIS LINE --> // $dompdf->stream( "virtue".$limitstart.".pdf", array('Attachment' => 1));
       // OR LEAVE THE FOLLOWING LINES COMMENTED WITH // AND THE $dompdf->stream( "virtue".$limitstart.".pdf", array('Attachment' => 1)); UNCOMMENTED, BOTH WORK AT LAST !!
       // $file = "virtutest1.pdf";
       // file_put_contents($file, $website);
       // $url = "dompdf.php?input_file=".  $mosConfig_live_site."/".rawurlencode($file) .
       //       "&paper=letter&output_file=" . rawurlencode("virtue".$limitstart.".pdf");
       //$url  = str_replace ("%3A", ":", $url );
       //$url  = str_replace ("%5C", "/", $url );
       //$url = str_replace ("&amp;", "&", $url);
       //header("Location: ".$mosConfig_live_site . "/administrator/components/com_virtuemart/classes/pdf/dompdf/$url");


       $dompdf->stream( "virtue".$limitstart.".pdf", array('Attachment' => 1));

	}

	
}
