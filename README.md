# Randflake ID Symfony Bundle Test Application

Symfony test application for my Randflake ID Symfony bundle.

This test application is solely intended for development and testing purposes of my [Randflake ID Symfony bundle](https://github.com/adambean/randflake-id-bundle).

This does not require a web server to run. As this project is highly backend data orientated, all of the functionality can be run from a command terminal.

## Requirements

- PHP† 8.1 or later
- Composer

† **This library requires a 64-bit build of PHP to function.** Check your `PHP_INT_SIZE`, which **must** be at least `8`:

```sh
php -r 'echo PHP_INT_SIZE;'
```

## Installation

This typically requires checking out at least two repositories, sometimes three:

1. Create a directory at the location of your choice, e.g. "php-randflake-id".
2. Clone this test application into directory "symfony-test-app" inside the directory created in step 1.
3. Clone the [Randflake ID Symfony bundle](https://github.com/adambean/randflake-id-bundle) into directory "symfony-bundle" inside the directory created in step 1.
4. (Optional) Clone the [Randflake ID library](https://github.com/adambean/randflake-id-php) into directory "lib" inside the directory created in step 1.
   - You only need to do this if you intend on testing changes to the PHP library itself (not the Symfony bundle) to test specifically in a Symfony environment.
5. Run `composer install` to install dependencies in all repositories.

Example quick command for step 5:

```sh
for REPO in lib symfony-bundle symfony-test-app ; do echo "${REPO}" ; $(which php8.1)$ $(which composer) -d "${REPO}" install ; done
```

This test application will then need its own installation steps:

1. Configure the environment file `.env.local`. (See below.)
2. Run `php bin/console doctrine:database:create` to create a database. (By default this is SQLite, but you can configure it to use any Doctrine supported database backend.)
3. Run `php bin/console doctrine:schema:update --force` to create the database schema.

If you are using [Visual Studio Code](https://code.visualstudio.com/) I would suggest creating a "php-randflake-id.code-workspace" file in the directory created from step 1 with the following contents:

```json
{
    "folders": [
        {
            "path": "lib"
        },
        {
            "path": "symfony-bundle"
        },
        {
            "path": "symfony-test-app"
        }
    ],
    "settings": {
        "cSpell.words": [
            "Randflake",
            "RandflakeID",
            "Sparx",
            "Symfony",
            "Doctrine",
            "Adambean",
            "phpstan"
        ],
        "sqltools.connections": [
            {
                "previewLimit": 50,
                "driver": "SQLite",
                "name": "DB",
                "database": "${workspaceFolder:symfony-test-app}/var/data_dev.db"
            }
        ]
    }
}
```

(Optionally change the [sqltools.connections](https://marketplace.visualstudio.com/items?itemName=mtxr.sqltools) configuration to point to another database backend.)

## Configuration

Create a `.env.local` file in the root of the project to override any default configuration values. The following values are available for configuration:

```dotenv
# Database connection URL. (Default: SQLite)
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"

# Randflake ID configuration values
RANDFLAKE_ID_NODE_ID=0
RANDFLAKE_ID_SECRET=ThisIsNotSecret!
RANDFLAKE_ID_ENCRYPTED=1
RANDFLAKE_ID_ENCODED=1
RANDFLAKE_ID_LEASE_START=
RANDFLAKE_ID_LEASE_END=
```

Changing the `DATABASE_URL` is only necessary to test on non-SQLite database backends.

See the [Randflake ID Symfony bundle README](https://github.com/Adambean/randflake-id-bundle#options) for more information on the Randflake ID specific configuration options. You should at the very least generate your own secret.

## Details

### Example entity class

An entity class ``ExampleRandflakeIdEntity`` is provided as an example use case of Randflake IDs in a Doctrine entity, which has the following properties:

- `id` - Primary key, stored without encryption and encoding, but will honour the `RANDFLAKE_ID_ENCRYPTED` and `RANDFLAKE_ID_ENCODED` configuration options when being read from and written to by the application layer.
- `name` - Arbitrary string, not important. The data fixture will set this to "Example entity %d" with `sprintf()` just for visual indication as to which entity is being worked with by the fixture's iteration index.
- `exampleDefault` - Follows the service configuration with regards to encryption and encoding behaviour.
- `exampleExplicitDd` - Overrides the service configuration: always behaves as decrypted and decoded.
- `exampleExplicitDe` - Overrides the service configuration: always behaves as decrypted and encoded.
- `exampleExplicitEd` - Overrides the service configuration: always behaves as encrypted and decoded.
- `exampleExplicitEe` - Overrides the service configuration: always behaves as encrypted and encoded.

### Example entity repository class

An entity repository class `ExampleRandflakeIdRepository` extending the bundle's `RandflakeIdServiceEntityRepository` class is provided to demonstrate how to use the Randflake ID service to search for entities by their Randflake ID. It has the following methods:

- `findByRandflakeId(string $property, string $value): ?ExampleRandflakeIdEntity`
  - Searches for an entity by a Randflake ID, which can be either the raw Randflake ID or the encoded Randflake ID.
  - This is used by the `app:example-entity:search` command below only when not querying the "id" primary key column, e.g. "exampleDefault".
  - This importantly applies a `setTypedParameter()` call to ensure that the column is treated with the correct encryption and encoding behaviour as per the service configuration, or if it has explicit options overriding the service configuration. (Any custom repository function you implement will need to do this.)
- `findOneByExampleExplicitProperties(string $dd, string $de, string $ed, string $ee): ?ExampleRandflakeIdEntity`
  - Searches for an entity by all four explicit properties on the entity, which will override the service configuration to ensure the correct behaviour is being applied.
  - This function exists solely for the `app:example-entity:explicit` command below.

## Usage

### Creating data

Doctrine data fixtures can be used to simulate data creation as this tests both configuration implicit and configuration overriding explicit behaviours on encryption and encoding.

A data fixture `AppFixtures` is provided, which can be installed into your database with the following command:

```sh
php bin/console doctrine:fixtures:load -v
```

This will create a batch of `ExampleRandflakeIdEntity` entity instances in your database, currently 10. You will be shown

### Reading data

Two commands are provided to simulate reading and searching Randflake IDs from the database.

#### Search for one by ID

This represents the most common use case of searching for a single Randflake ID, which can be either the raw Randflake ID or the encoded Randflake ID. It will make use of the service configuration to determine if the provided ID should be decoded and/or decrypted before performing a real database query.

`php bin/console app:example-entity:search <id> <property?>`

- `id` - The Randflake ID to search for. This can be either the raw Randflake ID, or the encoded Randflake ID.
- `property` (Optional) - The property to search on. If not provided, the default property "id" will be used.

If an entity is found with the provided Randflake ID in the specified property, then the test is successful, and will output the entity's primary key ID, its name string, and the five specific example ID columns.

#### Search for one by explicit columns

This is a more focused test case by explicitly searching four columns in all four explicit formats of encryption and encoding from `ExampleRandflakeIdEntity`, which will override the service configuration to ensure the correct behaviour is being applied.

`php bin/console app:example-entity:explicit <dd> <de> <ed> <ee>`

All four arguments must be provided:

- `dd` - The Randflake ID without encryption and encoding.
- `de` - The Randflake ID without encryption but with encoding.
- `ed` - The Randflake ID with encryption but not encoded.
- `ee` - The Randflake ID with encryption and encoding.

If an entity is found with all four columns matching, then the test is successful, and will output the entity's primary key ID and its name string.

`php app:example-entity:explicit`

## Development & contributing

Ensure any contribution is compatible with the earliest supported PHP version.

Clean up code style to the accepted standards:

```sh
composer cs:fix
```

Ensure static analysis checks pass:

```sh
composer stan
```

Ensure all tests pass:

```sh
composer test
```

## License

MIT License. See [LICENSE](LICENSE) for details.

## Credits

- PHP implementation: [Adam "Adambean" Reece](https://github.com/adambean)
