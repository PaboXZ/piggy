<?php

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;

class HomeController{

    private TemplateEngine $view;

    public function __construct(){
        $this->view = new TemplateEngine(Paths::VIEW);
    }

    public function home(){
        echo $this->view->render("/index.php");
    }
}