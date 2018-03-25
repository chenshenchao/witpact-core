<?php namespace Wit;

/**
 * 核心类
 * 
 * 初始化 WordPress 和项目路径处理。
 */
final class Core {
    private const DS = DIRECTORY_SEPARATOR;

    private $path;

    /**
     * 构造子，初始化。
     * 
     */
    public function __construct($projectPath) {
        $this->path = [
            'project' => $projectPath,
            'public' => $projectPath.self::DS.'public',
            'pact' => $projectPath.self::DS.'public'.self::DS.'pacts',
        ];
    }

    /**
     * WordPress 初始化。
     * 
     */
    public function initialize() {
        // 配置 WordPress
        define('WP_DEBUG', $_ENV['WP_DEBUG'] ?? false);
        self::locate($this->at('public'));
        self::setDatabase();
        self::addSalt();
        defined('ABSPATH') or define('ABSPATH', $this->at('pact'));
    }

    /**
     * 定位路径。
     * 
     * @param string $locator: 保留的定位符。
     * @param string ...$tail: 后接路径。
     * @return string: 生成的路径。
     */
    public function at($locator, ...$tail) {
        $head = $this->path[$locator];
        return $head.self::DS.join(self::DS, $tail);
    }

    /**
     * 设置 WordPress 定位。
     * 
     * @param string $publicPath: 网站根目录。
     */
    private static function locate($publicPath) {
        $protocol = self::isHttps() ? 'https' : 'http';
        $domain = $_ENV['WP_DOMAIN'] ?? $_SERVER['HTTP_HOST'];
        // 定位符。
        define('WP_HOME', $protocol.'://'.$domain);
        define('WP_SITEURL', WP_HOME.'/pacts');
        define('WP_CONTENT_URL', WP_HOME);
        define('WP_CONTENT_DIR', $publicPath);
    }

    /**
     * 配置数据库。
     * 
     */
    private static function setDatabase() {
        define('DB_HOST', $_ENV['DB_HOST']);
        define('DB_NAME', $_ENV['DB_NAME']);
        define('DB_USER', $_ENV['DB_USER']);
        define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
        define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8');
        define('DB_COLLATE', $_ENV['DB_COLLATE'] ?? '');
        $GLOBALS['table_prefix'] = $_ENV['DB_PREFIX'] ?? 'wp_';
    }

    /**
     * 加盐。
     * 
     */
    private static function addSalt() {
        // 钥。
        isset($_ENV['AUTH_KEY']) and define('AUTH_KEY', $_ENV['AUTH_KEY']);
        isset($_ENV['SECURE_AUTH_KEY']) and define('SECURE_AUTH_KEY', $_ENV['SECURE_AUTH_KEY']);
        isset($_ENV['LOGGED_IN_KEY']) and define('LOGGED_IN_KEY', $_ENV['LOGGED_IN_KEY']);
        isset($_ENV['NONCE_KEY']) and define('NONCE_KEY', $_ENV['NONCE_KEY']);
        // 盐。
        isset($_ENV['AUTH_SALT']) and define('AUTH_SALT', $_ENV['AUTH_SALT']);
        isset($_ENV['SECURE_AUTH_SALT']) and define('SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT']);
        isset($_ENV['LOGGED_IN_SALT']) and define('LOGGED_IN_SALT', $_ENV['LOGGED_IN_SALT']);
        isset($_ENV['NONCE_SALT']) and define('NONCE_SALT', $_ENV['NONCE_SALT']);
    }

    /**
     * 判断是否是 HTTPS 。
     * 
     */
    private static function isHttps() {
        return (isset($_SERVER['HTTPS']) and 'on' == $_SERVER['HTTPS'])
            or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO']);
    }
}