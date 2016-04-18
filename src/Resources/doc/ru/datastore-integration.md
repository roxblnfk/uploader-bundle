Интеграция с хранилища данных
===

Интегрировать можно практический с любимы хранилищ данных даже с простыми массивами. <br />
Для этого нужно использовать [загрузчик][Uploader].

Примеры использование загрузчика с [doctrine dbal][dbal]:
---

#### Настройка

Мэппинг:
```yaml
atom_uploader:

    # Если писать не зарегистрированное значение,
    #   то просто будут разрешено использовать любые название при мэппинге.
    # В дальнейшем можно создать вспомогательный сервис для определение какие название мэппинга разрешается,
    #   в таком случае в мэппингах можно будет использовать название интерфейса,родительского класса или трейта.
    #
    # Нам достаточно писать любое название, допустим это будет dbal.
    drivers: [dbal]

    mappings:

      # мэппинг со значениями по умолчанию
      uploadable: ~
```

База данных:
```sql
CREATE TABLE IF NOT EXISTS uploadable (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  file VARCHAR(255)
);
```

##### Сохранение загруженного файла
```php
$file = // Экземпляр \SplFileInfo;
$conn = $container->get('database_connection');
$uploader = $container->get('atom_uploader.uploader');

// Любое уникальное для этой транзакции значение.
$transactionId = uniqid();

$uploadable = compact('file');

// Перед сохранением данных сохраняется прикрепленные файлы.
// Название файла будет сгенирировано автоматически согласно мэппингу,
// т.е. значение $uploadable['file'] будут изменено на автоматически сгенерированное название(относительный путь).
$uploader->persist($transactionId, $uploadable, 'uploadable' /* для массивов нужно явно указат название мэппинга */);

try {
    $uploadable['id'] = $conn->insert('uploadable', ['file' => $uploadable['file']]);

    // После успешного сохранение данных нужно уведомлять об этом.
    $uploader->saved($transactionId);
} catch (\Exception $e) {
    // do nothing
} finally {
    // Если данные успешно сохранены то ничего не делается иначе файл удаляется.
    $uploader->flush();
}
```

##### Обновление
```php
$id = // идентификатор данных.
$file = // Экземпляр \SplFileInfo;
$conn = $container->get('database_connection');
$uploader = $container->get('atom_uploader.uploader');

$statement = $conn->prepare('SELECT id, file FROM uploadable t WHERE t.id = :id');
$statement->bindValue('id', $id);
$statement->execute();

$oldUploadable = $statement->fetch();
$newUploadable = array_merge($oldUploadable, ['file' => $file]);

// Любое уникальное для этой транзакции значение.
$transactionId = uniqid();

// Перед обновлением новый файл(если есть) сохраняется.
$uploader->update($identity, $newUploadable, $oldUploadable, 'uploadable');

try {
    $conn->update('uploadable', ['file' => $newUploadable['file']], compact('id'));

    // После успешного обновления старый файл удаляется.
    $uploader->updated($identity);
} catch (\Exception $e) {
    // do nothing
} finally {
    // Если обновления прошло успешно то ничего не делается иначе новый файл удаляется.
    $uploader->flush();
}
```

##### Получение
```php
$id = // идентификатор данных.
$conn = $container->get('database_connection');
$uploader = $container->get('atom_uploader.uploader');

$statement = $conn->prepare('SELECT id, file FROM uploadable t WHERE t.id = :id');
$statement->bindValue('id', $id);
$statement->execute();

$uploadable = $statement->fetch();

// Внедряется URI и информация о файле(\SplFileInfo)
$uploader->loaded($uploadable, 'uploadable');
```

##### Удаление

```php
$id = // идентификатор данных.
$conn = $container->get('database_connection');
$uploader = $container->get('atom_uploader.uploader');

$statement = $conn->prepare('SELECT id, file FROM uploadable t WHERE t.id = :id');
$statement->bindValue('id', $id);
$statement->execute();

$uploadable = $statement->fetch();

$conn->delete('uploadable', ['id' => $this->getId()]);

// После удаления данных файл тоже удаляется.
$uploader->removed($uploadable, 'uploadable');
```

Автоматизация
---
Выше приведенные примеры лучше использовать в событиях хранилища данных(если такое есть), см [ORMListener].

Оптимизация
---

TODO

Связанные ссылки
---

- [Использование с встраиваемыми объектами doctrine][usage-with-doctrine-embeddables]
- [Использование с сущностями doctrine][usage-with-doctrine-entities]
- [Справка о всех параметрах][reference]
- [Неймеры][namers]
- [Адаптеры файловой системы][fs-adapters]
- [Событие][events]

[usage-with-doctrine-entities]: usage-with-doctrine-entities.md
[usage-with-doctrine-embeddables]: usage-with-doctrine-embeddables.md
[reference]: reference.md
[namers]: namers.md
[events]: events.md
[fs-adapters]: fs-adapters.md
[Uploader]: https://github.com/atom-azimov/uploader/blob/master/src/Handler/Uploader.php
[dbal]: http://www.doctrine-project.org/projects/dbal.html
[IMappingHelper]: ../../../Mapping/IMappingHelper.php
[ORMListener]: https://github.com/atom-azimov/uploader/blob/master/src/Listener/ORM/ORMListener.php
