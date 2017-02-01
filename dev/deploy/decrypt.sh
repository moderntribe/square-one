#!/bin/bash

ansible-vault --vault-password-file=.vaultpass decrypt .wpengine/config/staging.cfg.vaulted --output=.wpengine/config/staging.cfg
ansible-vault --vault-password-file=.vaultpass decrypt .wpengine/config/production.cfg.vaulted --output=.wpengine/config/production.cfg
ansible-vault --vault-password-file=.vaultpass decrypt .wpengine/ansible_rsa.vaulted --output=.wpengine/ansible_rsa
ansible-vault --vault-password-file=.vaultpass decrypt .wpengine/ansible_rsa_password.vaulted --output=.wpengine/ansible_rsa_password