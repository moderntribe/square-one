#!/bin/bash

ansible-vault decrypt --vault-password-file=.vaultpass .host/config/dev.cfg.vaulted --output=.host/config/dev.cfg
ansible-vault decrypt --vault-password-file=.vaultpass .host/config/staging.cfg.vaulted --output=.host/config/staging.cfg
ansible-vault decrypt --vault-password-file=.vaultpass .host/config/production.cfg.vaulted --output=.host/config/production.cfg
ansible-vault decrypt --vault-password-file=.vaultpass .host/config/common.cfg.vaulted --output=.host/config/common.cfg
ansible-vault decrypt --vault-password-file=.vaultpass .host/ansible_rsa.vaulted --output=.host/ansible_rsa
ansible-vault decrypt --vault-password-file=.vaultpass .host/ansible_rsa_password.vaulted --output=.host/ansible_rsa_password
