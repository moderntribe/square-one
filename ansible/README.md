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

## Deployments

For info on deployments, check out our [Ansible docs for deployments](/docs/ansible/deploys.md).

## Tips

Avoid putting tasks directly in playbooks. Instead, find an appropriate role (or make a new one) to contain the tasks.

## Notes

The `local-config.php` file that gets used comes from the `roles/wordpress/templates` directory.