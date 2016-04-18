Использование с сущностями doctrine
===

Включайте интеграцию с doctrine сущностями
```yaml
# app/config/config.yml
atom_uploader:
    driver:
      - orm
```

Создавайте сущность
```php
# src/Entity/User.php

use Doctrine\ORM\Mapping\Column;

class User {
    
    /**
     * Если присвоите экземпляр \SplFileInfo к этому свойства
     * то при сохранение или обновлении сущность генерируется имя файла и сохраняется в файловой системы.
     * Сгенерированная имя файла является относительным путем к хранилище файлов.
     *
     * @Column()
     */
    private $avatar;

    /**
     * Эта необязательная свойства нужна для внедрение URI при загрузке сущность.
     * Этот функционал можно отключит.
     */
    private $avatarUri;


    /**
     * Эта необязательная свойства нужна для внедрение информацию о файле(\SplFileInfo) при загрузке сущность.
     * Этот функционал можно отключит.
     */
    private $avatarFileInfo;
}
```

Мэппинг:

```yaml
# app/config/config.yml

atom_uploader:
    mappings:
        Acme\Entity\User:

            # Т.к. название свойств сущность отличается от значение по умолчанию, определяем их тут.
            file_setter: avatar
            file_getter: avatar
            uri_setter: avatarUri
            file_info_setter: avatarFileInfo


            # Включаем внедрение информацию о файле т.к. по умолчанию этот функционал отключен
            inject_file_info_on_load: true
```

> [Справка о всех параметрах][reference]

Примеры
---

##### Сохранение загруженного файла:
```php
$file = // экземпляр \SplFileInfo
$em = // entity manager

$user = new Acme\Entity\User();
$user->setAvatar($file);

// Генерируется имя файла и сохраняется в файловой системы.
$em->persist($user);

// Если все хорошо то ничего не делается, иначе файл удаляется.
$em->flush();
```

##### Обновления:
```php
$file = // экземпляр \SplFileInfo
$user = // экземпляр Acme\Entity\User
$user->setAvatar($file);

// Генерируется имя файла и сохраняется в файловой системы.
// Удаляется старый файл если имя файла не совпадает с новым.
$em->flush();
```
##### Получение:
```php
// внедряется URI и информация о файле.
$user = $em->find('Acme\Entity\User', 1);
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

Связанные ссылки
---

- [Использование с встраиваемыми объектами doctrine][usage-with-doctrine-embeddables]
- [Справка о всех параметрах][reference]
- [Неймеры][namers]
- [Адаптеры файловой системы][fs-adapters]
- [Событие][events]
- [Интеграция с хранилища данных][datastore-integration]

[usage-with-doctrine-embeddables]: usage-with-doctrine-embeddables.md
[reference]: reference.md
[namers]: namers.md
[events]: events.md
[fs-adapters]: fs-adapters.md
[datastore-integration]: datastore-integration.md
