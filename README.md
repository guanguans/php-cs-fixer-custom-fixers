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

<!-- fixerdoc-start -->
<details>
<summary><b>AutocorrectFixer</b></summary>

Format `md` files using `autocorrect`.

*Risky: affected by `autocorrect`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['autocorrect']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['md', 'markdown', 'txt', 'text']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
-hello世界！
\ No newline at end of file
+hello 世界！
\ No newline at end of file
```
</details>

<details>
<summary><b>BladeFormatterFixer</b></summary>

Format `blade.php` files using `blade-formatter`.

*Risky: affected by `blade-formatter`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['blade-formatter']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['blade.php']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
 <!DOCTYPE html>
 <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
+
 <body
-class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
-@if (Route::has('login'))
-<div class="h-14.5 hidden lg:block"></div>
-@endif
+    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
+    @if (Route::has('login'))
+        <div class="h-14.5 hidden lg:block"></div>
+    @endif
 </body>
-</html>
\ No newline at end of file
+
+</html>
```
</details>

<details>
<summary><b>DockerFmtFixer</b></summary>

Format `Dockerfile` files using `dockerfmt`.

*Risky: affected by `dockerfmt`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['dockerfmt']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['Dockerfile']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
 RUN chmod +x /PrairieLearn/scripts/init.sh \
-&& mkdir /course{,{2..9}} \
-&& mkdir -p /jobs \
\ No newline at end of file
+    && mkdir /course{,{2..9}} \
+    && mkdir -p /jobs \
```
</details>

<details>
<summary><b>DotenvLinterFixer</b></summary>

Format `env` files using `dotenv-linter`.

*Risky: affected by `dotenv-linter`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['dotenv-linter', 'fix']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['env', 'env.example']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
 APP_DEBUG=true
+APP_ENV=local
 APP_KEY=
-APP_ENV=local
 
-
 DB_URL=http://localhost
-
```
</details>

<details>
<summary><b>LintMdFixer</b></summary>

Format `md` files using `lint-md`.

*Risky: affected by `lint-md`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['lint-md']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['md', 'markdown']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
-# hello世界
\ No newline at end of file
+# hello 世界
\ No newline at end of file
```
</details>

<details>
<summary><b>MarkdownLintCli2Fixer</b></summary>

Format `md` files using `markdownlint-cli2`.

*Risky: affected by `markdownlint-cli2`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['markdownlint-cli2']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['md', 'markdown']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
-# hello世界
\ No newline at end of file
+# hello世界
```
</details>

<details>
<summary><b>MarkdownLintFixer</b></summary>

Format `md` files using `markdownlint`.

*Risky: affected by `markdownlint`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['markdownlint']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['md', 'markdown']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
-# hello世界
\ No newline at end of file
+# hello世界
```
</details>

<details>
<summary><b>ShfmtFixer</b></summary>

Format `sh` files using `shfmt`.

*Risky: affected by `shfmt`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['shfmt']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['sh', 'bats']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
 #!/bin/bash
 
 # Chrome 扩展目录
 EXT_DIR="$HOME/Library/Application Support/Google/Chrome/Default/Extensions"
 
 # 遍历所有扩展并按最后更新时间排序
 find "$EXT_DIR" -type d -mindepth 2 -maxdepth 2 | while read ext_path; do
-  manifest="$ext_path/manifest.json"
-  if [[ -f "$manifest" ]]; then
-    # 获取扩展名称
-    name=$(grep -m1 '"name"' "$manifest" | awk -F '"' '{print }')
-    # 获取 manifest.json 的最后修改时间
-    last_update=$(stat -f "%Sm" -t "%Y-%m-%d %H:%M:%S" "$manifest")
-    # 输出扩展 ID、名称和最后更新时间
-    echo "$last_update | ID: $(basename "$(dirname "$manifest")") | 名称: $name"
-  fi
+	manifest="$ext_path/manifest.json"
+	if [[ -f "$manifest" ]]; then
+		# 获取扩展名称
+		name=$(grep -m1 '"name"' "$manifest" | awk -F '"' '{print }')
+		# 获取 manifest.json 的最后修改时间
+		last_update=$(stat -f "%Sm" -t "%Y-%m-%d %H:%M:%S" "$manifest")
+		# 输出扩展 ID、名称和最后更新时间
+		echo "$last_update | ID: $(basename "$(dirname "$manifest")") | 名称: $name"
+	fi
 done | sort
```
</details>

