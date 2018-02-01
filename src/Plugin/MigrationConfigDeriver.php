<?php

namespace Drupal\dp_docs\Plugin;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Cache\Cache;
use Drupal\dp_docs\Entity\APIRef;
use Drupal\file\Entity\File;
use Drupal\file\Plugin\Field\FieldType\FileFieldItemList;
use JsonSchema\Validator;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class MigrationConfigDeriver extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $migrations = [];

    foreach (APIRef::loadMultiple() as $ref) {
      /** @var APIRef $ref */
      $path = static::getSourcePath($ref);
      if ($path === NULL) {
        continue;
      }

      $migrations += static::createSwaggerMigrations($ref->migrationID(), $ref->id(), $path, $base_plugin_definition);
    }

    return $migrations;
  }

  /**
   * Returns the referneced source file for an APIDocsEntity.
   *
   * @param APIRef $ref
   *
   * @return null|string
   */
  protected static function getSourcePath(APIRef $ref) {
    /** @var FileFieldItemList $source */
    $source = $ref->get('source');
    /** @var File[] $referenced */
    $referenced = $source->referencedEntities();
    if (count($referenced) === 0) {
      return NULL;
    }
    $referenced_file = reset($referenced);
    return $referenced_file->getFileUri();
  }

  /**
   * Extracs the version from an API Reference.
   *
   * @param APIRef $ref
   *
   * @return string|null
   *
   * @throws \Exception
   * @throws \Drupal\dp_docs\Plugin\Swagger20ValidationException
   */
  public static function getVersionFromAPIRef(APIRef $ref) {
    $type = $ref->getType();
    $path = static::getSourcePath($ref);
    return static::getVersion($type, $path);
  }

  /**
   * Extracts the version from a source file.
   *
   * @param string $type
   *   Source file type.
   * @param string $path
   *   Source file path.
   *
   * @return string|null
   *
   * @throws \Exception
   * @throws \Drupal\dp_docs\Plugin\Swagger20ValidationException
   */
  public static function getVersion($type, $path) {
    if (!$path) {
      return NULL;
    }

    switch ($type) {
      case 'swagger_2_0':
        return static::parseSwagger($path)['info']['version'];
    }

    return NULL;
  }

  /**
   * Parses and validates a Swagger file.
   *
   * @param string $file_path
   *   The path of the Swagger file.
   *
   * @return array
   *   An associative array of the parsed Swagger file.
   *
   * @throws \Exception
   * @throws \Drupal\dp_docs\Plugin\Swagger20ValidationException
   */
  public static function parseSwagger($file_path) {
    $bin = \Drupal::cache('apifiles');
    $cid = $file_path . ':' . md5_file($file_path);
    $cached = $bin->get($cid);
    if ($cached) {
      return $cached->data;
    }

    $file_info = pathinfo($file_path);
    $file_ext = $file_info['extension'];

    if (($file_ext === 'yaml') || ($file_ext === 'yml')) {
      try {
        // Parse the Swagger definition but DO NOT convert it to an array yet!
        $swagger = Yaml::parse(file_get_contents($file_path), Yaml::PARSE_OBJECT_FOR_MAP);
      }
      catch (ParseException $e) {
        throw new \Exception("Can not parse YAML source file ({$file_path}).");
      }
    }
    else if ($file_ext === 'json') {
      // Parse the Swagger definition but DO NOT convert it to an array yet!
      $swagger = json_decode(file_get_contents($file_path));
      if ($swagger === NULL) {
        throw new \Exception("The JSON source file ({$file_path}) cannot be decoded or the encoded data is deeper then the recursion limit (512).");
      }
    }
    else {
      throw new \Exception("Unsupported source file extension: $file_ext. Please use YAML or JSON source.");
    }

    static::validateSwagger($swagger);

    // Now that the validation is done we can convert the Swagger object into a
    // manageable associative array.
    $swagger = json_decode(json_encode($swagger), TRUE);

    $bin->set($cid, $swagger, Cache::PERMANENT);

    return $swagger;
  }

  /**
   * Validates a Swagger 2.0 document.
   *
   * @param object $swagger
   *   The Swagger object to validate.
   *
   * @throws Swagger20ValidationException
   */
  public static function validateSwagger($swagger) {
    $validator = new Validator();
    $validator->validate($swagger, (object)[
      '$ref' => 'file://' . ($_SERVER['DOCUMENT_ROOT'] ?: getcwd()) . '/' . drupal_get_path('module', 'dp_docs') . '/data/swagger20-schema.json',
    ]);
    if (!$validator->isValid()) {
      $errors = $validator->getErrors();
      throw Swagger20ValidationException::fromErrors($errors);
    }
  }

  /**
   * Gets the table name suffix for a content type.
   *
   * @param string $type
   *
   * @return string|null
   */
  public static function tableSuffixForBundle($type) {
    static $map = [
      'swagger_2_0' => 'swagger_20',
    ];
    return isset($map[$type]) ? $map[$type] : NULL;
  }

  /**
   * Generates swagger migrations for a given swagger file.
   *
   * @param string $name
   *   Machine name prefix of the migrations.
   * @param integer $api_ref_id
   *   The unique ID of the API Reference entity.
   * @param string $source_file_path
   *   Path of the source file.
   * @param array $base_plugin_definition
   *
   * @return array
   */
  public static function createSwaggerMigrations($name, $api_ref_id, $source_file_path, $base_plugin_definition) {
    $base_id = $base_plugin_definition['id'];
    $group = "dp_docs_{$name}";
    $prefix = $name;

    $unprocessed_migrations = [
      static::apiParamItem($prefix, $group, $base_id, $api_ref_id),
      static::apiMimeTypeTaxonomyTerm($prefix, $group, $base_id, $api_ref_id),
      static::apiExtDoc($prefix, $group, $base_id, $api_ref_id),
      static::apiTag($prefix, $group, $base_id, $api_ref_id),
      static::apiVersionTag($prefix, $group, $base_id, $api_ref_id),
      static::apiInfo($prefix, $group, $base_id, $api_ref_id),
      static::apiContact($prefix, $group, $base_id, $api_ref_id),
      static::apiLicense($prefix, $group, $base_id, $api_ref_id),
      static::apiDoc($prefix, $group, $base_id, $api_ref_id),
      static::apiPathParam($prefix, $group, $base_id, $api_ref_id),
      static::apiGlobalSchema($prefix, $group, $base_id, $api_ref_id),
      static::apiSchema($prefix, $group, $base_id, $api_ref_id),
      static::apiBodyParam($prefix, $group, $base_id, $api_ref_id),
      static::apiQueryParam($prefix, $group, $base_id, $api_ref_id),
      static::apiHeaderParam($prefix, $group, $base_id, $api_ref_id),
      static::apiFormDataParam($prefix, $group, $base_id, $api_ref_id),
      static::apiMetaParam($prefix, $group, $base_id, $api_ref_id),
      static::apiGlobalParam($prefix, $group, $base_id, $api_ref_id),
      static::apiParam($prefix, $group, $base_id, $api_ref_id),
      static::apiEndpoint($prefix, $group, $base_id, $api_ref_id),
      static::apiEndpointSet($prefix, $group, $base_id, $api_ref_id),
      static::apiResponse($prefix, $group, $base_id, $api_ref_id),
      static::apiResponseHeader($prefix, $group, $base_id, $api_ref_id),
      static::apiResponseSet($prefix, $group, $base_id, $api_ref_id),
      static::apiGlobalResponse($prefix, $group, $base_id, $api_ref_id),
      static::apiMethod($prefix, $group, $base_id, $api_ref_id),
      static::apiResponseExample($prefix, $group, $base_id, $api_ref_id),
    ];

    $migrations = [];
    foreach ($unprocessed_migrations as $migration) {
      $migration['source']['source_file'] = $source_file_path;
      $migration['source']['api_ref_id'] = $api_ref_id;
      $migrations[$migration['id']] = $migration;
    }

    return $migrations;
  }

  protected static function apiParamItem($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_param_item_swagger_20",
      "label" => "Swagger 2.0 Items Objects to APIParamItem entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_param_item_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_param_item",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "param_type" => "type",
        "format" => "format",
        "collection_format" => "collection_format",
        "param_default" => "default",
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_param_item" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_param_item_swagger_20",
          "source" => "items",
        ],
        "extensions" => "extensions",
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiMimeTypeTaxonomyTerm($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_mime_type_taxonomy_term_swagger_20",
      "label" => "Swagger 2.0 mime types to taxonomy terms in API MIME Type vocabulary",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_mime_type_swagger_20",
      ],
      "destination" => [
        "plugin" => "entity:taxonomy_term",
        "default_bundle" => "api_mime_type",
      ],
      "process" => [
        "name" => "mime_type",
      ],
    ];
  }

  protected static function apiExtDoc($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_ext_doc_swagger_20",
      "label" => "Swagger 2.0 External Documentation Objects to APIExtDoc entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_ext_doc_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_ext_doc",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "url" => "url",
        "description/format" => [
          "plugin" => "default_value",
          "default_value" => "github_flavored_markdown",
        ],
        "description/value" => "description",
        "extensions" => "extensions",
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiTag($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_tag_swagger_20",
      "label" => "Swagger 2.0 Tag to APITag entity",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_tag_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_tag",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "description/format" => [
          "plugin" => "default_value",
          "default_value" => "github_flavored_markdown",
        ],
        "description/value" => "description",
        "extensions" => "extensions",
        "api_doc" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_doc_swagger_20",
          "source" => "api_ref_id",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_ext_doc" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_ext_doc_swagger_20",
          "source" => "ext_doc",
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_doc_swagger_20",
          "{$base_id}:{$prefix}_api_ext_doc_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiVersionTag($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_version_tag_swagger_20",
      "label" => "API Documentation versions to APIVersionTag entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_version_tag_swagger_20",
      ],
      "destination" => [
        "plugin" => "entity:api_version_tag",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
    ];
  }

  protected static function apiInfo($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_info_swagger_20",
      "label" => "Swagger 2.0 Info Object to APIInfo entity",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_info_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_info",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "title" => "title",
        "description/format" => [
          "plugin" => "default_value",
          "default_value" => "github_flavored_markdown",
        ],
        "description/value" => "description",
        "terms_of_service" => "terms_of_service",
        "api_contact" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_contact_swagger_20",
          "source" => "contact",
        ],
        "api_license" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_license_swagger_20",
          "source" => "license",
        ],
        "version" => "version",
        "extensions" => "extensions",
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_contact_swagger_20",
          "{$base_id}:{$prefix}_api_license_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiContact($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_contact_swagger_20",
      "label" => "Swagger 2.0 Contact Object to APIContact entity",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_contact_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_contact",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "url" => "url",
        "mail" => "email",
        "extensions" => "extensions",
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiLicense($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_license_swagger_20",
      "label" => "Swagger 2.0 License Object to APILicense entity",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_license_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_license",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "url" => "url",
        "extensions" => "extensions",
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiDoc($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_doc_swagger_20",
      "label" => "Swagger 2.0 Object to APIDoc entity",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_doc_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_doc",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "host" => "host",
        "base_path" => "base_path",
        "protocol" => "protocol",
        "consumes" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_mime_type_taxonomy_term_swagger_20",
          "source" => "consumes",
        ],
        "produces" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_mime_type_taxonomy_term_swagger_20",
          "source" => "produces",
        ],
        "source_file" => [
          [
            "plugin" => "getcontents",
            "source" => [
              "source_file",
              "destination_file",
            ],
            "rename" => TRUE,
          ],
          [
            "plugin" => "entity_generate",
          ],
        ],
        "extensions" => "extensions",
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_ext_doc" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_ext_doc_swagger_20",
          "source" => "ext_doc",
        ],
        "api_info" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_info_swagger_20",
          "source" => "api_ref_id",
        ],
        "uid" => [
          "plugin" => "default_value",
          "default_value" => 1,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
        "status" => [
          "plugin" => "default_value",
          // TODO: Fix this when the revisioning support is complete.
          "default_value" => 1,
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_mime_type_taxonomy_term_swagger_20",
          "{$base_id}:{$prefix}_api_ext_doc_swagger_20",
          "{$base_id}:{$prefix}_api_info_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiPathParam($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_path_param_swagger_20",
      "label" => "Swagger 2.0 \"Path\" type Parameter Objects to APIPathParam entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_path_param_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_path_param",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "description/format" => [
          "plugin" => "default_value",
          "default_value" => "github_flavored_markdown",
        ],
        "description/value" => "description",
        "required" => "required",
        "param_type" => "type",
        "format" => "format",
        "collection_format" => "collection_format",
        "param_default" => "default",
        "api_param_item" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_param_item_swagger_20",
          "source" => "items",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_param_item_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiGlobalSchema($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_global_schema_swagger_20",
      "label" => "Swagger 2.0 Schema Objects to APIGlobalSchema entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_global_schema_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_global_schema",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "value" => "value",
        "api_ext_doc" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_ext_doc_swagger_20",
          "source" => "ext_doc",
        ],
        "api_doc" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_doc_swagger_20",
          "source" => "api_ref_id",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_ext_doc_swagger_20",
          "{$base_id}:{$prefix}_api_doc_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiSchema($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_schema_swagger_20",
      "label" => "Swagger 2.0 Schema Objects to APISchema entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_schema_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_schema",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "schema_id" => "source_key",
        "inline_schema_def" => "inline_schema",
        "api_global_schema" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_global_schema_swagger_20",
          "source" => "global_schema",
        ],
        "api_ext_doc" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_ext_doc_swagger_20",
          "source" => "ext_doc",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_global_schema_swagger_20",
          "{$base_id}:{$prefix}_api_ext_doc_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiBodyParam($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_body_param_swagger_20",
      "label" => "Swagger 2.0 \"Body\" type Parameter Objects to APIBodyParam entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_body_param_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_body_param",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "description/format" => [
          "plugin" => "default_value",
          "default_value" => "github_flavored_markdown",
        ],
        "description/value" => "description",
        "required" => "required",
        "api_schema" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_schema_swagger_20",
          "source" => "schema",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_schema_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiQueryParam($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_query_param_swagger_20",
      "label" => "Swagger 2.0 \"Query\" type Parameter Objects to APIQueryParam entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_query_param_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_query_param",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "description/format" => [
          "plugin" => "default_value",
          "default_value" => "github_flavored_markdown",
        ],
        "description/value" => "description",
        "required" => "required",
        "allow_empty_value" => "allow_empty_value",
        "param_type" => "type",
        "format" => "format",
        "collection_format" => "collection_format",
        "param_default" => "default",
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_param_item" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_param_item_swagger_20",
          "source" => "items",
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_param_item_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiHeaderParam($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_header_param_swagger_20",
      "label" => "Swagger 2.0 \"Header\" type Parameter Objects to APIHeaderParam entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_header_param_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_header_param",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "description/format" => [
          "plugin" => "default_value",
          "default_value" => "github_flavored_markdown",
        ],
        "description/value" => "description",
        "required" => "required",
        "param_type" => "type",
        "format" => "format",
        "collection_format" => "collection_format",
        "param_default" => "default",
        "api_param_item" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_param_item_swagger_20",
          "source" => "items",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_param_item_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiFormDataParam($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_form_data_param_swagger_20",
      "label" => "Swagger 2.0 \"Form\" type Parameter Objects to APIFormDataParam entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_form_data_param_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_form_data_param",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "description/format" => [
          "plugin" => "default_value",
          "default_value" => "github_flavored_markdown",
        ],
        "description/value" => "description",
        "required" => "required",
        "allow_empty_value" => "allow_empty_value",
        "param_type" => "type",
        "format" => "format",
        "collection_format" => "collection_format",
        "param_default" => "default",
        "api_param_item" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_param_item_swagger_20",
          "source" => "items",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_param_item_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiMetaParam($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_meta_param_swagger_20",
      "label" => "Swagger 2.0 Parameter Objects to APIMetaParam entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_meta_param_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_meta_param",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "param_in" => "in",
        "api_path_param" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_path_param_swagger_20",
          "source" => "path_param",
        ],
        "api_body_param" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_body_param_swagger_20",
          "source" => "body_param",
        ],
        "api_query_param" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_query_param_swagger_20",
          "source" => "query_param",
        ],
        "api_header_param" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_header_param_swagger_20",
          "source" => "header_param",
        ],
        "api_form_data_param" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_form_data_param_swagger_20",
          "source" => "form_data_param",
        ],
        "extensions" => "extensions",
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_path_param_swagger_20",
          "{$base_id}:{$prefix}_api_body_param_swagger_20",
          "{$base_id}:{$prefix}_api_query_param_swagger_20",
          "{$base_id}:{$prefix}_api_header_param_swagger_20",
          "{$base_id}:{$prefix}_api_form_data_param_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiGlobalParam($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_global_param_swagger_20",
      "label" => "Swagger 2.0 Parameter Objects to APIGlobalParam entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_global_param_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_global_param",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "api_meta_param" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_meta_param_swagger_20",
          "source" => "meta_param",
        ],
        "api_doc" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_doc_swagger_20",
          "source" => "api_ref_id",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_meta_param_swagger_20",
          "{$base_id}:{$prefix}_api_doc_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiParam($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_param_swagger_20",
      "label" => "Swagger 2.0 Parameter and Reference Objects to APIParam entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_param_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_param",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "api_global_param" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_global_param_swagger_20",
          "source" => "global_param",
        ],
        "api_meta_param" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_meta_param_swagger_20",
          "source" => "meta_param",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_global_param_swagger_20",
          "{$base_id}:{$prefix}_api_meta_param_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiEndpoint($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_endpoint_swagger_20",
      "label" => "Swagger 2.0 Paths Object entries to APIEndpoint entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_endpoint_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_endpoint",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "uri" => "uri",
        "extensions" => "extensions",
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_param" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_param_swagger_20",
          "source" => "params",
        ],
        "api_endpoint_set" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_endpoint_set_swagger_20",
          "source" => "api_ref_id",
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_param_swagger_20",
          "{$base_id}:{$prefix}_api_endpoint_set_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiEndpointSet($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_endpoint_set_swagger_20",
      "label" => "Swagger 2.0 Paths Objects to APIEndpointSet entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_endpoint_set_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_endpoint_set",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "extensions" => "extensions",
        "api_doc" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_doc_swagger_20",
          "source" => "api_ref_id",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_doc_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiResponse($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_response_swagger_20",
      "label" => "Swagger 2.0 Response and Reference Objects to APIResponse entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_response_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_response",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "code" => "code",
        "description/format" => [
          "plugin" => "default_value",
          "default_value" => "github_flavored_markdown",
        ],
        "description/value" => "description",
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_schema" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_schema_swagger_20",
          "source" => "api_schema",
        ],
        "api_response_set" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_response_set_swagger_20",
          "source" => "api_response_set",
        ],
        "api_global_response" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_global_response_swagger_20",
          "source" => "api_global_response",
        ],
        "extensions" => "extensions",
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_schema_swagger_20",
          "{$base_id}:{$prefix}_api_response_set_swagger_20",
          "{$base_id}:{$prefix}_api_global_response_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiResponseHeader($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_response_header_swagger_20",
      "label" => "Swagger 2.0 Header Objects to APIResponseHeader entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_response_header_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_response_header",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "param_type" => "type",
        "name" => "name",
        "description" => "description",
        "format" => "format",
        "collection_format" => "collection_format",
        "param_default" => "default",
        "api_param_item" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_param_item_swagger_20",
          "source" => "items",
        ],
        "api_response" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_response_swagger_20",
          "source" => "response",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "extensions" => "extensions",
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_param_item_swagger_20",
          "{$base_id}:{$prefix}_api_response_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiResponseSet($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_response_set_swagger_20",
      "label" => "Swagger 2.0 Responses Objects to APIResponseSet entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_response_set_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_response_set",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "api_method" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_method_swagger_20",
          "source" => "api_method",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "extensions" => "extensions",
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_method_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiGlobalResponse($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_global_response_swagger_20",
      "label" => "Swagger 2.0 Response Objects to APIGlobalResponse entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_global_response_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_global_response",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "name" => "name",
        "description" => "description",
        "extensions" => "extensions",
        "api_schema" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_schema_swagger_20",
          "source" => "api_schema",
        ],
        "api_doc" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_doc_swagger_20",
          "source" => "api_ref_id",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_schema_swagger_20",
          "{$base_id}:{$prefix}_api_doc_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiMethod($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_method_swagger_20",
      "label" => "Swagger 2.0 Operation Objects to APIMethod entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_method_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_method",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "http_method" => "http_method",
        "summary" => "summary",
        "description/format" => [
          "plugin" => "default_value",
          "default_value" => "github_flavored_markdown",
        ],
        "description/value" => "description",
        "op_id" => "operation_id",
        "deprecated" => "deprecated",
        "consumes" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_mime_type_taxonomy_term_swagger_20",
          "source" => "consumes",
        ],
        "produces" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_mime_type_taxonomy_term_swagger_20",
          "source" => "produces",
        ],
        "api_ext_doc" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_ext_doc_swagger_20",
          "source" => "ext_doc",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "api_endpoint" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_endpoint_swagger_20",
          "source" => "endpoint",
        ],
        "api_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_tag_swagger_20",
          "source" => "tags",
        ],
        "api_param" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_param_swagger_20",
          "source" => "params",
        ],
        "extensions" => "extensions",
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_mime_type_taxonomy_term_swagger_20",
          "{$base_id}:{$prefix}_api_ext_doc_swagger_20",
          "{$base_id}:{$prefix}_api_endpoint_swagger_20",
          "{$base_id}:{$prefix}_api_tag_swagger_20",
          "{$base_id}:{$prefix}_api_param_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

  protected static function apiResponseExample($prefix, $group, $base_id, $api_ref_id) {
    return [
      "id" => "{$prefix}_api_response_example_swagger_20",
      "label" => "Swagger 2.0 Example Objects to APIResponseExample entities",
      "migration_tags" => [
        "Swagger",
      ],
      "migration_group" => $group,
      "source" => [
        "plugin" => "dp_api_response_example_swagger_20",
      ],
      "destination" => [
        "plugin" => "dp_docs_entity:api_response_example",
      ],
      "process" => [
        "type" => [
          "plugin" => "default_value",
          "default_value" => "swagger_2_0",
        ],
        "example" => "example",
        "api_response" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_response_swagger_20",
          "source" => "response",
        ],
        "api_ref" => [
          "plugin" => "default_value",
          "default_value" => $api_ref_id,
        ],
        "produces" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_mime_type_taxonomy_term_swagger_20",
          "source" => "produces",
        ],
        "api_version_tag" => [
          "plugin" => "migration_lookup",
          "migration" => "{$base_id}:{$prefix}_api_version_tag_swagger_20",
          "source" => "api_version",
        ],
        "langcode" => [
          "plugin" => "default_value",
          "source" => "language",
          "default_value" => "en",
        ],
      ],
      "migration_dependencies" => [
        "required" => [
          "{$base_id}:{$prefix}_api_response_swagger_20",
          "{$base_id}:{$prefix}_api_mime_type_taxonomy_term_swagger_20",
          "{$base_id}:{$prefix}_api_version_tag_swagger_20",
        ],
      ],
    ];
  }

}
