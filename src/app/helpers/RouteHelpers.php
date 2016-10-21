<?php

function renderWithLayout($renderer, $response, $template, $layout, $data=[]){
    $renderer->addAttribute('content_template', $template);
    $renderer->addAttribute('response', $response);
    return $renderer->render($response, $layout, $data);
}

