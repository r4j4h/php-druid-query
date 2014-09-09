php-druid-query
===============

PHP wrapper around querying druid.

Overview
---------------

The wrapper lives in the namespace `DruidFamiliar`. Druid itself was named in the sprit of the D&D character, and that
character could have a familiar - a spiritually linked animal companion. This wrapper in a sense lives as a companion to
Druid, and thus the name.

It's kind of stupid. Feel free to suggest another. I think the repo name should reflect the namespace for clarity,
it currently is `php-druid-query` while the namespace is `DruidFamiliar`. :/


Typical Use
---------------

In general, this wrapper's purpose is to streamline the execution of queries by encapsulating the cruft from the `HTTP` nature of Druid and the analytical grammar in query configuration.

1. Instantiate a connection, configured to hit a Druid endpoint.
2. Instantiate a parameterized query, configured with parameters.
3. Combine the connection and query to execute it, getting the result.

Interface wise, this looks like:

1. Instantiate a `IDruidConnection`, configured to hit a Druid endpoint.
2. Instantiate a `IDruidQuery`, configured with parameters.
3. Run the `IDruidConnection`'s `executeQuery` function with the `IDruidQuery`, getting the result.

Implementation wise, this can look like:

1. Instantiate a `DruidNodeConnection`, configured to hit a Druid endpoint.
2. Instantiate a `SegmentMetadataDruidQuery`, configured with parameters.
3. Run the `DruidNodeConnection`'s `executeQuery` function with the `SegmentMetadataDruidQuery`, getting the result.




Please refer to this diagram for an overview of how this works underneath the hood.

![Sequence Diagram](docs/sequence-diagram.png)

(From this [Dynamic LucidChart Source URL](https://www.lucidchart.com/publicSegments/view/540e3dcd-372c-4aa6-a52c-44d80a005fd1/image.png))
