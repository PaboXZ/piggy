<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;

class AboutController {

    private TemplateEngine $templateEngine;

    public function __construct() {
        $this->templateEngine = new TemplateEngine(Paths::VIEW);
    }

    public function about(){
        echo $this->templateEngine->render('/about.php');
    }
}