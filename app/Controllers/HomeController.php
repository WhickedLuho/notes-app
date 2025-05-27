<?php
namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        // var_dump("test");
        // Example database query
        // $stmt = $this->db->query("SELECT * FROM notes LIMIT 5");
        // $notes = $stmt->fetchAll();
        $notes = [];
        // Smarty template rendering
        $this->smarty->assign('notes', $notes);
        $this->smarty->display('home.tpl');
    }
}