AtomUploaderBundle
====

Symfony пакет который обеспечит сохранность файлов

---

[![Build Status](https://travis-ci.org/atom-azimov/uploader-bundle.svg?branch=master)](https://travis-ci.org/atom-azimov/uploader-bundle)
[![Gitter](https://badges.gitter.im/atom-azimov/uploader-bundle.svg)](https://gitter.im/atom-azimov/uploader-bundle?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Dependency Status](https://www.versioneye.com/user/projects/56e910044e714c004f4d09be/badge.svg?style=flat)](https://www.versioneye.com/user/projects/56e910044e714c004f4d09be)
[![Code Climate](https://codeclimate.com/github/atom-azimov/uploader-bundle/badges/gpa.svg)](https://codeclimate.com/github/atom-azimov/uploader-bundle)

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Latest Stable Version](https://poser.pugx.org/atom-azimov/uploader-bundle/v/stable)](https://packagist.org/packages/atom-azimov/uploader-bundle)
[![Latest Unstable Version](https://poser.pugx.org/atom-azimov/uploader-bundle/v/unstable)](https://packagist.org/packages/atom-azimov/uploader-bundle)
[![Total Downloads](https://poser.pugx.org/atom-azimov/uploader-bundle/downloads)](https://packagist.org/packages/atom-azimov/uploader-bundle)

---

Мотивация
---

Проект создался с целью облегчит загрузку файлов используя [встраиваемых объектов doctrine][embeddables].<br />
Но он не зависит от doctrine и его можно использовать с другими хранилищами данных, даже с простыми массивами.

Возможности:
---

- Автоматическое создание имён и сохранение файлов.
- Внедрят файл обратно в объект, когда он будет загружен из хранилища данных как экземпляр `\SplFileInfo`.
- Внедрят URI в объект, когда он будет загружен из хранилища данных.
- Удаление файла из файловой системы при удалении(или обновлении) объекта из хранилища данных.

Вес функционал настраиваемый.

> Неиспользуемые сервисы удаляется на этапе оптимизации DIC, а используемые сервисы инициализируется только тогда когда они понадобится.

Быстрый старт
---

> Быстрый старт подходит для [RAD] разработки<br />
> Для более гибкого использования читайте [документацию][documentation]

#### Установка:
```
composer require atom-azimov/uploader-bundle
```

#### Включения
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

AtomUploaderBundle представляет готовый
[встраиваемый объект][embeddables] для быстрой разработки.

Просто встройте его в сущность:
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

Готово ! Теперь прикреплённые файлы автоматически сохраняются в файловой системы,
по умолчанию в "%kernel.root_dir%/../web/uploads"

#### Примеры

##### Сохранение загруженного файла:
```php
$file = // экземпляр \SplFileInfo
$em = // entity manager

$avatar = new Atom\Uploader\Model\Embeddable\FileReference($file);

$user = new Acme\Entity\User();
$user->setAvatar($avatar);

// Генерируется имя файла и сохраняется в файловой системы.
$em->persist($user);

// Если все хорошо то ничего не делается, иначе файл удаляется.
$em->flush();
```

##### Обновление:
```php
$file = // экземпляр \SplFileInfo
$user = // экземпляр Acme\Entity\User
$avatar = new Atom\Uploader\Model\Embeddable\FileReference($file);
$user->setAvatar($avatar);

// Генерируется имя файла и сохраняется в файловой системы.
// Удаляется старый файл если имя файла не совпадает с новым.
$em->flush();
```
##### Получение:
```php
// внедряется URI и информация о файле.
$user = $em->find('Acme\Entity\User', 1);
```
> Внедрения информацию о файле (\SplFileInfo) по умолчанию отключена,
> его можно включит в `config.yml`:
```yaml
atom_uploader:
    mappings:
        Atom\Uploader\Model\Embeddable\FileReference:
            inject_file_info_on_load: true
```

##### Удаление:
```php
$user = // экземпляр Acme\Entity\User

$em->setAvatar(null);
// или
$em->remove($user);

// Файл удаляется.
$em->flush();
```


Документация
---

См. [src/Resources/doc/ru/index.md][documentation]

Внести свой вклад проекту
---

См. [contributing_ru.md][contributing]

[embeddables]: http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/tutorials/embeddables.html
[RAD]: https://ru.wikipedia.org/wiki/RAD_(%D0%BF%D1%80%D0%BE%D0%B3%D1%80%D0%B0%D0%BC%D0%BC%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5)
[documentation]: src/Resources/doc/ru/index.md
[contributing]: contributing_ru.md
