# 1 Rules Overview

## UpdateCodeSamplesRector

Update code samples rector

- class: [`Guanguans\PhpCsFixerCustomFixers\Support\Rectors\UpdateCodeSamplesRector`](UpdateCodeSamplesRector.php)

```diff
 protected function codeSamples(): array
 {
     return [
         new CodeSample(
             <<<'YAML_WRAP'
                 on:
                     issues:
                         types: [ opened ]
                 YAML_WRAP
-        )
+        ), new CodeSample(
+            <<<'YAML_WRAP'
+                on:
+                  issues:
+                    types: [opened]
+
+                YAML_WRAP
+        ),
     ];
 }
```

<br>
