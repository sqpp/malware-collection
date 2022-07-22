<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a trade license awarded by
 * Garamo Online L.T.D.
 *
 * Any use, reproduction, modification or distribution
 * of this source file without the written consent of
 * Garamo Online L.T.D It Is prohibited.
 *
 * @author    ReactionCode <info@reactioncode.com>
 * @copyright 2015-2020 Garamo Online L.T.D
 * @license   Commercial license
 */

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/rc_pgtagmanager.php');

// Make sure the request is POST, token exist and module is installed
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Tools::getValue('token') || !Module::isEnabled('rc_pgtagmanager')) {
    // Pretend we're not here if the message is invalid
    http_response_code(404);
    die;
}

try {
    $rcpgtmanager = new Rc_PgTagManager();

    if (Tools::getValue('token') === $rcpgtmanager->secret_key) {
        // get all post values is compliant with PS standards
        $post_values = Tools::getAllValues();

        // check if exist the data value
        if (isset($post_values['data'])) {
            // convert json data to array
            $data = json_decode($post_values['data'], true);
        }

        // check is array and is not empty
        if (is_array($data) && $data) {
            $rcpgtmanager->ajaxCall($data);
        } else {
            throw new Exception('Invalid data');
        }
    } else {
        throw new Exception('Invalid token');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo 'Error: ', $e->getMessage();
}
