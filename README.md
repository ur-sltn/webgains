# Webgains integration for Magento 1

Adds ability to export a product feed for Webgains via a scheduled cron and a Webgains tracking tag to all successful
orders.

Note that while this extension is now considered *stable*, it is strongly
recommended that it be tested on a development/staging site before deploying
on a production site.

## Features

  * Export product feed in CSV / XML format
  * Webgains affiliate tracking tag for successful orders (works on one page checkout and multi address shipping checkout)

## Compatibility

  * Magento Community Edition 1.7.0.1 - 1.9.2.4
  * Magento Enterprise Edition 1.11.x - 1.14.2.4

## Installation

**Method 1:**

  * Install via modman:

```sh
modman init
modman clone https://github.com/ur-sltn/webgains.git
```

  * Allow template symlinks in Magento admin:

```
Admin -> System -> Configuration -> [ADVANCED] Developer -> [Template Settings] Allow Symlinks: Yes
```

## Admin Configuration

```
Admin -> System -> Configuration -> [UR-SLTN LTD] Webgains
```

## [Change Log] (CHANGELOG.md)
