Использование с встраиваемыми объектами doctrine
===
Создавайте встраиваемый объект
```php
# src/Entity/Avatar.php

use Doctrine\ORM\Mapping\Column;

class Avatar {

    /**
     * Если присвоите экземпляр \SplFileInfo к этому свойсту
     * то при сохранение или обновлении сущноста генерируется имя файла и сохраняется в файловой системы.
     * Сгенирированная имя файла является относительним путем к хранилище файлов.
     *
     * @Column()
     */
    private $file;

    /**
     * Эта необязательная свойства нужна для внедрение URI при загрузке сущноста.
     * Этот функционал можно отключит.
     */
    private $uri;

    
    /**
     * Эта необязательная свойства нужна для внедрение информацию о файле(\SplFileInfo) при загрузке сущноста.
     * Этот функционал можно отключит.
     */
    private $fileInfo;

    public function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }
}
```

Встройте его в сущность
```php
# src/Entity/User.php

use Doctrine\ORM\Mapping\Embedded;

class User {
    ...

    /**
     * @Embedded(class="Acme\Entity\Avatar")
     */
    private $avatar;

    ...
}
```

Мэппинг:

```yaml
# app/config/config.yml

atom_uploader:
    mappings:
        Acme\Entity\Avatar:
            
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
$avatar = new Acme\Entity\Avatar($file);

$user = new Acme\Entity\User();
$user->setAvatar($avatar);

// Генерируется имя файла и сохраняется в файловой системы.
$em->persist($user);

// Если все хорошо то ничего не делается, иначе файл удаляется.
$em->flush();
```

##### Обновления:
```php
$file = // экземпляр \SplFileInfo
$user = // экземпляр Acme\Entity\User
$avatar = new Acme\Entity\Avatar($file);
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

- [Использование с сущностями doctrine][usage-with-doctrine-entities]
- [Справка о всех параметрах][reference]
- [Неймеры][namers]
- [Адаптеры файловой системы][fs-adapters]
- [Событие][events]
- [Интеграция с хранилища данных][datastore-integration]

[usage-with-doctrine-entities]: usage-with-doctrine-entities.md
[reference]: reference.md
[namers]: namers.md
[events]: events.md
[fs-adapters]: fs-adapters.md
[datastore-integration]: datastore-integration.md
