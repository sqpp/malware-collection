<?php include('session.php'); ?>

<?php 
  include("public/menubar.php");
  require("public/fcm.php");
?>
<link href="assets/css/bootstrap-select.css" rel="stylesheet">
<style>
div.ex1 {
    margin-bottom: 8px;
}
</style>

<?php
  $setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
  $setting_result = mysqli_query($connect, $setting_qry);
  $settings_row   = mysqli_fetch_assoc($setting_result);

  $onesignal_app_id = $settings_row['onesignal_app_id']; 
  $onesignal_rest_api_key = $settings_row['onesignal_rest_api_key']; 

  define("ONESIGNAL_APP_ID", $onesignal_app_id);
  define("ONESIGNAL_REST_KEY", $onesignal_rest_api_key);

  //'filters' => array(array('Area' => '=', 'value' => 'ALL')),

  function get_cat_name($cat_id) {
    //global $mysqli;
    $cat_qry = "SELECT * FROM tbl_news WHERE nid = '".$cat_id."'";
    $cat_result = mysqli_query($connect, $cat_qry); 
    $cat_row = mysqli_fetch_assoc($cat_result); 
     
    return $cat_row['news_title'];

  }

 
  $cat_qry="SELECT * FROM tbl_news ORDER BY news_title";
  $cat_result = mysqli_query($connect, $cat_qry); 
 

  if (isset($_POST['submit'])) {

     if ($_POST['external_link']!="") {
        $external_link = $_POST['external_link'];
     } else {
        $external_link = false;
     } 

     if ($_POST['cat_id'] != 0) {
        $cat_name = get_cat_name($_POST['cat_id']);
     } else {
        $cat_name='';
     }    

    if ($_FILES['big_picture']['name'] != "") {

        $big_picture=rand(0,99999)."_".$_FILES['big_picture']['name'];
        $tpath2='images/'.$big_picture;
        move_uploaded_file($_FILES["big_picture"]["tmp_name"], $tpath2);

        $file_path = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/images/'.$big_picture;
          
        $content = array(
                         "en" => $_POST['notification_msg']                                                 
                         );

        $fields = array(
                        'app_id' => ONESIGNAL_APP_ID,
                        'included_segments' => array('All'),                                            
                        'data' => array("foo" => "bar","cat_id"=>$_POST['cat_id'],"cat_name"=>$cat_name,"external_link"=>$external_link),
                        'headings'=> array("en" => $_POST['notification_title']),
                        'contents' => $content,
                        'big_picture' => $file_path                    
                        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        
    } else {

        $content = array(
                         "en" => $_POST['notification_msg']
                          );

        $fields = array(
                        'app_id' => ONESIGNAL_APP_ID,
                        'included_segments' => array('All'),                                      
                        'data' => array("foo" => "bar","cat_id"=>$_POST['cat_id'],"cat_name"=>$cat_name,"external_link"=>$external_link),
                        'headings'=> array("en" => $_POST['notification_title']),
                        'contents' => $content
                        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        
        curl_close($ch);

    }
        
        $_SESSION['msg'] = "16";
     
        header("Location:send-notification.php");
        exit; 

  }
  
?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">Push Notification</a></li>
        </ol>


       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  
                    <div class="card">
                        <div class="header">
                            <h2>SEND NOTIFICATION</h2>
                              <?php if(isset($_SESSION['msg'])){?>
                              <?php unset($_SESSION['msg']);}?> 
                        </div>
                        <div class="body">

                          <form id="form_validation" action="" name="addeditcategory" method="post" enctype="multipart/form-data">
                          <div class="row clearfix">
                                
                                <div>
                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Title *</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="notification_title" id="notification_title" placeholder="Title" required>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Message *</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="notification_msg" id="notification_msg" placeholder="Message" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="font-12">Big Image (Optional)</div>
                                        <div class="form-group">
                                                <input type="file" name="big_picture" id="big_picture" class="dropify-image" data-max-file-size="2M" data-allowed-file-extensions="jpg jpeg png gif" />
                                                <div class="font-12">(Recommended resolution: 600x293 or 650x317 or 700x342 or 750x366)</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">News (Optional)</div>
                                          <select name="cat_id" id="cat_id" class="form-control show-tick" required>
                                            <option value="0">Select News</option>
                                            <?php
                                                while ($cat_row = mysqli_fetch_array($cat_result)) {
                                            ?>                       
                                            <option value="<?php echo $cat_row['nid'];?>"><?php echo $cat_row['news_title'];?></option>                           
                                            <?php
                                              }
                                            ?>
                                          </select>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">External Link (Optional)</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="external_link" id="external_link" placeholder="http://www.google.com">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                         <button class="btn bg-blue waves-effect pull-right" type="submit" name="submit">SEND PUSH NOTIFICATION</button>
                                    </div>
                                    
                                </div>

                            </div>
                            </form>

                        </div>

                    </div>

                </div>
            </div>
            
        </div>

    </section>
        
<?php include("public/footer.php");?>       
