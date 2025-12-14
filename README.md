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
+    ->registerCustomFixers($fixers = Guanguans\PhpCsFixerCustomFixers\Fixers::make())
     ->setRules([
         '@PhpCsFixer:risky' => true,
+        Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\BladeFormatterFixer::name() => true,
+        Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\YamlfmtFixer::name() => true,
     ])
     ->setFinder(
         PhpCsFixer\Finder::create()
             ->in(__DIR__)
+            // ->name(Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\BladeFormatterFixer::make()->extensionPatterns())
+            // ->name(Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\YamlfmtFixer::make()->extensionPatterns())
+            ->name($fixers->extensionPatterns())
    );
```

## Fixers

<!-- fixers-document:start -->
<details>
<summary><b>AutocorrectFixer</b></summary>

Format `txt` files using [`autocorrect`](https://github.com/huacnlee/autocorrect).

Risky: it depends on the configuration of `autocorrect`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['autocorrect']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['txt', 'text', 'md', 'markdown']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

Sample 1: configuration(`default`)

```diff
-Hello世界！
\ No newline at end of file
+Hello 世界！
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

Sample 1: configuration(`default`)

```diff
-@if($paginator->hasPages())
+@if ($paginator->hasPages())
     <nav>
         <ul class="pagination">
-        {{-- Previous Page Link --}}
-        @if ($paginator->onFirstPage())
-
-               <li class="disabled" aria-disabled="true"><span>@lang('pagination.previous')</span></li>
-        @else
-
-               <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
-        @endif
+            {{-- Previous Page Link --}}
+            @if ($paginator->onFirstPage())
+                <li class="disabled" aria-disabled="true"><span>@lang('pagination.previous')</span></li>
+            @else
+                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
+            @endif
         </ul>
     </nav>
-@endif
\ No newline at end of file
+@endif
```

Sample 2: configuration(`['options' => ['--indent-size' => 2, '--extra-liners' => true]]`)

```diff
-@if($paginator->hasPages())
-    <nav>
-        <ul class="pagination">
-        {{-- Previous Page Link --}}
-        @if ($paginator->onFirstPage())
-
-               <li class="disabled" aria-disabled="true"><span>@lang('pagination.previous')</span></li>
-        @else
-
-               <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
-        @endif
-        </ul>
-    </nav>
-@endif
\ No newline at end of file
+@if ($paginator->hasPages())
+  <nav>
+    <ul class="pagination">
+      {{-- Previous Page Link --}}
+      @if ($paginator->onFirstPage())
+        <li class="disabled" aria-disabled="true"><span>@lang('pagination.previous')</span></li>
+      @else
+        <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
+      @endif
+    </ul>
+  </nav>
+@endif
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

Sample 1: configuration(`default`)

```diff
-RUN	foo \
+RUN foo \
     # comment 1
-&& \
-# comment 2
-bar && \
-# comment 3
-baz
\ No newline at end of file
+    # comment 2
+    && bar \
+    # comment 3
+    && baz
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

Sample 1: configuration(`default`)

```diff
-FOO= BAR
-BAR = FOO
\ No newline at end of file
+BAR=FOO
+FOO=BAR
```

Sample 2: configuration(`default`)

```diff
-FOO=${BAR
-BAR="$BAR}"
\ No newline at end of file
+BAR="${BAR}"
+FOO=${BAR}
```

Sample 3: configuration(`default`)

```diff
-FOO=BAR BAZ
\ No newline at end of file
+FOO="BAR BAZ"
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

Sample 1: configuration(`default`)

```diff
 ## 全角数字
 
-> 这件蛋糕只卖 １０００ 元。
\ No newline at end of file
+> 这件蛋糕只卖 1000 元。
\ No newline at end of file
```

Sample 2: configuration(`default`)

```diff
 ## 块引用空格
 
->   摇旗呐喊的热情
+> 摇旗呐喊的热情
 >携光阴渐远去
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

Sample 1: configuration(`default`)

