# PHP Basic access management with permissions 

## Requirements
- php >= 7.1
- composer https://getcomposer.org/download/

## Main goal

The goal of this library is to give the ability to manage in simple way the access level of an user based on the list of permissions she/he has.


## How to setup
```
composer require "dmakome/access-manager"
```

## How to use it

It will be good to use a Dependency injection Library, but if you cannot feel free to create a new instance of `DefaultDecisionManager`.

But before to do that, you need to implement the `Ccvf2s\AccessManager\Domain\User\UserProvider` interface.
This will give to the library the ability to retrieve your user based on the id passed in `findUser`.

Example:

```
<?php

use Ccvf2s\AccessManager\Application\DefaultDecisionManager;

$filePath = 'security.yml';
$decisionManager = new DefaultDecisionManager(
                    new YamlPermissionProvider(new YamlParser(), $filePath),
                    new EmployeeProvider(),// EmployeeProvider implements UserProvider
                    new DefaultUserAccess()
                );

$access = $decisionManager->isGranted('ROLE_USER', 1);
```

`$access` will be `true` if the user is allowed and `false` if not.

Example of the content in the yaml file(The file has to respect this architecture else there will be exceptions)
```yaml
ACCESS:
  ROLES:
    ROLE_USER: ALLOWED_1
    ROLE_MODERATOR: [ROLE_USER, ALLOWED_2]
    ROLE_ADMIN: [ROLE_MODERATOR, ALLOWED_3]
    ROLE_SUPER_ADMIN: [ROLE_ADMIN, ALLOWED_4]
```

## Overriding logic

The library provide some interfaces if you want to provide your own logic:

- `UserProvider` for providing your user provider.
- `PermissionProvider` for providing your list of permissions.
- `UserAccess` for providing some complex logic to give access to the user.
- `DecisionManager` if you want override and use your own.

## Exceptions

You will find some exceptions in the `Application` directory.

## How To Run The Test
```
composer test
```

## How To Contribute
- Fork this repo
- Post an issue https://github.com/ccvf2s/access-manager/issues
- Create the PR(Pull Request) and wait for the review