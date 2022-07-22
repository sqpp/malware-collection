<?php

/*
  API v7.0.2
  Quiz Online - WRTeam.in
  WRTeam Developers
 */
session_start();
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//header("Content-Type: multipart/form-data");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');

include('library/crud.php');
include('library/functions.php');

$db = new Database();
$db->connect();

$fn = new Functions();
$config = $fn->get_configurations();

include_once('library/verify-token.php');

if (isset($config['system_timezone']) && !empty($config['system_timezone'])) {
    date_default_timezone_set($config['system_timezone']);
} else {
    date_default_timezone_set('Asia/Kolkata');
}
if (isset($config['system_timezone_gmt']) && !empty($config['system_timezone_gmt'])) {
    $db->sql("SET `time_zone` = '" . $config['system_timezone_gmt'] . "'");
} else {
    $db->sql("SET `time_zone` = '+05:30'");
}

$db->sql("SET NAMES 'utf8'");
$response = array();
$access_key = "6808";

$toDate = date('Y-m-d');
$toDateTime = date('Y-m-d H:i:s');

/*
  API methods
  ------------------------------------
  1. get_languages()
  2. get_categories_by_language()
  3. get_categories()
  4. get_subcategory_by_maincategory()
  5. get_questions_by_category()
  6. get_questions_by_subcategory()
  7. get_questions_by_level()
  8. get_questions_by_type()
  9. get_questions_for_self_challenge()
  10. get_random_questions()
  11. get_random_questions_for_computer()
  12. report_question()
  13. user_signup()
  14. get_user_by_id()
  15. update_fcm_id()
  16. upload_profile_image()
  17. update_profile()
  18. set_monthly_leaderboard()
  19. get_monthly_leaderboard()
  20. get_datewise_leaderboard()
  21. get_global_leaderboard()
  22. get_system_configurations()
  23. get_about_us()
  24. get_privacy_policy_settings()
  25. get_terms_conditions_settings()
  26. get_instructions()
  27. get_notifications()
  28. set_battle_statistics()
  29. get_battle_statistics()
  30. set_users_statistics()
  31. get_users_statistics()
  32. set_level_data()
  33. get_level_data()
  34. set_bookmark()
  35. get_bookmark()
  36. get_daily_quiz()
  37. get_user_coin_score()
  38. set_user_coin_score()
  39. get_contest()
  40. get_questions_by_contest()
  41. contest_update_score()
  42. get_contest_score()
  43. create_room()
  44. get_question_by_room_id()
  45. destroy_room_by_room_id()
  46. get_public_room()
  47. invite_friend()
  48. get_firebase_settings()

  functions
  ------------------------------------
  1. get_fcm_id($user_id)
  2. checkBattleExists($match_id)
  3. set_monthly_leaderboard($user_id, $score)

 */

// 1. get_languages()
if (isset($_POST['access_key']) && isset($_POST['get_languages']) && $_POST['get_languages'] == 1) {
    /* Parameters to be passed
      access_key:6808
      get_languages:1
      id:1 // {optional}
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $db->escapeString($_POST['id']);
        $sql = "SELECT * FROM `languages` WHERE `status`= 1 AND `id`=" . $id . " ORDER BY id ASC";
    } else {
        $sql = "SELECT * FROM `languages` WHERE `status`= 1 ORDER BY id ASC";
    }
    $db->sql($sql);
    $res = $db->getResult();
    if (!empty($res)) {
        $response['error'] = "false";
        $response['data'] = $res;
    } else {
        $response['error'] = "true";
        $response['message'] = "No data found!";
    }
    print_r(json_encode($response));
}

// 2. get_categories_by_language() - get categories list by language id 
if (isset($_POST['access_key']) && isset($_POST['get_categories_by_language'])) {
    /* Parameters to be passed
      access_key:6808
      get_categories_by_language:1
      language_id:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['language_id']) && !empty($_POST['language_id'])) {
        $language_id = $db->escapeString($_POST['language_id']);
        $sql = "SELECT *,(select count(id) from question where question.category=c.id ) as no_of_que,
                        (SELECT @no_of_subcategories := count(*) from subcategory s WHERE s.maincat_id = c.id and s.status = 1 ) as no_of, 
			(select `language` from `languages` l where l.id = c.language_id ) as language,
			if(@no_of_subcategories = 0, (SELECT @maxlevel := MAX(`level`) from question q WHERE c.id = q.category ),@maxlevel := 0) as `maxlevel` 
			FROM `category` c where `language_id` = " . $language_id . " ORDER By CAST(c.row_order as unsigned) ASC";
        $db->sql($sql);
        $result = $db->getResult();

        if (!empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/category/' . $result[$i]['image'] : '';
                $result[0]['maxlevel'] = ($result[0]['maxlevel'] == '' || $result[0]['maxlevel'] == null ) ? '0' : $result[0]['maxlevel'];
            }
            $response['error'] = "false";
            $response['data'] = $result;
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 3. get_categories()
if (isset($_POST['access_key']) && isset($_POST['get_categories'])) {
    /* Parameters to be passed
      access_key:6808
      get_categories:1
      id:31 //{optional}
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['id'])) {
        $id = $db->escapeString($_POST['id']);
        $sql = "SELECT *,(select count(id) from question where question.category=c.id ) as no_of_que, (SELECT @no_of_subcategories := count(`id`) from subcategory s WHERE s.maincat_id = c.id and s.status = 1 ) as no_of, if(@no_of_subcategories = 0, (SELECT @maxlevel := MAX(`level`+0) from question q WHERE c.id = q.category ),@maxlevel := 0) as `maxlevel` FROM `category` c WHERE c.id = $id ORDER By CAST(c.row_order as unsigned) ASC";
        $db->sql($sql);
        $result = $db->getResult();
        if (!empty($result)) {
            $result[0]['image'] = (!empty($result[0]['image'])) ? DOMAIN_URL . 'images/category/' . $result[0]['image'] : '';
            $result[0]['maxlevel'] = ($result[0]['maxlevel'] == '' || $result[0]['maxlevel'] == null ) ? '0' : $result[0]['maxlevel'];
            $response['error'] = "false";
            $response['data'] = $result[0];
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $sql = "SELECT *,(select count(id) from question where question.category=c.id ) as no_of_que,(SELECT @no_of_subcategories := count(`id`) from subcategory s WHERE s.maincat_id = c.id and s.status = 1 ) as no_of, if(@no_of_subcategories = 0, (SELECT @maxlevel := MAX(`level`+0) from question q WHERE c.id = q.category ),@maxlevel := 0) as `maxlevel` FROM `category` c ORDER By CAST(c.row_order as unsigned) ASC";
        $db->sql($sql);
        $result = $db->getResult();
        if (!empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/category/' . $result[$i]['image'] : '';
                $result[$i]['maxlevel'] = ($result[$i]['maxlevel'] == '' || $result[$i]['maxlevel'] == null ) ? '0' : $result[$i]['maxlevel'];
            }
            $response['error'] = "false";
            $response['data'] = $result;
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    }
    print_r(json_encode($response));
}

// 4. get_subcategory_by_maincategory()
if (isset($_POST['access_key']) && isset($_POST['get_subcategory_by_maincategory'])) {
    /* Parameters to be passed
      access_key:6808
      get_subcategory_by_maincategory:1
      main_id:31
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['main_id'])) {
        $id = $db->escapeString($_POST['main_id']);
        $sql = "SELECT *,(select max(`level` + 0) from question where question.subcategory=subcategory.id ) as maxlevel,(select count(id) from question where question.subcategory=subcategory.id ) as no_of FROM `subcategory` WHERE `maincat_id`='$id' and `status`=1 ORDER BY CAST(row_order as unsigned) ASC";
        $db->sql($sql);
        $result = $db->getResult();

        if (!empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/subcategory/' . $result[$i]['image'] : '';
                $result[$i]['maxlevel'] = ($result[$i]['maxlevel'] == '' || $result[$i]['maxlevel'] == null ) ? '0' : $result[$i]['maxlevel'];
            }
            $response['error'] = "false";
            $response['data'] = $result;
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 5. get_questions_by_category()
if (isset($_POST['access_key']) && isset($_POST['get_questions_by_category'])) {
    /* Parameters to be passed
      access_key:6808
      get_questions_by_category:1
      category:115
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['category'])) {
        $id = $db->escapeString($_POST['category']);
        $sql = "SELECT * FROM `question` WHERE category=" . $id . " ORDER BY id DESC";
        $db->sql($sql);
        $result = $db->getResult();

        if (!empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/questions/' . $result[$i]['image'] : '';
                $result[$i]['optione'] = ($fn->is_option_e_mode_enabled() && $result[$i]['optione'] != null) ? trim($result[$i]['optione']) : '';
                $result[$i]['optiona'] = trim($result[$i]['optiona']);
                $result[$i]['optionb'] = trim($result[$i]['optionb']);
                $result[$i]['optionc'] = trim($result[$i]['optionc']);
                $result[$i]['optiond'] = trim($result[$i]['optiond']);
            }
            $response['error'] = "false";
            $response['data'] = $result;
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 6. get_questions_by_subcategory()
if (isset($_POST['access_key']) && isset($_POST['get_questions_by_subcategory'])) {
    /* Parameters to be passed
      access_key:6808
      get_questions_by_subcategory:1
      subcategory:115
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['subcategory'])) {
        $id = $db->escapeString($_POST['subcategory']);
        $sql = "SELECT * FROM `question` where subcategory=" . $id . " ORDER by RAND()";
        $db->sql($sql);
        $result = $db->getResult();

        if (!empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/questions/' . $result[$i]['image'] : '';
                $result[$i]['optione'] = ($fn->is_option_e_mode_enabled() && $result[$i]['optione'] != null) ? trim($result[$i]['optione']) : '';
                $result[$i]['optiona'] = trim($result[$i]['optiona']);
                $result[$i]['optionb'] = trim($result[$i]['optionb']);
                $result[$i]['optionc'] = trim($result[$i]['optionc']);
                $result[$i]['optiond'] = trim($result[$i]['optiond']);
            }
            $response['error'] = "false";
            $response['data'] = $result;
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 7. get_questions_by_level()
if (isset($_POST['access_key']) && isset($_POST['get_questions_by_level'])) {
    /* Parameters to be passed
      access_key:6808
      get_questions_by_level:1
      level:1
      category:115 {or}
      subcategory:115
      language_id:2   // {optional}
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['level']) && (isset($_POST['category']) || isset($_POST['subcategory']))) {
        $level = $db->escapeString($_POST['level']);
        $language_id = (isset($_POST['language_id']) && is_numeric($_POST['language_id'])) ? $db->escapeString($_POST['language_id']) : '';
        $id = (isset($_POST['category'])) ? $db->escapeString($_POST['category']) : $db->escapeString($_POST['subcategory']);
        $limit = $config['total_question'];

        $sql = "SELECT * FROM `question` WHERE level=" . $level;
        $sql .= (isset($_POST['category'])) ? " and `category`=" . $id : " and `subcategory`=" . $id;
        $sql .= (!empty($language_id)) ? " and `language_id`=" . $language_id : "";
        $sql .= " ORDER BY rand() DESC";
        if ($config['fix_question']) {
            $sql .= " LIMIT 0, " . $limit . "";
        }

        $db->sql($sql);
        $result = $db->getResult();

        if (!empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/questions/' . $result[$i]['image'] : '';
                $result[$i]['optione'] = ($fn->is_option_e_mode_enabled() && $result[$i]['optione'] != null) ? trim($result[$i]['optione']) : '';
                $result[$i]['optiona'] = trim($result[$i]['optiona']);
                $result[$i]['optionb'] = trim($result[$i]['optionb']);
                $result[$i]['optionc'] = trim($result[$i]['optionc']);
                $result[$i]['optiond'] = trim($result[$i]['optiond']);
            }
            $response['error'] = "false";
            $response['data'] = $result;
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Pass all mandatory fields";
    }
    print_r(json_encode($response));
}

// 8. get_questions_by_type()
if (isset($_POST['access_key']) && isset($_POST['get_questions_by_type'])) {
    /* Parameters to be passed
      access_key:6808
      get_questions_by_type:1
      type:1  //1=normal ,2 = true/false
      language_id:2   // {optional}
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['type']) && !empty($_POST['type']) && isset($_POST['limit']) && !empty($_POST['limit'])) {
        $language_id = (isset($_POST['language_id']) && is_numeric($_POST['language_id'])) ? $db->escapeString($_POST['language_id']) : '';
        $type = $db->escapeString($_POST['type']);
        $limit = $db->escapeString($_POST['limit']);
        $sql = "SELECT * FROM `question` where question_type=" . $type;
        $sql .= (!empty($language_id)) ? " and `language_id`=" . $language_id : "";
        $sql .= " ORDER BY rand() DESC";
        $sql .= " LIMIT 0, " . $limit . "";
        $db->sql($sql);
        $result = $db->getResult();

        if (!empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/questions/' . $result[$i]['image'] : '';
                $result[$i]['optione'] = ($fn->is_option_e_mode_enabled() && $result[$i]['optione'] != null) ? trim($result[$i]['optione']) : '';
                $result[$i]['optiona'] = trim($result[$i]['optiona']);
                $result[$i]['optionb'] = trim($result[$i]['optionb']);
                $result[$i]['optionc'] = trim($result[$i]['optionc']);
                $result[$i]['optiond'] = trim($result[$i]['optiond']);
            }
            $response['error'] = "false";
            $response['data'] = $result;
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 9. get_questions_for_self_challenge()
if (isset($_POST['access_key']) && isset($_POST['get_questions_for_self_challenge'])) {
    /* Parameters to be passed
      access_key:6808
      get_questions_for_self_challenge:1
      category:115 {or}
      subcategory:115
      limit:10
      language_id:2   // {optional}
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['limit']) && (isset($_POST['category']) || isset($_POST['subcategory']))) {
        $limit = $db->escapeString($_POST['limit']);

        $language_id = (isset($_POST['language_id']) && is_numeric($_POST['language_id'])) ? $db->escapeString($_POST['language_id']) : '';
        $id = (isset($_POST['category'])) ? $db->escapeString($_POST['category']) : $db->escapeString($_POST['subcategory']);

        $sql = "SELECT * FROM `question` ";
        $sql .= (isset($_POST['category'])) ? " WHERE `category`=" . $id : " WHERE `subcategory`=" . $id;
        $sql .= (!empty($language_id)) ? " AND `language_id`=" . $language_id : "";
        $sql .= " ORDER BY rand() DESC LIMIT 0, $limit";

        $db->sql($sql);
        $result = $db->getResult();

        if (!empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/questions/' . $result[$i]['image'] : '';
                $result[$i]['optione'] = ($fn->is_option_e_mode_enabled() && $result[$i]['optione'] != null) ? trim($result[$i]['optione']) : '';
                $result[$i]['optiona'] = trim($result[$i]['optiona']);
                $result[$i]['optionb'] = trim($result[$i]['optionb']);
                $result[$i]['optionc'] = trim($result[$i]['optionc']);
                $result[$i]['optiond'] = trim($result[$i]['optiond']);
            }
            $response['error'] = "false";
            $response['data'] = $result;
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please fill all the data and submit!";
    }
    print_r(json_encode($response));
}

// 10. get_random_questions()
if (isset($_POST['access_key']) && isset($_POST['get_random_questions'])) {
    /* Parameters to be passed
      access_key:6808
      get_random_questions:1
      match_id:your_match_id
      destroy_match:0 / 1
      language_id:2   //{optional}
      category:2
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }

    $match_id = $db->escapeString($_POST['match_id']);

    if (isset($_POST['destroy_match']) && $_POST['destroy_match'] == 1) {
        $sql = "DELETE FROM `battle_questions` WHERE `match_id` = '" . $match_id . "'";
        $db->sql($sql);
        $response['error'] = "false";
        $response['message'] = "Battle destroyed successfully";
        print_r(json_encode($response));
        return false;
        exit();
    }

    /* delete old data automatically */
    $sql = "DELETE FROM `battle_questions` WHERE date_created < ('" . $toDate . "')";
    $db->sql($sql);

    $language_id = (isset($_POST['language_id']) && is_numeric($_POST['language_id'])) ? $db->escapeString($_POST['language_id']) : '';

    if (isset($_POST['category']) && !empty($_POST['category'])) {
        $category = $db->escapeString($_POST['category']);
    } else {
        $category = '';
    }
    if (!checkBattleExists($match_id)) {
        /* if match does not exist read and store the questions */

        $sql = "SELECT * FROM `question` ";
        $sql .= (!empty($language_id)) ? " WHERE `language_id` = $language_id " : "";
        $sql .= (!empty($language_id)) ? ((!empty($category)) ? " AND `category`='" . $category . "' " : "") : ((!empty($category)) ? " WHERE `category`='" . $category . "' " : "" );
        $sql .= " ORDER BY RAND() LIMIT 0,10";
        $db->sql($sql);
        $res = $db->getResult();

        if (empty($res)) {
            $response['error'] = "true";
            $response['message'] = "No questions found to compete with each other!";
        } else {
            $questions = $db->escapeString(json_encode($res));
            $sql = "INSERT INTO `battle_questions` (`match_id`, `questions`) VALUES ('$match_id','$questions')";
            $db->sql($sql);

            foreach ($res as $row) {
                $row['image'] = (!empty($row['image'])) ? DOMAIN_URL . 'images/questions/' . $row['image'] : '';
                $row['optione'] = ($fn->is_option_e_mode_enabled() && $row['optione'] != null) ? $row['optione'] : '';
                $temp[] = $row;
            }
            $res = $temp;
            $response['error'] = "false";
            $response['message'] = "Data sent to devices via FCM 1";
            $response['data'] = $res;
            $data['data'] = $res;
        }
    } else {
        /* read the questions and send it. */
        $sql = "SELECT * FROM `battle_questions` WHERE `match_id` = '" . $match_id . "'";

        $db->sql($sql);
        $res = $db->getResult();

        $res = json_decode($res[0]['questions'], 1);
        foreach ($res as $row) {
            $row['image'] = (!empty($row['image'])) ? DOMAIN_URL . 'images/questions/' . $row['image'] : '';
            $row['optione'] = ($fn->is_option_e_mode_enabled() && $row['optione'] != null) ? $row['optione'] : '';
            $temp[] = $row;
        }
        $res[0]['questions'] = json_encode($temp);
        $response['error'] = "false";
        $response['message'] = "Data sent to devices via FCM";
        $response['data'] = json_decode($res[0]['questions']);
        $data['data'] = json_decode($res[0]['questions']);
    }
    print_r(json_encode($response));
}

