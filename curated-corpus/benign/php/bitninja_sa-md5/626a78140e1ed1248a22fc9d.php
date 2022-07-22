<?php
/**
* Codychat
*
* @package Codychat
* @author www.boomcoding.com
* @copyright 2020
* @terms any use of this script without a legal license is prohibited
* all the content of Codychat is the propriety of BoomCoding and Cannot be 
* used for another project.
*/
require_once('../config_session.php');
?>
<?php if(canRoom()){ ?>
<div class="vpad15 hpad10">
	<button  class="thin_button rounded_button theme_btn" onclick="openAddRoom();"><i class="fa fa-plus"></i> <?php echo $lang['add_room']; ?></button>
</div>
<?php } ?>
<div id="container_room">
	<?php echo getRoomList('list'); ?>
</div>