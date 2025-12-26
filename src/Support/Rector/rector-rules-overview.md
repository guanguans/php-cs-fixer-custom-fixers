# 2 Rules Overview

## NewExceptionToNewAnonymousExtendsExceptionImplementsRector

New exception to new anonymous extends exception implements

:wrench: **configure it!**

- class: [`Guanguans\PhpCsFixerCustomFixers\Support\Rector\NewExceptionToNewAnonymousExtendsExceptionImplementsRector`](NewExceptionToNewAnonymousExtendsExceptionImplementsRector.php)

```diff
-new \Exception('Testing');
+new class('Testing') extends \Exception implements \Guanguans\MonorepoBuilderWorker\Contracts\ThrowableContract
+{
+};
```

<br>

## UpdateCodeSamplesRector

Update code samples rector

- class: [`Guanguans\PhpCsFixerCustomFixers\Support\Rector\UpdateCodeSamplesRector`](UpdateCodeSamplesRector.php)

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
