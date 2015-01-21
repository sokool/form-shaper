# Authorize Module for Zend Framework 2

- your application is based on users?
- would you like to expose or hide some functionality for different users in the system?
- potential user can have one or more roles and/or permissions?

If you answered YES, then this module is for you, This, what you looking is commonly known as RBAC (role based access control).

**MintSoft Authorize** module is based on **RBAC** approach, which restricting system access to authorized users. This module will let you easy manage
access to your system resources based on logged user **ROLES** and/or **PERMISSIONS** .

## Status

[![Master Branch Build Status](https://travis-ci.org/sokool/authorize.svg?branch=master)](https://travis-ci.org/sokool/authorize)

## Installation
Installation of MintSoft/Authorize uses composer. For composer documentation, please refer to [getcomposer.org](http://getcomposer.org/).

```sh
php composer.phar require mint-soft/authorize:dev-master
```

Then add `MintSoft\Authorize` to your `config/application.config.php`
