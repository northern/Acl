<?php

namespace Northern\Acl;

use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

use Northern\Common\Helper\ArrayHelper as Arr;

class Acl extends \Zend\Permissions\Acl\Acl {

	public function loadPermissions( array $permissions )
	{
	   foreach( $permissions['roles'] as $role )
	   {
	      $roleName   = Arr::get( $role, 'name' );
	      $roleParent = Arr::get( $role, 'parent' );
	      
	      $this->addRole( new Role( $roleName ), $roleParent );
	   }

	   foreach( $permissions['resources'] as $resource )
	   {
	      $resourceName   = Arr::get( $resource, 'name' );
	      $resourceParent = Arr::get( $resource, 'parent' );
	      
	      $this->addResource( new Resource( $resourceName ), $resourceParent );
	   }

	   foreach( $permissions['rules'] as $rule )
	   {
	      $ruleAccess      = Arr::get( $rule, 'access' );
	      $ruleRole        = Arr::get( $rule, 'role' );
	      $ruleResources   = Arr::get( $rule, 'resources' );
	      $rulePermissions = Arr::get( $rule, 'permissions' );

	      if( method_exists( $this, $ruleAccess ) )
	      {
	         $this->{$ruleAccess}( $ruleRole, $ruleResources, $rulePermissions );
	      }
	   }
	}

}
