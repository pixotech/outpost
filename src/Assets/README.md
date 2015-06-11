# Assets

Outpost provides limited asset management capabilities, exclusively confined to image handling. The Asset Storage class manages the creation of image files in a public directory. Images are expected to originate at remote sites, and are made available for local transformations such as resizing, cropping, and compositing.

Local files can be retrieved from a `Storage` instance:

```php
$storage = new Storage("/path/to/assets", "http://example.org/assets");
$file = $storage->getFile($asset); # $asset must implement AssetInterface
print $file->getUrl(); # http://example.org/assets/EXTREMELYLONGHASHTHATUNIQUELYIDENTIFIESTHISFILE
```

See also: [Images](../Images)