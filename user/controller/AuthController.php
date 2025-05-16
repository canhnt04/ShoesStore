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
        // if (isset($_SESSION['auth_called']) && $_SESSION['auth_called'] === true) {
        //     return;
        // }
        try {
            $this->render("Auth.php");
            // $_SESSION['auth_called'] = true;
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
        $redirectUser = null;
        $redirectAdmin = null;

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
                session_start();
                $_SESSION["userId"] = $user["id"];
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role_id'];

                $userId = $_SESSION['userId'];
                $message = $user['role_id'] == 4
                    ? "Chào mừng người dùng $username id $userId đã đăng nhập!"
                    : "Chào mừng quản trị viên $username!";

                if ($user['role_id'] == 4) {
                    $redirectUser = "../../../ShoesStore/user/Route.php?page=Product&action=showList&pageNumber=1";
                } else {
                    $redirectAdmin =  "../../../ShoesStore/admin/view/";
                }
                echo json_encode([
                    'success' => true,
                    'message' => $message,
                    'redirectUser' => $redirectUser,
                    'redirectAdmin' => $redirectAdmin
                ]);
                return;
            } else {
                $error['login'] = "Tài khoản hoặc mật khẩu không đúng.";
            }
        }

        echo json_encode([
            'success' => false,
            'error' => reset($error)
        ]);
        exit;
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
                exit;
            } else {
                $error['register'] = "Đăng ký thất bại, vui lòng thử lại.";
            }
        }

        echo json_encode([
            'success' => false,
            'error' => reset($error)
        ]);
        exit;
    }
    public function logout()
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (isset($_SESSION['userId'])) {
            // Xóa tất cả session liên quan đến người dùng
            session_unset(); // Xóa tất cả các biến session
            session_destroy(); // Hủy session

            // Chuyển hướng người dùng về trang đăng nhập hoặc trang chủ
            // header("Location: ../../../../ShoesStore/user/Route.php");
            
            $this->render("ProductList.php");

            exit;
        } else {
            // Nếu chưa đăng nhập, có thể chuyển hướng đến trang đăng nhập
            // header("Location: ../../../user/router.php?page=Auth&action=auth");
            $this->render("Auth.php");
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $authController = new AuthController();
    $action = $_POST['action'] ?? '';
    if ($action === 'login') {
        $authController->login();
    } elseif ($action === 'register') {
        $authController->register();
    }
}
