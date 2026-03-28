# Laravel Seedster

A drop-in replacement for [eklundkristoffer/seedster](https://github.com/eklundkristoffer/seedster) with full Laravel 13 support.

Seedster provides an Artisan command to generate database seeders from existing database records, making it easy to capture the current state of your database as seed files.

#### Table of Contents
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [License](#license)

## Features

- Generate seeders from existing database records
- Support for all Laravel-supported database drivers
- Configurable table selection and filtering
- Drop-in replacement for eklundkristoffer/seedster
- Full compatibility with Laravel 13

## Installation

```bash
composer require jeremykenedy/laravel-seedster
```

The service provider is auto-discovered. No manual registration needed.

### Publish Configuration

```bash
php artisan vendor:publish --tag=seedster-config
```

## Usage

### Generate a Seeder

```bash
# Generate a seeder for a specific table
php artisan seedster:generate users

# Generate seeders for all tables
php artisan seedster:generate --all

# Specify output path
php artisan seedster:generate users --path=database/seeders
```

### Options

| Option | Description |
|--------|-------------|
| `--all` | Generate seeders for all tables |
| `--path` | Custom output path for generated seeders |
| `--chunk` | Number of records per insert chunk (default: 50) |
| `--force` | Overwrite existing seeder files |

## Configuration

After publishing the config file, you can customize settings in `config/seedster.php`:

- `path` - Default output path for generated seeders
- `chunk_size` - Default chunk size for insert statements
- `excluded_tables` - Tables to skip when using `--all`

## Testing

```bash
composer test
```

## License

The MIT License (MIT). See [LICENSE](LICENSE) for more information.

## Author

- [Jeremy Kenedy](https://github.com/jeremykenedy)
