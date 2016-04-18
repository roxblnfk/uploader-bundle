Событие
===
AtomUploaderBundle при обработке файла отправляет следующие событие:

- [`Atom\Uploader\Event\IUploadEvent::PRE_UPLOAD`][PRE_UPLOAD] <br />
  Перед сохранением загруженного файла.

- [`Atom\Uploader\Event\IUploadEvent::POST_UPLOAD`][POST_UPLOAD] <br />
  После сохранения загруженного файла.

- [`Atom\Uploader\Event\IUploadEvent::PRE_UPDATE`][PRE_UPDATE] <br />
  При обновлении данных, перед сохранением загруженного файла.

- [`Atom\Uploader\Event\IUploadEvent::POST_UPDATE`][POST_UPDATE] <br />
  При обновлении данных, после сохранения загруженного файла.

- [`Atom\Uploader\Event\IUploadEvent::PRE_REMOVE`][PRE_REMOVE] <br />
  Перед удалением файла.

- [`Atom\Uploader\Event\IUploadEvent::POST_REMOVE`][POST_REMOVE] <br />
  После удаления.

- [`Atom\Uploader\Event\IUploadEvent::PRE_REMOVE_OLD_FILE`][PRE_REMOVE_OLD_FILE] <br />
  Перед удалением старого файла.

- [`Atom\Uploader\Event\IUploadEvent::POST_REMOVE_OLD_FILE`][POST_REMOVE_OLD_FILE] <br />
  После удаления старого файла.

- [`Atom\Uploader\Event\IUploadEvent::PRE_INJECT_URI`][PRE_INJECT_URI] <br />
  Перед внедрением URI.

- [`Atom\Uploader\Event\IUploadEvent::POST_INJECT_URI`][POST_INJECT_URI] <br />
  После внедрения URI.

- [`Atom\Uploader\Event\IUploadEvent::PRE_INJECT_FILE_INFO`][PRE_INJECT_FILE_INFO] <br />
  Перед внедрением информацию о файле (\SplFileInfo)

- [`Atom\Uploader\Event\IUploadEvent::POST_INJECT_FILE_INFO`][POST_INJECT_FILE_INFO] <br />
  После внедрения информацию о файле (\SplFileInfo)

> Каждое событие является экземпляром [`Atom\Uploader\Event\IUploadEvent`][IUploadEvent] <br />
> поэтому в событиях можно получит [`metadata`][metadata] и экземпляр данных(массив или объект). <br />
> А также в событиях есть возможность остановит действия вызвав [`Atom\Uploader\Event\IUploadEvent::stopAction`][stopAction].
> Пример:

  ```php
  # src/Acme/Event/UploadListener.php

  class UploadListener {

      public function preUpload(\Atom\Uploader\Event\IUploadEvent $event)
      {
          $event->stopAction();
      }
  }
  ```

Связанные ссылки
---

- [Использование с встраиваемыми объектами doctrine][usage-with-doctrine-embeddables]
- [Использование с сущностями doctrine][usage-with-doctrine-entities]
- [Справка о всех параметрах][reference]
- [Неймеры][namers]
- [Адаптеры файловой системы][fs-adapters]
- [Интеграция с хранилища данных][datastore-integration]

[usage-with-doctrine-entities]: usage-with-doctrine-entities.md
[usage-with-doctrine-embeddables]: usage-with-doctrine-embeddables.md
[reference]: reference.md
[namers]: namers.md
[fs-adapters]: fs-adapters.md
[datastore-integration]: datastore-integration.md
[IUploadEvent]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php

[PRE_UPLOAD]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L12
[POST_UPLOAD]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L13

[PRE_UPDATE]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L15
[POST_UPDATE]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L16

[PRE_REMOVE]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L18
[POST_REMOVE]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L19

[PRE_REMOVE_OLD_FILE]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L21
[POST_REMOVE_OLD_FILE]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L22

[PRE_INJECT_URI]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L24
[POST_INJECT_URI]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L25

[PRE_INJECT_FILE_INFO]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L27
[POST_INJECT_FILE_INFO]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L28

[metadata]: https://github.com/atom-azimov/uploader/blob/master/src/Metadata/FileMetadata.php

[stopAction]: https://github.com/atom-azimov/uploader/blob/master/src/Event/IUploadEvent.php#L33