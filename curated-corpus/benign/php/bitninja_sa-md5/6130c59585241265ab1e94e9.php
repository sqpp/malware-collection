<?php
$tab = 'main-main_settings';
if(CART66_PRO && !Cart66Setting::getValue('order_number')) {
  $tab = 'main-order_number';
}
elseif($_SERVER['REQUEST_METHOD'] == "POST") {
  if($_POST['cart66-action'] == 'saveOrderNumber' && CART66_PRO) {
    $tab = 'main-order_number';
  }
}
?>
<?php if(!empty($data['success_message'])): ?>

  <script type="text/javascript">
    (function($){
      $(document).ready(function(){
        $("#Cart66SuccessBox").fadeIn(1500).delay(4000).fadeOut(1500);
      })
    
      <?php if($data['version_info']): ?>
        $(".unregistered").show().delay(5000).hide(1500);
      <?php  endif; ?>
    
    })(jQuery);
  </script> 
  
  <div class="Cart66Modal alert-message success" id="Cart66SuccessBox" style="">
    <p><strong><?php _e( 'Success' , 'cart66' ); ?></strong><br/>
    <?php echo $data['success_message'] ?></p>
  </div>

<?php endif; ?>
<div id="saveResult"></div>
<div id="cart66-inner-tabs">
  <ul class="subsubsub">
    <li><a href="#main-main_settings" class="main-main_settings"><?php _e('Main Settings', 'cart66'); ?></a> | </li>
    <li><a href="#main-status_options" class="main-status_options"><?php _e('Status Options', 'cart66'); ?></a> | </li>
    <li><a href="#main-store_home_page" class="main-store_home_page"><?php _e('Store Home Page', 'cart66'); ?></a> | </li>
    <li><a href="#main-admin_roles" class="main-admin_roles"><?php _e('Admin Roles', 'cart66'); ?></a><?php if(CART66_PRO): ?> | <?php endif; ?></li>
    <?php if(CART66_PRO): ?>
      <li><a href="#main-membership" class="main-membership"><?php _e('Membership', 'cart66'); ?></a> | </li>
    <?php endif; ?>
    <?php if(CART66_PRO && CART66_ORDER_NUMBER == false): ?>
      <li><a href="#main-order_number" class="main-order_number"><?php _e('Order Number', 'cart66'); ?></a></li>
    <?php endif; ?>
  </ul>
  <br clear="all">
  <form action="" method="post" id="mainSettingsForm" class="ajaxSettingForm">
    <input type="hidden" name="action" value="save_settings" />
    <input type="hidden" name="_success" value="<?php _e('Your main settings have been saved', 'cart66'); ?>." />
    <div id="main-main_settings" class="pane">
      <h3><?php _e('Main Settings', 'cart66'); ?></h3>
      <table class="form-table">
        <tbody>
          <tr valign="top">
            <th scope="row"><?php _e('Home Country', 'cart66'); ?></th>
            <td>
              <?php

              ?>
              <select title="country" id="home_country" name="home_country">
                <?php 
                  $homeCountryCode = 'US';
                  $homeCountry = Cart66Setting::getValue('home_country');
                  if($homeCountry) {
                    list($homeCountryCode, $homeCountryName) = explode('~', $homeCountry);
                  }

                  foreach(Cart66Common::getCountries(true) as $code => $name) {
                    $selected = ($code == $homeCountryCode) ? 'selected="selected"' : ''; ?>
                    <option value="<?php echo $code . '~' . $name; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
                  <?php }
                ?>
              </select>
              <input type="hidden" name="include_us_territories" value="">
              <input type="checkbox" name="include_us_territories" value="1" <?php echo (Cart66Setting::getValue('include_us_territories')) ? 'checked="checked" ' : ''; ?>> <?php _e('Include US Territories', 'cart66'); ?>
              <p class="description"><?php _e( 'Your home country will be the default country on your checkout form' , 'cart66' ); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Currency Symbol', 'cart66'); ?></th>
            <td>
              <input class="small-text" type="text" name="CART66_CURRENCY_SYMBOL" value="<?php echo Cart66Setting::getValue('CART66_CURRENCY_SYMBOL', true);  ?>" id="CART66_CURRENCY_SYMBOL">
              <label for="CART66_CURRENCY_SYMBOL">
                <span class="description"><?php _e( 'Use the HTML entity such as &amp;pound; for &pound; British Pound Sterling or &amp;euro; for &euro; Euro' , 'cart66' ); ?></span>
              </label>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Currency Character', 'cart66'); ?></th>
            <td>
              <input class="small-text" type="text" name="CART66_CURRENCY_SYMBOL_text" value="<?php echo Cart66Setting::getValue('CART66_CURRENCY_SYMBOL_text');  ?>" id="CART66_CURRENCY_SYMBOL_text">
              <label for="CART66_CURRENCY_SYMBOL_text">
                <span class="description"><?php _e( 'Do NOT use the HTML entity. This is the currency character used for the plain text email receipts' , 'cart66' ); ?></span>
              </label>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Currency Position', 'cart66'); ?></th>
            <td>
              <select name="currency_position">
                <option value="before"<?php echo Cart66Setting::getValue('currency_position') != 'after' ? ' selected="selected"' : ''; ?>><?php _e('Before', 'cart66'); ?></option>
                <option value="after"<?php echo Cart66Setting::getValue('currency_position') == 'after' ? ' selected="selected"' : ''; ?>><?php _e('After', 'cart66'); ?></option>
              </select>
              <span class="description"><?php _e( 'Choose the position of the currency symbol. The default position is before: $10.00' , 'cart66' ); ?></span>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Thousands Separator', 'cart66'); ?></th>
            <td>
              <input class="small-text" type="text" name="currency_thousands_sep" value="<?php echo Cart66Setting::getValue('currency_thousands_sep') ? htmlentities(Cart66Setting::getValue('currency_thousands_sep'), ENT_COMPAT, 'UTF-8') : ',';  ?>" id="currency_thousands_sep">
              <label for="currency_thousands_sep">
                <span class="description"><?php _e( 'This sets the thousands separator.  This is usually a' , 'cart66' ); ?> ,</span>
              </label>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Decimal Point', 'cart66'); ?></th>
            <td>
              <input class="small-text" type="text" name="currency_dec_point" value="<?php echo Cart66Setting::getValue('currency_dec_point') ? htmlentities(Cart66Setting::getValue('currency_dec_point'), ENT_COMPAT, 'UTF-8') : '.';  ?>" id="currency_dec_point">
              <label for="currency_dec_point">
                <span class="description"><?php _e( 'This sets the decimal point separator.  This is usually a', 'cart66' ); ?> .</span>
              </label>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Decimals', 'cart66'); ?></th>
            <td>
              <input class="small-text" type="text" name="currency_decimals" value="<?php echo Cart66Setting::getValue('currency_decimals') == 'no_decimal' ? 0 : (Cart66Setting::getValue('currency_decimals') ? htmlentities(Cart66Setting::getValue('currency_decimals'), ENT_COMPAT, 'UTF-8') : 2);  ?>" id="currency_decimals">
              <label for="currency_decimals">
                <span class="description"><?php _e( 'This sets the number of decimal points.  Use 0 to set to none.  Default is 2.' , 'cart66' ); ?></span>
              </label>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('International Sales', 'cart66'); ?></th>
            <td>
              <input type="radio" name="international_sales" id="international_sales_yes" value="1" <?php echo Cart66Setting::getValue('international_sales') == '1' ? 'checked="checked" ' : '' ?>/>
              <label for="international_sales_yes"><?php _e( 'Yes' , 'cart66' ); ?></label>
              <input type="radio" name="international_sales" id="international_sales_no" value="" <?php echo Cart66Setting::getValue('international_sales') != '1'? 'checked="checked" ' : '' ?>/>
              <label for="international_sales_no"><?php _e( 'No' , 'cart66' ); ?></label>
            </td>
          </tr>
          <tr valign="top" class="eligible_countries_block">
            <th scope="row"><?php _e('Ship to Countries', 'cart66'); ?></th>
            <td>
              <select id="countries" name="countries[]" class="multiselect" multiple="multiple">
                <?php
                  $countryList = Cart66Setting::getValue('countries');
                  $countryList = $countryList ? explode(',', $countryList) : array();
                ?>
                <?php foreach(Cart66Common::getCountries(true) as $code => $country): ?>
                  <?php 
                    $selected = (in_array($code . '~' .$country, $countryList)) ? 'selected="selected"' : '';
                    if(!empty($code)):
                  ?>
                    <option value="<?php echo $code . '~' . $country; ?>" <?php echo $selected ?>><?php echo $country ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Hide System Pages', 'cart66'); ?></th>
            <td>
              <input type="radio" name="hide_system_pages" id="hide_system_pages_yes" value="1" <?php echo Cart66Setting::getValue('hide_system_pages') == '1' ? 'checked="checked" ' : '' ?>/>
              <label for="hide_system_pages_yes"><?php _e( 'Yes' , 'cart66' ); ?></label>
              <input type="radio" name="hide_system_pages" id="hide_system_pages_no" value="" <?php echo Cart66Setting::getValue('hide_system_pages') != '1'? 'checked="checked" ' : '' ?>/>
              <label for="hide_system_pages_no"><?php _e( 'No' , 'cart66' ); ?></label>
              <p class="description"><?php _e( 'Hiding system pages will hide all the pages that Cart66 installs from your site\'s navigation. Express, IPN, and Receipt will always be hidden. Selecting \'Yes\' will also hide Store, Cart, and Checkout which you may want to have your customers access through the Cart66 Shopping Cart widget rather than your site\'s main navigation.' , 'cart66' ); ?></p>
            </td>
          </tr>
          <?php if(CART66_PRO): ?>
          <tr valign="top">
            <th scope="row"><?php _e('Track Inventory', 'cart66'); ?></th>
            <td>
              <input type="radio" name="track_inventory" value="1" id="track_inventory_yes" <?php echo Cart66Setting::getValue('track_inventory') == '1'? 'checked="checked" ' : '' ?>> <label for="track_inventory_yes"><?php _e( 'Yes' , 'cart66' ); ?></label>
              <input type="radio" name="track_inventory" value="" id="track_inventory_no" <?php echo Cart66Setting::getValue('track_inventory') != '1'? 'checked="checked" ' : '' ?>> <label for="track_inventory_no"><?php _e( 'No' , 'cart66' ); ?></label>
              <p class="description"><?php _e( 'This feature uses ajax. If you have javascript errors in your theme clicking Add To Cart buttons will not add products to the cart.' , 'cart66' ); ?></p>
            </td>
          </tr>
          <?php endif; ?>
          <tr valign="top">
            <th scope="row"><?php _e('\'Edit Product\' Links', 'cart66'); ?></th>
            <td>
              <?php
                $editProductLinks = Cart66Setting::getValue('enable_edit_product_links');
                if(!$editProductLinks) { $editProductLinks = 'no'; }
              ?>
              <input type="radio" name="enable_edit_product_links" id="enable_edit_product_links_yes" value="1" <?php echo Cart66Setting::getValue('enable_edit_product_links') == 1 ? 'checked="checked" ' : '' ?>/>
              <label for="enable_edit_product_links_yes"><?php _e( 'Yes' , 'cart66' ); ?></label>
              <input type="radio" name="enable_edit_product_links" id"enable_edit_product_links_no" value="" <?php echo Cart66Setting::getValue('enable_edit_product_links') != 1 ? 'checked="checked" ' : '' ?>>
              <label for="enable_edit_product_links_no"><?php _e( 'No' , 'cart66' ); ?></label>
              <p class="description"><?php _e( 'Use this option to enable the edit product links on your product pages' , 'cart66' ); ?>.</span>
            </td>
          </tr>
          <?php if(CART66_PRO): ?>
            <tr valign="top">
              <th scope="row"><?php _e('Out of Stock Label', 'cart66'); ?></th>
              <td>
                <input type="text" name="label_out_of_stock" id="label_out_of_stock" class="regular-text" value="<?php echo Cart66Setting::getValue('label_out_of_stock'); ?>" />
                <p class="description">
                  <label for="label_out_of_stock"><?php _e('Set the label for the out of stock label for products.  Default: Out of stock', 'cart66'); ?></label>
                </p>
              </td>
            </tr>
          <?php endif; ?>
          <tr valign="top">
            <th scope="row"><?php _e('Digital Product Folder', 'cart66'); ?></th>
            <td>
              <input type="text" class="large-text" name="product_folder" id="product_folder" value="<?php echo Cart66Setting::getValue('product_folder'); ?>" />
              <?php
                $dir = Cart66Setting::getValue('product_folder');
                if($dir) {
                  if(!file_exists($dir)) { mkdir($dir, 0700, true); }
                  if(!file_exists($dir)) { echo "<p class='description' style='color: red;'>" . "<strong>" . __("WARNING","cart66") . ":</strong> " . __("This directory does not exist","cart66") . ".</p>"; }
                  elseif(!is_writable($dir)) { echo "<p class='description' style='color: red;'>" . "<strong>" . __("WARNING","cart66") . ":</strong> " . __("WordPress cannot write to this folder","cart66") . ".</p>"; }
                }
              ?>
              <p class="description"><?php _e( 'Enter the absolute path to where you want to store your digital products. We suggest you choose a folder that is not web accessible. To help you figure out the path to your digital products folder, this is the absolute path to the page you are viewing now.' , 'cart66' ); ?><br/>
                <?php echo realpath('.'); ?><br/>
                <?php _e( 'Please note you should NOT enter a web url starting with http:// Your filesystem path will start with just a /' , 'cart66' ); ?> 
              </p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Custom CSS URL', 'cart66'); ?></th>
            <td>
              <input type="text" name="styles_url" id="styles_url" class="regular-text" value="<?php echo Cart66Setting::getValue('styles_url'); ?>" />
              <p class="description"><?php _e( 'If you would like to override the default styles, you may enter the URL to your custom style sheet.' , 'cart66' ); ?></p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="main-status_options" class="pane">
      <h3><?php _e('Status Options', 'cart66'); ?></h3>
      <p class="description"><?php _e( 'Define the order status options to suit your business needs. For example, you may want to have new, complete, and canceled.' , 'cart66' ); ?></p>
      <table class="form-table">
        <tbody>
          <tr valign="top">
            <th scope="row"><?php _e('Order Status Options', 'cart66'); ?></th>
            <td>
              <input type="text" name="status_options" id="status_options" class="regular-text" value="<?php echo Cart66Setting::getValue('status_options'); ?>" />
              <p class="description"><?php _e( 'Separate values with commas. (ex. new,complete,cancelled)' , 'cart66' ); ?></p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="main-store_home_page" class="pane">
      <h3><?php _e('Store Home Page', 'cart66'); ?></h3>
      <p class="description"><?php _e( 'This is the link to the page of your site that you consider to be the home page of your store.
        You can choose to have customers go back to the last page they were on when they clicked "Add to Cart" or you can force the continue shopping button to always go to the store home page.' , 'cart66' ); ?></p>
      <table class="form-table">
        <tbody>
          <tr valign="top">
            <th scope="row"><?php _e('Store URL', 'cart66'); ?></th>
            <td>
              <input type="text" name="store_url" id="store_url" class="regular-text" value="<?php echo Cart66Setting::getValue('store_url'); ?>" />
              <p class="description"><?php _e('This is the link to the page of your site that you consider to be the home page of your store.', 'cart66'); ?></p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="main-admin_roles" class="pane">
      <?php if(current_user_can('manage_options')): ?>
      <script type="text/javascript" charset="utf-8">
        (function($) { 
          $(document).ready(function(){
            <?php
              $pageRoles = Cart66Setting::getValue('admin_page_roles');
              if(!empty($pageRoles)){
                foreach (unserialize($pageRoles) as $key => $value) { ?>
                $("#admin_page_roles_<?php echo $key; ?>").val('<?php echo $value; ?>');
                <?php
                }
              }
            ?>
          })

        })(jQuery)
      </script>
      <h3><?php _e('Admin Roles', 'cart66'); ?></h3>
      <p class="description"><?php _e('Set the role required to access the areas of the Cart66 plugin. Note that the ability to edit these settings requires the "manage_options" capability normally assigned to Administrators.', 'cart66'); ?>
      </p>
      <table class="form-table">
        <tbody>
          <tr valign="top">
            <th scope="row"><?php _e('Orders', 'cart66'); ?></th>
            <td>
              <select name="admin_page_roles[orders]" id="admin_page_roles_orders">
                <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
              </select>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Products', 'cart66'); ?></th>
            <td>
              <select name="admin_page_roles[products]" id="admin_page_roles_products">
                <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
              </select>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('PayPal Subscriptions', 'cart66'); ?></th>
            <td>
              <select name="admin_page_roles[paypal-subscriptions]" id="admin_page_roles_paypal-subscriptions">
                <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
              </select>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Inventory', 'cart66'); ?></th>
            <td>
              <select name="admin_page_roles[inventory]" id="admin_page_roles_inventory">
                <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
              </select>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Promotions', 'cart66'); ?></th>
            <td>
              <select name="admin_page_roles[promotions]" id="admin_page_roles_promotions">
                <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
              </select>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Shipping', 'cart66'); ?></th>
            <td>
              <select name="admin_page_roles[shipping]" id="admin_page_roles_shipping">
                <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
              </select>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Settings', 'cart66'); ?></th>
            <td>
              <select name="admin_page_roles[settings]" id="admin_page_roles_settings">
                <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
              </select>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Reports', 'cart66'); ?></th>
            <td>
              <select name="admin_page_roles[reports]" id="admin_page_roles_reports">
                <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
              </select>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Accounts', 'cart66'); ?></th>
            <td>
              <select name="admin_page_roles[accounts]" id="admin_page_roles_accounts">
                <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
              </select>
            </td>
          </tr>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
    <div id="main-membership" class="pane">
      <h3><?php _e('Blog Post Access Denied Messages', 'cart66'); ?></h3>
      <p class="description"><?php _e( 'These are the messages your visitors will see when attempting to access a blog post that they do not have permission to view.' , 'cart66' ); ?></p>
      <table class="form-table">
        <tbody>
          <tr valign="top">
            <th scope="row"><?php _e('Not Logged In', 'cart66'); ?></th>
            <td>
              <textarea class="large-textarea" id="post_not_logged_in" name="post_not_logged_in"><?php echo Cart66Setting::getValue('post_not_logged_in'); ?></textarea>
              <p class="description"><?php _e( 'The message that appears when a private posted is accessed by a visitor who is not logged in.' , 'cart66' ); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e( 'Access denied' , 'cart66' ); ?></th>
            <td>
              <textarea class="large-textarea" id="post_access_denied" name="post_access_denied"><?php echo Cart66Setting::getValue('post_access_denied'); ?></textarea>
              <p class="description"><?php _e( 'The message that appears when a logged in member\'s subscription does not allow them to view the post.' , 'cart66' ); ?></p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <table class="form-table submit-table">
      <tbody>
        <tr valign="top">
          <th scope="row">
            <?php submit_button(); ?>
          </th>
          <td></td>
        </tr>
      </tbody>
    </table>
  </form>
  <?php if(CART66_PRO && CART66_ORDER_NUMBER == false): ?>
  <div id="main-order_number" class="pane">
    <form id="orderNumberActivation" method="post">
      <input type="hidden" name="cart66-action" value="saveOrderNumber" id="saveOrderNumber">
      <h3><?php _e( 'Order Number' , 'cart66' ); ?></h3>
      <table class="form-table cart66-settings-table">
        <tbody>
          <tr valign="top">
            <th scope="row"><?php _e('Activation', 'cart66'); ?></th>
            <td>
              <input type="password" name="order_number" id="orderNumber" value="<?php echo Cart66Setting::getValue('order_number'); ?>" />
              <p class="description"><?php _e( 'Please enter your Cart66 order number to get automatic upgrades and support.', 'cart66'); ?>
                <br /><?php _e('If you do not have an order number please', 'cart66'); ?> <a href="http://www.Cart66.com"><?php _e('buy a license', 'cart66'); ?></a></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row" class="cart66-settings-table">
              <?php submit_button(); ?>
            </th>
            <td>
              <?php if(!empty($data['order_number_failed'])): ?>
                <span class="alert-message alert-error"><?php _e( 'Invalid Order Number' , 'cart66' ); ?></span>
              <?php endif; ?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
  <?php endif; ?>
</div>
<script type="text/javascript">
  (function($){
    $(document).ready(function(){
      $('#cart66-inner-tabs div.pane').hide();
      $('#cart66-inner-tabs div#<?php echo $tab; ?>').show();
      $('#cart66-inner-tabs ul li a.<?php echo $tab; ?>').addClass('current');
      
      if($('#main-order_number').attr('id') == '<?php echo $tab; ?>') {
        $('.submit-table').hide();
      }
      
      $('#cart66-inner-tabs ul li a').click(function(){
        $('#cart66-inner-tabs ul li a').removeClass('current');
        $(this).addClass('current');
        var currentTab = $(this).attr('href');
        $('#cart66-inner-tabs div.pane').hide();
        $(currentTab).show();
        $('.submit-table').show();
        if(currentTab == '#main-order_number') {
          $('.submit-table').hide();
        }
        return false;
      });
      
      $(".multiselect").multiselect({sortable: true});
      $('#international_sales_yes').click(function() {
        $('.eligible_countries_block').show();
      });
      $('#international_sales_no').click(function() {
        $('.eligible_countries_block').hide();
      });
      if($('#international_sales_no').attr('checked')) {
        $('.eligible_countries_block').hide();
      }
      
    });
  })(jQuery);
</script>