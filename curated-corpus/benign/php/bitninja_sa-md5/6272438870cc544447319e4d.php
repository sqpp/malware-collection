<?php
/**
 * QuickQR - Digital QR Menu
 * @author Bylancer
 * @version 6.3
 * @Updated Date: 2/Nov/2021
 * @Copyright 2015-21 Bylancer
 */

require_once('../includes/config.php');
require_once('../includes/lib/HTMLPurifier/HTMLPurifier.standalone.php');
require_once('../includes/sql_builder/idiorm.php');
require_once('../includes/db.php');
require_once('../includes/classes/class.template_engine.php');
require_once('../includes/classes/class.country.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.sqlquery.php');
require_once('../includes/functions/func.users.php');
require_once('../includes/lang/lang_' . $config['lang'] . '.php');
require_once('../includes/seo-url.php');

sec_session_start();
define("ROOTPATH", dirname(__DIR__));

if (isset($_GET['action'])) {
    if ($_GET['action'] == "add_item") { add_item(); }
    if ($_GET['action'] == "edit_item") { edit_item(); }
    if ($_GET['action'] == "get_item") { get_item(); }
    if ($_GET['action'] == "delete_item") { delete_item(); }

    if ($_GET['action'] == "add_image_item") { add_image_item(); }
    if ($_GET['action'] == "get_image_menu") { get_image_menu(); }
    if ($_GET['action'] == "delete_image_menu") { delete_image_menu(); }

    if ($_GET['action'] == "submitBlogComment") { submitBlogComment(); }

    die(0);
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == "addNewCat") { addNewCat(); }
    if ($_POST['action'] == "editCat") { editCat(); }
    if ($_POST['action'] == "deleteCat") { deleteCat(); }
    if ($_POST['action'] == "updateCatPosition") { updateCatPosition(); }

    if ($_POST['action'] == "addNewSubCat") { addNewSubCat(); }
    if ($_POST['action'] == "editSubCat") { editSubCat(); }
    if ($_POST['action'] == "deleteSubCat") { deleteSubCat(); }
    if ($_POST['action'] == "updateSubCatPosition") { updateSubCatPosition(); }

    if ($_POST['action'] == "updateMenuPosition") { updateMenuPosition(); }
    if ($_POST['action'] == "updateExtrasPosition") { updateExtrasPosition(); }
    if ($_POST['action'] == "updateImageMenuPosition") { updateImageMenuPosition(); }

    if ($_POST['action'] == "ajaxlogin") { ajaxlogin(); }
    if ($_POST['action'] == "email_verify") { email_verify(); }

    if ($_POST['action'] == "addMenuExtra") { addMenuExtra(); }
    if ($_POST['action'] == "editMenuExtra") { editMenuExtra(); }
    if ($_POST['action'] == "deleteMenuExtra") { deleteMenuExtra(); }

    if ($_POST['action'] == "sendRestaurantOrder") { sendRestaurantOrder(); }
    if ($_POST['action'] == "completeOrder") { completeOrder(); }
    if ($_POST['action'] == "deleteOrder") { deleteOrder(); }

    if ($_POST['action'] == "quickHeartBeat") { quickHeartBeat(); }
    if ($_POST['action'] == "checkStoreSlug") { checkStoreSlug();}
    if ($_POST['action'] == "callTheWaiter") { callTheWaiter(); }

    if ($_POST['action'] == "addVariantOption") { addVariantOption(); }
    if ($_POST['action'] == "editVariantOption") { editVariantOption(); }
    if ($_POST['action'] == "deleteVariantOption") { deleteVariantOption(); }
    if ($_POST['action'] == "updateVariantOptionsPosition") { updateVariantOptionsPosition(); }

    if ($_POST['action'] == "addVariant") { addVariant(); }
    if ($_POST['action'] == "editVariant") { editVariant(); }
    if ($_POST['action'] == "deleteVariant") { deleteVariant(); }
    if ($_POST['action'] == "updateVariantsPosition") { updateVariantsPosition(); }

    die(0);
}

/**
 * Add menu item
 */
