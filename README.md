# php-cs-fixer-custom-fixers

> [!WARNING]
> This package is not stable yet, use it with caution.
> 
> Use php-cs-fixer to format bats,blade.php,Dockerfile,env,json,markdown,sh,sql,text,toml,xml,yaml...files. - 使用 php-cs-fixer 去格式化 bats、blade.php、Dockerfile、env、json、markdown、sh、sql、text、toml、xml、yaml...文件。

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

In your php-cs-fixer configuration register fixers and use them:

```diff
 <?php
 return (new PhpCsFixer\Config())
+    ->registerCustomFixers($fixers = new Guanguans\PhpCsFixerCustomFixers\Fixers())
     ->setRules([
         '@PhpCsFixer:risky' => true,
+        Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\BladeFormatterFixer::name() => true,
+        Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\YamlFmtFixer::name() => true,
     ])
     ->setFinder(
         PhpCsFixer\Finder::create()
             ->in(__DIR__)
+            // ->name(Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\BladeFormatterFixer::make()->extensionPatterns())
+            // ->name(Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\YamlFmtFixer::make()->extensionPatterns())
+            ->name($fixers->extensionPatterns())
    );
```

## Fixers

<!-- fixers-document:start -->
<details>
<summary><b>AutocorrectFixer</b></summary>

Format `md` files using [`autocorrect`](https://github.com/huacnlee/autocorrect).

Risky: it depends on the configuration of `autocorrect`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['autocorrect']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['md', 'markdown', 'txt', 'text']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
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

Format `blade.php` files using [`blade-formatter`](https://github.com/shufo/blade-formatter).

Risky: it depends on the configuration of `blade-formatter`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['blade-formatter']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['blade.php']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
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
<summary><b>DockerfmtFixer</b></summary>

Format `Dockerfile` files using [`dockerfmt`](https://github.com/reteps/dockerfmt).

Risky: it depends on the configuration of `dockerfmt`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['dockerfmt']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['Dockerfile']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
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

Format `env` files using [`dotenv-linter`](https://github.com/dotenv-linter/dotenv-linter).

Risky: it depends on the configuration of `dotenv-linter`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['dotenv-linter', 'fix']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['env', 'env.example']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
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

Format `md` files using [`lint-md`](https://github.com/lint-md/lint-md).

Risky: it depends on the configuration of `lint-md`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['lint-md']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['md', 'markdown']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
-# hello世界
\ No newline at end of file
+# hello 世界
\ No newline at end of file
```
</details>

<details>
<summary><b>MarkdownlintCli2Fixer</b></summary>

Format `md` files using [`markdownlint-cli2`](https://github.com/DavidAnson/markdownlint-cli2).

Risky: it depends on the configuration of `markdownlint-cli2`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['markdownlint-cli2']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['md', 'markdown']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
-# hello世界
\ No newline at end of file
+# hello世界
```
</details>

<details>
<summary><b>MarkdownlintFixer</b></summary>

Format `md` files using [`markdownlint`](https://github.com/igorshubovych/markdownlint-cli).

Risky: it depends on the configuration of `markdownlint`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['markdownlint']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['md', 'markdown']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
-# hello世界
\ No newline at end of file
+# hello世界
```
</details>

<details>
<summary><b>PintFixer</b></summary>

Format `php` files using [`pint`](https://github.com/laravel/pint).

Risky: it depends on the configuration of `pint`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['/opt/homebrew/Cellar/php@7.4/7.4.33_13/bin/php', 'vendor/bin/pint']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['php']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`
</details>

<details>
<summary><b>ShfmtFixer</b></summary>

Format `sh` files using [`shfmt`](https://github.com/mvdan/sh).

Risky: it depends on the configuration of `shfmt`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['shfmt']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['sh', 'bats']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
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
<summary><b>SqlfluffFixer</b></summary>

Format `sql` files using [`sqlfluff`](https://github.com/sqlfluff/sqlfluff).

Risky: it depends on the configuration of `sqlfluff`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['sqlfluff', 'format']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['sql']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
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
<summary><b>SqruffFixer</b></summary>

Format `sql` files using [`sqruff`](https://github.com/quarylabs/sqruff).

Risky: it depends on the configuration of `sqruff`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['sqruff', 'fix']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['sql']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
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
<summary><b>TextlintFixer</b></summary>

Format `md` files using [`textlint`](https://github.com/textlint/textlint).

Risky: it depends on the configuration of `textlint`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['textlint']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['md', 'markdown', 'txt', 'text']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
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

Format `toml` files using [`tombi`](https://github.com/tombi-toml/tombi).

Risky: it depends on the configuration of `tombi`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['tombi', 'format']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['toml']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
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
<summary><b>XmllintFixer</b></summary>

Format `xml` files using [`xmllint`](https://gnome.pages.gitlab.gnome.org/libxml2/xmllint.html).

Risky: it depends on the configuration of `xmllint`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['xmllint']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['xml', 'xml.dist']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
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
<summary><b>YamlfmtFixer</b></summary>

Format `yaml` files using [`yamlfmt`](https://github.com/google/yamlfmt).

Risky: it depends on the configuration of `yamlfmt`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['yamlfmt']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['yaml', 'yml']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
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
<summary><b>ZhlintFixer</b></summary>

Format `zh_CN.md` files using [`zhlint`](https://github.com/zhlint-project/zhlint).

Risky: it depends on the configuration of `zhlint`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['zhlint']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['zh_CN.md']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

```diff
-hello世界:
\ No newline at end of file
+hello 世界：
\ No newline at end of file
```
</details>

<details>
<summary><b>JsonFixer</b></summary>

Format `json` files using [`json_encode()/json_decode()`](https://www.php.net/manual/en/function.json-encode.php).

Risky: it depends on the configuration of `json_encode()/json_decode()`.

Configuration options:

- `decode_flags` (`int`): the flags to use when decoding JSON; defaults to `0`
- `encode_flags` (`int`): the flags to use when encoding JSON; defaults to `4194752`
- `extensions` (`string[]`): the file extensions to format; defaults to `['json']`
- `indent_string` (`string`): the string to use for indentation; defaults to `'    '`

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

```diff
 {
-    "phrase": "\u4f60\u597d\uff01"
+    "phrase": "你好！"
 }
\ No newline at end of file
```
</details>

<details>
<summary><b>SqlOfDoctrineSqlFormatterFixer</b></summary>

Format `sql` files using [`doctrine/sql-formatter`](https://github.com/doctrine/sql-formatter).

Risky: it depends on the configuration of `doctrine/sql-formatter`.

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
<summary><b>SqlOfPhpmyadminSqlParserFixer</b></summary>

Format `sql` files using [`phpmyadmin/sql-parser`](https://github.com/phpmyadmin/sql-parser).

Risky: it depends on the configuration of `phpmyadmin/sql-parser`.

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
<!-- fixers-document:end -->

## Composer scripts

```shell
composer checks:required
composer php-cs-fixer-custom-fixers:update-fixers-document
composer php-cs-fixer:fix
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
