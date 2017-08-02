# Node

This system uses npm for dependency management for front end assets. 

To find out which version you should be using, check the .nvmrc file at root. We recommend the version manager [nvm](https://github.com/creationix/nvm) to manage your node versions.
 
 With that installed you can just `nvm use` in the root of this folder before beginning dev tasks that involve node and it will put you on the correct version, after it has been installed by you initially.
 
To install your dependencies it is recommended you use Yarn, a global cli tool you install that fetches deps from npm, bower or other repos. First install globally in your currently required version of node if not already in place: [install yarn](https://yarnpkg.com/en/docs/install).

Then simply `yarn install` in the root of this project.
 
 ## Table of Contents
 
 * [Overview](/docs/build/README.md)
 * [Node](/docs/build/node.md)
 * [Grunt Tasks](/docs/build/grunt.md)