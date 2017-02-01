# Deploy Instructions

## Decrypting necessary files

We're not using Ansible for the deploy, but using vault for some files.  Create your `.vaultpass` file.  You can find the vault key at https://central.tri.be/projects/systems/wiki/Hosting_Info#Ansible-Vault-Key:

```
echo "[ vault key ]" > .vaultpass
```

```
ansible-vault --vault-password-file=.vaultpass decrypt .wpengine/ansible_rsa.vaulted --output=.wpengine/ansible_rsa
ansible-vault --vault-password-file=.vaultpass decrypt .wpengine/ansible_rsa_password.vaulted --output=.wpengine/ansible_rsa_password
ansible-vault --vault-password-file=.vaultpass decrypt .wpengine/config/staging.cfg.vaulted --output=.wpengine/config/staging.cfg
ansible-vault --vault-password-file=.vaultpass decrypt .wpengine/config/production.cfg.vaulted --output=.wpengine/config/production.cfg
```

## Run a deploy

```
./deploy.sh [staging|production]
```

You can optionally specify a branch name (defaults to server/staging or server/production, as appropriate). E.g.:

```
./deploy.sh staging sprint/1
```

This will ask you for an ssh key passphrase that most platforms can save after entering it the first time.  You can find the passphrase in `.wpengine/ansible_rsa_password`.

# Setup Instructions

We're not using Ansible for the deploy, but using vault for some files.  Create your `.vaultpass` file.  You can find the vault key at https://central.tri.be/projects/systems/wiki/Hosting_Info#Ansible-Vault-Key:

```
echo "[ vault key ]" > .vaultpass
```

* Run `init.sh` to build the files for the project.
* Edit the production and staging config files in `.wpengine/config`.
* Run `encrypt.sh` to re-encrypt those files after you edit them.
* Give the ssh key from `.wpengine/ansible_rsa.pub` access to push to the WP Engine git repo.
