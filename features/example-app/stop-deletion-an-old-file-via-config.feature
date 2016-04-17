@config @stop-action @remove-old-file
Feature: Stop action on remove an old file.

  Scenario Outline: Stop action on remove an old file.
    Given I use following config:
    """
    atom_uploader:
        mappings:
            Atom\Uploader\Model\Embeddable\FileReference:
                delete_old_file: false
            ExampleApp\Entity\UploadableEntity:
                delete_old_file: false
            dbal_uploadable:
                delete_old_file: false
    """
    And I have selected driver <driver>
    And I have got an uploaded file named "{tmp}/some-file"
    And I have a file named "{tmp}/another-file"
    When I update object with id "{last uploaded object id}" to replace the file to the new file "{tmp}/another-file"
    Then The file "{upload path}/{last uploaded filename}" is exist

    Examples:
      | driver         |
      | dbal           |
      | orm            |
      | orm_embeddable |