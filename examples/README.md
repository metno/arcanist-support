# Arcanist configuration examples

This folder contains examples of .arclint configurations suitable for use on development projects at MET. If you wish to deploy a linter, and there is no suitable example file for your development environment, feel free to contact the phab.met.no administration and/or submit a Diff.

## basic.arclint.example

.arclint file that includes the basic linters usable on pretty much any project regardless of development platform: whitespace/text, spelling, filenames, etc.

Note that for the text linter, we ignore the file length check (which defaults to 80 characters; we do not feel this makes sense to enforce). We also reduce the bad Charset test to advisory information, since we may occasionally wish to include Norwegian characters in our code.

## chef.arclint.example

.arclint file that is suitable for linting a Chef project. Requires the installation of foodcritic from the Chef Development Kit to work (download and install ChefDK).

## cpp.arclint.example

.arclint file that is suitable for linting a C++ project. This setup is uses both CppCheck **and** cpplint, and requires installation of both. Note that cpplint enforces the [Google C+++ Style Guide](http://google-styleguide.googlecode.com/svn/trunk/cppguide.html). Cppcheck can be installed via apt-get; cpplint is available from GoogleCode.

## play.arclint.example

.arclint file that is suitable for linting a Scala Play project. Requires the installation of the ScalaStyle in the project directory to function.
