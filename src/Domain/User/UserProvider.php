<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <makomedominick@gmail.com>
 */

namespace Ccvf2s\AccessManager\Domain\User;

interface UserProvider
{

  /**
   * @param mixed $id
   *
   * @return UserModel
   */
  public function findUser($id) : UserModel;

}