<?php namespace Wit;

use Dotenv\Dotenv;

/**
 * 条约
 * 
 * 修改了 WordPress 的目录结构。
 */
final class Pact {
    private $all;
    private $env;
    private $core;

    /**
     * 初始化目录信息。
     * 
     * @param string $projectPath: 服务器项目路径。
     */
    public function __construct($projectPath) {
        $this->all = [];
        $this->env = new Dotenv($projectPath);
        $this->core = new Core($projectPath);

        // 初始化开始。
        $this->env->load();
        $this->env->required([
            'DB_HOST',
            'DB_NAME',
            'DB_USER',
            'DB_PASSWORD',
        ]);
        $this->core->initialize();
        $this->all['at'] = [$this->core, 'at'];
    }

    /**
     * 调用器。
     * 
     * @param string $name: 调用的方法名。
     * @param string $arguments: 参数数组。
     * @return mixed: 返回的结构。
     */
    public function __call($name, $arguments) {
        $invoker = $this->all[$name];
        return call_user_func_array($invoker, $arguments);
    }

    /**
     * 签订。
     * 
     */
    public function sign($name, $invoker) {
        $this->all[$name] = $invoker;
    }
}
