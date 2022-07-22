<?php
$rate = new Cart66TaxRate();
$setting = new Cart66Setting();
$successMessage = '';
$versionInfo = false;

if($_SERVER['REQUEST_METHOD'] == "POST") {
  if($_POST['cart66-action'] == 'save rate') {
    $data = $_POST['tax'];
    if(isset($data['zip']) && !empty($data['zip'])) {
      list($low, $high) = explode('-', $data['zip']);
      
      if(isset($low)) {
        $low = trim($low);
      }
      
      if(isset($high)) {
        $high = trim($high);
      }
      else { $high = $low; }
      
      if(is_numeric($low) && is_numeric($high)) {
        if($low > $high) {
          $x = $high;
          $high = $low;
          $low = $x;
        }
        $data['zip_low'] = $low;
        $data['zip_high'] = $high;
      }
      
    }
    $rate->setData($data);
    $rate->save();
    $rate->clear();
    $successMessage = "Tax rate saved";
  }
  elseif($_POST['cart66-action'] == 'saveOrderNumber' && CART66_PRO) {
    $orderNumber = trim(Cart66Common::postVal('order_number'));
    Cart66Setting::setValue('order_number', $orderNumber);
    $versionInfo = Cart66ProCommon::getVersionInfo();
    if($versionInfo) {
      $successMessage = "Thank you! Cart66 has been activated.";
    }
    else {
      Cart66Setting::setValue('order_number', '');
      $orderNumberFailed = true;
    }
  }
} 
elseif(isset($_GET['task']) && $_GET['task'] == 'deleteTax' && isset($_GET['id']) && $_GET['id'] > 0) {
  $id = Cart66Common::getVal('id');
  $rate->load($id);
  $rate->deleteMe();
  $rate->clear();
}

$cardTypes = Cart66Setting::getValue('auth_card_types');
if($cardTypes) {
  $cardTypes = explode('~', $cardTypes);
}
else {
  $cardTypes = array();
}

?>

<?php if(!empty($successMessage)): ?>

<script type="text/javascript">
  (function($){
    $(document).ready(function(){
      $("#Cart66SuccessBox").fadeIn(1500).delay(4000).fadeOut(1500);
    })
    
    <?php if($versionInfo): ?>
      $(".unregistered").show().delay(5000).hide(1500);
    <?php  endif; ?>
    
  })(jQuery);
</script> 
  
<div class='Cart66SuccessModal' id="Cart66SuccessBox" style=''>
  <p><strong><?php _e( 'Success' , 'cart66' ); ?></strong><br/>
  <?php echo $successMessage ?></p>
</div>

<?php endif; ?>

<!-- Example Code Block -->
<!--
<div id="widgets-left">
  <div id="available-widgets">
    
    <div class="widgets-holder-wrap">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3>Example Setting <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description">This is a test</p>
        <div>
          <p>This is the content area</p>
        </div>
      </div>
    </div>
    
  </div>
</div>
-->

<h2><?php _e( 'Cart66 Settings' , 'cart66' ); ?></h2>

<div id="saveResult"></div>

