# Executing Deploys

## Overview

Ansible deploys are done using the `ansible-playbook` command line utility. When executing `ansible-playbook`, you specify the _inventory_ (e.g. server config) and _playbook_ that you wish to execute. A playbook is a collection of commands that are executed in sequential order. The typical usecase for a run-of-the-mill code deploy would be to execute the `deploy.yml` playbook. Whereas the other default playbooks that are provided in SquareOne are for initial setup of the server.

## VERY IMPORTANT Prerequisite

When deploying to a server, Ansible uses `ForwardAgent` when ssh-ing into the server. That way your local ssh key can be used to clone repos rather than requiring them on the server itself. To make sure this works like a champ, execute the following on your local compy from your terminal of choice:

```
ssh-add /path/to/key/you/use/for/github

ssh-agent

# Here's an example of what Matt B did:
ssh-add ~/.ssh/id_rsa
```

## Playbooks

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
