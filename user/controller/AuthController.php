<?php
require_once __DIR__ . "/BaseController.php";
require_once  __DIR__ . '/../model/User.php';
require_once __DIR__ . "../../../public/assets/helper/validator.php";

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

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $error = [];
        $success = '';

        if (!Validator::required($username)) {
            $error['username'] = "Vui lòng nhập tên đăng nhập.";
        } elseif (!Validator::username($username)) {
            $error['username'] = "Tên đăng nhập chỉ chứa chữ cái, số, gạch dưới, từ 3-20 ký tự.";
        } elseif (!Validator::required($password)) {
            $error['password'] = "Vui lòng nhập mật khẩu.";
        } elseif (!Validator::password($password)) {
            $error['password'] = "Mật khẩu phải từ 6 ký tự trở lên.";
        }

        if (empty($error)) {
            $user = $this->userModel->login($username, $password);
            if ($user) {
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role_id'];

                if ($user['role_id'] == 4) {
                    $success = "Chào mừng người dùng $username đã đăng nhập!";
                    $this->render("../resource/content/UserHome.php", ['sucess' => $success]);
                } else {
                    $success = "Chào mừng quản trị viên $username!";
                    $this->render("../resource/content/AdminDashboard.php", ['sucess' => $success]);
                }
            } else {
                $error['login'] = "Tài khoản hoặc mật khẩu không đúng.";
                $this->render("../resource/content/Auth.php", ['error' => $error]);
            }
        }
        $this->render("../resource/content/Auth.php", ['error' => $error]);
    }

    public function register()
    {

        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $repassword = $_POST['repassword'] ?? '';

        $error = [];
        $success = '';

        if (!Validator::required($username)) {
            $error['username'] = "Vui lòng nhập tên đăng nhập.";
        } elseif (!Validator::username($username)) {
            $error['username'] = "Tên đăng nhập bao gồm chữ cái và số từ 3-20 ký tự.";
        } elseif (empty($email)) {
            $error['email'] = "Vui lòng nhập email.";
        } elseif (!Validator::email(($email))) {
            $error['email'] = "Vui lòng nhập đúng định dạng email.";
        } elseif (!Validator::required($password)) {
            $error['password'] = "Vui lòng nhập mật khẩu.";
        } elseif (!Validator::password($password)) {
            $error['password'] = "Mật khẩu phải từ 6 ký tự trở lên.";
        } elseif ($password !== $repassword) {
            $error['repassword'] = "Mật khẩu không khớp.";
        } elseif ($this->userModel->checkUsernameExist($username)) {
            $error['username'] = "Tên đăng nhập đã tồn tại trong hệ thống.";
        } elseif ($this->userModel->checkEmailExist($_POST['email'])) {
            $error['email'] = "Email đã tồn tại trong hệ thống.";
        } elseif (empty($error)) {
            $user = $this->userModel->register($username, $email, $password);
            if ($user) {
                $success = "Đăng ký thành công, hãy đăng nhập để sử dụng tài khoản.";
            } else {
                $error['register'] = "Đăng ký thất bại, vui lòng thử lại.";
            }
        }
        $this->render("../resource/content/Auth.php", [
            'error' => $error,
            'success' => $success
        ]);
    }
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['action'])) {
        $authController = new AuthController();

        if ($_POST['action'] === 'login') {
            $authController->login();
        } else if ($_POST['action'] === "register") {
            $authController->register();
        }
    }
}
