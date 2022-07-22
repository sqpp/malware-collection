<?php 
    require_once("../../config/config.php");
    require_once("../../config/function.php");
    require_once('../../class/class.smtp.php');
    require_once('../../class/PHPMailerAutoload.php');
    require_once('../../class/class.phpmailer.php');
    require_once('../../class/Mobile_Detect.php');

    if($_POST['type'] == 'Login' )
    {
        $username = check_string($_POST['username']);
        $password = TypePassword(check_string($_POST['password']));
        if(empty($username))
        {
            msg_error2(lang(10));
        }
        if(!$row = $CMSNT->get_row(" SELECT * FROM `users` WHERE `username` = '$username' "))
        {
            msg_error2(lang(12));
        }
        if(empty($password))
        {
            msg_error2(lang(11));
        }
        if($CMSNT->get_row(" SELECT * FROM `users` WHERE `username` = '$username' AND `banned` = '1' "))
        {
            msg_error2(lang(14).' - Lý do: '.$row['reason_banned']);
        }
        if(!$CMSNT->get_row(" SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password' "))
        {
            msg_error2(lang(13));
        }
        $Mobile_Detect = new Mobile_Detect;

        $CMSNT->update("users", [
            'otp'       => NULL,
            'ip'        => myip(),
            'UserAgent' => $Mobile_Detect->getUserAgent()
        ], " `username` = '$username' ");

        $CMSNT->insert("logs", [
            'username'  => $username,
            'content'   => 'Thực hiện đăng nhập ('.$Mobile_Detect->getUserAgent().' IP '.myip().')',
            'createdate'=> gettime(),
            'time'      => time()
        ]);

        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        msg_success('Đăng nhập thành công ', BASE_URL('Dashbroad'), 0);
    }

    if($_POST['type'] == 'Register' )
    {
        $username = check_string($_POST['username']);
        $password = check_string($_POST['password']);
        $repassword = check_string($_POST['repassword']);
        if(empty($username))
        {
            msg_error2(lang(10));
        }
        if(check_username($username) != True)
        {
            msg_error2(lang(15));
        }
        if(strlen($username) < 5 || strlen($username) > 64)
        {
            msg_error2(lang(16));
        }
        if($CMSNT->get_row(" SELECT * FROM `users` WHERE `username` = '$username' "))
        {
            msg_error2(lang(17));
        }
        if(empty($password))
        {
            msg_error2(lang(11));
        }
        if($password != $repassword)
        {
            msg_error2(lang(134));
        }
        if(strlen($password) < 3)
        {
            msg_error2(lang(18));
        }
        if($CMSNT->num_rows(" SELECT * FROM `users` WHERE `ip` = '".myip()."' ") > 3)
        {
            msg_error2(lang(19));
        }
        $Mobile_Detect = new Mobile_Detect;
        $create = $CMSNT->insert("users", [
            'username'      => $username,
            'password'      => TypePassword($password),
            'token'         => random('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM', 64),
            'money'         => 0,
            'used_money'    => 0,
            'total_money'   => 0,
            'banned'        => 0,
            'ref'           => $_SESSION['ref'],
            'ip'            => myip(),
            'UserAgent'     => $Mobile_Detect->getUserAgent(),
            'time'          => time(),
            'createdate'    => gettime()
        ]);
        if ($create)
        {   
            $_SESSION['username'] = $username;
            msg_success(lang(20), BASE_URL('Dashbroad'), 1000);
        }
        else
        {
            msg_error2(lang(21));
        }
    }


    if($_POST['type'] == 'ForgotPassword' )
    {
        $email = check_string($_POST['email']);
        if(empty($email))
        {
            msg_error2(lang(22));
        }
        if(check_email($email) != True) 
        {
            msg_error2(lang(23));
        }
        $row = $CMSNT->get_row(" SELECT * FROM `users` WHERE `email` = '$email' ");
        if(!$row)
        {
            msg_error2(lang(24));
        }
        $otp = random('0123456789', '6');
        $CMSNT->update("users", array(
            'otp' => $otp
        ), " `id` = '".$row['id']."' " );
        $guitoi = $email;   
        $subject = lang(25);
        $bcc = $CMSNT->site('tenweb');
        $hoten ='Client';
        $noi_dung = '<h3>'.lang(26).'</h3>
        <table>
        <tbody>
        <tr>
        <td style="font-size:20px;">OTP:</td>
        <td><b style="color:blue;font-size:30px;">'.$otp.'</b></td>
        </tr>
        </tbody>
        </table>';
        sendCSM($guitoi, $hoten, $subject, $noi_dung, $bcc);   
        msg_success(lang(27), BASE_URL('Auth/ChangePassword'), 4000);
    }

    if($_POST['type'] == 'ChangePassword')
    {
        $otp = check_string($_POST['otp']);
        $repassword = check_string($_POST['repassword']);
        $password = check_string($_POST['password']);
        if(empty($otp))
        {
            msg_error2("Bạn chưa nhập OTP");
        }
        if(empty($password))
        {
            msg_error2(lang(28));
        }
        if(empty($repassword))
        {
            msg_error2(lang(29));
        }
        if(isset($_SESSION['countVeri']))
        {
            if($_SESSION['countVeri'] >= 3)
            {
                msg_error2("Chức năng này tạm khóa");
            }
        }
        else
        {
            $_SESSION['countVeri'] = 0;
        }
        $row = $CMSNT->get_row(" SELECT * FROM `users` WHERE `otp` = '$otp' ");
        if(!$row)
        {
            $_SESSION['countVeri'] = $_SESSION['countVeri'] + 1;
            msg_error2("OTP không tồn tại trong hệ thống");
        }
        if($password != $repassword)
        {
            msg_error2("Nhập lại mật khẩu không đúng");
        }
        if(strlen($password) < 5)
        {
            msg_error2('Vui lòng nhập mật khẩu có ích nhất 5 ký tự');
        }
        $CMSNT->update("users", [
            'otp' => NULL,
            'password' => TypePassword($password)
        ], " `id` = '".$row['id']."' ");
    
        msg_success2("Mật khẩu của bạn đã được thay đổi thành công !");
    }

    if($_POST['type'] == 'ChangeProfile')
    {
        if(empty($_SESSION['username']))
        {
            msg_error("Vui lòng đăng nhập ", BASE_URL(''), 2000);
        }
        if($CMSNT->site('status_demo') == 'ON')
        {
            msg_error2("Chức năng này không khả dụng trên trang web DEMO!");
        }
        $repassword = check_string($_POST['repassword']);
        $password = check_string($_POST['password']);
        $email = check_string($_POST['email']);
        $phone = check_string($_POST['phone']);
        $fullname = check_string($_POST['fullname']);
        $row = $CMSNT->get_row(" SELECT * FROM `users` WHERE `username` = '".$_SESSION['username']."' ");
        if(!$row)
        {
            msg_error("Vui lòng đăng nhập!", BASE_URL(''), 2000);
        }
        if(check_email($email) != True) 
        {
            msg_error2('Vui lòng nhập định dạng Email hợp lệ.');
        }
        if(check_phone($phone) != True)
        {
            msg_error2('Định dạng số điện thoại không đúng.');
        }
        if(empty($fullname))
        {
            msg_error2("Vui lòng điền Họ và Tên của bạn.");
        }
        if($CMSNT->get_row(" SELECT * FROM `users` WHERE `email` = '$email' AND `username` != '".$getUser['username']."'  "))
        {
            msg_error2('Địa chỉ Email đã tồn tại trong hệ thống.');
        }
        if($password != NULL)
        {
            if(empty($repassword))
            {
                msg_error2("Vui lòng xác minh lại mật khẩu");
            }
            if($password != $repassword)
            {
                msg_error2("Nhập lại mật khẩu không đúng");
            }
            if(strlen($password) < 5)
            {
                msg_error2('Vui lòng nhập mật khẩu có ích nhất 5 ký tự');
            }
            $CMSNT->update("users", [
                'otp' => NULL,
                'password' => TypePassword($password)
            ], " `id` = '".$row['id']."' ");
            /* THÊM NHẬT KÝ */
            $CMSNT->insert("logs", [
                'username'  => $getUser['username'],
                'content'   => 'Thao tác đổi mật khẩu',
                'createdate'=> gettime(),
                'time'      => time()
            ]);
        }
        if($getUser['email'] != $email)
        {
            /* THÊM NHẬT KÝ */
            $CMSNT->insert("logs", [
                'username'  => $getUser['username'],
                'content'   => 'Thao tác đổi địa chỉ Email '.$getUser['email'].' thành '.$email,
                'createdate'=> gettime(),
                'time'      => time()
            ]);
        }
        $CMSNT->update("users", [
            'otp'   => NULL,
            'email' => $email,
            'phone' => $phone,
            'fullname' => $fullname
        ], " `id` = '".$row['id']."' ");

        msg_success2(lang(80));
    }