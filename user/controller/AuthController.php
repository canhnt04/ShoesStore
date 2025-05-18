<?php
require_once __DIR__ . "/BaseController.php";
require_once  __DIR__ . '/../model/User.php';
require_once __DIR__ . "/../../public/assets/helper/validator.php";
require_once __DIR__ .  "/../../config/init.php";

class AuthController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function auth()
    {
        try {
            $this->render("Auth.php");
        } catch (Exception $e) {
            echo ($e);
        }
    }

    public function login()
    {
        header('Content-Type: application/json');
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $error = [];

        if (!Validator::required($username)) {
            $error['username'] = "Vui lòng nhập tên đăng nhập.";
        } elseif (!Validator::username($username)) {
            $error['username'] = "Tên đăng nhập bao gồm chữ cái và số từ 3-20 ký tự.";
        } elseif (!Validator::required($password)) {
            $error['password'] = "Vui lòng nhập mật khẩu.";
        } elseif (!Validator::password($password)) {
            $error['password'] = "Mật khẩu phải từ 6 ký tự trở lên.";
        }

        if (empty($error)) {
            $user = $this->userModel->login($username, $password);
            if ($user) {
                $_SESSION['userId'] = $user['id'];
                $_SESSION['role'] = $user['role_id'];

                $redirectRoutes = [
                    1 => "/ShoesStore/admin/view/",
                    2 => "/ShoesStore/admin/view/",
                    3 => "/ShoesStore/admin/view/",
                    4 => '/ShoesStore/public/index.php?page=Product&action=showList&pageNumber=1'
                ];

                $messages = [
                    1 => "Chào mừng quản trị viên $username!",
                    2 => "Chào mừng nhân viên nhập hàng $username!",
                    3 => "Chào mừng nhân viên bán hàng $username!",
                    4 => "Chào mừng người dùng $username đã đăng nhập!"
                ];

                $role = $user['role_id'];
                echo json_encode([
                    'success' => true,
                    'message' => $messages[$role] ?? 'Xin chào',
                    'redirect' => $redirectRoutes[$role] ?? '/ShoesStore/public/index.php?page=Product&action=showList&pageNumber=1'
                ]);
                exit();
            } else {
                $error['login'] = "Tài khoản hoặc mật khẩu không đúng.";
            }
        }

        echo json_encode([
            'success' => false,
            'error' => reset($error)
        ]);
        exit();
    }

    public function register()
    {
        header('Content-Type: application/json');
        $username = $_POST['new-username'] ?? '';
        $email = $_POST['new-email'] ?? '';
        $password = $_POST['new-password'] ?? '';
        $repassword = $_POST['new-repassword'] ?? '';
        $error = [];

        if (!Validator::required($username)) {
            $error['username'] = "Vui lòng nhập tên đăng nhập.";
        } elseif (!Validator::username($username)) {
            $error['username'] = "Tên đăng nhập bao gồm chữ cái và số từ 3-20 ký tự.";
        } elseif ($this->userModel->checkUsernameExist($username)) {
            $error['username'] = "Tên đăng nhập đã tồn tại trong hệ thống.";
        } elseif (empty($email)) {
            $error['email'] = "Vui lòng nhập email.";
        } elseif (!Validator::email($email)) {
            $error['email'] = "Vui lòng nhập đúng định dạng email.";
        } elseif ($this->userModel->checkEmailExist($email)) {
            $error['email'] = "Email đã tồn tại trong hệ thống.";
        } elseif (!Validator::required($password)) {
            $error['password'] = "Vui lòng nhập mật khẩu.";
        } elseif (!Validator::password($password)) {
            $error['password'] = "Mật khẩu phải từ 6 ký tự trở lên.";
        } elseif ($password !== $repassword) {
            $error['repassword'] = "Mật khẩu không khớp.";
        }

        if (empty($error)) {
            $new_user = $this->userModel->register($username, $email, $password);
            if ($new_user) {
                echo json_encode([
                    'success' => true,
                    'message' => "Đăng ký thành công, hãy đăng nhập để sử dụng tài khoản."
                ]);
                return;
            } else {
                $error['register'] = "Đăng ký thất bại, vui lòng thử lại.";
            }
        }

        echo json_encode([
            'success' => false,
            'error' => reset($error)
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $authController = new AuthController();
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'login':
            $authController->login();
            break;
        case 'register':
            $authController->register();
            break;
        case 'auth':
            $authController->auth();
            break;
        default:
            break;
    }
}
