<?php 

namespace Northern\Acl;

use Northern\Common\Helper\ArrayHelper as Arr;

class AclTest extends \PHPUnit_Framework_TestCase {

	public function testMemberAccess()
	{
		$acl = new Acl();
		$acl->loadPermissions( TestPermissions::getPermissions() );

		// Load the permissions for a member.
		$permissions = new TestPermissions( $acl, TestPermissions::ROLE_MEMBER );

		// Member is allowed to 'view' all resources.
		$this->assertTrue( $permissions->canViewUser() );
		$this->assertTrue( $permissions->canViewPost() );
		$this->assertTrue( $permissions->canViewComment() );

		// Member can 'create' posts and comments.
		$this->assertTrue( $permissions->canCreatePost() );
		$this->assertTrue( $permissions->canCreateComment() );
		$this->assertTrue( $permissions->canEditPost() );
		$this->assertTrue( $permissions->canEditComment() );

		// Member cannot do any 'admin' things.
		$this->assertFalse( $permissions->canDeleteUser() );
		$this->assertFalse( $permissions->canDeletePost() );
		$this->assertFalse( $permissions->canDeleteComment() );
	}

	public function testAdminAccess()
	{
		$acl = new Acl();
		$acl->loadPermissions( TestPermissions::getPermissions() );

		// Load the permissions for an admin.
		$permissions = new TestPermissions( $acl, TestPermissions::ROLE_ADMIN );

		// Admin should be able to delete all things.
		$this->assertTrue( $permissions->canDeleteUser() );
		$this->assertTrue( $permissions->canDeletePost() );
		$this->assertTrue( $permissions->canDeleteComment() );
	}

}
