<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//namespace app\commands;

namespace gvasilopulos\console\controllers;
use Yii;
use yii\console\Controller;
use common\models\User;
class RbacfeController extends Controller
{
     
     //   public $username;
    //   public function options($actionID)
   // {
   //     return ['username'];
   // }
    
   // public function optionAliases()
   // {
   //     return ['u' => 'username'];
   // }
    /**
     * 
     * 
     * lists the help
     */
    public function actionIndex()
    {
        echo "A simple command line rbac frontent for yii2 authManager. Please Read\n";
        echo "http://www.yiiframework.com/doc-2.0/guide-security-authorization.html\n";
        echo "type yii rbacfe/help for help on usage\n";
    }
    public function actionHelp()
    {
        echo "Commands: \n";
        echo "yii rbacfe shows about\n";
        echo "yii rbacfe/help displays this help\n";
        echo "yii rbacfe/listroles : lists the defined useroles in the application\n";
        echo "yii rbacfe/hasrole username :lists the current roles of a user\n";
        echo "yii rbacfe/assignrole username role :grants a given role to a given username\n";
        echo "yii rbacfe/hasmainrole username: displays the current roles directly assigned to the user \n"; 
        echo "yii rbacfe/revokerole username role: revokes a given role from a given username\n";
        echo "yii rbacfe/createpermissions permission1,permission2,permissionN :creates permissions\n";
        echo "from comma separeted values.\n";
        echo "In this quick and dirty way you cannot set descriptions for permisions\n";
        echo 'yii rbacfe/createpermission permission "description" :creates a given permission'."\n". 'with a given description.Description must be enclosed in ""'."\n";
        echo "yii rbacfe/createrole role :creates a role with a given rolename \n";
        echo "yii rbacfe/removeall : Removes ALL Rbac related data. Use with caution\n";
        echo "yii rbacfe/removeallpermissions : Removes all Permissions\n";
        echo "yii rbacfe/removechild parent child : Removes the given child of the given parent\n";
        echo "yii rbacfe/listchild parent : Lists the child objects of a given parent\n";
        echo "yii rbacfe/removechildren parent : Removes child obects of the given parent\n";
        echo "yii rbacfe/addchild parent child : Adds a given child object to a given parent\n";
        echo "yii rbacfe/permissionsbyrole role : Lists the permissions under a given role\n";
        echo "yii rbacfe/permissionsbyuser username : Gets the assigned permissions to the username\n";
        echo "yii rbacfe/revokeall username :Revokes all roles for a given username\n";
        echo "yii rbacfe/remove name : removes the object (role,permission,rule) with the given name\n";
        echo "yii rbacfe/permissions : Lists all the permissions in the application\n";
        echo "yii rbacfe/removeallroles : removes all roles in the appliction\n";
        echo "yii rbacfe/rules : Lists all the rules in the application\n";
        echo "yii rbacfe/checkaccess username pemission\n";
        return 0;
    }
    /**
     * lists the current roles in the application
     */
    public function actionListroles()
    {
        $auth=Yii::$app->authManager;
        $currentroles=$auth->getRoles();
        echo "current system roles are :";
        foreach ($currentroles as $currentrole)
        {
            echo " ".$currentrole->name ." ";
        }
        echo "\n";
    }
    /**
     * returns the roles assigned to a username;
     * @param type $username
     */
    public function actionHasrole($username)
    {        
      if (!$currentuser=User::findByUsername($username))
      {
       echo "Displays the given usernames role\n usage: rbacfe username";                 
      }
      else 
      {    
       $auth = \Yii::$app->authManager;
       $roles=$auth->getAssignments($currentuser->id);
         foreach ($roles as $userrole)
         {
          echo $currentuser->username." is ".$userrole->roleName."\n";
         }             
       }
     }
    /**
     * 
     * @param type $username
     * lists the given username directly assigned roles
     */ 
    public function actionHasmainrole($username)
    {
       if (!$currentuser=User::findByUsername($username))
      {
       echo "Displays the given usernames role\n usage: rbacfe username\n"
           . "please provide a valid username\n";                 
       return 1;       
      }
      else 
      {    
       $auth = \Yii::$app->authManager;
       $roles=$auth->getRolesByUser($currentuser->id);
         foreach ($roles as $userrole)
         {
          echo $currentuser->username." is ".$userrole->name."\n";
         }             
      }
      return 0;
     } 
     /**
      * 
      * @param type $username
      * @param type $role
      * adds a role to a given username
      */
    
