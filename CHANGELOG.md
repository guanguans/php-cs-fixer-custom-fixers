<!--- BEGIN HEADER -->
# Changelog

All notable changes to this project will be documented in this file.
<!--- END HEADER -->

<a name="unreleased"></a>
## [Unreleased]


<a name="1.1.1"></a>
## [1.1.1] - 2026-02-01
### 🐞 Bug Fixes
- **dependencies:** Update composer dependencies to latest versions ([2bea16c](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/2bea16c))
- **fixers:** Add support for text file detection in TyposFixer ([02ddacb](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/02ddacb))
- **fixers:** Correct installation command for libxml2 on Linux ([f56c161](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/f56c161))

### 📖 Documents
- **README:** Add note about custom fixers for php-cs-fixer ([ac95df5](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/ac95df5))


<a name="1.1.0"></a>
## [1.1.0] - 2026-01-11
### ✨ Features
- **fixers:** Add TyposFixer and corresponding test cases ([7dde147](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/7dde147))
- **fixers:** Add TyposFixer and configuration for typo detection ([c731c71](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/c731c71))

### 🤖 Continuous Integrations
- **config:** Update config files ([3b5f134](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/3b5f134))
- **config:** Update environment variables for PHP CS Fixer ([aa9e196](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/aa9e196))


<a name="1.0.6"></a>
## [1.0.6] - 2026-01-06
### ✨ Features
- **fixers:** Add initial fixers and rules files ([03a2555](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/03a2555))

### 🐞 Bug Fixes
- **autoload:** Allow nullable boolean for debugging parameter ([6aea741](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/6aea741))

### 🎨 Styles
- apply php-cs-fixer ([19345ca](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/19345ca))

### 📦 Builds
- **deps-dev:** update rector/jack requirement || ^0.5 ([#2](https://github.com/guanguans/php-cs-fixer-custom-fixers/issues/2)) ([99d0200](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/99d0200))


<a name="1.0.5"></a>
## [1.0.5] - 2025-12-26
### ✨ Features
- **configurable:** Add ConfigurableOfSkipPaths trait ([dc64eee](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/dc64eee))
- **fixers:** Add dependencyCommands method to retrieve commands ([4685592](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/4685592))
- **fixers:** Implement installationCommand method for command line tools ([a92e9de](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/a92e9de))

### 🐞 Bug Fixes
- **fixers:** Ensure unique allowed values for line endings ([2e0fe99](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/2e0fe99))

### 💅 Code Refactorings
- apply inspection ([794af4d](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/794af4d))
- **exceptions:** Rename InvalidConfigurationException to InvalidFixerConfigurationException ([85b80e4](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/85b80e4))
- **fixers:** Improve output handling and extend file extensions ([c93c3b0](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/c93c3b0))
- **fixers:** Rename HasFinalFile trait to HasFile and update references ([ee213ad](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/ee213ad))
- **fixers:** Rename installationCommand to installDependencyCommand ([9c3801e](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/9c3801e))

### ✅ Tests
- **tests:** Rename variables and clean up test files ([99d0573](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/99d0573))


<a name="1.0.4"></a>
## [1.0.4] - 2025-12-19
### ✨ Features
- **fixer-definition:** Add FileSpecificCodeSample class and update usage ([7567618](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/7567618))
- **fixers:** Add ConfigurableOfSingleBlankLineAtEof trait ([93c861a](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/93c861a))

### 🐞 Bug Fixes
- **CommandLineTool:** Handle missing command line tool warning ([e22a03d](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/e22a03d))

### 💅 Code Refactorings
- **fixers:** Rename fixedCode to format and restructure logic ([8b57ddd](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/8b57ddd))

### ✅ Tests
- **composer:** Update autoloading, test base class, and scripts for PhpCsFixer integration ([e75d8e4](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/e75d8e4))
- **fixers:** Add new fixer tests ([555e044](https://github.com/guanguans/php-cs-fixer-custom-fixers/commit/555e044))


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


[Unreleased]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.1.1...HEAD
[1.1.1]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.0.6...1.1.0
[1.0.6]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.0.5...1.0.6
[1.0.5]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.0.4...1.0.5
[1.0.4]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.0.3...1.0.4
[1.0.3]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/guanguans/php-cs-fixer-custom-fixers/compare/1.0.0...1.0.1
