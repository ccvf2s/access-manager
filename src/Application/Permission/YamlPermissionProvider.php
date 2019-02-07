<?php
declare(strict_types = 1);

/**
 * @author    Dominick Makome <dmakome@wayfair.com>
 */

namespace Ccvf2s\AccessManager\Application\Permission;

use Ccvf2s\AccessManager\Application\Permission\Parser\YamlParser;
use Ccvf2s\AccessManager\Domain\Permission\PermissionModel;
use Ccvf2s\AccessManager\Domain\Permission\PermissionProvider;
use Ccvf2s\AccessManager\Application\Permission\Exception\NotValidNameException;
use Ccvf2s\AccessManager\Application\Permission\Exception\RoleNotFoundException;

class YamlPermissionProvider implements PermissionProvider
{

  private const ROLES_KEY        = 'ROLES';
  private const PREFIX           = 'ROLE_';

  /**
   * @var YamlParser
   */
  private $parser;

  /**
   * @var string
   */
  private $pathFile;

  /**
   * YamlPermissionProvider constructor.
   *
   * @param YamlParser $parser
   * @param string     $pathFile
   */
  public function __construct(YamlParser $parser, string $pathFile)
  {
    $this->parser   = $parser;
    $this->pathFile = $pathFile;
  }

  /**
   * @param string $role
   *
   * @return PermissionModel[]
   */
  public function findPermissionsByRole(string $role) : array
  {
    $results                = $this->parser->parseFile($this->pathFile);
    $mappedRolesPermissions = $results[self::ROLES_KEY];

    $this->checkRole($role, $mappedRolesPermissions);

    $expectedPermissionsRole = $this->getPermissionsByRole($role, $mappedRolesPermissions);

    return $expectedPermissionsRole;
  }

  /**
   * @param string $role
   * @param array  $mappedRolesPermissions
   *
   * @throws NotValidNameException
   * @throws RoleNotFoundException
   */
  private function checkRole(string $role, array $mappedRolesPermissions)
  {
    if (!$this->isValidRoleName($role)) {
      throw new NotValidNameException();
    }

    if (!$this->isRoleExisting($role, $mappedRolesPermissions)) {
      throw new RoleNotFoundException($role);
    }
  }

  /**
   * @param string $role
   *
   * @return bool
   */
  private function isValidRoleName(string $role) : bool
  {
    return substr($role, 0, 5) === self::PREFIX;
  }

  /**
   * @param string $role
   * @param array  $mappedRolesPermissions
   *
   * @return bool
   */
  private function isRoleExisting(string $role, array $mappedRolesPermissions) : bool {
    return isset($mappedRolesPermissions[$role]);
  }

  /**
   * @param string $role
   * @param array  $mappedRolesPermissions
   *
   * @return PermissionModel[]
   */
  private function getPermissionsByRole(string $role, array $mappedRolesPermissions) : array
  {
    $result = [];

    foreach ($mappedRolesPermissions[$role] as $permissionName) {
      if ($this->isValidRole($permissionName, $mappedRolesPermissions)) {
        $result = $result + $this->getPermissionsByRole($permissionName, $mappedRolesPermissions);
      } else {
        $result[$permissionName] = new PermissionModel($permissionName);
      }
    }

    return $result;
  }

  /**
   * @param string $permission
   * @param array  $mappedRolesPermissions
   *
   * @return bool
   */
  private function isValidRole(string $permission, array $mappedRolesPermissions) : bool
  {
    return $this->isValidRoleName($permission) && $this->isRoleExisting($permission, $mappedRolesPermissions);
  }

}