    public function actionAssignrole($username, $role)
     {
         $auth=Yii::$app->authManager;
             if (!$currentuser=User::findByUsername($username))
             {
                 echo "you have to provide a valid username as first argument\n"; 
                 return 1;
             }
             if (!$auth->getRole($role))
             {
                 echo "no such role exists in the application\n";
                 return 1;
             }
             
            else 
            {               
             $newRole = $auth->getRole($role);
             $auth->assign($newRole, $currentuser->id);  
            
             return 0;
            }
     }
     /**
      * revokes a givel role from a giver username
      * @param type $username
      * @param type $role
      */
    public function actionRevokerole($username,$role)
    {
            $auth=\Yii::$app->authManager;
            
            if (!$currentuser=User::findByUsername($username))
            {
                 echo "you have to pass a valid username argument\n";                              
                 return 1;
                 
            }  
            if (!$auth->getRole($role)) 
            {
                 echo "there is no such role in the application\n";
                return 1;
            }
              $userroles=$auth->getRolesByUser($currentuser->id);
              $userrole=$auth->getRole($role);
              if (!in_array($userrole, $userroles))
              {
                  echo $currentuser->username." has no such role assigned\n";
                  return 1;
              }                                        
             $roletorevoke=$auth->getRole($role);    
             $auth->revoke($roletorevoke, $currentuser->id) ; 
             return 0;
   }
   /**
    * creates a list of permissions in rbac
    * @param array $permissions
    * @return int
    */
   public function actionCreatepermissions(array $permissions)
   {
          $auth=Yii::$app->authManager;
          foreach ($permissions as $name)
          {     
           $newPermission=$auth->createPermission($name);
           $auth->add($newPermission);
           echo "created permission with name ".$name."\n";
          }
          return 0;
   }
   /**
    * creates a role in rbac
    * @param type $role
    * @return int
    */
   public function actionCreaterole($role)
   {
        $auth=Yii::$app->authManager;
       if($auth->getRole($role))
       {
           echo "role ".$role." allready exists!\n";
           return 1;
       }       
        $newRole=$auth->createRole($role);
        $auth->add($newRole);
        echo "created role with name ".$role."\n";
        return 0;
   }
   /**
    * creates a permission by giving a permission name and a permission description
    * @param type $permission
    * @param type $description
    * @return int
    */
   public function actionCreatepermission($permission, $description)
   {
       $auth=Yii::$app->authManager;
       $newPermission=$auth->createPermission($permission);
       $newPermission->description=$description;
       $auth->add($newPermission);
       return 0;
   }
/**
 * removes all rbac related info
 * @return int
 */
   public function actionRemoveall()
           
   {   
       $message="Are you sure you want to erase all Rbac related data ? (y/n)\n";
//       echo $message;
//       $response=trim(fgets(STDIN));
//      if (($response=="yes") || ($response=="y"))
      if ($this->confirm($message))
       {
          $auth=Yii::$app->authManager;
          $auth->removeAll();
          return 0; 
      }
      else
      {
          echo "ok.. not removing anything\n";
          return 1;
      }           
   }
   /**
    * removes all permissions 
    * @return int
    */
   public function actionRemoveallpermissions()
   {
      echo "Are you sure you want to remove all Permissions in Rbac ? (y/n)\n";
        $response=trim(fgets(STDIN));
      if((response=="yes")||($response=="y"))
      {
       $auth=Yii::$app->authManager;
       $auth->removeAllPermissions();
       return 0;       
      }
      else 
      {
          echo "Aborted\n";
          return 1;
      }               
   }
   /**
    * adds a child to a parent
    */
   public function actionAddchild($parent, $child)
   {  
       $parenttype=99;
       $childtype=99;
       $auth=Yii::$app->authManager;
       
       if ($auth->getRole($parent))
       { 
          $parenttype=1;
       }   
       if ($auth->getPermission($parent))
       {
           $parenttype=2;
       }
       if ($auth->getRule($parent))
       {
           $parenttype=98;
       }
       switch ($parenttype)
       {
           case 1:
              $parent1=$auth->getRole($parent);
               break;
           case 2:
               $parent1=$auth->getPermission($parent);
               break;
           case 98:
               $parent1=$auth->getRule($parent);
               echo "you cannot use a rule as a parent\n";
               return 1;
           case 99:
               echo "no such parent found\n";
               return 1;
       }
       if ($auth->getRole($child))
       {
           $childtype=1;
       }
       if ($auth->getPermission($child))
       {
           $childtype=2;
       }
       if ($auth->getRule($child))
       {
           $childtype=98;
       }
       switch ($childtype)
       {
           case 1:
               $child1=$auth->getRole($child);
               break;
           case 2:
               $child1=$auth->getPermission($child);
               break;
           case 98:
               $child1=$auth->getRule($child);
               break;
           case 99:
               echo "no valid child given\n";
               return 1;
       }
       if (!$auth->canAddChild($parent1, $child1))
       { 
         // $parent1=$auth->getRole($parent);
         // $child1=$auth->getRole($child);
           echo "You cannot add this child to this parrent\n";
           return 1;
        }
      
       else
         {
         // $parent1=$auth->getRole($parent);
         // $child1=$auth->getRole($child);
          $auth->addChild($parent1, $child1);
          return 0;
         }
   }
   
