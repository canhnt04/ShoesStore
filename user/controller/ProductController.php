<?php
/* Controller PagesController sẽ được gọi bởi router nếu đúng định tuyến uri */

require_once __DIR__ . "/../controller/BaseController.php";

class ProductController extends BaseController
{
    public function home()
    {
        // Hard code data. Nếu xài sql thì chơi model bỏ vô đây
        $data = array(
            'name' => 'Huy UwU',
            'age' => 69
        );
        // render
        $this->render('productList');
    }

    public function error()
    {
        $this->render('error');
    }
}
?>