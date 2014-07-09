<?php

require_once 'config.php';

function redirect($uri)
{
  $full_url = 'http://'. $_SERVER['SERVER_NAME'] . $uri;
  header('Location: '.$full_url, true, 302);
  exit();
}

function load_data()
{
  try {
    $dbh = new PDO('mysql:host=localhost;dbname=' . Config::DB_NAME,
                   Config::DB_USER_NAME,
                   Config::DB_PASSWORD);
    $statement = $dbh->query('SELECT * FROM formsubmissions;');
    $results = $statement->fetchAll();
    return $results;
  } catch(PDOException $e) {
    return false;
  }
}

function format_field($data, $type)
{
  if($type!='strarray')
    return $data;
  $items = array();
  foreach($data as $d)
    $items[] = $d;
  return '{' . implode($items,',') . '}';
}

function list_main()
{
  if((! isset($_GET['key'])) || ($_GET['key']!=Config::REPORT_KEY)) {
    redirect(Config::NO_OK_REDIRECT_PATH);
  }
  $results = load_data();
  if($results != false) {
    foreach($results as $result) {
      $form_json = $result['form_data'];
      $form_raw = json_decode($form_json);
      $data = array();
      foreach(Config::$REPORT_FIELDS as $f => $t) {
        $data[] = format_field($form_raw->$f, $t);
      }
      echo htmlspecialchars(implode($data,',')) . '<br>';
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>ยินดีต้อนรับสู่คณะวิศวกรรมศาสตร์ ม.เกษตรศาสตร์ บางเขน</title>
  <link rel="stylesheet" href="css/pure/pure-min.css">
  <link rel="stylesheet" href="css/base.css">
  <script src="js/jquery-1.11.1.min.js"></script>
</head>
<body>
  <?php
  list_main();
?>
</body>
</html>