function add_item()
{
    global $config, $lang;

    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    if (empty($_POST['title'])) {
        $result['success'] = false;
        $result['message'] = $lang['TITLE_REQ'];
        die(json_encode($result));
    }

    if (empty($_POST['price'])) {
        $result['success'] = false;
        $result['message'] = $lang['PRICE_REQ'];
        die(json_encode($result));
    }
    $MainFileName = null;
    $main_imageName = '';
    $cat_id = validate_input($_POST['cat_id']);
    $title = validate_input($_POST['title']);
    $description = validate_input($_POST['description']);
    $price = validate_input($_POST['price']);

    // check if adding new item
    if (empty($_POST['id'])) {
        // Get usergroup details
        $group_id = get_user_group();
        // Get membership details
        switch ($group_id){
            case 'free':
                $plan = json_decode(get_option('free_membership_plan'), true);
                $settings = $plan['settings'];
                $limit = $settings['menu_limit'];
                break;
            case 'trial':
                $plan = json_decode(get_option('trial_membership_plan'), true);
                $settings = $plan['settings'];
                $limit = $settings['menu_limit'];
                break;
            default:
                $plan = ORM::for_table($config['db']['pre'] . 'plans')
                    ->select('settings')
                    ->where('id', $group_id)
                    ->find_one();
                if(!isset($plan['settings'])){
                    $plan = json_decode(get_option('free_membership_plan'), true);
                    $settings = $plan['settings'];
                    $limit = $settings['menu_limit'];
                }else{
                    $settings = json_decode($plan['settings'],true);
                    $limit = $settings['menu_limit'];
                }
                break;
        }


        if ($limit != "999") {
            $total = ORM::for_table($config['db']['pre'] . 'menu')
                ->where('user_id', $_SESSION['user']['id'])
                ->where('cat_id', $cat_id)
                ->count();

            if ($total >= $limit) {
                $result['success'] = false;
                $result['message'] = $lang['LIMIT_EXCEED_UPGRADE'];
                die(json_encode($result));
            }
        }
    }

    // Valid formats
    $valid_formats = array("jpeg", "jpg", "png");

    /*Start Item Logo Image Uploading*/
    $file = $_FILES['main_image'];
    $filename = $file['name'];
    $ext = getExtension($filename);
    $ext = strtolower($ext);
    if (!empty($filename)) {
        //File extension check
        if (in_array($ext, $valid_formats)) {
            $main_path = ROOTPATH . "/storage/menu/";
            $filename = uniqid(time()) . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $main_path . $filename)) {
                $MainFileName = $filename;
                //resizeImage(150, $main_path . $filename, $main_path . $filename);
            } else {
                $result['success'] = false;
                $result['message'] = $lang['ERROR_IMAGE'];
                die(json_encode($result));
            }
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ONLY_JPG_ALLOW'];
            die(json_encode($result));
        }
    }
    /*End Item Logo Image Uploading*/

    if (trim($title) != '' && is_string($title)) {
        $json = array();

        if (!empty($_POST['id'])) {
            $insert_menu = ORM::for_table($config['db']['pre'] . 'menu')->find_one($_POST['id']);
            $json = json_decode($insert_menu['translation'],true);
        } else {
            $insert_menu = ORM::for_table($config['db']['pre'] . 'menu')->create();
            $insert_menu->name = $title;
            $insert_menu->description = $description;
        }

        $user_lang = !empty($_COOKIE['Quick_user_lang_code'])? $_COOKIE['Quick_user_lang_code'] : $config['lang_code'];
        $json[$user_lang] = array('title'=> $title, 'description' => $description);

        $insert_menu->active = isset($_POST['active']) ? '1' : '0';
        $insert_menu->user_id = validate_input($_SESSION['user']['id']);
        $insert_menu->cat_id = $cat_id;
        $insert_menu->price = $price;
        $insert_menu->type = validate_input($_POST['type']);
        $insert_menu->translation = json_encode($json,JSON_UNESCAPED_UNICODE);
        if ($MainFileName) {
            $insert_menu->image = $MainFileName;
        }
        $insert_menu->save();

        $menu_id = $insert_menu->id();

        if ($menu_id) {
            $result['success'] = true;
            $result['message'] = $lang['SAVED_SUCCESS'];
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ERROR_TRY_AGAIN'];
        }

    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Get menu item's data
 */
function get_item()
{
    global $config;
    $result = ORM::for_table($config['db']['pre'] . 'menu')
        ->where('user_id', $_SESSION['user']['id'])
        ->find_one($_GET['id']);
    $response = array('success' => false);
    if (!empty($result)) {
        $response['success'] = true;
        $user_lang = !empty($_COOKIE['Quick_user_lang_code'])? $_COOKIE['Quick_user_lang_code'] : $config['lang_code'];
        $json = json_decode($result['translation'],true);

        $response['name'] = !empty($json[$user_lang]['title'])?$json[$user_lang]['title']:$result['name'];

        $description = !empty($json[$user_lang]['description'])?$json[$user_lang]['description']:$result['description'];
        $response['description'] = stripcslashes($description);

        $response['price'] = $result['price'];
        $response['type'] = $result['type'];
        $response['active'] = $result['active'];
        $response['image'] = !empty($result['image'])
            ? $config['site_url'] . 'storage/menu/' . $result['image']
            : $config['site_url'] . 'storage/menu/' . 'default.png';
    }
    die(json_encode($response));
}

/**
 * Edit menu item
 */
function edit_item()
{
    global $config, $lang;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    if (empty($_POST['menu_id'])) {
        $result['success'] = false;
        $result['message'] = $lang['TITLE_REQ'];
        die(json_encode($result));
    }

    if (empty($_POST['title'])) {
        $result['success'] = false;
        $result['message'] = $lang['TITLE_REQ'];
        die(json_encode($result));
    }
    if (empty($_POST['description'])) {
        $result['success'] = false;
        $result['message'] = $lang['DESC_REQ'];
        die(json_encode($result));
    }
    if (empty($_POST['price'])) {
        $result['success'] = false;
        $result['message'] = $lang['PRICE_REQ'];
        die(json_encode($result));
    }
    $MainFileName = null;
    $main_imageName = '';
    $cat_id = validate_input($_POST['cat_id']);
    $title = validate_input($_POST['title']);
    $description = validate_input($_POST['description']);
    $price = validate_input($_POST['price']);

    // Valid formats
    $valid_formats = array("jpeg", "jpg", "png");

    /*Start Item Logo Image Uploading*/
    $file = $_FILES['main_image'];
    $filename = $file['name'];
    $ext = getExtension($filename);
    $ext = strtolower($ext);
    if (!empty($filename)) {
        //File extension check
        if (in_array($ext, $valid_formats)) {
            $main_path = ROOTPATH . "/storage/menu/";
            $filename = uniqid(time()) . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $main_path . $filename)) {
                $MainFileName = $filename;
                //resizeImage(150, $main_path . $filename, $main_path . $filename);
            } else {
                $result['success'] = false;
                $result['message'] = $lang['ERROR_IMAGE'];
                die(json_encode($result));
            }
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ONLY_JPG_ALLOW'];
            die(json_encode($result));
        }
    }
    /*End Item Logo Image Uploading*/

    if (trim($title) != '' && is_string($title)) {

        $insert_menu = ORM::for_table($config['db']['pre'] . 'menu')->create();
        $insert_menu->user_id = validate_input($_SESSION['user']['id']);
        $insert_menu->cat_id = $cat_id;
        $insert_menu->name = $title;
        $insert_menu->description = $description;
        $insert_menu->price = $price;
        if ($MainFileName) {
            $insert_menu->image = $MainFileName;
        }
        $insert_menu->save();

        $menu_id = $insert_menu->id();

        if ($menu_id) {
            $result['success'] = true;
            $result['message'] = $lang['SAVED_SUCCESS'];
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ERROR_TRY_AGAIN'];
        }

    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Delete menu item
 */
function delete_item()
{
    global $lang, $config;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }
    $id = $_GET['id'];
    if (trim($id) != '') {
        $data = ORM::for_table($config['db']['pre'] . 'menu')
            ->where(array(
                'id' => $id,
                'user_id' => $_SESSION['user']['id'],
            ))
            ->delete_many();

        if ($data) {
            $result['success'] = true;
            $result['message'] = $lang['MENU_DELETED'];
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ERROR_TRY_AGAIN'];
        }
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Update menu positions
 */
function updateMenuPosition()
{
    global $config,$lang;
    $con = ORM::get_db();
    $position = $_POST['position'];
    if (is_array($position)) {
        foreach($position as $key => $id){
            $query = "UPDATE `".$config['db']['pre']."menu` SET `position` = '".$key."' WHERE `id` = '" . $id . "'";
            $con->query($query);
        }

        $result['success'] = true;
        $result['message'] = $lang['POSITION_UPDATED'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Add/edit menu image
 */
function add_image_item()
{
    global $config, $lang;

    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    if (empty($_POST['title'])) {
        $result['success'] = false;
        $result['message'] = $lang['TITLE_REQ'];
        die(json_encode($result));
    }

    if (empty($_FILES['main_image']['name']) && empty($_POST['id'])) {
        $result['success'] = false;
        $result['message'] = $lang['IMAGE_REQ'];
        die(json_encode($result));
    }

    $MainFileName = null;
    $main_imageName = '';
    $title = validate_input($_POST['title']);

    // check if adding new item
    if (empty($_POST['id'])) {
        // Get usergroup details
        $group_id = get_user_group();
        // Get membership details
        switch ($group_id){
            case 'free':
                $plan = json_decode(get_option('free_membership_plan'), true);
                $settings = $plan['settings'];
                $limit = $settings['menu_limit'];
                break;
            case 'trial':
                $plan = json_decode(get_option('trial_membership_plan'), true);
                $settings = $plan['settings'];
                $limit = $settings['menu_limit'];
                break;
            default:
                $plan = ORM::for_table($config['db']['pre'] . 'plans')
                    ->select('settings')
                    ->where('id', $group_id)
                    ->find_one();
                if(!isset($plan['settings'])){
                    $plan = json_decode(get_option('free_membership_plan'), true);
                    $settings = $plan['settings'];
                    $limit = $settings['menu_limit'];
                }else{
                    $settings = json_decode($plan['settings'],true);
                    $limit = $settings['menu_limit'];
                }
                break;
        }


        if ($limit != "999") {
            $total = ORM::for_table($config['db']['pre'] . 'image_menu')
                ->where('user_id', $_SESSION['user']['id'])
                ->count();

            if ($total >= $limit) {
                $result['success'] = false;
                $result['message'] = $lang['LIMIT_EXCEED_UPGRADE'];
                die(json_encode($result));
            }
        }
    }

    // Valid formats
    $valid_formats = array("jpeg", "jpg", "png");

    /*Start Item Logo Image Uploading*/
    $file = $_FILES['main_image'];
    $filename = $file['name'];
    $ext = getExtension($filename);
    $ext = strtolower($ext);
    if (!empty($filename)) {
        //File extension check
        if (in_array($ext, $valid_formats)) {
            $main_path = ROOTPATH . "/storage/menu/";
            $filename = uniqid(time()) . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $main_path . $filename)) {
                $MainFileName = $filename;
                resizeImage(1000, $main_path . $filename, $main_path . $filename);
            } else {
                $result['success'] = false;
                $result['message'] = $lang['ERROR_IMAGE'];
                die(json_encode($result));
            }
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ONLY_JPG_ALLOW'];
            die(json_encode($result));
        }
    }
    /*End Item Logo Image Uploading*/

    if (trim($title) != '' && is_string($title)) {
        if (!empty($_POST['id'])) {
            $insert_menu = ORM::for_table($config['db']['pre'] . 'image_menu')->find_one($_POST['id']);
        } else {
            $insert_menu = ORM::for_table($config['db']['pre'] . 'image_menu')->create();
        }

        $insert_menu->active = isset($_POST['active']) ? '1' : '0';
        $insert_menu->user_id = validate_input($_SESSION['user']['id']);
        $insert_menu->name = $title;
        if ($MainFileName) {
            $insert_menu->image = $MainFileName;
        }
        $insert_menu->save();

        $menu_id = $insert_menu->id();

        if ($menu_id) {
            $result['success'] = true;
            $result['message'] = $lang['SAVED_SUCCESS'];
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ERROR_TRY_AGAIN'];
        }

    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Get menu image data
 */
function get_image_menu()
{
    global $config;
    $result = ORM::for_table($config['db']['pre'] . 'image_menu')
        ->where('user_id', $_SESSION['user']['id'])
        ->find_one($_GET['id']);

    $response = array('success' => false);
    if (!empty($result)) {
        $response['success'] = true;
        $response['name'] = $result['name'];
        $response['active'] = $result['active'];
        $response['image'] = !empty($result['image'])
            ? $config['site_url'] . 'storage/menu/' . $result['image']
            : $config['site_url'] . 'storage/menu/' . 'default.png';
    }
    die(json_encode($response));
}

/**
 * Delete menu image
 */
function delete_image_menu()
{
    global $lang, $config;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }
    $id = $_GET['id'];
    if (trim($id) != '') {
        $data = ORM::for_table($config['db']['pre'] . 'image_menu')
            ->where(array(
                'id' => $id,
                'user_id' => $_SESSION['user']['id'],
            ))
            ->delete_many();

        if ($data) {
            $result['success'] = true;
            $result['message'] = $lang['MENU_DELETED'];
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ERROR_TRY_AGAIN'];
        }
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Update menu image positions
 */
function updateImageMenuPosition()
{
    global $config,$lang;
    $con = ORM::get_db();
    $position = $_POST['position'];
    if (is_array($position)) {
        foreach($position as $key => $id){
            $query = "UPDATE `".$config['db']['pre']."image_menu` SET `position` = '".$key."' WHERE `id` = '" . $id . "'";
            $con->query($query);
        }

        $result['success'] = true;
        $result['message'] = $lang['POSITION_UPDATED'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Add Category
 */
function addNewCat()
{
    global $config, $lang;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    // Get usergroup details
    $group_id = get_user_group();
    switch ($group_id){
        case 'free':
            $plan = json_decode(get_option('free_membership_plan'), true);
            $settings = $plan['settings'];
            $limit = $settings['category_limit'];
            break;
        case 'trial':
            $plan = json_decode(get_option('trial_membership_plan'), true);
            $settings = $plan['settings'];
            $limit = $settings['category_limit'];
            break;
        default:
            $plan = ORM::for_table($config['db']['pre'] . 'plans')
                ->select('settings')
                ->where('id', $group_id)
                ->find_one();
            if(!isset($plan['settings'])){
                $plan = json_decode(get_option('free_membership_plan'), true);
                $settings = $plan['settings'];
                $limit = $settings['category_limit'];
            }else{
                $settings = json_decode($plan['settings'],true);
                $limit = $settings['category_limit'];
            }
            break;
    }

    if ($limit != "999") {
        $total = ORM::for_table($config['db']['pre'] . 'catagory_main')
            ->where('user_id', $_SESSION['user']['id'])
            ->count();

        if ($total >= $limit) {
            $result['success'] = false;
            $result['message'] = $lang['LIMIT_EXCEED_UPGRADE'];
            die(json_encode($result));
        }
    }

    $name = validate_input($_POST['name']);
    if (trim($name) != '' && is_string($name)) {

        $user_lang = !empty($_COOKIE['Quick_user_lang_code'])? $_COOKIE['Quick_user_lang_code'] : $config['lang_code'];
        $json = array();
        $json[$user_lang] = array('title'=> $name);

        $insert_category = ORM::for_table($config['db']['pre'] . 'catagory_main')->create();
        $insert_category->cat_name = $name;
        $insert_category->user_id = $_SESSION['user']['id'];
        $insert_category->translation = json_encode($json, JSON_UNESCAPED_UNICODE);
        $insert_category->save();

        $category_id = $insert_category->id();

        if ($category_id) {
            $result['success'] = true;
            $result['message'] = $lang['SAVED_SUCCESS'];
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ERROR_TRY_AGAIN'];
        }

    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Edit category
 */
function editCat()
{
    global $lang, $config;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }
    $name = validate_input($_POST['name']);
    $id = validate_input($_POST['id']);
    if (trim($name) != '' && is_string($name) && trim($id) != '') {
        $catagory_update = ORM::for_table($config['db']['pre'] . 'catagory_main')
            ->use_id_column('cat_id')
            ->where(array(
                'user_id' => $_SESSION['user']['id'],
                'cat_id' => $id
            ))
            ->find_one();

        $user_lang = !empty($_COOKIE['Quick_user_lang_code'])? $_COOKIE['Quick_user_lang_code'] : $config['lang_code'];
        $json = json_decode($catagory_update['translation'],true);
        $json[$user_lang] = array('title'=> $name);

        $catagory_update->set('translation', json_encode($json));
        $catagory_update->save();

        $result['success'] = true;
        $result['message'] = $lang['SAVED_SUCCESS'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Delete Category
 */
function deleteCat()
{
    global $lang, $config;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }
    $id = validate_input($_POST['id']);
    if (trim($id) != '') {

        $data = ORM::for_table($config['db']['pre'] . 'catagory_main')
            ->where(array(
                'user_id' => $_SESSION['user']['id'],
                'cat_id' => $id
            ))
            ->delete_many();

        if ($data) {
            $result['success'] = true;
            $result['message'] = $lang['CATEGORY_DELETED'];
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ERROR_TRY_AGAIN'];
        }
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Add sub-category
 */
function addNewSubCat()
{
    global $config, $lang;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    // Get usergroup details
    $group_id = get_user_group();
    switch ($group_id){
        case 'free':
            $plan = json_decode(get_option('free_membership_plan'), true);
            $settings = $plan['settings'];
            $limit = $settings['category_limit'];
            break;
        case 'trial':
            $plan = json_decode(get_option('trial_membership_plan'), true);
            $settings = $plan['settings'];
            $limit = $settings['category_limit'];
            break;
        default:
            $plan = ORM::for_table($config['db']['pre'] . 'plans')
                ->select('settings')
                ->where('id', $group_id)
                ->find_one();
            if(!isset($plan['settings'])){
                $plan = json_decode(get_option('free_membership_plan'), true);
                $settings = $plan['settings'];
                $limit = $settings['category_limit'];
            }else{
                $settings = json_decode($plan['settings'],true);
                $limit = $settings['category_limit'];
            }
            break;
    }

    if ($limit != "999") {
        $total = ORM::for_table($config['db']['pre'] . 'catagory_main')
            ->where('user_id', $_SESSION['user']['id'])
            ->count();

        if ($total >= $limit) {
            $result['success'] = false;
            $result['message'] = $lang['LIMIT_EXCEED_UPGRADE'];
            die(json_encode($result));
        }
    }

    $name = validate_input($_POST['name']);
    $cat_id = validate_input($_POST['cat_id']);
    if (!empty($cat_id) && (trim($name) != '' && is_string($name))) {

        $user_lang = !empty($_COOKIE['Quick_user_lang_code'])? $_COOKIE['Quick_user_lang_code'] : $config['lang_code'];
        $json = array();
        $json[$user_lang] = array('title'=> $name);

        $insert_category = ORM::for_table($config['db']['pre'] . 'catagory_main')->create();
        $insert_category->cat_name = $name;
        $insert_category->parent = $cat_id;
        $insert_category->user_id = $_SESSION['user']['id'];
        $insert_category->translation = json_encode($json, JSON_UNESCAPED_UNICODE);
        $insert_category->save();

        $category_id = $insert_category->id();

        if ($category_id) {
            $result['success'] = true;
            $result['message'] = $lang['SAVED_SUCCESS'];
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ERROR_TRY_AGAIN'];
        }

    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Edit sub-category
 */
function editSubCat()
{
    global $lang, $config;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    $name = validate_input($_POST['name']);
    $cat_id = validate_input($_POST['cat_id']);
    $id = validate_input($_POST['id']);

    if (trim($name) != '' && is_string($name) && trim($id) != '') {
        $catagory_update = ORM::for_table($config['db']['pre'] . 'catagory_main')
            ->use_id_column('cat_id')
            ->where(array(
                'user_id' => $_SESSION['user']['id'],
                'cat_id' => $id
            ))
            ->find_one();
        $user_lang = !empty($_COOKIE['Quick_user_lang_code'])? $_COOKIE['Quick_user_lang_code'] : $config['lang_code'];
        $json = json_decode($catagory_update['translation'],true);
        $json[$user_lang] = array('title'=>$name);

        $catagory_update->set('translation', json_encode($json,JSON_UNESCAPED_UNICODE));
        $catagory_update->set('parent', validate_input($cat_id));
        $catagory_update->save();

        $result['success'] = true;
        $result['message'] = $lang['SAVED_SUCCESS'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Delete sub-category
 */
function deleteSubCat()
{
    global $lang, $config;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }
    $id = validate_input($_POST['id']);
    if (trim($id) != '') {

        $data = ORM::for_table($config['db']['pre'] . 'catagory_main')
            ->where(array(
                'user_id' => $_SESSION['user']['id'],
                'cat_id' => $id
            ))
            ->delete_many();

        if ($data) {
            $result['success'] = true;
            $result['message'] = $lang['SUBCATEGORY_DELETED'];
        } else {
            $result['success'] = false;
            $result['message'] = $lang['ERROR_TRY_AGAIN'];
        }
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Update category positions
 */
function updateCatPosition()
{
    global $config,$lang;
    $con = ORM::get_db();
    $position = $_POST['position'];
    if (is_array($position)) {
        foreach($position as $key => $catid){
            $query = "UPDATE `".$config['db']['pre']."catagory_main` SET `cat_order` = '".$key."' WHERE `cat_id` = '" . $catid . "'";
            $con->query($query);
        }

        $result['success'] = true;
        $result['message'] = $lang['POSITION_UPDATED'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Update sub-category positions
 */
function updateSubCatPosition()
{
    global $config,$lang;
    $con = ORM::get_db();
    $position = $_POST['position'];
    if (is_array($position)) {
        foreach($position as $key => $catid){
            $query = "UPDATE `".$config['db']['pre']."catagory_main` SET `cat_order` = '".$key."' WHERE `cat_id` = '" . $catid . "'";
            $con->query($query);
        }

        $result['success'] = true;
        $result['message'] = $lang['POSITION_UPDATED'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Add menu extra items
 */
function addMenuExtra()
{
    global $config, $lang;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    $title = validate_input($_POST['title']);
    $price = validate_input($_POST['price']);
    $menu_id = validate_input($_POST['menu_id']);

    if (trim($menu_id) == '' || empty($menu_id)) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    if (trim($title) == '' || empty($title)) {
        $result['success'] = false;
        $result['message'] = $lang['ALL_FIELDS_REQ'];
        die(json_encode($result));
    }

    if (trim($price) == '' || empty($price)) {
        $result['success'] = false;
        $result['message'] = $lang['ALL_FIELDS_REQ'];
        die(json_encode($result));
    }

    $user_lang = !empty($_COOKIE['Quick_user_lang_code'])? $_COOKIE['Quick_user_lang_code'] : $config['lang_code'];
    $json = array();
    $json[$user_lang] = array('title'=> $title);

    $insert = ORM::for_table($config['db']['pre'] . 'menu_extras')->create();
    $insert->title = $title;
    $insert->price = $price;
    $insert->translation = json_encode($json, JSON_UNESCAPED_UNICODE);
    $insert->menu_id = $menu_id;
    $insert->save();

    $id = $insert->id();

    if ($id) {
        $result['success'] = true;
        $result['message'] = $lang['SAVED_SUCCESS'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }

    die(json_encode($result));
}

/**
 * Edit menu extra items
 */
function editMenuExtra()
{
    global $config, $lang;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    $title = validate_input($_POST['title']);
    $price = validate_input($_POST['price']);
    $id = validate_input($_POST['id']);

    if (trim($id) == '' || empty($id)) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    if (trim($title) == '' || empty($title)) {
        $result['success'] = false;
        $result['message'] = $lang['ALL_FIELDS_REQ'];
        die(json_encode($result));
    }

    if (trim($price) == '' || empty($price)) {
        $result['success'] = false;
        $result['message'] = $lang['ALL_FIELDS_REQ'];
        die(json_encode($result));
    }

    $insert = ORM::for_table($config['db']['pre'] . 'menu_extras')->find_one($id);

    $user_lang = !empty($_COOKIE['Quick_user_lang_code'])? $_COOKIE['Quick_user_lang_code'] : $config['lang_code'];
    $json = json_decode($insert['translation'],true);
    $json[$user_lang] = array('title'=>validate_input($title));

    $insert->translation = json_encode($json, JSON_UNESCAPED_UNICODE);
    $insert->price = $price;
    $insert->active = isset($_POST['active']) ? 1 : 0;
    $insert->save();

    $result['success'] = true;
    $result['message'] = $lang['SAVED_SUCCESS'];


    die(json_encode($result));
}

/**
 * Delete menu extra items
 */
function deleteMenuExtra()
{
    global $lang, $config;

    $result['success'] = false;
    $result['message'] = $lang['ERROR_TRY_AGAIN'];
    if (!checkloggedin()) {
        die(json_encode($result));
    }
    $id = $_POST['id'];
    if (trim($id) != '') {
        // check menu is with same user
        $menu_extra = ORM::for_table($config['db']['pre'] . 'menu_extras')->find_one($id);

        if (!empty($menu_extra['menu_id'])) {
            $menu = ORM::for_table($config['db']['pre'] . 'menu')
                ->where(array(
                    'id' => $menu_extra['menu_id'],
                    'user_id' => $_SESSION['user']['id'],
                ))
                ->find_one();

            if (!empty($menu['id'])) {
                $data = ORM::for_table($config['db']['pre'] . 'menu_extras')
                    ->where(array(
                        'id' => $id
                    ))
                    ->delete_many();

                if ($data) {
                    $result['success'] = true;
                    $result['message'] = $lang['SUCCESS_DELETE'];
                }
            }
        }
    }
    die(json_encode($result));
}

/**
 * Update extra items positions
 */
function updateExtrasPosition()
{
    global $config,$lang;
    $con = ORM::get_db();
    $position = $_POST['position'];
    if (is_array($position)) {
        foreach($position as $key => $id){
            $query = "UPDATE `".$config['db']['pre']."menu_extras` SET `position` = '".$key."' WHERE `id` = '" . $id . "'";
            $con->query($query);
        }

        $result['success'] = true;
        $result['message'] = $lang['POSITION_UPDATED'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Login via ajax
 */
function ajaxlogin()
{
    global $config, $lang, $link;
    $loggedin = userlogin($_POST['username'], $_POST['password']);
    $result['success'] = false;
    $result['message'] = $lang['ERROR_TRY_AGAIN'];
    if (!is_array($loggedin)) {
        $result['message'] = $lang['USERNOTFOUND'];
    } elseif ($loggedin['status'] == 2) {
        $result['message'] = $lang['ACCOUNTBAN'];
    } else {
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
        $user_id = preg_replace("/[^0-9]+/", "", $loggedin['id']); // XSS protection as we might print this value
        $_SESSION['user']['id'] = $user_id;
        $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $loggedin['username']); // XSS protection as we might print this value
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['login_string'] = hash('sha512', $loggedin['password'] . $user_browser);
        $_SESSION['user']['user_type'] = $loggedin['user_type'];
        update_lastactive();

        $result['success'] = true;
        $result['message'] = $link['DASHBOARD'];
    }
    die(json_encode($result));
}

/**
 * Send confirmation email
 */
function email_verify()
{
    global $config, $lang;

    if (checkloggedin()) {
        /*SEND CONFIRMATION EMAIL*/
        email_template("signup_confirm", $_SESSION['user']['id']);

        $respond = $lang['SENT'];
        echo '<a class="button gray" href="javascript:void(0);">' . $respond . '</a>';
        die();

    } else {
        header("Location: " . $config['site_url'] . "login");
        exit;
    }
}

/**
 * Save blog comment
 */
function submitBlogComment()
{
    global $config, $lang;
    $comment_error = $name = $email = $user_id = $comment = null;
    $result = array();
    $is_admin = '0';
    $is_login = false;
    if (checkloggedin()) {
        $is_login = true;
    }
    $avatar = $config['site_url'] . 'storage/profile/default_user.png';
    if (!($is_login || isset($_SESSION['admin']['id']))) {
        if (empty($_POST['user_name']) || empty($_POST['user_email'])) {
            $comment_error = $lang['ALL_FIELDS_REQ'];
        } else {
            $name = removeEmailAndPhoneFromString($_POST['user_name']);
            $email = $_POST['user_email'];

            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            if (!preg_match($regex, $email)) {
                $comment_error = $lang['EMAILINV'];
            }
        }
    } else if ($is_login && isset($_SESSION['admin']['id'])) {
        $commenting_as = 'admin';
        if (!empty($_POST['commenting-as'])) {
            if (in_array($_POST['commenting-as'], array('admin', 'user'))) {
                $commenting_as = $_POST['commenting-as'];
            }
        }
        if ($commenting_as == 'admin') {
            $is_admin = '1';
            $info = ORM::for_table($config['db']['pre'] . 'admins')->find_one($_SESSION['admin']['id']);
            $user_id = $_SESSION['admin']['id'];
            $name = $info['name'];
            $email = $info['email'];
            if (!empty($info['image'])) {
                $avatar = $config['site_url'] . 'storage/profile/' . $info['image'];
            }
        } else {
            $user_id = $_SESSION['user']['id'];
            $user_data = get_user_data(null, $user_id);
            $name = $user_data['name'];
            $email = $user_data['email'];
            if (!empty($user_data['image'])) {
                $avatar = $config['site_url'] . 'storage/profile/' . $user_data['image'];
            }
        }
    } else if ($is_login) {
        $user_id = $_SESSION['user']['id'];
        $user_data = get_user_data(null, $user_id);
        $name = $user_data['name'];
        $email = $user_data['email'];
        if (!empty($user_data['image'])) {
            $avatar = $config['site_url'] . 'storage/profile/' . $user_data['image'];
        }
    } else if (isset($_SESSION['admin']['id'])) {
        $is_admin = '1';
        $info = ORM::for_table($config['db']['pre'] . 'admins')->find_one($_SESSION['admin']['id']);
        $user_id = $_SESSION['admin']['id'];
        $name = $info['name'];
        $email = $info['email'];
        if (!empty($info['image'])) {
            $avatar = $config['site_url'] . 'storage/profile/' . $info['image'];
        }
    } else {
        $comment_error = $lang['LOGIN_POST_COMMENT'];
    }

    if (empty($_POST['comment'])) {
        $comment_error = $lang['ALL_FIELDS_REQ'];
    } else {
        $comment = validate_input($_POST['comment']);
    }

    $duplicates = ORM::for_table($config['db']['pre'] . 'blog_comment')
        ->where('blog_id', $_POST['comment_post_ID'])
        ->where('name', $name)
        ->where('email', $email)
        ->where('comment', $comment)
        ->count();

    if ($duplicates > 0) {
        $comment_error = $lang['DUPLICATE_COMMENT'];
    }

    if (!$comment_error) {
        if ($is_admin) {
            $approve = '1';
        } else {
            if ($config['blog_comment_approval'] == 1) {
                $approve = '0';
            } else if ($config['blog_comment_approval'] == 2) {
                if ($is_login) {
                    $approve = '1';
                } else {
                    $approve = '0';
                }
            } else {
                $approve = '1';
            }
        }

        $blog_cmnt = ORM::for_table($config['db']['pre'] . 'blog_comment')->create();
        $blog_cmnt->blog_id = $_POST['comment_post_ID'];
        $blog_cmnt->user_id = $user_id;
        $blog_cmnt->is_admin = $is_admin;
        $blog_cmnt->name = $name;
        $blog_cmnt->email = $email;
        $blog_cmnt->comment = $comment;
        $blog_cmnt->created_at = date('Y-m-d H:i:s');
        $blog_cmnt->active = $approve;
        $blog_cmnt->parent = $_POST['comment_parent'];
        $blog_cmnt->save();

        $id = $blog_cmnt->id();
        $date = date('d, M Y');
        $approve_txt = '';
        if ($approve == '0') {
            $approve_txt = '<em><small>' . $lang['COMMENT_REVIEW'] . '</small></em>';
        }

        $html = '<li id="li-comment-' . $id . '"';
        if ($_POST['comment_parent'] != 0) {
            $html .= 'class="children-2"';
        }
        $html .= '>
                   <div class="comments-box" id="comment-' . $id . '">
                        <div class="comments-avatar">
                            <img src="' . $avatar . '" alt="' . $name . '">
                        </div>
                        <div class="comments-text">
                            <div class="avatar-name">
                                <h5>' . $name . '</h5>
                                <span>' . $date . '</span>
                            </div>
                            ' . $approve_txt . '
                            <p>' . nl2br(stripcslashes($comment)) . '</p>
                        </div>
                    </div>
                </li>';

        $result['success'] = true;
        $result['html'] = $html;
        $result['id'] = $id;
    } else {
        $result['success'] = false;
        $result['error'] = $comment_error;
    }
    die(json_encode($result));
}

/**
 * save restaurant order
 */
function sendRestaurantOrder(){
    global $config, $lang, $link;
    $result = array('success'=>false, 'message' => $lang['ERROR_TRY_AGAIN']);

    if (!empty($_POST['items']) && !empty($_POST['restaurant'])) {

        if (!isset($_POST['ordering-type']) || trim($_POST['ordering-type']) == '')
        {
            /* Check order type is sent */
            $result['message'] = $lang['ORDERING_TYPE_REQUIRED'];
        }
        else if (!in_array($_POST['ordering-type'], array('on-table', 'takeaway', 'delivery')))
        {
            /* Check order type is not changed */
            $result['message'] = $lang['ORDERING_TYPE_REQUIRED'];
        }
        else if (!isset($_POST['name']) || trim($_POST['name']) == '')
        {
            $result['message'] = $lang['YOUR_NAME_REQUIRED'];
        }
        else if ($_POST['ordering-type'] == 'on-table' && (!isset($_POST['table']) || trim($_POST['table']) == '' && !is_numeric($_POST['table'])))
        {
            $result['message'] = $lang['TABLE_NUMBER_REQUIRED'];
        }
        else if ($_POST['ordering-type'] != 'on-table' && (!isset($_POST['phone-number']) || trim($_POST['phone-number']) == '' && !is_numeric($_POST['phone-number'])))
        {
            $result['message'] = $lang['PHONE_NUMBER_REQUIRED'];
        }
        else if ($_POST['ordering-type'] == 'delivery' && (!isset($_POST['address']) || trim($_POST['address']) == ''))
        {
            $result['message'] = $lang['ADDRESS_REQUIRED'];
        }
        else
        {
            $amount = 0;
            $restaurant = ORM::for_table($config['db']['pre'] . 'restaurant')
                ->where('id', $_POST['restaurant'])
                ->find_one();

            if(isset($restaurant['id'])) {
                // save order
                $order = ORM::for_table($config['db']['pre'] . 'orders')->create();
                $order->restaurant_id = validate_input($_POST['restaurant']);
                $order->type = validate_input($_POST['ordering-type']);
                $order->customer_name = validate_input($_POST['name']);

                $customer_details = validate_input($_POST['name'])."\n";

                $icon_menu_item = "▪️";
                $icon_menu_extra = "▫️";
                $icon_phone = "☎️";
                $icon_hash = "#️⃣";
                $icon_address = "📌";
                $icon_message = "📝";

                $order_type = '';
                $delivery_charge = 0;
                if($_POST['ordering-type'] == 'on-table') {
                    /* on table */
                    $order->table_number = validate_input($_POST['table']);

                    $customer_details .= $icon_hash.' '.validate_input($_POST['table']);

                    $order_type = $lang['ON_TABLE'];
                } else if ($_POST['ordering-type'] == 'takeaway'){
                    /* takeaway */
                    $order->phone_number = validate_input($_POST['phone-number']);

                    $customer_details .= $icon_phone.' '.validate_input($_POST['phone-number']);

                    $order_type = $lang['TAKEAWAY'];
                } else if ($_POST['ordering-type'] == 'delivery'){
                    /* delivery */
                    $order->phone_number = validate_input($_POST['phone-number']);
                    $order->address = validate_input($_POST['address']);

                    $customer_details .= $icon_phone.' '.validate_input($_POST['phone-number'])."\n";
                    $customer_details .= $icon_address.' '.validate_input($_POST['address']);

                    $order_type = $lang['DELIVERY'];
                    $delivery_charge = get_restaurant_option($restaurant['id'],'restaurant_delivery_charge',0);
                }

                if(!empty($_POST['message'])){
                    $customer_details .= "\n".$icon_message.' '.validate_input($_POST['message'])."\n";
                }

                $order->message = validate_input($_POST['message']);
                $order->created_at = date('Y-m-d H:i:s');

                if($_POST['pay_via'] == 'pay_online'){
                    $order->status = 'unpaid';
                }

                $order->save();

                $items = json_decode($_POST['items'], true);
                $order_msg = $order_whatsapp_detail = '';

                $user_lang = !empty($_COOKIE['Quick_user_lang_code'])? $_COOKIE['Quick_user_lang_code'] : $config['lang_code'];

                foreach ($items as $item) {
                    $item_id = $item['id'];
                    $quantity = $item['quantity'];
                    $variants = $item['variants'];

                    $menu = ORM::for_table($config['db']['pre'] . 'menu')
                        ->where('id', $item_id)
                        ->find_one();

                    if(isset($menu['id'])) {
                        // save order items
                        $order_item = ORM::for_table($config['db']['pre'] . 'order_items')->create();
                        $order_item->order_id = $order->id();
                        $order_item->item_id = validate_input($item_id);
                        $order_item->quantity = validate_input($quantity);
                        $order_item->variation = is_numeric($variants) ? validate_input($variants) : 0;
                        $order_item->save();

                        $variant_title = array();
                        if(is_numeric($variants)){
                            $menu_variant = ORM::for_table($config['db']['pre'] . 'menu_variants')
                                ->where('id', $variants)
                                ->where('menu_id', $item_id)
                                ->find_one();

                            if(!empty($menu_variant['options'])) {
                                $menu['price'] = $menu_variant['price'];

                                $menu_variant['options'] = json_decode($menu_variant['options'], true);

                                foreach ($menu_variant['options'] as $option_id => $option_key) {
                                    $menu_variant_option = ORM::for_table($config['db']['pre'] . 'menu_variant_options')
                                        ->where('id', $option_id)
                                        ->find_one();

                                    $json = json_decode($menu_variant_option['translation'], true);
                                    $menu_variant_option['options'] = !empty($json[$user_lang]['options'])
                                        ? $json[$user_lang]['options']
                                        : json_decode($menu_variant_option['options'], true);

                                    $variant_title[] = $menu_variant_option['options'][$option_key];
                                }
                            }
                        }
                        $variant_title = !empty($variant_title) ? ' ('.implode(', ', $variant_title).')' : '';

                        $amount += $menu['price'] * $quantity;

                        if(!$config['email_template']){
                            $order_msg .= $menu['name'].$variant_title. ($quantity > 1 ? ' &times; '.$quantity:'').'<br>';
                        }else{
                            $order_msg .= $menu['name'].$variant_title. ($quantity > 1 ? ' X '.$quantity:'')."\n";
                        }

                        $json = json_decode($menu['translation'],true);
                        $title = !empty($json[$user_lang]['title'])?$json[$user_lang]['title']:$menu['name'];

                        $order_whatsapp_detail .= $icon_menu_item.$title.$variant_title. ' X '.$quantity."\n";

                        $extras = $item['extras'];
                        foreach ($extras as $extra) {
                            $menu_extra = ORM::for_table($config['db']['pre'] . 'menu_extras')
                                ->where('id', $extra['id'])
                                ->find_one();

                            if(isset($menu_extra['id'])) {
                                // save order items extras
                                $order_item_extras = ORM::for_table($config['db']['pre'] . 'order_item_extras')->create();
                                $order_item_extras->order_item_id = $order_item->id();
                                $order_item_extras->extra_id = validate_input($extra['id']);
                                $order_item_extras->save();

                                $amount += $menu_extra['price'] * $quantity;

                                if(!$config['email_template']){
                                    $order_msg .= $menu_extra['title'].'<br>';
                                }else{
                                    $order_msg .= $menu_extra['title']."\n";
                                }

                                $json = json_decode($menu_extra['translation'],true);
                                $title = !empty($json[$user_lang]['title'])?$json[$user_lang]['title']:$menu_extra['title'];

                                $order_whatsapp_detail .= $icon_menu_extra.$title."\n";
                            }
                        }
                        if(!$config['email_template']){
                            $order_msg .= '<br>';
                        }else{
                            $order_msg .= "\n";
                        }
                    }
                }
                $amount += $delivery_charge;

                if(get_restaurant_option($restaurant['id'], 'restaurant_send_order_notification', 1)){
                    $page = new HtmlTemplate();
                    $page->html = $config['email_sub_new_order'];
                    $page->SetParameter('RESTAURANT_NAME', $restaurant['name']);
                    $page->SetParameter('CUSTOMER_NAME', validate_input($_POST['name']));
                    $page->SetParameter('TABLE_NUMBER', validate_input($_POST['table']));
                    $page->SetParameter('PHONE_NUMBER', validate_input($_POST['phone-number']));
                    $page->SetParameter('ADDRESS', validate_input($_POST['address']));
                    $page->SetParameter('ORDER_TYPE', $order_type);
                    $email_subject = $page->CreatePageReturn($lang, $config, $link);

                    $page = new HtmlTemplate();
                    $page->html = $config['email_message_new_order'];
                    $page->SetParameter('RESTAURANT_NAME', $restaurant['name']);
                    $page->SetParameter('CUSTOMER_NAME', validate_input($_POST['name']));
                    $page->SetParameter('TABLE_NUMBER', validate_input($_POST['table']));
                    $page->SetParameter('PHONE_NUMBER', validate_input($_POST['phone-number']));
                    $page->SetParameter('ADDRESS', validate_input($_POST['address']));
                    $page->SetParameter('ORDER_TYPE', $order_type);
                    $page->SetParameter('ORDER', $order_msg);
                    $page->SetParameter('MESSAGE', validate_input($_POST['message']));
                    $email_body = $page->CreatePageReturn($lang, $config, $link);

                    $userdata = get_user_data(null,$restaurant['user_id']);

                    /* send email to restaurants */
                    email($userdata['email'], $userdata['name'], $email_subject, $email_body);
                }

                $result['success'] = true;
                $result['message'] = '';
                $result['whatsapp_url'] = '';

                if($config['quickorder_enable']) {
                    if (get_restaurant_option($restaurant['id'], 'quickorder_enable', 0)) {
                        $whatsapp_number = get_restaurant_option($restaurant['id'], 'whatsapp_number');

                        $whatsapp_message = get_restaurant_option($restaurant['id'], 'whatsapp_message');

                        if (empty($whatsapp_message))
                            $whatsapp_message = $config['quickorder_whatsapp_message'];

                        $userdata = get_user_data(null, $restaurant['user_id']);
                        $currency = !empty($userdata['currency']) ? $userdata['currency'] : get_option('currency_code');

                        $page = new HtmlTemplate();
                        $page->html = $whatsapp_message;
                        $page->SetParameter('ORDER_ID', $order->id());
                        $page->SetParameter('ORDER_DETAILS', $order_whatsapp_detail);
                        $page->SetParameter('CUSTOMER_DETAILS', $customer_details);
                        $page->SetParameter('ORDER_TYPE', $order_type);
                        $page->SetParameter('ORDER_TOTAL', price_format($amount, $currency, false));
                        $whatsapp_message = $page->CreatePageReturn($lang, $config, $link);

                        $result['whatsapp_url'] = 'https://api.whatsapp.com/send?phone=' . $whatsapp_number . '&text=' . urlencode($whatsapp_message);
                    }
                }


                if($_POST['pay_via'] == 'pay_online'){
                    /* Save in session for payment page */
                    $payment_type = "order";
                    $access_token = uniqid();

                    $_SESSION['quickad'][$access_token]['name'] = validate_input($restaurant['name']);
                    $_SESSION['quickad'][$access_token]['restaurant_id'] = $restaurant['id'];
                    $_SESSION['quickad'][$access_token]['amount'] = $amount;
                    $_SESSION['quickad'][$access_token]['payment_type'] = $payment_type;
                    $_SESSION['quickad'][$access_token]['order_id'] = $order->id();
                    $_SESSION['quickad'][$access_token]['whatsapp_url'] = $result['whatsapp_url'];
                    $_SESSION['quickad'][$access_token]['phone'] = isset($_POST['phone-number']) ? validate_input($_POST['phone-number']) : '';

                    $url = $link['PAYMENT']."/" . $access_token;
                    $result['message'] = $url;
                }
            }
        }
    }
    die(json_encode($result));
}

/**
 * Complete order
 */
function completeOrder(){
    global $config, $lang;
    $result = array('success'=>false, 'message' => $lang['ERROR_TRY_AGAIN']);
    if(isset($_POST['id'])) {
        // get restaurant
        $restaurant = ORM::for_table($config['db']['pre'] . 'restaurant')
            ->where('user_id', $_SESSION['user']['id'])
            ->find_one();

        $orders = ORM::for_table($config['db']['pre'] . 'orders')
            ->where(array(
                'restaurant_id' => $restaurant['id'],
                'id' => $_POST['id']
            ))
            ->find_one();
        $orders->status = 'completed';
        $orders->save();

        $result['success'] = true;
        $result['message'] = '';
    }
    die(json_encode($result));
}

/**
 * Delete order
 */
function deleteOrder(){
    global $config, $lang;
    $result = array('success'=>false, 'message' => $lang['ERROR_TRY_AGAIN']);
    if(isset($_POST['id'])) {
        // get restaurant
        $restaurant = ORM::for_table($config['db']['pre'] . 'restaurant')
            ->where('user_id', $_SESSION['user']['id'])
            ->find_one();

        // get order
        $orders = ORM::for_table($config['db']['pre'] . 'orders')
            ->where(array(
                'restaurant_id' => $restaurant['id'],
                'id' => $_POST['id']
            ))
            ->find_one();

        if(isset($orders['id'])){
            // get order items
            $order_items = ORM::for_table($config['db']['pre'] . 'order_items')
                ->where(array(
                    'order_id' => $orders['id']
                ))
                ->find_many();

            foreach ($order_items as $order_item){
                // delete item extras
                ORM::for_table($config['db']['pre'] . 'order_item_extras')
                    ->where(array(
                        'order_item_id' => $order_item['id']
                    ))
                    ->delete_many();
            }

            // delete order items
            ORM::for_table($config['db']['pre'] . 'order_items')
                ->where(array(
                    'order_id' => $orders['id']
                ))
                ->delete_many();

            // delete order
            ORM::for_table($config['db']['pre'] . 'orders')
                ->where(array(
                    'restaurant_id' => $restaurant['id'],
                    'id' => $orders['id']
                ))
                ->delete_many();
        }

        $result['success'] = true;
        $result['message'] = '';
    }
    die(json_encode($result));
}

/**
 * Check store slug validation
 */
function checkStoreSlug()
{
    global $config, $lang, $link;

    if (empty($_POST['slug'])) {
        $slug_error = $lang['RESTRO_SLUG_REQ'];
        echo "<span class='status-not-available'> ".$slug_error."</span>";

    } else if(!preg_match('/^[a-z0-9]+(-?[a-z0-9]+)*$/i', $_POST['slug'])) {
        $slug_error = $lang['RESTRO_SLUG_INVALID'];
        echo "<span class='status-not-available'> " . $slug_error . "</span>";

    } else if(in_array($config['site_url'].$_POST['slug'], $link)){
        $slug_error = $lang['RESTRO_SLUG_INVALID'];
        echo "<span class='status-not-available'> ".$slug_error."</span>";

    } else {
        $count = ORM::for_table($config['db']['pre'].'restaurant')
            ->where('slug', $_POST['slug'])
            ->where_not_equal('user_id',$_SESSION['user']['id'])
            ->count();

        // check row exist
        if ($count) {
            $slug_error = $lang['RESTRO_SLUG_NOT_EXIST'];
            echo "<span class='status-not-available'> ".$slug_error."</span>";
        } else {
            $slug_success = $lang['SUCCESS'];
            echo "";
        }
    }
    die();
}

/**
 * Call the waiter
 */
function callTheWaiter()
{
    global $config;

    if(isset($_POST['restaurant'], $_POST['table'])) {
        $order_item = ORM::for_table($config['db']['pre'] . 'waiter_call')->create();
        $order_item->restaurant_id = validate_input($_POST['restaurant']);
        $order_item->table_no = validate_input($_POST['table']);
        $order_item->save();
    }
    echo 1;
}

/**
 * Quick HeartBeat
 */
function quickHeartBeat()
{
    $result = array(
        'orders' => getOrders(),
        'waiterCalls' => getWaiterCalls()
    );
    die(json_encode($result));
}

/**
 * Get order for notifications
 * @return array
 */
function getOrders(){
    global $config, $lang;
    $orders_data = array();

    if (checkloggedin()) {
        $ses_userdata = get_user_data($_SESSION['user']['username']);
        $currency = !empty($ses_userdata['currency']) ? $ses_userdata['currency'] : get_option('currency_code');

        $restaurant = ORM::for_table($config['db']['pre'] . 'restaurant')
            ->where('user_id', $_SESSION['user']['id'])
            ->find_one();

        if (isset($restaurant['user_id'])) {
            // get orders
            $orders = ORM::for_table($config['db']['pre'] . 'orders')
                ->where(array(
                    'restaurant_id' => $restaurant['id'],
                    'seen' => 0
                ))
                ->where_not_equal('status','unpaid')
                ->order_by_desc('id')
                ->find_many();

            foreach ($orders as $order) {
                $orders_data[$order['id']]['id'] = $order['id'];
                $orders_data[$order['id']]['type'] = $order['type'];
                $orders_data[$order['id']]['customer_name'] = $order['customer_name'];
                $orders_data[$order['id']]['table_number'] = $order['table_number'];
                $orders_data[$order['id']]['phone_number'] = $order['phone_number'];
                $orders_data[$order['id']]['address'] = $order['address'];
                $orders_data[$order['id']]['is_paid'] = $order['is_paid'];
                $orders_data[$order['id']]['status'] = $order['status'];
                $orders_data[$order['id']]['message'] = $order['message'];
                $orders_data[$order['id']]['created_at'] = date('d M Y h:i A',strtotime($order['created_at']));

                // get order items
                $order_items = ORM::for_table($config['db']['pre'] . 'order_items')
                    ->table_alias('oi')
                    ->select_many('oi.*', 'm.name', 'm.price')
                    ->where(array(
                        'order_id' => $order['id']
                    ))
                    ->join($config['db']['pre'] . 'menu', array('oi.item_id', '=', 'm.id'), 'm')
                    ->order_by_desc('id')
                    ->find_many();

                $orders_data[$order['id']]['items_tpl'] = $print_tpl = '';
                $price = 0;
                foreach ($order_items as $order_item) {

                    /* Menu Variants */
                    $variant_title = array();
                    if(is_numeric($order_item['variation'])){
                        $menu_variant = ORM::for_table($config['db']['pre'] . 'menu_variants')
                            ->where('id', $order_item['variation'])
                            ->where('menu_id', $order_item['item_id'])
                            ->find_one();

                        if(!empty($menu_variant['options'])) {
                            $order_item['price'] = $menu_variant['price'];

                            $menu_variant['options'] = json_decode($menu_variant['options'], true);

                            foreach ($menu_variant['options'] as $option_id => $option_key) {
                                $menu_variant_option = ORM::for_table($config['db']['pre'] . 'menu_variant_options')
                                    ->where('id', $option_id)
                                    ->find_one();

                                $menu_variant_option['options'] = json_decode($menu_variant_option['options'], true);

                                $variant_title[] = $menu_variant_option['options'][$option_key];
                            }
                        }
                    }
                    $variant_title = !empty($variant_title) ? ' <small>('.implode(', ', $variant_title).')</small>' : '';

                    $tpl = '<div class="order-table-item">';
                    $tpl .= '<strong><i class="icon-material-outline-restaurant"></i> '.$order_item['name'].$variant_title.'</strong>';
                    if($order_item['quantity'] > 1){
                        $tpl .= ' &times; '.$order_item['quantity'];
                    }
                    $price += $order_item['price'] * $order_item['quantity'];

                    $print_tpl_extra = $print_tpl_menu = '';

                    $print_tpl_menu .= '<tr><td>'.$order_item['name'].$variant_title.' &times; '.$order_item['quantity'].'</td><td>'.price_format($order_item['price'] * $order_item['quantity'],$currency).'</td></tr>';

                    // get order extras
                    $order_item_extras = ORM::for_table($config['db']['pre'] . 'order_item_extras')
                        ->table_alias('oie')
                        ->select_many('oie.*', 'me.title', 'me.price')
                        ->where(array(
                            'order_item_id' => $order_item['id']
                        ))
                        ->join($config['db']['pre'] . 'menu_extras', array('oie.extra_id', '=', 'me.id'), 'me')
                        ->order_by_desc('id')
                        ->find_many();

                    if($order_item_extras->count()) {
                        $tpl .= '<div  class="padding-left-10">';
                        foreach ($order_item_extras as $order_item_extra) {
                            $price += $order_item_extra['price'] * $order_item['quantity'];
                            $tpl .= '<div><i class="icon-feather-plus"></i> ' . $order_item_extra['title'].'</div>';
                            $print_tpl_extra .= '<tr class="order-menu-extra"><td><span class="margin-left-5">'.$order_item_extra['title'].'</span></td><td>'.price_format($order_item_extra['price'] * $order_item['quantity'],$currency).'</td></tr>';
                        }
                        $tpl .= '</div>';
                    }
                    $tpl .= '</div>';
                    $orders_data[$order['id']]['items_tpl'] .= $tpl;
                    $print_tpl .= $print_tpl_menu . $print_tpl_extra;
                }

                $delivery_charge = 0;

                if($orders_data[$order['id']]['type'] == 'on-table')
                    $type = $orders_data[$order['id']]['table_number'];
                elseif($orders_data[$order['id']]['type'] == 'takeaway')
                    $type = $lang['TAKEAWAY'];
                elseif($orders_data[$order['id']]['type'] == 'delivery'){
                    $type = $lang['DELIVERY'];
                    $delivery_charge = get_restaurant_option($restaurant['id'],'restaurant_delivery_charge',0);
                }

                $orders_data[$order['id']]['price'] = price_format($price + $delivery_charge,$currency);

                if($delivery_charge){
                    $print_tpl .= '<tr><td>'.$lang['DELIVERY_CHARGE'].'</td><td>'.price_format($delivery_charge,$currency).'</td></tr>';
                }

                $order_print_tpl = "<table>
                            <tr>
                                <td>{$lang['TIME']}</td>
                                <td>{$orders_data[$order['id']]['created_at']}</td>
                            </tr>
                            <tr>
                                <td>{$lang['NAME']}</td>
                                <td>{$orders_data[$order['id']]['customer_name']}</td>
                            </tr>
                            <tr>
                                <td>{$lang['TABLE_NO_ORDER_TYPE']}</td>
                                <td>$type</td>
                            </tr>".
                    (!empty($orders_data[$order['id']]['phone_number'])?"<tr>
                                <td>{$lang['PHONE']}</td>
                                <td>{$orders_data[$order['id']]['phone_number']}</td>
                            </tr>":'')
                    .
                    (!empty($orders_data[$order['id']]['address'])?"<tr>
                                <td>{$lang['ADDRESS']}</td>
                                <td>{$orders_data[$order['id']]['address']}</td>
                            </tr>":'').
                    (!empty($orders_data[$order['id']]['message'])?"<tr>
                                <td>{$lang['MESSAGE']}</td>
                                <td>{$orders_data[$order['id']]['message']}</td>
                            </tr>":'').
                    (!empty($orders_data[$order['id']]['is_paid'])?"<tr>
                                <td>{$lang['PAYMENT']}</td>
                                <td>{$lang['PAID']}</td>
                            </tr>":'')."
                        </table>
                        <div class='order-print-divider'></div>
                        <table class='order-print-menu'>
                            <thead>
                            <tr>
                                <th>{$lang['MENU']}</th>
                                <th>{$lang['PRICE']}</th>
                            </tr>
                            </thead>
                            <tbody id='order-print-menu'>$print_tpl</tbody>
                            <tfoot>
                            <tr>
                                <th>{$lang['TOTAL']}</th>
                                <td>{$orders_data[$order['id']]['price']}</td>
                            </tr>
                            </tfoot>
                        </table>";

                $orders_data[$order['id']]['order_print_tpl'] = $order_print_tpl;

                $orders = ORM::for_table($config['db']['pre'] . 'orders')->find_one($order['id']);
                $orders->seen = 1;
                $orders->save();
            }
        }
    }
    return $orders_data;
}

/**
 * Get waiter calls
 * @return array
 */
function getWaiterCalls()
{
    global $config, $lang;
    $notifications = array();
    if (checkloggedin()) {

        $restaurant = ORM::for_table($config['db']['pre'] . 'restaurant')
            ->where('user_id', $_SESSION['user']['id'])
            ->find_one();

        if (isset($restaurant['user_id'])) {
            // get calls
            $calls = ORM::for_table($config['db']['pre'] . 'waiter_call')
                ->where(array(
                    'restaurant_id' => $restaurant['id'],
                    'seen' => 0
                ))
                ->find_many();

            foreach ($calls as $call) {
                $notifications[] = '<i class="fa fa-bell"></i> ' . $lang['CALL_WAITER_MSG'] .' '. $call['table_no'];

                ORM::for_table($config['db']['pre'] . 'waiter_call')
                    ->where(array(
                        'id' => $call['id']
                    ))
                    ->delete_many();
            }
        }
    }

    return $notifications;
}

/**
 * Add variant option
 */
function addVariantOption() {
    global $config, $lang;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    $title = validate_input($_POST['title']);
    $options = validate_input($_POST['options']);
    $menu_id = validate_input($_POST['menu_id']);

    if (trim($menu_id) == '' || empty($menu_id)) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    if (trim($title) == '' || empty($title)) {
        $result['success'] = false;
        $result['message'] = $lang['ALL_FIELDS_REQ'];
        die(json_encode($result));
    }

    if (trim($options) == '' || empty($options)) {
        $result['success'] = false;
        $result['message'] = $lang['ALL_FIELDS_REQ'];
        die(json_encode($result));
    }

    $options = explode(',', $options);
    $options = array_map('trim', $options);

    $user_lang = !empty($_COOKIE['Quick_user_lang_code'])? $_COOKIE['Quick_user_lang_code'] : $config['lang_code'];
    $json = array();
    $json[$user_lang] = array('title'=> $title, 'options' => $options);

    $insert = ORM::for_table($config['db']['pre'] . 'menu_variant_options')->create();
    $insert->title = $title;
    $insert->options = json_encode($options, JSON_UNESCAPED_UNICODE);
    $insert->translation = json_encode($json, JSON_UNESCAPED_UNICODE);
    $insert->menu_id = $menu_id;
    $insert->save();

    $id = $insert->id();

    if ($id) {
        $result['success'] = true;
        $result['message'] = $lang['SAVED_SUCCESS'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }

    die(json_encode($result));
}

/**
 * Edit variant option
 */
function editVariantOption()
{
    global $config, $lang;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    $title = validate_input($_POST['title']);
    $options = validate_input($_POST['options']);
    $id = validate_input($_POST['id']);

    if (trim($id) == '' || empty($id)) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    if (trim($title) == '' || empty($title)) {
        $result['success'] = false;
        $result['message'] = $lang['ALL_FIELDS_REQ'];
        die(json_encode($result));
    }

    if (trim($options) == '' || empty($options)) {
        $result['success'] = false;
        $result['message'] = $lang['ALL_FIELDS_REQ'];
        die(json_encode($result));
    }

    $options = explode(',', $options);
    $options = array_map('trim', $options);

    $insert = ORM::for_table($config['db']['pre'] . 'menu_variant_options')->find_one($id);

    $user_lang = !empty($_COOKIE['Quick_user_lang_code']) ? $_COOKIE['Quick_user_lang_code'] : $config['lang_code'];
    $json = json_decode($insert['translation'], true);
    $json[$user_lang] = array('title' => $title, 'options' => $options);

    $insert->translation = json_encode($json, JSON_UNESCAPED_UNICODE);
    $insert->active = isset($_POST['active']) ? 1 : 0;
    $insert->save();

    $result['success'] = true;
    $result['message'] = $lang['SAVED_SUCCESS'];


    die(json_encode($result));
}

/**
 * Delete variant option
 */
function deleteVariantOption()
{
    global $lang, $config;

    $result['success'] = false;
    $result['message'] = $lang['ERROR_TRY_AGAIN'];
    if (!checkloggedin()) {
        die(json_encode($result));
    }
    $id = $_POST['id'];
    if (trim($id) != '') {
        // check menu is with same user
        $variant_option = ORM::for_table($config['db']['pre'] . 'menu_variant_options')->find_one($id);

        if (!empty($variant_option['menu_id'])) {
            $menu = ORM::for_table($config['db']['pre'] . 'menu')
                ->where(array(
                    'id' => $variant_option['menu_id'],
                    'user_id' => $_SESSION['user']['id'],
                ))
                ->find_one();

            if (!empty($menu['id'])) {
                $data = ORM::for_table($config['db']['pre'] . 'menu_variant_options')
                    ->where(array(
                        'id' => $id
                    ))
                    ->delete_many();

                if ($data) {
                    $result['success'] = true;
                    $result['message'] = $lang['SUCCESS_DELETE'];
                }
            }
        }
    }
    die(json_encode($result));
}

/**
 * Update variant options positions
 */
function updateVariantOptionsPosition()
{
    global $config,$lang;
    $con = ORM::get_db();
    $position = $_POST['position'];
    if (is_array($position)) {
        foreach($position as $key => $id){
            $query = "UPDATE `".$config['db']['pre']."menu_variant_options` SET `position` = '".$key."' WHERE `id` = '" . $id . "'";
            $con->query($query);
        }

        $result['success'] = true;
        $result['message'] = $lang['POSITION_UPDATED'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}

/**
 * Add variant
 */
function addVariant() {
    global $config, $lang;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    $price = validate_input($_POST['price']);
    $menu_id = validate_input($_POST['menu_id']);

    if (trim($price) == '' || empty($price)) {
        $result['success'] = false;
        $result['message'] = $lang['ALL_FIELDS_REQ'];
        die(json_encode($result));
    }

    if (trim($menu_id) == '' || empty($menu_id)) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    /* Process the submitted options */
    $variant_options = json_encode($_POST['variant_option']);

    $insert = ORM::for_table($config['db']['pre'] . 'menu_variants')->create();
    $insert->price = $price;
    $insert->menu_id = $menu_id;
    $insert->options = $variant_options;
    $insert->save();

    $id = $insert->id();

    if ($id) {
        $result['success'] = true;
        $result['message'] = $lang['SAVED_SUCCESS'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }

    die(json_encode($result));
}

/**
 * Edit variant
 */
function editVariant()
{
    global $config, $lang;
    if (!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    $price = validate_input($_POST['price']);
    $id = validate_input($_POST['id']);

    if (trim($id) == '' || empty($id)) {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
        die(json_encode($result));
    }

    if (trim($price) == '' || empty($price)) {
        $result['success'] = false;
        $result['message'] = $lang['ALL_FIELDS_REQ'];
        die(json_encode($result));
    }

    /* Process the submitted options */
    $variant_options = json_encode($_POST['variant_option']);

    $insert = ORM::for_table($config['db']['pre'] . 'menu_variants')->find_one($id);
    $insert->price = $price;
    $insert->options = $variant_options;
    $insert->active = isset($_POST['active']) ? 1 : 0;
    $insert->save();

    $id = $insert->id();

    if ($id) {
        $result['success'] = true;
        $result['message'] = $lang['SAVED_SUCCESS'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }

    die(json_encode($result));
}

/**
 * Delete variant
 */
function deleteVariant()
{
    global $lang, $config;

    $result['success'] = false;
    $result['message'] = $lang['ERROR_TRY_AGAIN'];
    if (!checkloggedin()) {
        die(json_encode($result));
    }
    $id = $_POST['id'];
    if (trim($id) != '') {
        // check menu is with same user
        $variant = ORM::for_table($config['db']['pre'] . 'menu_variants')->find_one($id);

        if (!empty($variant['menu_id'])) {
            $menu = ORM::for_table($config['db']['pre'] . 'menu')
                ->where(array(
                    'id' => $variant['menu_id'],
                    'user_id' => $_SESSION['user']['id'],
                ))
                ->find_one();

            if (!empty($menu['id'])) {
                $data = ORM::for_table($config['db']['pre'] . 'menu_variants')
                    ->where(array(
                        'id' => $id
                    ))
                    ->delete_many();

                if ($data) {
                    $result['success'] = true;
                    $result['message'] = $lang['SUCCESS_DELETE'];
                }
            }
        }
    }
    die(json_encode($result));
}

/**
 * Update variants positions
 */
function updateVariantsPosition()
{
    global $config,$lang;
    $con = ORM::get_db();
    $position = $_POST['position'];
    if (is_array($position)) {
        foreach($position as $key => $id){
            $query = "UPDATE `".$config['db']['pre']."menu_variants` SET `position` = '".$key."' WHERE `id` = '" . $id . "'";
            $con->query($query);
        }

        $result['success'] = true;
        $result['message'] = $lang['POSITION_UPDATED'];
    } else {
        $result['success'] = false;
        $result['message'] = $lang['ERROR_TRY_AGAIN'];
    }
    die(json_encode($result));
}