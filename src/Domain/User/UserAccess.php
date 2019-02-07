<?php
declare(strict_types = 1);

/**
 * @author    Dominick Makome <makomedominick@gmail.com>
 */

namespace Ccvf2s\AccessManager\Domain\User;

use Ccvf2s\AccessManager\Domain\Permission\PermissionModel;

interface UserAccess
{
  /**
   * @param UserModel         $user
   * @param PermissionModel[] $expectedPermissions
   *
   * @return bool
   */
  public function check(UserModel $user, array $expectedPermissions) : bool;

}