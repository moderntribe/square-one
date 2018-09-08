# Prerequisites

* Node (8.9.3), preferrably via [NVM](https://github.com/creationix/nvm)

## Docker CE

### MacOS

[Download]((https://store.docker.com/editions/community/docker-ce-desktop-mac)) and install the latest **EDGE** version of the native Docker client for your platform.

### Linux

Linux users can use their package manager of your distribution of choice. Either **STABLE** or **EDGE** channels are fine.

### Windows

[Download]((https://store.docker.com/editions/community/docker-ce-desktop-windows)) and install the latest **STABLE** release as EDGE is [broken](https://github.com/docker/for-win/issues/1829) with the tribe-proxy container.

Enable [Hyper-V](https://docs.microsoft.com/en-us/virtualization/hyper-v-on-windows/quick-start/enable-hyper-v) and [Bash](https://msdn.microsoft.com/en-us/commandline/wsl/install_guide?f=255&MSPPError=-2147217396). If you're on an older Windows version or don't want the Linux subsystem for some weird reason, install [Cygwin](https://www.cygwin.com/).

If you have issues with Bash, use [babun](https://github.com/babun/babun) and install [babun-docker](https://github.com/tiangolo/babun-docker).

### Configuring your OS

#### DNS Configuration
Ensure that...

* `dnsmasq` is uninstalled.
* Your `etc/hosts/` file does not include **.tribe** domains

Then, update your computer's primary DNS server to `127.0.0.1` and the secondary to `1.1.1.1`. Here's a link to instructions for your...

- [Mac](http://osxdaily.com/2015/12/05/change-dns-server-settings-mac-os-x/)
- [Linux](https://support.rackspace.com/how-to/changing-dns-settings-on-linux/)
- [Windows](https://www.windowscentral.com/how-change-your-pcs-dns-settings-windows-10)
  - You may need to change the DNS servers on the vEthernet (Default Switch) adapter instead of your main adapter.

**Note** that a full restart may be necessary to resolve `*.tribe` domains.

# Docker image setup

Start by cloning the Square One repo to your development machine. Open a terminal in the repo's directory and ensure that bash is installed:

```sh
command -v bash # => /bin/bash
```

>#### Linux users
>You'll need to configure your this project's `.env` file. Start by duplicating the sample config:
>
>```sh
>cp dev/docker/global/.env{.sample,}
>```
>
>Use `ifconfig` to determine your Docker service IP address. The output should include an \interface named `Docker0` or `Docker1` or similar. Take note of the `inet addr`; that IP address should replace `0.0.0.0` in your `.env` file. Tell Daniel or Jonathan what that IP is, maybe we can find some commonalities and automate the process for Linux too.


## Install the image

```sh
npm run docker:start
```

The start command will take awhile the first time. You may run into an issue that reads like this:

```
Starting docker-compose project: global
Creating network "global_proxy" with driver "bridge"
ERROR: Pool overlaps with other one on this address space
```

This might happen if you are using, or used, a number of Docker-managed local development stacks; running `docker network prune` should solve the issue.

### SSL certificates

The first-run of the start command will install Central Authority on your local machine.  to sign trusted SSL certificates. You'll need to configure your OS to trust self-signed certificates.

#### MacOS

Congratulations. You're done. Use this time to send Jonathan (@jbrinley) a taco for automating it for you.

#### Windows

[Import trusted certificates](http://www.cs.virginia.edu/~gsw2c/GridToolsDir/Documentation/ImportTrustedCertificates.htm)

#### Linux

[Adding a self-signed certificate to the “trusted list”](https://unix.stackexchange.com/questions/90450/adding-a-self-signed-certificate-to-the-trusted-list)

### MySQL Database

|Host         |Port  |Username|Password  |
|-------------|------|--------|----------|
|`mysql.tribe`|`3306`|`root`  |`password`|

Verify that your Docker image's MySQL server is accessible with the following command:

```sh
mysql -u root -h mysql.tribe -p
$ Enter password:
```

>If you prefer a GUI client, consider [SequelPro](https://www.sequelpro.com/) or [HeidiSQL](https://www.heidisql.com/).

#### Fixing permission erros on MacOS (MySQL installed via Brew)

Installing MySQL with Brew may prevent password logins for the `root` user. Enable password login with the following:

```sh
echo '[mysqld]\ndefault-authentication-plugin=mysql_native_password' >> ~/.my.cnf
```

### Web server (.tribe domains)

Verify that you're able to navigate to http://mailhog.tribe in your web browser. If the domain isn't resolvable, consider...

- Clearing your DNS cache
- Restarting your machine
- Verifying that your DNS primary and secondary are configured
- Verfiying `npm run docker:start` executes without error
- Submitting a bug report (ie: talk to Jonathan (@jbrinley))

Connected successfully? You're done! You can go back to the terminal and run `npm run docker:stop` or just leave global running if you're planning on doing some work.


>### Setup `ctop` (optional)
>
>You can install `ctop` to monitor all your containers and get real time metrics.
>```sh
>docker run -ti --name ctop --rm -v /var/run/docker.sock:/var/run/docker.sock quay.io/vektorlab/ctop:latest -i
>```
>
>Check out the [`ctop`](https://github.com/bcicen/ctop) for more details.

# Clone a new Square One derived project

Start by cloning the repo for the new project, then initializing the submodules.

```sh
git clone git@github.com:moderntribe/{{some-project-name}}.git
cd {{some-project-name}}
git submodule update --init
```

Next, find the domain your BE lead chose for the project when they set it up the first time in `dev/docker/docker-compose.yml`.

*nix users can run the following:

```sh
cat ./dev/docker/docker-compose.yml | grep VIRTUAL_HOST
```

Windows users, search for a the line `- VIRTUAL_HOST={{something}}.tribe`.

Create a new terminal window in **your main Square One** repo, then create a certificate for the project's domain:

```sh
npm run docker:cert {{something}}.tribe
```

Connect to MySQL and create (or import) a database for the project. The connection info for your project is defined in `local-config.php`, though is commonly:

|Host         |Port  |Username|Password  |
|-------------|------|--------|----------|
|`mysql.tribe`|`3306`|`root`  |`password`|


## Setup Browsersync (optional)

Front-end developers can follow the instructions in `local-config-sample.json`.
The `certs_path` property is the full path to your `"square-one/dev/docker/global/certs"` directory.


## Daily usage

Start by running your global containers from **your main Square One clone**:

```sh
cd {{your-projects-directory}}/square-one
npm run docker:start
```

It's generally recommended to leave the container running until you're finished working for the day:

```sh
npm run docker:stop
```

Next, navigate to the project directory you're developing in. Start its Docker instance:

```sh
cd {{your-projects-directory}}/{{some-project-name}}
npm run docker:start
```

That's it. You should be able to load the project in the browser.

### Notes

#### Accessing project logs

```sh
npm run docker:logs
```

We recommend always logging while developing to see important information — most notably, PHP errors.

#### WP-CLI

To use WP-CLI commands from outside of the project container, use `wp.sh` from `dev/docker`. Usage example: `bash wp.sh media regenerate`. This script can handle all commands and arguments available from WP-CLI. `--allow-root` is a default flag executed from within the script.


### Backend leads

`docker-compose up` doesn't look for changes on the PHP Dockerfile, so if you change how the PHP image is built (ie: add an extension or update PHP version) you need to instruct your team to go to the docker folder on the project and run `docker-compose build`. This is probably not going to happen often.

# If you're creating a new project (ie: forking Square One)

1. Recognize how silly is to keep doing this manually and go insist Daniel or Jonathan it's time to automate this.
2. Create your Square One fork as usual, change git remotes to the new project, etc
3. Delete `dev/docker/global`
4. Change .projectID on `dev/docker` to a representative slug for the project.
5. Replace `square1.tribe` with whatever domain you want for the project in `docker-composer.yml` and `phpdocker/nginx/nginx.conf`
6. Replace `square1.tribe` also in wp-cli.yml
7. If you need to change the Dockerfile for building the php-fpm image, change the image name from `image: tribe-phpfpm:7.0` to whatever makes sense on your docker-compose.yml
8. That's it. Be happy.

# Multisite setup on a new project

1. After installing WordPress, run the wp-cli command
    ```
    wp core multisite-convert
    ```
    If using subdomains then use
    ```
    wp core multisite-convert --subdomains
    ```
    Then you will need to copy the output from the command into your local-config.php file and visit /wp-admin/network/setup.php to copy the changes you need in your .htaccess file
2. In your wp-config.php change
    ```
    'WP_ALLOW_MULTISITE' => tribe_getenv( 'WP_ALLOW_MULTISITE', false ),
    ```
    to
    ```
    'WP_ALLOW_MULTISITE' => tribe_getenv( 'WP_ALLOW_MULTISITE', true ),
    ```
3. In dev/docker/phpdocker/nginx/nginx.conf uncomment the two lines below by removing the # symbol at the beginning
    ```
    location @rewrites {
        rewrite /wp-admin$ $scheme://$host$uri/ permanent;
        #rewrite ^(/[^/]+)?(/wp-(admin|includes|content).*) $2 last;
        #rewrite ^(/[^/]+)?(/.*\.php) $2 last;
        rewrite ^ /index.php last;
    }
    ```
4. You may need to update this in your local-config.php and use your local domain which will be your projectID.tribe most likely
    ```
    define( 'DOMAIN_CURRENT_SITE', '{your-project-domain.tribe' );
    ```
    In your wp-config.php you will need to define the domain for the production site.
5. In your wp-config.php you also need to set
   ```
   'MULTISITE' => tribe_getenv( 'WP_MULTISITE', true ),
   ```
   and if you are using subdomains instead of paths set
   ```
   'SUBDOMAIN_INSTALL' => tribe_getenv( 'SUBDOMAIN_INSTALL', true ),
   ```
6. Restart your project's docker container by running /dev/docker/stop.sh then /dev/docker/start.sh
7. You should now have a fully functioning multisite setup.

# WP-CLI and xdebug

The `start.sh` script will attempt to symlink your WP-CLI binary to `dev/bin/wp` when starting your local container. If
this fails, you should manually symlink it with: `ln -s /path/to/wp dev/bin/wp`.

In PhpStorm, you'll need to ensure you map your `wp` symlink to the container's `/usr/local/bin/wp` path.

![PhpStorm Server Panel Screenshot](https://i.imgur.com/ZXHxLty.png)

### Usage

```
cd dev/docker
./exec.sh /bin/bash
cd /application/www/dev/docker
./wpx.sh <command>
```
Or, from your host machine, run:

```
cd dev/docker
./exec.sh /application/www/dev/docker/wpx.sh <command>
```

Example

```
cd dev/docker
./exec.sh /application/www/dev/docker/wpx.sh s1 cache-prime
```
