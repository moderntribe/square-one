# Scripts to run them all

Set of scripts that should be default for all our environments, to make it easier for any deploy process.

# TODO:
* This is just a barebones set of scripts

## Prerequisites

Make sure you have a the private key added by:

```shell
$ ssh-add ${project_private_key.key}
```

* Certificate: You can get it from the project 1Password.
* Find Square One .env API keys in [1Password](https://moderntribe.1password.com/vaults/all/allitems/ydscklaxsrcy3l6rwoqoqz4xwa).

## Run a deploy

At the root folder of the project, run:

```shell
./script/cibuild
./script/cideploy [dev|staging|production]
```

This will build and deploy your current folder to the specified environment.