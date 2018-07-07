# Ansible Provisioning, Configuration, and Deployment

## Requirements

* Ansible >= 2.1.1

There are some other requirements if you're actually provisioning/configuring the servers with Ansible, but if you're just deploying, installing Ansible should cover you.

After installing ansible you should run `ansible-galaxy install -r requirements.yml -c` from this directory

## Security of Keys and Configuration
Some variables and files used by Ansible contain sensitive data, so we're using a tool called [Ansible Vault](http://docs.ansible.com/ansible/playbooks_vault.html) to encrypt the data in place.  To work with this data, you'll need to have the vault key.  You can find it here: https://central.tri.be/projects/systems/wiki/Hosting_Info.  When you have it, run the following command from this directory: `echo "[the key]" >> .vaultpass`.

Once you do that, you'll need to decrypt the necessary resources manually:

```
ansible-vault decrypt .aws/WHATEVER.pem.vaulted --output=.aws/WHATEVER.pem
```

The rest of the decryption will be handled during Ansible Playbook execution.

## Terminology

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

## Deployment

There are a few different playbooks that are included here:

### `site.yml` for intial deployments

When you deploy for the first time on any inventory, you need to do:

```
ansible-playbook -i inventory/WHATEVER site.yml
```

This will execute:

* `setup-system.yml` playbook
* `setup-databse.yml` playbook
* `setup-project.yml` playbook

If bootstraps the system for the project.

### `setup-system.yml` for system-level deploys

If there's a deploy for monkeying with any of the parts of the system not specifically related to the project's codebase itself or the database, this is the playbook that you'd execute.

```
ansible-playbook -i inventory/WHATEVER setup-system.yml
```

If there are additional apt packages, log settings, nginx changes, etc - this is the playbook you'd call the roles from.

### `setup-database.yml` for database-level deploys

If something is changing on the database side of the house - like a new db, a new user, etc - this is the playbook that'd be executed.

```
ansible-playbook -i inventory/WHATEVER setup-database.yml
```

### `setup-project.yml` for project-level setup deploys

This playbook sets up the project-specific tools (like grunt and wpcli) and also fetchs/builds the project.

```
ansible-playbook -i inventory/WHATEVER setup-project.yml
```

### `deploy.yml` for code-level deploys

This is the playbook that'll be used the most. It deploys the latest checkouts for the codebase and runs the relevant build/cleanup processes.

```
ansible-playbook -i inventory/WHATEVER deploy.yml
```

## Tips

Avoid putting tasks directly in playbooks. Instead, find an appropriate role (or make a new one) to contain the tasks.

## Notes

The `local-config.php` file that gets used comes from the `roles/wordpress/templates` directory.