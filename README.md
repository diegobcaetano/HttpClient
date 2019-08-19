# HttpClient

### Description
This package provides a simple way to make http(s) request.
It supports mocks.

### Installation

Run the following command on the terminal:
```
composer config repositories.httpClient git git@github.com:madeiramadeirabr/HttpClient.git
```
and
```
composer require madeiramadeirabr/http-client
```

## Usage

This package works based on environment settings. Below is a reference table:

### General Settings
| Environment Key | Description | Values |
| --- | --- | --- |
| `LOG_ENABLED` | Enable or disable log feature | 1 or 0 |
| `LOG_DRIVER` | Driver to publish the logs.  | 'filesystem' or 'elastic-search' |
| `LOG_CHANNEL` | Channel on Elastic or filesystem sub directory |  |
| `LOG_TYPE` | Log Type | (string). Example: SHOP_API |

#### Disable log level

By default all levels are enabled.

| Environment Key | Description | Values |
| --- | --- | --- |
| `LOG_DISABLE_LEVEL_EMERGENCY` | Disable emergency level | 0 or 1 (default 0) |
| `LOG_DISABLE_LEVEL_ALERT` | Disable alert level | 0 or 1 (default 0) |
| `LOG_DISABLE_LEVEL_CRITICAL` | Disable critical level | 0 or 1 (default 0) |
| `LOG_DISABLE_LEVEL_ERROR` | Disable error level | 0 or 1 (default 0) |
| `LOG_DISABLE_LEVEL_WARNING` | Disable warning level | 0 or 1 (default 0) |
| `LOG_DISABLE_LEVEL_NOTICE` | Disable notice level | 0 or 1 (default 0) |
| `LOG_DISABLE_LEVEL_INFO` | Disable info level | 0 or 1 (default 0) |
| `LOG_DISABLE_LEVEL_DEBUG` | Disable debug level | 0 or 1 (default 0) |

### Filesystem 

By default, when the chosen driver is filesystem, all files are saved in `/tmp/log`
It is possible to set a different location, it is important that the directory has write permission.

#### Settings
| Environment Key | Description | Values |
| --- | --- | --- |
| `LOG_FILESYSTEM_DIRECTORY` | Filesystem local directory | (default) `/tmp/log` |

### Elastic Search

#### Settings
| Environment Key | Description | Values |
| --- | --- | --- |
| `LOG_AWS_ACCESS_KEY_ID` | AWS Access Key ID | Required if elastic-search driver is enabled. |
| `LOG_AWS_SECRET_ACCESS_KEY` | AWS Secret access key | Required if elastic-search driver is enabled. |
| `LOG_AWS_REGION` | AWS Region | Required if elastic-search driver is enabled. |
| `LOG_ELASTIC_SEARCH_HOST` | ES host | Required if elastic-search driver is enabled. |
| `LOG_ELASTIC_SEARCH_PORT` | ES port | Required if elastic-search driver is enabled. |

By default, logs will always be encapsulated in a json with the key "content". If you need to index a field that is within the log content, you can use the following setting:

| Environment Key | Description | Values |
| --- | --- | --- |
| `LOG_ELASTIC_SEARCH_CONTEXT_FIELDS_TO_INDEX` | Fields to index. | Fields can be separated by the pipe character. You can traverse an associative array using the "." Selector. |

Example:
```
$logger = Factory::getInstance();

$logger->critical('test', [
    "testKey" => "test",
        "testObject" => [
            "testChildObject" => [
                 "property" => "test2"
            ]
        ],
    "testNoIndex" => "testNoIndex"
]);

$returns = $logger->processLogs();
```
`LOG_ELASTIC_SEARCH_CONTEXT_FIELDS_TO_INDEX=testKey|testObject.testChildObject.property`



