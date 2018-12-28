# Prooph Fixtures

[![Build Status](https://travis-ci.org/elythyr/prooph-fixtures.svg?branch=master)](https://travis-ci.org/elythyr/prooph-fixtures)
[![Coverage Status](https://coveralls.io/repos/github/elythyr/prooph-fixtures/badge.svg?branch=master)](https://coveralls.io/github/elythyr/prooph-fixtures?branch=master)

During my experiments with ES I find myself in a situation where Doctrine data fixtures missed me.

Thats why I decided to try to reproduce something similar for Prooph.


## Installation

Oh, sweet Composer!

```shell
composer require --dev elythyr/prooph-fixtures
```


## Configuration

There is no configuration per se.
All the configuration should already be done, see [Prooph EventStore](https://github.com/prooph/event-store)
for more information.


## Usage

An example of how to configure the pieces together:

```php
// /test.php

// Replace it by your own container or retrieve everything manually :'(
$youContainer = new class() implements ContainerInterface {
    public function has($id) { return false; }
    public function get($id) { return null; }
};

$eventStore = $yourContainer->get('event_store');
$projectionManagersLocator = $yourContainer->get('projection_managers_locator');
// The projection names must be the keys
// The values are usually the id of the projection managers inside your container
// They are not used, only the names of the projections are need
$projectionManagerNames = $yourContainer->get('projection_manager_names');

// Create a locator for your fixtures
$fixturesLocator = new InMemoryFixturesLocator([
    $youContainer->get('a_fixture'),
    $youContainer->get('another_fixture'),
    // ...
]);

// Create the cleaner you want to use, here we will clean both event streams and projections
$cleaner = new ChainCleaner([
    new EventStreamsCleaner($eventStore),
    new ProjectionsCleaner($projectionManagersLocator, $projectionManagerNames),
]);

// Create the fixtures manager, just a front to regroup everything in one place
$fixturesManager = new FixturesManager($fixturesLocator, $cleaner);

// Lets do some cleaning !
$fixturesManager->cleanUp();

// Loading is so easy, you can do it yourself :)
// Under the hood the manager do all the heavy lifting by ordering the fixtures
foreach ($fixturesManager->getFixtures() as $fixture) {
    $fixture->load();
}
```


## Todo

- [x] Adds CI with Travis
- [x] Adds tests coverage
- [x] Make a first release
- [x] Publish to packagist
- [ ] \(When needed) Adds the possibility to not clean the DB
- [ ] \(When needed) Adds the possibility to filter the fixtures to load
