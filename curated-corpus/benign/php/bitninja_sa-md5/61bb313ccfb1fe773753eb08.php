<?php
require __DIR__.'/vendor/autoload.php';
/*
 * Including the sdk of php
 */

use Aspose\PDF\Api\PdfApi; //@am
use Aspose\PDF\Configuration; //@am


    /* ----- @am Convert PDF TO DOC AND UPLOAD TO STORAGE AND GET DOC FILE (New Api)-------- */
    $appSid = $_REQUEST['appSID'];
    $appKey = $_REQUEST['appKey'];;
    $tempFolder = '';
    $config = new Configuration();
    $config->setAppKey($appKey);
    $config->setAppSid($appSid);
	
	$pdfApi = new PdfApi(null, $config);


if(isset($_REQUEST['aspose_folder']) && !empty($_REQUEST['aspose_folder'])) {
    $aspose_folder = $_REQUEST['aspose_folder'];
} else {
    $aspose_folder = '';
}


$files = $pdfApi->getFilesList($aspose_folder);

$asposeFolders = array();
$asposeFiles = array();
$options = '<option> --- Select Folder --- </option>';
$aspose_files_rows = "
    <tr>
                    <td width=\"5%\"> </td>
					<td width=\"95%\" ><strong> File Name </strong> </td>

                  </tr>
";
foreach($files as $file){
    if($file->IsFolder == '1'){
        array_push($asposeFolders,$file->Name);
        $options .= '<option value="'.$file->Name.'">'.$file->Name.'</option>';
    } else {
        array_push($asposeFiles,$file->Name);
        if($aspose_folder !=''){
            $aspose_folder = $aspose_folder . '/';
        }
        $aspose_files_rows .= '
            <tr>
                <td> <input type="radio" name="aspose_filename" value="'.$aspose_folder . $file->Name.'" /> </td>
                <td> '.$file->Name.' </td>
            </tr>
        ';
    }
}
if(is_array($asposeFolders) && count($asposeFolders) > 0) {
    $select_aspose_folder = '<select name="aspose_folder_name" id="aspose_folder_name">';
    $select_aspose_folder .= $options;
    $select_aspose_folder .='</select>';
} else {
    $select_aspose_folder = '';
}

if(count($asposeFiles) < 1){
    $aspose_files_rows = '
        <tr>
            <td colspan="2"> No Files Found. </td>
        </tr>
    ';
}


$result = '<tr>
		<td>'.$select_aspose_folder.'</td>
		</tr>
		<tr>
		<td>
		 <table id="bucket_files" cellspacing="8" width="100%">
		    '.$aspose_files_rows.'
		 </table>
		</td>
		</tr>
	';


echo $result;

