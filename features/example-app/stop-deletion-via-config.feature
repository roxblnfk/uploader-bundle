@config @stop-action @remove-file
Feature: Stop deletion via config.

  Scenario Outline: Stop deletion via config.
    Given I use following config:
    """
    atom_uploader:
        mappings:
            Atom\Uploader\Model\Embeddable\FileReference:
                delete_on_remove: false
            ExampleApp\Entity\UploadableEntity:
                delete_on_remove: false
            dbal_uploadable:
                delete_on_remove: false
    """
    And I have selected driver <driver>
    And I have got an uploaded file named "{tmp}/some-file"
    When I delete the object with id "{last uploaded object id}"
    Then amount of files in upload path is 1

    Examples:
      | driver         |
      | dbal           |
      | orm            |
      | orm_embeddable |