<div id="widgets-left" style="margin-right: 50px;">
  <div id="available-widgets">

    <?php if(CART66_PRO && CART66_ORDER_NUMBER == false): ?>    
    <!-- Order Number -->
    <div class="widgets-holder-wrap">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Order Number' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'Please enter your Cart66 order number to get automatic upgrades and support.<br/>
          If you do not have an order number please <a href="http://www.Cart66.com">buy a license</a>.' , 'cart66' ); ?></p>
        <div>
          
          <form id="orderNumberActivation" method="post">
            <input type="hidden" name="cart66-action" value="saveOrderNumber" id="saveOrderNumber">
            <ul>
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;"  for='order_number'><?php _e( 'Order Number' , 'cart66' ); ?>:</label>
                <input type='text' name='order_number' id='orderNumber' style='width: 375px;' 
                  value="<?php echo Cart66Setting::getValue('order_number'); ?>" />
              </li>
              
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;" >&nbsp;</label>
                <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" />
                <?php if(!empty($orderNumberFailed)): ?>
                  <span style="color: red;"><?php _e( 'Invalid Order Number' , 'cart66' ); ?></span>
                <?php endif; ?>
              </li>
            </ul>
          </form>
          
        </div>
      </div>
    </div>
    <?php endif; ?>
    
    <!-- Main Settings -->
    <div class="widgets-holder-wrap">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Main Settings' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <div>
          <form id="orderNumberForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="Your main settings have been saved.">
            <ul>
              
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;" for='paypalpro_email'><?php _e( 'Hide system pages' , 'cart66' ); ?>:</label>
                <input type='radio' name='hide_system_pages' id='hide_system_pages' value="1" 
                  <?php echo Cart66Setting::getValue('hide_system_pages') == '1' ? 'checked="checked"' : '' ?>/> <?php _e( 'Yes' , 'cart66' ); ?>
                <input type='radio' name='hide_system_pages' id='hide_system_pages' value="" 
                  <?php echo Cart66Setting::getValue('hide_system_pages') != '1'? 'checked="checked"' : '' ?>/> <?php _e( 'No' , 'cart66' ); ?>
                <p class="label_desc" style="width: 450px;"><?php _e( 'Hiding system pages will hide all the pages that Cart66 installs 
                  from your site\'s navigation. Express, IPN, and Receipt will always be hidden. Selecting \'Yes\' will also hide
                  Store, Cart, and Checkout which you may want to have your customers access through the Cart66 Shopping Cart widget rather than your site\'s main navigation.' , 'cart66' ); ?></p>
              </li>
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;"  for='order_number'><?php _e( 'Home country' , 'cart66' ); ?>:</label>
                <select title="country" id="home_country" name="home_country">
                  <?php 
                    $homeCountryCode = 'US';
                    $homeCountry = Cart66Setting::getValue('home_country');
                    if($homeCountry) {
                      list($homeCountryCode, $homeCountryName) = explode('~', $homeCountry);
                    }
                    
                    foreach(Cart66Common::getCountries(true) as $code => $name) {
                      $selected = ($code == $homeCountryCode) ? 'selected="selected"' : '';
                      echo "<option value=\"$code~$name\" $selected>$name</option>";
                    }
                  ?>
                </select>
                <input type="hidden" name="include_us_territories" value="">
                <input type="checkbox" name="include_us_territories" value="1" <?php echo (Cart66Setting::getValue('include_us_territories')) ? "checked='checked'" : ""; ?>> <?php _e('Include US Territories', 'cart66'); ?>
                <p class="label_desc"><?php _e( 'Your home country will be the default country on your checkout form' , 'cart66' ); ?></p>
              </li>
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;"><?php _e( 'Currency symbol' , 'cart66' ); ?>:</label>
                <input type="text" name="CART66_CURRENCY_SYMBOL" value="<?php echo htmlentities(Cart66Setting::getValue('CART66_CURRENCY_SYMBOL'));  ?>" id="CART66_CURRENCY_SYMBOL">
                <span class="description"><?php _e( 'Use the HTML entity such as &amp;pound; for &pound; British Pound Sterling or &amp;euro; for &euro; Euro' , 'cart66' ); ?></span>
              </li>
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;"><?php _e( 'Currency character' , 'cart66' ); ?>:</label>
                <input type="text" name="CART66_CURRENCY_SYMBOL_text" value="<?php echo Cart66Setting::getValue('CART66_CURRENCY_SYMBOL_text'); ?>" id="CART66_CURRENCY_SYMBOL_text">
                <span class="description"><?php _e( 'Do NOT use the HTML entity. This is the currency character used for the email receipts.' , 'cart66' ); ?></span>
              </li>
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;" for='international_sales'><?php _e( 'International sales' , 'cart66' ); ?>:</label>
                <input type='radio' name='international_sales' id='international_sales_yes' value="1" 
                  <?php echo Cart66Setting::getValue('international_sales') == '1' ? 'checked="checked"' : '' ?>/> <?php _e( 'Yes' , 'cart66' ); ?>
                <input type='radio' name='international_sales' id='international_sales_no' value="" 
                  <?php echo Cart66Setting::getValue('international_sales') != '1'? 'checked="checked"' : '' ?>/> <?php _e( 'No' , 'cart66' ); ?>
              </li>
              
              <li id="eligible_countries_block">
                <label style="display: inline-block; width: 120px; text-align: right;" for="countries"><?php _e( 'Ship to countries' , 'cart66' ); ?>:</label>
                <div style="float: none; margin: -10px 0px 20px 125px;">
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
                </div>
              </li>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for=""><?php _e( 'Accept Cards' , 'cart66' ); ?>:</label>
                <input type="checkbox" name="auth_card_types[]" value="mastercard" style='width: auto;' 
                <?php echo in_array('mastercard', $cardTypes) ? 'checked="checked"' : '' ?>><label style='width: auto; padding-left: 5px;'>Mastercard</label>
                <input type="checkbox" name="auth_card_types[]" value="visa" style='width: auto;'
                <?php echo in_array('visa', $cardTypes) ? 'checked="checked"' : '' ?>><label style='width: auto; padding-left: 5px;'>Visa</label>
                <input type="checkbox" name="auth_card_types[]" value="amex" style='width: auto;'
                <?php echo in_array('amex', $cardTypes) ? 'checked="checked"' : '' ?>><label style='width: auto; padding-left: 5px;'>American Express</label>
                <input type="checkbox" name="auth_card_types[]" value="discover" style='width: auto;'
                <?php echo in_array('discover', $cardTypes) ? 'checked="checked"' : '' ?>><label style='width: auto; padding-left: 5px;'>Discover</label>
                <p style="width: 450px;" class="label_desc"><?php _e('You also need to configure your Payment Gateway settings or your PayPal Pro Settings if you are accepting credit cards', 'cart66'); ?></p>
              </li>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;"><?php _e( 'Use SSL' , 'cart66' ); ?>:</label>
              <?php
                $force = Cart66Setting::getValue('auth_force_ssl');
                if(!$force) { $force = 'no'; }
              ?>
              <input type='radio' name='auth_force_ssl' value="yes" style='width: auto;' <?php if($force == 'yes') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'Yes' , 'cart66' ); ?></label>
              <input type='radio' name='auth_force_ssl' value="no" style='width: auto;' <?php if($force == 'no') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'No' , 'cart66' ); ?></label>
                <p style="width: 450px;" class="label_desc"><?php _e( 'Be sure use an SSL certificate if you are using a payment gateway other than PayPal Website Payments Standard or PayPal Express Checkout.' , 'cart66' ); ?></p>
              </li>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;"><?php _e( 'Database Sessions' , 'cart66' ); ?>:</label>
              <?php
                $session_type = Cart66Common::sessionType();
              ?>
              <input type='radio' name='session_type' value="database" style='width: auto;' <?php if($session_type == 'database') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'Yes' , 'cart66' ); ?></label>
              <input type='radio' name='session_type' value="native" style='width: auto;' <?php if($session_type == 'native') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'No' , 'cart66' ); ?></label>
              <p style="width: 450px;" class="label_desc"><?php _e( 'Database sessions offer better performance but if you have trouble with them, try using the standard PHP sessions by disabling database sessoins' , 'cart66' ); ?></p>
              </li>
              
              <?php if(CART66_PRO): ?>
                <li><label style="display: inline-block; width: 120px; text-align: right;" for="track_inventory"><?php _e( 'Track inventory' , 'cart66' ); ?>:</label>
                <?php
                  $track = Cart66Setting::getValue('track_inventory');
                ?>
                <input type="radio" name="track_inventory" value="1" style="width: auto;" <?php if($track == '1') { echo "checked='checked'"; } ?>><label style="width: auto; padding-left: 5px;"><?php _e( 'Yes' , 'cart66' ); ?></label>
                <input type="radio" name="track_inventory" value="0" style="width: auto;" <?php if($track == '0') { echo "checked='checked'"; } ?>><label style="width: auto; padding-left: 5px;"><?php _e( 'No' , 'cart66' ); ?></label>
                  <p style="width: 450px;" class="label_desc"><?php _e( 'This feature uses ajax. If you have javascript errors in your theme clicking Add To Cart buttons will not add products to the cart.' , 'cart66' ); ?></p>
                </li>
              <?php endif; ?>

              <li>
                <label style="display: inline-block; width: 120px; text-align: right;" >&nbsp;</label>
                <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" />
              </li>

            </ul>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Cart and Checkout Settings -->
    <div class="widgets-holder-wrap <?php echo (Cart66Setting::getValue('sameAsBillingOff') || Cart66Setting::getValue('userPriceLabel') || Cart66Setting::getValue('userQuantityLabel') || Cart66Setting::getValue('product_links_in_cart')) ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Cart and Checkout Settings' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( '' , 'cart66' ); ?></p>
        <div>
          <form id="cartCheckoutSettingsForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="The cart and checkout settings have been saved." />
            <input type='hidden' name='disable_edit_product_links' value="" />
            <ul>
              <li><label style="display: inline-block; width: 120px; text-align: right;"><?php _e( 'Shipping Form' , 'cart66' ); ?>:</label>
              <?php
                $shippingOff = Cart66Setting::getValue('sameAsBillingOff');
                if(!$shippingOff) { $shippingOff = 'on'; }
              ?>
              <input type='radio' name='sameAsBillingOff' value="" style='width: auto;' <?php if($shippingOff == 'on') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'Show "Same as Billing"' , 'cart66' ); ?></label>
              <input type='radio' name='sameAsBillingOff' value="1" style='width: auto;' <?php if($shippingOff == '1') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'Always show the shipping form' , 'cart66' ); ?></label>
                <p style="width: 450px;" class="label_desc"><?php _e( 'Choose whether or not to display the shipping address form on the checkout page by default.' , 'cart66' ); ?></p>
              </li>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='userPriceLabel'><?php _e( 'User Price Label' , 'cart66' ); ?>:</label>
              <input type='text' name='userPriceLabel' id='userPriceLabel' style='width: 375px;' value="<?php echo Cart66Setting::getValue('userPriceLabel'); ?>" />
              <p style="width: 450px;" class="label_desc"><?php _e( 'Defaults to "Enter an amount: "' , 'cart66' ); ?></p>
              </li>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='userQuantityLabel'><?php _e( 'User Quantity Label' , 'cart66' ); ?>:</label>
              <input type='text' name='userQuantityLabel' id='userQuantityLabel' style='width: 375px;' value="<?php echo Cart66Setting::getValue('userQuantityLabel'); ?>" />
              <p style="width: 450px;" class="label_desc"><?php _e( 'Defaults to "Quantity: "' , 'cart66' ); ?></p>
              </li>
              
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;" for='ajaxOptions'><?php _e( 'AJAX Add To Cart' , 'cart66' ); ?>:</label>
                <?php
                  $enableAjaxDefault = Cart66Setting::getValue('enable_ajax_by_default');
                  if(!$enableAjaxDefault) { $enableAjaxDefault = 'no'; }
                ?>
                <input type='radio' name='enable_ajax_by_default' value="yes" style='width: auto;' <?php if($enableAjaxDefault == 'yes') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'Yes' , 'cart66' ); ?></label>
                <input type='radio' name='enable_ajax_by_default' value="no" style='width: auto;' <?php if($enableAjaxDefault == 'no') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'No' , 'cart66' ); ?></label>
                <p style="width: 450px;" class="label_desc"><?php _e( 'This changes the default action when adding a product shortcode to a page or post' , 'cart66' ); ?>.</p>
              </li>

              <li>
                <label style="display: inline-block; width: 120px; text-align: right;" for='product_links_in_cart'><?php _e( 'Product Links in Cart' , 'cart66' ); ?>:</label>
                <?php
                  $enableProductLinkDefault = Cart66Setting::getValue('product_links_in_cart');
                  if(!$enableProductLinkDefault) { $enableProductLinkDefault = 'no'; }
                ?>
                <input type='radio' name='product_links_in_cart' value="yes" style='width: auto;' <?php if($enableProductLinkDefault == 'yes') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'Yes' , 'cart66' ); ?></label>
                <input type='radio' name='product_links_in_cart' value="no" style='width: auto;' <?php if($enableProductLinkDefault == 'no') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'No' , 'cart66' ); ?></label>
                <p style="width: 450px;" class="label_desc"><?php _e( 'Use this option to add a link back to the original product page' , 'cart66' ); ?>.</span>
              </li>
              
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;" for='product_links_in_cart'><?php _e( 'Edit Product Links' , 'cart66' ); ?>:</label>
                <?php
                  $editProductLinks = Cart66Setting::getValue('enable_edit_product_links');
                  if(!$editProductLinks) { $editProductLinks = 'no'; }
                ?>
                <input type='radio' name='enable_edit_product_links' value="yes" style='width: auto;' <?php if($editProductLinks == 'yes') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'Yes' , 'cart66' ); ?></label>
                <input type='radio' name='enable_edit_product_links' value="no" style='width: auto;' <?php if($editProductLinks == 'no') { echo "checked='checked'"; } ?>><label style='width: auto; padding-left: 5px;'><?php _e( 'No' , 'cart66' ); ?></label>
                <p style="width: 450px;" class="label_desc"><?php _e( 'Use this option to enable the edit product links on your product pages' , 'cart66' ); ?>.</span>
              </li>
              
              <?php if(CART66_PRO): ?>
                <li>
                  <label style="display: inline-block; width: 20px; text-align: right;">&nbsp;</label>
                  <strong><?php _e( 'Minimum Cart Amount Settings' , 'cart66' ); ?></strong>
                </li>
                <li><label style="display: inline-block; width: 120px; text-align: right;" for="minimum_cart_amount"><?php _e( 'Min. Amount' , 'cart66' ); ?>:</label>
                <?php
                  $track = Cart66Setting::getValue('minimum_cart_amount');
                ?>
                <input type="radio" name="minimum_cart_amount" id="minimum_cart_amount_yes" value="1" style="width: auto;" <?php if($track == '1') { echo "checked='checked'"; } ?>><label style="width: auto; padding-left: 5px;"><?php _e( 'Yes' , 'cart66' ); ?></label>
                <input type="radio" name="minimum_cart_amount" id="minimum_cart_amount_no" value="0" style="width: auto;" <?php if($track == '0') { echo "checked='checked'"; } ?>><label style="width: auto; padding-left: 5px;"><?php _e( 'No' , 'cart66' ); ?></label>
                  <p style="width: 450px;" class="label_desc"><?php _e( 'This feature allows you to set a minimum cart amount before your customers can checkout.' , 'cart66' ); ?></p>
                </li>

                <li class="min_amount">
                  <label style="display: inline-block; width: 120px; text-align: right;" for="minimum_amount"><?php _e( 'Amount' , 'cart66' ); ?>: <?php echo CART66_CURRENCY_SYMBOL; ?></label>
                  <input type="text" name="minimum_amount" value="<?php echo htmlentities(Cart66Setting::getValue('minimum_amount'));  ?>" id="minimum_amount">
                  <span class="description"><?php _e( 'Set the amount required in order for a customer to checkout' , 'cart66' ); ?>.</span>
                </li>

                <li class="min_amount"><label style="display: inline-block; width: 120px; text-align: right;" for='minimum_amount_label'><?php _e( 'Min. Amount Label' , 'cart66' ); ?>:</label>
                <input type='text' name='minimum_amount_label' id='minimum_amount_label' style='width: 375px;' value="<?php echo Cart66Setting::getValue('minimum_amount_label'); ?>" />
                <p style="width: 450px;" class="label_desc"><?php _e( 'Defaults to "You have not yet reached the required minimum amount in order to checkout."' , 'cart66' ); ?></p>
                </li>
              <?php endif; ?>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            </ul>
          </form>
        </div>
      </div>
    </div>
  
    <!-- Tax Rates -->
    <?php $rates = $rate->getModels(); ?>
    <div class="widgets-holder-wrap <?php echo count($rates) ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Tax Rates' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'If you would like to collect sales tax please enter the tax rate information below. 
          You may enter tax rates for zip codes or states. If you are entering zip codes, you can enter individual 
          zip codes or zip code ranges. A zip code range is entered with the low value separated from the high value
          by a dash. For example, 23000-25000. Zip code tax rates take precedence over state tax rates.
          You may also choose whether or not you want to apply taxes to shipping charges.' , 'cart66' ); ?></p>
          
        <p class="description"><?php _e( 'NOTE: If you are using PayPal Website Payments Standard you must set up the tax rate 
          information <strong>in your paypal account</strong>.' , 'cart66' ); ?></p>
          
        <div>
          <form action="" method='post'>
            <input type='hidden' name='cart66-action' value="save rate" />
            <ul>
              <li><label for="tax-state" style="width: auto;"><?php _e( 'State' , 'cart66' ); ?>:</label>
                <select name='tax[state]' id="tax-state">
                  <option value="">&nbsp;</option>
                  <option value="All Sales"><?php _e( 'All Sales' , 'cart66' ); ?></option>
                  <optgroup label="United States">
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DC">D. C.</option>
                    <option value="DE">Delaware</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                  </optgroup>
                  <optgroup label="Canada">
                    <option value="AB">Alberta</option>
                    <option value="BC">British Columbia</option>
                    <option value="MB">Manitoba</option>
                    <option value="NB">New Brunswick</option>
                    <option value="NF">Newfoundland</option>
                    <option value="NT">Northwest Territories</option>
                    <option value="NS">Nova Scotia</option>
                    <option value="NU">Nunavut</option>
                    <option value="ON">Ontario</option>
                    <option value="PE">Prince Edward Island</option>
                    <option value="PQ">Quebec</option>
                    <option value="SK">Saskatchewan</option>
                    <option value="YT">Yukon Territory</option>
                  </optgroup>
                </select>
              
                <span style="width: auto; text-align: center; padding: 0px 10px;"><?php _e( 'or' , 'cart66' ); ?></span>
                <label for="tax-zip" style='width:auto;'><?php _e( 'Zip' , 'cart66' ); ?>:</label>
                  <input type='text' value="" id="tax-zip" name='tax[zip]' size="14" />
                <label for="tax-rate" style='width:auto; padding-left: 5px;'><?php _e( 'Rate' , 'cart66' ); ?>:</label>
                  <input type='text' value="" id="tax-rate" name='tax[rate]' style='width: 55px;' /> %
                <select name='tax[tax_shipping]'>
                  <option value="0"><?php _e( 'Don\'t tax shipping' , 'cart66' ); ?></option>
                  <option value="1"><?php _e( 'Tax shipping' , 'cart66' ); ?></option>
                </select>
                <input type='submit' name='submit' class="button-primary" style='width: 60px; margin: 10px; margin-right: 0px;' value="<?php _e('Save', 'cart66'); ?>" />
              </li>
            </ul>
          </form>
          
          <?php if(count($rates)): ?>
          <table class="widefat" style='width: 350px; margin-bottom: 30px;'>
          <thead>
          	<tr>
          		<th><?php _e( 'Location' , 'cart66' ); ?></th>
          		<th><?php _e( 'Rate' , 'cart66' ); ?></th>
          		<th><?php _e( 'Tax Shipping' , 'cart66' ); ?></th>
          		<th><?php _e( 'Actions' , 'cart66' ); ?></th>
          	</tr>
          </thead>
          <tbody>
            <?php foreach($rates as $rate): ?>
             <tr>
               <td>
                 <?php 
                 if($rate->zip_low > 0) {
                   if($rate->zip_low > 0) { echo str_pad($rate->zip_low, 5, "0", STR_PAD_LEFT); }
                   if($rate->zip_high > $rate->zip_low) { echo '-' . str_pad($rate->zip_high, 5, "0", STR_PAD_LEFT); }
                 }
                 else {
                   echo $rate->getFullStateName();
                 }
                 ?>
               </td>
               <td><?php echo number_format($rate->rate,2) ?>%</td>
               <td>
                 <?php
                 echo $rate->tax_shipping > 0 ? __("yes","cart66") : __("no","cart66");
                 ?>
               </td>
               <td>
                 <a class='delete' href='?page=cart66-settings&task=deleteTax&id=<?php echo $rate->id ?>'><?php _e( 'Delete' , 'cart66' ); ?></a>
               </td>
             </tr>
            <?php endforeach; ?>
          </tbody>
          </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <!-- Mijireh Settings -->
    <?php
      $has_mijireh = Cart66Setting::getValue('mijireh_store_id') || Cart66Setting::getValue('mijireh_access_key');
    ?>
    <div class="widgets-holder-wrap <?php echo ($has_mijireh) ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e('Mijireh Settings - Secure Credit Card Processing', 'cart66'); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <?php if(!Cart66Setting::getValue('mijireh_access_key')): ?>
          <p class="description"><a href="http://www.mijireh.com"><?php _e('Secure credit card processing. Get started for FREE', 'cart66'); ?></a>.</p>
        <?php endif; ?>
        
        <p class="description"><?php _e('Accept credit cards with peace of mind using', 'cart66'); ?> <a href="http://www.mijireh.com"><?php _e('Mijireh', 'cart66'); ?></a>. 
          <?php _e('You focus on the selling while Mijireh takes care of the security', 'cart66'); ?>.</p>
        
        <p class="description"><?php _e('Note: Mijireh checkout will not process recurring payments', 'cart66'); ?>.</p>
        <?php if(!$has_mijireh): ?>
          <p class="description"><a href="http://mijireh.com"><?php _e('Get Mijireh Now', 'cart66'); ?></a></p>
        <?php endif; ?>
        <div>
          <form id="MijirehSettings" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="Your Mijireh settings have been saved.">
            <ul>
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;" for='mijireh_access_key'><?php _e( 'Access Key' , 'cart66' ); ?>:</label>
                <input type='text' name='mijireh_access_key' id='mijireh_access_key' style='width: 375px;' value="<?php echo Cart66Setting::getValue('mijireh_access_key'); ?>" />
              </li>
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;">&nbsp;</label>
                <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" />
              </li>
            </ul>
          </form>
        </div>
        
        <?php
          $post = get_page_by_path('store/mijireh-secure-checkout');
          $slurp_page = 'post.php?post=' . $post->ID . '&action=edit';
        ?>
        <p class="description">To get setup with Mijireh:</p>
        <p class="description">
        1. Enter you Mijireh Access Key<br/>
        2. Make sure your store/checkout page has the [checkout_mijireh] shortcode on it.<br/>
        3. Go to the <a href="<?php echo $slurp_page; ?>">Mijireh Secure Checkout</a> page and slurp it<br/>
        4. Configure your gateways in Mijireh and go live<br/>
        </p>
      </div>
    </div>

    
    <!-- PayPal Settings -->
    <div class="widgets-holder-wrap <?php echo (Cart66Setting::getValue('paypal_email') || Cart66Setting::getValue('paypalpro_api_username') ) ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'PayPal Settings' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'If you have signed up for the PayPal Pro account or if you plan to use PayPal Express Checkout, 
          please configure you settings below.' , 'cart66' ); ?></p>
        <div>
          <form id="PayPalSettings" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="Your PayPal settings have been saved.">
            <input type="hidden" name="paypal_sandbox" value="" />

            <ul>
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='paypal_email'><?php _e( 'PayPal Email' , 'cart66' ); ?>:</label>
              <input type='text' name='paypal_email' id='paypal_email' style='width: 375px;' value="<?php echo Cart66Setting::getValue('paypal_email'); ?>" />
              </li>
              
              <label style="display: inline-block; width: 120px; text-align: right;" for="currency_code"><?php _e( 'Default Currency' , 'cart66' ); ?>:</label>
              <select name="currency_code"  id="currency_code">
                <?php
                  $currencies = Cart66Common::getPayPalCurrencyCodes();
                  $current_lc = Cart66Setting::getValue('currency_code');
                  foreach($currencies as $name => $code) {
                    $selected = '';
                    if($code == $current_lc) {
                      $selected = 'selected="selected"';
                    }
                    echo "<option value=\"$code\" $selected>$name</option>\n";
                  }
                ?>
              </select>

              <li><label style="display: inline-block; width: 120px; text-align: right;" for='shopping_url'><?php _e( 'Shopping URL' , 'cart66' ); ?>:</label>
              <input type='text' name='shopping_url' id='paypal_email' style='width: 375px;' value="<?php echo Cart66Setting::getValue('shopping_url'); ?>" />
              <p style="margin-left: 125px;" class="description"><?php _e( 'Used when buyers click \'Continue Shopping\' in the PayPal Cart.' , 'cart66' ); ?></p>
              </li>

              <li><label style="display: inline-block; width: 120px; text-align: right;" for='paypal_return_url'><?php _e( 'Return URL' , 'cart66' ); ?>:</label>
              <input type='text' name='paypal_return_url' id='paypal_return_url' 
              style='width: 375px;' value="<?php echo Cart66Setting::getValue('paypal_return_url'); ?>" />
              <p style="margin-left: 125px;" class="description"><?php _e( 'Where buyers are sent after paying at PayPal.' , 'cart66' ); ?></p>
              </li>

              <li><label style="display: inline-block; width: 120px; text-align: right;" for='ipn_url'><?php _e( 'Notification URL' , 'cart66' ); ?>:</label>
              <span style="padding:0px; margin:0px;">
                <?php
                $ipnPage = get_page_by_path('store/ipn');
                $ipnUrl = get_permalink($ipnPage->ID);
                echo $ipnUrl;
                ?>
              </span>
              <p style="margin-left: 125px;" class="description"><?php _e( 'Instant Payment Notification (IPN)' , 'cart66' ); ?></p></li>

              <li>
                <label style="display: inline-block; width: 120px; text-align: right;">&nbsp;</label>
                <strong><?php _e( 'PayPal API Settings for Express Checkout' , 'cart66' ); ?> <?php if(CART66_PRO) { echo 'and Website Payments Pro'; } ?></strong>
              </li>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='paypalpro_api_username'><?php _e( 'API Username' , 'cart66' ); ?>:</label>
              <input type='text' name='paypalpro_api_username' id='paypalpro_api_username' style='width: 375px;' 
              value="<?php echo Cart66Setting::getValue('paypalpro_api_username'); ?>" />
              </li>

              <li><label style="display: inline-block; width: 120px; text-align: right;" for='paypalpro_api_password'><?php _e( 'API Password' , 'cart66' ); ?>:</label>
              <input type='text' name='paypalpro_api_password' id='paypalpro_api_password' style='width: 375px;' 
              value="<?php echo Cart66Setting::getValue('paypalpro_api_password'); ?>" />
              </li>			  

              <li><label style="display: inline-block; width: 120px; text-align: right;" for='paypalpro_api_signature'><?php _e( 'API Signature' , 'cart66' ); ?>:</label>
              <input type='text' name='paypalpro_api_signature' id='paypalpro_api_signature' style='width: 375px;' 
              value="<?php echo Cart66Setting::getValue('paypalpro_api_signature'); ?>" />
              </li>

			  <?php if(CART66_PRO): ?>
				
				<li style="padding-top:10px;">
	                <label style="display: inline-block; width: 120px; text-align: right;">&nbsp;</label>
	                <strong><?php _e( 'Dont Require PayPal account for Express Checkout' , 'cart66' ); ?></strong>
	            </li>
	
				<li>
	                <label style="display: inline-block; width: 120px; text-align: right;" for='express_force_paypal'>&nbsp;</label>
					<input type="hidden" name='express_force_paypal' value=''>
	                <input type='checkbox' name='express_force_paypal' id='express_force_paypal' value="true" 
	                  <?php echo Cart66Setting::getValue('express_force_paypal') ? 'checked="checked"' : '' ?>
	                />
	                <span class="label_desc"><?php _e( 'Allow Express Checkout customers to checkout without a PayPal Account' , 'cart66' ); ?></span>
	            </li>

			  <?php endif; ?>
              
              <li style="padding-top:10px;">
                <label style="display: inline-block; width: 120px; text-align: right;">&nbsp;</label>
                <strong><?php _e( 'Use PayPal Sandbox' , 'cart66' ); ?></strong>
              </li>
              
              <li>
                <label style="display: inline-block; width: 120px; text-align: right;" for='paypal_sandbox'>&nbsp;</label>
                <input type='checkbox' name='paypal_sandbox' id='paypal_sandbox' value="1" 
                  <?php echo Cart66Setting::getValue('paypal_sandbox') ? 'checked="checked"' : '' ?>
                />
                <span class="label_desc"><?php _e( 'Send transactions to <a href="https://developer.paypal.com">PayPal\'s developer sandbox</a>.' , 'cart66' ); ?></span>
              </li>

              <li><label style="display: inline-block; width: 120px; text-align: right;">&nbsp;</label>
                <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
                
              <?php if(CART66_PRO): ?>
                <li><p class='label_desc' style='color: #999'><?php _e( 'Note: The Website Payments Pro solution can only be implemented by UK, Canadian and US Merchants.' , 'cart66' ); ?>
                  <a href="https://www.x.com/developers/paypal/products/website-payments-pro"><?php _e( 'Learn more' , 'cart66' ); ?></a></p></li>
              <?php else: ?>
                <li><p class='label_desc' style='color: #999'><?php _e( 'Note: The Website Payments Pro solution is only available in <a href="http://cart66.com">Cart66 Professional</a> and can only be implemented by UK, Canadian and US Merchants.' , 'cart66' ); ?></p></li>
              <?php endif; ?>
              
            </ul>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Gateway Settings -->
    <a name="gateway"></a>
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('auth_url') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Payment Gateway Settings' , 'cart66' ); ?><span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <?php if(CART66_PRO): ?>
        <p class="description"><?php _e( 'These settings configure your connection to your Authorize.net AIM compatible payment gateway.' , 'cart66' ); ?></p>
        <!--
        <p class="description"><b>Authorize.net URL:</b> <em>https://secure.authorize.net/gateway/transact.dll</em></p>
        <p class="description"><b>Quantum Gateway URL:</b> <em>https://secure.quantumgateway.com/cgi/authnet_aim.php</em></p>
        -->
        <div>
          <form id="AuthorizeFormSettings" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="Your payment gateway settings have been saved.">
            <input type="hidden" name="eway_sandbox" value="" />
            <input type='hidden' name='payleap_test_mode' value="" />
            <input type='hidden' name='mwarrior_test_mode' value="" />
            <input type='hidden' name='stripe_test' value="" />

            <ul>
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='auth_url'><?php _e( 'Gateway' , 'cart66' ); ?>:</label>
                <select name="auth_url" id="auth_url">
                  <option value="https://secure.authorize.net/gateway/transact.dll">Authorize.net</option>
                  <option value="https://test.authorize.net/gateway/transact.dll">Authorize.net Test</option>
                  <option value="https://secure.quantumgateway.com/cgi/authnet_aim.php">Quantum Gateway</option>
                  <option value="https://www.eway.com.au/gateway_cvn/xmlpayment.asp">eWay</option>
                  <option value="https://api.merchantwarrior.com/post/">Merchant Warrior</option>
                  <option value="https://secure1.payleap.com/TransactServices.svc/ProcessCreditCard">PayLeap</option>
                  <option value="https://api.stripe.com/v1/charges">Stripe</option>
                  <option value="other"><?php _e( 'Other' , 'cart66' ); ?></option>
                </select>
                <p id="authorizenetTestMessage" class="description"><?php _e( 'The Authorize.net test server requires a developer test account which is different than your normal authorize.net account. You can sign up for one here: <a href="https://developer.authorize.net/testaccount/" target="_blank">https://developer.authorize.net/testaccount/' , 'cart66' ); ?></a></p>
              </li>
              
              
              <li id="emulation_url_item">
                <label style="display: inline-block; width: 120px; text-align: right;" for='emulation_url'><?php _e( 'Emulation URL' , 'cart66' ); ?>:</label>
                <input type='text' name='auth_url_other' id='auth_url_other' style='width: 375px;' value="<?php echo Cart66Setting::getValue('auth_url_other'); ?>" />
                <p id="emulation_url_desc" class="description" style='margin-left: 125px;'><?php _e( 'Autorize.net AIM emulation URL' , 'cart66' ); ?></p>
              </li>
              

              <div id="eway_live">
                <li>
                  <label style="display: inline-block; width: 120px; text-align: right;" for='eway_customer_id'><?php _e( 'eWay Customer ID' , 'cart66' ); ?>:</label>
                  <input type='text' name='eway_customer_id' id='eway_customer_id' style='width: 375px;' value="<?php echo Cart66Setting::getValue('eway_customer_id'); ?>" />
                </li>
                <li>  
                  <label style="display: inline-block; width: 120px; text-align: right;" for='eway_sandbox'><?php _e( 'eWay Sandbox' , 'cart66' ); ?>:</label>
                  <input type="checkbox" name="eway_sandbox" id="eway_sandbox" class="eway_sandbox" value="1" <?php echo Cart66Setting::getValue('eway_sandbox') ? 'checked="checked"' : '' ?> />
                </li>
                <div id="eway_sandbox_display">
                  <p class="description"><?php _e( 'These are the settings used for test transactions with eWay.' , 'cart66' ); ?></p>
                  <li>
                    <label style="display: inline-block; width: 120px; text-align: right;" for='eway_sandbox_customer_id'><?php _e( 'Sandbox ID' , 'cart66' ); ?>:</label>
                    <input type='text' name='eway_sandbox_customer_id' id='eway_sandbox_customer_id' style='width: 375px;' value="<?php echo Cart66Setting::getValue('eway_sandbox_customer_id'); ?>" />
                  </li>
                </div>
              </div>
              
              <div id="stripe_live">
                <li>
                  <label style="display: inline-block; width: 120px; text-align: right;" for='stripe_api_key'><?php _e( 'Stripe API Key' , 'cart66' ); ?>:</label>
                  <input type='text' name='stripe_api_key' id='stripe_api_key' style='width: 375px;' value="<?php echo Cart66Setting::getValue('stripe_api_key'); ?>" />
                </li>
                <li>  
                  <label style="display: inline-block; width: 120px; text-align: right;" for='stripe_test'><?php _e( 'Stripe Test Mode' , 'cart66' ); ?>:</label>
                  <input type="checkbox" name="stripe_test" id="stripe_test" class="stripe_test" value="1" <?php echo Cart66Setting::getValue('stripe_test') ? 'checked="checked"' : '' ?> />
                </li>
                <div id="stripe_test_display">
                  <p class="description"><?php _e( 'These are the settings used for test transactions with Stripe.' , 'cart66' ); ?></p>
                  <li>
                    <label style="display: inline-block; width: 120px; text-align: right;" for='stripe_test_api_key'><?php _e( 'Stripe Test API Key' , 'cart66' ); ?>:</label>
                    <input type='text' name='stripe_test_api_key' id='stripe_test_api_key' style='width: 375px;' value="<?php echo Cart66Setting::getValue('stripe_test_api_key'); ?>" />
                  </li>
                </div>
              </div>
              
              <div id="mwarrior_live">
                <li>
                  <label style="display: inline-block; width: 120px; text-align: right;" for='mwarrior_currency'><?php _e( 'Currency' , 'cart66' ); ?>:</label>
                  <select name="mwarrior_currency" id="mwarrior_currency">
                    <option value="AUD">AUD</option>
                    <option value="NZD">NZD</option>
                  </select>
                </li>
                <li>
                  <label style="display: inline-block; width: 120px; text-align: right;" for='mwarrior_api_passphrase'><?php _e( 'API Passphrase' , 'cart66' ); ?>:</label>
                  <input type='text' name='mwarrior_api_passphrase' id='mwarrior_api_passphrase' style='width: 375px;' value="<?php echo Cart66Setting::getValue('mwarrior_api_passphrase'); ?>" />
                </li>
                <li><label style="display: inline-block; width: 120px; text-align: right;" for='mwarrior_merchant_uuid'><?php _e( 'MerchantUUID' , 'cart66' ); ?>:</label>
                <input type='text' name='mwarrior_merchant_uuid' id='mwarrior_merchant_uuid' style='width: 375px;' value="<?php echo Cart66Setting::getValue('mwarrior_merchant_uuid'); ?>" />
                </li>
                <li><label style="display: inline-block; width: 120px; text-align: right;" for='mwarrior_api_key'><?php _e( 'API key' , 'cart66' ); ?>:</label>
                <input type='text' name='mwarrior_api_key' id='mwarrior_api_key' style='width: 375px;' value="<?php echo Cart66Setting::getValue('mwarrior_api_key'); ?>" />
                </li>
                <li>
                  <label style="display: inline-block; width: 120px; text-align: right;" for='mwarrior_test_mode'><?php _e( 'Test Mode' , 'cart66' ); ?>:</label>
                  <input type="checkbox" name="mwarrior_test_mode" id="mwarrior_test_mode" class="mwarrior_test_mode" value="1" <?php echo Cart66Setting::getValue('mwarrior_test_mode') ? 'checked="checked"' : '' ?> />
                </li>
                <div id="mwarrior_test">
                  <p class="description"><?php _e( 'These are the settings used for test transactions with Merchant Warrior.' , 'cart66' ); ?></p>
                  <li>
                    <label style="display: inline-block; width: 120px; text-align: right;" for='mwarrior_test_api_passphrase'><?php _e( 'API Passphrase' , 'cart66' ); ?>:</label>
                    <input type='text' name='mwarrior_test_api_passphrase' id='mwarrior_test_api_passphrase' style='width: 375px;' value="<?php echo Cart66Setting::getValue('mwarrior_test_api_passphrase'); ?>" />
                  </li>
                  <li><label style="display: inline-block; width: 120px; text-align: right;" for='mwarrior_test_merchant_uuid'><?php _e( 'MerchantUUID' , 'cart66' ); ?>:</label>
                  <input type='text' name='mwarrior_test_merchant_uuid' id='mwarrior_test_merchant_uuid' style='width: 375px;' value="<?php echo Cart66Setting::getValue('mwarrior_test_merchant_uuid'); ?>" />
                  </li>
                  <li><label style="display: inline-block; width: 120px; text-align: right;" for='mwarrior_test_api_key'><?php _e( 'API key' , 'cart66' ); ?>:</label>
                  <input type='text' name='mwarrior_test_api_key' id='mwarrior_test_api_key' style='width: 375px;' value="<?php echo Cart66Setting::getValue('mwarrior_test_api_key'); ?>" />
                  </li>
                </div>
              </div>
              
              <div id="payleap_live">
                <li>
                  <label style="display: inline-block; width: 120px; text-align: right;" for='payleap_api_username'><?php _e( 'API Username' , 'cart66' ); ?>:</label>
                  <input type='text' name='payleap_api_username' id='payleap_api_username' style='width: 375px;' value="<?php echo Cart66Setting::getValue('payleap_api_username'); ?>" />
                </li>
                <li>  
                  <label style="display: inline-block; width: 120px; text-align: right;" for='payleap_transaction_key'><?php _e( 'Transaction Key' , 'cart66' ); ?>:</label>
                  <input type='text' name='payleap_transaction_key' id='payleap_transaction_key' style='width: 375px;' value="<?php echo Cart66Setting::getValue('payleap_transaction_key'); ?>" />
                </li>
                <li>
                  <label style="display: inline-block; width: 120px; text-align: right;" for='payleap_test_mode'><?php _e( 'PayLeap Test Mode' , 'cart66' ); ?>:</label>
                  <input type="checkbox" name="payleap_test_mode" id="payleap_test_mode" value="1" <?php echo Cart66Setting::getValue('payleap_test_mode') ? 'checked="checked"' : '' ?> />
                </li>
                <div id="payleap_test">
                  <p class="description"><?php _e( 'These are the settings used for test transactions with PayLeap.' , 'cart66' ); ?></p>
                  <li>
                    <label style="display: inline-block; width: 120px; text-align: right;" for='payleap_test_api_username'><?php _e( 'API Username' , 'cart66' ); ?>:</label>
                    <input type='text' name='payleap_test_api_username' id='payleap_test_api_username' style='width: 375px;' value="<?php echo Cart66Setting::getValue('payleap_test_api_username'); ?>" /><br />
                  </li>
                  <li>
                    <label style="display: inline-block; width: 120px; text-align: right;" for='payleap_test_transaction_key'><?php _e( 'Transaction Key' , 'cart66' ); ?>:</label>
                    <input type='text' name='payleap_test_transaction_key' id='payleap_test_transaction_key' style='width: 375px;' value="<?php echo Cart66Setting::getValue('payleap_test_transaction_key'); ?>" />
                  </li>
                </div>
              </div>

              <li id="api_login_id"><label style="display: inline-block; width: 120px; text-align: right;" for='auth_username'><?php _e( 'API Login ID' , 'cart66' ); ?>:</label>
              <input type='text' name='auth_username' id='auth_username' style='width: 375px;' value="<?php echo Cart66Setting::getValue('auth_username'); ?>" />
              <p id="authnet-image" class="label_desc"><a href="http://cart66.com/system66/wp-content/uploads/authnet-api-login.jpg" target="_blank"><?php _e( 'Where can I find my Authorize.net API Login ID and Transaction Key?' , 'cart66' ); ?></a></p>
              </li>
                      
              <li id="transaction_key"><label style="display: inline-block; width: 120px; text-align: right;" for='auth_trans_key'><?php _e( 'Transaction key' , 'cart66' ); ?>:</label>
              <input type='text' name='auth_trans_key' id='auth_trans_key' style='width: 375px;' value="<?php echo Cart66Setting::getValue('auth_trans_key'); ?>" />
              </li>

              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" />
              </li>
            </ul>
          </form>
        </div>
        <?php else: ?>
          <div style="padding: 5px 20px;">
          <p><?php _e( 'With <a href="http://cart66.com">Cart66 Professional</a> you can accept credit cards directly on your website using:' , 'cart66' ); ?></p>
          <ul style="padding: 2px 30px; list-style: disc;"> 
            <li>PayPal Website Payments Pro</li>
            <li>Quantum Gateway</li>
            <li>eProcessing Network</li>
            <li>Authorize.net AIM</li>
            <li><?php _e( 'Any other gateway that implements the Authorize.net AIM interface' , 'cart66' ); ?></li>
          </ul>
          </div>
        <?php endif; ?>
      </div>
    </div>
    
    <!-- Receipt Settings -->
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('receipt_from_name') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Email Receipt Settings' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <div>
          <p class="description"><?php _e( 'These are the settings used for sending email receipts to your customers after they place an order.' , 'cart66' ); ?></p>
          <form id="emailReceiptForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="The email receipt settings have been saved.">
            <ul>
              <div class="emailSettings">
                <li><label style="display: inline-block; width: 120px; text-align: right;" for='receipt_from_name'><?php _e( 'From Name' , 'cart66' ); ?>:</label>
                <input type='text' name='receipt_from_name' id='receipt_from_name' style='width: 375px;' 
                value="<?php echo Cart66Setting::getValue('receipt_from_name', true); ?>" />
                <p style="margin-left: 125px;" class="description"><?php _e( 'The name of the person from whom the email receipt will be sent. 
                  You may want this to be your company name.' , 'cart66' ); ?></p></li>

                <li><label style="display: inline-block; width: 120px; text-align: right;" for='receipt_from_address'><?php _e( 'From Address' , 'cart66' ); ?>:</label>
                <input type='text' name='receipt_from_address' id='receipt_from_address' style='width: 375px;' 
                value="<?php echo Cart66Setting::getValue('receipt_from_address'); ?>" />
                <p  style="margin-left: 125px;" class="description"><?php _e( 'The email address the email receipt will be from.' , 'cart66' ); ?></p>
                </li>

                <li><label style="display: inline-block; width: 120px; text-align: right;" for='receipt_subject'><?php _e( 'Receipt Subject' , 'cart66' ); ?>:</label>
                <input type='text' name='receipt_subject' id='receipt_subject' style='width: 375px;' 
                value="<?php echo Cart66Setting::getValue('receipt_subject', true); ?>" />
                <p style="margin-left: 125px;" class="description"><?php _e( 'The subject of the email receipt' , 'cart66' ); ?></p></li>

                <li><label style="display: inline-block; width: 120px; text-align: right; margin-top: 0px;" for='receipt_intro'><?php _e( 'Receipt Intro' , 'cart66' ); ?>:</label>
                <br/><textarea style="width: 375px; height: 140px; margin-left: 125px; margin-top: -20px;" 
                name='receipt_intro'><?php echo Cart66Setting::getValue('receipt_intro'); ?></textarea>
                <p style="margin-left: 125px;" class="description"><?php _e( 'This text will appear at the top of the receipt email message above the list of items purchased.' , 'cart66' ); ?></p></li>

                <li><label style="display: inline-block; width: 120px; text-align: right;" for='receipt_copy'><?php _e( 'Copy Receipt To' , 'cart66' ); ?>:</label>
                <input type='text' name='receipt_copy' id='receipt_copy' style='width: 375px;' value="<?php echo Cart66Setting::getValue('receipt_copy'); ?>" />
                <p style="margin-left: 125px;" class="description"><?php _e( 'Use commas to separate addresses.' , 'cart66' ); ?></p>
                </li>
              </div>

              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            </ul>
          </form>
        </div>
      </div>
    </div>

    
    <!-- Password Reset Email Settings -->
    <?php if(CART66_PRO): ?>
      <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('reset_subject') ? '' : 'closed'; ?>">
        <div class="sidebar-name">
          <div class="sidebar-name-arrow"><br/></div>
          <h3><?php _e( 'Password Reset Email Settings' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
        </div>
        <div class="widget-holder">
          <div>
            <p class="description"><?php _e( 'These are the settings used for sending password reset emails your subscribers who forget their passwords.' , 'cart66' ); ?></p>
            <form id="emailResetForm" class="ajaxSettingForm" action="" method='post'>
              <input type='hidden' name='action' value="save_settings" />
              <input type='hidden' name='_success' value="The password reset email settings have been saved.">
              <ul>

                <li><label style="display: inline-block; width: 120px; text-align: right;" for='reset_from_name'><?php _e( 'From Name' , 'cart66' ); ?>:</label>
                <input type='text' name='reset_from_name' id='reset_from_name' style='width: 375px;' 
                value="<?php echo Cart66Setting::getValue('reset_from_name', true); ?>" />
                <p style="margin-left: 125px;" class="description"><?php _e( 'The name of the person from whom the email will be sent. 
                  You may want this to be your company name.' , 'cart66' ); ?></p></li>

                <li><label style="display: inline-block; width: 120px; text-align: right;" for='reset_from_address'><?php _e( 'From Address' , 'cart66' ); ?>:</label>
                <input type='text' name='reset_from_address' id='reset_from_address' style='width: 375px;' 
                value="<?php echo Cart66Setting::getValue('reset_from_address'); ?>" />
                <p  style="margin-left: 125px;" class="description"><?php _e( 'The email address the email will be from.' , 'cart66' ); ?></p>
                </li>

                <li><label style="display: inline-block; width: 120px; text-align: right;" for='reset_subject'><?php _e( 'Email Subject' , 'cart66' ); ?>:</label>
                <input type='text' name='reset_subject' id='reset_subject' style='width: 375px;' 
                value="<?php echo Cart66Setting::getValue('reset_subject', true); ?>" />
                <p style="margin-left: 125px;" class="description"><?php _e( 'The subject of the email.' , 'cart66' ); ?></p></li>

                <li><label style="display: inline-block; width: 120px; text-align: right; margin-top: 0px;" for='reset_intro'><?php _e( 'Email Intro' , 'cart66' ); ?>:</label>
                <br/><textarea style="width: 375px; height: 140px; margin-left: 125px; margin-top: -20px;" 
                name='reset_intro'><?php echo Cart66Setting::getValue('reset_intro'); ?></textarea>
                <p style="margin-left: 125px;" class="description"><?php _e( 'This text will appear at the top of the reset email message above the new password.' , 'cart66' ); ?></p></li>

                <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
                <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
              </ul>
            </form>
          </div>
        </div>
      </div>
    
      <!-- Blog Post Access Denied Messages -->
      <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('post_not_logged_in') ? '' : 'closed'; ?>">
        <div class="sidebar-name">
          <div class="sidebar-name-arrow"><br/></div>
          <h3><?php _e( 'Blog Post Access Denied Messages' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
        </div>
        <div class="widget-holder">
          <div>
            <p class="description"><?php _e( 'These are the messages your visitors will see when attempting to access a blog post that they do not have permission to view.' , 'cart66' ); ?></p>
            <form id="postAccessSettings" class="ajaxSettingForm" action="" method='post'>
              <input type='hidden' name='action' value="save_settings" />
              <input type='hidden' name='_success' value="The blog post access denied settings have been saved.">
              <ul>

                <li><label style="display: inline-block; width: 120px; text-align: right;" for='reset_from_name'><?php _e( 'Not logged in' , 'cart66' ); ?>:</label><br/>
                <textarea style="width: 375px; height: 140px; margin-left: 125px; margin-top: -20px;" 
                id="post_not_logged_in" name="post_not_logged_in"><?php echo Cart66Setting::getValue('post_not_logged_in'); ?></textarea>
                <p style="margin-left: 125px; padding-bottom: 15px;" class="description"><?php _e( 'The message that appears when a private posted is accessed by a visitor who is not logged in.' , 'cart66' ); ?></p></li>

                <li><label style="display: inline-block; width: 120px; text-align: right;" for='reset_from_name'><?php _e( 'Access denied' , 'cart66' ); ?>:</label><br/>
                <textarea style="width: 375px; height: 140px; margin-left: 125px; margin-top: -20px;" 
                id="post_access_denied" name="post_access_denied"><?php echo Cart66Setting::getValue('post_access_denied'); ?></textarea>
                <p style="margin-left: 125px;" class="description"><?php _e( 'The message that appears when a logged in member\'s subscription does not allow them to view the post.' , 'cart66' ); ?></p></li>

                <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
                <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
              </ul>
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>
    
    
    <!-- Order Status Options -->
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('status_options') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Status Options' , 'cart66' ); ?><span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'Define the order status options to suit your business needs. For example, you may want to have new, complete, and canceled.' , 'cart66' ); ?></p>
        <div>
          <form id="statusOptionForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="The order status option settings have been saved.">
            <ul>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='status_options'><?php _e( 'Order statuses' , 'cart66' ); ?>:</label>
              <input type='text' name='status_options' id='status_options' style='width: 80%;' 
              value="<?php echo Cart66Setting::getValue('status_options'); ?>" />
              <p style="margin-left: 125px;" class="description"><?php _e( 'Separate values with commas. (ex. new,complete,cancelled)' , 'cart66' ); ?></p></li>

              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            </ul>
          </form>
        </div>
      </div>
    </div>

    <!-- Digital Product Settings -->
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('product_folder') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Digital Product Settings' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'Enter the absolute path to where you want to store your digital products. We suggest you choose a folder that is not web accessible. To help you figure out the path to your digital products folder, this is the absolute path to the page you are viewing now.' , 'cart66' ); ?><br/>
          <?php echo realpath('.'); ?><br/>
          <?php _e( 'Please note you should NOT enter a web url starting with http:// Your filesystem path will start with just a /' , 'cart66' ); ?> 
        </p>
        <div>
          <form id="productFolderForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="The product folder setting has been saved.">
            <ul>
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='product_folder'><?php _e( 'Product folder' , 'cart66' ); ?>:</label>
              <input type='text' name='product_folder' id='product_folder' style='width: 80%;' 
              value="<?php echo Cart66Setting::getValue('product_folder'); ?>" />
              <?php
                $dir = Cart66Setting::getValue('product_folder');
                if($dir) {
                  if(!file_exists($dir)) { mkdir($dir, 0700, true); }
                  if(!file_exists($dir)) { echo "<p class='label_desc' style='color: red;'>" . __("<strong>WARNING:</strong> This directory does not exist.","cart66") . "</p>"; }
                  elseif(!is_writable($dir)) { echo "<p class='label_desc' style='color: red;'>" . __("<strong>WARNING:</strong> WordPress cannot write to this folder.","cart66") . "</p>"; }
                }
              ?>
              </li>

              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            </ul>
          </form>
        </div>
      </div>
    </div>
    
    
    <!-- Store Home Page -->
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('store_url') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Store Home Page' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'This is the link to the page of your site that you consider to be the home page of your store.
          You can choose to have customers go back to the last page they were on when they clicked "Add to Cart" or you can force the continue shopping button to always go to the store home page.' , 'cart66' ); ?></p>
        <div>
          <form id="storeHomeForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="The store home page setting has been saved.">            
            <ul>
            
            <li><label style="display: inline-block; width: 120px; text-align: right;" for="continue_shopping"><?php _e( 'Continue Shopping' , 'cart66' ); ?>:</label>
            <select name='continue_shopping' id='continue_shopping'>
                <option value="0"><?php _e( 'Send customer back to the last page' , 'cart66' ); ?></option>
                <option value="1"><?php _e( 'Always go to the store home page' , 'cart66' ); ?></option>
            </select></li>
              
            <li><label style="display: inline-block; width: 120px; text-align: right;" for='store_url'><?php _e( 'Store URL' , 'cart66' ); ?>:</label>
            <input type='text' name='store_url' id='store_url' style='width: 80%;' value="<?php echo Cart66Setting::getValue('store_url'); ?>" />
            </li>

            <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
            <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            
            </ul>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Google Analytics Ecommerce Tracking -->
    <a href="#" name="googleanalytics"></a>
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('enable_google_analytics') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e('Google Analytics Ecommerce Tracking', 'cart66'); ?><span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e('Collect transaction and purchase data for your website using the Google Analytics tracking code.  If you already use another analytics plugin, make sure you specify that here so that your site does not track the receipt page twice.', 'cart66'); ?></p>
        <?php if(CART66_PRO): ?>
          <div>
            <form id="googleAnalyticsOptionsForm" class="ajaxSettingForm" action="" method='post'>
              <input type='hidden' name='action' value='save_settings' />
              <input type='hidden' name='_success' value='Your Google Analytics settings have been saved.'>
              <ul>
                <li>
                  <label style="display: inline-block; width: 120px; text-align: right;" for='enable_google_analytics'><?php _e( 'Enable for my site' , 'cart66' ); ?>:</label>
                    <input type="hidden" name='enable_google_analytics' value="" />  
                    <input type="checkbox" name='enable_google_analytics' id='enable_google_analytics' value="1" <?php echo (Cart66Setting::getValue('enable_google_analytics') == 1) ? 'checked="checked"' : ''; ?> />
                </li>
                <li>
                  <label style="display: inline-block; width: 120px; text-align: right;" for='spreedly_shortname'><?php _e( 'Other plugins' , 'cart66' ); ?>:</label>
                  <select name='use_other_analytics_plugin' id='use_other_analytics_plugin'>
                    <option value="yes"><?php _e('Yes, I want to use Cart66 with other Google Analytics plugins', 'cart66'); ?></option>
                    <option value="no"><?php _e('No, I want to use Cart66 to track on its own', 'cart66'); ?></option>
                  </select>
                </li>
                <li id="google_analytics_product_id">
                  <label style="display: inline-block; width: 120px; text-align: right;" for='spreedly_shortname'><?php _e( 'Web Product ID' , 'cart66' ); ?>:</label>
                  <input type='text' name='google_analytics_wpid' id='google_analytics_wpid' value='<?php echo Cart66Setting::getValue('google_analytics_wpid'); ?>' />
                  <p class="description" style='margin-left: 125px;'><?php _e( 'Starts with UA-XXXXXXXX-X' , 'cart66' ); ?></p>
                </li>
                <li>
                  <label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
                  <input type='submit' name='submit' class="button-primary" style='width: 60px;' value='Save' />
                </li>
              </ul>
            </form>
          </div>
        <?php else: ?>
          <p class="description"><?php _e( 'This feature is only available in <a href="http://cart66.com">Cart66 Professional</a>.' , 'cart66' ); ?></p>
        <?php endif; ?>
      </div>
    </div>
    
    <!-- Spreedly Settings -->
    <a href="#" name="spreedly"></a>
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('spreedly_shortname') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Spreedly Account Information' , 'cart66' ); ?><span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'Configure your Spreedly account information to sell subscriptions.' , 'cart66' ); ?></p>
        <div>
          <form id="spreedlyOptionForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value='save_settings' />
            <input type='hidden' name='_success' value='Your Spreedly settings have been saved.'>
            <ul>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='spreedly_shortname'><?php _e( 'Short site name' , 'cart66' ); ?>:</label>
              <input type='text' name='spreedly_shortname' id='spreedly_shortname' value='<?php echo Cart66Setting::getValue('spreedly_shortname'); ?>' />
              <p class="description" style='margin-left: 125px;'><?php _e( 'Look in your spreedly account under Site Details for the short site name (Used in URLs, etc)' , 'cart66' ); ?></p>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='spreedly_apitoken'><?php _e( 'API token' , 'cart66' ); ?>:</label>
              <input type='text' name='spreedly_apitoken' id='spreedly_apitoken' style="width: 70%;"
              value='<?php echo Cart66Setting::getValue('spreedly_apitoken'); ?>' />
              <p class="description" style='margin-left: 125px;'><?php _e( 'Look in your spreedly account under Site Details for the API Authentication Token.' , 'cart66' ); ?></p>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='auto_logout_link'><?php _e( 'Log out link' , 'cart66' ); ?>:</label>
              <input type='radio' name='auto_logout_link' id='auto_logout_link' value="1" <?php if(Cart66Setting::getValue('auto_logout_link')) { echo 'checked="checked"'; } ?> /> Yes
              <input type='radio' name='auto_logout_link' id='auto_logout_link' value="" <?php if(!Cart66Setting::getValue('auto_logout_link')) { echo 'checked="checked"'; } ?> /> No
              <p class="description" style='margin-left: 125px;'><?php _e( 'Append a logout link to your site\'s navigation.<br/>Note, this only works with themes that build the navigation using the wp_list_pages() function. See the documentation for other log out options when using WordPress 3.0 Menus.' , 'cart66' ); ?></p>
              
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value='Save' /></li>
            </ul>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Amazon S3 Settings -->
    <a href="#" name="amazons3"></a>
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('amazons3_id') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Amazon S3 Settings' , 'cart66' ); ?><span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description" style="font-style: normal; color: #333; width: 600px;"><?php _e( 'Amazon S3 provides a simple web services interface for delivering digital content. It gives you access to the same highly scalable, reliable, secure, fast, inexpensive infrastructure that Amazon uses to run its own global network of web sites. Deliver you Cart66 digital products through your Amazon S3 account to increase security and performance when selling digital products.' , 'cart66' ); ?></p>
        <p class="description"><?php _e( 'Configure your Amazon S3 account information so Cart66 can distribute secure digital downloads from your Amazon S3 account.' , 'cart66' ); ?></p>
        <div>
          <form id="amazons3Form" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="Your Amazon S3 settings have been saved.">
            <ul>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='amazons3_id'><?php _e( 'Access Key ID' , 'cart66' ); ?>:</label>
              <input type='text' name='amazons3_id' id='amazons3_id' style="width: 75%;" value="<?php echo Cart66Setting::getValue('amazons3_id'); ?>" />
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='amazons3_key'><?php _e( 'Secret Key' , 'cart66' ); ?>:</label>
              <input type='text' name='amazons3_key' id='amazons3_key' style="width: 75%;" value="<?php echo Cart66Setting::getValue('amazons3_key'); ?>" />
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            </ul>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Constant Contact Settings -->
    <a href="#" name="constantcontact"></a>
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('constantcontact_username') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Constant Contact Settings' , 'cart66' ); ?><span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'Configure your Constant Contact account information so your buyers can opt in to your newsletter.' , 'cart66' ); ?></p>
        <div>
          <?php if(CART66_PRO): ?>
          <form id="constantcontact" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="Your Constant Contact settings have been saved.">
            <input type='hidden' name='constantcontact_apikey' value="9a2f451c-ccd6-453f-994f-6cc8c5dc1e94">
            <ul>
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='constantcontact_username'><?php _e( 'Username' , 'cart66' ); ?>:</label>
              <input type='text' name='constantcontact_username' id='constantcontact_username' value="<?php echo Cart66Setting::getValue('constantcontact_username'); ?>" />
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='constantcontact_password'><?php _e( 'Password' , 'cart66' ); ?>:</label>
              <input type='text' name='constantcontact_password' id='constantcontact_password' value="<?php echo Cart66Setting::getValue('constantcontact_password'); ?>" />
              
              <li><label style="display: inline-block; width: 120px; text-align: right; margin-top: 0px;" for='constantcontact_opt_in_message'><?php _e( 'Opt-in Message' , 'cart66' ); ?>:</label>
              <br/><textarea style="width: 375px; height: 140px; margin-left: 125px; margin-top: -20px;" 
              name='constantcontact_opt_in_message'><?php echo Cart66Setting::getValue('constantcontact_opt_in_message'); ?></textarea>
              <p style="margin-left: 125px;" class="description"><?php _e( 'Provide a message to tell your buyers what your newsletter is about.<br/>For example, you might want to say something like "Yes! I would like to subscribe to:"' , 'cart66' ); ?></p></li>
                
              <?php
                // Show the constant contact lists
                if(Cart66Setting::getValue('constantcontact_username')) {
                  echo '<li><label style="display: inline-block; width: 120px; text-align: right;">Show lists:</label>';
                  echo '<div style="width: 600px; display: block; margin-left: 125px; margin-top: -1.25em;">';
                  echo '<input type="hidden" name="constantcontact_list_ids" value="" />';
                  $cc = new Cart66ConstantContact();
                  $lists = $cc->get_all_lists('lists', 3);
                  if(is_array($lists)) {
                    $savedListIds = array();
                    if($savedLists = Cart66Setting::getValue('constantcontact_list_ids')) {
                      $savedListIds = explode('~', $savedLists);
                    }
                    
                    foreach($lists as $list) {
                      $checked = '';
                      $val = $list['id'] . '::' . $list['Name'];
                      Cart66Common::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] looking for: $val in " . print_r($savedListIds, true));
                      if(in_array($val, $savedListIds)) {
                        $checked = 'checked="checked"';
                      }
                      echo '<input type="checkbox" name="constantcontact_list_ids[]" value="' . $val . '" ' . $checked . '> ' . $list['Name'] . '<br />';
                    }
                  }
                  else {
                    echo '<p class="description">You do not have any lists</p>';
                  } 
                  echo '</div></li>';
                }
              ?>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            </ul>
          </form>
          <?php else: ?>
            <p class="description" style="font-style: normal; color: #333; width: 600px;"><?php _e( 'Constant Contact is
              an industry leader in email marketing. Constant Contact provides email marketing software that makes it easy to 
              create professional HTML email campaigns with no tech skills.' , 'cart66' ); ?></p>
            <p class="description"><?php _e( 'This feature is only available in <a href="http://cart66.com">Cart66 Professional</a>.' , 'cart66' ); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    
    <!-- MailChimp Settings -->
    <a href="#" name="mailchimp"></a>
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('mailchimp_apikey') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'MailChimp Settings' , 'cart66' ); ?><span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <div class="widget-logo" style="float:right;">
              <a href="http://eepurl.com/dtQBb" target="_blank">
                <img src="https://cart66.com/images/MC_MonkeyReward_06.png" align="left" alt="Powered by MailChimp">
              </a>
        </div>
        <p class="description">
          <?php _e( 'Configure your <a href="http://eepurl.com/dtQBb" target="_blank">Mail Chimp</a> account information so your buyers can opt in to your newsletter.' , 'cart66' ); ?>
        </p>
        <div>
          <?php if(CART66_PRO): ?>
          <form id="mailchimp" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="Your MailChimp settings have been saved.">
            <input type="hidden" name="mailchimp_list_ids" value="" />
            <ul>
              <li>
                <label style="display: inline-block; width: 150px; text-align: right;" for='mailchimp_apikey'><?php _e( 'MailChimp API Key' , 'cart66' ); ?>:</label>
                <input type='text' name='mailchimp_apikey' id='mailchimp_apikey' value="<?php echo Cart66Setting::getValue('mailchimp_apikey'); ?>" size="60" />
                 <p style="margin-left: 155px;" class="description"><?php _e( 'Need an API key? Find out how to get one' , 'cart66' ); ?> <a href="http://kb.mailchimp.com/article/where-can-i-find-my-api-key/" title="Where can I find my API Key?" target="_blank">here.</a></p>
              </li>
              <li><label style="display: inline-block; width: 150px; text-align: right; margin-top: 0px;" for='mailchimp_opt_in_message'><?php _e( 'Opt-in Message' , 'cart66' ); ?>:</label>
              <br/><textarea style="width: 375px; height: 140px; margin-left: 155px; margin-top: -20px;" 
              name='mailchimp_opt_in_message'><?php echo Cart66Setting::getValue('mailchimp_opt_in_message'); ?></textarea>
              <p style="margin-left: 155px;" class="description"><?php _e( 'Provide a message to tell your buyers what your newsletter is about.<br/>For example, you might want to say something like "Yes! I would like to subscribe to:"' , 'cart66' ); ?></p></li>
                
              <li><label style="display: inline-block; width: 150px; text-align: right; margin-top: 0px;" for='mailchimp_doubleoptin'><?php _e( 'Double Opt-In' , 'cart66' ); ?>:</label>
                  
                  <input type='radio' name='mailchimp_doubleoptin' id='mailchimp_doubleoptin' value="true" <?php 
                  $doubleOptin = Cart66Setting::getValue('mailchimp_doubleoptin');
                  echo (empty($doubleOptin) ||  $doubleOptin == true) ? 'checked="checked"' : '' ?> /> <?php _e( 'Send a Double Opt-In email' , 'cart66' ); ?>
                  
                  <input type='radio' name='mailchimp_doubleoptin' id='mailchimp_doubleoptin' value="no-optin" <?php 
                  $doubleOptin = Cart66Setting::getValue('mailchimp_doubleoptin');
                  echo ($doubleOptin=="no-optin") ? 'checked="checked"' : '' ?> /> <?php _e( 'Don\'t send a Double Opt-In email' , 'cart66' ); ?>
                  <p style="margin-left: 155px;" class="description"><?php _e( 'Send a double opt-in confirmation message. <strong>Abusing this may cause your account to be suspended.</strong>' , 'cart66' ); ?> <a href="http://blog.mailchimp.com/opt-in-vs-confirmed-opt-in-vs-double-opt-in/" target="blank"><?php _e( 'Read more about Opt-Ins' , 'cart66' ); ?></a></p>
              </li>
              <li>
                <label style="display: inline-block; width: 150px; text-align: right;" ><?php _e( 'Show Lists' , 'cart66' ); ?>:</label>
                <div style="width: 600px; display: block; margin-left: 155px; margin-top: -1.25em;">
                  <?php
                    $mcLists = false;
                    if($mailChimpKey = Cart66Setting::getValue('mailchimp_apikey')) {
                      $mc = new Cart66MailChimp($mailChimpKey);
                      $mcLists = $mc->getLists();
                    }
                    
                    if(is_array($mcLists)){
                      $mcSavedListIds = array();
                      if($mcSavedLists = Cart66Setting::getValue('mailchimp_list_ids')) {
                        $mcSavedListIds = explode('~', $mcSavedLists);
                      }
                      
                      foreach ($mcLists as $list){
                    		$checked = '';
                        $val = $list['id'] . '::' . $list['name'];
                        Cart66Common::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] looking for: $val in " . print_r($mcSavedListIds, true));
                        if(in_array($val, $mcSavedListIds)) {
                          $checked = 'checked="checked"';
                        }
                        echo '<input type="checkbox" name="mailchimp_list_ids[]" value="' . $val . '" ' . $checked . '> ' . $list['name'] . ' - ' . $list['stats']['member_count'] . ' Members' . '<br />';
                    		
                    	}
                    }
                    else{
                      echo '<p class="description">You do not have any lists<br>' . $mcLists . '</p>';
                    }
                  ?>
                </div>
                <div style="clear:both;">&nbsp;</div>
              </li>
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            </ul>
            
          </form>  
          <?php endif; ?>
        </div>
      </div>
    </div>

    
    <!-- iDevAffiliate Settings -->
    <a href="#" name="idevaffiliate"></a>
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('idevaff_url') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3>iDevAffiliate <?php _e( 'Settings' , 'cart66' ); ?><span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'Configure your iDevAffiliate account information so Cart66 can award commissions to your affiliates.' , 'cart66' ); ?></p>
        <div>
          <?php if(CART66_PRO): ?>
          <form id="iDevAffiliateForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="Your iDevAffiliate settings have been saved.">
            <ul>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='idevaff_url'>URL:</label>
              <input type='text' name='idevaff_url' id='idevaff_url' style="width: 75%;"
              value="<?php echo Cart66Setting::getValue('idevaff_url'); ?>" />
              <p class="description" style='margin-left: 125px;'><?php _e( 'Copy and paste your iDevAffiliate "3rd Party Affiliate Call" URL. It will looks like' , 'cart66' ); ?>:<br/>
                http://www.yoursite.com/idevaffiliate/sale.php?profile=72198&amp;idev_saleamt=XXX&amp;idev_ordernum=XXX<br/>
                <?php _e( 'Be sure to leave the XXX\'s in place and Cart66 will replace the XXX\'s with the appropriate values for each sale.' , 'cart66' ); ?>
                <?php if(Cart66Setting::getValue('idevaff_url')): ?>
                  <br/><br/><em><?php _e( 'Note: To disable iDevAffiliate integration, simply delete this URL and click Save.' , 'cart66' ); ?></em>
                <?php endif; ?>
              </p>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            </ul>
          </form>
          <?php else: ?>
            <p class="description" style="font-style: normal; color: #333; width: 600px;"><a href="http://www.idevdirect.com/14717499.html">iDevAffiliate</a> <?php _e( 'is The Industry Leader in self managed affiliate program software. Started in 1999, iDevAffiliate is the original in self managed affiliate software! iDevAffiliate was hand coded from scratch by the same team that provides their technical support! iDevAffilaite is also the affilate software that runs our' , 'cart66' ); ?> <a href="http://affiliates.reality66.com/idevaffiliate/"><?php _e('Cart66 Affiliate Program', 'cart66'); ?></a>.</p>
            <p class="description"><?php _e( 'This feature is only available in <a href="http://cart66.com">Cart66 Professional</a>.' , 'cart66' ); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <!-- Zendesk Settings -->
    <a href="#" name="zendesk"></a>
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('zendesk_token') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Zendesk Account Information' , 'cart66' ); ?><span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'Configure your Zendesk account information to enable remote authentication.' , 'cart66' ); ?></p>
        <div>
          <?php if(CART66_PRO): ?>
          <form id="zendeskOptionForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="Your Zendesk settings have been saved.">
            <ul>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='zendesk_token'><?php _e( 'Token' , 'cart66' ); ?>:</label>
              <input type='text' name='zendesk_token' id='zendesk_token' style="width: 50%;"
              value="<?php echo Cart66Setting::getValue('zendesk_token'); ?>" />
              <p class="description" style='margin-left: 125px;'><?php _e( 'Look in your Zendesk account under "Settings > Security > Authentication > Single Sign-On" for the Authentication Token.' , 'cart66' ); ?></p>
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='zendesk_prefix'><?php _e( 'Prefix' , 'cart66' ); ?>:</label>
              <input type='text' name='zendesk_prefix' id='zendesk_prefix' style=""
              value="<?php echo Cart66Setting::getValue('zendesk_prefix'); ?>" />
              <p class="description" style='margin-left: 125px;'><?php _e( 'The prefix is the first part of your zendesk account URL.<br/>For example, if your Zendesk URL is http://<strong style="font-size: 14px;">mycompany</strong>.zendesk.com Then your prefix is mycompany.' , 'cart66' ); ?></p>
              
              <!--
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='zendesk_organization'>Organization:</label>
              <input type='text' name='zendesk_organization' id='zendesk_organization' 
              value="<?php echo Cart66Setting::getValue('zendesk_organization'); ?>" />
              <span class="description">Optional</span>
              <p class="description" style='margin-left: 125px;'>If you have a logical grouping of users in your current system which you want to retain in Zendesk, this can be done setting the organization parameter. If you set a value here, but do not supply the <strong>name of an existing organization</strong>, the user will be removed from his current organization (if any). If you do not set the parameter, nothing will happen.</p>
              -->
              
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            </ul>
          </form>
          <?php else: ?>
            <p class="description" style="font-style: normal; color: #333; width: 600px;"><a href="http://www.zendesk.com">Zendesk</a> <?php _e( 'is the industry leader in web-based help desk software with an elegant support ticket system and a self-service customer support platform. Agile, smart, and convenient.' , 'cart66' ); ?></p>
            <p class="description"><?php _e( 'This feature is only available in <a href="http://cart66.com">Cart66 Professional</a>.' , 'cart66' ); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <!-- Terms of Service -->
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('require_terms') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Terms of Service' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'Require customer acceptance of your terms of service.' , 'cart66' ); ?></p>
        <div>
          <?php if(CART66_PRO): ?>
          <form id="cartTermsForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="The terms of service settings have been saved." />
            <ul>
              <li><label style="display: inline-block; width: 150px; text-align: right;" for="require_terms"><?php _e( 'Require Terms' , 'cart66' ); ?>:</label>
              <input type="hidden" name='require_terms' id='require_terms' value="" />  
              <input type="checkbox" name='require_terms' id='require_terms' value="1" <?php echo (Cart66Setting::getValue('require_terms') == 1) ? 'checked="checked"' : ''; ?> />
              </li>
              
              <li><label style="display: inline-block; width: 150px; text-align: right;" for="cart_terms_title"><?php _e( 'Terms Title' , 'cart66' ); ?>:</label>
              <input type='text' name='cart_terms_title' id='cart_terms_title' style='width: 375px;' 
              value="<?php echo Cart66Setting::getValue('cart_terms_title') ? Cart66Setting::getValue('cart_terms_title') : 'Terms of Service'; ?>" /></li>
              
              <li><label style="display: inline-block; width: 150px; text-align: right;" for="cart_terms_text"><?php _e( 'Terms Text' , 'cart66' ); ?>:</label>
              <br/><textarea id="cart_terms_text" style="width: 375px; height: 140px; margin-left: 155px; margin-top: -20px;" 
              name='cart_terms_text'><?php echo Cart66Setting::getValue('cart_terms_text'); ?></textarea></li>
              
              <li><label style="display: inline-block; width: 150px; text-align: right;" for="cart_terms_acceptance_label"><?php _e( 'Acceptance Label' , 'cart66' ); ?>:</label>
              <input type='text' name='cart_terms_acceptance_label' id='cart_terms_acceptance_label' style='width: 375px;' 
              value="<?php echo (Cart66Setting::getValue('cart_terms_acceptance_label')) ? Cart66Setting::getValue('cart_terms_acceptance_label') : __( 'I Agree, proceed to Checkout' ); ?>" /></li>
              
              <li><label style="display: inline-block; width: 150px; text-align: right;" for="cart_terms_replacement_text"><?php _e( 'Replacement Text' , 'cart66' ); ?>:</label>
              <input type='text' name='cart_terms_replacement_text' id='cart_terms_replacement_text' style='width: 375px;' 
              value="<?php echo Cart66Setting::getValue('cart_terms_replacement_text') ? Cart66Setting::getValue('cart_terms_replacement_text') : 'Please accept the terms of service to checkout.'; ?>" />
              <p style="margin-left:155px;" class="description"><?php _e('Enter the text to be displayed instead of the checkout button, prior to the customer accepting the terms of service', 'cart66'); ?>.</p>
             </li>              

              <li><label style="display: inline-block; width: 150px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            
            </ul>
          </form>
          <?php else: ?>
            <p class="description"><?php _e( 'This feature is only available in <a href="http://cart66.com">Cart66 Professional</a>.' , 'cart66' ); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    
    <!-- Customize Cart Images -->
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('cart_images_url') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Customize Cart Images' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'If you would like to use your own shopping cart images (Add To Cart, Checkout, etc), enter the URL to the directory where you will be storing the images. The path should be outside the plugins/cart66 directory so that they are not lost when you upgrade your Cart66 intallation to a new version.' , 'cart66' ); ?></p>
        <p class="description"><?php _e( 'For example you may want to store your custom cart images here' , 'cart66' ); ?>:<br/>
        <?php echo WPCURL ?>/uploads/cart-images/</p>
        <p class="description"><?php _e( 'Be sure that your path ends in a trailing slash like the example above and that you have all of the image names below in your directory' , 'cart66' ); ?>:</p>
        <ul class="description" style='list-style-type: disc; padding: 0px 0px 0px 30px;'>
          <?php
          $dir = new DirectoryIterator(dirname(__FILE__) . '/../images');
          foreach ($dir as $fileinfo) {
              if (substr($fileinfo->getFilename(), -3) == 'png') {
                  echo '<li>' . $fileinfo->getFilename() . '</li>';
              }
          }
          ?>
        </ul>
        <div>
          <form id="cartImageForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="The cart images setting has been saved.">
            <ul>
              
              <li><label style="display: inline-block; width: 150px; text-align: right;" for="cart_images_url"><?php _e( 'URL to image directory' , 'cart66' ); ?>:</label>
              <input type='text' name='cart_images_url' id='cart_images_url' style='width: 375px;' 
              value="<?php echo Cart66Setting::getValue('cart_images_url'); ?>" /></li>

              <li><label style="display: inline-block; width: 150px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            
            </ul>
          </form>
        </div>
      </div>
    </div>
  
    <!-- Customize CSS Styles -->
    <div class="widgets-holder-wrap <?php echo Cart66Setting::getValue('styles_url') ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Customize Styles' , 'cart66' ); ?> <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e( 'If you would like to override the default styles, you may enter the URL to your custom style sheet.' , 'cart66' ); ?></p>
        <div>
          <form id="cssForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="The custom css style setting has been saved.">
            <ul>
              <li><label style="display: inline-block; width: 120px; text-align: right;" for='styles_url'><?php _e( 'URL to CSS' , 'cart66' ); ?>:</label>
              <input type='text' name='styles_url' id='styles_url' style='width: 375px;' value="<?php echo Cart66Setting::getValue('styles_url'); ?>" /></li>

              <li><label style="display: inline-block; width: 120px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e('Save', 'cart66'); ?>" /></li>
            </ul>
          </form>
        </div>
      </div>
    </div>
    
    
    <?php if(current_user_can('manage_options')): ?>
    <!-- Admin Page Roles -->
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
    <div class="widgets-holder-wrap">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3>Admin Roles <span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <p class="description"><?php _e('Set the role required to access the areas of the Cart66 plugin. Note that the ability to edit these settings requires the "manage_options" capability normally assigned to Administrators.', 'cart66'); ?>
        </p>
        <div>
          <form id="pageRolesForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="<?php _e( 'The admin page roles have been saved.', 'cart66' ); ?>">

            <ul>
              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='styles_url'><?php _e( 'Orders' , 'cart66' ); ?>:</label>
                <select name='admin_page_roles[orders]' id='admin_page_roles_orders' style="width: 150px;">
                  <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                  <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                  <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                  <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
                </select>
                <span class="label_desc"><?php _e( '' , 'cart66' ); ?></span>
              </li>
              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='styles_url'><?php _e( 'Products' , 'cart66' ); ?>:</label>
                <select name='admin_page_roles[products]' id='admin_page_roles_products' style="width: 150px;">
                  <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                  <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                  <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                  <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
                </select>
                <span class="label_desc"><?php _e( '' , 'cart66' ); ?></span>
              </li>
              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='styles_url'><?php _e( 'PayPal Subscriptions' , 'cart66' ); ?>:</label>
                <select name='admin_page_roles[paypal-subscriptions]' id='admin_page_roles_paypal-subscriptions' style="width: 150px;">
                  <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                  <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                  <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                  <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
                </select>
                <span class="label_desc"><?php _e( '' , 'cart66' ); ?></span>
              </li>
              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='styles_url'><?php _e( 'Inventory' , 'cart66' ); ?>:</label>
                <select name='admin_page_roles[inventory]' id='admin_page_roles_inventory' style="width: 150px;">
                  <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                  <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                  <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                  <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
                </select>
                <span class="label_desc"><?php _e( '' , 'cart66' ); ?></span>
              </li>
              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='styles_url'><?php _e( 'Promotions' , 'cart66' ); ?>:</label>
                <select name='admin_page_roles[promotions]' id='admin_page_roles_promotions' style="width: 150px;">
                  <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                  <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                  <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                  <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
                </select>
                <span class="label_desc"><?php _e( '' , 'cart66' ); ?></span>
              </li>
              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='styles_url'><?php _e( 'Shipping' , 'cart66' ); ?>:</label>
                <select name='admin_page_roles[shipping]' id='admin_page_roles_shipping' style="width: 150px;">
                  <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                  <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                  <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                  <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
                </select>
                <span class="label_desc"><?php _e( '' , 'cart66' ); ?></span>
              </li>
              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='styles_url'><?php _e( 'Settings' , 'cart66' ); ?>:</label>
                <select name='admin_page_roles[settings]' id='admin_page_roles_settings' style="width: 150px;">
                  <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                  <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                  <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                  <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
                </select>
                <span class="label_desc"><?php _e( '' , 'cart66' ); ?></span>
              </li>
              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='styles_url'><?php _e( 'Notifications' , 'cart66' ); ?>:</label>
                <select name='admin_page_roles[notifications]' id='admin_page_roles_notifications' style="width: 150px;">
                  <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                  <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                  <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                  <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
                </select>
                <span class="label_desc"><?php _e( '' , 'cart66' ); ?></span>
              </li>
              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='styles_url'><?php _e( 'Reports' , 'cart66' ); ?>:</label>
                <select name='admin_page_roles[reports]' id='admin_page_roles_reports' style="width: 150px;">
                  <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                  <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                  <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                  <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
                </select>
                <span class="label_desc"><?php _e( '' , 'cart66' ); ?></span>
              </li>
              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='styles_url'><?php _e( 'Accounts' , 'cart66' ); ?>:</label>
                <select name='admin_page_roles[accounts]' id='admin_page_roles_accounts' style="width: 150px;">
                  <option value="manage_options"><?php _e( 'Administrator' , 'cart66' ); ?></option>
                  <option value="edit_pages"><?php _e( 'Editor' , 'cart66' ); ?></option>
                  <option value="publish_posts"><?php _e( 'Author' , 'cart66' ); ?></option>
                  <option value="edit_posts"><?php _e( 'Contributor' , 'cart66' ); ?></option>               
                </select>
                <span class="label_desc"><?php _e( '' , 'cart66' ); ?></span>
              </li>
              
              <li><label style="display: inline-block; width: 220px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e( 'Save' , 'cart66'); ?>" /></li>
            
            </ul>
          </form>
        </div>
      </div>
    </div>
    <?php endif; ?>
    
    
    <!-- Error Logging -->
    <div class="widgets-holder-wrap <?php echo (Cart66Setting::getValue('enable_logging') || Cart66Setting::getValue('paypal_sandbox')) ? '' : 'closed'; ?>">
      <div class="sidebar-name">
        <div class="sidebar-name-arrow"><br/></div>
        <h3><?php _e( 'Error Logging &amp; Debugging' , 'cart66' ); ?><span><img class="ajax-feedback" alt="" title="" src="images/wpspin_light.gif"/></span></h3>
      </div>
      <div class="widget-holder">
        <div>
          <form id="debuggingForm" class="ajaxSettingForm" action="" method='post'>
            <input type='hidden' name='action' value="save_settings" />
            <input type='hidden' name='_success' value="The logging and debugging settings have been saved.">
            <input type="hidden" name="enable_logging" value="" id="enable_logging" />

            <ul>
              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='enable_logging'><?php _e( 'Enable logging' , 'cart66' ); ?>:</label>
                <input type='checkbox' name='enable_logging' id='enable_logging' value="1"
                  <?php echo Cart66Setting::getValue('enable_logging') ? 'checked="checked"' : '' ?>
                />
                <span class="label_desc"><?php _e( 'Only enable logging when testing your site. The log file will grow quickly.' , 'cart66' ); ?></span>
              </li>

              <li>
                <label style="display: inline-block; width: 220px; text-align: right;" for='disable_caching'><?php _e( 'Disable caching' , 'cart66' ); ?>:</label>
                <select name='disable_caching' id='disable_caching' style="width: 150px;">
                  <option value="0"><?php _e( 'never' , 'cart66' ); ?></option>
                  <option value="1" <?php echo Cart66Setting::getValue('disable_caching') == 1 ? 'selected="selected"' : '' ?>><?php _e( 'on cart pages' , 'cart66' ); ?></option>
                  <option value="2" <?php echo Cart66Setting::getValue('disable_caching') == 2 ? 'selected="selected"' : '' ?>><?php _e( 'on all pages' , 'cart66' ); ?></option>
                </select>
                <span class="label_desc"><?php _e( 'Send HTTP headers to prevent pages from being cached by web browsers.' , 'cart66' ); ?></span>
              </li>
              
              <li style="background-color: #eee; border: 1px solid #933; margin: 10px 50px; padding: 10px;">
                <label style="display: inline-block; width: 220px; text-align: right;" for='uninstall_db'><?php _e( 'Delete database when uninstalling' , 'cart66' ); ?>:</label>
                <input type='checkbox' name='uninstall_db' id='uninstall_db' value="1" <?php echo Cart66Setting::getValue('uninstall_db') ? 'checked="checked"' : '' ?> />
                <p style="padding: 10px;" class="description"><?php _e( '<strong>WARNING:</strong> Cart66 Lite and Cart66 Professional share the same database. If you are upgrading from Cart66 Lite to Professional and want to keep all your settings, <strong>do not delete the database</strong> when uninstalling Cart66 Lite.' , 'cart66' ); ?></p>
              </li>

              <li><label style="display: inline-block; width: 220px; text-align: right;" for='submit'>&nbsp;</label>
              <input type='submit' name='submit' class="button-primary" style='width: 60px;' value="<?php _e( 'Save' , 'cart66'); ?>" /></li>
              
            </ul>
            
          </form>
          
         
          <?php if(Cart66Log::exists()): ?>
            <ul>
              <li>
                <div style="display: block; width:350px; margin-left:124px;">
                <form action="" method="post" style="padding: 10px 100px;">
                  <input type="hidden" name="cart66-action" value="download log file" id="cart66-action" />
                  <input type="submit" value="Download Log File" class="button-secondary" />
                </form>
                </div>
              </li>
            </ul>
          <?php endif; ?>
          
          <ul>
            <li>
             <label style="display: inline-block; width: 220px; text-align: right;float:left;" for='styles_url'><?php _e( 'Debugging Data' , 'cart66' ); ?>:</label>
             <div style="display: block; width:auto; margin-left:230px;">
                  <?php
                    global $wpdb; 
                  ?>
                  Cart66 <?php if(CART66_PRO){ echo " Pro"; } ?> Version: <?php echo Cart66Setting::getValue('version');?><br>
                  WP Version: <?php echo get_bloginfo("version"); ?><br>
                  WPMU: <?php echo Cart66Setting::validateDebugValue((!defined('MULTISITE') || !MULTISITE) ? "False" : "True", "False");  ?><br>
                  PHP Version: <?php echo phpversion(); ?><br>
                  Session Save Path: <?php echo ini_get("session.save_path"); ?><br>
                  MySQL Version: <?php echo $wpdb->db_version();?><br>
                  MySQL Mode: <?php 
                                  $mode = $wpdb->get_row("SELECT @@SESSION.sql_mode as Mode"); 
                                  if(empty($mode->Mode)){
                                      $sqlMode = "Normal";
                                  }
                                  else{
                                      $sqlMode = $mode->Mode;
                                  }
                                  echo Cart66Setting::validateDebugValue($sqlMode,"Normal"); ?><br>
                  Table Prefix: <?php echo $wpdb->prefix; ?><br>
                  Tables: <?php 
                              $required_tables = array($wpdb->prefix."cart66_products",
                              $wpdb->prefix."cart66_downloads",
                              $wpdb->prefix."cart66_promotions",
                              $wpdb->prefix."cart66_shipping_methods",
                              $wpdb->prefix."cart66_shipping_rates",
                              $wpdb->prefix."cart66_shipping_rules",
                              $wpdb->prefix."cart66_tax_rates",
                              $wpdb->prefix."cart66_cart_settings",
                              $wpdb->prefix."cart66_orders",
                              $wpdb->prefix."cart66_order_items",
                              $wpdb->prefix."cart66_inventory",
                              $wpdb->prefix."cart66_accounts",
                              $wpdb->prefix."cart66_account_subscriptions",
                              $wpdb->prefix."cart66_pp_recurring_payments",
                              $wpdb->prefix."cart66_sessions"
                              );
                              $matched_tables = $wpdb->get_results("SHOW TABLES LIKE '".$wpdb->prefix."cart66_%'","ARRAY_N");
                              if(empty($matched_tables)){
                                $tableStatus = "All Tables Are Missing!";
                              }
                              else {
                                foreach($matched_tables as $key=>$table){
                                  $cart_tables[] = $table[0];
                                }

                                $diff = array_diff($required_tables,$cart_tables);
                                if(!empty($diff)){
                                  $tableStatus = "Missing tables: ";
                                  foreach($diff as $key=>$table){
                                    $tableStatus .= "$table  ";
                                  }
                                }
                                else{
                                  $tableStatus = "All Tables Present";
                                }
                              }
                              echo Cart66Setting::validateDebugValue($tableStatus,"All Tables Present");
                          ?><br>
									Current Dir: <?php echo getcwd(); ?><br>
									WP Url: <?php echo get_bloginfo('wpurl'); ?><br>
  								Server Name: <?php echo $_SERVER['SERVER_NAME']; ?><br>
  								Cookie Domain: <?php $cookieDomain = parse_url( strtolower( get_bloginfo('wpurl') ) ); echo $cookieDomain['host']; ?><br>
  								Curl Test: <?php
  								$cart66CurlTest = (isset($_GET['cart66_curl_test'])) ? $_GET['cart66_curl_test'] : false;
  								if($cart66CurlTest == "run"){
  								  $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL,"https://cart66.com/curl-test.php");
                    curl_setopt($ch, CURLOPT_POST, 1); 
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch,CURLOPT_POSTFIELDS,"curl_check=validate");
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    $result = curl_exec($ch);
                    curl_close($ch);
                    echo ($result == "PASS") ? "PASSED" : "FAILED";
  								}
  								else{
  								  echo "<a href='admin.php?page=cart66-settings&cart66_curl_test=run'>Run Test</a>";
  								}
  								?><br>
  								Write Permissions: <?php 
  								  $isWritable = (is_writable(CART66_PATH)) ? "Writable" : "Not Writable";
  								  echo Cart66Setting::validateDebugValue($isWritable,"Writable");
  								?><br>
  								
             </div>
           </li>
          </ul>
          
          
        </div>
      </div>
    </div>
    
    
    
  
  </div>
