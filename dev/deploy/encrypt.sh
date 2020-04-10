#!/bin/bash

ansible-vault encrypt --vault-password-file=.vaultpass .host/config/dev.cfg --output=.host/config/dev.cfg.vaulted
ansible-vault encrypt --vault-password-file=.vaultpass .host/config/staging.cfg --output=.host/config/staging.cfg.vaulted
ansible-vault encrypt --vault-password-file=.vaultpass .host/config/production.cfg --output=.host/config/production.cfg.vaulted
ansible-vault encrypt --vault-password-file=.vaultpass .host/config/common.cfg --output=.host/config/common.cfg.vaulted
ansible-vault encrypt --vault-password-file=.vaultpass .host/ansible_rsa --output=.host/ansible_rsa.vaulted
ansible-vault encrypt --vault-password-file=.vaultpass .host/ansible_rsa_password --output=.host/ansible_rsa_password.vaulted
