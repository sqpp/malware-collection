
<script type="text/javascript">

function myFunction() {
//	alert("keshav");
var x = document.getElementById("state").value;
//alert(x);
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function(){
if(xmlhttp.readyState==4&&xmlhttp.status==200){
document.getElementById('city').innerHTML = xmlhttp.responseText;}}
xmlhttp.open('GET', 'city.php?state='+x , true);
xmlhttp.send();
}

</script>



<?php include('userHeader.php'); ?>



      <div class="content-wrap">
            <div class="main">
			    <div class="col-md-12">
					<div class="container-fluid">
						<div class="row">
							<div class="page-header">
								<div class="page-title">
									<h1>Website Setting</h1>
								</div>
							</div>
						</div>
						<!-- /# row -->
						<section id="main-content">
							<div class="row">
							   <?php
								//including the database connection file
								include("config.php");
				
								if(isset($_POST['submit'])) { 
									$msg = '';	
									
									$webname= $_POST['webname'];
                                    $weburl = $_POST['weburl'] ;
									$webtitle = $_POST['webtitle'];
									$acname = $_POST['acname'];
                                    $acno = $_POST['acno'] ;
									$ifsc = $_POST['ifsc'];
									$bankname = $_POST['bankname'];
									$upi = $_POST['upi'];
									$paytm = $_POST['paytm'];
                                    $phonepay = $_POST['phonepay'] ;
									$smsapi = $_POST['smsapi'];
									$senderid = $_POST['senderid'];
									$supdiscom = $_POST['supdiscom'] ;
									$discom = $_POST['discom'] ;
									$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["imagefile"]["name"]);
                                    
									//echo 
									
									$q = "";
									$q = "SELECT * FROM setting";
									$r = mysqli_query($connection,$q);
									$rw = mysqli_fetch_assoc($r);
									//$rw['aadharpoint'];
									
									//elseif($rw['pancardpoint']>$pancardpoint || $_SESSION['userid']>1 ){
									//	$msgno = 'You Enter Less Pancard Point to Your Point  .... ';
									//} 
									
									
								



										$query = "UPDATE `setting` SET `webname`='".$webname."',`weburl`='".$weburl."',
										`webtitle`='".$webtitle."',`acname`='".$acname."', `acno`='".$acno."',`ifsc`='".$ifsc."',`bankname`='".$bankname."',
										`upi`='".$upi."',`paytm`='".$paytm."',`phonepay`='".$phonepay."',`smsapi`='".$smsapi."',`senderid`='".$senderid."',`supdiscom`='".$supdiscom."',`discom`='".$discom."',ifile='".$target_file."' WHERE id=1";
										//echo $query;
										$aquery=mysqli_query($connection,$query);
										move_uploaded_file($_FILES["imagefile"]["tmp_name"], $target_file);
										
										$msg = 'User Name Update Successfully.........';
										
									
									?>
									<script>
									setTimeout(function () {
										window.location.href= 'setting.php';
									}, 3000);
									</script>
									<?php
								}
								?>
								
								
								
						
						<!-- /# row -->
						<?php if($msg !='') { ?>
								<div style="width=100%"  class="row cvmsgok"><?php echo $msg; ?></div>
								<?php } elseif($msgno !='') { ?>
								<div style="width=100%"  class="row cvmsgno"><?php echo $msgno; ?></div>
								<?php  } ?>
								<form method="post" enctype="multipart/form-data">
									<div class="row dgnform">
									    <div class="row col-md-12 col-sm-12 col-xs-12">
											<div class="col-md-3 col-sm-3 col-xs-6">
											<?php
												error_reporting(0);
												include("config.php");

												
												$sqla="select * from setting";
												$updt = mysqli_query($connection,$sqla) ;
												$slct = mysqli_fetch_array($updt);
												//$slct = mysqli_fetch_assoc($r);
												//$slct['aadharpoint'];

												?>
												
												
												
                                    
													
												
												</div> 
											</div>
											
											<div class="col-md-12 col-sm-4 col-xs-6">
												<label>WEBSITE NAME</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control"  id="webname" value="<?php echo $slct['webname'];?>" name="webname" placeholder="Website URL/ Link" required>
												<span id="erroruserid" class="error"></span>  
												</div> 
											</div>										
											
															
													
											<div class="col-md-12 col-sm-4 col-xs-6">
												<label>Website Title</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control" id="webtitle" value="<?php echo $slct['webtitle'];?>" name="webtitle" placeholder="Website Title" required>
												<span id="errorusername" class="error"></span>  
												</div>											
											</div>
																
													
											<div class="col-md-12 col-sm-4 col-xs-6">
												<label>Website URL</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control" id="weburl" value="<?php echo $slct['weburl'];?>" name="weburl" placeholder="Website Title" required>
												<span id="errorusername" class="error"></span>  
												</div> </div> 
																</br>	</br></br></br></br></br></br>
																
													
											<div class="col-md-12 col-sm-4 col-xs-6">
												<label>ACOUNT HOLDER NAME</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control" id="acname" value="<?php echo $slct['acname'];?>" name="acname" placeholder="Account Holder Name" required>
												<span id="errorusername" class="error"></span>
												</div> </div> 

                                                 <div class="col-md-12 col-sm-4 col-xs-6">
												<label>ACCOUNT NUMBER</label>
												<div class="form-group">
												<td><input type="text" name="acno"  class="form-control" id="acno" required  value="<?php echo $slct['acno'];?>"></td>
												
												 
												
												</div> 
											</div>
											<div class="col-md-12 col-sm-4 col-xs-6">
												<label>IFSC NO</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control"  id="ifsc" value="<?php echo $slct['ifsc'];?>" name="ifsc" placeholder="IFSC CODE" required>
												<span id="erroruserid" class="error"></span>  
												
												</div> 
																						
										</div>												
												
												<div class="col-md-12 col-sm-4 col-xs-6">
												<label>BANK NAME</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control" id="bankname" value="<?php echo $slct['bankname'];?>" name="bankname" placeholder="BANK NAME" required>
												<span id="errorusername" class="error"></span>
												
												</div> </div>  
												
												<div class="col-md-12 col-sm-4 col-xs-6">
												<label>UPI</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control" id="upi" value="<?php echo $slct['upi'];?>" name="upi" placeholder="UPI ID" required>
												<span id="errorusername" class="error"></span>
											
												</div> </div>  
												
												<div class="col-md-12 col-sm-4 col-xs-6">
												<label>Paytm</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control" id="paytm" value="<?php echo $slct['paytm'];?>" name="paytm" placeholder="PAYTM NO" required>
												<span id="errorusername" class="error"></span>
												
												
												
												
												</div> </div>  
												
												<div class="col-md-12 col-sm-4 col-xs-6">
												<label>PhonePay</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control" id="phonepay" value="<?php echo $slct['phonepay'];?>" name="phonepay" placeholder="Website Title" required>
												<span id="errorusername" class="error"></span>
												
												
												
												</br></br></br>
												</br>
												
												
												</div> </div>  
												
												<div class="col-md-12 col-sm-4 col-xs-6">
												<label>SUPER-DISTRIBUTER COMISSION</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control" readonly id="supdiscom" value="<?php echo $slct['supdiscom'];?>" name="supdiscom" placeholder="MSG91 API" >
												<span id="errorusername" class="error"></span>
												
												
												
												</div> </div>  
												
												<div class="col-md-12 col-sm-4 col-xs-6">
												<label>DISTRIBUTER COMISSION</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control" readonly id="discom" value="<?php echo $slct['discom'];?>" name="discom" placeholder="MSG91 API SENDER ID">
												<span id="errorusername" class="error"></span>
												
												
												
												<br></br></br>
												</br>
												
												
												</div> </div>  
												
												<div class="col-md-12 col-sm-4 col-xs-6">
												<label>MSG91 API</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control" id="smsapi" value="<?php echo $slct['smsapi'];?>" name="smsapi" placeholder="MSG91 API" >
												<span id="errorusername" class="error"></span>
												
												
												
												</div> </div>  
												
												<div class="col-md-12 col-sm-4 col-xs-6">
												<label>MSG91 SENDER ID</label>
												<div class="form-group">              
												<input autocomplete="off" type="text" 
												
												class="form-control" id="senderid" value="<?php echo $slct['senderid'];?>" name="senderid" placeholder="MSG91 API SENDER ID">
												<span id="errorusername" class="error"></span>
												
												
												
												<br></br></br>
												</br>
												
												

												
												
												<div class="col-sm-3">
                                            <label>Select Background Image <br/>
											Recomended size <br/> : width- 1503px  * Height- 809px 
											</label>
                                            <div class="form-group">
											  <input type="file" name="imagefile" class="form-control" id="imgInp" />
                                              <img src="<?php echo $slct['ifile'];?>"   id="blah" width="100px" height="100px" />
                                            </div>
                                        </div>
												
												
												
												
												
												
										</div> 
											</table>											
										</div>										
										<div class="col-md-3 col-sm-4 col-xs-6">
											<label>&nbsp;</label>
											<div class="form-group">              
											   <button type="submit" id="submit" name="submit" class="btn btn-success btn-block">Update</button> 
											</div> 
										</div>
									</div>
								</form>
								
					
			</div>
			
			
			
		
           
								
			
			
			
        </div>
        <script type="text/javascript">
		$(document).ready(function()
				{
					$("#usertype").on('change',function()
					{
						var vals = $(this).val();
						if(vals == 'RETAILER')
						{
						$('#aadharpoint').val(100);	
						}
						else if(vals == 'DISTRIBUTER')
						{
							$('#aadharpoint').val(98);	
						}
						else 
						{
							$('#aadharpoint').val(97);	
						}
					});
				});
            	
            	function validation() {
            		//alert('ok');
            		var userid = document.getElementById('userid').value;
            		if ( userid.trim()  == "" ) {
                         document.getElementById('erroruserid').innerHTML = " **Please Enter Login Name/ID";
                         document.getElementById('userid').style.border = "1px solid red";
                         document.getElementById('userid').focus();
                         return false;
            		}
					var username = document.getElementById('username').value;
            		if ( username.trim()  == "" ) {
                         document.getElementById('errorusername').innerHTML = " **Please Enter User Name";
                         document.getElementById('username').style.border = "1px solid red";
                         document.getElementById('username').focus();
                         return false;
            		}
                    var emailid = document.getElementById('emailid').value;
            		if ( emailid.trim()  == "" ) {
                         document.getElementById('erroremailid').innerHTML = " **Please Enter Email Id";
                         document.getElementById('emailid').style.border = "1px solid red";
                         document.getElementById('emailid').focus();
                         return false;
            		}

					var mobileno = document.getElementById('mobileno').value;
            		if ( mobileno.trim()  == "" ) {
                         document.getElementById('errormobileno').innerHTML = " **Please Enter Mobile No";
                         document.getElementById('mobileno').style.border = "1px solid red";
                         document.getElementById('mobileno').focus();
                         return false;
            		}
					var password = document.getElementById('password').value;
            		if ( password.trim()  == "" ) {
                         document.getElementById('errorpassword').innerHTML = " **Please Enter Password";
                         document.getElementById('password').style.border = "1px solid red";
                         document.getElementById('password').focus();
                         return false;
            		}

					var confirmpassword = document.getElementById('confirmpassword').value;
            		if ( confirmpassword.trim() != password.trim() ) {
                         document.getElementById('errorconfirmpassword').innerHTML = " **Please Enter Correct Confirm Password";
                         document.getElementById('confirmpassword').style.border = "1px solid red";
                         document.getElementById('confirmpassword').focus();
                         return false;
            		}
					var aadharpoint = document.getElementById('aadharpoint').value;
            		if ( aadharpoint.trim()  == "" ) {
                       //  document.getElementById('errorcity').innerHTML = " **Please Enter City";
                         document.getElementById('aadharpoint').style.border = "1px solid red";
                         document.getElementById('aadharpoint').focus();
                         return false;
            		}
			
            	}
            </script>
			
<?php include('userFooter.php'); ?>