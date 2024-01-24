<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;

class AboutController {

    public function __construct(private TemplateEngine $templateEngine) {
    }

    public function about(){
        echo $this->templateEngine->render('/about.php');
    }
}