// 11. get_random_questions_for_computer()
if (isset($_POST['access_key']) && isset($_POST['get_random_questions_for_computer'])) {
    /* Parameters to be passed
      access_key:6808
      get_random_questions_for_computer:1
      language_id:2   //{optional}
      category:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    /* if match does not exist read and store the questions */
    $language_id = (isset($_POST['language_id']) && !empty($_POST['language_id']) && is_numeric($_POST['language_id'])) ? $db->escapeString($_POST['language_id']) : '';

    if (isset($_POST['category']) && !empty($_POST['category'])) {
        $category = $db->escapeString($_POST['category']);
    } else {
        $category = '';
    }

    $sql = "SELECT * FROM `question` ";
    $sql .= (!empty($language_id)) ? " where `language_id` = $language_id " : "";
    $sql .= (!empty($language_id)) ? ((!empty($category)) ? " AND `category`='" . $category . "' " : "") : ((!empty($category)) ? " WHERE `category`='" . $category . "' " : "" );
    $sql .= " ORDER BY RAND() LIMIT 0,10";
    $db->sql($sql);
    $res = $db->getResult();

    if (empty($res)) {
        $response['error'] = "true";
        $response['message'] = "No questions found to compete with each other!";
    } else {
        $tempRow = array();
        foreach ($res as $row) {
            $tempRow['id'] = $row['id'];
            $tempRow['category'] = $row['category'];
            $tempRow['subcategory'] = $row['subcategory'];

            $tempRow['image'] = (!empty($row['image'])) ? DOMAIN_URL . 'images/questions/' . $row['image'] : '';
            $tempRow['question'] = $row['question'];
            $tempRow['question_type'] = $row['question_type'];
            $tempRow['optiona'] = $row['optiona'];
            $tempRow['optionb'] = $row['optionb'];
            $tempRow['optionc'] = $row['optionc'];
            $tempRow['optiond'] = $row['optiond'];
            $tempRow['optione'] = ($fn->is_option_e_mode_enabled() && $row['optione'] != null) ? $row['optione'] : '';
            $tempRow['answer'] = $row['answer'];
            $tempRow['level'] = $row['level'];
            $tempRow['note'] = $row['note'];
            $newresult[] = $tempRow;
        }
        $response['error'] = "false";
        $response['message'] = "Data sent to devices via FCM 1";
        $response['data'] = $newresult;
    }
    print_r(json_encode($response));
}

