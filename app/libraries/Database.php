<?php
/*
 * PDO Database Class
 * Connect to database
 * Create prepared statement
 * Bind values
 * Return rows and results
 */

class Database
{
 private $host      = DB_HOST;
 private $user      = DB_USER;
 private $pass      = DB_PASS;
 private $dbname    = DB_NAME;
 private $dbcharset = DB_CHARSET;

 private $pdo;
 private $stmt;
 private $error;
 public function __construct()
 {
  $dsn     = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=" . $this->dbcharset;
  $options = array(
   PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
   PDO::ATTR_EMULATE_PREPARES   => false,
   PDO::ATTR_PERSISTENT         => true,
  );

  // Create PDO instance
  try {
   $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
  } catch (PDOException $e) {
   $this->error = $e->getMessage();
   echo $this->error;
  }
 }

 /*
  * @param integer $id
  * @return Model
  */
 public function getById($table, $id)
 {
  $stm = $this->pdo->prepare('SELECT * FROM ' . $table . ' WHERE `id` = :id');
  $stm->bindValue(':id', $id);
  $success = $stm->execute();
  $row     = $stm->fetch(PDO::FETCH_ASSOC);
  return ($success) ? $row : [];
 }
 public function readAll($table)
 {
  $stm     = $this->pdo->prepare('SELECT * FROM ' . $table);
  $success = $stm->execute();
  $rows    = $stm->fetchAll(PDO::FETCH_ASSOC);
  return ($success) ? $rows : [];
 }
 public function readData($table, $id)
 {
  $stm = $this->pdo->prepare('SELECT * FROM ' . $table . ' WHERE `director_id` = (SELECT `id` FROM `director` WHERE `id` =:id)');
  $stm->bindValue(':id', $id);
  $success = $stm->execute();
  $row     = $stm->fetchAll(PDO::FETCH_ASSOC);
  return ($success) ? $row : [];
 }
 public function create($table, $data)
 {
  try {
   $columns    = array_keys($data);
   $columnSql  = implode(',', $columns);
   $bindingSql = ':' . implode(',:', $columns);
   $sql        = "INSERT INTO $table ($columnSql) VALUES ($bindingSql)";
   $stm        = $this->pdo->prepare($sql);
   foreach ($data as $key => $value) {
    $stm->bindValue(':' . $key, $value);
   }
   $status = $stm->execute();
   return ($status) ? $this->pdo->lastInsertId() : false;

  } catch (Exception $e) {
   echo ($e);
  }

 }
 public function update($table, $id, $data)
 {
  if (isset($data['id'])) {
   unset($data['id']);
  }

  $columns = array_keys($data);
  $columns = array_map(function ($item) {
   return $item . '=:' . $item;
  }, $columns);
  $bindingSql = implode(',', $columns);
  $sql        = "UPDATE $table SET $bindingSql WHERE `id` = :id";
  $stm        = $this->pdo->prepare($sql);
  $data['id'] = $id;
  foreach ($data as $key => $value) {
   $stm->bindValue(':' . $key, $value);
  }
  $status = $stm->execute();
  return $status;
 }
 /**
  * @param $table
  * @param $id
  * @return bool
  */
 public function delete($table, $id)
 {
  $stm = $this->pdo->prepare('DELETE FROM ' . $table . ' WHERE id = :id');
  $stm->bindParam(':id', $id);
  $success = $stm->execute();
  return ($success);
 }

 public function save($table, $data)
 {
  if (isset($data['id'])) {
   $this->update($table, $data['id'], $data);
  } else {
   return $this->create($table, $data);
  }
 }

}