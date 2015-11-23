# what3words php-wrapper

Use the what3words API in your PHP site (see http://developer.what3words.com/api)

## Functions

### wordsToPosition($words);
This function takes either:
- a string of 3 words `'table.book.chair'`
- an array of 3 words `['table', 'book', 'chair']`

And returns
- an array of 2 coordinates `[0.1234, 1.5678]`

### positionToWords($position);
This function takes either:
- a string of 2 positions `'0.1234, 1.5678'`
- an array of 2 positions `[0.1234, 1.5678]`

And returns
- an array of 3 words `['table', 'book', 'chair']`

### setLanguage($language)
This function sets the classes' language, and takes a 2 letter language string:
- `setLanguage('fr');`

## Code examples

### Get 3 words

```php
<?php

$w3w = new What3words;
$result = $w3w->wordsToPosition('table.book.chair');
print_r($result);

?>
```

### Get position

```php
<?php

$w3w = new What3words;
$result = $w3w->positionToWords('51.484463, -0.195405');
print_r($result);

?>
```

## Revision History

* `v1.0.0` 8/12/14 - Initial release
* `v1.0.1` 22/12/14 - Documentation updates to `README.md`
* `v1.0.2` 7/1/15 - More `README.md` updates
* `v1.0.3` 18/1/15 - Remove hard-coded API key
* `v1.0.4` 6/3/15 - Normalise class name across what3words wrappers
