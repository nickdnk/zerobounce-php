## 1.3.0

#### 2022-09-27

* Added `ConnectionException` and `HttpException` subclasses to `APIError` to allow distinction between HTTP and
  connection errors. Retained naming and namespace to prevent breaking changes.
* Moved development namespace in `composer.json` to `autoload-dev`.
* Added a changelog.

## 1.2.0

#### 2022-09-20

* Added `proxy` option to the constructor. See https://docs.guzzlephp.org/en/stable/request-options.html#proxy for
  available parameters.
