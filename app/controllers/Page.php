<?php
class Page extends Controller
{
 private $db;
 public function __construct()
 {
  $this->userModel = $this->model('User');
  $this->db = new Database;
 }

 public function index()
 {

  $data = [
   'title' => 'Home'
  ];
  $this->view('pages/index', $data);

 }

 public function create($id)
 {
  echo $id;
 }

 public function about()
 {
  $data = [
   'title' => 'About',
  ];

  $this->view('pages/about', $data);

 }
 
}
