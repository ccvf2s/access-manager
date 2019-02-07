<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <makomedominick@gmail.com>
 */

namespace Ccvf2s\AccessManager\Domain\Permission;

interface PermissionProvider
{

  /**
   * @param string $role role
   *
   * @return PermissionModel[]
   */
  public function findPermissionsByRole(string $role) : array;

}