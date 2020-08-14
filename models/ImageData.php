<?php
  class ImageData {

    private $conn;
    private $table = 'imageData';

    public $id;
    public $name;
    public $url;
    public $page;
    public $requestCount;

    public function __construct($db) {
      $this->conn = $db;
    }

    public function getAllImageData() {
      $query = 'SELECT
        id,
        name,
        url,
        page,
        requestCount
      FROM
        ' . $this->table . '
      ORDER BY
        id DESC';

      $stmt = $this->conn->prepare($query);

      $stmt->execute();

      return $stmt;
    }

    public function getImageById(){

      $query = 'SELECT
          id,
          name,
          url,
          page,
          requestCount
        FROM
            ' . $this->table . '
        WHERE id = ?
        LIMIT 0,1';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->url = $row['url'];
        $this->page = $row['page'];
        $this->requestCount = $row['requestCount'];
    }

     public function getImageByPage(){
        $query = 'SELECT
            id,
            name,
            url,
            page,
            requestCount
          FROM
              ' . $this->table . '
          WHERE page = ?
          LIMIT 0,1';

          $stmt = $this->conn->prepare($query);

          $stmt->bindParam(1, $this->page);

          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          $this->id = $row['id'];
          $this->name = $row['name'];
          $this->url = $row['url'];
          $this->page = $row['page'];
          $this->requestCount = $row['requestCount'];
      }

      public function getMostPopularImage(){

          $query = 'SELECT
              id,
              name,
              url,
              page,
              requestCount
            FROM
              ' . $this->table . '
            WHERE requestCount = (SELECT MAX(requestCount) FROM  ' . $this->table . ')';

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
      }
       public function create() {

                $page = $this->getPage();

                $query = 'INSERT INTO ' . $this->table . " SET name = :name, url = :url, page = $page, requestCount = 0";

                $stmt = $this->conn->prepare($query);

                $this->name = htmlspecialchars(strip_tags($this->name));
                $this->url = htmlspecialchars(strip_tags($this->url));
                $this->page = htmlspecialchars(strip_tags($this->page));


                $stmt->bindParam(':name', $this->name);
                $stmt->bindParam(':url', $this->url);
  
                if($stmt->execute()) {
                  return true;
            }

            printf("Error: %s.\n", $stmt->error);

            return false;
      }

      private function getPage(){


        $query = "SELECT COUNT(id) AS id_num ,page FROM " . $this->table . " 
                        WHERE page = (SELECT MAX(page) FROM  " . $this->table . ")
                        ";
             $stmt = $this->conn->prepare($query);

             $stmt->execute();

              $row = $stmt->fetch(PDO::FETCH_ASSOC);


              $id_num = $row['id_num'];
              $last_page = $row['page'];

              $new_page = intval($last_page) + 1;

               if($id_num>=9){
                    return $new_page;
                  }else{
                    return intval($last_page);
                }

      }

  }
