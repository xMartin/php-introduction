PHP introduction
=====================

Source files for <http://berlinonline.github.io/php-introduction/>.

## Setup

* clone the repo
* install composer (if you don't have it already)
* run `composer install`

## Development

* `vendor/bin/sculpin generate --watch --server`: builds the dev version of the site, `--watch` will auto-regenerate on changes, `--server` will start a dev server

## Deployment

Run `./deploy-gh-pages` to rebuild the live site and push it to gh-pages.
