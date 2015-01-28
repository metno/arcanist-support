# arcanist-support

Scripts and tools for using the arcanist command line tool with Phabricator.

## bin/arc\_delete\_patches

Deletes all arcpatch-* branches in a repository. Handy if your code review workflow involves `arc patch` a lot.

## src/linters/ArcanistFoodcriticLinter.php

Simple implementation of arcanist hooks for Foodcritic. This allows `arc lint` to run the Foodcritic linter on Chef cookbooks.

Note that there are a number of limitations in how foodcritic interacts with arcanist. Most notably, foodcritic is primarily
designed to run on the entire cookbook rather than individual files; this can cause some errors (so far observed on test files.
To minimize false positives, exclude the test directory from linting with foodcritic.

Add the following code to .arclint to use foodcritic.

    "foodcritic": {
       "type": "foodcritic",
       "exclude": "(^test/)"
    }

## src/linters/ArcanistScalastyleLinter.php

Arcanist hooks for ScalaStyle. This allows `arc lint` to run the ScalaStyle linter on Scala code.

## src/unit/SBTTestEngine.php

Arcanist hooks for the unit test engine for SBT. This allows `arc unit` to be set up with unit testing for Scala/SBT.

## Development Environment

### Eclipse

The PHP code formatter should use the PSR-2 style, with 2-space indents.

### Arcanist Linting

To lint this project, you will need PHP_CodeSniffer installed. On Ubuntu, ensure that you have the php-pear package installed, and then run:
```sudo pear install PHP_CodeSniffer```
