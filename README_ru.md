AtomUploaderBundle это пакет для symfony который обеспечит сохранность файлов.
==============================================================================

[![Gitter](https://badges.gitter.im/atom-azimov/uploader-bundle.svg)](https://gitter.im/atom-azimov/uploader-bundle?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Build Status](https://travis-ci.org/atom-azimov/uploader-bundle.svg?branch=master)](https://travis-ci.org/atom-azimov/uploader-bundle)
[![Latest Stable Version](https://poser.pugx.org/atom-azimov/uploader-bundle/v/stable)](https://packagist.org/packages/atom-azimov/uploader-bundle)
[![Latest Unstable Version](https://poser.pugx.org/atom-azimov/uploader-bundle/v/unstable)](https://packagist.org/packages/atom-azimov/uploader-bundle)
[![Total Downloads](https://poser.pugx.org/atom-azimov/uploader-bundle/downloads)](https://packagist.org/packages/atom-azimov/uploader-bundle)
[![Dependency Status](https://www.versioneye.com/user/projects/56e910044e714c004f4d09be/badge.svg?style=flat)](https://www.versioneye.com/user/projects/56e910044e714c004f4d09be)
[![Code Climate](https://codeclimate.com/github/atom-azimov/uploader-bundle/badges/gpa.svg)](https://codeclimate.com/github/atom-azimov/uploader-bundle)
[![Issue Count](https://codeclimate.com/github/atom-azimov/uploader-bundle/badges/issue_count.svg)](https://codeclimate.com/github/atom-azimov/uploader-bundle)

Возможности:
------------

- Автоматическое создание имен и сохранение файлов.
- Внедрят файл обратно в объект, когда он будет загружен из хранилища данных как экземпляр `\SplFileInfo`.
- Удаление файла из файловой системы при удалении(или обновлении) объекта из хранилища данных.


Быстрый старт
-------------

> Быстрый старт подходит для RAD разработки<br />
> Для более гибкого использования читайте [документацию](src/Resources/doc/ru/index.md)

#### Установка:
```
composer require atom-azimov/uploader-bundle
```

#### Включение
```php
# app/AppKernel.php
public function registerBundles()
{
    $bundles = [
        ...
        new Atom\UploaderBundle\AtomUploaderBundle(),
        ...
    ];
}
```

#### Использования

AtomUploaderBundle представляет готовый [объект значение](http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/tutorials/embeddables.html)
 для быстрой разработки.

Чтобы использовать просто встройте его в сущность:
```php
# src/Entity/User.php

namespace Acme\Entity;

use Doctrine\ORM\Mapping\Embedded;

class User
{
    ...

    /**
     * @Embedded(class="Atom\Uploader\Model\Embeddable\FileReference")
     */
    private $avatar;
}
```

Готово ! теперь если присвоите экземпляр `Atom\Uploader\Model\Embeddable\FileReference` в `User::$avatar`
то при персисте и обновлении
прикрепленные файлы автоматически будут сохранены в файловой системе(по умолчанию в `%kernel.root_dir%/../web/uploads`)

Простой пример:
```php
public function someController(\Symfony\Component\HttpFoundation\Request $request)
{
    // $file должен быть экземпляром `\SplFileInfo`
    $file = $request->files->get('...');
    $avatar = new Atom\Uploader\Model\Embeddable\FileReference($file);

    $user = new Acme\Entity\User();
    $user->setAvatar($avatar);

    $em = $this->get('doctrine.entity_manager');

    // Генерируется имя файла и сохраняется в файловой системе, по умолчанию в '%kernel.root_dir%/../web/uploads'.
    $em->persist($user);

    // Если все хорошо то ничего не делается, иначе файл удаляется.
    $em->flush();
}
```

> Для упрощения примеры показаны в контроллере

Документация
------------

Посмотрите [src/Resources/doc/ru/index.md](src/Resources/doc/ru/index.md)

Помочь проекту
--------------

Посмотрите [contributing_ru.md](contributing_ru.md)