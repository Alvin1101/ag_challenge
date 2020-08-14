<?php

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/ImageData.php';

  $database = new Database();
  $db = $database->connect();

  $imageData = new ImageData($db);


  $imageData->page = isset($_GET['page']) ? $_GET['page'] : die();

  $imageData->getImageByPage();

  $image_arr = array(
    'id' => $imageData->id,
    'name' => $imageData->name,
    'url' => $imageData->url,
    'page' => $imageData->page,
    'requestCount' => $imageData->requestCount
  );

  print_r(json_encode($image_arr));