</div>

<script type="text/javascript">
  (function($){
    
    $(document).ready(function(){
      $(".multiselect").multiselect({sortable: true});

      $('.sidebar-name').click(function() {
       $(this.parentNode).toggleClass("closed");
      });

      $("#continue_shopping").val("<?php echo Cart66Setting::getValue('continue_shopping'); ?>");
      <?php if(CART66_PRO): ?>
        enableAdvancedNotifications();
        $('#enable_advanced_notifications').change(function() {
          enableAdvancedNotifications();
        });
      <?php endif; ?>
      $('#international_sales_yes').click(function() {
       $('#eligible_countries_block').show();
      });

      $('#international_sales_no').click(function() {
       $('#eligible_countries_block').hide();
      });

      if($('#international_sales_no').attr('checked')) {
       $('#eligible_countries_block').hide();
      }
      
      $('#minimum_cart_amount_yes').click(function() {
       $('.min_amount').show();
      });

      $('#minimum_cart_amount_no').click(function() {
       $('.min_amount').hide();
      });

      if($('#minimum_cart_amount_no').attr('checked')) {
       $('.min_amount').hide();
      }

      $('#payleap_test_mode').change(function() {
        payleapDisplay();
      });
      
      $('#eway_sandbox').change(function() {
        ewayDisplay();
      });
      
      $('#stripe_test').change(function() {
        stripeDisplay();
      });
      
      $('#mwarrior_test_mode').change(function() {
        mwarriorDisplay();
      });

      $("#use_other_analytics_plugin").val("<?php echo Cart66Setting::getValue('use_other_analytics_plugin'); ?>");

      $('#use_other_analytics_plugin').change(function() {
         setGoogleAnalytics();
      });
      
      $('#eway_sandbox').change(function() {
        ewayDisplay();
      });

      $('#auth_url').change(function() {
        setGatewayDisplay();
      });

      <?php if($authUrl = Cart66Setting::getValue('auth_url')): ?>
          $('#auth_url').val('<?php echo $authUrl; ?>').attr('selected', true);
      <?php endif; ?>

      payleapDisplay();
      setGatewayDisplay();
      ewayDisplay();
      stripeDisplay();
      mwarriorDisplay();
      setGoogleAnalytics();
    })
   
  })(jQuery);
  
  $jq = jQuery.noConflict();
  
  function setGatewayDisplay() {
    $jq("#api_login_id, #transaction_key").show();
    if($jq('#auth_url').val() == 'other') {
      $jq('#emulation_url_item').css('display', 'inline');
    }
    else {
      $jq('#emulation_url_item').css('display', 'none');
    }
    
    if($jq('#auth_url :selected').text() == 'Authorize.net Test'){
      $jq("#authorizenetTestMessage").show();
    }
    else{
      $jq("#authorizenetTestMessage").hide();
    }
    
    if($jq('#auth_url :selected').text() == 'eWay'){
      $jq("#eway_live").show();
      $jq("#api_login_id, #transaction_key").hide();
    }
    else{
      $jq("#eway_live").hide();
    }
    
    if($jq('#auth_url :selected').text() == 'PayLeap'){
      $jq("#payleap_live").show();
      $jq("#api_login_id, #transaction_key").hide();
    }
    else{
      $jq("#payleap_live").hide();
    }
    
    if($jq('#auth_url :selected').text() == 'Merchant Warrior'){
      $jq("#mwarrior_live").show();
      $jq("#api_login_id, #transaction_key").hide();
    }
    else{
      $jq("#mwarrior_live").hide();
    }
    
    if($jq('#auth_url :selected').text() == 'Stripe'){
      $jq("#stripe_live").show();
      $jq("#api_login_id, #transaction_key").hide();
    }
    else{
      $jq("#stripe_live").hide();
    }
    
    if($jq('#auth_url :selected').text() == 'Authorize.net' || $jq('#auth_url :selected').text() == 'Authorize.net Test') {
      $jq('#authnet-image').css('display', 'block');
    }
    else {
      $jq('#authnet-image').css('display', 'none');
    }
    
  }
  <?php if(CART66_PRO): ?>
  function enableAdvancedNotifications() {
    if($jq('#enable_advanced_notifications').is(':checked')) { 
      $jq('.advNotifications').show();
      $jq('.emailSettings').hide();
    }
    else {
      $jq('.advNotifications').hide();
      $jq('.emailSettings').show();
    }
  }
  <?php endif; ?>
  function setGoogleAnalytics() {
    if($jq('#use_other_analytics_plugin :selected').val() == 'no'){
      $jq("#google_analytics_product_id").show();
    }
    else{
      $jq("#google_analytics_product_id").hide();
    }
  }

  function ewayDisplay() {
    if ($jq('#eway_sandbox').is(':checked')) { 
      $jq("#eway_sandbox_display").show(); 
    } 
    else { 
      $jq("#eway_sandbox_display").hide(); 
    }
  }
  
  function stripeDisplay() {
    if ($jq('#stripe_test').is(':checked')) { 
      $jq("#stripe_test_display").show(); 
    } 
    else { 
      $jq("#stripe_test_display").hide(); 
    }
  }
  
  function payleapDisplay() {
    if ($jq('#payleap_test_mode').is(':checked')) { 
      $jq("#payleap_test").show();
    }
    else {
      $jq("#payleap_test").hide();
    }
  }
  
  function mwarriorDisplay() {
    if ($jq('#mwarrior_test_mode').is(':checked')) { 
      $jq("#mwarrior_test").show();
    }
    else {
      $jq("#mwarrior_test").hide();
    }
  }
  
</script> 
