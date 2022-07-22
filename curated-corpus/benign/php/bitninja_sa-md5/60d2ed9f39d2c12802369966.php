<?php include("includes/header.php");

  require("includes/function.php");
  require("language/language.php");
   
  
  $qry="SELECT * FROM tbl_settings where id='1'";
  $result=mysqli_query($mysqli,$qry);
  $settings_row=mysqli_fetch_assoc($result);

 

  if(isset($_POST['submit']))
  {


    $img_res=mysqli_query($mysqli,"SELECT * FROM tbl_settings WHERE id='1'");
    $img_row=mysqli_fetch_assoc($img_res);
    

    if($_FILES['app_logo']['name']!="")
    {        

            unlink('images/'.$img_row['app_logo']);   

            $app_logo=$_FILES['app_logo']['name'];
            $pic1=$_FILES['app_logo']['tmp_name'];

            $tpath1='images/'.$app_logo;      
            copy($pic1,$tpath1);


              $data = array(      
              'email_from'  =>  $_POST['email_from'],       
              'app_name'  =>  $_POST['app_name'],
              'app_logo'  =>  $app_logo,  
              'app_description'  => addslashes($_POST['app_description']),
              'app_version'  =>  $_POST['app_version'],
              'app_author'  =>  $_POST['app_author'],
              'app_contact'  =>  $_POST['app_contact'],
              'app_email'  =>  $_POST['app_email'],   
              'app_website'  =>  $_POST['app_website'],
              'app_developed_by'  =>  $_POST['app_developed_by']                     

              );

    }
    else
    {
  
                $data = array(
                'email_from'  =>  $_POST['email_from'],     
                'app_name'  =>  $_POST['app_name'],
                'app_description'  => addslashes($_POST['app_description']),
                'app_version'  =>  $_POST['app_version'],
                'app_author'  =>  $_POST['app_author'],
                'app_contact'  =>  $_POST['app_contact'],
                'app_email'  =>  $_POST['app_email'],   
                'app_website'  =>  $_POST['app_website'],
                'app_developed_by'  =>  $_POST['app_developed_by']               

                  );

    } 

     $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
 
  

        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
  
 
  }

  if(isset($_POST['admob_submit']))
  {

        $data = array(
                'publisher_id'  =>  $_POST['publisher_id'],
                'interstital_ad'  =>  $_POST['interstital_ad'],
                'interstital_ad_id'  =>  $_POST['interstital_ad_id'],
                'interstital_ad_click'  =>  $_POST['interstital_ad_click'],
                'banner_ad'  =>  $_POST['banner_ad'],
                'banner_ad_id'  =>  $_POST['banner_ad_id'],
                'publisher_id_ios'  =>  $_POST['publisher_id_ios'],
                'app_id_ios'  =>  $_POST['app_id_ios'],
                'interstital_ad_ios'  =>  $_POST['interstital_ad_ios'],
                'interstital_ad_id_ios'  =>  $_POST['interstital_ad_id_ios'],
                'interstital_ad_click_ios'  =>  $_POST['interstital_ad_click_ios'],
                'banner_ad_ios'  =>  $_POST['banner_ad_ios'],
                'banner_ad_id_ios'  =>  $_POST['banner_ad_id_ios']
                  );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
   
        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
  
  }
  if(isset($_POST['notification_submit']))
  {

        $data = array(
                'onesignal_app_id' => $_POST['onesignal_app_id'],
                'onesignal_rest_key' => $_POST['onesignal_rest_key'],
                  );

    
     $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
  
 
        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
 
  }

  if(isset($_POST['api_submit']))
  {

        $data = array(
                'api_latest_limit'  =>  $_POST['api_latest_limit'],
                'api_cat_order_by'  =>  $_POST['api_cat_order_by'],
                'api_cat_post_order_by'  =>  $_POST['api_cat_post_order_by'],
                'api_all_order_by'  =>  $_POST['api_all_order_by']
                  );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
 
 
        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
   
 
  }

  if(isset($_POST['app_pri_poly']))
  {

        $data = array(
                'app_privacy_policy'  =>  $_POST['app_privacy_policy'] 
                  );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
 
 
        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
 
  }


