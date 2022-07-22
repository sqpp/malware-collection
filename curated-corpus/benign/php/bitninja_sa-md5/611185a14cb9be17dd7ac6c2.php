<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  $osC_DirectoryListing = new osC_DirectoryListing('includes/modules/statistics');
  $osC_DirectoryListing->setIncludeDirectories(false);
  $osC_DirectoryListing->setCheckExtension('php');
?>

<h1><?php echo osc_link_object(osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule()), $osC_Template->getPageTitle()); ?></h1>

<?php
  if ( $osC_MessageStack->size($osC_Template->getModule()) > 0 ) {
    echo $osC_MessageStack->get($osC_Template->getModule());
  }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="2" class="dataTable">
  <thead>
    <tr>
      <th><?php echo $osC_Language->get('table_heading_modules'); ?></th>
      <th width="150"><?php echo $osC_Language->get('table_heading_action'); ?></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th colspan="2">&nbsp;</th>
    </tr>
  </tfoot>
  <tbody>

<?php
  $installed_modules = array();

  foreach ( $osC_DirectoryListing->getFiles() as $file ) {
    include('includes/modules/statistics/' . $file['name']);

    $class = 'osC_Statistics_' . str_replace(' ', '_', ucwords(str_replace('_', ' ', substr($file['name'], 0, strrpos($file['name'], '.')))));

    if ( class_exists($class) ) {
      $module = new $class();
?>

    <tr onmouseover="rowOverEffect(this);" onmouseout="rowOutEffect(this);">
      <td><?php echo osc_link_object(osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '&module=' . substr($file['name'], 0, strrpos($file['name'], '.'))), $module->getIcon() . '&nbsp;' . $module->getTitle()); ?></td>
      <td align="right">

<?php
      echo osc_link_object(osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '&module=' . substr($file['name'], 0, strrpos($file['name'], '.'))), osc_icon('run.png'));
?>

      </td>
    </tr>

<?php
    }
  }
?>

  </tbody>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td style="opacity: 0.5; filter: alpha(opacity=50);"><?php echo '<b>' . $osC_Language->get('table_action_legend') . '</b> ' . osc_icon('run.png') . '&nbsp;' . $osC_Language->get('icon_run'); ?></td>
  </tr>
</table>

<p><?php echo $osC_Language->get('modules_location') . ' ' . realpath(dirname(__FILE__) . '/../../../includes/modules/statistics'); ?></p>
