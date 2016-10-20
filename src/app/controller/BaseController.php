<?php namespace App\Controller;

class BaseController {

    public static function renderWithLayout($renderer, $response, $template, $layout, $data=[]){
        $renderer->addAttribute('content_template', $template);
        $renderer->addAttribute('response', $response);
        return $renderer->render($response, $layout, $data);
    }
}
 