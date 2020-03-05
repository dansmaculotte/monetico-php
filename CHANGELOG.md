# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

## 2.0.0 (2020-03-05)


### ⚠ BREAKING CHANGES

* API has changed, see ReadMe.me

* chore(release): 2.0.0-0

* chore(release): 2.0.0-beta.0

* refactor: leave successUrl and errorUrl as submitted

* refactor(AbstractRequest): make getUrl static

* chore(release): 2.0.0-beta.1

* feat: add cart resource and unify resources

* docs: update code examples in readme

* tests: add resource test

* chore(release): 2.0.0-beta.2

* refactor: wip

* Revert "refactor: wip"

This reverts commit 7d6c1bb48195d8f9e162429cf5c56d2ca2a481c3.

* refactor: rename payment to capture

* fix: bad naming, update with camelCase

* fix: reference limit to 50 and uppercase language

* fix: return if value is not defined in setParameter method

* revert: if value is not defined in parameter

* refactor: rename capture in purchase

* refactor: change resource constructor

* refactor: change purchase response toArray method visibility

* refactor: include in context only not null resource

* fix: do not escape to unicode for context_command

* fix: add missing fields in purchase response

* docs: add purchase request

* docs: add purchase response

* chore: update dependencies

* refactor: travis.yml

* fix: missing return in baseFields

* chore(release): 2.0.0-beta.3

* docs: update usage

* docs: remove Carbon from usage examples

* refactor: cleanup assertions

* chore: update ci

### Bug Fixes

* **github:** add missing comma ([937c8f3](https://github.com/dansmaculotte/monetico-php/commit/937c8f3))
* **github:** add missing ext mbstring ([1371cf7](https://github.com/dansmaculotte/monetico-php/commit/1371cf7))


### Features

* add github workflow ([7d2aaf0](https://github.com/dansmaculotte/monetico-php/commit/7d2aaf0))


* New API methods and 3DSv2 support (#15) ([7058a8a](https://github.com/dansmaculotte/monetico-php/commit/7058a8a)), closes [#15](https://github.com/dansmaculotte/monetico-php/issues/15)

## 1.0.0 (2019-07-24)


### Bug Fixes

* adjust quotes wrapping for payment receipt output format and lowercase seal in seal validation. Resolves [#2](https://github.com/dansmaculotte/monetico-php/issues/2). ([c7a9244](https://github.com/dansmaculotte/monetico-php/commit/c7a9244))
* move carbon to dev dependencies ([5235c76](https://github.com/dansmaculotte/monetico-php/commit/5235c76))
* transform security key before seal generation ([4bc97d3](https://github.com/dansmaculotte/monetico-php/commit/4bc97d3))


### Features

* Add base files ([0419ab4](https://github.com/dansmaculotte/monetico-php/commit/0419ab4))
* add php-cs-fixer and git hooks ([1689a16](https://github.com/dansmaculotte/monetico-php/commit/1689a16))
* Add ReadMe and Licence ([b61d7ff](https://github.com/dansmaculotte/monetico-php/commit/b61d7ff))

## [2.0.0-beta.3](https://github.com/dansmaculotte/monetico-php/compare/v2.0.0-beta.2...v2.0.0-beta.3) (2019-11-30)


### Bug Fixes

* add missing fields in purchase response ([b4c633b](https://github.com/dansmaculotte/monetico-php/commit/b4c633bc3519f99cc10f927505ee551f189870c9))
* bad naming, update with camelCase ([4cc2d15](https://github.com/dansmaculotte/monetico-php/commit/4cc2d152b7409a5292840388e65e71c7d9ff2cdb))
* do not escape to unicode for context_command ([1d578b6](https://github.com/dansmaculotte/monetico-php/commit/1d578b63e5fd063d932c00547bb774073bd9e231))
* missing return in baseFields ([9f894da](https://github.com/dansmaculotte/monetico-php/commit/9f894daf806823f010d36fa07612541de4dcc6ec))
* reference limit to 50 and uppercase language ([609808d](https://github.com/dansmaculotte/monetico-php/commit/609808d6dfab7fe5024eac52cc9f0260ff4b0bba))
* return if value is not defined in setParameter method ([44af33b](https://github.com/dansmaculotte/monetico-php/commit/44af33bb38659f74d0780e7f400df60a20491226))

## [2.0.0-beta.2](https://github.com/dansmaculotte/monetico-php/compare/v2.0.0-beta.1...v2.0.0-beta.2) (2019-08-29)


### Features

* add cart resource and unify resources ([cf45695](https://github.com/dansmaculotte/monetico-php/commit/cf45695))

## [2.0.0-beta.1](https://github.com/DansMaCulotte/monetico-php/compare/v2.0.0-0...v2.0.0-beta.1) (2019-08-28)

## [2.0.0-beta.0](https://github.com/DansMaCulotte/monetico-php/compare/v2.0.0-0...v2.0.0-beta.0) (2019-08-27)

## 2.0.0-0 (2019-08-27)


### ⚠ BREAKING CHANGES

* API has changed, see ReadMe.me

* separate requests and responses in folders ([c312854](https://github.com/DansMaCulotte/monetico-php/commit/c312854))

## 1.0.0 (2019-07-24)


### Bug Fixes

* adjust quotes wrapping for payment receipt output format and lowercase seal in seal validation. Resolves [#2](https://github.com/DansMaCulotte/monetico-php/issues/2). ([c7a9244](https://github.com/DansMaCulotte/monetico-php/commit/c7a9244))
* move carbon to dev dependencies ([5235c76](https://github.com/DansMaCulotte/monetico-php/commit/5235c76))
* transform security key before seal generation ([4bc97d3](https://github.com/DansMaCulotte/monetico-php/commit/4bc97d3))


### Features

* Add base files ([0419ab4](https://github.com/DansMaCulotte/monetico-php/commit/0419ab4))
* add php-cs-fixer and git hooks ([1689a16](https://github.com/DansMaCulotte/monetico-php/commit/1689a16))
* Add ReadMe and Licence ([b61d7ff](https://github.com/DansMaCulotte/monetico-php/commit/b61d7ff))
