@config @stop-action @inject-uri @inject-file-info @inject
Feature: Stop action on inject an uri and file info.

  Scenario Outline: Stop action on inject an uri and file info.
    Given I use following config:
    """
    atom_uploader:
        mappings:
            Atom\Uploader\Model\Embeddable\FileReference:
                inject_uri_on_load: false
                inject_file_info_on_load: false
            ExampleApp\Entity\UploadableEntity:
                inject_uri_on_load: false
                inject_file_info_on_load: false
            dbal_uploadable:
                inject_uri_on_load: false
                inject_file_info_on_load: false
    """
    And I have selected driver <driver>
    And I have got an uploaded file named "{tmp}/some-file"
    When I get an object with id "{last uploaded object id}"
    Then I should see uri null
    And I should see file info null

    Examples:
      | driver         |
      | dbal           |
      | orm            |
      | orm_embeddable |