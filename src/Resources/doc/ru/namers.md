Неймеры
===
AtomUploaderBundle представляет следующие неймеры:

- #### basename
  Для генерации оригинальных имен.

- #### unique_id
  Для генерации уникальных имен.


Создание своего неймера
---

Реализуйте интерфейс [`Atom\Uploader\Namer\INamer`][INamer]
и зарегистрируйте его в качестве сервиса с тегом `atom_uploader.namer_repo` и параметром `strategy`. <br />
Например:
```yaml
services:
    acme.deep_directory_namer:
        public: false
        class: Acme\Namer\DeepDirectoryNamer
        tags:
            -  { name: atom_uploader.namer_repo, strategy: deep_directory }
```

> Создавайте сколько угодно неймеров - это не влияет на производительность, <br />
 Т.к. неиспользуемые неймеры будут удалены на этапе оптимизации DIC. <br />
 И если созданный вами неймер могут быть полезным для других то не забудьте отправит pull request,<br />
 я очень ценю любой вклад в проект.

Связанные ссылки
---

- [Использование с встраиваемыми объектами doctrine][usage-with-doctrine-embeddables]
- [Использование с сущностями doctrine][usage-with-doctrine-entities]
- [Справка о всех параметрах][reference]
- [Адаптеры файловой системы][fs-adapters]
- [Событие][events]
- [Интеграция с хранилища данных][datastore-integration]

[usage-with-doctrine-entities]: usage-with-doctrine-entities.md
[usage-with-doctrine-embeddables]: usage-with-doctrine-embeddables.md
[reference]: reference.md
[events]: events.md
[fs-adapters]: fs-adapters.md
[datastore-integration]: datastore-integration.md
[INamer]: https://github.com/atom-php/uploader/blob/master/src/Naming/INamer.php