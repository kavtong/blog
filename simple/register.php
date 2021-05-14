<?php
/**
 * 1. 环境变量初始化
 * 1.1. 注册目录路径变量的初始化
 * 1.2. 目录路径的初始化
 */
// 1.1. 注册目录路径变量的初始化
$dir_name = getcwd() . DIRECTORY_SEPARATOR . 'register';
// 1.2. 目录路径的初始化
init_register_dir($dir_name);

/**
 * 2. 接收用户的参数
 * 2.1. Get方式 $_GET
 * 2.2. Post方式 $_POST
 * 2.3. Get+Post $_REQUEST
 */
$data = $_REQUEST;
/**
 * 3. 检测用户的输入的合法性
 * 3.1.输入的内容是否为空(未输入值 或 手机号为空 或 密码为空)
 * 3.2.输入的手机号是否已经注册
 */
// 3.1.输入的内容是否为空
if (empty($data) || empty($data['telephone']) || empty($data['password'])) {
    die('用户的手机号或密码不能为空');
}
// 3.2.输入的手机号是否已经注册
$telephone = (string)$data['telephone'];
// 注册文件的路径
$register_path = get_register_path($telephone, $dir_name);
if (check_telephone_is_valid($register_path)) {
    die('手机号注册过了');
}
/**
 * 4. 注册用户 并 返回 注册结果
 */
if (register_user($data['password'], $register_path)) {
    echo '注册成功';
} else {
    echo '注册失败';
}


/**
 * 初始化目录
 * @param string $dir_name 目录的名称
 */
function init_register_dir(string $dir_name): void
{
    // 判断目录是否存在
    if (!is_dir($dir_name) && !@mkdir($dir_name, 0755) && !is_dir($dir_name)) {
        die('注册目录初始化失败');
    }
}

/**
 * 获取用户注册文件的路径
 * @param string $telephone 手机号码
 * @param string $dir_name 注册文件的路径
 * @return string
 */
function get_register_path(string $telephone, string $dir_name): string
{
    // 用户的注册文件路径
    return $dir_name . DIRECTORY_SEPARATOR . 'user_' . $telephone . '.txt';
}

/**
 * 验证用户是否存在(注册)
 * @param string $register_path 注册文件路径
 * @return bool
 *              true 已经注册过了
 *              false 未注册
 */
function check_telephone_is_valid(string $register_path): bool
{
    // 判断文件是否存在
    return file_exists($register_path);
}

/**
 * 注册用户
 * @param string $password 密码
 * @param string $register_path 注册文件路径
 * @return bool
 */
function register_user(string $password, string $register_path): bool
{
    /**
     * 写文件
     * $password 更加安全的写法是 md5() 函数加密,不进行明文保存
     */
    return (bool)file_put_contents($register_path, $password);
}