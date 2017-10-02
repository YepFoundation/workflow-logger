[![Build Status](https://travis-ci.org/YepFoundation/workflow-logger.svg?branch=master)](https://travis-ci.org/YepFoundation/workflow-logger)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/YepFoundation/workflow-logger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/YepFoundation/workflow-logger/?branch=master)
[![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/YepFoundation/workflow-logger/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/YepFoundation/workflow-logger/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/yep/workflow-logger/v/stable)](https://packagist.org/packages/yep/workflow-logger)
[![Total Downloads](https://poser.pugx.org/yep/workflow-logger/downloads)](https://packagist.org/packages/yep/workflow-logger)
[![License](https://poser.pugx.org/yep/workflow-logger/license)](https://github.com/YepFoundation/workflow-logger/blob/v2/LICENSE.md)

# Workflow logger

## Packagist
Reflection is available on [Packagist.org](https://packagist.org/packages/yep/workflow-logger),
just add the dependency to your composer.json.

```json
{
  "require" : {
    "yep/workflow-logger": "^1.2"
  }
}
```

or run Composer command:

```php
php composer.phar require yep/workflow-logger
```

## What Yep/WorkflowLogger do?
It helps to log workflows! :flushed:

### Try to imagine this situation ↓↓↓
```php
<?php
class SomeImportantManagerClass {
    public function doSomeImportantJob($importantParameter) {
        $foo = $this->doSomethingImportant($importantParameter);

        if($foo > 1) {
            $this->doSomeMagicButReallyImportantMagic();
        }
    }
    ...
}

$someImportantManagerClass = new SomeImportantManagerClass();
$someImportantVariable = 1;

$someImportantManagerClass->doSomeImportantJob($someImportantVariable);
```

Question: How do you know, that each method done exactly what you expect?<br>
Answer: I don't know, but I can add Logger! :blush:<br>
Reaction: :+1:

### So we will add Monolog\Logger ↓↓↓
```php
<?php
class SomeImportantManagerClass {
    /** @var Monolog\Logger  */
    private $logger;
    
    public function __construct(Monolog\Logger $logger) {
        $this->logger = $logger;
    }
    
    public function doSomeImportantJob($importantParameter) {
        $this->logger->info('Im in!');

        $foo = $this->doSomethingImportant($importantParameter);
        $this->logger->info('I just done something important!', ['foo' => $foo]);

        if($foo > 1) {
            $result = $this->doSomeMagicButReallyImportantMagic();
            $this->logger->alert('Abracadabra #copperfield', ['result' => $result, 'foo' => $foo]);
        }
        else {
            $this->logger->error('No Abracadabra #sadCopperfield', ['importantParameter' => $importantParameter, 'foo' => $foo]);
        }
    }
    ...
}

$importantLogger = new Monolog\Logger('ImportantLogger');

$someImportantManagerClass = new SomeImportantManagerClass($importantLogger);
$someImportantVariable = 1;

$someImportantManagerClass->doSomeImportantJob($someImportantVariable);
```

Question: How many log items we will have?<br>
Answer: 3! :yum:<br>
Reaction: Yes, Correct! :+1:

Question: But what should we do if we want only one log record? :smirk:<br>
Answer: Dunno... :scream:<br>
Reaction: Really? So, have a look below! :sunglasses:

### We will "improve" our logging ↓↓↓
```php
<?php
class SomeImportantManagerClass {
    /** @var Monolog\Logger  */
    private $logger;
    
    public function __construct(Monolog\Logger $logger) {
        $this->logger = $logger;
    }
    
    public function doSomeImportantJob($importantParameter) {
        $logMessage = "Im in!\n";
        $logContext = ['importantParameter' => $importantParameter];

        $foo = $this->doSomethingImportant($importantParameter);
        $logMessage .= "I just done something important!\n";
        $logContext['foo'] = $foo;

        if($foo > 1) {
            $result = $this->doSomeMagicButReallyImportantMagic();
            $logMessage .= "Abracadabra #copperfield\n";
            $logContext['result'] = $result;
        }
        else {
            $logMessage .= "No Abracadabra #sadCopperfield\n";
        }

        $this->logger->info($logMessage, $logContext);
    }
    ...
}
```

Question: Much better! What do you think?<br>
Answer: But but but moooom, in this case I can log these messages only with one type and I don't know, for which one is the context data...<br>
Reaction: Yop, you are right... :sweat_smile:<br>However, you can use our WorkflowLogger! :bowtie:

### Now the real magic with Yep\WorkflowLogger\Logger! :sunglasses: ↓↓↓
```php
<?php
class SomeImportantManagerClass
{
    /** @var Yep\WorkflowLogger\Logger */
    private $logger;

    public function __construct(Yep\WorkflowLogger\Logger $logger)
    {
        $this->logger = $logger;
    }

    public function doSomeImportantJob($importantParameter)
    {
        $workflow = $this->logger->workflow();
        $workflow->info('Im in!');

        $foo = $this->doSomethingImportant($importantParameter);
        $workflow->info('I just done something important!', ['foo' => $foo]);

        if($foo > 1) {
            $result = $this->doSomeMagicButReallyImportantMagic();
            $workflow->alert('Abracadabra #copperfield', ['result' => $result, 'foo' => $foo]);
        }
        else {
            $workflow->error('No Abracadabra #sadCopperfield', ['importantParameter' => $importantParameter, 'foo' => $foo]);
        }

        $workflow->finish('Finished one of many important workflows', ['nextStep' => 'improve!']);
    }

}

// $dumper = new Yep\WorkflowLogger\ContextDumper\PrintRDumper();
// $dumper = new Yep\WorkflowLogger\ContextDumper\TracyDumper();
$dumper = new Yep\WorkflowLogger\ContextDumper\SymfonyVarDumper();
$formatter = new Yep\WorkflowLogger\Formatter\StandardFormatter($dumper);
$importantLogger = new Yep\WorkflowLogger\Logger('ImportantLogger', $formatter);

$someImportantManagerClass = new SomeImportantManagerClass($importantLogger);
$someImportantVariable = 1;

$someImportantManagerClass->doSomeImportantJob($someImportantVariable);
```

##### Log result
```
[2017-10-02 01:52:20] ImportantLogger.WORKFLOW: Finished one of many important workflows

Workflow:
[2017-10-02 01:52:20.388575] INFO: Im in!
[2017-10-02 01:52:20.388633] INFO: I just done something important!
Context:
[
  "foo" => 2
]

[2017-10-02 01:52:20.388643] ALERT: Abracadabra #copperfield
Context:
[
  "result" => "Alohomora"
  "foo" => 2
]

 {"nextStep":"improve!"} []
```

Reaction: :flushed: :scream:


### Hints
* You can use the same workflow more times until is locked during finish or manually by lock method.<br>
If you want to get the same workflow just call `$logger->workflow($key)` with `key` as the first argument.<br>
Every time you will call that method, logger will give you the same workflow until is locked. :sunglasses:
* If you want to use `\Monolog\Formatter\ChromePHPFormatter`, `\Monolog\Formatter\GelfMessageFormatter`, `\Monolog\Formatter\WildfireFormatter` or similar with freezed log Levels, you have to use `\Yep\WorkflowLogger\MonologFormatterAdapter`.

> That's all. I hope you like it. :kissing_smiling_eyes:
