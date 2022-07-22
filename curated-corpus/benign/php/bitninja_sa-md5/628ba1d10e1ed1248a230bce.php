<?php 
	require("includes/connection.php");
	require("includes/function.php");
	require("language/language.php");
	require("language/app_language.php");

    $file_path_img = getBaseUrl();

	$response=array();

	$external_link=false;

	define("VIDEO_ADD_POINTS_STATUS",$settings_details['video_add_status']);

	define("RESPONSE_CLASS",'success');

	function get_user_info($user_id,$field_name) 
	{
		global $mysqli;

		$qry_user="SELECT * FROM tbl_users WHERE id='".$user_id."' AND status='1'";
		$query1=mysqli_query($mysqli,$qry_user);
		$row_user = mysqli_fetch_array($query1);

		$num_rows1 = mysqli_num_rows($query1);

		if ($num_rows1 > 0)
		{     
		  return $row_user[$field_name];
		}
		else
		{
		  return "";
		}
	}

	function delete_videos($video_ids) 
	{
		global $mysqli;

		$sql="SELECT * FROM tbl_video WHERE id IN ($video_ids)";
		$res=mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

		while ($row=mysqli_fetch_assoc($res)){

			if($row['video_thumbnail']!="")
			{
				unlink('images/'.$row['video_thumbnail']);
			}

			if($row['video_type']=='local'){
				unlink('uploads/'.basename($row['video_url']));
			}

			$delete_comment="DELETE FROM tbl_comments WHERE `post_id` = ".$row['id']." AND `type`='video'";

			mysqli_query($mysqli, $delete_comment);

			$delete_report="DELETE FROM tbl_reports WHERE `post_id` = ".$row['id']." AND `report_type`='video'";

			mysqli_query($mysqli, $delete_report);

			$delete_slider="DELETE FROM tbl_slider WHERE `post_id` = ".$row['id']." AND `slider_type`='video'";

			mysqli_query($mysqli, $delete_slider);
		}

		$deleteSql="DELETE FROM tbl_video WHERE id IN ($video_ids)";

		mysqli_query($mysqli, $deleteSql);

	}

	function delete_img_status($image_ids) 
	{
		global $mysqli;

		$sql="SELECT * FROM tbl_img_status WHERE id IN ($image_ids)";
		$res=mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

		while ($row=mysqli_fetch_assoc($res)) {

			if($row['image_file']!="" && file_exists('images/'.$row['image_file']))
			{
				unlink('images/'.$row['image_file']);
			}

			$delete_comment="DELETE FROM tbl_comments WHERE `post_id` IN (".$row['id'].") AND `type`='".$row['status_type']."'";

			mysqli_query($mysqli, $delete_comment);

			$delete_report="DELETE FROM tbl_reports WHERE `post_id` IN (".$row['id'].") AND `report_type`='".$row['status_type']."'";

			mysqli_query($mysqli, $delete_report);

			$delete_slider="DELETE FROM tbl_slider WHERE `post_id` IN (".$row['id'].") AND `slider_type`='".$row['status_type']."'";

			mysqli_query($mysqli, $delete_slider);

		}

		$deleteSql="DELETE FROM tbl_img_status WHERE id IN ($image_ids)";

		mysqli_query($mysqli, $deleteSql);

	}

	function delete_quotes_status($quotes_id) 
	{
		global $mysqli;

		$deleteSql="DELETE FROM tbl_quotes WHERE id IN ($quotes_id)";

		mysqli_query($mysqli, $deleteSql);

		$delete_comment="DELETE FROM tbl_comments WHERE `post_id` IN ($quotes_id) AND `type`='quote'";

		mysqli_query($mysqli, $delete_comment);

		$delete_report="DELETE FROM tbl_reports WHERE `post_id` IN ($quotes_id) AND `report_type`='quote'";

		mysqli_query($mysqli, $delete_report);

		$delete_slider="DELETE FROM tbl_slider WHERE `post_id` IN ($quotes_id) AND `slider_type`='quote'";

		mysqli_query($mysqli, $delete_slider);

	}

	function send_notification($fields){

		$fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $notify_res = curl_exec($ch);  

        curl_close($ch);

        return $notify_res;
	}

	switch ($_POST['action']) {
		case 'toggle_status':
			$id=$_POST['id'];
			$for_action=$_POST['for_action'];
			$column=$_POST['column'];
			$tbl_id=$_POST['tbl_id'];
			$table_nm=$_POST['table'];

			if($for_action=='active'){

			    if(isset($_POST['status_type'])){

			    	$status_type=trim($_POST['status_type']);

			    	switch ($status_type) {
			    		case 'video':
			    			{
			    				if(VIDEO_ADD_POINTS_STATUS=='true')
								{
									$sql="SELECT * FROM tbl_video WHERE `id` IN ('$id')";
									$result=mysqli_query($mysqli,$sql);
									
									while ($row=mysqli_fetch_assoc($result))
									{
										$post_id=$row['id'];
										$user_id=$row['user_id'];

										if(is_suspend($user_id)==0){

											$sql_activity = "SELECT * FROM tbl_users_rewards_activity WHERE `post_id` = '$post_id' AND `user_id` = '$user_id' AND `activity_type`='".$app_lang['add_video']."'";

											$res_activity = mysqli_query($mysqli,$sql_activity);

											$add_point=$settings_details['video_add']; 

											if ($res_activity->num_rows == 0)
											{

												$qry2 = "SELECT * FROM tbl_users WHERE `id` = '".$user_id."'";
												$result2 = mysqli_query($mysqli,$qry2);
												$row2=mysqli_fetch_assoc($result2); 

												$user_total_point=$row2['total_point']+$add_point;

												$user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE `id` = '".$user_id."'");

												user_reward_activity($post_id,$user_id,$app_lang['add_video'],$add_point);
											}

											$img_path=$file_path_img.'images/'.$row['video_thumbnail'];

											$user_name=ucwords(get_user_info($user_id,'name'));

					                      	$content = array("en" => str_replace('###', $user_name, $client_lang['add_video_notify_msg']));

					                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

											$res_follower=mysqli_query($mysqli, $sql_follower);

											$followers=array();

											while ($row_follower=mysqli_fetch_assoc($res_follower)) {
												$followers[]=$row_follower['player_id'];
											}

											if(!empty($followers))
											{
												$fields = array(
										              'app_id' => ONESIGNAL_APP_ID,                                       
										              'include_player_ids' => $followers, 
										              'data' => array("foo" => "bar","type" => "single_status","status_type" => $status_type,"id" => $post_id,"external_link"=>$external_link),
										              'headings'=> array("en" => APP_NAME),
										              'contents' => $content,
										              'big_picture' =>$img_path
										              );

												send_notification($fields);
											}
										}
										else{
											$response['status']=2;
									      	echo json_encode($response);
									      	exit;
										}
									} 	
								}
			    			}
			    			break;

			    		case 'image':
			    			{
			    				if($settings_details['image_add_status']=='true')
								{
									$sql="SELECT * FROM tbl_img_status WHERE `id` IN ('$id') AND `status_type`='image'";
									$result=mysqli_query($mysqli,$sql);
									
									while ($row=mysqli_fetch_assoc($result))
									{
										$post_id=$row['id'];
										$user_id=$row['user_id'];

										if(is_suspend($user_id)==0){

											$sql_activity = "SELECT * FROM tbl_users_rewards_activity WHERE `post_id` = '$post_id' AND `user_id` = '$user_id' AND `activity_type`='".$app_lang['add_image']."'";

											$res_activity = mysqli_query($mysqli,$sql_activity);

											$add_point=$settings_details['image_add']; 

											if ($res_activity->num_rows == 0)
											{

												$qry2 = "SELECT * FROM tbl_users WHERE id = '".$user_id."'";
												$result2 = mysqli_query($mysqli,$qry2);
												$row2=mysqli_fetch_assoc($result2); 

												$user_total_point=$row2['total_point']+$add_point;

												$user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE id = '".$user_id."'");

												user_reward_activity($post_id,$user_id,$app_lang['add_image'],$add_point);
											}

											$img_path=$file_path_img.'images/'.$row['image_file'];

											$user_name=ucwords(get_user_info($user_id,'name'));
											
											$content = array("en" => str_replace('###', $user_name, $client_lang['add_img_notify_msg']));

					                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

											$res_follower=mysqli_query($mysqli, $sql_follower);

											$followers=array();

											while ($row_follower=mysqli_fetch_assoc($res_follower)) {
												$followers[]=$row_follower['player_id'];
											}

											if(!empty($followers))
											{
												$fields = array(
										              'app_id' => ONESIGNAL_APP_ID,                                       
										              'include_player_ids' => $followers, 
										              'data' => array("foo" => "bar","type" => "single_status","status_type" => $status_type,"id" => $post_id,"external_link"=>$external_link),
										              'headings'=> array("en" => APP_NAME),
										              'contents' => $content,
										              'big_picture' =>$img_path
										              );

												send_notification($fields);
											}
										}
										else{
											$response['status']=2;
									      	echo json_encode($response);
									      	exit;
										}
									} 	
								}
			    			}
			    			break;

			    		case 'gif':
			    			{
			    				if($settings_details['gif_add_status']=='true')
								{
									$sql="SELECT * FROM tbl_img_status WHERE `id` IN ('$id') AND `status_type`='gif'";
									$result=mysqli_query($mysqli,$sql);
									
									while ($row=mysqli_fetch_assoc($result))
									{
										$post_id=$row['id'];
										$user_id=$row['user_id'];

										if(is_suspend($user_id)==0){

											$sql_activity = "SELECT * FROM tbl_users_rewards_activity WHERE `post_id` = '$post_id' AND `user_id` = '$user_id' AND `activity_type`='".$app_lang['add_gif']."'";

											$res_activity = mysqli_query($mysqli,$sql_activity);

											$add_point=$settings_details['gif_add']; 

											if ($res_activity->num_rows == 0)
											{

												$qry2 = "SELECT * FROM tbl_users WHERE id = '".$user_id."'";
												$result2 = mysqli_query($mysqli,$qry2);
												$row2=mysqli_fetch_assoc($result2); 

												$user_total_point=$row2['total_point']+$add_point;

												$user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE id = '".$user_id."'");

												user_reward_activity($post_id,$user_id,$app_lang['add_gif'],$add_point);
											}

											$img_path=$file_path_img.'images/'.$row['image_file'];

											$user_name=ucwords(get_user_info($user_id,'name'));
											
											$content = array("en" => str_replace('###', $user_name, $client_lang['add_gif_notify_msg']));

					                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

											$res_follower=mysqli_query($mysqli, $sql_follower);

											$followers=array();

											while ($row_follower=mysqli_fetch_assoc($res_follower)) {
												$followers[]=$row_follower['player_id'];
											}

											if(!empty($followers))
											{
												$fields = array(
										              'app_id' => ONESIGNAL_APP_ID,                                       
										              'include_player_ids' => $followers, 
										              'data' => array("foo" => "bar","type" => "single_status","status_type" => $status_type,"id" => $post_id,"external_link"=>$external_link),
										              'headings'=> array("en" => APP_NAME),
										              'contents' => $content,
										              'big_picture' =>$img_path
										              );

												send_notification($fields);
											}
										}
										else{
											$response['status']=2;
									      	echo json_encode($response);
									      	exit;
										}
									} 	
								}
			    			}
			    			break;

			    		case 'quote':
			    			{
			    				if($settings_details['quotes_add_status']=='true')
								{
									$sql="SELECT * FROM tbl_quotes WHERE `id` IN ('$id')";
									$result=mysqli_query($mysqli,$sql);
									
									while ($row=mysqli_fetch_assoc($result))
									{
										$post_id=$row['id'];
										$user_id=$row['user_id'];

										if(is_suspend($user_id)==0){

											$sql_activity = "SELECT * FROM tbl_users_rewards_activity WHERE `post_id` = '$post_id' AND `user_id` = '$user_id' AND `activity_type`='".$app_lang['add_quote']."'";

											$res_activity = mysqli_query($mysqli,$sql_activity);

											$add_point=$settings_details['quotes_add']; 

											if ($res_activity->num_rows == 0)
											{

												$qry2 = "SELECT * FROM tbl_users WHERE id = '".$user_id."'";
												$result2 = mysqli_query($mysqli,$qry2);
												$row2=mysqli_fetch_assoc($result2); 

												$user_total_point=$row2['total_point']+$add_point;

												$user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE id = '".$user_id."'");

												user_reward_activity($post_id,$user_id,$app_lang['add_quote'],$add_point);
											}

											$user_name=ucwords(get_user_info($user_id,'name'));
											
											$content = array("en" => str_replace('###', $user_name, $client_lang['add_quote_notify_msg']));

					                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

											$res_follower=mysqli_query($mysqli, $sql_follower);

											$followers=array();

											while ($row_follower=mysqli_fetch_assoc($res_follower)) {
												$followers[]=$row_follower['player_id'];
											}

											if(!empty($followers))
											{
												$fields = array(
										              'app_id' => ONESIGNAL_APP_ID,                                       
										              'include_player_ids' => $followers, 
										              'data' => array("foo" => "bar","type" => "single_status","status_type" => $status_type,"id" => $post_id,"external_link"=>$external_link),
										              'headings'=> array("en" => APP_NAME),
										              'contents' => $content
										              );

												send_notification($fields);
											}
										}
										else{
											$response['status']=2;
									      	echo json_encode($response);
									      	exit;
										}
									} 	
								}
			    			}
			    			break;
			    		
			    		default:
			    			break;
			    	}

			    	$sql="SELECT * FROM tbl_slider WHERE `post_id`=$id AND `slider_type`='$status_type'";
    				$res=mysqli_query($mysqli, $sql);

    				if($res->num_rows > 0){

    					$row=mysqli_fetch_assoc($res);

    					$data = array('status'  =>  '1');
    					$edit_status=Update('tbl_slider', $data, "WHERE id = ".$row['id']);
    				}

			    	if($column=='featured'){

			    		if($res->num_rows == 0){

							$data_slider = array(
								'post_id' => $id,
								'slider_type' =>  $status_type,
								'slider_title' =>  '',
								'external_url' =>  '',
								'external_image' =>  ''
							);  

							$qry = Insert('tbl_slider',$data_slider);
						}

			    	}

			    }

			    $data = array($column  =>  '1');
			    $edit_status=Update($table_nm, $data, "WHERE $tbl_id = '$id'");

			    if($column=='show_on_home'){
					$_SESSION['msg']="24";
				}
				else if($column=='featured'){
					$_SESSION['msg']="26";
				}
				else{
					$_SESSION['msg']="13";
				}



			}else{
				$data = array($column  =>  '0');
			    $edit_status=Update($table_nm, $data, "WHERE $tbl_id = '$id'");

				if($column=='show_on_home'){
					$_SESSION['msg']="25";
				}
				else if($column=='featured'){
					$_SESSION['msg']="27";
				}
				else{
					$_SESSION['msg']="14";
				}

				if(isset($_POST['status_type'])){

			    	$status_type=trim($_POST['status_type']);

			    	$sql="SELECT * FROM tbl_slider WHERE `post_id`=$id AND `slider_type`='$status_type'";
    				$res=mysqli_query($mysqli, $sql);

    				if($res->num_rows > 0){

    					$row=mysqli_fetch_assoc($res);

    					$data = array('status'  =>  '0');
    					$edit_status=Update('tbl_slider', $data, "WHERE id = ".$row['id']);
    				}

			    	mysqli_free_result($res);

			    	if($column=='featured'){

						$sqlDelete="DELETE FROM tbl_slider WHERE `post_id`=$id AND `slider_type`='$status_type'";
						mysqli_query($mysqli, $sqlDelete);
			    	}
			    }

			}
			
	      	$response['status']=1;
	      	$response['action']=$for_action;
	      	echo json_encode($response);
			break;

		case 'removeReport':
			$id=$_POST['id'];

			if(!isset($_POST['post_id'])){
				$sqlDelete="DELETE FROM tbl_reports WHERE `id`=$id";
				mysqli_query($mysqli, $sqlDelete);
			}
			else{
				$type=$_POST['type'];
				$post_id=$_POST['post_id'];
				$sqlDelete="DELETE FROM tbl_reports WHERE `post_id` IN ($post_id) AND `report_type`='$type'";
				mysqli_query($mysqli, $sqlDelete);

			}
	      	
	      	$response['status']=1;
	      	echo json_encode($response);
			break;

		case 'removeComment':
			$id=$_POST['id'];

			if(!isset($_POST['post_id'])){
				$sqlDelete="DELETE FROM tbl_comments WHERE `id`=$id";
				mysqli_query($mysqli, $sqlDelete);
			}
			else{
				$type=$_POST['type'];
				$post_id=$_POST['post_id'];
				$sqlDelete="DELETE FROM tbl_comments WHERE `post_id` IN ($post_id) AND `type`='$type'";
				mysqli_query($mysqli, $sqlDelete);

			}
	      	
	      	$response['status']=1;
	      	echo json_encode($response);
			break;

		case 'removeAllComment':
			
			$post_id=implode(',', $_POST['post_id']);

			$type=$_POST['type'];

			$sqlDelete="DELETE FROM tbl_comments WHERE `post_id` IN ($post_id) AND `type`='$type'";

			if(mysqli_query($mysqli, $sqlDelete)){
				$response['status']=1;	
			}
			else{
				$response['status']=0;
			}
	      	echo json_encode($response);

			break;

		case 'removeAllTransaction':
			
			$ids=implode(',', $_POST['ids']);

			$type=$_POST['type'];

			$sqlDelete="DELETE FROM tbl_users_redeem WHERE `id` IN ($ids)";

			if(mysqli_query($mysqli, $sqlDelete)){
				$response['status']=1;	
			}
			else{
				$response['status']=0;
			}
	      	echo json_encode($response);

			break;

		case 'removeContact':

			$ids=is_array($_POST['ids']) ? implode(',', $_POST['ids']) : $_POST['ids'];

			$sqlDelete="DELETE FROM tbl_contact_list WHERE `id` IN ($ids)";
			
			if(mysqli_query($mysqli, $sqlDelete)){
				$response['status']=1;	
			}
			else{
				$response['status']=0;
			}

	      	echo json_encode($response);

			break;

		case 'removeAllReports':
			
			$post_id=implode(',', $_POST['post_id']);

			$type=$_POST['type'];

			$sqlDelete="DELETE FROM tbl_reports WHERE `post_id` IN ($post_id) AND `report_type`='$type'";

			if(mysqli_query($mysqli, $sqlDelete)){
				$response['status']=1;	
			}
			else{
				$response['status']=0;
			}
	      	echo json_encode($response);

			break;

		case 'notify':
			
			$sql = "SELECT * FROM tbl_verify_user WHERE `is_opened`='0' AND `status`='0' ORDER BY `id` DESC";
			$qry = mysqli_query($mysqli, $sql);
			$info=array();
			while ($row = mysqli_fetch_assoc($qry)) {
				
				$data='<li>
	                    <a href="javascript:void(0)" class="btn_verify" data-id="'.$row['id'].'">
	                      <span class="badge badge-success pull-right">'.date('d M, Y',$row['created_at']).'</span>
	                      <div class="message">
	                        <div class="content">
	                          <div class="title">'.$client_lang['verify_request_notify'].'</div>
	                          <div class="description"><strong>By:</strong> '.$row['full_name'].' </div>
	                        </div>
	                      </div>
	                    </a>
	                  </li>';

				array_push($info, $data);
			}

			if($count!=0){
	      		$response['content']=$info;	
	      	}
	      	else{

	      		$data='<li>
	                    <p style="text-align: center;font-size: 16px;padding: 10px;color: #333;font-weight: 400;">'.$client_lang['no_data_msg'].'</p>
	                  </li>';

				array_push($info, $data);

	      		$response['content']=$info;
	      	}

	      	$response['status']=1;
	      	$response['count']=mysqli_num_rows($qry);

	      	echo json_encode($response);
			break;

		case 'openAllNotify':
			
			$data = array('is_opened'  =>  '1');
			$edit_status=Update('tbl_verify_user', $data, "WHERE is_opened = '0'");
			$info=array();
			$response['content']=$info;

	      	$response['status']=1;
	      	$response['count']=0;

	      	echo json_encode($response);
			break;

		case 'openNotify':

			$id=$_POST['id'];
			
			$data = array('is_opened'  =>  '1');
			$edit_status=Update('tbl_verify_user', $data, "WHERE id = '$id'");

			$sql = "SELECT * FROM tbl_verify_user WHERE `is_opened`='0' AND `status`='0' ORDER BY `id` DESC";
			$qry = mysqli_query($mysqli, $sql);
			$info=array();
			while ($row = mysqli_fetch_assoc($qry)) {
				
				$data='<li>
	                    <a href="" class="btn_verify" data-id="'.$row['id'].'">
	                      <span class="badge badge-success pull-right">'.date('d M, Y',$row['created_at']).'</span>
	                      <div class="message">
	                        <div class="content">
	                          <div class="title">'.$client_lang['verify_request_notify'].'</div>
	                          <div class="description"><strong>By:</strong> '.$row['full_name'].' </div>
	                        </div>
	                      </div>
	                    </a>
	                  </li>';

				array_push($info, $data);
			}

			$response['content']=$info;

	      	$response['status']=1;
	      	$response['count']=mysqli_num_rows($qry);

	      	echo json_encode($response);
			break;
			
		case 'multi_action':

			$action=$_POST['for_action'];
			$ids=implode(",", $_POST['id']);
			$table=$_POST['table'];

			if($ids == '') {
				$ids = $_POST['id'];
			}

			if($action=='enable'){

				if(isset($_POST['status_type'])){

			    	$status_type=trim($_POST['status_type']);

			    	switch ($status_type) {
			    		case 'video':
			    			{
			    				if(VIDEO_ADD_POINTS_STATUS=='true')
								{
									$sql="SELECT * FROM tbl_video WHERE `id` IN ('$ids')";
									$result=mysqli_query($mysqli,$sql);
									
									while ($row=mysqli_fetch_assoc($result))
									{
										$post_id=$row['id'];
										$user_id=$row['user_id'];

										if(is_suspend($user_id)==0){

											$sql_activity = "SELECT * FROM tbl_users_rewards_activity WHERE `post_id` = '$post_id' AND `user_id` = '$user_id' AND `activity_type`='".$app_lang['add_video']."'";

											$res_activity = mysqli_query($mysqli,$sql_activity);

											$add_point=$settings_details['video_add']; 

											if ($res_activity->num_rows == 0)
											{

												$qry2 = "SELECT * FROM tbl_users WHERE `id` = '".$user_id."'";
												$result2 = mysqli_query($mysqli,$qry2);
												$row2=mysqli_fetch_assoc($result2); 

												$user_total_point=$row2['total_point']+$add_point;

												$user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE `id` = '".$user_id."'");

												user_reward_activity($post_id,$user_id,$app_lang['add_video'],$add_point);
											}

											$img_path=$file_path_img.'images/'.$row['video_thumbnail'];

											$user_name=ucwords(get_user_info($user_id,'name'));
											
											$content = array("en" => str_replace('###', $user_name, $client_lang['add_video_notify_msg']));

					                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

											$res_follower=mysqli_query($mysqli, $sql_follower);

											$followers=array();

											while ($row_follower=mysqli_fetch_assoc($res_follower)) {
												$followers[]=$row_follower['player_id'];
											}

											if(!empty($followers))
											{
												$fields = array(
										              'app_id' => ONESIGNAL_APP_ID,                                       
										              'include_player_ids' => $followers, 
										              'data' => array("foo" => "bar","type" => "single_status","status_type" => $status_type,"id" => $post_id,"external_link"=>$external_link),
										              'headings'=> array("en" => APP_NAME),
										              'contents' => $content,
										              'big_picture' =>$img_path
										              );

												send_notification($fields);
											}
										}
									} 	
								}
			    			}
			    			break;

			    		case 'image':
			    			{
			    				if($settings_details['image_add_status']=='true')
								{
									$sql="SELECT * FROM tbl_img_status WHERE `id` IN ('$ids') AND `status_type`='image'";
									$result=mysqli_query($mysqli,$sql);
									
									while ($row=mysqli_fetch_assoc($result))
									{
										$post_id=$row['id'];
										$user_id=$row['user_id'];

										if(is_suspend($user_id)==0){

											$sql_activity = "SELECT * FROM tbl_users_rewards_activity WHERE `post_id` = '$post_id' AND `user_id` = '$user_id' AND `activity_type`='".$app_lang['add_image']."'";

											$res_activity = mysqli_query($mysqli,$sql_activity);

											$add_point=$settings_details['image_add']; 

											if ($res_activity->num_rows == 0)
											{

												$qry2 = "SELECT * FROM tbl_users WHERE id = '".$user_id."'";
												$result2 = mysqli_query($mysqli,$qry2);
												$row2=mysqli_fetch_assoc($result2); 

												$user_total_point=$row2['total_point']+$add_point;

												$user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE id = '".$user_id."'");

												user_reward_activity($post_id,$user_id,$app_lang['add_image'],$add_point);
											}

											$img_path=$file_path_img.'images/'.$row['image_file'];

											$user_name=ucwords(get_user_info($user_id,'name'));
											
											$content = array("en" => str_replace('###', $user_name, $client_lang['add_img_notify_msg']));

					                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

											$res_follower=mysqli_query($mysqli, $sql_follower);

											$followers=array();

											while ($row_follower=mysqli_fetch_assoc($res_follower)) {
												$followers[]=$row_follower['player_id'];
											}

											if(!empty($followers))
											{
												$fields = array(
										              'app_id' => ONESIGNAL_APP_ID,                                       
										              'include_player_ids' => $followers, 
										              'data' => array("foo" => "bar","type" => "single_status","status_type" => $status_type,"id" => $post_id,"external_link"=>$external_link),
										              'headings'=> array("en" => APP_NAME),
										              'contents' => $content,
										              'big_picture' =>$img_path
										              );

												send_notification($fields);
											}
										}
									} 	
								}
			    			}
			    			break;

			    		case 'gif':
			    			{
			    				if($settings_details['gif_add_status']=='true')
								{
									$sql="SELECT * FROM tbl_img_status WHERE `id` IN ('$ids') AND `status_type`='gif'";
									$result=mysqli_query($mysqli,$sql);
									
									while ($row=mysqli_fetch_assoc($result))
									{
										$post_id=$row['id'];
										$user_id=$row['user_id'];

										if(is_suspend($user_id)==0){

											$sql_activity = "SELECT * FROM tbl_users_rewards_activity WHERE `post_id` = '$post_id' AND `user_id` = '$user_id' AND `activity_type`='".$app_lang['add_gif']."'";

											$res_activity = mysqli_query($mysqli,$sql_activity);

											$add_point=$settings_details['gif_add']; 

											if ($res_activity->num_rows == 0)
											{

												$qry2 = "SELECT * FROM tbl_users WHERE id = '".$user_id."'";
												$result2 = mysqli_query($mysqli,$qry2);
												$row2=mysqli_fetch_assoc($result2); 

												$user_total_point=$row2['total_point']+$add_point;

												$user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE id = '".$user_id."'");

												user_reward_activity($post_id,$user_id,$app_lang['add_gif'],$add_point);
											}

											$img_path=$file_path_img.'images/'.$row['image_file'];

											$user_name=ucwords(get_user_info($user_id,'name'));
											
											$content = array("en" => str_replace('###', $user_name, $client_lang['add_gif_notify_msg']));

					                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

											$res_follower=mysqli_query($mysqli, $sql_follower);

											$followers=array();

											while ($row_follower=mysqli_fetch_assoc($res_follower)) {
												$followers[]=$row_follower['player_id'];
											}

											if(!empty($followers))
											{
												$fields = array(
										              'app_id' => ONESIGNAL_APP_ID,                                       
										              'include_player_ids' => $followers, 
										              'data' => array("foo" => "bar","type" => "single_status","status_type" => $status_type,"id" => $post_id,"external_link"=>$external_link),
										              'headings'=> array("en" => APP_NAME),
										              'contents' => $content,
										              'big_picture' =>$img_path
										              );

												send_notification($fields);
											}
										}
									} 	
								}
			    			}
			    			break;

			    		case 'quote':
			    			{
			    				if($settings_details['quotes_add_status']=='true')
								{
									$sql="SELECT * FROM tbl_quotes WHERE `id` IN ('$ids')";
									$result=mysqli_query($mysqli,$sql);
									
									while ($row=mysqli_fetch_assoc($result))
									{
										$post_id=$row['id'];
										$user_id=$row['user_id'];

										if(is_suspend($user_id)==0){

											$sql_activity = "SELECT * FROM tbl_users_rewards_activity WHERE `post_id` = '$post_id' AND `user_id` = '$user_id' AND `activity_type`='".$app_lang['add_quote']."'";

											$res_activity = mysqli_query($mysqli,$sql_activity);

											$add_point=$settings_details['quotes_add']; 

											if ($res_activity->num_rows == 0)
											{

												$qry2 = "SELECT * FROM tbl_users WHERE id = '".$user_id."'";
												$result2 = mysqli_query($mysqli,$qry2);
												$row2=mysqli_fetch_assoc($result2); 

												$user_total_point=$row2['total_point']+$add_point;

												$user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE id = '".$user_id."'");

												user_reward_activity($post_id,$user_id,$app_lang['add_quote'],$add_point);
											}

											$user_name=ucwords(get_user_info($user_id,'name'));
											
											$content = array("en" => str_replace('###', $user_name, $client_lang['add_quote_notify_msg']));

					                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

											$res_follower=mysqli_query($mysqli, $sql_follower);

											$followers=array();

											while ($row_follower=mysqli_fetch_assoc($res_follower)) {
												$followers[]=$row_follower['player_id'];
											}

											if(!empty($followers))
											{
												$fields = array(
										              'app_id' => ONESIGNAL_APP_ID,                                       
										              'include_player_ids' => $followers, 
										              'data' => array("foo" => "bar","type" => "single_status","status_type" => $status_type,"id" => $post_id,"external_link"=>$external_link),
										              'headings'=> array("en" => APP_NAME),
										              'contents' => $content
										              );

												send_notification($fields);
											}
										}
									} 	
								}
			    			}
			    			break;
			    		
			    		default:
			    			break;
			    	}

			    }

			    $sql="UPDATE $table SET status='1' WHERE `id` IN ($ids)";
				mysqli_query($mysqli, $sql);
				$response['status']=1;	
				$_SESSION['msg']="13";
				
			}
			else if($action=='disable'){
				$sql="UPDATE $table SET status='0' WHERE `id` IN ($ids)";
				if(mysqli_query($mysqli, $sql)){
					$response['status']=1;	
					$_SESSION['msg']="14";
				}
			}
			else if($action=='delete'){

				if($table=='tbl_video'){
					delete_videos($ids);
					$response['status']=1;		
				}
				else if($table=='tbl_img_status'){
					delete_img_status($ids);
					$response['status']=1;	
				}
				else if($table=='tbl_quotes'){
					delete_quotes_status($ids);
					$response['status']=1;	
				}
				else if($table=='tbl_category')
				{
					$sql="SELECT * FROM tbl_category WHERE cid IN ($ids)";
					$result=mysqli_query($mysqli,$sql);
					while($row=mysqli_fetch_assoc($result))
					{
						if($row['category_image']!="")
						{
							unlink('images/'.$row['category_image']);
							unlink('images/thumbs/'.$row['category_image']);
						}
					}

					$deleteSql="DELETE FROM tbl_category WHERE `cid` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);

					$response['status']=1;
				}
				else if($table=='tbl_language')
				{
					$sql="SELECT * FROM tbl_language WHERE id IN ($ids)";
					$result=mysqli_query($mysqli,$sql);
					while($row=mysqli_fetch_assoc($result))
					{
						if($row['language_image']!="")
						{
							unlink('images/'.$row['language_image']);
							unlink('images/thumbs/'.$row['language_image']);
						}
					}

					$deleteSql="DELETE FROM tbl_language WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);

					$response['status']=1;
				}
				else if($table=='tbl_slider')
				{
					$sql="SELECT * FROM tbl_slider WHERE id IN ($ids)";
					$result=mysqli_query($mysqli,$sql);
					while($row=mysqli_fetch_assoc($result))
					{
						if($row['external_image']!="")
						{
							unlink('images/'.$row['external_image']);
						}

						if($row['slider_type']=='video')
						{
							$updateSql="UPDATE tbl_video SET `featured`='0' WHERE id = ".$row['post_id'];
						}
						else if($row['slider_type']=='image'){
							$updateSql="UPDATE tbl_img_status SET `featured`='0' WHERE id = ".$row['post_id'];
						}
						else if($row['slider_type']=='gif'){
							$updateSql="UPDATE tbl_img_status SET `featured`='0' WHERE id = ".$row['post_id'];
						}
						else if($row['slider_type']=='quote'){
							$updateSql="UPDATE tbl_quotes SET `featured`='0' WHERE id = ".$row['post_id'];
						}

						$update=mysqli_query($mysqli,$updateSql) or die(mysqli_error($mysqli));
					}

					$deleteSql="DELETE FROM tbl_slider WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);

					$response['status']=1;
				}
				else if($table=='tbl_users'){

					if(is_array($_POST['id'])){
						foreach ($_POST['id'] as $key => $value)
						{
							$del_id = $value; 

							deleted_user_copy($del_id);

							Delete('tbl_comments','user_id='.$del_id); 
							Delete('tbl_users_rewards_activity','user_id='.$del_id); 

							Delete('tbl_favourite','user_id='.$del_id);
							Delete('tbl_suspend_account','user_id='.$del_id); 

							Delete('tbl_comments','user_id='.$del_id); 
							Delete('tbl_reports','user_id='.$del_id);

							Delete('tbl_active_log','user_id='.$del_id);

							Delete('tbl_users_rewards_activity','user_id='.$del_id); 

							$sql="SELECT user_id FROM tbl_follows WHERE follower_id='$del_id'";
							$res=mysqli_query($mysqli, $sql);

							while($row=mysqli_fetch_assoc($res)){

								$updateSql="UPDATE tbl_users SET total_followers= total_followers - 1  WHERE id = '".$row['user_id']."'";

								$update=mysqli_query($mysqli,$updateSql) or die(mysqli_error($mysqli));
							}

							mysqli_free_result($res);

							$sql="SELECT follower_id FROM tbl_follows WHERE `user_id`='$del_id'";
							$res=mysqli_query($mysqli, $sql);

							while($row=mysqli_fetch_assoc($res)){

								$updateSql="UPDATE tbl_users SET total_following= total_following - 1  WHERE id = '".$row['follower_id']."'";

								$update=mysqli_query($mysqli,$updateSql) or die(mysqli_error($mysqli));
							}


							Delete('tbl_follows','user_id='.$del_id);
							Delete('tbl_follows','follower_id='.$del_id);

							mysqli_free_result($res);

							$sql="SELECT * FROM tbl_video WHERE `user_id`='$del_id' AND `video_type`='local'";
							$res=mysqli_query($mysqli, $sql);
							while ($row = mysqli_fetch_assoc($res)) {
								if(file_exists('images/'.$row['video_thumbnail'])){
									unlink('images/'.$row['video_thumbnail']);
								}

								if(file_exists('uploads/'.basename($row['video_url']))){
									unlink('uploads/'.basename($row['video_url']));
								}

								$delete_slider="DELETE FROM tbl_slider WHERE `post_id` IN (".$row['id'].") AND `slider_type`='video'";

								mysqli_query($mysqli, $delete_slider);
							}

							Delete('tbl_video','user_id='.$del_id); 

							$sql="SELECT * FROM tbl_img_status WHERE `user_id`='$del_id'";
							$res=mysqli_query($mysqli, $sql);
							while ($row = mysqli_fetch_assoc($res)) {
								if(file_exists('images/'.$row['image_file'])){
									unlink('images/'.$row['image_file']);
								}

								$delete_slider="DELETE FROM tbl_slider WHERE `post_id` IN (".$row['id'].") AND `slider_type`='".$row['status_type']."'";

								mysqli_query($mysqli, $delete_slider);
							}

							Delete('tbl_img_status','user_id='.$del_id); 

							mysqli_free_result($res);

							$sql="SELECT * FROM tbl_quotes WHERE `user_id`='$del_id'";
							$res=mysqli_query($mysqli, $sql);
							while ($row = mysqli_fetch_assoc($res)) {

								$delete_slider="DELETE FROM tbl_slider WHERE `post_id` IN (".$row['id'].") AND `slider_type`='quote'";

								mysqli_query($mysqli, $delete_slider);
							}

							Delete('tbl_quotes','user_id='.$del_id);
							mysqli_free_result($res);

							$sql="SELECT * FROM tbl_verify_user WHERE `user_id`='$del_id'";
							$res=mysqli_query($mysqli, $sql);
							while ($row = mysqli_fetch_assoc($res)) {

								if(file_exists('images/documents/'.$row['document'])){
									unlink('images/documents/'.$row['document']);
								}
							}

							Delete('tbl_verify_user','user_id='.$del_id);

							mysqli_free_result($res);

							$sql="SELECT * FROM tbl_users_redeem WHERE `user_id`='$del_id'";
							$res=mysqli_query($mysqli, $sql);
							while ($row = mysqli_fetch_assoc($res)) {

								if(file_exists('images/payment_receipt/'.$row['receipt_img'])){
									unlink('images/payment_receipt/'.$row['receipt_img']);
								}
							}

							Delete('tbl_users_redeem','user_id='.$del_id);

							mysqli_free_result($res);

							$sql="SELECT * FROM tbl_users WHERE `id`='$del_id'";
							$result=mysqli_query($mysqli, $sql);

							$row_user=mysqli_fetch_assoc($result);

							if(file_exists('images/'.$row_user['user_image'])){
								unlink('images/'.$row_user['user_image']);
							}

							Delete('tbl_users','id='.$del_id);

							mysqli_free_result($res);

						}
					}
					else
					{
						$del_id = $_POST['id']; 

						deleted_user_copy($del_id);

						Delete('tbl_comments','user_id='.$del_id); 
						Delete('tbl_users_rewards_activity','user_id='.$del_id); 

						Delete('tbl_favourite','user_id='.$del_id);

						Delete('tbl_suspend_account','user_id='.$del_id); 

						Delete('tbl_comments','user_id='.$del_id); 
						Delete('tbl_reports','user_id='.$del_id);

						Delete('tbl_active_log','user_id='.$del_id);

						Delete('tbl_users_rewards_activity','user_id='.$del_id); 

						$sql="SELECT user_id FROM tbl_follows WHERE follower_id='$del_id'";
						$res=mysqli_query($mysqli, $sql);

						while($row=mysqli_fetch_assoc($res)){

							$updateSql="UPDATE tbl_users SET total_followers= total_followers - 1  WHERE id = '".$row['user_id']."'";

							$update=mysqli_query($mysqli,$updateSql) or die(mysqli_error($mysqli));
						}

						mysqli_free_result($res);

						$sql="SELECT follower_id FROM tbl_follows WHERE `user_id`='$del_id'";
						$res=mysqli_query($mysqli, $sql);

						while($row=mysqli_fetch_assoc($res)){

							$updateSql="UPDATE tbl_users SET total_following= total_following - 1  WHERE id = '".$row['follower_id']."'";

							$update=mysqli_query($mysqli,$updateSql) or die(mysqli_error($mysqli));
						}


						Delete('tbl_follows','user_id='.$del_id);
						Delete('tbl_follows','follower_id='.$del_id);

						mysqli_free_result($res);

						$sql="SELECT * FROM tbl_video WHERE `user_id`='$del_id' AND `video_type`='local'";
						$res=mysqli_query($mysqli, $sql);
						while ($row = mysqli_fetch_assoc($res)) {
							if(file_exists('images/'.$row['video_thumbnail'])){
								unlink('images/'.$row['video_thumbnail']);
							}

							if(file_exists('uploads/'.basename($row['video_url']))){
								unlink('uploads/'.basename($row['video_url']));
							}

							$delete_slider="DELETE FROM tbl_slider WHERE `post_id` IN (".$row['id'].") AND `slider_type`='video'";

							mysqli_query($mysqli, $delete_slider);
						}

						Delete('tbl_video','user_id='.$del_id); 

						$sql="SELECT * FROM tbl_img_status WHERE `user_id`='$del_id'";
						$res=mysqli_query($mysqli, $sql);
						while ($row = mysqli_fetch_assoc($res)) {
							if(file_exists('images/'.$row['image_file'])){
								unlink('images/'.$row['image_file']);
							}

							$delete_slider="DELETE FROM tbl_slider WHERE `post_id` IN (".$row['id'].") AND `slider_type`='".$row['status_type']."'";

							mysqli_query($mysqli, $delete_slider);
						}

						Delete('tbl_img_status','user_id='.$del_id); 

						mysqli_free_result($res);

						$sql="SELECT * FROM tbl_quotes WHERE `user_id`='$del_id'";
						$res=mysqli_query($mysqli, $sql);
						while ($row = mysqli_fetch_assoc($res)) {

							$delete_slider="DELETE FROM tbl_slider WHERE `post_id` IN (".$row['id'].") AND `slider_type`='quote'";

							mysqli_query($mysqli, $delete_slider);
						}

						Delete('tbl_quotes','user_id='.$del_id);
						mysqli_free_result($res);

						$sql="SELECT * FROM tbl_verify_user WHERE `user_id`='$del_id'";
						$res=mysqli_query($mysqli, $sql);
						while ($row = mysqli_fetch_assoc($res)) {

							if(file_exists('images/documents/'.$row['document'])){
								unlink('images/documents/'.$row['document']);
							}
						}

						Delete('tbl_verify_user','user_id='.$del_id);

						mysqli_free_result($res);

						$sql="SELECT * FROM tbl_users_redeem WHERE `user_id`='$del_id'";
						$res=mysqli_query($mysqli, $sql);
						while ($row = mysqli_fetch_assoc($res)) {

							if(file_exists('images/payment_receipt/'.$row['receipt_img'])){
								unlink('images/payment_receipt/'.$row['receipt_img']);
							}
						}

						Delete('tbl_users_redeem','user_id='.$del_id);

						mysqli_free_result($res);

						$sql="SELECT * FROM tbl_users WHERE `id`='$del_id'";
						$result=mysqli_query($mysqli, $sql);

						$row_user=mysqli_fetch_assoc($result);

						if(file_exists('images/'.$row_user['user_image'])){
							unlink('images/'.$row_user['user_image']);
						}

						Delete('tbl_users','id='.$del_id);

						mysqli_free_result($res);
					}

					$response['status']=1;	
				}

				else if($table=='tbl_payment_mode'){

					$deleteSql="DELETE FROM tbl_payment_mode WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);

					$response['status']=1;	
				}

				else if($table=='tbl_contact_sub'){

					$deleteSql="DELETE FROM tbl_contact_sub WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);

					$response['status']=1;	
				}

				else if($table=='tbl_spinner')
				{

					$deleteSql="DELETE FROM tbl_spinner WHERE `block_id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);

					$response['status']=1;	
				}

				else if($table=='tbl_verify_user')
				{
					$sql="SELECT * FROM tbl_verify_user WHERE id IN ($ids)";
					$result=mysqli_query($mysqli,$sql);
					while ($row = mysqli_fetch_assoc($res))
					{

						if($row['document']!="")
						{
							if(file_exists('images/documents/'.$row['document'])){
								unlink('images/documents/'.$row['document']);
							}
						}
					}

					$deleteSql="DELETE FROM tbl_verify_user WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);

					$response['status']=1;
				}

				else if($table=='tbl_deleted_users')
				{

					$deleteSql="DELETE FROM tbl_deleted_users WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);

					$response['status']=1;	
				}

				else if($table=='tbl_users_redeem')
				{
					$sql="SELECT * FROM tbl_users_redeem WHERE id IN ($ids)";
					$result=mysqli_query($mysqli,$sql);

					while ($row = mysqli_fetch_assoc($res)) 
					{
						if($row['receipt_img']!="")
						{
							if(file_exists('images/payment_receipt/'.$row['receipt_img'])){
								unlink('images/payment_receipt/'.$row['receipt_img']);
							}
						}
					}

					$deleteSql="DELETE FROM tbl_users_redeem WHERE `id` IN ($ids)";
					mysqli_query($mysqli, $deleteSql);

					$response['status']=1;
				}

				if($response['status']==1){
					$_SESSION['msg']="12";
				}
			}

	      	echo json_encode($response);
			break;

		case 'verifyUser':
			
			$verify_user_id=$_POST['verify_user_id'];
			$user_id=$_POST['user_id'];
			$perform=$_POST['perform'];

			$sql="SELECT * FROM tbl_verify_user WHERE id='$verify_user_id'";
			$res=mysqli_query($mysqli, $sql);

			$row=mysqli_fetch_assoc($res);

			if(is_suspend($user_id)==0){

				if($perform=='approve'){

					$content = array("en" => $client_lang['verify_request_approve_msg']);

		            $fields = array(
						'app_id' => ONESIGNAL_APP_ID,                                       
						'included_segments' => array('Subscribed Users'), 
						'data' => array("foo" => "bar","type" => "account_verification","external_link"=>$external_link),
						'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $user_id)),
						'headings'=> array("en" => APP_NAME),
						'contents' => $content
					);

					send_notification($fields);

					$data = array('status'  =>  '1','verify_at'  =>  strtotime(date('d-m-Y h:i:s A')));
				    $update=Update("tbl_verify_user", $data, "WHERE id = '$verify_user_id'");

				    $data = array('is_verified'  =>  '1');
				    $update=Update("tbl_users", $data, "WHERE id = '$user_id'");

				    $_SESSION['msg']="22";

				}
				else if($perform=='reject'){


					$content = array("en" => $client_lang['verify_request_reject_msg']);

		            $fields = array(
						'app_id' => ONESIGNAL_APP_ID,                                       
						'included_segments' => array('Subscribed Users'), 
						'data' => array("foo" => "bar","type" => "account_verification","external_link"=>$external_link),
						'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $user_id)),
						'headings'=> array("en" => APP_NAME),
						'contents' => $content
					);

					send_notification($fields);

					$reason=addslashes(trim($_POST['reject_reason']));

					$data = array('reject_reason'  =>  $reason,'status'  =>  '2','verify_at'  =>  strtotime(date('d-m-Y h:i:s A')));
				    $update=Update("tbl_verify_user", $data, "WHERE id = '$verify_user_id'");

				    $data = array('is_verified'  =>  '2');
				    $update=Update("tbl_users", $data, "WHERE id = '$user_id'");

				    $_SESSION['msg']="23";
				}
			}
			else{
				$_SESSION['msg']="24";
			}
			
			$response['status']=1;
			echo json_encode($response);
			break;

		case 'auto_approve':

			$column='';

			if($_POST['param']=='auto_approve'){
				$auto_approve=$_POST['value'];
				$column='auto_approve';
			}
			else if($_POST['param']=='auto_approve_img'){
				$auto_approve=$_POST['value'];
				$column='auto_approve_img';
			}
			else if($_POST['param']=='auto_approve_gif'){
				$auto_approve=$_POST['value'];
				$column='auto_approve_gif';
			}
			else if($_POST['param']=='auto_approve_quote'){
				$auto_approve=$_POST['value'];
				$column='auto_approve_quote';
			}

			$data = array
			(
				$column => $auto_approve
			);

			$settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
			
			$response['status']=1;
	      	echo json_encode($response);
			break;

		case 'notify_users':

			$id=$_POST['id'];
			$user_id=$_POST['uid'];

			$status_type=trim($_POST['status_type']);

			$followers=array();

	    	switch ($status_type) {
	    		case 'video':
	    			{
	    				$sql="SELECT * FROM tbl_video WHERE `id` IN ('$id')";
						$result=mysqli_query($mysqli,$sql);
						
						$row=mysqli_fetch_assoc($result);

						$img_path=$file_path_img.'images/'.$row['video_thumbnail'];

						$user_name=ucwords(get_user_info($user_id,'name'));

						$content = array("en" => str_replace('###', $user_name, $client_lang['add_video_notify_msg']));

                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

						$res_follower=mysqli_query($mysqli, $sql_follower);

						while ($row_follower=mysqli_fetch_assoc($res_follower)) {
							$followers[]=$row_follower['player_id'];
						}
	    			}
	    			break;

	    		case 'image':
	    			{
	    				$sql="SELECT * FROM tbl_img_status WHERE `id` IN ('$id') AND `status_type`='image'";
						$result=mysqli_query($mysqli,$sql);
						
						$row=mysqli_fetch_assoc($result);

						$img_path=$file_path_img.'images/'.$row['image_file'];

						$user_name=ucwords(get_user_info($user_id,'name'));
						
						$content = array("en" => str_replace('###', $user_name, $client_lang['add_img_notify_msg']));

                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

						$res_follower=mysqli_query($mysqli, $sql_follower);

						$followers=array();

						while ($row_follower=mysqli_fetch_assoc($res_follower)) {
							$followers[]=$row_follower['player_id'];
						}	
	    			}
	    			break;

	    		case 'gif':
	    			{
	    				$sql="SELECT * FROM tbl_img_status WHERE `id` IN ('$id') AND `status_type`='gif'";
						$result=mysqli_query($mysqli,$sql);
						
						$row=mysqli_fetch_assoc($result);

						$img_path=$file_path_img.'images/'.$row['image_file'];

						$user_name=ucwords(get_user_info($user_id,'name'));
						
						$content = array("en" => str_replace('###', $user_name, $client_lang['add_gif_notify_msg']));

                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

						$res_follower=mysqli_query($mysqli, $sql_follower);

						$followers=array();

						while ($row_follower=mysqli_fetch_assoc($res_follower)) {
							$followers[]=$row_follower['player_id'];
						}
	    			}
	    			break;

	    		case 'quote':
	    			{
	    				$sql="SELECT * FROM tbl_quotes WHERE `id` IN ('$id')";
						$result=mysqli_query($mysqli,$sql);
						
						$row=mysqli_fetch_assoc($result);

						$user_name=ucwords(get_user_info($user_id,'name'));
						
						$content = array("en" => str_replace('###', $user_name, $client_lang['add_quote_notify_msg']));

                      	$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

						$res_follower=mysqli_query($mysqli, $sql_follower);

						while ($row_follower=mysqli_fetch_assoc($res_follower)) {
							$followers[]=$row_follower['player_id'];
						}

						if(!empty($followers))
						{
							$fields = array(
					              'app_id' => ONESIGNAL_APP_ID,                                       
					              'include_player_ids' => $followers, 
					              'data' => array("foo" => "bar","type" => "single_status","status_type" => $status_type,"id" => $id,"external_link"=>$external_link),
					              'headings'=> array("en" => APP_NAME),
					              'contents' => $content
					              );

							send_notification($fields);
						}
						$response['status']=1;
	      				echo json_encode($response);
	      				exit;
	    			}
	    			break;

	    		default:
	    			break;
	    	}

	    	if(!empty($followers))
			{
				$fields = array(
		              'app_id' => ONESIGNAL_APP_ID,                                       
		              'include_player_ids' => $followers, 
		              'data' => array("foo" => "bar","type" => "single_status","status_type" => $status_type,"id" => $id,"external_link"=>$external_link),
		              'headings'=> array("en" => APP_NAME),
		              'contents' => $content,
		              'big_picture' =>$img_path
		              );

				send_notification($fields);
			}
			
			$response['status']=1;
	      	echo json_encode($response);
			break;

		case 'account_status':

			$for_action=$_POST['for_action'];
			$user_id=$_POST['id'];

			if($for_action=='suspend'){

				$data = array(
		            'user_id'  => $user_id,
		            'suspended_on'  => strtotime(date('d-m-Y h:i:s A')),
		            'suspension_reason'  => trim($_POST['suspend_reason'])
		        );		

	          	$insert = Insert('tbl_suspend_account',$data);

	          	$data = array('status' => '0');

				$update=Update('tbl_video', $data, "WHERE user_id =".$user_id);
				$update=Update('tbl_img_status', $data, "WHERE user_id =".$user_id);
				$update=Update('tbl_quotes', $data, "WHERE user_id =".$user_id);

				$update=Update('tbl_comments', $data, "WHERE user_id =".$user_id);

	          	$data = array('status' => '2');

				$update=Update('tbl_users', $data, "WHERE id =".$user_id);

				$content = array("en" => $client_lang['account_suspend_msg']);

	            $fields = array(
				  'app_id' => ONESIGNAL_APP_ID,                                       
				  'included_segments' => array('Subscribed Users'),
				  'data' => array("foo" => "bar","type" => "account_status","id" => $user_id,"external_link"=>$external_link),
				  'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $user_id)),
				  'headings'=> array("en" => APP_NAME),
				  'contents' => $content
				);

				send_notification($fields);

	          	$response['status']=1;
			}
			else if($for_action=='active'){

				$data = array(
					'activated_on'  => strtotime(date('d-m-Y h:i:s A')),
		            'status'  => 0
		        );		

				$update=Update('tbl_suspend_account', $data, "WHERE user_id =".$user_id." AND status=1");

	          	$data = array('status' => '1');

				$update=Update('tbl_users', $data, "WHERE id =".$user_id);

				$sql="SELECT post_id FROM tbl_users_rewards_activity WHERE `user_id`='$user_id'";
				$res=mysqli_query($mysqli, $sql);

				while ($row=mysqli_fetch_assoc($res)) {

					$video = "video";
                    $image = "image";
                    $gif = "gif";
                    $quote = "quote";
					$activity = strtolower($row['activity_type']);

					if(strpos($activity, $video) !== false)
                    {
                        $update=Update('tbl_video', $data, "WHERE id =".$row['post_id']);
                    } 
                    else if(strpos($activity, $image) !== false)
                    {
                        $update=Update('tbl_img_status', $data, "WHERE id =".$row['post_id']);
                    } 
                    else if(strpos($activity, $gif) !== false)
                    {
                        $update=Update('tbl_img_status', $data, "WHERE id =".$row['post_id']);
                    } 
                    else if(strpos($activity, $quote) !== false)
                    {
                        $update=Update('tbl_quotes', $data, "WHERE id =".$row['post_id']);
                    }
					
				}

				$update=Update('tbl_comments', $data, "WHERE user_id =".$user_id);

				$content = array("en" => $client_lang['account_activated_msg']);

	            $fields = array(
				  'app_id' => ONESIGNAL_APP_ID,                                       
				  'included_segments' => array('Subscribed Users'),
				  'data' => array("foo" => "bar","type" => "account_status","id" => $user_id,"external_link"=>$external_link),
				  'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $user_id)),
				  'headings'=> array("en" => APP_NAME),
				  'contents' => $content
				);

				send_notification($fields);

	          	$response['status']=1;

			}
			
	      	echo json_encode($response);
			break;

		case 'delete_video':

			$ids=$_POST['id'];

			delete_videos($ids);
			
			$response['status']=1;
	      	echo json_encode($response);
			break;

		case 'delete_img_status':

			$ids=$_POST['id'];

			delete_img_status($ids);
			
			$response['status']=1;
	      	echo json_encode($response);
			break;

		case 'delete_quote':

			$ids=$_POST['id'];

			delete_quotes_status($ids);
			
			$response['status']=1;
	      	echo json_encode($response);
			break;

		default:
			$response['message']='No method available !';
			$response['status']=0;
	      	echo json_encode($response);
			break;
	}

?>