<?php
class User extends Controller
{
 private $db;
 public function __construct()
 {
  $this->db        = new Database;
 }

 public function index()
 {
  $user = $this->db->getById("users",1);
  $data = [
   'title' => 'Welcome',
   'user'  => $user,
  ];
  
  echo "This is user index";
  //$this->view('pages/index', $data);

 }

 
 public function create()
 {
  echo "This is user create";
 }

}