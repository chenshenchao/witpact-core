<?php namespace Wit;

use Dotenv\Dotenv;

/**
 * 条约
 * 
 * 修改了 WordPress 的目录结构。
 */
final class Pact {
    private $rootPath;
    private $publicPath;
    private $env;

    /**
     * 初始化目录信息。
     * 
     * @param string $publicPath: 服务器开放根路径。
     */
    public function __construct($publicPath) {
        $this->rootPath = dirname($publicPath);
        $this->publicPath = $publicPath;
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
            'WP_DOMAIN',
            'WP_DEBUG',
        ]);

        define('WP_DEBUG', getenv('WP_DEBUG'));

        $protocol = self::isHttps() ? 'https' : 'http';

        // 定位符。
        define('WP_HOME', $protocol.'://'.getenv('WP_DOMAIN'));
        define('WP_SITEURL', WP_HOME.'/pacts');
        define('WP_CONTENT_DIR', $this->publicPath);
        define('WP_CONTENT_URL', WP_HOME);

        // 数据库。
        define('DB_HOST', getenv('DB_HOST'));
        define('DB_NAME', getenv('DB_NAME'));
        define('DB_USER', getenv('DB_USER'));
        define('DB_PASSWORD', getenv('DB_PASSWORD'));
        define('DB_CHARSET', getenv('DB_CHARSET') ?? 'utf8');
        define('DB_COLLATE', '');
        $GLOBALS['table_prefix'] = $_ENV['DB_PREFIX'] ?? 'wp_';

        // 盐。
        define('AUTH_KEY', getenv('AUTH_KEY'));
        define('SECURE_AUTH_KEY', getenv('SECURE_AUTH_KEY'));
        define('LOGGED_IN_KEY', getenv('LOGGED_IN_KEY'));
        define('NONCE_KEY', getenv('NONCE_KEY'));
        define('AUTH_SALT', getenv('AUTH_SALT'));
        define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT'));
        define('LOGGED_IN_SALT', getenv('LOGGED_IN_SALT'));
        define('NONCE_SALT', getenv('NONCE_SALT'));

        defined('ABSPATH') or define('ABSPATH', $this->publicPath.'/pacts/');
    }

    /**
     * 判断是否是 HTTPS 。
     */
    public static function isHttps() {
        return (isset($_SERVER['HTTPS']) and 'on' == $_SERVER['HTTPS'])
            or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO']);
    }
}