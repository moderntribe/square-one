# SquareOne setup guide for Ubuntu

This is a step by step installation guide intended to get the project up and running in the shortest time possible and hopefully with none or few issues in between.

This guide has been tested on a clean installation of Ubuntu 18.04 using the minimal setup options and but it should work on previous versions of Ubuntu as well.

# Steps

### Prerequisites

1. Get package list up to date
```
sudo apt-get update
```

2. Install mysql-client
```
sudo apt-get install mysql-client
```

3. Install Git with default packages
```
sudo apt-get install git
```

4. Install Python (required by some node package)
```
sudo apt-get install python
```

5. Install CURL (if you don't already have it). [View source](https://linuxhint.com/install-curl-on-ubuntu-18-04/)
```
sudo apt-get install curl
```

6. Install network tools
```
sudo apt-get install net-tools
```

7. Install the ca-certificates package
```
sudo apt-get install ca-certificates
```

8. Install certificate management tool. [View source](https://leehblue.com/add-self-signed-ssl-google-chrome-ubuntu-16-04/)
```
sudo apt-get install libnss3-tools
```

9. Install NVM (Node Version Manager). [View source](https://github.com/creationix/nvm) - read the instructions to make sure the command works. Close the terminal and open a new one after installing nvm.
```
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.11/install.sh | bash
```

10. Install the Node version specified on the .nvmrc
```
nvm install {version number}
```

11. Install gulp-cli globally. [View source](https://github.com/gulpjs/gulp-cli)
```
npm install gulp-cli -g
```

12. Install Yarn. [View source](https://linuxize.com/post/how-to-install-yarn-on-ubuntu-18-04/)
  
	1. The first step is to enable the Yarn repository. Start by importing the repository’s GPG key using the following curl command:
	```
	curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
	```

	2. Add the Yarn APT repository to your system’s software repository list by typing:
	```
	echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
	```

	3. Once the repository is added to the system, update the package list and install Yarn, with
	```
	sudo apt-get update
	```

	4. Install yarn skipping nodejs install because we're using nvm
	```
	sudo apt-get install --no-install-recommends yarn
	```

13. Install Docker using the repository. [View source](https://docs.docker.com/v17.09/engine/installation/linux/docker-ce/ubuntu/)

    1. Install packages to allow apt to use a repository over HTTPS.
	```
	sudo apt-get install \
	apt-transport-https \
	software-properties-common
	```

    2. Add Docker’s official GPG key.
	```
	curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
	```

    3. Optionally - Verify that you now have the key with the proper fingerprint 9DC8 5822 9FC7 DD38 854A E2D8 8D81 803C 0EBF CD88, by searching for the last 8 characters of the fingerprint using the following command.
	```
	sudo apt-key fingerprint 0EBFCD88
	```

    4. Use the following command to set up the stable repository. You always need the stable repository, even if you want to install builds from the edge or test repositories as well.
	```
	sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
	```

    5. Update package index.
	```
	sudo apt-get update
	```

    6. Install the latest version of Docker CE. Any existing installation of Docker is replaced.
	```
	sudo apt-get install docker-ce
	```

    7. Verify that Docker CE is installed correctly by running the hello-world image.
	```
	sudo docker run hello-world
	```

    8. There seems to be an issue with docker and permissions, so executing the following commands will save you from that headache. [View source](https://github.com/docker/compose/issues/4181)
	```
	sudo usermod -aG docker $USER
	newgrp docker
	```

    10. Restart

13. Install Docker Compose. [View source](https://docs.docker.com/compose/install/)

    1. Download the latest version of Docker Compose.
	```
	sudo curl -L "https://github.com/docker/compose/releases/download/1.22.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
	```

    2. Apply executable permissions to the binary.
	```
	sudo chmod +x /usr/local/bin/docker-compose
	```

    3. Optionally - install command completion for the bash and zsh shell. [View source](https://docs.docker.com/compose/completion/)

    4. Test the installation.
	```
	docker-compose --version
	```
    <!-- 0.
	```
	``` -->

### OS Configuration

1. Disable DNS Cache

	1. Open Network Manager configuration file.
	```
	sudo gedit /etc/NetworkManager/NetworkManager.conf
	```

	2. under [main] section comment the following entry by adding the # symbol to start of line like below (if the line exists), save the configuration file and close the editor.
     ```
	 #dns=dnsmasq
	 ```

	3. Update your computer's primary DNS server through the network's graphical interface.
	```
	127.0.0.1, 1.1.1.1, 8.8.8.8 
	```

	4. Execute following commands to restart both the network-manager and networking services.
    ```
	sudo service network-manager restart
    sudo service networking restart
	```

	1. Restart

### Setup docker image

Make sure you have setup your ssh keys previously

1. Open a terminal and ensure that bash is installed
```
command -v bash # => /bin/bash
```

2. Clone the SquareOne repo to your development machine. Go to your worskpace directory, 
```
git clone {repository url}
```

3. Open a terminal in the cloned repository's directory in order to copy the sample config files
```
cp local-config-sample.json local-config.json
cp dev/docker/global/.env{.sample,}
```

4. Use ifconfig to determine your Docker service IP address. The output should include an \interface named Docker0 or Docker1 or similar. Take note of the inet addr; that IP address should replace 0.0.0.0 in your dev/docker/global/.env file.

5. Install required packages
```
yarn install
```

6. Start your global container, it will take a while the first time since it is downloading/installing stuff. Note: there might be an error saying something like "Creating tribe-dns-external ... error" ignore it, it will go away later.
```
npm run docker:global:start
```

7. Disable the systemd-resolved service
```
sudo systemctl disable systemd-resolved.service
```

8. Stop the systemd-resolved service
```
sudo service systemd-resolved stop
```

9. Stop your global container
```
npm run docker:global:stop
```

10. Restart

11. Start your global container. Yes, you need to start your global container before starting to work.
```
npm run docker:global:start
```

# Troubleshooting

### 1. After installing the global container, I can't access the internet at all. Or: I can only access the internet when the global container is on.

1. Ensure that the resolvconf is installed.
```
sudo apt update && sudo apt install resolvconf
```

2. Create a resolv.conf head file. This will place any content above any auto generated `/etc/resolv.conf` content.
```
sudo nano /etc/resolvconf/resolv.conf.d/head
```
with following contents:
```
nameserver 127.0.0.1
nameserver 1.1.1.1
nameserver 1.0.0.1
```
Note: You can use your preferred nameservers here, as long as `127.0.0.1` is at the top. It's also possible NetworkManager will provide them automatically via DHCP.
3. Start the service and see if it worked
```
sudo systemctl start resolvconf.service
```
4. Verify `/etc/resolv.conf` contains the proper nameservers

`cat /etc/resolv.conf`

Should display the following at the top of the file:

nameserver 127.0.0.1
nameserver 1.1.1.1
nameserver 1.0.0.1
4. Now restart your computer.
