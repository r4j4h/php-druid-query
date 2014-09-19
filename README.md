php-druid-query
===============

PHP wrapper around querying [Druid](http://druid.io).


Overview
---------------

The wrapper lives in the namespace `DruidFamiliar`. Druid itself was named in the sprit of the D&D character, and that
character could have a familiar - a spiritually linked animal companion. This wrapper in a sense lives as a companion to
Druid, and thus the name.

It's kind of stupid. Feel free to suggest another. I think the repo name should reflect the namespace for clarity,
it currently is `php-druid-query` while the namespace is `DruidFamiliar`. :/


Changelog
-----------

0.2.x Major refactoring

- Query and Response Handling separated. All interfaces renamed and redesigned.
  - `IDruidConnection` is now `IDruidQueryExecutor`.
  - `IDruidQuery` is split into `IDruidQueryGenerator` and `IDruidQueryParameters` and `IDruidQueryResponseHandler`.
  - `BaseQuery` is no longer needed, many similar classes were deprecated or removed.
  - `DruidNodeConnection` is now `DruidNodeDruidQueryExecutor`.

0.1.0 Initial release

Quick sketch for sharing early.

Typical Use
---------------
// TODO Rewrite the typical use to demonstrate how things work now
In general, this wrapper's purpose is to streamline the execution of queries by encapsulating the cruft from the `HTTP` nature of Druid and the analytical grammar in query configuration.

1. Instantiate a connection, configured to hit a Druid endpoint.
2. Instantiate a query generator object for the desired query.
3. Instantiate a query parameters object, configured with desired query parameters.
4. Instantiate a result handler to format the results (otherwise use `DoNothingResponseHandler`)
5. Combine the connection, query, parameters, and response handler to execute it, getting the result.

Interface wise, this looks like:

1. Instantiate a `IDruidQueryExecutor`, configured to hit a Druid endpoint.
2. Instantiate a `IDruidQueryGenerator`.
3. Instantiate a `IDruidQueryParameters`, configured with parameters.
4. Instantiate a `IDruidQueryResponseHandler`.
5. Run the `IDruidQueryExecutor`'s `executeQuery` function with the `IDruidQuery`, getting the result.

Implementation wise, this can look like:

1. Instantiate a `DruidNodeDruidQueryExecutor`, configured to hit a Druid endpoint.
2. Instantiate a `SegmentMetadataDruidQuery`.
3. Instantiate a `SegmentMetadataDruidQueryParameters`, configured with parameters.
4. Instantiate a `SegmentMetadataResponseHandler`.
5. Run the `DruidNodeDruidQueryExecutor`'s `executeQuery` function with the classes spawned in the previous steps, getting the result.


How to Install
---------------

Right now, there is no tagged version.

- Stable branch: `~1.0-dev`
- Stable branch w/ PHP 5.3 Compatibility Support: `dev-php-53-compat`
- Cutting edge: `dev-develop`


To install, it is suggested to use [Composer](http://getcomposer.org). If you have it installed, then the following instructions
in a composer.json should be all you need to get started:

If you are using PHP 5.3, there [is](https://bugs.php.net/bug.php?id=66818) [a](http://php.net/archive/2014.php#id2014-08-14-1) [bug](https://bugs.php.net/bug.php?id=43200) and you will need to use an alternative branch.

Up to date PHP:

```json
{
    "require": {
        "r4j4h/php-druid-query": "~1.0-dev"
    }
}
```

PHP 5.3 Compatibility:

```json
{
    "require": {
        "r4j4h/php-druid-query": "dev-php-53-compat"
    }
}
```

Once that is in, `composer install` and `composer update` should work.

Once those are run, require Composer's autoloader and you are off to the races, or tree circles as it were (bad Druid reference):

1. `require 'vendor/autoload.php';`
2. `$yay = new \DruidFamiliar\Query\TimeBoundaryDruidQuery('my-cool-data-source');`
3. Refer to the `Typical Use` section above.


How to Test
-------------

From the root directory, in a command terminal run: `php vendor/phpunit/phpunit/phpunit tests`.



Examples
---------------

Examples are located in the [\examples](examples) folder.

They share connection information from `_examples-config.php`.
Change that match your Druid instance's connection info.

Right now most are designed to run via the CLI, but will work in a browser if a web server running php serves them.

The HTML outputting ones should print the query results to HTML:

![Example HTML TimeBoundary Output](docs/html-timeboundary-output.png)



Recently Added
---------------

- [Abstract class BasicDruidQuery](src/DruidFamiliar/BasicDruidQuery.php)
- [Group By Example](examples/html-query-printer-improved.php)


Things I want to change: (major breaking changes coming soon)
---------------
One mistake I made is the generateQuery function should really return a raw JSON string ready for submission instead of a PHP array that the




How it Works & How to Extend
---------------

Please refer to this diagram for an overview of how this works underneath the hood.

![Sequence Diagram](docs/sequence-diagram.png)

(From this [Dynamic LucidChart Source URL](https://www.lucidchart.com/publicSegments/view/540e3dcd-372c-4aa6-a52c-44d80a005fd1/image.png))

In general, to add support for a new query all you need to do is create a new class wherever you want that implements `IDruidQuery`.

By wherever you want, that could be in a fork of this repo, or outside of this repo using this repo's interfaces. That is up to you. :)




References
---------------

- [Druid](http://druid.io)
- [Composer](http://getcomposer.org)
- [Guzzle](http://guzzle.readthedocs.org)


Appendix A. Composer.json example that does not rely on Packagist.org:
---------------

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:r4j4h/php-druid-query"
        }
    ],
    "require": {
        "r4j4h/php-druid-query": "~1.0-dev"
    }
}
```
