#!/bin/bash

ansible-vault --vault-password-file=.vaultpass decrypt .host/config/dev.cfg.vaulted --output=.host/config/dev.cfg
ansible-vault --vault-password-file=.vaultpass decrypt .host/config/staging.cfg.vaulted --output=.host/config/staging.cfg
ansible-vault --vault-password-file=.vaultpass decrypt .host/config/production.cfg.vaulted --output=.host/config/production.cfg
ansible-vault --vault-password-file=.vaultpass decrypt .host/config/common.cfg.vaulted --output=.host/config/common.cfg
ansible-vault --vault-password-file=.vaultpass decrypt .host/ansible_rsa.vaulted --output=.host/ansible_rsa
ansible-vault --vault-password-file=.vaultpass decrypt .host/ansible_rsa_password.vaulted --output=.host/ansible_rsa_password