   /**
    * removes a role from the application
    */
    public function actionRemovechild($parent, $child)
    { 
        $auth=Yii::$app->authManager;
        $parenttype=99;
        $childtype=99;
        if ($auth->getRole($parent))        
        {
            $parenttype=1;
        }
        if ($auth->getPermission($parent))
        {
            $parenttype=2;
        }
        if ($auth->getRule($parent))
        {
            $parenttype=98;
        }
        switch ($parenttype)
        {
            case 1:
                $parent1=$auth->getRole($parent);
                break;
            case 2:
                $parent1=$auth->getPermission($parent);
                break;
            case 98:
                $parent1=$auth->getRule($parent);
                break;
            case 99:
                echo "no such parent exists\n";
                return 1;
             
        }
            
        if (!$auth->getChildren($parent))
        {
            echo "Nothing to remove!\n";
            return 1;            
        }
        //a child can be a permission a role or a rule 
        if ($auth->getPermission($child))
        {
            $childtype=2;
        }
        if ($auth->getRole($child))
        {
            $childtype=1;
        }
        if ($auth->getRule($child))
        {
            $childtype=98;
        }
        
        switch ($childtype)
        {
                case 98:
                    $childitem=$auth->getRule($child);
                    break;
                case 1:
                    $childitem=$auth->getRole($child);
                    break;
                case 2:
                    $childitem=$auth->getPermission($child);
                    break;
                case 99:
                    echo "no such children found\n";
                    return 1;
        } 

            $auth->removeChild($parent1, $childitem);
            echo "child " .$child. " of ".$parent. " removed\n";
            return 0;
        
    }
    /**
     * removes all children fron a givern parent
     * @param $parent int
     * 
     */
    public function actionRemovechildren($parent)
    {          
        $auth=Yii::$app->authManager;
        if (!$auth->getChildren($parent))
        {
            echo "there are no childs for the given parent\n";
            return 1;
        }
        else 
        {  
            $message="Are you sure you want to remove all children for" .$parent. " ?\n";
//          $response=trim(fgets(STDIN));
//          if((response=="yes")||($response=="y"))
            if($this->confirm($message))
           {
            $auth->removeChildren($parent);
            echo "removed ALL children for" .$parent."\n";
           }
           else 
           {
               echo "Aborted! \n";
           }
            
        }
    }       
    /**
     * list the child roles of a given parrent
     */
    public function actionListchild($parent)
    {
        $auth=Yii::$app->authManager;
        if (!$auth->getChildren($parent))
        {
            echo "there are no childs for the given parent\n";
            return 1;
        }
        else 
        {        
            $childs=$auth->getChildren($parent);
                    foreach($childs as $children)
                    {    echo $children->name. " ";}
                    echo "\n";         
        }
    }
    /**
     * lists the roles of a given role
     */
    public function actionPermissionsbyrole($rolename)
    {
        $auth=Yii::$app->authManager;
        if($permissions=$auth->getPermissionsByRole($rolename))
        {
            foreach ($permissions as $permission)
            {
               echo $permission->name." "; 
            }
            echo "\n";
            return 0;
        }
        else 
        { 
            echo "no permissions found\n";
        }
    }
    /**
     * list permissions of a given username
     * @param type $user
     * @return int
     */
    public function actionPermissionsbyuser($username)
    {
        $auth=Yii::$app->authManager;
        if (!$currentuser=User::findByUsername($username))
        {
         echo "Displays the given usernames permissions\n"
           . "please provide a valid username\n";                 
         return 1;       
        }
        if($permissions=$auth->getPermissionsByUser($currentuser->id))
        {
            foreach ($permissions as $permission)
            {
               echo $permission->name." "; 
            }
            echo "\n";
            return 0;
        }
        else 
        { 
            echo "no permissions found\n";
            return 1;
        }
    }
    /**
     * revokes all permissions of a given username
     * @param type $user
     */
    public function actionRevokeall($username)
    {
        $auth=Yii::$app->authManager;

      if (!$currentuser=User::findByUsername($username))
      {
       echo "revokes the given usernames roles\n"
           . "please provide a valid username\n";                 
       return 1;       
      }
      else
      {  
          $auth->revokeAll($currentuser->id);
          echo "revoked all ".$currentuser->username." roles!\n";
          return 0;
      }
    }
    /**
     * returns user id and username per line for a given role
     * @param type $role
     */
    public function actionUsersbyrole($role)
    {
        $auth=Yii::$app->authManager;
        if (!$auth->getRole($role))
        {
            echo "no such role exsists in the appliction\n";
            return 1;
        }
        else
        {
        $userIds=$auth->getUserIdsByRole($role);
          foreach ($userIds as $userId)
          {  
              $user=User::findIdentity($userId);
              echo $user->id." ".$user->username."\n";
          }
        return 0;
        }
    }
    /**
     * reoves an object from the rbac
     * @param type $name
     */
    public function actionRemove($name)
    {
        $auth=Yii::$app->authManager;
        if (($object=$auth->getRole($name))||($object=$auth->getPermission($name))||($object=$auth->getRule($name)))
        {
            if ($this->confirm("Are you sure you want to remove ".$name."?\n"))
            {
            $auth->remove($object);
            echo "removed ".$name."\n";
            return 0;
            }
        }
        else 
        {
            echo "non existent object ".$name." given\n";
        }
    }
    /**
     * lists all the permissions of the application
     */
    public function actionPermissions()
    {
        $auth=Yii::$app->authManager;
        $permissions=$auth->getPermissions();
        foreach ($permissions as $permission)
        {
            echo $permission->name. "\n";
        }
    }
    /**
     * removes all roles in the application
     */
    public function actionRemoveallroles()
    {
       $auth=Yii::$app->authManager;
       if($this->confirm("Are you sure you want to remove all roles in the application?\n"))
       {
       $auth->removeAllRoles();
       }
    }
    /**
     * removes all assignments in the application
     */
    public function actionRemoveallassignments()
    {
        $auth=Yii::$app->authManager;
       if($this->confirm("Are you sure you want to remove all assignments in the application?\n"))
       {
       $auth->removeAllAssignments();
       }
    }
    public function actionRemoveallrules()
    {
       $auth=Yii::$app->authManager;
       if($this->confirm("Are you sure you want to remove all rules in the application?\n"))
       {
       $auth->removeAllRules();
       }
    }
    public function actionRules()
    {
        $auth=Yii::$app->authManager;
        $rules=$auth->getRules();
        foreach($rules as $rule)
        {
            echo $rule->name."\n";
        }
    }
    public function actionCheckaccess($username,$permission) 
     {
        $auth=Yii::$app->authManager;
        if(!$user=User::findByUsername($username))
        {
            echo "Cannot find user \n";
            return 1;
        }
        if(!$auth->getPermission($permission))
        {    
            echo "No such permission\n";
            return 1;
        }
        if($auth->checkAccess($user->id, $permission))
        {
            echo $user->username." has ".$permission."\n";
                return 0;            
        }
        else 
        {
         echo $user->username. " does not have " .$permission."\n";   
        }
    }
}