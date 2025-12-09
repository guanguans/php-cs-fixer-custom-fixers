# 1 Rules Overview

## UpdateFixedCodeSampleRector

Update fixed code sample rector

- class: [`Guanguans\PhpCsFixerCustomFixers\Support\Rectors\UpdateFixedCodeSampleRector`](UpdateFixedCodeSampleRector.php)

```diff
 final class JsonFixer extends AbstractInlineHtmlFixer
 {
     public function getDefinition(): FixerDefinitionInterface
     {
         return new FixerDefinition(
             $summary = \sprintf('Format `%s` files.', $this->defaultExtensions()[0]),
             [
                 new CodeSample(
                     <<<'JSON'
                         {
                         "foo": "bar",
                             "baz": {
                         "qux": "quux"
                             }
                         }
                         JSON
-                )
+                ), new CodeSample(
+                    <<<'JSON'
+                    {
+                        "foo": "bar",
+                        "baz": {
+                            "qux": "quux"
+                        }
+                    }
+                    JSON
+                ),
             ],
             $summary,
             'Affected by JSON encoding/decoding functions.'
         );
     }
 }
```

<br>
