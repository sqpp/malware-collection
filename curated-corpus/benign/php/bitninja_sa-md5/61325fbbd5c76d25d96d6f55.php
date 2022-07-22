<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  $Qgroups = $osC_Database->query('select distinct banners_group from :table_banners order by banners_group');
  $Qgroups->bindTable(':table_banners', TABLE_BANNERS);
  $Qgroups->execute();

  $groups_array = array();

  while ( $Qgroups->next() ) {
    $groups_array[] = array('id' => $Qgroups->value('banners_group'),
                            'text' => $Qgroups->value('banners_group'));
  }

  $osC_ObjectInfo = new osC_ObjectInfo(osC_BannerManager_Admin::getData($_GET['bID']));
?>

<h1><?php echo osc_link_object(osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule()), $osC_Template->getPageTitle()); ?></h1>

<?php
  if ( $osC_MessageStack->size($osC_Template->getModule()) > 0 ) {
    echo $osC_MessageStack->get($osC_Template->getModule());
  }
?>

<div class="infoBoxHeading"><?php echo osc_icon('edit.png') . ' ' . $osC_ObjectInfo->get('banners_title'); ?></div>
<div class="infoBoxContent">
  <form name="bEdit" action="<?php echo osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '&page=' . $_GET['page'] . '&bID=' . $osC_ObjectInfo->get('banners_id') . '&action=save'); ?>" method="post" enctype="multipart/form-data">

  <p><?php echo $osC_Language->get('introduction_edit_banner'); ?></p>

  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td width="40%"><?php echo '<b>' . $osC_Language->get('field_title') . '</b>'; ?></td>
      <td width="60%"><?php echo osc_draw_input_field('title', $osC_ObjectInfo->get('banners_title'), 'style="width: 100%;"'); ?></td>
    </tr>
    <tr>
      <td width="40%"><?php echo '<b>' . $osC_Language->get('field_url') . '</b>'; ?></td>
      <td width="60%"><?php echo osc_draw_input_field('url', $osC_ObjectInfo->get('banners_url'), 'style="width: 100%;"'); ?></td>
    </tr>
    <tr>
      <td width="40%"><?php echo '<b>' . $osC_Language->get('field_group') . '</b>'; ?></td>
      <td width="60%"><?php echo osc_draw_pull_down_menu('group', $groups_array, $osC_ObjectInfo->get('banners_group')) . $osC_Language->get('field_group_new') . '<br />' . osc_draw_input_field('group_new', null, 'style="width: 100%;"'); ?></td>
    </tr>
    <tr>
      <td width="40%"><?php echo '<b>' . $osC_Language->get('field_image') . '</b>'; ?></td>
      <td width="60%"><?php echo osc_draw_file_field('image', true) . ' ' . $osC_Language->get('field_image_local') . '<br />' . realpath('../images/') . '/' . osc_draw_input_field('image_local', $osC_ObjectInfo->get('banners_image')); ?></td>
    </tr>
    <tr>
      <td width="40%"><?php echo '<b>' . $osC_Language->get('field_image_target') . '</b>'; ?></td>
      <td width="60%"><?php echo realpath('../images') . '/' . osc_draw_input_field('image_target'); ?></td>
    </tr>
    <tr>
      <td width="40%"><?php echo '<b>' . $osC_Language->get('field_html_text') . '</b>'; ?></td>
      <td width="60%"><?php echo osc_draw_textarea_field('html_text', $osC_ObjectInfo->get('banners_html_text')); ?></td>
    </tr>
    <tr>
      <td width="40%"><?php echo '<b>' . $osC_Language->get('field_scheduled_date') . '</b>'; ?></td>
      <td width="60%"><?php echo osc_draw_input_field('date_scheduled', $osC_ObjectInfo->get('date_scheduled')); ?></td>
    </tr>
    <tr>
      <td width="40%"><?php echo '<b>' . $osC_Language->get('field_expiry_date') . '</b>'; ?></td>
      <td width="60%"><?php echo osc_draw_input_field('date_expires', $osC_ObjectInfo->get('expires_date')); ?></td>
    </tr>
    <tr>
      <td width="40%"><?php echo '<b>' . $osC_Language->get('field_maximum_impressions') . '</b>'; ?></td>
      <td width="60%"><?php echo osc_draw_input_field('expires_impressions', null, 'maxlength="7" size="7"'); ?></td>
    </tr>
    <tr>
      <td width="40%"><?php echo '<b>' . $osC_Language->get('field_status') . '</b>'; ?></td>
      <td width="60%"><?php echo osc_draw_checkbox_field('status', null, (($osC_ObjectInfo->get('status') == '1') ? true : false)); ?></td>
    </tr>
  </table>

  <p align="center"><?php echo osc_draw_hidden_field('subaction', 'confirm') . '<input type="submit" value="' . $osC_Language->get('button_save') . '" class="operationButton" /> <input type="button" value="' . $osC_Language->get('button_cancel') . '" onclick="document.location.href=\'' . osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '&page=' . $_GET['page']) . '\';" class="operationButton" />'; ?></p>

  </form>
</div>

<script type="text/javascript"><!--
  $(function() {
    $("#date_scheduled").datepicker( {
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true
    } );

    $("#date_expires").datepicker( {
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true
    } );
  });
//--></script>

<p><?php echo $osC_Language->get('info_banner_fields'); ?></p>
