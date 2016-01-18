<?php


namespace plugin\Offline;

/**
 * @Name Offline-mode
 * @Description Requires HTTPS, Browsed content is available offline
 * @Author Erik Hamrin
 * @Version v1.0
 * @Icon  fa-file-o
 */


class Offline  extends \app\AbstractPlugin
{

    public function HookRootAccess($method){
        return (strtolower($method) == 'serviceworker') || (strtolower($method) == 'offline');
    }
}