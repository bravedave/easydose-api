### DVC Simple Authenticating Template

This is a template for using the DVC Framework

## Features
1. Quick to setup - easy PHP development environment
1. Simple Authenticating System
1. SQLite3
1. Clean URL's

## Running this demo
1. Creates a SQLite3 database
2. Populates it with basic data
3. **DOES NOT** lock down the system
   * but if you select settings > lockdown and save
     * you will require a username/password to gain access
     * default user/pass = **admin** / **admin**

## Install
To use DVC on a Windows 10 computer (Devel Environment)
1. Install PreRequisits
   * Install PHP : http://windows.php.net/download/
      * Install the non threadsafe binary
      * by default there is no php.ini (required)
        * copy php.ini-production to php.ini
        * edit and modify (uncomment)
          * extension=php_fileinfo.dll
          * extension=php_sqlite3.dll
   * Install Git : https://git-scm.com/
     * Install the *Git Bash Here* option
   * Install Composer : https://getcomposer.org/

1. Clone or download this repo
   * Start the *Git Bash* Shell
     * Composer seems to work best here, depending on how you installed Git
   * MD C:\Data\ && CD C:\Data
   * clone:
      * git clone https://github.com/bravedave/dvc-auth
   * or download as zip and extract
      * https://github.com/bravedave/dvc-auth/archive/master.zip
   * or setup as new project
     * `composer create-project --prefer-dist --stability=dev --repository='{"type":"vcs","url":"https://github.com/bravedave/dvc-auth"}' bravedave/dvc-auth my-project @dev`

1. optionally change the name and change to the folder
   * cd dvc-auth
1. run *composer install*

To run the demo
   * Review the run.cmd
     * The program is now accessible: http://localhost
     * Run this from the command prompt to see any errors - there may be a firewall
       conflict options to fix would be - use another port e.g. 8080
