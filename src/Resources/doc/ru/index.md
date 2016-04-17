Начало работы с AtomUploaderBundle
===

Установка
---
```
composer require atom-azimov/uploader-bundle
```

Включение
---

```php
# src/AppKernel.php
public function registerBundles()
{
    $bundles = [
        ...
        new Atom\UploaderBundle\AtomUploaderBundle(),
        ...
    ];
}
```

Использование
---

- [С встраиваемыми объектами doctrine][usage-with-doctrine-embeddables]
- [С сущностями doctrine][usage-with-doctrine-entities]

Связанные ссылки
---

- [Справка о всех параметрах][reference]
- [Неймеры][namers]
- [Адаптеры файловой системы][fs-adapters]
- [Событие][events]
- [Интеграция с хранилища данных][datastore-integration]

[usage-with-doctrine-entities]: usage-with-doctrine-entities.md
[usage-with-doctrine-embeddables]: usage-with-doctrine-embeddables.md
[reference]: reference.md
[namers]: namers.md
[events]: events.md
[fs-adapters]: fs-adapters.md
[datastore-integration]: datastore-integration.md
