# Images

Outpost is able to get images from remote sites, and make local transformations such as resizing, cropping, and compositing. Command line access to Imagemagick binaries is required.

Outpost's Image classes can be combined to describe complex images:

```php
$image = new ImageWithOverlay(new ResizedImage(new RemoteImage("http://example.com/image.jpg", "Some alt text"), 600, 400), new SolidOverlay('ff0000'));
```

Local image files can be retrieved from an [Assets\Storage][asset storage] instance.

[asset storage]: ../Assets