#!/bin/bash

ansible-vault --vault-password-file=.vaultpass encrypt .wpengine/config/staging.cfg --output=.wpengine/config/staging.cfg.vaulted
ansible-vault --vault-password-file=.vaultpass encrypt .wpengine/config/production.cfg --output=.wpengine/config/production.cfg.vaulted
ansible-vault --vault-password-file=.vaultpass encrypt .wpengine/config/common.cfg --output=.wpengine/config/common.cfg.vaulted
ansible-vault --vault-password-file=.vaultpass encrypt .wpengine/ansible_rsa --output=.wpengine/ansible_rsa.vaulted
ansible-vault --vault-password-file=.vaultpass encrypt .wpengine/ansible_rsa_password --output=.wpengine/ansible_rsa_password.vaulted