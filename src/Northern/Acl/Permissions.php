<?php

namespace Northern\Acl;

abstract class Permissions {

	const ALLOW = 'allow';
	const DENY  = 'deny';

	protected $acl;
	protected $role;

	/**
	 * Loads the permissions for a specific role.
	 * 
	 * @param \Northern\Acl\Acl $acl
	 * @param string $role
	 */
	public function __construct( \Northern\Acl\Acl $acl, $role )
	{
		$this->acl  = $acl;
		$this->role = $role;
	}

	/**
	 * Returns an array of all ROLE_ constants.
	 *
	 * @return array
	 */
	abstract public function getRoles();

	/**
	 * Returns an array of all RESOURCE_ constants.
	 *
	 * @return array
	 */
	abstract public function getResources();

	/**
	 * Returns an array of all RULE_ constants.
	 *
	 * @return array
	 */
	abstract public function getRules();

	/**
	 * Probes for the existance of a permission. To probe, use either the role or a combination
	 * of the permission and the resource. E.g. to test for the 'admin' role use isRoleAdmin.
	 * To for 'post edit' permissions use, canEditPost, etc.
	 *
	 * @param  string $method
	 * @param  array  $args
	 * @return boolean
	 */
	public function __call( $method, $args )
	{
		$a = preg_split('/(?<=[a-z])(?=[A-Z])/x', $method);

		$a = array_map('strtolower', $a);

		if( $a[0] === 'is' )
		{
			$role = $a[2];

			return $this->role === $role;
		}
		else
		if( $a[0] === 'can' )
		{
			$resource   = $a[2];
			$permission = $a[1];

			return $this->acl->isAllowed( $this->role, $resource, $permission );
		}
		else
		{
			// TODO: Throw exception?
		}
	}

}
