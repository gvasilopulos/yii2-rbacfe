

    
         A simple command line rbac frontent for yii2 authManager.
         It uses the standard User model of yii2 you may have to tweek it if you
         want to use an alternate implementation 
         Please Read:
         http://www.yiiframework.com/doc-2.0/guide-security-authorization.html
         type yii rbacfe/help for help on usage
    
         You have to add the following to your console configuration file

         just before return statement 

         Yii::$classMap['gvasilopulos\console\controllers\RbacfeController'] = '@vendor/gvasilopulos/yii2-rbacfe/console/controllers/RbacfeController.php';

         and this as an element of the return statement 

         'controllerMap' => [
         'rbacfe' => 'gvasilopulos\console\controllers\RbacfeController'
         ],
       

         Usage: 
         yii rbacfe shows about
         
         yii rbacfe/help displays help

         yii rbacfe/listroles : lists the defined useroles in the application

         yii rbacfe/hasrole username :lists the current roles of a user

         yii rbacfe/assignrole username role :grants a given role to a given username

         yii rbacfe/hasmainrole username: displays the current roles directly assigned to the user 
 
         yii rbacfe/revokerole username role: revokes a given role from a given username

         yii rbacfe/createpermissions permission1,permission2,permissionN :creates permissions
         from comma separeted values. In this quick and dirty way you cannot set descriptions 
         for permisions

         yii rbacfe/createpermission permission "description" :creates a given permission with a given description.
         Description must be enclosed in ""

         yii rbacfe/createrole role :creates a role with a given rolename 

         yii rbacfe/removeall : Removes ALL Rbac related data. Use with caution

         yii rbacfe/removeallpermissions : Removes all Permissions

         yii rbacfe/removechild parent child : Removes the given child of the given parent

         yii rbacfe/listchild parent : Lists the child objects of a given parent

         yii rbacfe/removechildren parent : Removes child obects of the given parent

         yii rbacfe/addchild parent child : Adds a given child object to a given parent

         yii rbacfe/permissionsbyrole role : Lists the permissions under a given role

         yii rbacfe/permissionsbyuser username : Gets the assigned permissions to the username

         yii rbacfe/revokeall username :Revokes all roles for a given username

         yii rbacfe/remove name : removes the object (role,permission,rule) with the given name

         yii rbacfe/permissions : Lists all the permissions in the application

         yii rbacfe/removeallroles : removes all roles in the appliction

         yii rbacfe/rules : Lists all the rules in the application

         yii rbacfe/checkaccess username pemission


[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)