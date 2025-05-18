<?php
class Validator
{

    // Kiểm tra không rỗng
    public static function required($value): bool
    {
        return !empty(trim($value));
    }

    // Kiểm tra tên người dùng: bao gồm chữ cái và số, từ 3-20 ký tự
    public static function username($value): bool
    {
        return preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z0-9]{3,20}$/', $value);
    }

    // Mật khẩu ít nhất 6 ký tự
    public static function password($value): bool
    {
        return preg_match('/^.{6,}$/', $value);
    }

    // Email hợp lệ
    public static function email($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