// 12. report_question()
if (isset($_POST['report_question']) && isset($_POST['access_key'])) {
    /* Parameters to be passed
      access_key:6808
      report_question:1
      question_id:115
      message: Any reporting message
      user_id:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['question_id']) && isset($_POST['user_id']) && isset($_POST['message'])) {
        $user_id = $db->escapeString($_POST['user_id']);
        $question_id = $db->escapeString($_POST['question_id']);
        $message = $db->escapeString(htmlspecialchars($_POST['message']));
        $data = array(
            'question_id' => $question_id,
            'user_id' => $user_id,
            'message' => $message,
            'date' => date("Y-m-d H:i:s")
        );
        $db->insert('question_reports', $data);  // Table name, column names and respective values
        $res = $db->getResult();

        $response['error'] = false;
        $response['message'] = "Report submitted successfully";
        $response['id'] = $res[0];
    } else {
        $response['error'] = true;
        $response['message'] = "Please fill all the data and submit!";
    }
    print_r(json_encode($response));
}

// 13. user_signup()
if (isset($_POST['access_key']) && isset($_POST['user_signup'])) {
    /* 	Parameters to be passed
      access_key:6808
      user_signup:1
      firebase_id : mf5FQ7MtNwdguEDMlTLNarkj4AZ2 //Firebase ID
      name:Jaydeep Goswami
      email:jaydeepjgiri@yahoo.com
      profile:Image URL
      mobile:7894561230
      type: email / gmail / fb
      fcm_id: xyz123654
      refer_code:xyz123654
      friends_code:xyz123654
      ip_address: 191.1.0.4
      status:1   // 1 - Active & 0 Deactive
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['type']) && isset($_POST['firebase_id']) && ($_POST['firebase_id'] != 'null') && ($_POST['firebase_id'] != 'NULL')) {

        $firebase_id = $db->escapeString($_POST['firebase_id']);
        $type = $db->escapeString($_POST['type']);

        $email = (isset($_POST['email'])) ? $db->escapeString(htmlspecialchars($_POST['email'])) : '';
        $name = (isset($_POST['name'])) ? $db->escapeString(htmlspecialchars($_POST['name'])) : '';
        $mobile = (isset($_POST['mobile'])) ? $db->escapeString(htmlspecialchars($_POST['mobile'])) : '';
        $profile = (isset($_POST['profile'])) ? $db->escapeString($_POST['profile']) : '';
        $ip_address = (isset($_POST['ip_address'])) ? $db->escapeString($_POST['ip_address']) : '';
        $fcm_id = (isset($_POST['fcm_id'])) ? $db->escapeString($_POST['fcm_id']) : '';
        $refer_code = (isset($_POST['refer_code'])) ? $db->escapeString(htmlspecialchars($_POST['refer_code'])) : '';
        $friends_code = (isset($_POST['friends_code'])) ? $db->escapeString(htmlspecialchars($_POST['friends_code'])) : '';
        $points = '0';
        $status = '1';

        if (!empty($friends_code)) {
            $code = $fn->valid_friends_refer_code($friends_code);
            if (!$code['is_valid']) {
                $friends_code = '';
            }
        }
        $sql = "SELECT * FROM users WHERE firebase_id='$firebase_id'";
        $db->sql($sql);
        $res = $db->getResult();
        if (!empty($res)) {
            $user_id = $res[0]['id'];

            $friends_code_is_used = $fn->check_friends_code_is_used_by_user($user_id);
            if (!($friends_code_is_used['is_used']) && $friends_code != '') {
                /* give coins to both the users 50 & 100 for each */
                $sql = "UPDATE `users` SET `friends_code`='" . $friends_code . "', `coins` = `coins` + " . $config['refer_coin'] . "  WHERE id = " . $res[0]['id'];
                $db->sql($sql);
                $resf = $db->getResult();
                $credited = $fn->credit_coins_to_friends_code($friends_code);
            }
            if (!empty($fcm_id)) {
                $sql = " UPDATE `users` SET fcm_id='" . $fcm_id . "' WHERE `id` = " . $res[0]['id'];
                $db->sql($sql);
            }
            if (!$fn->is_refer_code_set($user_id) && !empty($refer_code)) {
                $sql = " UPDATE `users` SET refer_code='" . $refer_code . "' WHERE `id` = " . $res[0]['id'];
                $db->sql($sql);
            }

            foreach ($res as $row) {
                if (filter_var($row['profile'], FILTER_VALIDATE_URL) === FALSE) {
                    // Not a valid URL. Its a image only or empty
                    $tempRow['profile'] = (!empty($row['profile'])) ? DOMAIN_URL . 'uploads/profile/' . $row['profile'] : '';
                } else {
                    /* if it is a ur than just pass url as it is */
                    $tempRow['profile'] = $row['profile'];
                }

                $tempRow['user_id'] = $row['id'];
                $tempRow['firebase_id'] = $row['firebase_id'];
                $tempRow['name'] = $row['name'];
                $tempRow['email'] = $row['email'];
                $tempRow['mobile'] = $row['mobile'];
                $tempRow['type'] = $row['type'];
                $tempRow['fcm_id'] = $row['fcm_id'];
                $tempRow['refer_code'] = $row['refer_code'];
                $tempRow['coins'] = $row['coins'];
                $tempRow['ip_address'] = $row['ip_address'];
                $tempRow['status'] = $row['status'];
                $tempRow['date_registered'] = $row['date_registered'];
                $newresult[] = $tempRow;
            }
            $response['error'] = "false";
            $response['message'] = "Successfully logged in";
            $response['data'] = $newresult[0];
        } else {
            $data = array(
                'firebase_id' => $firebase_id,
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'type' => $type,
                'profile' => $profile,
                'fcm_id' => $fcm_id,
                'refer_code' => $refer_code,
                'friends_code' => $friends_code,
                'coins' => '0',
                'ip_address' => $ip_address,
                'status' => $status
            );
            $sql = $db->insert('users', $data);
            $res = $db->getResult();

            $data = array(
                'user_id' => "$res[0]",
                'firebase_id' => $firebase_id,
                'name' => $name,
                'email' => $email,
                'profile' => $profile,
                'mobile' => $mobile,
                'fcm_id' => $fcm_id,
                'refer_code' => $refer_code,
                'coins' => '0',
                'type' => $type,
                'ip_address' => $ip_address,
                'status' => $status
            );

            if ($friends_code != '') {
                $data['coins'] = $config['refer_coin'];
                $sql = "UPDATE `users` SET `coins` = `coins` + " . $config['refer_coin'] . "  WHERE `id` = " . $res[0];
                $db->sql($sql);
                $credited = $fn->credit_coins_to_friends_code($friends_code);
            }

            $response['error'] = "false";
            $response['message'] = "User Registered successfully";
            $response['data'] = $data;
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 14. get_user_by_id()
if (isset($_POST['access_key']) && isset($_POST['get_user_by_id'])) {
    /* Parameters to be passed
      access_key:6808
      get_user_by_id:1
      id:31
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['id'])) {
        $id = $db->escapeString($_POST['id']);
        $sql = "SELECT * FROM `users` WHERE id = $id ";
        $db->sql($sql);
        $result = $db->getResult();

        $sql = "SELECT r.score,r.user_rank FROM (SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT user_id, sum(score) score  FROM monthly_leaderboard m join users u on u.id = m.user_id GROUP BY user_id ) s, (SELECT @user_rank := 0) init ORDER BY score DESC ) r INNER join users u on u.id = r.user_id WHERE r.user_id =" . $id;
        $db->sql($sql);
        $my_rank = $db->getResult();

        if (!empty($result)) {
            if (filter_var($result[0]['profile'], FILTER_VALIDATE_URL) === FALSE) {
                // Not a valid URL. Its a image only or empty
                $result[0]['profile'] = (!empty($result[0]['profile'])) ? DOMAIN_URL . 'uploads/profile/' . $result[0]['profile'] : '';
            } else {
                /* if it is a ur than just pass url as it is */
                $result[0]['profile'] = $result[0]['profile'];
            }
            $result[0]['all_time_score'] = (isset($my_rank[0]['score'])) ? $my_rank[0]['score'] : "0";
            $result[0]['all_time_rank'] = (isset($my_rank[0]['user_rank'])) ? $my_rank[0]['user_rank'] : "0";

            $response['error'] = "false";
            $response['data'] = $result[0];
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please Pass all the fields!";
    }
    print_r(json_encode($response));
}

// 15. update_fcm_id()
if (isset($_POST['access_key']) && isset($_POST['update_fcm_id'])) {
    /* Parameters to be passed
      access_key:6808
      update_fcm_id:1
      user_id:1
      fcm_id:xyzCode
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['fcm_id']) && isset($_POST['user_id']) && !empty($_POST['user_id']) && !empty($_POST['fcm_id'])) {
        $fcm_id = $db->escapeString($_POST['fcm_id']);
        $id = $db->escapeString($_POST['user_id']);

        $sql = "UPDATE `users` SET `fcm_id`='" . $fcm_id . "' WHERE `id`='" . $id . "'";
        $db->sql($sql);
        $response['error'] = "false";
        $response['message'] = " FCM updated successfully";
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 16. upload_profile_image()
if (isset($_POST['access_key']) && isset($_POST['upload_profile_image'])) {
    /* Parameters to be passed
      access_key:6808
      upload_profile_image:1
      user_id:37
      image: image file
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['user_id'])) {
        // Path to move uploaded files
        $target_path = "uploads/profile/";
        // Folder create if not exists
        if (!is_dir($target_path)) {
            mkdir($target_path, 0777, true); /* 3rd parameter is required in recursive mode */
        }
        $id = $db->escapeString($_POST['user_id']);
        $old_profile = '';

        $sql = "select `profile` from `users` where id = " . $id;
        $db->sql($sql);
        $res = $db->getResult();

        if (!empty($res) && isset($res[0]['profile'])) {
            if (filter_var($res[0]['profile'], FILTER_VALIDATE_URL) === FALSE) {
                // Not a valid URL. Its an image only 
                $old_profile = (!empty($res[0]['profile'])) ? $target_path . '' . $res[0]['profile'] : '';
            }
        }

        // final file url that is being uploaded
        $file_upload_url = $target_path;

        if (isset($_FILES['image']['name'])) {
            $allowedExts = array("gif", "jpeg", "jpg", "png", "JPEG", "JPG", "PNG");
            ;
            $extension = pathinfo($_FILES["image"]["name"])['extension'];
            if (!(in_array($extension, $allowedExts))) {
                $response['error'] = "true";
                $response['message'] = 'Image type is invalid';
                echo json_encode($response);
                return false;
            }
            $filename = microtime(true) . '.' . strtolower($extension);
            $target_path = $target_path . $filename;

            try {
                // Throws exception incase file is not being moved
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                    // make error flag true
                    $response['error'] = "true";
                    $response['message'] = 'Could not move the file!';
                }
                $sql = "UPDATE `users` SET `profile`='" . $filename . "' WHERE `id`=" . $id . "";
                $db->sql($sql);
                if (!empty($old_profile) && file_exists($old_profile)) {
                    unlink($old_profile);
                }

                // File successfully uploaded
                $response['error'] = "false";
                $response['message'] = 'File uploaded successfully!';
                $response['file_path'] = DOMAIN_URL . $file_upload_url . $filename;
            } catch (Exception $e) {
                // Exception occurred. Make error flag true
                $response['error'] = "true";
                $response['message'] = $e->getMessage();
            }
        } else {
            // File parameter is missing
            $response['error'] = "true";
            $response['message'] = 'Not received any file!';
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 17. update_profile()
if (isset($_POST['access_key']) && isset($_POST['update_profile'])) {
    /* Parameters to be passed
      access_key:6808
      update_profile:1
      user_id:1
      email:jaydeepjgiri@yahoo.com
      name:Jaydeep Goswami
      mobile:7894561230
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['name'])) {

        $id = $db->escapeString($_POST['user_id']);
        $name = $db->escapeString(htmlspecialchars($_POST['name']));

        $sql = "UPDATE `users` SET `name`='" . $name . "'";
        $sql .= (isset($_POST['mobile']) && !empty($_POST['mobile'])) ? " ,`mobile`='" . htmlspecialchars($_POST['mobile']) . "'" : "";
        $sql .= (isset($_POST['email']) && !empty($_POST['email'])) ? " ,`email`='" . htmlspecialchars($_POST['email']) . "'" : "";
        $sql .= " WHERE `id`='" . $id . "'";
        $db->sql($sql);

        $response['error'] = "false";
        $response['message'] = "Profile updated successfully";
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 18. set_monthly_leaderboard()
if (isset($_POST['access_key']) && isset($_POST['set_monthly_leaderboard'])) {
    /* Parameters to be passed
      access_key:6808
      set_monthly_leaderboard:1
      user_id:10
      score:100
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (!empty($_POST['access_key']) && !empty($_POST['user_id']) && isset($_POST['score']) && $_POST['score'] != '') {
        $user_id = $db->escapeString($_POST['user_id']);
        $score = $db->escapeString($_POST['score']);

        set_monthly_leaderboard($user_id, $score);

        $response['error'] = "false";
        $response['message'] = "successfully update score";
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 19. get_monthly_leaderboard()
if (isset($_POST['access_key']) && isset($_POST['get_monthly_leaderboard'])) {
    /* Parameters to be passed
      access_key:6808
      get_monthly_leaderboard:1
      date:2019-02-01		// use date format = YYYY-MM-DD
      limit:10            // {optional} - Number of records per page
      offset:0            // {optional} - starting position
      user_id:54 			// for get current user rank (optional) (login user_id)
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (empty($_POST['date']) || !isset($_POST['date'])) {
        $response['error'] = "true";
        $response['message'] = "Please fill all the data and submit!";
        print_r(json_encode($response));
        return false;
    }

    $offset = (isset($_POST['offset']) && !empty($_POST['offset']) && is_numeric($_POST['offset'])) ? $db->escapeString($_POST['offset']) : 0;
    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit'])) ? $db->escapeString($_POST['limit']) : 25;

    $date = $db->escapeString($_POST['date']);

    /* get the total no of records */
    $sql = "SELECT COUNT(m.id) as `total` FROM `monthly_leaderboard` m JOIN users ON users.id = m.user_id WHERE ( MONTH( m.date_created ) = MONTH('" . $date . "') AND YEAR( m.date_created ) = YEAR('" . $date . "') ) ORDER BY m.score DESC";
    $db->sql($sql);
    $total = $db->getResult();

    $sql = "SELECT r.*,u.email,u.name,u.profile FROM (
        SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM 
        ( SELECT user_id, sum(score) score FROM monthly_leaderboard m join users u on u.id = m.user_id
         WHERE ( MONTH( m.date_created ) = month('" . $date . "') AND YEAR( m.date_created ) = year('" . $date . "') )
         GROUP BY user_id) s,
        (SELECT @user_rank := 0) init ORDER BY score DESC
    ) r 
    INNER join users u on u.id = r.user_id ORDER BY r.user_rank ASC LIMIT $offset,$limit";
    $db->sql($sql);
    $res = $db->getResult();

    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $user_id = $db->escapeString($_POST['user_id']);
        $sql = "SELECT r.*,u.email,u.name,u.profile FROM (
        SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM 
        ( SELECT user_id, sum(score) score FROM monthly_leaderboard m join users u on u.id = m.user_id
         WHERE ( MONTH( m.date_created ) = month('" . $date . "') AND YEAR( m.date_created ) = year('" . $date . "') )
         GROUP BY user_id) s,
        (SELECT @user_rank := 0) init ORDER BY score DESC
    ) r 
    INNER join users u on u.id = r.user_id WHERE user_id =" . $user_id . " ORDER BY r.user_rank ASC LIMIT $offset,$limit";
        $db->sql($sql);
        $my_rank = $db->getResult();
        if (!empty($my_rank)) {
            if (filter_var($my_rank[0]['profile'], FILTER_VALIDATE_URL) === FALSE) {
                // Not a valid URL. Its a image only or empty
                $my_rank[0]['profile'] = (!empty($my_rank[0]['profile'])) ? DOMAIN_URL . 'uploads/profile/' . $my_rank[0]['profile'] : '';
            }
            $user_rank['my_rank'] = $my_rank[0];
            array_unshift($res, $user_rank);
        } else {
            $my_rank = array(
                'id' => $user_id,
                'user_rank' => 0
            );
            $user_rank['my_rank'] = $my_rank;
            array_unshift($res, $user_rank);
        }
    }

    if (!empty($res)) {
        foreach ($res as $row) {
            if (isset($row['profile'])) {
                if (filter_var($row['profile'], FILTER_VALIDATE_URL) === FALSE) {
                    // Not a valid URL. Its a image only or empty
                    $row['profile'] = (!empty($row['profile'])) ? DOMAIN_URL . 'uploads/profile/' . $row['profile'] : '';
                }
            }
            $tempRow[] = $row;
        }
        $response['error'] = "false";
        $response['total'] = $total[0]['total'];
        $response['data'] = $tempRow;
    } else {
        $response['error'] = "true";
        $response['message'] = "Data not found";
    }
    print_r(json_encode($response));
}

