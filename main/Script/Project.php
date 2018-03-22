<?php namespace Wit\Script;

use Composer\Script\Event;
use Composer\Util\Filesystem;

/**
 * 项目脚本
 * 
 */
abstract class Project {
    /**
     * 项目安装。
     * 
     * @param Event $event: 事件。
     */
    public static function install(Event $event) {
        $io = $event->getIO();
        $composer = $event->getComposer();
        $project = $composer->getPackage();
        $filesystem = new Filesystem;
        $projectPath = $project->getTargetDir();
        $accessPath = $projectPath.'/access';
        $publicPath = $projectPath.'/public';
        $io->write("install project [$projectPath].");
        $io->write("copy [$accessPath] to [$publicPath].");
        $filesystem->copy($accessPath, $publicPath);
        $filesystem->removeDirectory($accessPath);
    }
}