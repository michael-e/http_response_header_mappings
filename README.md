# HTTP Response Header Mappings

Allows more control over Frontend page HTTP response headers by using customizable page types. Each mapping is stored in the Symphony config file, and page type is matched against these mappings.

**Credits:** This extension is based completely on [Alistair Kearney](https://github.com/pointybeard)'s [Content Type Mappings](https://github.com/pointybeard/content_type_mappings) extension.


## Usage

1. Enable the extension. 

    - Note: this has a known conflict with [CacheLite](https://github.com/symphonists/cachelite). Be sure to add any pages you would like to modify to CacheLite’s `Excluded URLs` preference.

2. Add HTTP response header mappings to your `/manifest/config.php` file with the format `'PAGE TYPE' => 'RESPONSE HEADER'`.

3. If a page uses a type listed in the config, that appropriate HTTP response header will be set. *Should more than one match be found, the last one encountered will be used.*

Upon installation, the following will be injected:

    ###### HTTP-RESPONSE-HEADER-MAPPINGS ######
    'http-response-header-mappings' => array(
      '503' => 'HTTP/1.1 503 Service Unavailable',
      'ra1h' => 'Retry-After: 3600',
      'ra1d' => 'Retry-After: 86400',
      'ra1w' => 'Retry-After: 604800',
      'xml' => 'Content-Type: text/xml; charset=utf-8',
      'txt' => 'Content-Type: text/plain; charset=utf-8',
      'js' => 'Content-Type: application/x-javascript; charset=utf-8',
      'json' => 'Content-Type: application/json; charset=utf-8',
      'css' => 'Content-Type: text/css; charset=utf-8',
      'csv' => 'Content-Type: text/comma-separated-values; charset=utf-16',
      'xls' => 'Content-Type: application/msexcel',
      'rss' => 'Content-Type: application/rss+xml',
      'text' => 'Content-Type: text/plain',
    ),
    ########

For example, to have a page render in json, simply add the page-type `json`.

![screen shot 2014-01-10 at 4 46 35 pm](https://f.cloud.github.com/assets/241963/1892779/5b745ae4-7a5a-11e3-8ca5-173e0645a7d1.png)
