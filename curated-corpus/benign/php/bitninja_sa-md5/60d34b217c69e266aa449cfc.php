<?php 
global $wpdb;
$dsp_virtual_gifts = $wpdb->prefix . DSP_VIRTUAL_GIFT_TABLE;
if (isset($_REQUEST['add_image'])) {
    if (isset($_FILES['virtual_image']['name'])) {
        @chmod(WP_DSP_ABSPATH . "gifts", 0777);
        if (copy($_FILES['virtual_image']['tmp_name'], ABSPATH . "/wp-content/uploads/dsp_media/gifts/" . $_FILES['virtual_image']['name'])) {
            $wpdb->insert($dsp_virtual_gifts, array('image' => $_FILES['virtual_image']['name']));
            echo '<script>location.href="admin.php?page=dsp-admin-sub-page3&pid=virtual_flirts"</script>';
        }
    } else {
        echo 'Please select image to upload.';
    }
}
if (isset($_REQUEST['delete_id'])) {
    $get_image = $wpdb->get_var("select image from $dsp_virtual_gifts where id=" . $_REQUEST['delete_id']);
    $wpdb->delete($dsp_virtual_gifts, array('id' => $_REQUEST['delete_id']));
    @unlink(ABSPATH . "/wp-content/uploads/dsp_media/gifts/" . $get_image);
    echo '<script>location.href="admin.php?page=dsp-admin-sub-page3&pid=virtual_flirts"</script>';
}
?>
<div style="float:left; width:100%;" id="general" class="postbox">
    <h3 class="hndle"><span><?php echo __('Virtual Gifts', 'wpdating'); ?></span></h3>
    <?php
    $virtual_gifts = $wpdb->get_results("select * from $dsp_virtual_gifts");
    foreach ($virtual_gifts as $gift_row) {
        $giftName = $gift_row->image;
        $src = WP_DSP_ABSPATH . '/images/gifts/' . $giftName;
        $imagePath = ABSPATH . "/wp-content/uploads/dsp_media/gifts/" . $giftName;
        if(!file_exists($imagePath) && file_exists($src)){
            $locations = array(
                                'src' =>  WP_DSP_ABSPATH . '/images/gifts/' ,
                                'dest'=> ABSPATH . '/wp-content/uploads/dsp_media/gifts/'
                        );
            do_action('dsp_copy_images',$giftName,$locations);
        }
        ?><div class="gift-box">
            <img src='<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $gift_row->image; ?>' alt="<?php echo $gift_row->image;?>">

            <span ><a href="admin.php?page=dsp-admin-sub-page3&pid=virtual_flirts&delete_id=<?php echo $gift_row->id; ?>">Delete</a></span>
        </div>
    <?php } ?>
    <div style="clear:both"></div>
    <div class="add-gift">
        <form method="post" action="" enctype="multipart/form-data">
            <input type="file" name="virtual_image"  />
            <br />
            <input type="submit" name="add_image" value="<?php echo __('Add', 'wpdating'); ?>" />
        </form>
    </div>
</div>