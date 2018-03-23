<?php namespace Wit;

use Dotenv\Dotenv;

/**
 * 条约
 * 
 * 修改了 WordPress 的目录结构。
 */
final class Pact {
    private $publicPath;
    private $pactPath;
    private $rootPath;
    private $env;

    /**
     * 初始化目录信息。
     * 
     * @param string $publicPath: 服务器开放根路径。
     */
    public function __construct($publicPath) {
        $this->publicPath = $publicPath;
        $this->rootPath = dirname($publicPath);
        $this->pactPath = $publicPath.DIRECTORY_SEPARATOR.'pacts';
        $this->env = new Dotenv($this->rootPath);
    }

    /**
     * 公约签订，使结构确定。
     * 
     */
    public function sign() {
        $this->env->load();
        $this->env->required([
            'DB_HOST',
            'DB_NAME',
            'DB_USER',
            'DB_PASSWORD',
        ]);

        define('WP_DEBUG', $_ENV['WP_DEBUG'] ?? false);

        $protocol = self::isHttps() ? 'https' : 'http';
        $domain = $_ENV['WP_DOMAIN'] ?? $_SERVER['HTTP_HOST'];
        // 定位符。
        define('WP_HOME', $protocol.'://'.$domain);
        define('WP_SITEURL', WP_HOME.'/pacts');
        define('WP_CONTENT_URL', WP_HOME);
        define('WP_CONTENT_DIR', $this->publicPath);

        // 数据库。
        define('DB_HOST', $_ENV['DB_HOST']);
        define('DB_NAME', $_ENV['DB_NAME']);
        define('DB_USER', $_ENV['DB_USER']);
        define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
        define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8');
        define('DB_COLLATE', '');
        $GLOBALS['table_prefix'] = $_ENV['DB_PREFIX'] ?? 'wp_';

        // 盐。
        define('AUTH_KEY', $_ENV['AUTH_KEY']);
        define('SECURE_AUTH_KEY', $_ENV['SECURE_AUTH_KEY']);
        define('LOGGED_IN_KEY', $_ENV['LOGGED_IN_KEY']);
        define('NONCE_KEY', $_ENV['NONCE_KEY']);
        define('AUTH_SALT', $_ENV['AUTH_SALT']);
        define('SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT']);
        define('LOGGED_IN_SALT', $_ENV['LOGGED_IN_SALT']);
        define('NONCE_SALT', $_ENV['NONCE_SALT']);

        defined('ABSPATH') or define('ABSPATH', $this->pactPath.DIRECTORY_SEPARATOR);
    }

    /**
     * 判断是否是 HTTPS 。
     * 
     */
    public static function isHttps() {
        return (isset($_SERVER['HTTPS']) and 'on' == $_SERVER['HTTPS'])
            or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO']);
    }
}