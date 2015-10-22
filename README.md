LOGman Komento plugin
========================

Plugin for integrating [Komento](http://stackideas.com/komento/) with LOGman. [LOGman](http://joomlatools.com/logman) is a user analytics and audit trail solution for Joomla.

## Installation

### Composer

You can install this package using [Composer](https://getcomposer.org/) by simply going to the root directory of your Joomla site using the command line and executing the following command:

```
composer require joomlatools/logman-komento:*
```

Run composer install.

### Package

For downloading an installable package just make use of the **Download ZIP** button located in the right sidebar of this page.

After downloading the package, you may install this plugin using the Joomla! extension manager.

## Usage

After the package is installed, make sure to enable the plugin and that both LOGman and Komento are installed.

## Supported activities

The following Komento actions are currently logged:

### Comments

* Add
* Delete
* Publish
* Unpublish

## Limitations

* Delete, publish and unpublished actions are only logged when deleting items from the fronted interface. The latest Komento stable release (2.0.5) does not trigger events from these actions when performed from the backend interface.
