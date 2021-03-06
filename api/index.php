<?php
class db{
    private $link;

    public function __construct(){
        $this->connect();
    }

    private function connect(){
        try{
            $this->link = new SQLite3('/tmp/db.sqlite');
            $this->link->query('CREATE TABLE IF NOT EXISTS "visits" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                "url" VARCHAR,
                "time" DATETIME
            )');
        }catch(PDOException $e){
            die('Не могу подключиться к БД!!!');
        }
    }

    public function query($sql, $params = []){
        $stmt = $this->link->prepare($sql);

        $keyArray = array_keys($params);
        $i = 0;
        foreach ($params as $key) {
            $stmt->bindValue($keyArray[$i++], $key);
        }

        $read = $stmt->execute();
        $i = 0;
        while($res[$i]=$read->fetchArray(SQLITE3_ASSOC)) {$i++;}
        $res = array_diff($res, ['']);
        return $res;
    }

    public function make($sql, $params = []){
        $stmt = $this->link->prepare($sql);

        $keyArray = array_keys($params);
        $i = 0;
        foreach ($params as $key) {
            if (($stmt->bindValue($keyArray[$i++], $key)) != 1) {
                return 0; //Ошибка при передачи параметров в запрос.
            }
        }
        $stmt->execute();
        return 1;
    }
}

echo '<pre>';
$test = new db();

$params = [
    ':url'=>'test1',
    ':time'=>'urlsssss'
];
print_r($test->make('INSERT INTO "visits" ("url", "time") VALUES (:url, :time)', $params));

//$params = [
//    ':id'=>839
//];
//$test->make("DELETE FROM `visits` WHERE `visits`.`id` = :id", $params);

//$params = [
//    ':id'=>840,
//    ':url'=>'lf,'
//];
//$test->make("UPDATE `visits` SET `url` = :url WHERE `visits`.`id` = :id", $params);

//print_r($test->make("UPDATE `test` SET `text` = ? WHERE `test`.`id` = 1", ['ban']));
$params = [
    ':url'=>'test1',
    ':time'=>'urlsssss',
];
print_r($test->query('SELECT * FROM "visits" WHERE url = :url AND time = :time;', $params));



//$statement = $db->prepare('INSERT INTO "visits" ("url", "time") VALUES (?, ?)');
//$url = 'test.com';
//$time = date('Y-m-d H:i:s');
//$statement->bindValue(':url', $url);
//$statement->execute();
//
//$visits = $db->querySingle('SELECT COUNT(id) FROM "visits"');


//print_r($result->fetchArray(SQLITE3_ASSOC));




//$db->close();
