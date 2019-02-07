<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <makomedominick@gmail.com>
 */

namespace Ccvf2s\AccessManager\Application\Permission\Exception;

use RuntimeException;

class NotValidNameException extends RuntimeException
{

  private const MESSAGE = 'Not valid name, all the roles have to be prefixed by ROLE_';

  /**
   * NotValidNameException constructor.
   */
  public function __construct()
  {
    parent::__construct(self::MESSAGE);
  }

}