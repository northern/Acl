# ACL

A simple Role based Access Control List build on Zend Framework 2 ACL.

## Introduction

Northern\Acl is a role based ACL that allows for easy definition of permissions for specific roles. Roles can inherit from other roles. Simply by storing a role against a user, using that role would allow you to test if that role is permitted a certain access criteria.

## Installation

To use Northern\Acl add it to your project using Composer:

    "northern/acl": "1.*"

## Usage

To use Northern\Acl start by defining a permissions list. We can start with an empty list:

    $permsissions = [
       'roles'     => [],
       'resources' => [],
       'rules'     => [],
    ];

Our permissions list contains three top-level requirements, `roles`, `resources` and `rules`. The idea behind a role based ACL is that a specific role has access to resources through specified rules. Don't confuse the elements you define in this list with 'real' objects in your application. The permissions list is simply a structure (or model) we test against, it is static and therefore it doesn't need to be stored in a database but can simply be defined in a business object in your application as part of your business rules.

Let's add some permissions..

For the purpose of this demonstration we define four roles; `guest`, `member`, `author` and `admin`. For the sake of argument, we define the resources for a simple blog so we have `post` and `comment` as resources:

    $permsissions = [
       'roles'     => [
          ['name' => 'guest'],
          ['name' => 'member', 'parent' => 'guest'],
          ['name' => 'author', 'parent' => 'member'],
          ['name' => 'admin',  'parent' => 'author'],
       ],
       'resources' => [
          ['name' => 'post'],
          ['name' => 'comment'],
       ],
       'rules'     => [],
    ];

Easy as. Now lets define a rule that allows guests to view both posts and comments:

    $permsissions = [
       'roles'     => [
          ['name' => 'guest'],
          ['name' => 'member', 'parent' => 'guest'],
          ['name' => 'admin', 'parent' => 'member'],
       ],
       'resources' => [
          ['name' => 'post'],
          ['name' => 'comment'],
       ],
       'rules'     => [
          [
             'access'      => 'allow',
             'role'        => 'guest',
             'permissions' => ['view'],
             'resources'   => ['post', 'comment'],
          ]
       ],
    ];

As you can see, the rule is pretty straight forward. both `permissions` and `resources` can either be set as single values or as an array. Let's create a rule that allows members to create comments:

    $permsissions = [
       'roles'     => [
          ['name' => 'guest'],
          ['name' => 'member', 'parent' => 'guest'],
          ['name' => 'admin', 'parent' => 'member'],
       ],
       'resources' => [
          ['name' => 'post'],
          ['name' => 'comment'],
       ],
       'rules'     => [
          [
             'access'      => 'allow',
             'role'        => 'guest',
             'permissions' => ['view'],
             'resources'   => ['post', 'comment'],
          ], [
             'access'      => 'allow',
             'role'        => 'member',
             'permissions' => ['create'],
             'resources'   => ['comment'],
          ]
       ],
    ];

Great. Now let's fill in the rest of the permissions:

    $permsissions = [
       'roles'     => [
          ['name' => 'guest'],
          ['name' => 'member', 'parent' => 'guest'],
          ['name' => 'admin', 'parent' => 'member'],
       ],
       'resources' => [
          ['name' => 'post'],
          ['name' => 'comment'],
       ],
       'rules'     => [
          [
             'access'      => 'allow',
             'role'        => 'guest',
             'permissions' => ['view'],
             'resources'   => ['post', 'comment'],
          ], [
             'access'      => 'allow',
             'role'        => 'member',
             'permissions' => ['create'],
             'resources'   => ['comment'],
          ], [
             'access'      => 'allow',
             'role'        => 'author',
             'permissions' => ['create', 'edit', 'delete'],
             'resources'   => ['post'],
          ], [
             'access'      => 'allow',
             'role'        => 'admin',
             'permissions' => NULL,
             'resources'   => NULL,
          ]
       ],
    ];

We added the author permissions and set the admin permissions to allow all access on all resources.

To use these permissions we need to load them into the ACL, like this:

    $acl = new \Northern\Acl\Acl();
    $acl->loadPermissions( $permissions );

The `$acl` instance will allow us to test for permissions through the `isAllowed` method. However, the true power of Northern\Acl is in the `Permissions` class of which need need to create a subclass:

    class Permissions extends \Northern\Acl\Permissions {

        public function getRoles()
        {
           return ['guest', 'member', 'author', 'admin'];
        }

        public function getResources()
        {
           return ['post', 'comment'];
        }

        public function getRules()
        {
        	  return ['create', 'view', 'edit', 'delete'];
        }

    }

We can now use this `Permissions` class to do some magic:

    $acl = new \Northern\Acl\Acl();
    $acl->loadPermissions( $permissions );

    $permissions = new Permissions( $acl, 'member' );

    $permissions->canCreatePost();
    // TRUE!

As you can see. The `Permissions` instance allows you to test for permissions on a role through magic methods.

That's all folks!
