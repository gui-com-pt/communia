Communia
====================

A common library i created from [pi-framework](https://github.com/guilhermegeek/pi-framework) and i reused in others projects.

A set of tools and common implementations/interfaces i use as well.

## Http Request

[HttpRequest](src/Pi/Common/Http/HttpRequest.php) is a utility object to execute HTTP requests supporting features like headers, cookies and authentication.

Responses are returned as [HttpMessage](src/Pi/Common/Http/HttpMessage.php)

## Container

My implementation for a IOC is horrible and temporary. I've wrote a custom one because i want direct implementation with cache mechanism and others providers like [AbstractMetadataFactory](src/Pi/Common/Mapping/AbstractMetadataFactory.php) and [AbstractHydratorFactory](src/Pi/Common/Mapping/AbstractHydratorFactory.php).


## Cache Provider

The Cache Providers implements the [ICacheProvider](src/Pi-Interfaces/ICacheProvider.php) interface:

```php
public function get($key = null) : ?mixed; 
public function set($key, $value) : void;
public function getArray(string $key) : ?array;
public function push(string $key, string $value) : void;
public function pushObject(string $key, mixed $obj) : void;
public function getAs(string $key, string $className) : ?mixed;
public function getMap(string $key) : ?Map<string,string>;
public function pushMap(string $key, string $mapKey, string $mapValue) : void;
public function contains($key) : bool;
```

Available Cache Providers
+ APC
+ Redis (not finished yet)
+ Memcached (not testes yet)

## Log

The Log Providers implements the [ILogFactory](src/Pi-Interfaces/ILogFactory.php) interface. Each factory is responsible for returning a [ILog](src/Pi-Interfaces/ILog.php) instance (available for specific types) implementing:

```php
public function debug(string $message);
public function error($message);
public function fatal($message);
public function info($message);
public function warn($message);
```