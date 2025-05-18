<?php
class BaseController
{
    public function render($pageName, $data = [])
    {
        extract($data);
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            include __DIR__ . "/../resource/content/{$pageName}";
        } else {
            include __DIR__ . "/../view/{$pageName}";
        }
    }
}
