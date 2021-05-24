# Automatic git hooks

SquareOne uses [Lefthook](https://github.com/evilmartians/lefthook/blob/master/docs/full_guide.md) to automatically 
manage and install git hooks.

The configuration file, [lefthook.yml](../../lefthook.yml) is available in the root of the project and custom script
hooks in [.lefthook](../../.lefthook).

## Installation

Running `nvm use && yarn install` should automatically activate the appropriate hooks. If it does not, 
you can do an aggressive install which removes all existing git hooks and re-installs the hooks 
from Lefthook: `lefthook install -a`.
