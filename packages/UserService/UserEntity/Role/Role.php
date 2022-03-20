<?php

namespace Packages\UserService\UserEntity\Role;

Enum Role: int
{
    case ADMIN = 1;
    case MODERATOR = 2;
    case USER = 3;
}