<details>
<summary><b>SqRuffFixer</b></summary>

Format `sql` files using `sqruff`.

*Risky: affected by `sqruff`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['sqruff', 'fix']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['sql']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
 select
     c.id, c.name, o.address,
     o.orderedat
 from
     customers c
 left join orders o on (o.customerid = c.id)
 order by
-    o.orderedat;
\ No newline at end of file
+    o.orderedat;
```
</details>

<details>
<summary><b>SqlFluffFixer</b></summary>

Format `sql` files using `sqlfluff`.

*Risky: affected by `sqlfluff`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['sqlfluff', 'format']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['sql']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
 select
-    c.id, c.name, o.address,
+    c.id,
+    c.name,
+    o.address,
     o.orderedat
 from
     customers c
 left join orders o on (o.customerid = c.id)
 order by
-    o.orderedat;
\ No newline at end of file
+    o.orderedat;
```
</details>

<details>
<summary><b>TextLintFixer</b></summary>

Format `md` files using `textlint`.

*Risky: affected by `textlint`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['textlint']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['md', 'markdown', 'txt', 'text']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
-jquery is javascript library.
\ No newline at end of file
+jQuery is JavaScript library.
\ No newline at end of file
```
</details>

<details>
<summary><b>TombiFixer</b></summary>

Format `toml` files using `tombi`.

*Risky: affected by `tombi`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['tombi', 'format']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['toml']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
 paths = [
-"app/",
-"bootstrap/",
-"config/",
-"tests/",
-]
\ No newline at end of file
+  "app/",
+  "bootstrap/",
+  "config/",
+  "tests/",
+]
```
</details>

<details>
<summary><b>XmlLintFixer</b></summary>

Format `xml` files using `xmllint`.

*Risky: affected by `xmllint`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['xmllint']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['xml', 'xml.dist']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`
- `wrap_attrs_min_num` (`int`): wrap attributes to multiple lines when the number of attributes is greater than or equal to this value; defaults to `5`

```diff
-<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd" bootstrap="vendor/autoload.php" cacheDirectory=".build/phpunit/" colors="true">
-</phpunit>
\ No newline at end of file
+<?xml version="1.0" encoding="UTF-8"?>
+<phpunit
+  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
+  xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
+  bootstrap="vendor/autoload.php"
+  cacheDirectory=".build/phpunit/"
+  colors="true"
+>
+</phpunit>
```
</details>

<details>
<summary><b>YamlFmtFixer</b></summary>

Format `yaml` files using `yamlfmt`.

*Risky: affected by `yamlfmt`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['yamlfmt']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['yaml', 'yml']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
 on:
-    issues:
-        types: [ opened ]
\ No newline at end of file
+  issues:
+    types: [opened]
```
</details>

<details>
<summary><b>ZhLintFixer</b></summary>

Format `zh_CN.md` files using `zhlint`.

*Risky: affected by `zhlint`.*

Configuration options:

- `command` (`string[]`): the command to run the tool (e.g. `dotenv-linter fix`); defaults to `['zhlint']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['zh_CN.md']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the tool (e.g. `--fix`); defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff

```
</details>

<details>
<summary><b>DoctrineSqlFixer</b></summary>

Format `sql` files using `phpmyadmin/sql-parser`.

*Risky: affected by `phpmyadmin/sql-parser`.*

Configuration options:

- `extensions` (`string[]`): the file extensions to format; defaults to `['sql']`
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
</details>

<details>
<summary><b>JsonFixer</b></summary>

Format `json` files.

*Risky: affected by JSON encoding/decoding functions.*

Configuration options:

- `decode_flags` (`int`): the flags to use when decoding JSON; defaults to `0`
- `encode_flags` (`int`): the flags to use when encoding JSON; defaults to `4194752`
- `extensions` (`string[]`): the file extensions to format; defaults to `['json']`
- `indent_size` (`int`): the number of spaces to use for indentation; defaults to `4`

```diff
 {
-"foo": "bar",
+    "foo": "bar",
     "baz": {
-"qux": "quux"
+        "qux": "quux"
     }
 }
\ No newline at end of file
```
</details>

<details>
<summary><b>PhpMyAdminSqlFixer</b></summary>

Format `sql` files using `doctrine/sql-formatter`.

*Risky: affected by `doctrine/sql-formatter`.*

Configuration options:

- `extensions` (`string[]`): the file extensions to format; defaults to `['sql']`
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
</details>
<!-- fixerdoc-end -->

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