?>
 
   <div class="row">
      <div class="col-md-12">
        <div class="card">
            
            <div class="card-header">
                                <h4 class="card-title">Settings</h4>
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
          <hr>
          <div class="card-body mrg_bottom">
            <!-- Nav tabs -->
           

               <div class="alert">
            <ul class="nav nav-tabs" role="tablist">
                
                <a href="#app_settings" aria-controls="app_settings" role="tab" data-toggle="tab"><li class="btn btn-primary"role="presentation" class="active">App Settings</li></a>
                
                <a href="#admob_settings" aria-controls="admob_settings" role="tab" data-toggle="tab"><li class="btn btn-primary" role="presentation">Ad Settings</li></a>

                <a href="#notification_settings" aria-controls="notification_settings" role="tab" data-toggle="tab"><li class="btn btn-primary" role="presentation">Notification Settings</li></a>

                <!--<li role="presentation"><a href="#api_settings" aria-controls="api_settings" role="tab" data-toggle="tab">API Settings</a></li>-->
                       
                    <a href="#api_privacy_policy" aria-controls="api_privacy_policy" role="tab" data-toggle="tab">
                        <li class="btn btn-primary" role="presentation">App Privacy Policy</li></a>
                
            </ul></div>
       
           <div class="tab-content">
              
              <div role="tabpanel" class="tab-pane active" id="app_settings">   
                <form action="" name="settings_from" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Host Email :-
                      <p class="control-label-help">(Note: This email required otherwise forgot password email feature will not be work. e.g.info@example.com)</p>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="email_from" id="email_from" value="<?php echo $settings_row['email_from'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">App Name :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_name" id="app_name" value="<?php echo $settings_row['app_name'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">App Logo :-</label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="app_logo" id="fileupload">
                         
                          <?php if($settings_row['app_logo']!="") {?>
                            <div class="fileupload_img"><img type="image" src="images/<?php echo $settings_row['app_logo'];?>" alt="image" /></div>
                          <?php } else {?>
                            <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="image" /></div>
                          <?php }?>
                        
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">App Description :-</label>
                    <div class="col-md-6">
                 
                      <textarea name="app_description" id="app_description" class="form-control"><?php echo $settings_row['app_description'];?></textarea>

                      <script>CKEDITOR.replace( 'app_description' );</script>
                    </div>
                  </div>
                  <div class="form-group">&nbsp;</div>                 


                  <div class="form-group">
                    <label class="col-md-3 control-label">App Version :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_version" id="app_version" value="<?php echo $settings_row['app_version'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Author :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_author" id="app_author" value="<?php echo $settings_row['app_author'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Contact :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_contact" id="app_contact" value="<?php echo $settings_row['app_contact'];?>" class="form-control">
                    </div>
                  </div>     
                  <div class="form-group">
                    <label class="col-md-3 control-label">Email :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_email" id="app_email" value="<?php echo $settings_row['app_email'];?>" class="form-control">
                    </div>
                  </div>                 
                   <div class="form-group">
                    <label class="col-md-3 control-label">Website :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_website" id="app_website" value="<?php echo $settings_row['app_website'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Developed By :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_developed_by" id="app_developed_by" value="<?php echo $settings_row['app_developed_by'];?>" class="form-control">
                    </div>
                  </div> 
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary btn-round">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div>
              <div role="tabpanel" class="tab-pane" id="admob_settings">   
                <form action="" name="admob_settings" method="post" class="form form-horizontal" enctype="multipart/form-data">
                <div class="section">
          <div class="section-body">            
            <div class="row">
            <div class="form-group">
              <div class="col-md-26" style="width:100%;">                
              <div class="col-md-6">
                <div class="admob_title">Android</div>
                <div class="form-group">
                  <label class="control-label">Publisher ID :-</label>
                  <div class="">
                    <input type="text" name="publisher_id" id="publisher_id" value="<?php echo $settings_row['publisher_id'];?>" class="form-control">
                  </div>
                </div>
                <div class="">
                  <div class="">
                    <div class="form-group">
                      <label class="control-label">Banner Ad:-</label>
                      <div class="col-md-9">
                       <select name="banner_ad" id="banner_ad" class="select2">
                                <option value="true" <?php if($settings_row['banner_ad']=='true'){?>selected<?php }?>>True</option>
                                <option value="false" <?php if($settings_row['banner_ad']=='false'){?>selected<?php }?>>False</option>
                    
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Banner ID :-</label>
                      <div class="">
                      <input type="text" name="banner_ad_id" id="banner_ad_id" value="<?php echo $settings_row['banner_ad_id'];?>" class="form-control">
                      </div>
                    </div>                    
                  </div>
                </div>  
              </div>
              <div class="">
                <div class="">
                  <div class="col-md-12"> 
                    <div class="form-group">
                      <label class="control-label">Interstital :-</label>
                      <div class="col-md-9">
                        <select name="interstital_ad" id="interstital_ad" class="select2">
                                <option value="true" <?php if($settings_row['interstital_ad']=='true'){?>selected<?php }?>>True</option>
                                <option value="false" <?php if($settings_row['interstital_ad']=='false'){?>selected<?php }?>>False</option>
                    
                            </select> 
                      </div>
                    </div>
                    <div class="form-group">
                      <label class=" control-label mr_bottom20">Interstital ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="interstital_ad_id" id="interstital_ad_id" value="<?php echo $settings_row['interstital_ad_id'];?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label mr_bottom20">Interstital Clicks :-</label>
                      <div class="col-md-9">
                      <input type="text" name="interstital_ad_click" id="interstital_ad_click" value="<?php echo $settings_row['interstital_ad_click'];?>" class="form-control">
                      </div>
                    </div>                    
                  </div>                  
                </div>  
              </div>
              </div>
              <div class="col-md-6" style="display:none">                
              <div class="col-md-12">
                <div class="admob_title">iOS</div>
                <div class="form-group">
                  <label class=" control-label">Publisher ID :-</label>
                  <div class="col-md-9">
                    <input type="text" name="publisher_id_ios" id="publisher_id_ios" value="<?php echo $settings_row['publisher_id_ios'];?>" class="form-control">
                  </div>
                  <label class=" control-label">Application ID :-</label>
                  <div class="col-md-9">
                    <input type="text" name="app_id_ios" id="app_id_ios" value="<?php echo $settings_row['app_id_ios'];?>" class="form-control">
                  </div>
                </div>
                <div class="banner_ads_block">
                  <div class="banner_ad_item">
                    <label class="control-label">Banner Ads :-</label>                                  
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class=" control-label">Banner Ad:-</label>
                      <div class="col-md-9">
                         <select name="banner_ad_ios" id="banner_ad_ios" class="select2">
                                <option value="true" <?php if($settings_row['banner_ad_ios']=='true'){?>selected<?php }?>>True</option>
                                <option value="false" <?php if($settings_row['banner_ad_ios']=='false'){?>selected<?php }?>>False</option>
                    
                            </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class=" control-label mr_bottom20">Banner ID :-</label>
                      <div class="col-md-9">
                        <input type="text" name="banner_ad_id_ios" id="banner_ad_id_ios" value="<?php echo $settings_row['banner_ad_id_ios'];?>" class="form-control">
                      </div>
                    </div>                    
                  </div>
                </div>  
              </div>
              <div class="col-md-12">
                <div class="interstital_ads_block">
                  <div class="interstital_ad_item">
                    <label class="control-label">Interstital Ads :-</label>                   
                  </div>  
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class=" control-label">Interstital:-</label>
                      <div class="col-md-9">
                        <select name="interstital_ad_ios" id="interstital_ad_ios" class="select2">
                                <option value="true" <?php if($settings_row['interstital_ad_ios']=='true'){?>selected<?php }?>>True</option>
                                <option value="false" <?php if($settings_row['interstital_ad_ios']=='false'){?>selected<?php }?>>False</option>
                    
                            </select> 
                      </div>
                    </div>
                    <div class="form-group">
                      <label class=" control-label mr_bottom20">Interstital ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="interstital_ad_id_ios" id="interstital_ad_id_ios" value="<?php echo $settings_row['interstital_ad_id_ios'];?>" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label mr_bottom20">Interstital Clicks :-</label>
                      <div class="col-md-9">
                      <input type="text" name="interstital_ad_click_ios" id="interstital_ad_click_ios" value="<?php echo $settings_row['interstital_ad_click_ios'];?>" class="form-control">
                      </div>
                    </div>                    
                  </div>                  
                </div>  
              </div>
              </div>
            </div>
            </div>                        
            <div class="form-group">
              <div class="col-md-9">
              <button type="submit" name="admob_submit" class="btn btn-primary btn-round">Save</button>
              </div>
            </div>
            </div>
          </div>
                </form>
              </div>
        <div role="tabpanel" class="tab-pane" id="notification_settings">
              <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">
                <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">OneSignal App ID :-</label>
                    <div class="col-md-6">
                      <input type="text" name="onesignal_app_id" id="onesignal_app_id" value="<?php echo $settings_row['onesignal_app_id'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">OneSignal Rest Key :-</label>
                    <div class="col-md-6">
                      <input type="text" name="onesignal_rest_key" id="onesignal_rest_key" value="<?php echo $settings_row['onesignal_rest_key'];?>" class="form-control">
                    </div>
                  </div>              
                  <div class="form-group">
                  <div class="col-md-9 col-md-offset-3">
                    <button type="submit" name="notification_submit" class="btn btn-primary btn-round">Save</button>
                  </div>
                  </div>
                </div>
                </div>
              </form>
            </div>             
              <div role="tabpanel" class="tab-pane" id="api_settings">   
                <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  
                  <div class="form-group">
                    <div class="col-md-6">
                    </div>
                  </div> <hr/> 
                  <div class="form-group">
                    <label class="col-md-3 control-label">Latest Limit:-</label>
                    <div class="col-md-6">
                       
                      <input type="number" name="api_latest_limit" id="api_latest_limit" value="<?php echo $settings_row['api_latest_limit'];?>" class="form-control"> 
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Category List Order By:-</label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_cat_order_by" id="api_cat_order_by" class="select2">
                          <option value="cid" <?php if($settings_row['api_cat_order_by']=='cid'){?>selected<?php }?>>ID</option>
                          <option value="category_name" <?php if($settings_row['api_cat_order_by']=='category_name'){?>selected<?php }?>>Name</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Category Video Order:-</label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_cat_post_order_by" id="api_cat_post_order_by" class="select2">
                          <option value="ASC" <?php if($settings_row['api_cat_post_order_by']=='ASC'){?>selected<?php }?>>ASC</option>
                          <option value="DESC" <?php if($settings_row['api_cat_post_order_by']=='DESC'){?>selected<?php }?>>DESC</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">All Video Order:-</label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_all_order_by" id="api_all_order_by" class="select2">
                          <option value="ASC" <?php if($settings_row['api_all_order_by']=='ASC'){?>selected<?php }?>>ASC</option>
                          <option value="DESC" <?php if($settings_row['api_all_order_by']=='DESC'){?>selected<?php }?>>DESC</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  
                
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="api_submit" class="btn btn-primary btn-round">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div> 

              <div role="tabpanel" class="tab-pane" id="api_privacy_policy">   
                <form action="" name="api_privacy_policy" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">App Privacy Policy :-</label>
                    <div class="col-md-6">
                 
                      <textarea name="app_privacy_policy" id="privacy_policy" class="form-control"><?php echo $settings_row['app_privacy_policy'];?></textarea>

                      <script>CKEDITOR.replace( 'privacy_policy' );</script>
                    </div>
                  </div>
                  
                
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="app_pri_poly" class="btn btn-primary btn-round">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div> 

            </div>   

          </div>
        </div>
      </div>
    </div>

        
<?php include("includes/footer.php");?>       
