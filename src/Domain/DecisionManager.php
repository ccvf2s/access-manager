<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <makomedominick@gmail.com>
 */

namespace Ccvf2s\AccessManager\Domain;

interface DecisionManager
{

  /**
   * @param string $role role
   * @param string $userID user id
   *
   * @return bool
   */
  public function isGranted(string $role, $userID) : bool;

}