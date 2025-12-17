<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

<a name="unreleased"></a>
## [Unreleased]


<a name="1.0.3"></a>
## [1.0.3] - 2025-12-17
### ✨ Features
- **configurable:** Add Configurable trait and refactor related classes ([c39e0d1](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/c39e0d1))
- **contract:** Add InstallDependencyContract interface ([8718d05](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/8718d05))
- **fixers:** Introduce Configurable trait and refactor related classes ([092cd7c](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/092cd7c))

### 🐞 Bug Fixes
- **ComposerScripts:** Update exit strategy for validation checks ([b2d3d9a](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/b2d3d9a))

### 💅 Code Refactorings
- apply phpstan ([3ccc330](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/3ccc330))
- apply inspection ([b7abbfc](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/b7abbfc))
- **SqlOfPhpmyadminSqlParserFixer:** Refactor configuration options for SQL formatter ([c62495c](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/c62495c))


<a name="1.0.2"></a>
## [1.0.2] - 2025-12-15
### 🐞 Bug Fixes
- **composer.json:** Fix invalid package information ([579f08a](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/579f08a))


<a name="1.0.1"></a>
## [1.0.1] - 2025-12-15
### 💅 Code Refactorings
- **commandline-tool:** Refactor command line option handling with normalization and validation ([fa350ab](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/fa350ab))
- **fixers:** Enhance final file deletion logic and add isSequential method ([46b0ef1](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/46b0ef1))
- **fixers:** Rename Concerns namespace to Concern ([fa09e24](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/fa09e24))
- **fixers:** Add EXTENSIONS constant to relevant fixers ([426b0b9](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/426b0b9))
- **fixers:** Update required options and code samples ([652d5cc](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/652d5cc))
- **fixers:** improve fixers document generation ([a695561](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/a695561))
- **scripts:** Implement checkDocument method for validation ([707ffa3](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/707ffa3))
- **xmllint-fixer:** Rename wrap_attrs_min_num to wrap_attributes_min_attrs in XmllintFixer ([d9cf606](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/d9cf606))

### 📦 Builds
- **deps:** update dependencies and configuration ([c07bb91](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/c07bb91))


<a name="1.0.0"></a>
## 1.0.0 - 2025-12-13
### ✨ Features
- wip ([7f454f9](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/7f454f9))
- Build the basic skeleton ([844d4d2](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/844d4d2))
- **extensions:** add methods for default extensions retrieval ([5ebae90](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/5ebae90))
- **fixers:** implement MakeStaticable trait for static instantiation ([1e8783b](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/1e8783b))

### 💅 Code Refactorings
- apply inspection ([ce1e939](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/ce1e939))
- **fixers:** rename fixers ([af88967](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/af88967))
- **project:** finalize exceptions, improve ZhLintFixer, and update fixer config ([72b7e0a](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/72b7e0a))
- **utils:** Move Utils to Support namespace and update imports ([27ee71a](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/27ee71a))

### 📦 Builds
- **deps:** bump actions/cache from 4 to 5 ([89a0ce1](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/89a0ce1))

### Pull Requests
- Merge pull request [#1](https://github.com/guanguans/php-cs-fixer-custom-fixers/issues/1) from guanguans/dependabot/github_actions/actions/cache-5


[Unreleased]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.0.3...HEAD
[1.0.3]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.0.0...1.0.1
