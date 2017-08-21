While I write the real docs, some notes for JB and whoever wants to test this:

1) Make sure you're running the latest EDGE version of Docker
2) Run global from your main square-one clone, keep it up to date
3) Run project specific from your proyect. Duh!
4) The only setting you need to make on your computer is: Primary DNS = 127.0.0.1 / Secondary DNS = 8.8.8.8 (so your computer still works when you are not running the global compose)
5) Any service you want to access from your local directly needs to either be routed via the proxy (see mailhog or rabbitmq) or share ports with localhost (see mysql) 

Have fun!

# Install
## Instalation on OSX
## Instalation on Windows

# Usage
## Global 
## Project Specific

# Tools
## Mailhog
## Proxy
## xDebug

# Handy docker commands and git aliases

# FAQ
## How to add PHP specific configurations
## How to add services that can be reached out by internal domain name
## "LookupError: unknown encoding: cp65001" on Windows


