<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/php-cs-fixer-custom-fixers
 */

namespace Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool;

use Guanguans\PhpCsFixerCustomFixers\Fixer\CommandLineTool\Concerns\PostFinalFileCommand;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;

/**
 * @see https://github.com/mvdan/sh
 */
final class ShfmtFixer extends AbstractCommandLineToolFixer
{
    use PostFinalFileCommand;

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            $summary = \sprintf('Format `%s` files using `shfmt`.', $this->defaultExtensions()[0]),
            [
                new CodeSample(
                    <<<'SH_WRAP'
                        #!/bin/bash

                        # Chrome 扩展目录
                        EXT_DIR="$HOME/Library/Application Support/Google/Chrome/Default/Extensions"

                        # 遍历所有扩展并按最后更新时间排序
                        find "$EXT_DIR" -type d -mindepth 2 -maxdepth 2 | while read ext_path; do
                          manifest="$ext_path/manifest.json"
                          if [[ -f "$manifest" ]]; then
                            # 获取扩展名称
                            name=$(grep -m1 '"name"' "$manifest" | awk -F '"' '{print $4}')
                            # 获取 manifest.json 的最后修改时间
                            last_update=$(stat -f "%Sm" -t "%Y-%m-%d %H:%M:%S" "$manifest")
                            # 输出扩展 ID、名称和最后更新时间
                            echo "$last_update | ID: $(basename "$(dirname "$manifest")") | 名称: $name"
                          fi
                        done | sort

                        SH_WRAP
                ), new CodeSample(
                    <<<'SH_WRAP'
                        #!/bin/bash

                        # Chrome 扩展目录
                        EXT_DIR="$HOME/Library/Application Support/Google/Chrome/Default/Extensions"

                        # 遍历所有扩展并按最后更新时间排序
                        find "$EXT_DIR" -type d -mindepth 2 -maxdepth 2 | while read ext_path; do
                        	manifest="$ext_path/manifest.json"
                        	if [[ -f "$manifest" ]]; then
                        		# 获取扩展名称
                        		name=$(grep -m1 '"name"' "$manifest" | awk -F '"' '{print $4}')
                        		# 获取 manifest.json 的最后修改时间
                        		last_update=$(stat -f "%Sm" -t "%Y-%m-%d %H:%M:%S" "$manifest")
                        		# 输出扩展 ID、名称和最后更新时间
                        		echo "$last_update | ID: $(basename "$(dirname "$manifest")") | 名称: $name"
                        	fi
                        done | sort

                        SH_WRAP
                ),
            ],
            $summary,
            'Affected by `shfmt`'
        );
    }

    /**
     * @see `-ln, --language-dialect str  bash/posix/mksh/bats, default "auto"`
     *
     * @return non-empty-list<string>
     */
    public function defaultExtensions(): array
    {
        return ['sh', 'bats'];
    }

    /**
     * @return list<string>
     */
    protected function defaultCommand(): array
    {
        return ['shfmt'];
    }

    /**
     * @return array<int|string, null|scalar>
     */
    protected function requiredOptions(): array
    {
        return [
            '--write',
            // '--simplify',
            // '--minify',
        ];
    }
}
