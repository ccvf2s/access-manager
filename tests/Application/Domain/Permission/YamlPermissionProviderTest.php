<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <makomedominick@gmail.com>
 */

namespace Tests\Ccvf2s\AccessManager\Application\Domain\Permission;

use Ccvf2s\AccessManager\Application\Permission\Exception\NotValidNameException;
use Ccvf2s\AccessManager\Application\Permission\Exception\RoleNotFoundException;
use Ccvf2s\AccessManager\Application\Permission\Parser\YamlParser;
use Ccvf2s\AccessManager\Application\Permission\YamlPermissionProvider;
use Ccvf2s\AccessManager\Domain\Permission\PermissionModel;
use Ccvf2s\AccessManager\Domain\Permission\PermissionProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class YamlPermissionProviderTest extends TestCase {

  /**
   * @var YamlParser
   */
  private $yamlParser;

  /**
   * @var PermissionProvider
   */
  private $permissionProvider;

  /**
   * @return void
   */
  public function setUp()
  {
    $this->yamlParser = $this->prophesize(YamlParser::class);
    $this->yamlParser
        ->parseFile(Argument::type('string'))
        ->willReturn(
            [
                'ROLES' => [
                    'ROLE_USER'       => ['CAN_VIEW'],
                    'ROLE_PRIVILEGED' => ['ROLE_USER', 'CAN_EDIT'],
                    'ROLE_ADMIN'      => ['ROLE_PRIVILEGED', 'CAN_DELETE'],
                ]
            ]
        );

    $pathFile                 = 'security.yml';
    $this->permissionProvider = new YamlPermissionProvider($this->yamlParser->reveal(), $pathFile);
  }

  /**
   * @dataProvider dataProviderWithExceptions
   *
   * @param string $role             role
   * @param string $exception        exception
   * @param string $exceptionMessage exception message
   *
   * @return void
   *
   * @throws NotValidNameException
   * @throws RoleNotFoundException
   */
  public function testFindPermissionByRoleWillThrowARuntimeException(string $role, string $exception, string $exceptionMessage)
  {
    $this->expectException($exception);
    $this->expectExceptionMessage($exceptionMessage);
    $this->permissionProvider->findPermissionsByRole($role);
  }

  /**
   * @dataProvider dataProviderForValidRoles
   *
   * @param string $role                role asked
   * @param array  $expectedPermissions array of permission
   *
   * @return void
   */
  public function testFindPermissionByRoleWillReturnListOfPermissions(string $role, array $expectedPermissions)
  {
    $this->assertEquals($expectedPermissions, $this->permissionProvider->findPermissionsByRole($role));
  }

  /**
   * @return array
   */
  public function dataProviderForValidRoles() : array
  {
    return [
        'ROLE_USER should send one permission'        => [
            'role_name'   => 'ROLE_USER',
            'permissions' => [
                'CAN_VIEW' => new PermissionModel('CAN_VIEW')
            ]
        ],
        'ROLE_PRIVILEGED should send two permissions' => [
            'role_name'   => 'ROLE_PRIVILEGED',
            'permissions' => [
                'CAN_VIEW' => new PermissionModel('CAN_VIEW'),
                'CAN_EDIT' => new PermissionModel('CAN_EDIT')
            ],
        ],
        'ROLE_ADMIN should send three permissions'    => [
            'role_name'   => 'ROLE_ADMIN',
            'permissions' => [
                'CAN_VIEW'   => new PermissionModel('CAN_VIEW'),
                'CAN_EDIT'   => new PermissionModel('CAN_EDIT'),
                'CAN_DELETE' => new PermissionModel('CAN_DELETE')
            ],
        ]
    ];
  }

  /**
   * @return array
   */
  public function dataProviderWithExceptions() : array
  {
    return [
        'ROLE_CHECK not found'          => [
            'role_name'         => 'ROLE_CHECK',
            'exception'         => RoleNotFoundException::class,
            'exception message' => 'The role ROLE_CHECK has not been found.',
        ],
        'NOT_ROLE_CHECK not valid name' => [
            'role_name'         => 'NOT_ROLE_CHECK',
            'exception'         => NotValidNameException::class,
            'exception message' => 'Not valid name, all the roles have to be prefixed by ROLE_',
        ],
    ];
  }

}