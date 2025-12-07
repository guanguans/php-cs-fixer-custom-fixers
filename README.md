# php-cs-fixer-custom-fixers

> [!NOTE]
> todo. - todo。

[![tests](https://github.com/guanguans/php-cs-fixer-custom-fixers/actions/workflows/tests.yml/badge.svg)](https://github.com/guanguans/php-cs-fixer-custom-fixers/actions/workflows/tests.yml)
[![php-cs-fixer](https://github.com/guanguans/php-cs-fixer-custom-fixers/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/guanguans/php-cs-fixer-custom-fixers/actions/workflows/php-cs-fixer.yml)
[![codecov](https://codecov.io/gh/guanguans/php-cs-fixer-custom-fixers/graph/badge.svg?token=0RtgSGom4K)](https://codecov.io/gh/guanguans/php-cs-fixer-custom-fixers)
[![Latest Stable Version](https://poser.pugx.org/guanguans/php-cs-fixer-custom-fixers/v)](https://packagist.org/packages/guanguans/php-cs-fixer-custom-fixers)
[![GitHub release (with filter)](https://img.shields.io/github/v/release/guanguans/php-cs-fixer-custom-fixers)](https://github.com/guanguans/php-cs-fixer-custom-fixers/releases)
[![Total Downloads](https://poser.pugx.org/guanguans/php-cs-fixer-custom-fixers/downloads)](https://packagist.org/packages/guanguans/php-cs-fixer-custom-fixers)
[![License](https://poser.pugx.org/guanguans/php-cs-fixer-custom-fixers/license)](https://packagist.org/packages/guanguans/php-cs-fixer-custom-fixers)

## Requirement

* PHP >= 7.4

## Installation

```shell
composer require guanguans/php-cs-fixer-custom-fixers --dev --ansi -v
```

## Usage

> todo

## Fixers

<details>
<summary><b>details</b></summary>

<!-- ruledoc-start -->

#### XmlLintFixer
Format a [Xml Lint] file.
  *Risky: .*
Configuration options:
- `command` (`array`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['xmllint']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`array`): the file extensions to format; defaults to `['xml', 'xml.dist']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`
- `wrap_attrs_min_num` (`int`): wrap attributes to multiple lines when the number of attributes is greater than or equal to this value; defaults to `5`
```diff
 <?xml version="1.0" encoding="UTF-8"?>
-<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd" bootstrap="vendor/autoload.php" cacheDirectory=".build/phpunit/" colors="true">
-</phpunit>
\ No newline at end of file
+<phpunit
+  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
+  xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
+  bootstrap="vendor/autoload.php"
+  cacheDirectory=".build/phpunit/"
+  colors="true"
+>
+</phpunit>
```

#### DoctrineSqlFixer
Format a [Doctrine Sql] file.
  *Risky: .*
Configuration options:
- `extensions` (`array`): the file extensions to format; defaults to `['sql']`
- `indent_string` (`string`): the SQL string with HTML styles and formatting wrapped in a &lt;pre&gt; tag; defaults to `'    '`
```diff
-select
-    c.id, c.name, o.address,
+SELECT
+    c.id,
+    c.name,
+    o.address,
     o.orderedat
-from
+FROM
     customers c
-left join orders o on (o.customerid = c.id)
-order by
+    LEFT JOIN orders o ON (o.customerid = c.id)
+ORDER BY
     o.orderedat;
\ No newline at end of file
```

#### PhpMyAdminSqlFixer
Format a [Php My Admin Sql] file.
  *Risky: .*
Configuration options:
- `extensions` (`array`): the file extensions to format; defaults to `['sql']`
- `options` (`array`): the formatting options; defaults to `['type' => 'text']`
```diff
-select
-    c.id, c.name, o.address,
+SELECT
+    c.id,
+    c.name,
+    o.address,
     o.orderedat
-from
+FROM
     customers c
-left join orders o on (o.customerid = c.id)
-order by
+LEFT JOIN orders o ON
+    (o.customerid = c.id)
+ORDER BY
     o.orderedat;
\ No newline at end of file
```

<!-- ruledoc-end -->
</details>

## Composer scripts

```shell
composer benchmark
composer checks:required
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

* [guanguans](https://github.com/guanguans)
* [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
