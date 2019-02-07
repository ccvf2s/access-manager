<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <dmakome@wayfair.com>
 */

namespace Ccvf2s\AccessManager\Application\User;

use Ccvf2s\AccessManager\Domain\Permission\PermissionModel;
use Ccvf2s\AccessManager\Domain\User\UserAccess;
use Ccvf2s\AccessManager\Domain\User\UserModel;

class DefaultUserAccess implements UserAccess
{

  /**
   * @param UserModel         $user
   * @param PermissionModel[] $expectedPermissions
   *
   * @return bool
   */
  public function check(UserModel $user, array $expectedPermissions) : bool
  {
    $numberOfAccess          = 0;
    $expectedPermissionsName = $this->getPermissionsName($expectedPermissions);

    foreach ($user->getPermissions() as $permission) {
      if (isset($expectedPermissionsName[$permission->getName()])) {
        $numberOfAccess++;
      }
    }

    return $numberOfAccess >= count($expectedPermissions);
  }

  /**
   * @param PermissionModel[] $permissions
   *
   * @return array
   */
  private function getPermissionsName(array $permissions) : array
  {
    $permissionsName = [];
    foreach ($permissions as $permission) {
      $permissionsName[$permission->getName()] = $permission->getName();
    }

    return $permissionsName;
  }

}