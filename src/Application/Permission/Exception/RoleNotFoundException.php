<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <makomedominick@gmail.com>
 */

namespace Ccvf2s\AccessManager\Application\Permission\Exception;

use RuntimeException;

class RoleNotFoundException extends RuntimeException
{

  private const MESSAGE = 'The role %s has not been found.';

  /**
   * RoleNotFoundException constructor.
   *
   * @param string $role role not found
   */
  public function __construct(string $role)
  {
    parent::__construct(sprintf(self::MESSAGE, $role));
  }

}