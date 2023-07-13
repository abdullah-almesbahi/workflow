
## Theme development

Workflow Application uses [Grunt](http://gruntjs.com/) for compiling LESS to CSS, checking for JS errors, live reloading, concatenating and minifying files.

### Install Grunt

**Unfamiliar with npm? Don't have node installed?** [Download and install node.js](http://nodejs.org/download/) before proceeding.

From the command line:

1. Install `grunt-cli` globally with `npm install -g grunt-cli`.
2. Navigate to backend/web directory, then run `npm install`. npm will look at `package.json` and automatically install the necessary dependencies.

When completed, you'll be able to run the various Grunt commands provided from the command line.

**N.B.** 
You will need write permission to the global npm directory to install `grunt-cli`. You will also likely have to be using an elevated terminal or prefix the command with `sudo`, i.e., `sudo npm install -g grunt-cli`. 

We also advise against running as root user. NPM deliberately uses limited privileges when executing certain commands such as those included in the Roots post-install process, and when this happens to the root user, any file system objects that are not expressly writable by the root user will fail to write during the execution of the command. These might include directories such as `/var/www` or `/home/someotheruser`. If you're running as root and have problems, don't say we didn't warn you.

### Available Grunt commands

* `grunt dev` — Compile LESS to CSS, concatenate and validate JS
* `grunt watch` — Compile assets when file changes are made
* `grunt prod` — Create minified assets that are used on non-development environments