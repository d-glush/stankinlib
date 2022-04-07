<?php

namespace Packages\UserService\UserEntity\Role;

Enum Role: int
{
    case ADMIN = 1;
    case PUBLICIST = 2;
    case STUDENT = 3;
}