// 20. get_datewise_leaderboard()
if (isset($_POST['access_key']) && isset($_POST['get_datewise_leaderboard'])) {
    /* Parameters to be passed
      access_key:6808
      get_datewise_leaderboard:1
      from:2019-06-01		// use date format = YYYY-MM-DD
      to:2019-06-07		// use date format = YYYY-MM-DD
      offset:0        // {optional} - Starting position
      limit:20        // {optional} - number of records per page
      user_id:25			// to get current user's rank (optional) ( login user_id )
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }

    if ((empty($_POST['from']) || !isset($_POST['from'])) || ( empty($_POST['to']) || !isset($_POST['to']))) {
        $response['error'] = "true";
        $response['message'] = "Please fill all the data and submit!";
        print_r(json_encode($response));
        return false;
    }

    $from = $db->escapeString($_POST['from']);
    $to = $db->escapeString($_POST['to']);

    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit'])) ? $db->escapeString($_POST['limit']) : 25;
    $offset = (isset($_POST['offset']) && !empty($_POST['offset']) && is_numeric($_POST['offset'])) ? $db->escapeString($_POST['offset']) : 0;

    /* get the total no of records */
    $sql = "SELECT COUNT(d.id) as `total` FROM `daily_leaderboard` d JOIN users ON users.id = d.user_id where (DATE(`date_created`) BETWEEN date('" . $from . "') and date('" . $to . "')) ORDER BY score DESC";
    $db->sql($sql);
    $total = $db->getResult();

    $sql = "SELECT r.*,u.email,u.name,u.profile FROM ( SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT user_id, score FROM daily_leaderboard d join users u on u.id = d.user_id WHERE ((DATE(d.date_created) BETWEEN date('" . $from . "') and date('" . $to . "')))) s, (SELECT @user_rank := 0) init ORDER BY score DESC ) r INNER join users u on u.id = r.user_id ORDER BY r.user_rank ASC LIMIT $offset,$limit";
    $db->sql($sql);
    $res = $db->getResult();

    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $user_id = $db->escapeString($_POST['user_id']);

        $sql = "SELECT r.*,u.email,u.name,u.profile FROM ( SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT user_id, score FROM daily_leaderboard d join users u on u.id = d.user_id WHERE ((DATE(d.date_created) BETWEEN date('" . $from . "') and date('" . $to . "')))) s, (SELECT @user_rank := 0) init ORDER BY score DESC ) r INNER join users u on u.id = r.user_id WHERE user_id =" . $user_id . "";
        $db->sql($sql);
        $my_rank = $db->getResult();
        if (!empty($my_rank)) {
            if (filter_var($my_rank[0]['profile'], FILTER_VALIDATE_URL) === FALSE) {
                // Not a valid URL. Its a image only or empty
                $my_rank[0]['profile'] = (!empty($my_rank[0]['profile'])) ? DOMAIN_URL . 'uploads/profile/' . $my_rank[0]['profile'] : '';
            }
            $user_rank['my_rank'] = $my_rank[0];
            array_unshift($res, $user_rank);
        } else {
            $my_rank = array(
                'id' => $user_id,
                'user_rank' => 0
            );
            $user_rank['my_rank'] = $my_rank;
            array_unshift($res, $user_rank);
        }
    }

    if (!empty($res)) {
        foreach ($res as $row) {
            if (isset($row['profile'])) {
                if (filter_var($row['profile'], FILTER_VALIDATE_URL) === FALSE) {
                    // Not a valid URL. Its a image only or empty
                    $row['profile'] = (!empty($row['profile'])) ? DOMAIN_URL . 'uploads/profile/' . $row['profile'] : '';
                }
            }
            $tempRow[] = $row;
        }
        $response['error'] = "false";
        $response['total'] = $total[0]['total'];
        $response['data'] = $tempRow;
    } else {
        $response['error'] = "true";
        $response['message'] = "Data not found";
    }
    print_r(json_encode($response));
}

// 21. get_global_leaderboard()
if (isset($_POST['access_key']) && isset($_POST['get_global_leaderboard'])) {
    /* Parameters to be passed
      access_key:6808
      get_global_leaderboard:1
      offset:0        // {optional} - Starting position
      limit:20        // {optional} - number of records per page
      user_id:25		// to get current user's rank (optional) ( login user_id )
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }

    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit'])) ? $db->escapeString($_POST['limit']) : 25;
    $offset = (isset($_POST['offset']) && !empty($_POST['offset']) && is_numeric($_POST['offset'])) ? $db->escapeString($_POST['offset']) : 0;

    /* get the total no of records */
    //$sql = "SELECT COUNT(m.id) as `total` FROM `monthly_leaderboard` m ";
    $sql = "SELECT COUNT(DISTINCT m.user_id) as `total` FROM `monthly_leaderboard` m JOIN users u ON u.id=m.user_id";
    $db->sql($sql);
    $total = $db->getResult();

    $sql = "SELECT r.*,u.email,u.name,u.profile FROM ( SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT user_id, sum(score) score FROM monthly_leaderboard m join users u on u.id = m.user_id GROUP BY user_id) s, (SELECT @user_rank := 0) init ORDER BY score DESC) r INNER join users u on u.id = r.user_id ORDER BY r.user_rank ASC LIMIT $offset,$limit";
    $db->sql($sql);
    $res = $db->getResult();

    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $user_id = $db->escapeString($_POST['user_id']);

        $sql = "SELECT r.*,u.email,u.name,u.profile FROM (SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT user_id, sum(score) score FROM monthly_leaderboard m join users u on u.id = m.user_id GROUP BY user_id ) s, (SELECT @user_rank := 0) init ORDER BY score DESC ) r INNER join users u on u.id = r.user_id WHERE r.user_id =" . $user_id;
        $db->sql($sql);
        $my_rank = $db->getResult();
        if (!empty($my_rank)) {
            if (filter_var($my_rank[0]['profile'], FILTER_VALIDATE_URL) === FALSE) {
                // Not a valid URL. Its a image only or empty
                $my_rank[0]['profile'] = (!empty($my_rank[0]['profile'])) ? DOMAIN_URL . 'uploads/profile/' . $my_rank[0]['profile'] : '';
            }

            $user_rank['my_rank'] = $my_rank[0];
            array_unshift($res, $user_rank);
        } else {
            $my_rank = array(
                'id' => $user_id,
                'user_rank' => 0,
            );
            $user_rank['my_rank'] = $my_rank;
            array_unshift($res, $user_rank);
        }
    }

    if (!empty($res)) {
        foreach ($res as $row) {
            if (isset($row['profile'])) {
                if (filter_var($row['profile'], FILTER_VALIDATE_URL) === FALSE) {
                    // Not a valid URL. Its a image only or empty
                    $row['profile'] = (!empty($row['profile'])) ? DOMAIN_URL . 'uploads/profile/' . $row['profile'] : '';
                }
            }
            $tempRow[] = $row;
        }
        $response['error'] = "false";
        $response['total'] = $total[0]['total'];
        $response['data'] = $tempRow;
    } else {
        $response['error'] = "true";
        $response['message'] = "Data not found";
    }
    print_r(json_encode($response));
}

// 22. get_system_configurations()
if (isset($_POST['access_key']) && isset($_POST['get_system_configurations'])) {
    /* Parameters to be passed
      access_key:6808
      get_system_configurations:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (!empty($config)) {
        $response['error'] = "false";
        $response['data'] = $config;
    } else {
        $response['error'] = "true";
        $response['message'] = "No configurations found yet!";
    }
    print_r(json_encode($response));
}

// 23. get_about_us()
if (isset($_POST['access_key']) && isset($_POST['get_about_us']) && $_POST['get_about_us'] == 1) {
    /* Parameters to be passed
      access_key:6808
      get_about_us:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }

    if (!empty($_POST['access_key'])) {
        $sql = "SELECT * FROM `settings` WHERE type='about_us'";
        $db->sql($sql);
        $res = $db->getResult();
        if (!empty($res)) {
            $response['error'] = "false";
            $response['data'] = $res[0]['message'];
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 24. get_privacy_policy_settings()
if (isset($_POST['access_key']) && isset($_POST['privacy_policy_settings']) && $_POST['privacy_policy_settings'] == 1) {
    /* Parameters to be passed
      access_key:6808
      privacy_policy_settings:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (!empty($_POST['access_key'])) {
        $sql = "SELECT * FROM `settings` WHERE type='privacy_policy'";
        $db->sql($sql);
        $res = $db->getResult();
        if (!empty($res)) {
            $response['error'] = "false";
            $response['data'] = $res[0]['message'];
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 25. get_terms_conditions_settings()
if (isset($_POST['access_key']) && isset($_POST['get_terms_conditions_settings']) && $_POST['get_terms_conditions_settings'] == 1) {
    /* Parameters to be passed
      access_key:6808
      get_terms_conditions_settings:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (!empty($_POST['access_key'])) {
        $sql = "SELECT * FROM `settings` WHERE type='update_terms'";
        $db->sql($sql);
        $res = $db->getResult();
        if (!empty($res)) {
            $response['error'] = "false";
            $response['data'] = $res[0]['message'];
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 26. get_instructions()
if (isset($_POST['access_key']) && isset($_POST['get_instructions']) && $_POST['get_instructions'] == 1) {
    /* Parameters to be passed
      access_key:6808
      get_instructions:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (!empty($_POST['access_key'])) {
        $sql = "SELECT * FROM `settings` WHERE type='instructions'";
        $db->sql($sql);
        $res = $db->getResult();
        if (!empty($res)) {
            $response['error'] = "false";
            $response['data'] = $res[0]['message'];
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 27. get_notifications()
if (isset($_POST['access_key']) && isset($_POST['get_notifications'])) {
    /* Parameters to be passed
      access_key:6808
      get_notifications:1
      sort:id / users / type // {optional}
      order:DESC / ASC // {optional}
      offset:0    // {optional} - Starting position
      limit:20    // {optional} - number of records per page
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }

    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit'])) ? $db->escapeString($_POST['limit']) : 10;
    $offset = (isset($_POST['offset']) && !empty($_POST['offset']) && is_numeric($_POST['offset'])) ? $db->escapeString($_POST['offset']) : 0;

    $sort = (isset($_POST['sort']) && !empty($_POST['sort'])) ? $db->escapeString($_POST['sort']) : 'id';
    $order = (isset($_POST['order']) && !empty($_POST['order'])) ? $db->escapeString($_POST['order']) : 'DESC';

    $sql = "SELECT * FROM `notifications` m where users = 'all' ORDER BY $sort $order limit $offset,$limit";
    $db->sql($sql);
    $result = $db->getResult();
    if (!empty($result)) {
        for ($i = 0; $i < count($result); $i++) {
            if (filter_var($result[$i]['image'], FILTER_VALIDATE_URL) === FALSE) {
                /* Not a valid URL. Its a image only or empty */
                $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/notifications/' . $result[$i]['image'] : '';
            } else {
                /* if it is a ur than just pass url as it is */
                $result[$i]['image'] = $result[$i]['image'];
            }
        }
        $response['error'] = "false";
        $response['data'] = $result;
    } else {
        $response['error'] = "true";
        $response['message'] = "No notifications to read.";
    }
    print_r(json_encode($response));
}

