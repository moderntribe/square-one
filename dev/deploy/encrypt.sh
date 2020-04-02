#!/bin/bash

ansible-vault --vault-password-file=.vaultpass encrypt .host/config/dev.cfg --output=.host/config/dev.cfg.vaulted
ansible-vault --vault-password-file=.vaultpass encrypt .host/config/staging.cfg --output=.host/config/staging.cfg.vaulted
ansible-vault --vault-password-file=.vaultpass encrypt .host/config/production.cfg --output=.host/config/production.cfg.vaulted
ansible-vault --vault-password-file=.vaultpass encrypt .host/config/common.cfg --output=.host/config/common.cfg.vaulted
ansible-vault --vault-password-file=.vaultpass encrypt .host/ansible_rsa --output=.host/ansible_rsa.vaulted
ansible-vault --vault-password-file=.vaultpass encrypt .host/ansible_rsa_password --output=.host/ansible_rsa_password.vaulted
