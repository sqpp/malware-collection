<?php include("includes/header.php");
         include("includes/connection.php");

  require("includes/function.php");
  require("language/language.php");

  //'filters' => array(array('Area' => '=', 'value' => 'ALL')),

  $video_qry="SELECT * FROM tbl_video ORDER BY id DESC";
  $video_result=mysqli_query($mysqli,$video_qry); 

    // echo ONESIGNAL_APP_ID;
    // echo $_POST['submit'];
    // echo ONESIGNAL_REST_KEY;
  if(isset($_POST['submit']))
  {
    if((strcasecmp(DEMO, 'no') == 0)) {
     if($_POST['external_link']!="")
     {
        $external_link = $_POST['external_link'];
     }
     else
     {
        $external_link = false;
     } 

       

    if($_FILES['big_picture']['name']!="")
    {   

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
                        'data' => array("foo" => "bar","video_id"=>$_POST['video_id'],"external_link"=>$external_link),
                        'headings'=> array("en" => $_POST['notification_title']),
                        'contents' => $content,
                        'big_picture' =>$file_path,
                        'ios_attachments' => array(
                             'id' => $file_path,
                        ),                  
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
        
        //Rajan Save
        $data = array( 
    			    'title'  =>  $_POST['notification_title'],
    			    'msg'  =>  $_POST['notification_msg'],
    			    'image'  =>  $big_picture,
    			    'videoid'  =>  $_POST['video_id'],
                    'url'  =>  $external_link
                    );		
    
 		$qry = Insert('notification',$data);

        
    }
    else
    {

 
        $content = array(
                         "en" => $_POST['notification_msg']
                          );

        $fields = array(
                        'app_id' => ONESIGNAL_APP_ID,
                        'included_segments' => array('All'),                                      
                        'data' => array("foo" => "bar","video_id"=>$_POST['video_id'],"external_link"=>$external_link),
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
        
        //Rajan Save
        $data = array( 
    			    'title'  =>  $_POST['notification_title'],
    			    'msg'  =>  $_POST['notification_msg'],
    			    'videoid'  =>  $_POST['video_id'],
                    'url'  =>  $external_link
                    );		
    
 		$qry = Insert('notification',$data);


    }
        
        $_SESSION['msg']="16";
     
        header( "Location:send_notification.php");
        exit; 
        
    }
     
     
  }
  
   

?>
<div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
                                <h4 class="card-title">Send Notification</h4>
                            </div>
          <div class="clearfix"></div>
          <div class="row mrg-top">
            <div class="col-md-12">
               
              <div class="col-md-12 col-sm-12">
                <?php if(isset($_SESSION['msg'])){?> 
                 <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <?php echo $client_lang[$_SESSION['msg']] ; ?></a> </div>
                <?php unset($_SESSION['msg']);}?> 
              </div>
            </div>
          </div>
          <div class="card-body mrg_bottom"> 
            <form action="" name="addeditcategory" method="post" class="form form-horizontal" enctype="multipart/form-data">
               
              <div class="section">
                <div class="section-body">

                  <div class="form-group">
                    <label class="col-md-3 control-label">Title :-</label>
                    <div class="col-md-6">
                      <input type="text" name="notification_title" id="notification_title" class="form-control" value="" placeholder="" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Message :-</label>
                    <div class="col-md-6">
                        <textarea name="notification_msg" id="notification_msg" class="form-control" required></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                       <label class="col-md-3 control-label">Image :-<br/>(Optional)<p class="control-label-help">(Recommended resolution: 750x366)</p></label>
                       <br>
                       <div class="col-md-6">
                           <div class="form-group is-empty is-fileinput">
                                <input type="file" name="big_picture" id="fileupload" value="" multiple="">
                                <div class="input-group">
                                  <input type="text" readonly="" class="form-control" placeholder="Placeholder w/file chooser...">
                                    <span class="input-group-btn input-group-sm">
                                      <button type="button" class="btn btn-fab btn-fab-mini">
                                        <i class="material-icons">attach_file</i>
                                      </button>
                                    </span>
                                </div>
                            </div>
                   </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <label class="col-md-4 control-label">Video :-<br/>(Optional)
                    <p class="control-label-help">To directly open single video when click on notification</p></label>
                    <div class="col-md-3">
                      <select name="video_id" id="video_id" class="form-group" required>
                        <option value="0">--Select Video--</option>
                        <?php
                            while($video_row=mysqli_fetch_array($video_result))
                            {
                        ?>                       
                        <option value="<?php echo $video_row['id'];?>"><?php echo $video_row['video_title'];?></option>                           
                        <?php
                          }
                        ?>
                      </select>
                    </div>
                  </div> 
                  <br>
                  <div class="form-group">
                    <label class="col-md-4 control-label">External Link :-<br/>(Optional)</label>
                    <div class="col-md-8">
                      <input type="text" name="external_link" id="external_link" class="form-control" value="" placeholder="http://www.google.com">
                    </div>
                  </div>
                  <br/>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                        <?php if (strcasecmp(DEMO, 'yes') == 0) {
                              echo '<button type="submit" name="submit" class="btn btn-primary btn-round" disabled>Send</button>';
                              echo '<p>---This option is disabled in demo---</p>';
                          } else {
                              echo '<button type="submit" name="submit" class="btn btn-primary btn-round">Send</button>';
                          }
                        ?>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
        
<?php include("includes/footer.php");?>       