```diff
 # Examples
+
 ## This is ordered list
 
-1.    First item
+1. First item
 2. Second item
 
 ## This is unordered list
 
-* https://link.com
-* [ this is link  ](https://link.com   )
-* ** bold text **
\ No newline at end of file
+* <https://link.com>
+* [this is link](https://link.com   )
+* **bold text**
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

Sample 1: configuration(`default`)

```diff
 # Examples
+
 ## This is ordered list
 
-1.    First item
+1. First item
 2. Second item
 
 ## This is unordered list
 
 * https://link.com
-* [ this is link  ](https://link.com   )
-* ** bold text **
\ No newline at end of file
+* [this is link](https://link.com   )
+* **bold text**
```
</details>

<details>
<summary><b>PintFixer</b></summary>

Format `php` files using [`pint`](https://github.com/laravel/pint).

Risky: it depends on the configuration of `pint`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['php', 'vendor/bin/pint']`
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

Sample 1: configuration(`default`)

```diff
 #!/bin/bash
 
-if foo ;   then
-    bar
+if foo; then
+	bar
 fi
 
 for i in 1 2 3; do
-            bar
-done
\ No newline at end of file
+	bar
+done
```

Sample 2: configuration(`['options' => ['--minify' => true]]`)

```diff
 #!/bin/bash
-
-if foo ;   then
-    bar
+if foo;then
+bar
 fi
-
-for i in 1 2 3; do
-            bar
-done
\ No newline at end of file
+for i in 1 2 3;do
+bar
+done
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

Sample 1: configuration(`default`)

```diff
-SELECT customer_id, customer_name, COUNT(order_id) as total
+SELECT
+    customer_id,
+    customer_name,
+    COUNT(order_id) AS total
 FROM customers INNER JOIN orders ON customers.customer_id = orders.customer_id
 GROUP BY customer_id, customer_name
 HAVING COUNT(order_id) > 5
-ORDER BY COUNT(order_id) DESC;
\ No newline at end of file
+ORDER BY COUNT(order_id) DESC;
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

Sample 1: configuration(`default`)

```diff
-SELECT customer_id, customer_name, COUNT(order_id) as total
+SELECT customer_id, customer_name, COUNT(order_id) AS total
 FROM customers INNER JOIN orders ON customers.customer_id = orders.customer_id
 GROUP BY customer_id, customer_name
 HAVING COUNT(order_id) > 5
-ORDER BY COUNT(order_id) DESC;
\ No newline at end of file
+ORDER BY COUNT(order_id) DESC;
```
</details>

<details>
<summary><b>TextlintFixer</b></summary>

Format `txt` files using [`textlint`](https://github.com/textlint/textlint).

Risky: it depends on the configuration of `textlint`.

Configuration options:

- `command` (`string[]`): the command line to run the tool; defaults to `['textlint']`
- `cwd` (`string`, `null`): the working directory or null to use the working dir of the current PHP process; defaults to `null`
- `env` (`array`): the environment variables or null to use the same environment as the current PHP process; defaults to `[]`
- `extensions` (`string[]`): the file extensions to format; defaults to `['txt', 'text', 'md', 'markdown']`
- `input` (`string`, `null`): the input as stream resource, scalar or \Traversable, or null for no input; defaults to `null`
- `options` (`array`): the options to pass to the command line tool; defaults to `[]`
- `timeout` (`float`, `int`, `null`): the timeout in seconds or null to disable; defaults to `10`

Sample 1: configuration(`['options' => ['--rule' => 'terminology']]`)

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

Sample 1: configuration(`default`)

```diff
 key1 = "value1"
-
-key2 = "value2"
\ No newline at end of file
+key2 = "value2"
```

Sample 2: configuration(`default`)

```diff
-items = [
-  "aaaa",
-  "bbbb",
-  "cccc"
-]
\ No newline at end of file
+items = ["aaaa", "bbbb", "cccc"]
```

Sample 3: configuration(`default`)

```diff
-items = ["aaaa", "bbbb", "cccc", "dddd", "eeee", "ffff", "gggg", "hhhh", "iiii","jjjj"]
\ No newline at end of file
+items = [
+  "aaaa",
+  "bbbb",
+  "cccc",
+  "dddd",
+  "eeee",
+  "ffff",
+  "gggg",
+  "hhhh",
+  "iiii",
+  "jjjj"
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
- `wrap_attributes_min_attrs` (`int`): wrap attributes to multiple lines when the number of attributes is greater than or equal to this value; defaults to `5`

Sample 1: configuration(`default`)

```diff
-<phpunit bootstrap="vendor/autoload.php" colors="true" failOnDeprecation="true" failOnRisky="true" failOnWarning="true">
+<?xml version="1.0" encoding="UTF-8"?>
+<phpunit
+  bootstrap="vendor/autoload.php"
+  colors="true"
+  failOnDeprecation="true"
+  failOnRisky="true"
+  failOnWarning="true"
+>
   <php>
-    <ini name="memory_limit" value="-1"   />
-    <env name="DUMP_LIGHT_ARRAY" value=""></env>
+    <ini name="memory_limit" value="-1"/>
+    <env name="DUMP_LIGHT_ARRAY" value=""/>
   </php>
   <source>
-      <include>
-          <directory>src/</directory>
-      </include>
+    <include>
+      <directory>src/</directory>
+    </include>
   </source>
-</phpunit>
\ No newline at end of file
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

Sample 1: configuration(`default`)

```diff
 issues:
-    types: [ opened ]
\ No newline at end of file
+  types: [opened]
```

Sample 2: configuration(`default`)

```diff
 to_be_merged: &tbm
   key1: value1
 merged_map:
-  <<: *tbm
\ No newline at end of file
+  !!merge <<: *tbm
```

Sample 3: configuration(`default`)

```diff
-commands: >
+commands: >-
   [ -f "/usr/local/bin/foo" ] &&
   echo "skip install" ||
-  go install github.com/foo/foo@latest
\ No newline at end of file
+  go install github.com/foo/foo@latest
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

Sample 1: configuration(`default`)

```diff
-3 minite(s) left 中文
+3 minite(s)left 中文
 
-case-abbr：Pure JavaScript (a.k.a. Vanilla) 中文
+case-abbr：Pure JavaScript(a.k.a. Vanilla)中文
 
-case-backslash：a \# b 中文\# __中文__ \# 中文 __\#__ __中文__\#中文__\#__
+case-backslash：a \# b 中文\# __中文__ \# 中文 __\#__ __中文__\#中文 __\#__
 
-case-traditional：a「b『c』d」e 中文
+case-traditional：a “b ‘c’ d” e 中文
 
-mark-raw：a `b` c `d`e`f` g`h`i 中文
+mark-raw：a `b` c `d` e `f` g `h` i 中文
 
-mark-type：a__[b](x)__c__[ d ](y)__e 中文
+mark-type：a__[b](x)__c__ [d](y) __e 中文
 
-space-brackets：(x)a(b)c (d )e( f) g ( h ) i（j）k （l） m __( a )__ b( __c__ )d(e) 中文
+space-brackets：(x)a(b)c(d)e(f)g(h)i(j)k(l)m__(a)__b(__c__)d(e)中文
 
-space-punctuation：中文 。 中文(中文)中文。中文 . 中文（中文）中文.
+space-punctuation：中文。中文(中文)中文。中文。中文(中文)中文。
 
-space-quotations: a " hello world " b 中文
+space-quotations：a “hello world” b 中文
 
-unify-punctuation：中文,中文 （中文） 中文'中文'中文"中文"中文 （中文）（中文）中文 （中文）。
\ No newline at end of file
+unify-punctuation：中文，中文(中文)中文 ‘中文’ 中文 “中文” 中文(中文)(中文)中文(中文)。
\ No newline at end of file
```
</details>

<details>
<summary><b>JsonFixer</b></summary>

Format `json` files using [`json_encode()`](https://www.php.net/manual/en/function.json-encode.php).

Risky: it depends on the configuration of `json_encode()`.

Configuration options:

- `decode_flags` (`int`): the flags to use when decoding JSON; defaults to `0`
- `encode_flags` (`int`): the flags to use when encoding JSON; defaults to `4194752`
- `extensions` (`string[]`): the file extensions to format; defaults to `['json']`
- `indent_string` (`string`): the string to use for indentation; defaults to `'    '`

Sample 1: configuration(`default`)

```diff
 {
-    "phrase": "\u4f60\u597d\uff01"
+    "phrase": "你好！"
 }
\ No newline at end of file
```

Sample 2: configuration(`['indent_string' => '  ']`)

```diff
 {
-    "name": "guanguans/php-cs-fixer-custom-fixers",
-    "keywords": [
-        "dev",
-        "fixer",
-        "standards"
-    ],
-    "authors": [
-        {
-            "name": "guanguans",
-            "email": "ityaozm@gmail.com",
-            "homepage": "https://github.com/guanguans"
-        }
-    ]
+  "name": "guanguans/php-cs-fixer-custom-fixers",
+  "keywords": [
+    "dev",
+    "fixer",
+    "standards"
+  ],
+  "authors": [
+    {
+      "name": "guanguans",
+      "email": "ityaozm@gmail.com",
+      "homepage": "https://github.com/guanguans"
+    }
+  ]
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

Sample 1: configuration(`default`)

```diff
-SELECT customer_id, customer_name, COUNT(order_id) as total
-FROM customers INNER JOIN orders ON customers.customer_id = orders.customer_id
-GROUP BY customer_id, customer_name
-HAVING COUNT(order_id) > 5
-ORDER BY COUNT(order_id) DESC;
\ No newline at end of file
+SELECT
+    customer_id,
+    customer_name,
+    COUNT(order_id) AS total
+FROM
+    customers
+    INNER JOIN orders ON customers.customer_id = orders.customer_id
+GROUP BY
+    customer_id,
+    customer_name
+HAVING
+    COUNT(order_id) > 5
+ORDER BY
+    COUNT(order_id) DESC;
\ No newline at end of file
```

Sample 2: configuration(`['indent_string' => '  ']`)

```diff
-SELECT customer_id, customer_name, COUNT(order_id) as total
-FROM customers INNER JOIN orders ON customers.customer_id = orders.customer_id
-GROUP BY customer_id, customer_name
-HAVING COUNT(order_id) > 5
-ORDER BY COUNT(order_id) DESC;
\ No newline at end of file
+SELECT
+  customer_id,
+  customer_name,
+  COUNT(order_id) AS total
+FROM
+  customers
+  INNER JOIN orders ON customers.customer_id = orders.customer_id
+GROUP BY
+  customer_id,
+  customer_name
+HAVING
+  COUNT(order_id) > 5
+ORDER BY
+  COUNT(order_id) DESC;
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

Sample 1: configuration(`default`)

```diff
-SELECT customer_id, customer_name, COUNT(order_id) as total
-FROM customers INNER JOIN orders ON customers.customer_id = orders.customer_id
-GROUP BY customer_id, customer_name
-HAVING COUNT(order_id) > 5
-ORDER BY COUNT(order_id) DESC;
\ No newline at end of file
+SELECT
+    customer_id,
+    customer_name,
+    COUNT(order_id) AS total
+FROM
+    customers
+INNER JOIN orders ON customers.customer_id = orders.customer_id
+GROUP BY
+    customer_id,
+    customer_name
+HAVING
+    COUNT(order_id) > 5
+ORDER BY
+    COUNT(order_id)
+DESC
+    ;
\ No newline at end of file
```

Sample 2: configuration(`['options' => ['type' => 'text', 'indentation' => '  ']]`)

```diff
-SELECT customer_id, customer_name, COUNT(order_id) as total
-FROM customers INNER JOIN orders ON customers.customer_id = orders.customer_id
-GROUP BY customer_id, customer_name
-HAVING COUNT(order_id) > 5
-ORDER BY COUNT(order_id) DESC;
\ No newline at end of file
+SELECT
+  customer_id,
+  customer_name,
+  COUNT(order_id) AS total
+FROM
+  customers
+INNER JOIN orders ON customers.customer_id = orders.customer_id
+GROUP BY
+  customer_id,
+  customer_name
+HAVING
+  COUNT(order_id) > 5
+ORDER BY
+  COUNT(order_id)
+DESC
+  ;
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
