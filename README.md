#what3words php-wrapper

PHP wrapper for the what3words API

## Functions

### wordsToPosition($words);
This function takes either:
- a string of 3 words `'table.book.chair'`
- a oneword `'*BobsHouse'`
- or an array or 3 words `['table', 'book', 'chair']`

And returns
- an array of 2 coordinates `[0.1234, 1.5678]`

### positionToWords($position);
This function takes either:
- a string of 2 positions `'0.1234, 1.5678'`
- or an array or 2 positions `[0.1234, 1.5678]`

And returns
- an array of 3 words `['table', 'book', 'chair']`

### setLanguage($language)
This function sets the classes' language, and takes a 2 letter language string:
- `setLanguage('fr');`
