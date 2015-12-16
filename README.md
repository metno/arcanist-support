# arcanist-support

Scripts and tools for using the arcanist command line tool with Phabricator.

## Installing

We recommend installing to /usr/local (you'll need write access, of course):

    $ curl -L https://raw.github.com/metno/arcanist-support/master/bin/arc-install | sudo sh

Alternatively, download the file using wget, and install manually to your preferred directory. Run `arc --help` to view information on the available options.

Add <INSTALL_DIR>/bin/ to your PATH.

    $ export PATH="$PATH:<INSTALL_DIR>/arcanist/bin/"

You may already have /usr/local/bin in your path.

To use the linters defined in arcanist-support, configure your arcanist environment. This can be done in the .arcconfig of each project, but we recommend setting this up globally. Either manually edit your .arcrc file, or run the following command:

    $ arc set-config load '["<INSTALL_DIR>/include/php/arcanist-support/src/"]'

This will allow arcanist to access arcanist-support from wherever it is. Note that if "load" is set in a projects .arcconfig, this will overrule your global settings.

## Scripts

### bin/arc-install

Installs arcanist, libphutil, and arcanist-support.

### bin/arc-uninstall

Uninstalls arcanist, libphutil, and arcanist-support. Note that this does not remove the base directories.

### bin/arc-upgrade

Upgrades arcanist, libphutil, and arcanist-support.

### bin/arc-clean

Deletes all arcpatch-* branches in a repository. Handy if your code review workflow involves `arc patch` a lot.

## Examples

This folder contains a number of arclint sample files for chef, cpp, play, and eventually other development setups at MET. See the README for a more detailed overview of these.

## Linters

### src/linters/ArcanistFoodcriticLinter.php

Simple implementation of arcanist hooks for Foodcritic. This allows `arc lint` to run the Foodcritic linter on Chef cookbooks.

Note that there are a number of limitations in how foodcritic interacts with arcanist. Most notably, foodcritic is primarily
designed to run on the entire cookbook rather than individual files; this can cause some errors (so far observed on test files.
To minimize false positives, exclude the test directory from linting with foodcritic).

Add the following code to .arclint to use foodcritic.

    "foodcritic": {
       "type": "foodcritic",
       "exclude": "(^test/)"
    }

### src/linters/ArcanistGoogleCppLinter.php

Fixes https://secure.phabricator.com/T8404 in the Arcanist linter. This allows for using cpplint.py to lint C and C++ files.

Note that you need cpplint.py installed in your path to get this to work. The linter itself can be downloaded from
https://github.com/google/styleguide/tree/gh-pages/cpplint

Add the following code to .arclint to use googlecpplint.

    "googleccplint": {
      "type": "googlecpplint",
      "include": [
        "(\\.cpp$)",
        "(\\.cc$)",
        "(\\.c$)",
        "(\\.hpp$)",
        "(\\.h$)"
      ]
    }

### src/linters/ArcanistScalastyleLinter.php

Arcanist hooks for ScalaStyle. This allows `arc lint` to run the ScalaStyle linter on Scala code. Note that you need access to scalastyle to make this work. See https://github.com/metno/scalastyle for a way to do this.

Add the following code to .arclint to use scalastyle (adjusting config as appropriate).

    "scalastyle": {
      "type": "scalastyle",
      "config": "/etc/scalastyle-config.xml"
    },

## Unit Testers

### src/unit/SBTTestEngine.php

Arcanist hooks for the unit test engine for SBT. This allows `arc unit` to be set up with unit testing for Scala/SBT.

## Contributing

To work on the source code here, please ensure that your environment is compatible with the code style being used. Contributions should follow the Phabricator coding standards (https://secure.phabricator.com/book/phabcontrib/article/php_coding_standards/).
