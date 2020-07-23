<?php

  defined('_DIRECT_ACCESS_CHECK') or exit();

  $variables = [
      'SITE_TITLE' => 'FlashPaper :: Self-Destructing Message',
  ];

  foreach ($variables as $key => $value) {
      putenv("$key=$value");
  }
?>