// 28. set_battle_statistics()
if (isset($_POST['access_key']) && isset($_POST['set_battle_statistics'])) {
    /* Parameters to be passed
      access_key:6808
      set_battle_statistics:1
      user_id1:709
      user_id2:710
      winner_id:710
      is_drawn:0 / 1 (0->no_drawn,1->drawn)
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['user_id1']) && isset($_POST['user_id2']) && isset($_POST['winner_id'])) {
        $user_id1 = $db->escapeString($_POST['user_id1']);
        $user_id2 = $db->escapeString($_POST['user_id2']);
        $winner_id = $db->escapeString($_POST['winner_id']);
        $is_drawn = $db->escapeString($_POST['is_drawn']);

        $sql = "INSERT INTO `battle_statistics` (`user_id1`,`user_id2`,`is_drawn`,`winner_id`) VALUES ('" . $user_id1 . "','" . $user_id2 . "','" . $is_drawn . "','" . $winner_id . "')";
        $db->sql($sql);
        $result = $db->getResult();

        $response['error'] = "false";
        $response['message'] = " Insert successfully";
    } else {
        $response['error'] = "true";
        $response['message'] = "Please fill all the data and submit!";
    }
    print_r(json_encode($response));
}

// 29. get_battle_statistics()
if (isset($_POST['access_key']) && isset($_POST['get_battle_statistics'])) {
    /* Parameters to be passed
      access_key:6808
      get_battle_statistics:1
      user_id:12
      sort:id / is_drawn / winner_id // {optional}
      order:DESC / ASC // {optional}
      offset:0    // {optional} - Starting position
      limit:20    // {optional} - number of records per page
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }

    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit'])) ? $db->escapeString($_POST['limit']) : 5;
    $offset = (isset($_POST['offset']) && !empty($_POST['offset']) && is_numeric($_POST['offset'])) ? $db->escapeString($_POST['offset']) : 0;

    $sort = (isset($_POST['sort']) && !empty($_POST['sort'])) ? $db->escapeString($_POST['sort']) : 'id';
    $order = (isset($_POST['order']) && !empty($_POST['order'])) ? $db->escapeString($_POST['order']) : 'DESC';

    if (isset($_POST['user_id'])) {
        $user_id = $db->escapeString($_POST['user_id']);
        $sql = "SELECT 
		    (SELECT COUNT(*) FROM (SELECT DISTINCT `date_created` from `battle_statistics` WHERE winner_id = $user_id)as w ) AS Victories,
		    (SELECT COUNT(*) FROM (SELECT DISTINCT `date_created` from `battle_statistics` WHERE (user_id1= $user_id || user_id2= $user_id)AND is_drawn=1)as d) AS Drawn,
		    (SELECT COUNT(*) FROM (SELECT DISTINCT `date_created` from `battle_statistics` WHERE (user_id1= $user_id || user_id2= $user_id) AND winner_id != $user_id and is_drawn = 0)as l )AS Loose";

        $db->sql($sql);
        $result = $db->getResult();
        $response['myreport'] = $result;

        $matches = $temp = array();
        $sql = "SELECT *,
		    (select `name` from users u WHERE u.id = m.user_id1 ) as user_1,
		    (select `name` from users u WHERE u.id = m.user_id2 ) as user_2, 
		    (select `profile` from users u WHERE u.id = m.user_id1 ) as user_profile1, 
		    (select `profile` from users u WHERE u.id = m.user_id2 ) as user_profile2 
		    FROM `battle_statistics` m where user_id1 = $user_id or user_id2 = $user_id GROUP BY `date_created` ORDER BY $sort $order limit $offset,$limit";

        $db->sql($sql);
        $result = $db->getResult();
        if (!empty($result)) {
            foreach ($result as $row) {
                $temp['opponent_id'] = ($row['user_id1'] == $user_id) ? $row['user_id2'] : $row['user_id1'];
                $temp['opponent_name'] = ($row['user_id1'] == $user_id) ? $row['user_2'] : $row['user_1'];
                $temp['opponent_profile'] = ($row['user_id1'] == $user_id) ? $row['user_profile2'] : $row['user_profile1'];
                if (!empty($temp['opponent_profile']) || $temp['opponent_profile'] != null) {
                    if (filter_var($temp['opponent_profile'], FILTER_VALIDATE_URL) === FALSE) {
                        // Not a valid URL. Its a image only or empty
                        $temp['opponent_profile'] = (!empty($temp['opponent_profile'])) ? DOMAIN_URL . 'uploads/profile/' . $temp['opponent_profile'] : '';
                    } else {
                        /* if it is a ur than just pass url as it is */
                        $temp['opponent_profile'] = $temp['opponent_profile'];
                    }
                }

                if ($row['is_drawn'] == 1) {
                    $temp['mystatus'] = "Draw";
                } else {
                    $temp['mystatus'] = ($row['winner_id'] == $user_id) ? "Won" : "Lost";
                }
                $temp['date_created'] = $row['date_created'];
                $matches[] = $temp;
            }
            $response['error'] = "false";
            $response['data'] = $matches;
        } else {
            $response['error'] = "true";
            $response['message'] = "No matches played. Play the match now";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 30. set_users_statistics()
if (isset($_POST['access_key']) && isset($_POST['set_users_statistics'])) {
    /* Parameters to be passed
      access_key:6808
      set_users_statistics:1
      user_id:10
      questions_answered:100
      correct_answers:10
      category_id:1 //(id of category which user played)
      ratio: 50 // (In percenatge)
      coins:20 // {optional}
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (!empty($_POST['access_key']) && !empty($_POST['user_id']) && isset($_POST['category_id']) && isset($_POST['questions_answered']) && isset($_POST['correct_answers']) && $_POST['ratio'] != "") {

        $user_id = $db->escapeString($_POST['user_id']);
        $questions_answered = $db->escapeString($_POST['questions_answered']);
        $correct_answers = $db->escapeString($_POST['correct_answers']);
        $category_id = $db->escapeString($_POST['category_id']);
        $ratio = $db->escapeString($_POST['ratio']);

        // update users coins if set
        if (isset($_POST['coins']) && $_POST['coins'] != '' && is_numeric($_POST['coins'])) {
            $coins = $db->escapeString($_POST['coins']);
            $sql = "UPDATE `users` SET `coins` = $coins  WHERE id = " . $user_id;
            $db->sql($sql);
        }

        $sql = "SELECT * FROM `users_statistics` WHERE `user_id`=" . $user_id . "";
        $db->sql($sql);
        $result1 = $db->getResult();

        if (!empty($result1)) {
            $qa = $result1[0]['questions_answered'];
            $ca = $result1[0]['correct_answers'];
            $sc = $result1[0]['strong_category'];
            $r1 = $result1[0]['ratio1'];
            $wc = $result1[0]['weak_category'];
            $r2 = $result1[0]['ratio2'];
            $bp = $result1[0]['best_position'];

            $sql1 = "SELECT r.* FROM "
                    . "(SELECT s.*, @user_rank := @user_rank + 1 user_rank  FROM "
                    . "(SELECT user_id, sum(score) score FROM monthly_leaderboard m GROUP BY user_id ) s, "
                    . "(SELECT @user_rank := 0) init ORDER BY score DESC ) r  "
                    . "INNER join users u on u.id = r.user_id WHERE r.user_id =" . $user_id;
            $db->sql($sql1);
            $my_rank = $db->getResult();
            $rank1 = $my_rank[0]['user_rank'];
            if ($rank1 < $bp || $bp == 0) {
                $bp = $rank1;
                $sql = "UPDATE `users_statistics` SET `best_position`= '" . $bp . "' WHERE user_id=" . $user_id;
                $db->sql($sql);
            }

            if ($ratio > 50) {
                /* update strong category */
                /* when ratio is > 50 he is strong in this particular category */
                $sql = "UPDATE `users_statistics` SET `questions_answered`= `questions_answered` + '" . $questions_answered . "', `correct_answers`= `correct_answers` + '" . $correct_answers . "',";
                $sql .= ( $ratio > $r1 || $sc == 0 ) ? "`strong_category`= '" . $category_id . "', `ratio1`= '" . $ratio . "', " : "";
                $sql .= ( $wc == $category_id ) ? "`weak_category`= '0', " : "";
                $sql .= "`best_position`= '" . $bp . "' WHERE user_id=" . $user_id;
                $db->sql($sql);

                $response['error'] = "false";
                $response['message'] = "Strong Updated successfully";
            } else {
                /* update weak category */
                /* when ratio is < 50 he is weak in this particular category */
                $sql = "UPDATE `users_statistics` SET `questions_answered`= `questions_answered` + '" . $questions_answered . "', `correct_answers`= `correct_answers` + '" . $correct_answers . "',";
                $sql .= ( $ratio < $r2 || $wc == 0 ) ? "`weak_category`= '" . $category_id . "',`ratio2`= '" . $ratio . "'," : "";
                $sql .= ( $sc == $category_id ) ? "`strong_category`= '0', " : "";
                $sql .= " `best_position`= '" . $bp . "' WHERE user_id=" . $user_id;
                $db->sql($sql);
                $response['error'] = "false";
                $response['message'] = "Weak Updated successfully";
            }
        } else {
            if ($ratio > 50) {
                $sql = "INSERT INTO `users_statistics` (`user_id`, `questions_answered`,`correct_answers`, `strong_category`, `ratio1`, `weak_category`, `ratio2`, `best_position`) VALUES ('" . $user_id . "','" . $questions_answered . "','" . $correct_answers . "','" . $category_id . "','" . $ratio . "','0','0','0')";
                $db->sql($sql);
                $response['error'] = "false";
                $response['message'] = "Strong inserted successfully";
            } else {
                $sql = "INSERT INTO `users_statistics` (`user_id`, `questions_answered`,`correct_answers`, `strong_category`, `ratio1`, `weak_category`, `ratio2`, `best_position`) VALUES ('" . $user_id . "','" . $questions_answered . "','" . $correct_answers . "','0','0','" . $category_id . "','" . $ratio . "','0')";
                $db->sql($sql);
                $response['error'] = "false";
                $response['message'] = "Weak inserted successfully";
            }
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 31. get_users_statistics()
if (isset($_POST['access_key']) && isset($_POST['get_users_statistics'])) {
    /* Parameters to be passed
      access_key:6808
      get_users_statistics:1
      user_id:31
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['user_id'])) {
        $user_id = $db->escapeString($_POST['user_id']);
        $sql = "SELECT us.*,u.name,u.profile,(SELECT category_name FROM category c WHERE c.id=us.strong_category) as strong_category, (SELECT category_name FROM category c WHERE c.id=us.weak_category) as weak_category FROM `users_statistics` us LEFT JOIN users u on u.id = us.user_id WHERE `user_id`=" . $user_id;
        $db->sql($sql);
        $result = $db->getResult();

        if (!empty($result)) {
            if ($result[0]['strong_category'] == null) {
                $result[0]['strong_category'] = "0";
            }
            if ($result[0]['weak_category'] == null) {
                $result[0]['weak_category'] = "0";
            }
            if (filter_var($result[0]['profile'], FILTER_VALIDATE_URL) === FALSE) {
                // Not a valid URL. Its a image only or empty
                $result[0]['profile'] = (!empty($result[0]['profile'])) ? DOMAIN_URL . 'uploads/profile/' . $result[0]['profile'] : '';
            } else {
                /* if it is a ur than just pass url as it is */
                $result[0]['profile'] = $result[0]['profile'];
            }
            $response['error'] = "false";
            $response['data'] = $result[0];
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please Pass all the fields!";
    }
    print_r(json_encode($response));
}

