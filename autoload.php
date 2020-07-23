<?php

  defined('_DIRECT_ACCESS_CHECK') or exit();

  if(file_exists('./env.php')) {
      include './env.php';
  } else {
    echo 'Please make sure env.php is readable';
    die();
  }

  if(!function_exists('env')) {
      function env($key, $default = null)
      {
          $value = getenv($key);

          if ($value === false) {
              return $default;
          }

          return $value;
      }
  }

?>