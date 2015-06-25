# Ecostud.io

## Setup

First of all, you will need Node.js, NPM and Grunt CLI installed.

After cloning the repo, you need to pull in dependencies:

```shell
composer install
npm install
bower install
```

Then you should run the Grunt task that sets up the show:

```shell
grunt setup
```

## Build

To build, you can simply run:

```shell
grunt
```

The site will be ready to deploy in the `dist` directory.
