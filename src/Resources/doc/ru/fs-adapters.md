Адаптеры файловой системы
===

AtomUploaderBundle представляет следующие адаптеры:

- #### local
  Адаптер для локального хранение файлов. <br />
  Параметр `fs_prefix` должен быть абсолютным путем.

- #### flysystem
  Обертка над [flysystem]. <br />
  Параметр `fs_prefix` должен быть названием файловой системы [flysystem].

Создание своего адаптера
---

Реализуйте интерфейс [`Atom\Uploader\Filesystem\IFilesystemAdapter`][IFilesystemAdapter]
и зарегистрируйте его в качестве сервиса с тегом `atom_uploader.filesystem_adapter_repo` и параметром `adapter`. <br />
Например:
```yaml
services:
    acme.dropbox_adapter:
        public: false
        class: Acme\Filesystem\DropboxAdapter
        tags:
            -  { name: atom_uploader.filesystem_adapter_repo, adapter: dropbox }
```

> Создавайте сколько угодно адаптеров - это не влияет на производительность, <br />
 Т.к. неиспользуемые адаптеры будут удалены на этапе оптимизации DIC. <br />
 И если созданный вами адаптер могут быть полезным для других то не забудьте отправит pull request,<br />
 я очень ценю любой вклад в проект.

Связанные ссылки
---

- [Использование с встраиваемыми объектами doctrine][usage-with-doctrine-embeddables]
- [Использование с сущностями doctrine][usage-with-doctrine-entities]
- [Справка о всех параметрах][reference]
- [Неймеры][namers]
- [Событие][events]
- [Интеграция с хранилища данных][datastore-integration]

[usage-with-doctrine-entities]: usage-with-doctrine-entities.md
[usage-with-doctrine-embeddables]: usage-with-doctrine-embeddables.md
[reference]: reference.md
[namers]: namers.md
[events]: events.md
[datastore-integration]: datastore-integration.md
[flysystem]: https://github.com/thephpleague/flysystem
[IFilesystemAdapter]: https://github.com/atom-php/uploader/blob/master/src/Filesystem/IFilesystemAdapter.php