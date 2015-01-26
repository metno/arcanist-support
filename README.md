# arcanist-support

Scripts and tools for using the arcanist command line tool with Phabricator.

## bin/arc\_delete\_patches

Deletes all arcpatch-* branches in a repository. Handy if your code review workflow involves `arc patch` a lot.

## src/linters/ArcanistScalastyleLinter.php

Arcanist hooks for ScalaStyle. This allows `arc lint` to run the ScalaStyle linter on Scala code.

## src/unit/SBTTestEngine.php

Arcanist hooks for the unit test engine for SBT. This allows `arc unit` to be set up with unit testing for Scala/SBT. 