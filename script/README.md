# Scripts To Rule Them All

## TL;DR How to deploy locally

These set of scripts should allow you be to build and deploy this package from you local machine or from any CI system. Here are some basic instructions on how to deploy a Square One project from a docker container:

* If you want to execute this from inside a container:
```shell
NODE_VERSION=12.13.1
$ docker run -it --rm -v $(pwd):/app node:${NODE_VERSION}-alpine /bin/sh
cd /app/
eval $(ssh-agent) ; ssh-add ${project_private_key.key} # Add private key to SSH Agent
```
* If you need composer, find the Square One .env API keys in [1Password](https://moderntribe.1password.com/vaults/all/allitems/ydscklaxsrcy3l6rwoqoqz4xwa).

```shell
./script/cibootstrap
./script/cibuild
./script/cideploy [dev|staging|production]
```

That's it.

## TL;DR Setup

* For examples on how to deploy to WPEngine, Ansible, Docker or a simple Linux box you may on check the [Devops Repository](https://github.com/moderntribe/DevOps/tree/master/Deploy%20Scripts).
* Move your sample config file from `local.ini.sample` to `local.ini`.

## The scripts

This is a set of boilerplate scripts that describe how every project in Modern Tribe can be used.
And it was **copied** inspired by [this project](http://githubengineering.com/scripts-to-rule-them-all/). While these patterns can work for projects based on any framework or language, these particular examples works to build a clean Square One project.

## The Idea

If your scripts are normalized by name across all of your projects, your
contributors only need to know the pattern, not a deep knowledge of the
application. This means they can jump into a project and make contributions
without first learning how to bootstrap the project or how to get its tests to
run.

The intricacies of things like test commands and bootstrapping can be managed by
maintainers, who have a rich understanding of the project's domain. Individual
contributors need only to know the patterns and can simply run the commands and
get what they expect.

## The Scripts

Each of these scripts is responsible for a unit of work. This way they can be
called from other scripts.

This not only cleans up a lot of duplicated effort, it means contributors can do
the things they need to do, without having an extensive fundamental knowledge of
how the project works. Lowering friction like this is key to faster and happier
contributions.

The following is a list of scripts and their primary responsibilities.

### script/cibootstrap

[`script/cibootstrap`][cibootstrap] is used solely for fulfilling dependencies of the project or the continuous integration server (Jenkins, Travis, Github Actions...).

This can mean Linux programs, npm packages, Homebrew packages, PHP versions, Git submodules, etc.

The goal is to make sure all required dependencies are installed.


### script/setup

[`script/setup`][setup] is used to set up a project in an initial state.
This is typically run after an initial clone, or, to reset the project back to
its initial state. For Square One, it's when we create the initial Private Keys for SSH usage and create the sample config files.

This is also useful for ensuring that your bootstrapping actually works well.

### script/cibuild

[`script/cibuild`][cibuild] is used for your continuous integration server.
This script is typically only called from your CI server.

You should set up any specific things for your environment here before your tests
are run. Your test are run simply by calling [`script/test`][test].

### script/cideploy

[`script/cideploy`][cideploy] is used for your continuous integration server.
This script is also typically only called from your CI server and it's used to deploy your app to a specific environment.

```
./script/cideploy [dev|staging|production]
```

[cibootstrap]: script/cibootstrap
[setup]: script/setup
[cibuild]: script/cibuild
[cideploy]: script/cideploy
