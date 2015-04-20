# arcanist-support

Scripts and tools for using the arcanist command line tool with Phabricator.

## Installing

We recommend installing metno/arcanist-support to your workspace directory (i.e., the root folder of your projects).

    $ git clone https://github.com/metno/arcanist-support

To use the scripts, modify your PATH env so that it accesses <ABSOLUTE-PATH-TO-ARCANIST-SUPPORT>/bin/

To use the linters defined in arcanist-support, configure your arcanist environment. This can be done in the .arcconfig of each project, but we recommend setting this up globally. Either manually edit your .arcrc file, or run the following command:

    $ arc set-config load '["<ABSOLUTE-PATH-TO-ARCANIST-SUPPORT>/src/"]'

This will allow arcanist to access arcanist-support from wherever it is. Note that if "load" is set in a projects .arcconfig, this will overrule your global settings.

## Scripts

### bin/arc\_delete\_patches

Deletes all arcpatch-* branches in a repository. Handy if your code review workflow involves `arc patch` a lot.

## Examples

This folder contains a number of arclint sample files for chef, cpp, play, and eventually other development setups at MET. See the README for a more detailed overview of these.

## Linters

### src/linters/ArcanistFoodcriticLinter.php

Simple implementation of arcanist hooks for Foodcritic. This allows `arc lint` to run the Foodcritic linter on Chef cookbooks.

Note that there are a number of limitations in how foodcritic interacts with arcanist. Most notably, foodcritic is primarily
designed to run on the entire cookbook rather than individual files; this can cause some errors (so far observed on test files.
To minimize false positives, exclude the test directory from linting with foodcritic.

Add the following code to .arclint to use foodcritic.

    "foodcritic": {
       "type": "foodcritic",
       "exclude": "(^test/)"
    }

### src/linters/ArcanistScalastyleLinter.php

Arcanist hooks for ScalaStyle. This allows `arc lint` to run the ScalaStyle linter on Scala code. Note that you need access to scalastyle to make this work. See
https://github.com/metno/scalastyle for a way to do this.

## Unit Testers

### src/unit/SBTTestEngine.php

Arcanist hooks for the unit test engine for SBT. This allows `arc unit` to be set up with unit testing for Scala/SBT.

## Contributing

To work on the source code here, please ensure that your environment is compatible with the code style being used.

### Eclipse

The PHP code formatter should use the PSR-2 style, with 2-space indents.

### Arcanist Linting

To lint this project, you will need PHP_CodeSniffer installed. On Ubuntu, ensure that you have the php-pear package installed, and then run:
```sudo pear install PHP_CodeSniffer```
