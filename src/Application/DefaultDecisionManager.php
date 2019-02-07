<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <makomedominick@gmail.com>
 */

namespace Ccvf2s\AccessManager\Application;

use Ccvf2s\AccessManager\Domain\DecisionManager;
use Ccvf2s\AccessManager\Domain\Permission\PermissionProvider;
use Ccvf2s\AccessManager\Domain\User\UserAccess;
use Ccvf2s\AccessManager\Domain\User\UserProvider;

class DefaultDecisionManager implements DecisionManager
{

  /**
   * @var PermissionProvider
   */
  private $permissionProvider;

  /**
   * @var UserProvider
   */
  private $userProvider;

  /**
   * @var UserAccess
   */
  private $userAccess;

  /**
   * DefaultDecisionManager constructor.
   *
   * @param PermissionProvider $permissionProvider
   * @param UserProvider       $retrieveUser
   * @param UserAccess         $userAccess
   */
  public function __construct(
      PermissionProvider $permissionProvider,
      UserProvider $retrieveUser,
      UserAccess $userAccess
  ) {
    $this->permissionProvider = $permissionProvider;
    $this->userProvider       = $retrieveUser;
    $this->userAccess         = $userAccess;
  }

  /**
   * @param string $role   role
   * @param mixed  $userID user id
   *
   * @return bool
   */
  public function isGranted(string $role, $userID) : bool
  {
    $expectedPermissions = $this->permissionProvider->findPermissionsByRole($role);
    $user                = $this->userProvider->findUser($userID);

    return $this->userAccess->check($user, $expectedPermissions);
  }

}