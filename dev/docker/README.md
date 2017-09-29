# Prerequisites

1. You need to install the latest **EDGE** version of the native Docker client for your platform. For Mac you can 
download it from [here](https://store.docker.com/editions/community/docker-ce-desktop-mac). For Windows you can download it from [here](https://store.docker.com/editions/community/docker-ce-desktop-windows). If you use Linux you can use the package manager of your distribution of choice, in your case using the stable version instead of EDGE is fine.
2. Clean your environment from the old tools you were using. Make sure you don't have installed _dnsmasq_ or any other DNS server, and that you don't have any entries for **.tribe** domains in your /etc/hosts
3. **For Windows users:** [enable Hyper-V](https://docs.microsoft.com/en-us/virtualization/hyper-v-on-windows/quick-start/enable-hyper-v) and [enable Bash](https://msdn.microsoft.com/en-us/commandline/wsl/install_guide?f=255&MSPPError=-2147217396). If you're on an older Windows version or don't want the Linux subsystem for some weird reason, install [Cygwin](https://www.cygwin.com/).
4. Update your computer's primary DNS server to `127.0.0.1` and the secondary to `8.8.8.8`. Instructions for [Mac](http://osxdaily.com/2015/12/05/change-dns-server-settings-mac-os-x/), [Windows](https://www.windowscentral.com/how-change-your-pcs-dns-settings-windows-10) and [Linux](https://support.rackspace.com/how-to/changing-dns-settings-on-linux/). 

# Your first run ever

1. Make sure you have an updated clone of Square One somewhere handy. If you don't… why??? Like, really, c'mon.
2. On a terminal window that can run `bash`, go to your Square One checkout's `dev/docker/global` folder. 
3. **Only if you are on Linux:** Figure out what your Docker service IP address is. If you look at the result of `ifconfig` you should see an interface named `Docker0` or `Docker1` or similar. Take note of the `inet addr` field and create a `.env` file on the `global` folder with the contents of `.env.sample` but replacing `0.0.0.0` with your Docker IP. Tell Daniel or Jonathan what that IP is, maybe we can find some commonalities and automate the process for Linux too.
4. Run `bash start.sh`. It's going to take a bit. Only this time, I promise.
5. One thing `bash.sh` did was to create a certificate on your local machine for a Central Authority so you can sign "real" SSL certificates. This is a bit messy, but the alternative would be having all of that as part of the repo, and it's quite insecure. Any potential attacker with access to our repo could basically fake every secure site on your computer. Whatever. This is better. Trust me. Obviously no one trusts you as a CA yet, so you need to tell your computer to trust it. If you're on OSX, congratulations. You're done. Use this time to go give Jonathan a taco for automating it for you. If you're on Windows [follow this](http://www.cs.virginia.edu/~gsw2c/GridToolsDir/Documentation/ImportTrustedCertificates.htm) or [this](https://unix.stackexchange.com/questions/90450/adding-a-self-signed-certificate-to-the-trusted-list) if you're on Linux.
6. Open your mysql client (SequelPro, HeidiSQL, etc). Try to connect to your new MySQL server with this info: `host: mysql.tribe - port: 3306 - username: root - password: password`. Open a browser and go to http://mailhog.tribe. Hopefully it all works. If it doesn't try clearing your OS DNS cache. If it still doesn't work submit a bug report (ie: talk to Daniel or Jonathan).
7. You're done. You can go back to the terminal and run `bash stop.sh` or just leave global running if you're planning on doing some work.


# Your first run for each new project

1. Clone the the repo for the new project
2. Find domain your BE lead chose for the project when they set it up the first time on `dev/docker/docker-compose.yml`. You can find it in the line that reads: `- VIRTUAL_HOST={something}.tribe` in the `website` service. Take note of that domain.
3. Go to *your main Square One* clone, the one you use to run the global containers from. Go to the `dev/docker/global folder` and run `bash cert.sh something.tribe` obviously using the right domain you found in the previous point.
4. Connect to MySQL and create a new DB for it, or import an existing one. Remember the connection info for your local-config.php is: `host: mysql.tribe - port: 3306 - username: root - password: password`
5. **For FE Devs:** To use Browsersnyc, follow the instructions in the `local-config-sample.json`. You will need to add your local path to the global certs directory (where your certs from step 3 are installed) to the `certs_path` parameter.

# Day to Day usage

1. Make sure you are running your Global containers from your main Square One clone. If you're not just run `bash start.sh`. If you're unsure just run `bash stop.sh` and then `bash start.sh`. Wait until they start (about 10 seconds). You don't really need to stop this often, you can just leave it running and start it again after you restart you computer.
2. Go to the folder of the project you want to work on and in `dev/docker` you'll find another `start.sh`. Run it with `bash start.sh`. That's it. You should be able to load the project in the browser.
3. Get into the habit of having a terminal window running `bash logs.sh` from the same folder. There's important information there. Most notably, PHP errors.
4. To use WPCLI commands from outside of the project container, use `wp.sh` from `dev/docker`. Usage example: `bash wp.sh media regenerate`. This script can handle all commands and arguments available from WPCLI. `--allow-root` is a default flag executed from within the script.
5. You don't *need* to `bash stop.sh` when you stop working, but it's not a bad idea to do so and to start it again next time, so if someone on the team pushed changes to the infrastructure you'll get them automatically.
6. **Just for BE leads:** `docker-compose up` doesn't look for changes on the PHP Dockerfile, so if you change how the PHP image is built (ie: add an extension or update PHP version) you need to instruct your team to go to the docker folder on the project and run `docker-compose build`. This is probably not going to happen often.

# If you're creating a new project (ie: forking Square One)

1. Recognize how silly is to keep doing this manually and go insist Daniel or Jonathan it's time to automate this.
2. Create your Square One fork as usual, change git remotes to the new project, etc
3. Delete `dev/docker/global`
4. Change .projectID on `dev/docker` to a representative slug for the project.
5. Replace `square1.tribe` with whatever domain you want for the project in `docker-composer.yml` and `phpdocker/nginx/nginx.conf`
6. That's it. Be happy.

