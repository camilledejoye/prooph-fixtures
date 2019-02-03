# Prooph Fixtures

[![Build Status](https://travis-ci.org/elythyr/prooph-fixtures.svg?branch=master)](https://travis-ci.org/elythyr/prooph-fixtures)
[![Coverage Status](https://img.shields.io/coveralls/github/elythyr/prooph-fixtures/master.svg)](https://coveralls.io/github/elythyr/prooph-fixtures?branch=master)

During my experiments with ES I find myself in a situation where Doctrine data fixtures missed me.

Thats why I decided to try to reproduce something similar for Prooph.


## Installation

Oh, sweet Composer!

```shell
composer require --dev elythyr/prooph-fixtures
```

### Versions management
Since its a practice project, I don't really care about BC breaks.
I will only try to not break minor versions, meaning that:
* Updating from `1.0.0` to `1.0.9` should not break anything
* Updating from `1.0.0` to `1.1.0` might break a lot of stuff


## Configuration

There is no configuration per se.
All the configuration should already be done, see [Prooph EventStore](https://github.com/prooph/event-store)
for more information.


## Usage

An example of how to configure the pieces together:

```php
// /test.php

// Configure your system:
// Replace it by your own container or create everything manually :'(
$container = new class() implements ContainerInterface {
    public function has($id) { return false; }
    public function get($id) { return null; }
};

// Retrieve your event store
$eventStore = $container->get('event_store');

// Create a provider for your fixtures
$fixturesProvider = new InMemoryFixturesProvider([
    $youContainer->get('a_fixture'),
    $youContainer->get('another_fixture'),
    // ...
]);

// Retrieve the cleaning projection strategy
// No implementations are provided since it depends on your EventStore implementation
$cleaningProjectionStrategy = $container->get('cleaning_projection_strategy');

// Retrieve the names of all your projections
$projectionsNames = $container->get('projections_names');

// Create the cleaner you want to use, here we will clean both event streams and projections
$cleaner = new ChainCleaner([
    new EventStreamsCleaner($eventStore),
    new ProjectionsCleaner(
        $cleaningProjectionStrategy,
        $projectionsNames
    ),
]);

// Create the fixtures manager, just a front to regroup everything in one place
$fixturesManager = new FixturesManager($fixturesProvider, $cleaner);

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
