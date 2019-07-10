# Getting started with SquareOne

This section is for folks who aren't familiar with SquareOne and are looking for a place to start.  If you are looking for a deeper dive into SquareOne and it's internals read the [documentation](/docs/README.md).

## Table of Contents
* [SquareOne Overview](#square-one-overview)
	* [General](#general)
	* [DevOps](#devops)
	* [Front-End / UI](#frontend-ui)
	* [Backend](#backend)
* [Getting started Guides](#getting-started-guides)

## SquareOne Overview <a name="square-one-overview"></a>

There are a number of starter frameworks for WordPress, yet few—if any—of the public systems are suitable for enterprise-level projects. SquareOne is the product of years of constant evolution from the Modern Tribe team. 

SquareOne is comprised of best practices combined across all of our development disciplines, DevOps, Back-End, Front-End, and UI.  It is has been built on top of WordPress as an enhancement to provide many benefits of modern development toolsets and workflows that WordPress, being a 10+ year old platform, has not been able to adopt into its core. The framework includes common starter plugins and supports a wealth of utilities, scales effortlessly, utilizes best practices top-to-bottom, and supplies a system that accelerates development.

We stand by WordPress as the first in class CMS, and paired with SquareOne it can handle enterprise bespoke application needs gracefully. SquareOne is a semi-private project we make available to a number of customers who have come to adopt it on their own projects. We love working with the tool itself, and we’re just as proud to hand it off to your team.

## General <a name="general"></a>

### Battle Tested
SquareOne is battle tested at various scales from a simple brochure site to a site handling millions of hits a day. It supports fully cached sites, dynamic sites, complex applications, API headless,  and commerce sites with proven success.

### Coding Standards
SquareOne follows the WordPress Coding Standards with some enhancements for using some of the latest technology like PHP7+, ES6, and more.

### SOLID Principle
SquareOne has been architected to follow the [SOLID](https://en.wikipedia.org/wiki/SOLID) principles. This allows for the use of modern architecture, OOP based practices, testability, and more. 

### Documented
SquareOne has embedded docs that cover every part of the system. In addition, inline documentation is a standard, including DocBlocks with @action &  @filter references.

## DevOps <a name="devops"></a>

### SquareOne Local
We’ve built our own Docker-based local development environment. It allows for the local dev environment to be managed by code so everyone can keep infrastructure in sync. It’s comprised of a Global container stack that manages DNS and SQL, and the Project Container stack that runs the HTTP container, caching layer, and other required services for the project. This stack is optional, but makes it easy to get up and running with identical environment quickly.
	
### Security
SquareOne also has years of security best practices bundled and our coding standards require heavy use of validation, sanitation, and nonce to verify any actions. It also bundles an Apache and Nginx configurations that enforces many best practice security policies around WordPress. If project secrets are required, we leverage environment variables to keep them safe.

### Deployments
SquareOne bundles infrastructure as code and supports various deployment methods. It can be integrated with a CI/CD tool or be deployed manually. SquareOne can be configured to deploy for common managed hosts (WP Engine, Pantheon) or for a custom setup.

## Front-End / UI <a name="freontend-ui"></a>

### Modern Build Tools
Front-end development moves fast and we regularly review and adopt industry leading tools into our workflows for enhancements in efficiency and quality. Some of the build tools SquareOne currently uses are:

* NPM / Yarn - Node Package Managers
* Grunt / Gulp - Build and Task running
* Webpack - Assets Bundler 
* PostCSS - Pre-processing tool, provides various CSS enhancements for things like Mixins, functions, variables, and linting to CSS.
* Babel - Backwards compatible ES6 Support
* Linting - Code quality and Syntax enforcement 

These build tools provide several ways to package and bundle the front-end assets of the site. This includes Dev and Prod release bundles that optimize for each scenario. 

#### BEM
We use Block Element Modifier (BEM) CSS class conventions for clear semantics, modular reusable components, and flexibility. 

#### Kitchen Sink / Forms
SquareOne comes with a Kitchen Sink that accounts for all standard styling used throughout the site. This style guide is a reference tool that helps maintain consistency of content presentation. This sink bundles all typography, interactive elements like buttons, media, and form inputs.

#### Accessibility
SquareOne core templates and components are accessible. We also bundle ally.js with our front-end dev build for accessibility feedback in real-time during development.

#### Ready for React (or any modern JS framework)
The JS build stack allows for vanilla JavaScript or full blown React applications. You can pick the right tool for the job and even chunk JavaScript and React Apps for different parts of the site – all from the same build process.

#### Image handling
We’ve baked in some advanced image handling into SquareOne, including src-set, responsive images, lazy-loading, and have some additional S3/CDN modules for offloading media. 

#### Components / Twig
We’ve added Twig to our Front-end tech so we can cleanly separate logic and presentation. This also enforces componentization of our templates, making things more reusable and portable.

## Back-End <a name="backend"></a>

### Separation of Concerns
WordPress inherently couples its templates and logic tightly. To take a modern approach, SquareOne breaks up the business logic and data model into a Core Plugin and keeps the presentational layer separated in the Core Theme. This allows us to easily componentize and modularize templates and code for reuse and portability. It also makes our code far more testable.

### Composer / Submodules
We use composer, and in some cases submodules, to manage all our external PHP dependencies so we can version lock upgrades through code. This prevents upgrade regressions and a lighter repository for long term maintainability. This allows for some nice benefits like auto-loaders and access to the wealth of composer packages from the PHP community.

### Modern PHP Architecture
We’ve built an OOP based architecture within the Core Plugin. This allows for single purpose principle, as well as other SOLID principles. Combined with PSR-4 we create a very approachable, extendable, and maintainable standards-focused code base.

### Dependency Injection / Service Providers
We use a dependency Injection package called Pimple, which allows us to gracefully inject Objects into other classes through containers.  This makes a clean separation so we can modify, extend, mock, or replace a Class without tight coupling. This isn’t novel, but something that all Modern PHP developers have come to expect.

### Testability
We bundle the Codeception testing framework, which is built on PHPUnit, in SquareOne. We also bundle WP-Browser–built by one of our own team members–that adds helper tools for testing WordPress.  Paired with our use of Dependency Injection, this modern testing stack allows us to write unit, integration, functional, and acceptance tests for the codebase.

### Abstractions
SquareOne has been built for efficiency and reliability. There are many common features we build on every project and over time we’ve abstracted them into APIs so we scaffold and extend quickly. Here are some of the core abstractions we’ve built.
	
* Custom Post Types - Registering Custom Post Types is required for creating manageable lists of data. We’ve build an abstraction that combines with other typical needs beyond a basic post type.
* Meta Data / Custom Fields - Combined with the CPT registration is the Custom fields and Meta Data registration. 
* Relationships - Taxonomies / P2P - We’ve abstracted relationships using taxonomies and Post-to-Post, so we can create common schema needs like one-to-many, many-to-many, and many-to-one.
* Permissions - We have a custom permissions framework that allows for code based capabilities management.
* Caching - Our caching layer goes all in on Memory Caches and add basic object caching as well as APIs for caching anything we need into a high speed memory cache.
* WP-CLI - Run WordPress on the Command Line. We have integrations for running long running processes outside of WordPress, like migrations and data syncing procedures.
* Queueing - We’ve built a queuing system into WordPress for events management. This is great for deferring background processes that are expensive and not required to be JIT.
* Syndication - As an optional enhancement,  we’ve created a method of sharing content without content duplication we call syndication. This allows cross site queries and data sharing seamlessly across a large multi-site network.

### Panels
An optional Page Builder content management extension for WordPress. It’s lightyears ahead of the Core editing experience and provides Drag-and-Drop, dynamic querying, and more. In technology it’s agnostic of the CMS, but built to integrate seamlessly.

### The WordPress way

A common concern is "why not the WordPress way?", and our answer is why not both? With SquareOne, you can build the simplest theme based site OR build a modern application.

## Getting Started Guides 

### [I'm starting a new project](/docs/setup/new-project.md)

### [I'm setting up and existing project](/docs/setup/existing-project.md)
