<?php

require_once 'config.php';

function redirect($uri)
{
  $full_url = 'http://'. $_SERVER['SERVER_NAME'] . $uri;
  header('Location: '.$full_url, true, 302);
  exit();
}

function save_json_data($data, $from_addr) {
  try {
    $dbh = new PDO('mysql:host=localhost;dbname=' . Config::DB_NAME,
                   Config::DB_USER_NAME,
                   Config::DB_PASSWORD);

    $sql = 'INSERT INTO formsubmissions (timestamp, from_addr, form_data) VALUES (NOW(), :fromaddr, :data)';
    $stmt = $dbh->prepare($sql);
    $result = $stmt->execute(array(':fromaddr' => $from_addr,
                                   ':data' => $data));
    return $result;
  } catch(PDOException $e) {
    return false;
  }
}

function submit_main() {
  if(! isset($_POST['ok'])) {
    redirect(Config::NO_OK_REDIRECT_PATH);
  }

  $data = array();
  foreach(array_keys($_POST) as $key) {
    if($key != 'ok')
      $data[$key] = $_POST[$key];
  }

  save_json_data(json_encode($data), $_SERVER['REMOTE_ADDR']);
  redirect(Config::SUCCESS_REDIRECT_PATH);
}

submit_main();
