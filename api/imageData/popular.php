<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/ImageData.php';

  $database = new Database();
  $db = $database->connect();

  $imageData = new ImageData($db);

  $result = $imageData->getMostPopularImage();
  
  $num = $result->rowCount();

  if($num > 0) {

        $cat_arr = array();
        $cat_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $cat_item = array(
            'id' => $id,
            'name' => $name,
            'url' => $url,
            'page' => $page,
            'requestCount' => $requestCount
          );

          array_push($cat_arr['data'], $cat_item);
        }

        echo json_encode($cat_arr);

  } else {
        echo json_encode(
          array('message' => 'No Image Found')
        );
  }
