# Codeception Local Setup commands

*Additional commands to make [Codeception](http://codeception.com/ "Codeception - BDD-style PHP testing.") local setup easy across teams.*

## Installation
* require the package using [Composer](https://getcomposer.org/):

```bash
composer require --dev lucatume/codeception-setup
```

* add the commands to Codeception extensions in the `codeception.yml` file:

```yaml
extensions:
    commands:
        - tad\Codeception\Command\SearchReplace
        - tad\Codeception\Command\Setup
```

## Usage
Both the commands will be available on the `codecept` CLI tool:

```bash
codecept search-replace foo bar some-source-file some-destination-file
codecept setup ./setup.yml
```

Both commands support the `--save-config` option; when used the value of any option specified to run the command will be written to the `commands-config.yaml` file.  
On subsequent runs any option that has not a value specified for an option will default to the stored one; **this is for options (`--option-name` format) values only, arguments need to be specified every time the command runs.**

### search-replace
Simply put this command will replace every instance of a string in a file and either rewrite the result in the same file or output the modified content in a specified output file.  
Teams sharing files that are used to set up fixtures can automate the "localization" of these fixture files with the command.  
An example might be one where a team shares a database dump used to set up acceptance tests in a distribution format; the database dump needs to be customized for each member of the team replacing some values in it.  
The below command will replace all instances of `http://site.local` with `http://localhost:8080` in the `dump.sql` file.

```bash
codecept search-replace http://site.local http://localhost:8080 ./tests/_data/dump.sql
```

To avoid manipulating a common source file an optional output file can be specified like this:

```bash
codecept search-replace http://site.local http://localhost:8080 ./tests/_data/dump.dist.sql ./tests/_data/dump.sql
```

The command is file agnostic and will work on any file; the following is a legit use too:

```bash
codecept search-replace http://site.local http://localhost:8080 ./tests/acceptance.suite.dist.yml ./tests/acceptance.suite.yml
```

If used in a loop some source files might be missing; in that case the `--skip-if-missing` option can be used to avoid errors when the source file is missing:

```bash
FILES="./tests/_data/dump1.sql
./tests/_data/dump2.sql
./tests/_data/dump3.sql"

for f in $FILES
do
    codecept search-replace http://site.local http://localhost:8080 $f --skip-if-missing
done
```

### setup
On the same line of team sharing way more complex set up procedures might be required to set up a local, ready to use and customized testing environment.  
While embedding shell scripts in the repository is always an option the `setup` tries to remove some required knowledge relying on a Yaml configuration file.  
If ran without specifying the configuration file to use the command will try to look for a `setup.yml` file in the project root folder:

```bash
codecept setup
```

The configuration file to use can be specified using the optional argument:

```bash
codecept setup local-setup-config.yml
```

A basic local setup configuration file might be the one below:

```yaml
foo:
    var:
        name: first
        question: First var value?
        default: 23
    message: Var value is \$first
```

The console command output will be:

```bash
> Configuring "foo"...
> First var value? (23) // user inputs 'bar' and presses Enter
> Var value is bar
```

#### if setup instruction

Any instruction can be conditionally executed using the simple `if` or `unless` syntax.  
An `if` condition will make sure the instruction is executed **if** the specified condition is true; an `unless` condition will make sure the instruction is executed if the specified condition is false.  
The condition syntax in both cases is:

```
<var> (is|not) <value>
```

The configuration file below will show the message "Hello world" if the "show" variable has not a value of "no".

```yaml
Show:
    var:
        name: show
        question: Show the message?
        validate: yesno
        default: yes
    message:
        unless: show is no
        value: Hello world
```

Which is equivalent to:

```yaml
Show:
    var:
        name: show
        question: Show the message?
        validate: yesno
        default: yes
    message:
        if: show not no
        value: Hello world
```

Condition can also be an existence one where the chekc is made on a variable being empty or not:

```yaml
Show:
    var:
        name: show
        question: Show the message?
        validate: yesno
        default: yes
    message:
        if: show
        value: Hello world
```

#### for loop setup instruction
The `message`, `command` and `exec` support the `for` argument.  
This argument allows the instruction to be executed an arbitrary number of times looping over a user entered or hard-coded variable.  
The setup instructions below will create 3 files in the root folder:

```yaml
foo:
    exec:
        for: fileName in one,two,three
        value: touch $fileName.txt
```

the string `one,two,three` represents an array of comma separated values. The format allows for spaces around commas to improved readability; the setup file below:

```yaml
foo:
    message:
        for: name in John, Marc, Daniel
        value: Hello $name!
```

will output:

> Hello John!
> Hello Marc!
> Hello Daniel!

Variables too can be used in the for loop but those must be integer values:

```yaml
foo:
    var:
        name: times
        validate: int
        default: 3
        question: How many times to loop?
    message:
        if: times
        for: i in times
        value: Loop run $i
```

will output, presuming an user input of `4`:

> Loop run 1
> Loop run 2
> Loop run 3
> Loop run 4

Please note that loops are human-friendly and start at `1` and not `0` as developers are used to.

##### var
Ask the user for a variable value **or** store a variable value.  
In the first case required fields for the `var` instructions are:

* `name` - the name the variable will be referenced with across the setup file
* `question`  - the question that will prompt the user to enter a value for the variable

If the purpose is to store a value for later use then required fields are:

* `name` - the name the variable will be referenced with across the setup file
* `value`  - the value that should be stored in the variable

Optional arguments are:

* `default` - the var default value should the user not provide any value pressing Enter without typing anything
* `validate` - the type the var should be validate with; the command will keep asking for a valid value until the user enters it; supported types are `int`, `float`, `bool`, `url`, `email`, `yesno`.  
    In addition the `regexp` validation mode is available supporting default PHP validation.

##### break
If the `setup` execution should be stopped then the `break` instruction can be used.  
The instruction has two arguments:

* `if` or `unless` condition - whether the execution should be stopped or not; required
* `value` - a message to show before the execution is stopped; optional

An example usage:

```yaml
config-name:
    var:
        name: stop
        question: stop?
        validate: yesno
    break:
        if: stop
        value: Stopped.
    message: Will not see this.
```

##### message
Displays a message to the user.  
Messages can be one line arguments like this:

```yaml
config-block:
    message: Hello world!
```

or multiline arguments to allow specifying a condition:

```yaml
config-block:
    message:
        if: someVar is yes
        value: Hello world!
```

In the latter case the `value` argument is required.  
Variable values previously obtained can be replaced in a `message` using the `$varName` notation:

```yaml
config-block:
    var:
        name: yourName
        question: What's your name?
        default: Luca
    message:
        if: yourName
        value: Hello $yourName!
```

##### command
Runs a sub-command registered on the the `codecept` CLI tool; an example might be the `search-replace` subcommand above.  
The `command` instruction can be a one liner:

```yaml
config-block:
    command: search-replace foo bar ./tests/_data/dump.dist.sql ./tests/_data/dump.sql
```

or a multilne instruction if a condition needs to be specified:

```yaml
config-block:
    var:
        name: runCommand
        validate: yesno
        default: yes
        question: run the command?
    command:
        if: runCommand is yes
        value: search-replace foo bar ./tests/_data/dump.dist.sql ./tests/_data/dump.sql 
```

Variable values previously obtained can be replaced in a `command` instruction using the `$varName` notation:

```yaml
config-block:
    var:
        name: domain
        validate: url
        default: http://local.dev
        question: local development domain?
    command: search-replace http://dist-domain.dev $domain ./tests/_data/dump.dist.sql ./tests/_data/dump.sql 
```

##### exec
Runs a script using PHP `exec()` function.  
The `exec` instruction can be a one liner:

```yaml
config-block:
    exec: touch somefile.txt
```

or a multilne instruction if a condition needs to be specified:

```yaml
config-block:
    var:
        name: runExec
        validate: yesno
        default: yes
        question: touch somefile.txt?
    exec:
        if: runExec is yes
        value: touch somefile.txt 
```

Variable values previously obtained can be replaced in an `exec` instruction using the `$varName` notation:

```yaml
config-block:
    var:
        name: fileName
        default: someFile
        question: filename?
    exec: touch $fileName.txt 
```

### setup:scaffold
While a `setup` command consumable file can be easily created the `setup:scaffold` command was born to give a quick starting point.  
Beint project-agnostic it is meant to be used after the local testing environment has been set up.  
The command allows for some options:

* `--destination` - by default the command will write a setup file to the `setup.yml` file in the project root folder: relative paths will be resolved from the project root folder. 
* `--skip-suites` - by default the command will generate a distribution version of each suite configuration file (`*.suite.yml`) if not existing already: use this option to skip it.
* `--yes` - answer `y` to all confirmation requests the command requires.
