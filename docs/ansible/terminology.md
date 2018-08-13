# Terminology

Ansible uses some terminology that is worth knowing. It helps understand the directory structure and what the commands are doing.

- **Group Vars**: Declared variables that get used alongside the _inventory_ of the same name. The `group_vars/all/` directory are used by _all_ inventories. The `group_vars/[inventory]/` directory overrides/adds to the "all" directory.
- **Handler**: A special kind of task that runs only when it is notified (with the `notify` property) to execute.
- **Inventory**: A list of servers. The inventory should be named after what it is deploying to (i.e. development, staging, and production)
- **Playbook** _or_ **Play**: A `.yml` file that contains all of the things that will be executed against the specified inventory. The playbook specifies which server in the inventory it executes against and all the things it will execute.
- **Role**: A collection of default variables, tasks, templates, and handlers
- **Task**: An action that Ansible will execute during deploy. Like a shell command, a variable assignment, or an ansible command.
- **Template**: A template file that will be populated with variables. The templates use the [Jinja2](http://jinja.pocoo.org/docs/2.10/) templating language.
- **Variable**: Well...we're all smart here. A variable is a variable. They have an [order of precedence](https://docs.ansible.com/ansible/latest/user_guide/playbooks_variables.html#variable-precedence-where-should-i-put-a-variable) that is worth knowing.

Basically:

* A playbook is executed while specifying an inventory
* A playbook contains roles (and technically it can contain other things, like tasks)
* A role contains tasks
* A task executes commands
* A task can trigger a handler