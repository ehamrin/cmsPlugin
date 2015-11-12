# cmsPlugin

##Create a plugin
1. Choose a plugin name (referenced here as "{plugin}")
2. Create directory {plugin} in /src/plugin/
3. Name class and file {plugin} in /src/plugin/{plugin}/
4. Make sure the class implements the \IPlugin interface
5. If you want it to be able to hook to the admin panel, also implement \plugin\Admin\IAdminPanel

###Hook to public cms url
1. create public function HookRootAccess($method){}
2. The method should return true if the requested url is implemented in the plugin
```PHP
/**
* @return bool
*/
public function HookRootAccess($method){
  return strtolower($method) == 'yoururl';
}
```

###Create admin panel menu
```PHP
/**
* @return \NavigationItem[]
*/
public function HookAdminItems() 
{
  return array(
    new \NavigationItem(...)
  );
}
```

###Add user permission
```PHP
/**
* @return \plugin\Authentication\model\Permission[]
*/
public function HookUserPermissions() 
{
  return array(
    new \plugin\Authentication\model\Permission(...)
  );
}
```

