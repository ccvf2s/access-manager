<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <makomedominick@gmail.com>
 */

namespace Ccvf2s\AccessManager\Domain\User;

use Ccvf2s\AccessManager\Domain\Permission\PermissionModel;

class UserModel
{

  /**
   * @var mixed
   */
  private $id;

  /**
   * @var PermissionModel[]
   */
  private $permissions;

  /**
   * UserModel constructor.
   *
   * @param mixed             $id
   * @param PermissionModel[] $permissions
   */
  public function __construct($id, array $permissions)
  {
    $this->id          = $id;
    $this->permissions = $permissions;
  }


  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return PermissionModel[]
   */
  public function getPermissions() : array {
    return $this->permissions;
  }

}