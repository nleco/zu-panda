# PXC Web UI

## Project

The project uses Apache and PHP, jQuery, and Bootstrap.

## Setup

1. Check out the code where you want it to be.

2. Set up apache to point to the top directory of this project, NOT the `www` directory. Also make sure you have the VirtualHost set up to read .htaccess files.

3. Go to `Configs/environment/` and make a copy of `config.bootstrap.template.ini`. Then rename it to `config.bootstrap.ini` (just remove the word 'template' from the file name).  Then change the value of `environment=` to what you want it to be.

4. Restart apache.

### Dev Setup Steps

1. We use [Gulp](https://gulpjs.com/) to handle any kind of processing automatically (found in `gulpfile.js`).
    * Make sure you have node/npm installed:
    * If this is the first time you're going to run gulp, make sure to run this first:\
    `$ npm i`
    * Open a terminal in the root directory of the project and type:\
    `$ gulp`

        Make sure to leave the terminal running.

    * If you want to use [Browsersync](https://www.browsersync.io/), then set up your `hosts` file to have `pxc-web-ui.test` point to localhost. Then access your dev site via `pxc-web-ui.test:8888'. BrowserSync lets you open many different browsers and controll them all at once.


## SCSS

The CSS files are compiled from [SCSS (Sass)](https://sass-lang.com/documentation/syntax) found in the 'SCSS' directory. Don't modify the `www/css/*` files directly.

1. the _var.scss file has all variable overrides that help theme or change any imported libraries, like bootstrap.

2. main.scss is the file where we import anything else.
    * 'underscored' files are ingnored by SCSS compilers and meant for imports.

## Debugging

If you want to use the `logger()` function to output data to a log file, simply add a file named 'log.logger.log' in the root directory and make sure it is writable.  You can then tail follow this file. If the file does not exist, then the function does nothing.

## MVC

This is a simple MVC PHP framework. It was a modification of: https://github.com/ngrt/MVC_todo

To create a url path like `/foo/bar`, you would follow the `/[controller]/[action]/a/b/c` convention. This would tell the framework what code to run. You then `render()` the view file you want to use, which must be inside the folder of the same name as the Controller. Anything in the url after the controller and action are going to be parameters. If no `action` is provided, then `index` is assumed.

### Example

The url `/admin/user/1` would use the following. The URL sections will be labeled in bracekets.

1. `/Controllers/[admin]Controller.php` and the `[user]()` method within.

2. `/Views/Pages/[Admin]/[user].view.php`.
    * the view file can include files, found in `/Views/Includes/`. The '.inc.php' is just a convention.
    * variables in the view will be named the same as the `key` value you assigned in the controller.

#### Controller
``` php
// /Controllers/adminController.php
class adminController extends Controller
{
    function user()
    {
        // set view data all at once.
        // this will be appended to variable list.
        $d['variable1'] = '1';
        $d['variable2'] = '2';
        $this->set($d);

        // or individually
        $this->assign('variable1', 1);
        $this->assign('variable2', 2);

        // will render the view with above data.
        // the parameter value 'user' will refer to the view
        $this->render("user");
    }
}
```
#### View
``` php
// /Views/Pages/Admin/user.view.php
this is variable1: <?php echo $variable1; ?> <br/>
this is variable2: <?php echo $variable2; ?>
```

#### Layout

The default layout is used and is under `/Views/Layouts/default.layout.php`.

you can change the layout in the controller by using `$this->layout = 'nameOfLayout'` to use `/Views/Layouts/nameOfLayout.layout.php`.
