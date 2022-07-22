<?php
/**
 * @package   WooCommerce Stock Manager
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      http:/toret.cz
 * @copyright 2015 Toret.cz
 */

$stock = $this->stock();
 
function stockautoUTF($s){
    if (preg_match('#[\x80-\x{1FF}\x{2000}-\x{3FFF}]#u', $s))
        return $s;

    if (preg_match('#[\x7F-\x9F\xBC]#', $s))
        return iconv('WINDOWS-1250', 'UTF-8', $s);

    return iconv('ISO-8859-2', 'UTF-8', $s);
}


?>
<script>

jQuery( document ).ready(function() {


    function convertArrayOfObjectsToCSV(args) {  
        var result, ctr, keys, columnDelimiter, lineDelimiter, data;

        data = args.datas || null;
        if (data == null || !data.length) {
            return null;
        }

        columnDelimiter = args.columnDelimiter || ';';
        lineDelimiter = args.lineDelimiter || '\n';

        keys = Object.keys(data[0]);

        result = '';
        result += keys.join(columnDelimiter);
        result += lineDelimiter;
        
        console.log(args);
        
        data.forEach(function(item) {
            ctr = 0;
            keys.forEach(function(key) {
                if (ctr > 0) result += columnDelimiter;

                result += item[key];
                ctr++;
            });
            result += lineDelimiter;
        });

        return result;
    }

    jQuery('.product-export').on( 'click', function(e){  

        e.preventDefault();
        jQuery( '.export-output' ).empty();
        jQuery( '#csv' ).append( 'Generating csv file' );
        jQuery( '#csv' ).css( 'display','block' );

        var offset = '0';
        wsm_export_products( offset );        

        function wsm_export_products( offset ) {
            
            var data = {
                'action'  : 'wsm_get_products_or_export',
                'offset'   : offset
            };

            jQuery.post(ajaxurl, data, function( response ) {
                
                var result = jQuery.parseJSON( response );
                
                if( result.status != 'finish' ){
                    
                    var jsonObject = JSON.stringify(result.data);

                    jsonObject = jsonObject.slice( 1 );
                    jsonObject = jsonObject.slice(0, -1);

                    jQuery( '.export-output' ).append( jsonObject + ',' );

                    wsm_export_products( result.offset );
                    

                }else{
                    
                    var string = jQuery( '.export-output' ).text();
                    string = string.slice(0, -1);
                    
                    string = '{' + string + '}';

                    jQuery( '#csv' ).empty();
                    jQuery( '#csv' ).append( 'All done!' );

                    var data = {
                        'action'  : 'wsm_get_csv_file',
                        'data'   : string
                    };

                    jQuery.post(ajaxurl, data, function( response ) {

                        var data, filename, link;
                        var csv = convertArrayOfObjectsToCSV({
                            datas: JSON.parse( response )
                        });
                        if (csv == null) return;

                        filename = 'stock-manager-export.csv';

                        if (!csv.match(/^data:text\/csv/i)) {
                            csv = 'data:text/csv;charset=utf-8,' + csv;
                        }
                        data = encodeURI(csv);

                        link = document.createElement('a');
                        link.setAttribute('href', data);
                        link.setAttribute('download', filename);
                        link.click();

                    });
            
                }
    

            });
        }
    });
});
</script>
<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  
  

  
<div class="t-col-6">
  <div class="toret-box box-info">
    <div class="box-header">
      <h3 class="box-title"><?php _e('Import','woocommerce-stock-manager'); ?></h3>
    </div>
  <div class="box-body">
    <h4><?php _e('You can upload csv file, with your stock data. ','woocommerce-stock-manager'); ?></h4>
    <p><?php _e('CSV file must be in this format, or you can export file with exist data and edit them. ','woocommerce-stock-manager'); ?></p>
    <p><?php _e('If you have a lot of products and export/import not working, increase memory limit.. ','woocommerce-stock-manager'); ?></p>
    <h3><?php _e('File format. ','woocommerce-stock-manager'); ?></h3>
    <table class="table-bordered">
      <tr>
        <td><?php _e('SKU','woocommerce-stock-manager'); ?></td>
        <td><?php _e('Product name','woocommerce-stock-manager'); ?></td>
        <td><?php _e('Manage stock','woocommerce-stock-manager'); ?></td>
        <td><?php _e('Stock status','woocommerce-stock-manager'); ?></td>
        <td><?php _e('Backorders','woocommerce-stock-manager'); ?></td>
        <td><?php _e('Stock','woocommerce-stock-manager'); ?></td>
        <td><?php _e('Product type','woocommerce-stock-manager'); ?></td>
        <td><?php _e('Parent SKU','woocommerce-stock-manager'); ?></td>
      </tr>
      <tr>
        <td><?php _e('111111','woocommerce-stock-manager'); ?></td>
        <td><?php _e('T-shirt','woocommerce-stock-manager'); ?></td>
        <td><?php _e('yes','woocommerce-stock-manager'); ?></td>
        <td><?php _e('instock','woocommerce-stock-manager'); ?></td>
        <td><?php _e('yes','woocommerce-stock-manager'); ?></td>
        <td><?php _e('10','woocommerce-stock-manager'); ?></td>
        <td><?php _e('simple','woocommerce-stock-manager'); ?></td>
        <td></td>
      </tr>  
    </table>  
    
    <ul>
      <li><strong><?php _e('SKU','woocommerce-stock-manager'); ?></strong> <?php _e('product unique identificator, required. Neccessary for import and export.','woocommerce-stock-manager'); ?></li>
      <li><strong><?php _e('Manage stock','woocommerce-stock-manager'); ?></strong> <?php _e('values: "yes", "notify", "no". If is empty "no" will be save.','woocommerce-stock-manager'); ?></li>
      <li><strong><?php _e('Stock status','woocommerce-stock-manager'); ?></strong> <?php _e('values: "instock", "outofstock". If is empty "outofstock" will be save.','woocommerce-stock-manager'); ?></li>
      <li><strong><?php _e('Backorders','woocommerce-stock-manager'); ?></strong> <?php _e('values: "yes", "notify", "no". If is empty "no" will be save.','woocommerce-stock-manager'); ?></li>
      <li><strong><?php _e('Stock','woocommerce-stock-manager'); ?></strong> <?php _e('quantity value. If is empty, 0 will be save.','woocommerce-stock-manager'); ?></li>
    </ul>
    
    
    <form method="post" action="" class="setting-form" enctype="multipart/form-data">	
        <table class="table-bordered">
          <tr>
            <th><?php _e('Upload csv file', 'woocommerce-stock-manager'); ?></th>
            <td>
              <input type="file" name="uploadFile">
            </td>
          </tr>
    
        </table>
        <div class="clear"></div>
      <input type="hidden" name="upload" value="ok" />
      <input type="submit" class="btn btn-info" value="<?php _e('Upload', 'woocommerce-stock-manager'); ?>" />
    </form>  
    <?php
    if(isset($_POST['upload'])){
  
        $target_dir = STOCKDIR.'admin/views/upload/';
        $target_dir = $target_dir . basename( $_FILES["uploadFile"]["name"]);
        $uploadOk   = true;

        if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_dir)) {
  
            echo __('The file '. basename( $_FILES['uploadFile']['name']). ' has been uploaded.','woocommerce-stock-manager');
    
            $row = 1;
            if (($handle = fopen($target_dir, "r")) !== FALSE) {
  
                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    $num = count($data);
                  
                    $product_id   = stockautoUTF($data[0]);
                    $sku          = stockautoUTF($data[1]);
                    $manage_stock = stockautoUTF($data[3]);
                    $stock_status = stockautoUTF($data[4]);
                    $backorders   = stockautoUTF($data[5]);
                    $stock        = stockautoUTF($data[6]);             
    
                    if($row != 1){
                      
                        if( !empty( $product_id ) ){

                            $values = array(
                                'manage_stock' => $manage_stock,
                                'backorders' => $stock_status,
                                'stock_status' => $backorders,
                                'stock' => $stock
                            );

                            WCM_Save::save_one_item( $data, $product_id );
                      
                            echo '<p>'.__('Product with ID: '.$product_id.' was updated.','woocommerce-stock-manager').'</p>';
    
                        }
                    }
                    $row++;
    
                }
                fclose($handle);
            }
      
        }else{
            echo '<p>'.__('Sorry, there was an error uploading your file.','woocommerce-stock-manager').'</p>';
        }
  
    } 
?>    
  </div>
</div>
</div>



<div class="t-col-6">
  <div class="toret-box box-info">
    <div class="box-header">
      <h3 class="box-title"><?php _e('Export','woocommerce-stock-manager'); ?></h3>
    </div>
  <div class="box-body">
    <h4><?php _e('You can download csv file, with your stock data. ','woocommerce-stock-manager'); ?></h4>
    <p><a href="#" class="btn btn-danger product-export"><?php _e('Create export file','woocommerce-stock-manager'); ?></a></p> 
    <div class="export-output" style="display:none;"></div>
    <div id="csv" style="display:none;"></div>
  </div>
</div>
</div>  
  

</div>
