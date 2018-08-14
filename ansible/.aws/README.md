# .aws direction

Place your .pem file in here and encrypt it using `ansible-vault`. That way you can commit the vaulted file.

From the `ansible/` directory, run:

```
ansible-vault encrypt WHATEVER.pem --output=WHATEVER.pem.vaulted
```
