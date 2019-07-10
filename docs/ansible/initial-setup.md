# Initial Setup

## Overview

The `ansible/` directory is a starter template for Ansible deploys. It won't be fully functional until you make some edits and additions. While there are a ton of ways that the Ansible setup can be customized, this doc should help get you started with the basics.

## Add a `.vaultpass` file

Ansible handles a lot of variables. Some variables hold sensitive data and thus should be encrypted. You'll need to create a `.vaultpass` for your project. It should be a random string of at least 8 characters.

### Add to 1Password

The `.vaultpass` file should never be committed to the repository. However, we'll need the string in someplace secure so we can decrypt the encrypted files. We prefer placing the `.vaultpass` in 1Password over the project wiki. Each project should have a 1Password Vault in 1Password. Create an entry called `Project Name - .vaultpass` with the string as a password field.

## Server setup

First and foremost, you'll need an AWS EC2 instance that Ansible can push server settings and code to. Without that, what good is an `ansible/` directory? Here's the minimum server you'll need:

* Type: Ubuntu server
* Size: t2.small or larger
* Security group: `web-server` or an equivalent that provides ports 22, 80, and 443
* You'll need the name of the SSL key pair that you want created (the name will end up being the name of the .pem file)

### PEM file

During the process of setting up the EC2 server, the SSL key pair will be made available for download. Download that bad boy (it'll be named NAME.pem), do the following:

_Note: `NAME.pem` is just a placeholder filename. You'll probably have something much more intuitive like `loxi.pem`_.

1. Place the `NAME.pem` file in the `ansible/.aws/` directory
2. Create an encrypted version of the pem file. From the `ansible/` directory, execute:

```
ansible-vault encrypt .aws/NAME.pem --output=.aws/NAME.pem.vaulted
```

3. Commit the vaulted pem file

### Routing (set up an A record)

Now the the server exists, you'll need to go to wherever the domain's nameservers are handled and add an A record for your new instance. Point the A record at the EC2 server's IP Address.

## Inventory setup

In Ansible, an _"inventory"_ is essentially a server list file. Those can be found in `ansible/inventory/`. You'll need to edit the relevant inventory file that you are preparing. There are only a few things to change in there:

1. Replace every occurence of `ENTER_IP_ADDRESS` with the IP Address of the EC2 server
2. Replace every occurence of `.aws/CHANGEME.pem` with the name of the unencrypted pem file you set up earlier.
3. Make any additional adjustments as needed (that's for advanced use - typically with staging and production where the database is on a separate RDS instance rather than right on the EC2 instance - if all else fails, ask your friendly neighborhood DevOps)

## Update group_vars variables

There's a pile of variables that are available and some key ones you need to update. Here's what the directory structure for variables looks like by default:

* `ansible/group_vars/all/` - Variables that will apply to _all_ inventories (e.g. servers)
* `ansible/group_vars/development/` - Variables that will only apply to development. These override the variables in `ansible/group_vars/all/`.
* `ansible/group_vars/staging/` - Variables that will only apply to staging. These override the variables in `ansible/group_vars/all/`.
* `ansible/group_vars/production/` - Variables that will only apply to production. These override the variables in `ansible/group_vars/all/`.

## Variable files

Ansible has a lot of variables and we've broken them out into a nubmer of files. Some files are plain text while others are encrypted. Here's a run-down:

### `common.yml`

Holds a number of variables that help dictate how the project will be built and what features are enabled.

The most important variables to update in `ansible/group_vars/all/common.yml` are:

* `projects.wordpress.path` - should hold the location on the server the code/deploys should be placed. (i.e. `/srv/www/pue-service`)
* `projects.wordpress.repo` - should hold the repository where it will fetch code from

The most important variables to update in `ansible/group_vars/<inventory>/common.yml` are:

* `domain` - the domain to be used for the inventory in question (the `development/common.yml`'s `domain` variable should be set to the development domain)
* `project_repo_version` - the branch that should be checked out by default

### `databases.yml`

This holds a number of variables for MySQL. Most likely these won't need to be edited as they get their values from variables in the `vault.yml` or `wordpress.yml` variable files.

### `nginx.yml`

This holds a number of variables for nginx. Most likely these won't need to be edited unless there are some advanced nginx needs. When in doubt, talk it through with DevOps.

### `php.yml`

This holds a number of variables for PHP. Most likely these won't need to be edited unless there are some advanced PHP needs. When in doubt, talk it through with DevOps.

### `s3.yml`

This holds variables for S3 Uploads. If the project is using S3 Uploads, you'll want to edit the relevant inventory's group_vars file to enable it and set the appropriate variables. _Note: some S3 Uploads variables should live in the vault.yml file_.

### `smtp.yml`

This holds variables for SMTP (username). The SMTP password should live in the `vault.yml` file.

### `tribe.yml`

This should hold Tribe-specific variables. Glomar state for one. Add more here if desired (remember, if the variable is global across all inventories, add it to `group_vars/all/`).

### `vault.yml`

This won't exist by default. You'll need to make these files and encrypt them with your `.vaultpass` string.

#### Creating a `vault.yml` file

To create a `vault.yml` file, simply execute (from the `ansible/` directory):

```
ansible-vault create group_vars/<location>/vault.yml
```

#### Editing a `vault.yml` file

Since the `vault.yml` file is encrypted, you can't edit it the traditional way. Instead, you'll have to use `ansible-vault` to help you. From the `ansible/` directory, run:

```
ansible-vault edit group_vars/<location>/vault.yml
```

#### Viewing a `vault.yml` file

If you are curious what is sitting in the `vault.yml` file, you can run (from the `ansible/` directory):

```
ansible-vault view group_vars/<location>/vault.yml
```

#### Contents of `vault.yml` in `group_vars/all/`

In the `vault.yml` file of `group_vars/all/`, you will (at minimum) need a GitHub OAuth token that has access to our secured repos. This should _not_ be a personal OAuth token, but instead be `tr1b0t`'s OAuth token. You can **find that in 1Password** in the _General Systems_ Vault under the title `tr1b0t GitHub OAuth Token`. Set the contents of this `vault.yml` file to _at least_ the following (it can have more if needed):

```
---
# GitHub OAuth Token is owned by tr1b0t
vaulted_composer_github_oauth_token: "THE_TOKEN_FOR_TR1B0T"
```

#### Contents of `vault.yml` in `group_vars/<inventory>`

In each of the inventory `vault.yml` files, here's an example of what the contents should look like:

```
---
mysql_root_username: root
mysql_root_password: "CHANGEME"
wp_site_db_password: "CHANGEME"
wp_site_admin_password: "CHANGEME"

wp_site_auth_key: "CHANGEME"
wp_site_secure_auth_key: "CHANGEME"
wp_site_logged_in_key: "CHANGEME"
wp_site_nonce_key: "CHANGEME"
wp_site_auth_salt: "CHANGEME"
wp_site_secure_auth_salt: "CHANGEME"
wp_site_logged_in_salt: "CHANGEME"
wp_site_nonce_salt: "CHANGEME"

newrelic_license: "CHANGEME"
datadog_api_key: "CHANGEME"
s3_uploads_key: 'CHANGEME'
s3_uploads_secret: 'CHANGEME'
```

### `wordpress.yml`

This variable file holds all of the WordPress-specific variables. The most important variables to update in `group_vars/<inventory>/wordpress.yml` are:

* `wp_site_db_host` - point to either `localhost` or the server address of the RDS instance
* `wp_site_db_name` - the database name for the WP db
* `wp_site_db_user` - the database user for the WP db
* `wp_site_title`   - the title of the WP site (this might also be a candidate for editing in `group_vars/all/wordpress.yml`)

Give the rest of the variables a read-through and update any you feel necessary.

## Install dependencies

Our SquareOne Ansible setup relies on a number of external roles (e.g. collections of commands to be executed on the server). Some are 3rd party. Others are our own (found at [moderntribe/tribe-ansible-roles](https://github.com/moderntribe/tribe-ansible-roles)). Luckily, there's a single command that installs them all. From the `ansible/` directory, run:

```
ansible-galaxy install -r requirements.yml -c
```

## The first deploy

If you've done all of the above, you should be ready to execute your first deploy. That deploy will set up the server by installing all the relevant packages, set up the relevant services, pull the code, and run the code's build processes.

We have some more info on deploys documented in the [Executing Deploys](/docs/ansible/deploys.md) doc page. But here's the command for the first execution all the same (run this from the `ansible/` directory):

```
# This is an example initial deployment of the development inventory

ansible-playbook -i inventory/development site.yml
```
