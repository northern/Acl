<?php

namespace Northern\Acl;

class TestPermissions extends \Northern\Acl\Permissions {
	
	const ROLE_GUEST          = 'guest';
	const ROLE_MEMBER         = 'member';
	const ROLE_ADMIN          = 'admin';

	const RESOURCE_ALL        = NULL;
	const RESOURCE_USER       = 'user';
	const RESOURCE_POST       = 'post';
	const RESOURCE_COMMENT    = 'comment';

	const RULE_ALL            = NULL;
	const RULE_VIEW           = 'view';
	const RULE_CREATE         = 'create';
	const RULE_EDIT           = 'edit';
	const RULE_DELETE         = 'delete';

	static public function getPermissions()
	{
		$permissions = array(
			'roles' => [
				[ 'name' => static::ROLE_GUEST ],
				[ 'name' => static::ROLE_MEMBER, 'parent' => static::ROLE_GUEST ],
				[ 'name' => static::ROLE_ADMIN,  'parent' => static::ROLE_MEMBER ],
			],
			'resources' => [
			   [ 'name' => static::RESOURCE_USER ],
				[ 'name' => static::RESOURCE_POST ],
				[ 'name' => static::RESOURCE_COMMENT ],
			],
			'rules' => [
				[
					'access'      => static::ALLOW, 
					'role'        => static::ROLE_MEMBER,  
					'permissions' => static::RULE_VIEW,
					'resources'   => static::RESOURCE_ALL,
				], [
					'access'      => static::ALLOW, 
					'role'        => static::ROLE_MEMBER,  
					'permissions' => [static::RULE_CREATE, static::RULE_EDIT],
					'resources'   => [static::RESOURCE_POST, static::RESOURCE_COMMENT],
				], [
					'access'      => static::ALLOW,
					'role'        => static::ROLE_ADMIN,
					'permissions' => static::RULE_ALL,
					'resources'   => static::RESOURCE_ALL,
				],
			],
		);

		return $permissions;
	}

	public function getRoles()
	{
		return [
			static::ROLE_MEMBER,
			static::ROLE_ADMIN,
		];
	}

	public function getResources()
	{
		return [
			RESOURCE_ALL,
			RESOURCE_USER,
			RESOURCE_POST,
			RESOURCE_COMMENT,
		];
	}

	public function getRules()
	{
		return [
			RULE_ALL,
			RULE_VIEW,
			RULE_CREATE,
			RULE_EDIT,
			RULE_DELETE,
		];
	}

}