// 32. set_level_data()
if (isset($_POST['access_key']) && isset($_POST['set_level_data'])) {
    /* Parameters to be passed
      access_key:6808
      set_level_data:1
      user_id:10
      category:1
      subcategory:2
      level:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (!empty($_POST['access_key']) && !empty($_POST['user_id']) && !empty($_POST['category']) && !empty($_POST['level'])) {
        $user_id = $db->escapeString($_POST['user_id']);
        $category = $db->escapeString($_POST['category']);
        $subcategory = (empty($_POST['subcategory'])) ? 0 : $db->escapeString($_POST['subcategory']);
        $level = $db->escapeString($_POST['level']);

        $sql = "SELECT * FROM tbl_level WHERE user_id='$user_id' AND category='$category' AND subcategory='$subcategory'";
        $db->sql($sql);
        $result = $db->getResult();

        if (!empty($result)) {
            $sql = "UPDATE `tbl_level` SET `level`='$level' WHERE user_id='$user_id' AND category='$category' AND subcategory='$subcategory'";
            $db->sql($sql);
            $response['error'] = "false";
            $response['message'] = "successfully update data";
        } else {
            $sql = 'INSERT INTO `tbl_level` (`user_id`, `category`, `subcategory`, `level`) VALUES (' . $user_id . ',' . $category . ',' . $subcategory . ',' . $level . ')';
            $db->sql($sql);
            $response['error'] = "false";
            $response['message'] = "successfully insert data";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 33. get_level_data()
if (isset($_POST['access_key']) && isset($_POST['get_level_data'])) {
    /* Parameters to be passed
      access_key:6808
      get_level_data:1
      user_id:10
      category:1
      subcategory:2
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (!empty($_POST['access_key']) && !empty($_POST['user_id']) && !empty($_POST['category'])) {
        $user_id = $db->escapeString($_POST['user_id']);
        $category = $db->escapeString($_POST['category']);
        $subcategory = (empty($_POST['subcategory'])) ? 0 : $db->escapeString($_POST['subcategory']);

        $sql = "SELECT level FROM tbl_level WHERE user_id='$user_id' AND category='$category' AND subcategory='$subcategory'";
        $db->sql($sql);
        $result = $db->getResult();

        if (!empty($result)) {
            $response['error'] = "false";
            $response['data'] = $result[0];
        } else {
            $res = array("level" => "1");
            $response['error'] = "false";
            $response['data'] = $res;
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 34. set_bookmark()
if (isset($_POST['access_key']) && isset($_POST['set_bookmark'])) {
    /* Parameters to be passed
      access_key:6808
      set_bookmark:1
      user_id:2
      question_id:11
      status:1   //1-bookmark,0-unmark
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['question_id']) && isset($_POST['status']) && !empty($_POST['question_id']) && $_POST['status'] != '') {
        $user_id = $db->escapeString($_POST['user_id']);
        $question_id = $db->escapeString($_POST['question_id']);
        $status = $db->escapeString($_POST['status']);

        if ($status == '1') {
            $sql = 'INSERT INTO `tbl_bookmark` (`user_id`, `question_id`, `status`) VALUES (' . $user_id . ',' . $question_id . ',' . $status . ')';
            $db->sql($sql);
        } else {
            $sql = 'DELETE FROM `tbl_bookmark` WHERE `user_id`=' . $user_id . ' AND `question_id`=' . $question_id;
            $db->sql($sql);
        }
        $response['error'] = "false";
        $response['message'] = "successfully insert data";
    } else {
        $response['error'] = "true";
        $response['message'] = "Please fill all the data and submit!";
    }
    print_r(json_encode($response));
}

// 35. get_bookmark()
if (isset($_POST['access_key']) && isset($_POST['get_bookmark'])) {
    /* Parameters to be passed
      access_key:6808
      get_bookmark:1
      user_id:2
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $user_id = $db->escapeString($_POST['user_id']);

        $sql = "SELECT * FROM tbl_bookmark b JOIN question q ON q.id=b.question_id  where user_id=" . $user_id . " ORDER BY b.id DESC";
        $db->sql($sql);
        $result = $db->getResult();

        if (!empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/questions/' . $result[$i]['image'] : '';
                $result[$i]['optione'] = ($fn->is_option_e_mode_enabled() && $result[$i]['optione'] != null) ? trim($result[$i]['optione']) : '';
                $result[$i]['optiona'] = trim($result[$i]['optiona']);
                $result[$i]['optionb'] = trim($result[$i]['optionb']);
                $result[$i]['optionc'] = trim($result[$i]['optionc']);
                $result[$i]['optiond'] = trim($result[$i]['optiond']);
            }
            $response['error'] = "false";
            $response['data'] = $result;
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please fill all the data and submit!";
    }
    print_r(json_encode($response));
}

// 36. get_daily_quiz() 
if (isset($_POST['access_key']) && isset($_POST['get_daily_quiz'])) {
    /* Parameters to be passed
      access_key:6808
      get_daily_quiz:1
      language_id:2   // {optional}
     */
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['get_daily_quiz']) && isset($_POST['user_id']) && !empty($_POST['user_id'])) {

        $user_id = $db->escapeString($_POST['user_id']);
        $questions = $response = array();
        $language_id = (isset($_POST['language_id']) && is_numeric($_POST['language_id'])) ? $db->escapeString($_POST['language_id']) : '0';

        $sql1 = "SELECT * from daily_quiz_user WHERE date='$toDate' AND user_id=" . $user_id . "";
        $db->sql($sql1);
        $res1 = $db->getResult();
        if (empty($res1)) {
            $sql = "SELECT * from daily_quiz WHERE date_published='$toDate' AND `language_id`=" . $language_id . "";
            $db->sql($sql);
            $res = $db->getResult();

//        if (empty($res)) {
//            $sql = "SELECT * from daily_quiz WHERE `language_id`=" . $language_id . " ORDER BY date_published DESC LIMIT 0,1";
//            $db->sql($sql);
//            $res = $db->getResult();
//        }
            if (!empty($res)) {
                $sql2 = "SELECT * from daily_quiz_user WHERE user_id=" . $user_id . "";
                $db->sql($sql2);
                $res2 = $db->getResult();
                if (!empty($res2)) {
                    $sql3 = "UPDATE `daily_quiz_user` SET `date` = '" . $toDate . "' WHERE user_id=" . $user_id;
                } else {
                    $sql3 = 'INSERT INTO `daily_quiz_user` (`user_id`, `date`) VALUES (' . $user_id . ',"' . $toDate . '")';
                }
                $db->sql($sql3);

                $questions = $res[0]['questions_id'];

                $sql = "SELECT * FROM `question` WHERE `id` IN (" . $questions . ") ORDER BY FIELD(id," . $questions . ")";
                $db->sql($sql);
                $result = $db->getResult();

                if (!empty($result)) {
                    for ($i = 0; $i < count($result); $i++) {
                        $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/questions/' . $result[$i]['image'] : '';
                        $result[$i]['optione'] = ($fn->is_option_e_mode_enabled() && $result[$i]['optione'] != null) ? trim($result[$i]['optione']) : '';
                        $result[$i]['optiona'] = trim($result[$i]['optiona']);
                        $result[$i]['optionb'] = trim($result[$i]['optionb']);
                        $result[$i]['optionc'] = trim($result[$i]['optionc']);
                        $result[$i]['optiond'] = trim($result[$i]['optiond']);
                    }
                    $response['error'] = "false";
                    $response['data'] = $result;
                } else {
                    $response['error'] = "true";
                    $response['message'] = "No data found!";
                }
            } else {
                $response['error'] = "true";
                $response['message'] = "No data found!";
            }
        } else {
            $response['error'] = "true";
            $response['message'] = "daily quiz already played";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 37. get_user_coin_score() - get user details
if (isset($_POST['access_key']) && isset($_POST['get_user_coin_score'])) {
    /* Parameters to be passed
      access_key:6808
      get_user_coin_score:1
      user_id:31
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['user_id'])) {
        $user_id = $db->escapeString($_POST['user_id']);
        $sql = "SELECT coins FROM `users` WHERE id = $user_id ";
        $db->sql($sql);
        $result = $db->getResult();

        $sql1 = "SELECT r.score,r.user_rank FROM (SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT user_id, sum(score) score FROM monthly_leaderboard m GROUP BY user_id ) s, (SELECT @user_rank := 0) init ORDER BY score DESC ) r INNER join users u on u.id = r.user_id WHERE r.user_id =" . $user_id;
        $db->sql($sql1);
        $my_rank = $db->getResult();

        if (!empty($result)) {
            $result[0]['score'] = (isset($my_rank[0]['score'])) ? $my_rank[0]['score'] : 0;
            $response['error'] = "false";
            $response['data'] = $result[0];
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please Pass all the fields!";
    }
    print_r(json_encode($response));
}

// 38. set_user_coin_score() - get user details
if (isset($_POST['access_key']) && isset($_POST['set_user_coin_score'])) {
    /* Parameters to be passed
      access_key:6808
      set_user_coin_score:1
      user_id:31
      coin:10
      score:2      //if deduct coin than set with minus sign -2
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['user_id']) && isset($_POST['coins']) && !empty($_POST['user_id'])) {
        $user_id = $db->escapeString($_POST['user_id']);
        $coins = $db->escapeString($_POST['coins']);

        if (isset($_POST['score']) && !empty($_POST['score'])) {
            $sql = "SELECT id, user_id FROM `monthly_leaderboard` WHERE `user_id`=" . $user_id . " and month(monthly_leaderboard.date_created) = month('" . $toDate . "') and year(monthly_leaderboard.date_created) = year('" . $toDate . "') ";
            $db->sql($sql);
            $result = $db->getResult();
            $score = $db->escapeString($_POST['score']);
            set_monthly_leaderboard($user_id, $score);
        }

        if (isset($_POST['coins']) && !empty($_POST['coins'])) {
            $sql1 = "UPDATE `users` SET  `coins` = `coins` + " . $coins . "  WHERE id = " . $user_id;
            $db->sql($sql1);
        }

        $sql = "SELECT coins FROM `users` WHERE id = $user_id ";
        $db->sql($sql);
        $result = $db->getResult();

        $sql1 = "SELECT r.score,r.user_rank FROM (SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT user_id, sum(score) score FROM monthly_leaderboard m GROUP BY user_id ) s, (SELECT @user_rank := 0) init ORDER BY score DESC ) r INNER join users u on u.id = r.user_id WHERE r.user_id =" . $user_id;
        $db->sql($sql1);
        $my_rank = $db->getResult();

        if (!empty($result)) {
            $result[0]['score'] = (isset($my_rank[0]['score'])) ? $my_rank[0]['score'] : 0;
            $response['error'] = "false";
            $response['message'] = "successfully insert record";
            $response['data'] = $result[0];
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please Pass all the fields!";
    }
    print_r(json_encode($response));
}

// 39. get_contest()
if (isset($_POST['access_key']) && isset($_POST['get_contest'])) {
    /* Parameters to be passed
      access_key:6808
      get_contest:1
      user_id:59
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $user_id = $db->escapeString($_POST['user_id']);

        /* selecting live quiz ids */
        $sql = "SELECT id FROM `contest` where ('$toDate') between CAST(`start_date` AS DATE) and CAST(`end_date` AS DATE)";
        $db->sql($sql);
        $result = $db->getResult();
        // print_r($result);
        $live_type_ids = $past_type_ids = '';
        if (!empty($result)) {
            foreach ($result as $type_id) {
                $live_type_ids .= $type_id['id'] . ', ';
            }
            $live_type_ids = rtrim($live_type_ids, ', ');

            /* getting past quiz ids & its data which user has played */
            $sql = "SELECT `contest_id` FROM `contest_leaderboard` WHERE `contest_id` in ($live_type_ids) and `user_id` = $user_id ORDER BY `id` DESC";
            $db->sql($sql);
            $result = $db->getResult();
            // print_r($result);
            if (!empty($result)) {
                foreach ($result as $type_id) {
                    $past_type_ids .= $type_id['contest_id'] . ', ';
                }
                $past_type_ids = rtrim($past_type_ids, ', ');

                $sql = "SELECT *, (select SUM(points) FROM contest_prize WHERE contest_prize.contest_id=contest.id) as points, (select count(contest_id) FROM contest_prize WHERE contest_prize.contest_id=contest.id) as top_users,(SELECT COUNT(*) from contest_leaderboard where contest_leaderboard.contest_id = contest.id ) as `participants` FROM `contest` WHERE `id` in ($past_type_ids) ORDER BY `id` DESC";
                $db->sql($sql);
                $past_result = $db->getResult();
                unset($result);
                foreach ($past_result as $quiz) {
                    $quiz['image'] = (!empty($quiz['image'])) ? DOMAIN_URL . 'images/contest/' . $quiz['image'] : '';
                    $quiz['start_date'] = date("d-M", strtotime($quiz['start_date']));
                    $quiz['end_date'] = date("d-M", strtotime($quiz['end_date']));
                    $s = "SELECT top_winner, points FROM `contest_prize` WHERE contest_id= " . $quiz['id'];
                    $db->sql($s);
                    $points = $db->getResult();
                    $quiz['points'] = $points;
                    $result[] = $quiz;
                }
                $past_result = $result;
                $response['past_contest']['error'] = false;
                $response['past_contest']['message'] = "Contest you have played";
                $response['past_contest']['data'] = (!empty($past_result)) ? $past_result : '';
            } else {
                $sql = "SELECT q.*, (select SUM(points) FROM contest_prize WHERE contest_prize.contest_id=q.id) as points, (select count(contest_id) FROM contest_prize WHERE contest_prize.contest_id=q.id) as top_users,(SELECT COUNT(*) from contest_leaderboard where l.contest_id = q.id )as `participants` FROM `contest_leaderboard` as l, `contest` as q WHERE l.user_id = '$user_id' and l.contest_id = q.id ORDER BY q.`id`  DESC";
                $db->sql($sql);
                $past_result = $db->getResult();
                if (!empty($past_result)) {
                    foreach ($past_result as $quiz) {
                        $quiz['image'] = (!empty($quiz['image'])) ? DOMAIN_URL . 'images/contest/' . $quiz['image'] : '';
                        $quiz['start_date'] = date("d-M", strtotime($quiz['start_date']));
                        $quiz['end_date'] = date("d-M", strtotime($quiz['end_date']));
                        $s = "SELECT top_winner, points FROM `contest_prize` WHERE contest_id= " . $quiz['id'];
                        $db->sql($s);
                        $points = $db->getResult();
                        $quiz['points'] = $points;
                        $result[] = $quiz;
                    }
                    $past_result = $result;
                    $response['past_contest']['error'] = false;
                    $response['past_contest']['message'] = "Contest you have played";
                    $response['past_contest']['data'] = (!empty($past_result)) ? $past_result : '';
                } else {
                    $response['past_contest']['error'] = true;
                    $response['past_contest']['message'] = "You have not played any contest yet. Go and play the contest once there is a live contest";
                }
            }

            /* getting all quiz details by ids retrieved */
            $sql = (empty($past_type_ids)) ?
                    "SELECT *, (select SUM(points) FROM contest_prize WHERE contest_prize.contest_id=contest.id) as points, (select count(contest_id) FROM contest_prize WHERE contest_prize.contest_id=contest.id) as top_users,(SELECT COUNT(*) from contest_leaderboard where contest_leaderboard.contest_id = contest.id )as `participants` FROM `contest` WHERE `id` in ($live_type_ids) AND status='1' ORDER BY `id` DESC" :
                    "SELECT *, (select SUM(points) FROM contest_prize WHERE contest_prize.contest_id=contest.id) as points, (select count(contest_id) FROM contest_prize WHERE contest_prize.contest_id=contest.id) as top_users,(SELECT COUNT(*) from contest_leaderboard where contest_leaderboard.contest_id = contest.id )as `participants` FROM `contest` WHERE `id` in ($live_type_ids) and `id` not in ($past_type_ids) AND status='1' ORDER BY `id` DESC"
            ;

            $db->sql($sql);
            $live_result = $db->getResult();
            $result = array();
            if (!empty($live_result)) {
                foreach ($live_result as $quiz) {
                    $quiz['image'] = (!empty($quiz['image'])) ? DOMAIN_URL . 'images/contest/' . $quiz['image'] : '';
                    $quiz['start_date'] = date("d-M", strtotime($quiz['start_date']));
                    $quiz['end_date'] = date("d-M", strtotime($quiz['end_date']));
                    $s = "SELECT top_winner, points FROM `contest_prize` WHERE contest_id= " . $quiz['id'];
                    $db->sql($s);
                    $points = $db->getResult();
                    $quiz['points'] = $points;
                    $result[] = $quiz;
                }
                $live_result = $result;
                $response['live_contest']['error'] = false;
                $response['live_contest']['message'] = "Play & Win exciting prizes";
                $response['live_contest']['data'] = (!empty($live_result)) ? $live_result : '';
            } else {
                $response['live_contest']['error'] = true;
                $response['live_contest']['message'] = "No contest is available to play right now. Come back again";
            }
        } else {
            $sql = "SELECT q.*, (select SUM(points) FROM contest_prize WHERE contest_prize.contest_id=q.id) as points, (select count(contest_id) FROM contest_prize WHERE contest_prize.contest_id=q.id) as top_users,(SELECT COUNT(*) from contest_leaderboard where l.contest_id = q.id )as `participants` FROM `contest_leaderboard` as l, `contest` as q WHERE l.user_id = '$user_id' and l.contest_id = q.id ORDER BY q.`id`  DESC";
            $db->sql($sql);
            $past_result = $db->getResult();
            if (!empty($past_result)) {
                foreach ($past_result as $quiz) {
                    $quiz['image'] = (!empty($quiz['image'])) ? DOMAIN_URL . 'images/contest/' . $quiz['image'] : '';
                    $quiz['start_date'] = date("d-M", strtotime($quiz['start_date']));
                    $quiz['end_date'] = date("d-M", strtotime($quiz['end_date']));
                    $s = "SELECT top_winner, points FROM `contest_prize` WHERE contest_id= " . $quiz['id'];
                    $db->sql($s);
                    $points = $db->getResult();
                    $quiz['points'] = $points;
                    $result[] = $quiz;
                }
                $past_result = $result;
                $response['past_contest']['error'] = false;
                $response['past_contest']['message'] = "Contest you have played";
                $response['past_contest']['data'] = (!empty($past_result)) ? $past_result : '';
            } else {
                $response['past_contest']['error'] = true;
                $response['past_contest']['message'] = "You have not played any contest yet. Go and play the contest once there is a live contest";
            }
            $response['live_contest']['error'] = true;
            $response['live_contest']['message'] = "No contest is available to play right now. Come back again";
        }

        /* selecting upcoming quiz ids */
        $sql = "SELECT id FROM `contest` where (CAST(`start_date` AS DATE) > '$toDate')";
        $db->sql($sql);
        $result = $db->getResult();
        $upcoming_type_ids = '';
        if (!empty($result)) {
            foreach ($result as $type_id) {
                $upcoming_type_ids .= $type_id['id'] . ', ';
            }
            $upcoming_type_ids = rtrim($upcoming_type_ids, ', ');

            /* getting all quiz details by ids retrieved */
            $sql = "SELECT *, (select SUM(points) FROM contest_prize WHERE contest_prize.contest_id=contest.id) as points, (select count(contest_id) FROM contest_prize WHERE contest_prize.contest_id=contest.id) as top_users FROM `contest` WHERE `id` in ($upcoming_type_ids) ORDER BY `id` DESC";
            $db->sql($sql);
            $upcoming_result = $db->getResult();
            $result = array();
            if (!empty($upcoming_result)) {
                foreach ($upcoming_result as $quiz) {
                    $quiz['image'] = (!empty($quiz['image'])) ? DOMAIN_URL . 'images/contest/' . $quiz['image'] : '';
                    $quiz['start_date'] = date("d-M", strtotime($quiz['start_date']));
                    $quiz['end_date'] = date("d-M", strtotime($quiz['end_date']));
                    $s = "SELECT top_winner, points FROM `contest_prize` WHERE contest_id= " . $quiz['id'];
                    $db->sql($s);
                    $points = $db->getResult();
                    $quiz['points'] = $points;
                    $quiz['participants'] = "";
                    $result[] = $quiz;
                }
                $upcoming_result = $result;
            }
            $response['upcoming_contest']['error'] = false;
            $response['upcoming_contest']['message'] = "Please stay tune to play & win exciting prizes.";
            $response['upcoming_contest']['data'] = (!empty($upcoming_result)) ? $upcoming_result : '';
        } else {
            $response['upcoming_contest']['error'] = true;
            $response['upcoming_contest']['message'] = "No upcoming contest to show. Soon we will be announcing the one.";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 40. get_questions_by_contest()
if (isset($_POST['access_key']) && isset($_POST['get_questions_by_contest'])) {
    /* Parameters to be passed
      access_key:6808
      get_questions_by_contest:1
      contest_id:5
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['contest_id']) && !empty($_POST['contest_id']) && !empty($_POST['access_key'])) {
        $contest_id = $db->escapeString($_POST['contest_id']);
        $sql = "SELECT * FROM `contest_questions` WHERE `contest_id` = $contest_id ORDER BY id DESC";
        $db->sql($sql);
        $result = $db->getResult();
        if (!empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $result[$i]['image'] = (!empty($result[$i]['image'])) ? DOMAIN_URL . 'images/contest-question/' . $result[$i]['image'] : '';
                $result[$i]['optione'] = ($fn->is_option_e_mode_enabled() && $result[$i]['optione'] != null) ? trim($result[$i]['optione']) : '';
                $result[$i]['optiona'] = trim($result[$i]['optiona']);
                $result[$i]['optionb'] = trim($result[$i]['optionb']);
                $result[$i]['optionc'] = trim($result[$i]['optionc']);
                $result[$i]['optiond'] = trim($result[$i]['optiond']);
            }
            $response['error'] = "false";
            $response['data'] = $result;
        } else {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 41. contest_update_score() - set the score of the user after he plays the contest
if (isset($_POST['access_key']) && isset($_POST['contest_update_score'])) {
    /* Parameters to be passed
      access_key:6808
      contest_update_score:1
      user_id:33
      contest_id:6
      questions_attended:10
      correct_answers:8
      score:8
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['user_id']) && !empty($_POST['user_id']) && !empty($_POST['contest_id']) && isset($_POST['score']) && isset($_POST['correct_answers']) && isset($_POST['questions_attended'])) {
        $user_id = $db->escapeString($_POST['user_id']);
        $contest_id = $db->escapeString($_POST['contest_id']);
        $questions_attended = $db->escapeString($_POST['questions_attended']);
        $correct_answers = $db->escapeString($_POST['correct_answers']);
        $score = $db->escapeString($_POST['score']);

        $sql = "select * from `contest_leaderboard` WHERE `user_id`='" . $user_id . "' and `contest_id`='" . $contest_id . "' ";
        $db->sql($sql);
        $res = $db->getResult();
        if (empty($res)) {
            $sql = "INSERT INTO `contest_leaderboard`(`user_id`, `contest_id`, `questions_attended`, `correct_answers`, `score`,`last_modified`,`date_created`) VALUES 
			(" . $user_id . "," . $contest_id . ", " . $questions_attended . "," . $correct_answers . "," . $score . ",'" . $toDateTime . "','" . $toDateTime . "')";
            $db->sql($sql);  // Table name, column names and respective values
            set_monthly_leaderboard($user_id, $score);
            $response['error'] = "false";
            $response['message'] = "Score insert successfully";
        } else {
            $id = $res[0]['id'];
            $sql = 'UPDATE `contest_leaderboard` SET `questions_attended`="' . $questions_attended . '",`correct_answers`="' . $correct_answers . '",`score`="' . $score . '",`last_modified`="' . $toDateTime . '" WHERE `id`=' . $id;
            $db->sql($sql);  // Table name, column names and respective values
            set_monthly_leaderboard($user_id, $score);
            $response['error'] = "false";
            $response['message'] = "Score updated successfully";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 42. get_contest_leaderboard() - get the top 15 players list for a contest
if (isset($_POST['access_key']) && isset($_POST['get_contest_leaderboard'])) {
    /* Parameters to be passed
      access_key:6808
      get_contest_leaderboard:1
      contest_id:6
      user_id:54 // (when show my quiz rank) (optional)
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['contest_id']) && !empty($_POST['contest_id'])) {
        $contest_id = $db->escapeString($_POST['contest_id']);

        $offset = (isset($_POST['offset']) && !empty($_POST['offset']) && is_numeric($_POST['offset'])) ? $db->escapeString($_POST['offset']) : 0;
        $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit'])) ? $db->escapeString($_POST['limit']) : 25;

//        $sql = "SELECT @user_rank:= @user_rank + 1 as user_rank, s.* FROM ( SELECT contest_leaderboard.user_id, users.name, users.profile, contest_leaderboard.score FROM contest_leaderboard, users WHERE contest_id = " . $contest_id . " and users.id = contest_leaderboard.user_id ORDER BY score DESC LIMIT 15 ) s cross join (SELECT @user_rank := 0) r";
        $sql = "SELECT r.*,u.name,u.profile FROM (SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT user_id, score FROM contest_leaderboard c join users u on u.id = c.user_id  WHERE contest_id=" . $contest_id . " ) s, (SELECT @user_rank := 0) init ORDER BY score DESC ) r INNER join users u on u.id = r.user_id ORDER BY r.user_rank ASC LIMIT $offset,$limit";
        $db->sql($sql);
        $res = $db->getResult();
        for ($i = 0; $i < count($res); $i++) {
            if (filter_var($res[$i]['profile'], FILTER_VALIDATE_URL) === FALSE) {
                // Not a valid URL. Its a image only or empty
                $res[$i]['profile'] = (!empty($res[$i]['profile'])) ? DOMAIN_URL . 'uploads/profile/' . $res[$i]['profile'] : '';
            } else {
                $res[$i]['profile'] = $res[$i]['profile'];
            }
        }
        if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
            $user_id = $db->escapeString($_POST['user_id']);
            //$sql = "SELECT id , user_id , contest_id , score , user_rank FROM ( SELECT * , (@user_rank := @user_rank + 1) AS user_rank FROM contest_leaderboard CROSS JOIN( SELECT @user_rank := 0 ) AS init_var_var where contest_id = '" . $contest_id . "' ORDER BY contest_leaderboard.score DESC ) AS logins_ordered_user_ranked WHERE user_id = '" . $user_id . "' and contest_id = '" . $contest_id . "' ";
            $sql = "SELECT r.*,u.name,u.profile FROM (SELECT s.*, @user_rank := @user_rank + 1 user_rank FROM ( SELECT user_id, score FROM contest_leaderboard c join users u on u.id = c.user_id  WHERE contest_id=" . $contest_id . " ) s, (SELECT @user_rank := 0) init ORDER BY score DESC ) r INNER join users u on u.id = r.user_id WHERE user_id = '" . $user_id . "' ORDER BY r.user_rank ASC";
            $db->sql($sql);
            $my_rank = $db->getResult();
            if (!empty($my_rank)) {
                if (filter_var($my_rank[0]['profile'], FILTER_VALIDATE_URL) === FALSE) {
                    // Not a valid URL. Its a image only or empty
                    $my_rank[0]['profile'] = (!empty($my_rank[0]['profile'])) ? DOMAIN_URL . 'uploads/profile/' . $my_rank[0]['profile'] : '';
                }
                $response['my_rank'] = $my_rank[0];
            }
        }
        if (empty($res)) {
            $response['error'] = "true";
            $response['message'] = "No contest played yet! No rankings found!";
        } else {
            $response['error'] = "false";
            $response['data'] = $res;
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 43. create_room()
if (isset($_POST['access_key']) && isset($_POST['create_room'])) {
    /* Parameters to be passed
      access_key:6808
      create_room:1
      user_id:1
      room_id:1
      room_type:public / private
      language_id:2   //{optional}
      category:1      // required if room category enable form panel
      no_of_que:10
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['room_id']) && !empty($_POST['user_id']) && !empty($_POST['room_type']) && !empty($_POST['no_of_que'])) {
        $user_id = $db->escapeString($_POST['user_id']);
        $room_id = $db->escapeString($_POST['room_id']);
        $room_type = $db->escapeString($_POST['room_type']);
        $no_of_que = $db->escapeString($_POST['no_of_que']);

        $language_id = (isset($_POST['language_id']) && !empty($_POST['language_id']) && is_numeric($_POST['language_id'])) ? $db->escapeString($_POST['language_id']) : '';

        if (isset($_POST['category']) && !empty($_POST['category'])) {
            $category = $db->escapeString($_POST['category']);
        } else {
            $category = '';
        }

        $sql1 = "SELECT * FROM `tbl_rooms` where room_id='$room_id'";
        $db->sql($sql1);
        $res1 = $db->getResult();
        if (empty($res1)) {
            $sql = "SELECT * FROM `question` ";
            $sql .= (!empty($language_id)) ? " where `language_id` = $language_id " : "";
            $sql .= (!empty($language_id)) ? ((!empty($category)) ? " AND `category`='" . $category . "' " : "") : ((!empty($category)) ? " WHERE `category`='" . $category . "' " : "" );
            $sql .= " ORDER BY RAND() LIMIT 0, " . $no_of_que . "";
            $db->sql($sql);
            $res = $db->getResult();

            if (empty($res)) {
                $response['error'] = "true";
                $response['message'] = "No questions found";
            } else {
                $questions = $db->escapeString(json_encode($res));
                $sql1 = "INSERT INTO `tbl_rooms`(`room_id`, `user_id`, `room_type`, `category_id`, `no_of_que`, `questions`, `date_created`) VALUES ('$room_id','$user_id','$room_type','$category','$no_of_que','$questions','$toDateTime')";
                $db->sql($sql1);

                $response['error'] = "false";
                $response['message'] = "room create successfully";
            }
        } else {
            $response['error'] = "true";
            $response['message'] = "room is already created";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 44. get_question_by_room_id()
if (isset($_POST['access_key']) && isset($_POST['get_question_by_room_id'])) {
    /* Parameters to be passed
      access_key:6808
      get_question_by_room_id:1
      room_id:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['room_id']) && !empty($_POST['room_id'])) {
        $room_id = $db->escapeString($_POST['room_id']);

        $sql = "SELECT * FROM `tbl_rooms` where room_id='$room_id'";
        $db->sql($sql);
        $res = $db->getResult();

        if (empty($res)) {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        } else {
            $res = json_decode($res[0]['questions'], 1);
            foreach ($res as $row) {
                $row['image'] = (!empty($row['image'])) ? DOMAIN_URL . 'images/questions/' . $row['image'] : '';
                $row['optione'] = ($fn->is_option_e_mode_enabled() && $row['optione'] != null) ? $row['optione'] : '';
                $temp[] = $row;
            }
            $res[0]['questions'] = json_encode($temp);

            $response['error'] = "false";
            $response['data'] = json_decode($res[0]['questions']);
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 45. destroy_room_by_room_id()
if (isset($_POST['access_key']) && isset($_POST['destroy_room_by_room_id'])) {
    /* Parameters to be passed
      access_key:6808
      destroy_room_by_room_id:1
      room_id:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['room_id']) && !empty($_POST['room_id'])) {
        $room_id = $db->escapeString($_POST['room_id']);

        $sql = "DELETE FROM `tbl_rooms` WHERE `room_id` = '" . $room_id . "'";
        $db->sql($sql);
        $response['error'] = "false";
        $response['message'] = "Room destroyed successfully";
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 46. get_public_room()
if (isset($_POST['access_key']) && isset($_POST['get_public_room'])) {
    /* Parameters to be passed
      access_key:6808
      get_public_room:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['get_public_room'])) {
        $sql = "SELECT r.id, r.room_id, r.user_id, r.room_type, r.category_id, r.no_of_que, r.date_created, u.name FROM tbl_rooms r JOIN users u ON u.id=r.user_id where room_type='public'";
        $db->sql($sql);
        $res = $db->getResult();
        if (empty($res)) {
            $response['error'] = "true";
            $response['message'] = "No data found!";
        } else {
            $response['error'] = "false";
            $response['data'] = $res;
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 47. invite_friend()
if (isset($_POST['access_key']) && isset($_POST['invite_friend'])) {
    /* Parameters to be passed
      access_key:6808
      invite_friend:1
      user_id:1
      room_id:1
      invited_id:2
      room_key: your room key
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    if (isset($_POST['room_key']) && isset($_POST['room_id']) && !empty($_POST['user_id']) && !empty($_POST['invited_id'])) {
        $user_id = $db->escapeString($_POST['user_id']);
        $room_id = $db->escapeString($_POST['room_id']);
        $invited_id = $db->escapeString($_POST['invited_id']);
        $room_key = $db->escapeString($_POST['room_key']);
        //get user name
        $sql = "SELECT `name` FROM `users` where `id` = " . $user_id;
        $db->sql($sql);
        $res = $db->getResult();
        $user_name = $res[0]['name'];

        //get fcm_key 
        $sql1 = 'select `fcm_key` from `tbl_fcm_key` where id=1';
        $db->sql($sql1);
        $res1 = $db->getResult();
        define('API_ACCESS_KEY', $res1[0]['fcm_key']);

        //get user fcm_id 
        $fcm_id = get_fcm_id($invited_id);

        $title = 'Quiz';
        $message = $user_name . ' is Inviting for Quiz Battle';

        $newMsg = array();
        $success = $failure = 0;

        $fcmMsg = array(
            'type' => 'invite',
            'room_key' => $room_key,
            'title' => $title,
            'message' => $message,
            'room_id' => $room_id
        );
        $newMsg['data'] = $fcmMsg;

        $fcmFields = array(
            'to' => $fcm_id,
            'priority' => 'high',
            'data' => $newMsg
        );
        $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, 1);

        if ($result['success']) {
            $response['error'] = "false";
            $response['message'] = "Notification Sent Successfully";
        } else {
            $response['error'] = "true";
            $response['message'] = "Somthing Wrong";
        }
    } else {
        $response['error'] = "true";
        $response['message'] = "Please pass all the fields";
    }
    print_r(json_encode($response));
}

// 48. get_firebase_settings()
if (isset($_POST['access_key']) && isset($_POST['get_firebase_settings'])) {
    /* Parameters to be passed
      access_key:6808
      get_firebase_settings:1
     */
    if (!verify_token()) {
        return false;
    }
    if ($access_key != $_POST['access_key']) {
        $response['error'] = "true";
        $response['message'] = "Invalid Access Key";
        print_r(json_encode($response));
        return false;
    }
    $setting = [
        'apiKey', 'authDomain', 'databaseURL', 'projectId', 'storageBucket', 'messagingSenderId', 'appId', 'client_id_google', 'app_id_fb'
    ];
    $data = array();
    foreach ($setting as $row) {
        $sql = "SELECT * FROM settings WHERE type='" . $row . "' LIMIT 1";
        $db->sql($sql);
        $res = $db->getResult();
        $data[$row] = (!empty($res)) ? $res[0]['message'] : '';
    }
    if (!empty($data)) {
        $response['error'] = "false";
        $response['data'] = $data;
    } else {
        $response['error'] = "true";
        $response['message'] = "No data found!";
    }
    print_r(json_encode($response));
}

function get_fcm_id($user_id) {
    $db = new Database();
    $db->connect();

    $sql = "SELECT `fcm_id` FROM `users` where `id` = " . $user_id;
    $db->sql($sql);
    $res = $db->getResult();
    return $res[0]['fcm_id'];
}

function checkBattleExists($match_id) {
    $db = new Database();
    $db->connect();

    $sql = "SELECT `id` FROM `battle_questions` where `match_id` = '" . $match_id . "'";
    $db->sql($sql);
    $res = $db->getResult();
    return $res;
    if (empty($res)) {
        return false;
    } else {
        return true;
    }
}

function set_monthly_leaderboard($user_id, $score) {
    if (isset($user_id) && isset($score) && !empty($user_id)) {
        $db = new Database();
        $db->connect();

        $toDate = date('Y-m-d');
        $toDateTime = date('Y-m-d H:i:s');

        $sql = "SELECT id, user_id, score FROM `monthly_leaderboard` WHERE `user_id`=" . $user_id . " and month(monthly_leaderboard.date_created) = month('" . $toDate . "') 
            and year(monthly_leaderboard.date_created) = year('" . $toDate . "') ";
        $db->sql($sql);
        $result = $db->getResult();

        $sql1 = "SELECT id, user_id FROM `daily_leaderboard` WHERE `user_id`=" . $user_id;
        $db->sql($sql1);
        $result1 = $db->getResult();

        if (!empty($result) && !empty($result1)) {
            $sql2 = "SELECT id, user_id, score FROM `daily_leaderboard` WHERE `user_id`=" . $user_id . " and day(daily_leaderboard.date_created) = day('" . $toDate . "') ";
            $db->sql($sql2);
            $result2 = $db->getResult();

            if (!empty($result2)) {
                $old = $result2[0]['score'];
                $new = $old + $score;
                $score1 = ($new <= 0) ? 0 : $score;
                if ($new <= 0) {
                    $sql1 = "UPDATE `daily_leaderboard` SET `score`= '" . $score1 . "' WHERE id = " . $result2[0]['id'] . " and user_id=" . $user_id;
                } else {
                    $sql1 = "UPDATE `daily_leaderboard` SET `score`= `score` + '" . $score1 . "' WHERE id = " . $result2[0]['id'] . " and user_id=" . $user_id;
                }
                $db->sql($sql1);
            } else {
                $score1 = ($score <= 0) ? 0 : $score;
                $sql1 = "UPDATE `daily_leaderboard` SET `date_created` = '" . $toDateTime . "', `score`= '" . $score1 . "' WHERE user_id=" . $user_id;
                $db->sql($sql1);
            }
            $old1 = $result[0]['score'];
            $new1 = $old1 + $score;
            $score1 = ($new1 <= 0) ? 0 : $score;
            if ($new1 <= 0) {
                $sql = "UPDATE `monthly_leaderboard` SET `score`= '" . $score1 . "' WHERE id = " . $result[0]['id'] . " and user_id=" . $user_id;
            } else {
                $sql = "UPDATE `monthly_leaderboard` SET `score`= `score` + '" . $score1 . "' WHERE id = " . $result[0]['id'] . " and user_id=" . $user_id;
            }
            $db->sql($sql);
        } else {
            $score1 = ($score <= 0) ? 0 : $score;
            if (!empty($result1[0]['user_id'])) {
                $sql1 = "UPDATE `daily_leaderboard` SET `date_created` = '" . $toDateTime . "', `score`= '" . $score1 . "' WHERE id = " . $result1[0]['id'] . " and user_id=" . $user_id;
                $db->sql($sql1);
            } else {
                $sql1 = 'INSERT INTO `daily_leaderboard` (`user_id`, `score`, `last_updated`) VALUES (' . $user_id . ',' . $score1 . ',"' . $toDateTime . '")';
                $db->sql($sql1);
            }
            $sql = 'INSERT INTO `monthly_leaderboard` (`user_id`, `score`, `last_updated`) VALUES (' . $user_id . ',' . $score1 . ',"' . $toDateTime . '")';
            $db->sql($sql);
        }
    }
}

?>