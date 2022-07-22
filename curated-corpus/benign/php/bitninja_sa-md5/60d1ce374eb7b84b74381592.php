<?php 
ini_set('session.save_path', '/tmp');
session_start();
require 'db.php';
if(isset($_SESSION['username']))
{
    
}
else
{
   ?>
    <script>
        window.location.href="index.php";
    </script>
    <?php 
}
?>
<!DOCTYPE html>
<html>



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>Banner Add/Update</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/favicon.png">

    <!-- plugins css -->
    <link rel="stylesheet" href="assets/vendors/bootstrap/dist/css/bootstrap.css" />
    <link rel="stylesheet" href="assets/vendors/PACE/themes/blue/pace-theme-minimal.css" />
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/css/perfect-scrollbar.min.css" />
     <link rel="stylesheet" href="assets/vendors/datatables/media/css/jquery.dataTables.css" />

    <!-- core css -->
    <link href="assets/css/ei-icon.css" rel="stylesheet">
    <link href="assets/css/themify-icons.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/animate.min.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
</head>

<body>
    <div class="app">
        <div class="layout">
            <!-- Side Nav START -->
            <div class="side-nav">
                <div class="side-nav-inner">
                    <div class="side-nav-logo">
                        <a href="index.php">
                            <div class="logo logo-dark" style="background-image: url('assets/images/logo/logo.png')"></div>
                            <div class="logo logo-white" style="background-image: url('assets/images/logo/logo-white.png')"></div>
                        </a>
                        <div class="mobile-toggle side-nav-toggle">
                            <a href="#">
                                <i class="ti-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                   <?php require 'side.php';?>
                </div>
            </div>
            <!-- Side Nav END -->

            <!-- Page Container START -->
            <div class="page-container">
                <!-- Header START -->
                <div class="header navbar">
                    <?php require 'head.php';?>
                </div>
                <!-- Header END -->

                <!-- Side Panel START -->
                <div class="side-panel">
                    <div class="side-panel-wrapper bg-white">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item active">
                                <a class="nav-link" href="#chat" role="tab" data-toggle="tab">
                                    <span>Chat</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#profile" role="tab" data-toggle="tab">
                                    <span>Profile</span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" href="#todo-list" role="tab" data-toggle="tab">
                                    <span>Todo</span>
                                </a>
                            </li>
                            <li class="panel-close">
                                <a class="side-panel-toggle" href="javascript:void(0);">
                                    <i class="ti-close"></i>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- chat START -->
                            
                            <!-- chat END -->
                            <!-- profile START -->
                           
                            <!-- profile END -->
                            <!-- todo START -->
                            
                        </div>
                    </div>
                </div>
                <!-- Side Panel END -->

                <!-- theme configurator START -->
                <div class="theme-configurator">
                    <div class="configurator-wrapper">
                        <div class="config-header">
                            <h4 class="config-title">Config Panel</h4>
                            <button class="config-close">
                                <i class="ti-close"></i>
                            </button>
                        </div>
                        <div class="config-body">
                            <div class="mrg-btm-30">
                                <p class="lead font-weight-normal">Header Color</p>
                                <div class="theme-colors header-default">
                                    <input type="radio" id="header-default" name="theme">
                                    <label for="header-default"></label>
                                </div>
                                <div class="theme-colors header-primary">
                                    <input type="radio" class="primary" id="header-primary" name="theme">
                                    <label for="header-primary"></label>
                                </div>
                                <div class="theme-colors header-info">
                                    <input type="radio" id="header-info" name="theme">
                                    <label for="header-info"></label>
                                </div>
                                <div class="theme-colors header-success">
                                    <input type="radio" id="header-success" name="theme">
                                    <label for="header-success"></label>
                                </div>
                                <div class="theme-colors header-danger">
                                    <input type="radio" id="header-danger" name="theme">
                                    <label for="header-danger"></label>
                                </div>
                                <div class="theme-colors header-dark">
                                    <input type="radio" id="header-dark" name="theme">
                                    <label for="header-dark"></label>
                                </div>
                            </div>
                            <div class="mrg-btm-30">
                                <p class="lead font-weight-normal">Sidebar Color</p>
                                <div class="theme-colors sidenav-default">
                                    <input type="radio" id="sidenav-default" name="sidenav">
                                    <label for="sidenav-default"></label>
                                </div>
                                <div class="theme-colors side-nav-dark">
                                    <input type="radio" id="side-nav-dark" name="sidenav">
                                    <label for="side-nav-dark"></label>
                                </div>
                            </div>
                            <div class="mrg-btm-30">
                                <p class="lead font-weight-normal no-mrg-btm">RTL</p>
                                <div class="toggle-checkbox checkbox-inline toggle-sm mrg-top-10">
                                    <input type="checkbox" name="rtl-toogle" id="rtl-toogle">
                                    <label for="rtl-toogle"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- theme configurator END -->

                <!-- Theme Toggle Button START -->
                <button class="theme-toggle btn btn-rounded btn-icon">
                    <i class="ti-palette"></i>
                </button>
                <!-- Theme Toggle Button END -->

                <!-- Content Wrapper START -->
                <div class="main-content">
                    <div class="container-fluid">
                        <div class="page-title">
                            <h4>Banner List And Add/Update Banner</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-heading border bottom">
                                        <h4 class="card-title">Banner Add/Update</h4>
                                    </div>
                                    <div class="card-block">
                                        <div class="mrg-top-40">
                                            <div class="row">
                                                <div class="col-md-8 ml-auto mr-auto">
                                                    <?php if(isset($_GET['qid'])) {
                            $udata = mysqli_fetch_assoc(mysqli_query($con,"select * from banner where id=".$_GET['qid'].""));
                            ?>
                             <form method="post" enctype="multipart/form-data">
                                                       
                                                        
                                                         
                                                        
                                                        
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Banner Image</label>
                                                                    <input type="file" name="qduration" class="form-control" required>
                                                                    <img src="<?php echo $udata['banner_img'];?>" width="100px" height="100px">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                           
                                                            <div class="col-md-12 col-xs-12">
                                                                <div class="text-right mrg-top-5">
                                                                    <button type="submit" name="up_quiz" class="btn btn-primary">Update Banner</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    
                            <?php } else {?>
                                                    <form method="post" enctype="multipart/form-data">
                                                                                                                
                                                       
                                                        
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Banner Image</label>
                                                                    <input type="file" name="qduration"  class="form-control" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                           
                                                            <div class="col-md-12 col-xs-12">
                                                                <div class="text-right mrg-top-5">
                                                                    <button type="submit" class="btn btn-primary" name="sav_quiz">Create Banner</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-heading border bottom">
                                        <h4 class="card-title">Banner List</h4>
                                    </div>
                                    <div class="card-block">
                                        <div class="table-overflow">
                                            <table id="dt-opt" class="table table-lg table-hover">
                                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Banner Image</th>
                                        
                                       
                                        <th>Status</th>
                                       
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                               <tbody>
                                    <?php 
                                    $sel_list = mysqli_query($con,"select * from banner");
                                    $i=0;
                                    while($row = mysqli_fetch_assoc($sel_list))
                                    {
                                        $i = $i + 1;
                                    ?>
                                    <tr>
                                       <td><?php echo $i;?></td>
                                       <td><img src="<?php echo $row['banner_img'];?>" width="100px" height="100px"/></td>
                                       
                                       
                                       <td><?php if($row['status'] == 1){?>
                                       <a href="?s=0&pid=<?php echo $row['id'];?>"><button class="btn btn-danger">Deactive</button></a>
                                       <?php }else{?>
                                       <a href="?s=1&pid=<?php echo $row['id'];?>"><button class="btn btn-primary">Active</button></a>
                                       <?php }?></td>
                                        
                                        <td>
                                        <a href="?status=update&qid=<?php echo $row['id'];?>"><button class="btn btn-success">Update</button></a> 
                                        <a href="?statuss=delete&qid=<?php echo $row['id'];?>"><button class="btn btn-danger">Delete</button></a></td>
                                    </tr>
                                    <?php  } ?>
                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php 
    if(isset($_GET['statuss']))
    {
        $qid = $_GET['qid'];
        mysqli_query($con,"delete from banner where id=".$qid."");
        
        ?>
        <script>
            alert('successfully Delete banner');
          // alert('You Use Demo So It Can not Delete Banner!!');
            window.location.href="offer.php";
        </script>
        <?php 
    }
    ?>
    
    
    <?php 
    if(isset($_GET['s']))
    {
        $qid = $_GET['s'];
        mysqli_query($con,"update banner set status=".$qid." where id=".$_GET['pid']."");
        
        ?>
        <script>
            alert('successfully Change Status ');
           // alert('You Use Demo So It Can not Change Status!!');
            window.location.href="offer.php";
        </script>
        <?php 
    }
    ?>
    
                        <?php 
	if(isset($_POST['sav_quiz']))
	{
	    
	    $target_dir = "product/";
	    $names = mysqli_real_escape_string($con,uniqid().$_FILES["qduration"]["name"]);
$target_file = $target_dir . basename($names);
	    
	    
      mysqli_query($con,"insert into banner(`banner_img`)values('".$target_file."')");
     move_uploaded_file($_FILES["qduration"]["tmp_name"], $target_file);
     
      ?>
      <script>
          alert('Banner Save Successfully!!');
         // alert('You Use Demo So It Can not Insert Banner!!');
          window.location.href="offer.php";
      </script>
      <?php 
	}
	?>
	
	<?php 
	if(isset($_POST['up_quiz']))
	{
	   
	   $target_dir = "product/";
	    $names = mysqli_real_escape_string($con,uniqid().$_FILES["qduration"]["name"]);
$target_file = $target_dir . basename($names);
 if($_FILES["qduration"]["name"] != '')
 {
	    mysqli_query($con,"update banner set banner_img='".$target_file."'where id=".$_GET['qid']."");
	     move_uploaded_file($_FILES["qduration"]["tmp_name"], $target_file);
 }
 
	    ?>
	     <script>
          alert('Update Banner  Successfully!!');
         // alert('You Use Demo So It Can not Update Banner!!');
          window.location.href="offer.php";
      </script>
	    <?php 
	}
	?>
                        
                    </div>
                </div>
                <!-- Content Wrapper END -->

                <!-- Footer START -->
                <footer class="content-footer">
                    <div class="footer">
                        <div class="copyright">
                            <span>Copyright © 2020 <b class="text-dark">Fish &amp; Meat</b>. All rights reserved.</span>
                           
                        </div>
                    </div>
                </footer>
                <!-- Footer END -->

            </div>
            <!-- Page Container END -->

        </div>
    </div>

    <script src="assets/js/vendor.js"></script>

    <script src="assets/js/app.min.js"></script>

    <!-- page plugins js -->
    <script src="assets/vendors/jquery-validation/dist/jquery.validate.min.js"></script>

    <!-- page js -->
     <script src="assets/vendors/datatables/media/js/jquery.dataTables.js"></script>

    <!-- page js -->
    <script src="assets/js/table/data-table.js"></script>
    <script src="assets/js/forms/form-validation.js"></script>

</body>


</html>