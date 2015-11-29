# SgDatatablesBundle

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/61803d08-17ab-4a69-ad13-6ec448762332/big.png)](https://insight.sensiolabs.com/projects/61803d08-17ab-4a69-ad13-6ec448762332)

[![knpbundles.com](http://knpbundles.com/stwe/DatatablesBundle/badge)](http://knpbundles.com/stwe/DatatablesBundle)

[![Latest Stable Version](https://poser.pugx.org/sg/datatablesbundle/v/stable)](https://packagist.org/packages/sg/datatablesbundle) [![Total Downloads](https://poser.pugx.org/sg/datatablesbundle/downloads)](https://packagist.org/packages/sg/datatablesbundle) [![Latest Unstable Version](https://poser.pugx.org/sg/datatablesbundle/v/unstable)](https://packagist.org/packages/sg/datatablesbundle) [![License](https://poser.pugx.org/sg/datatablesbundle/license)](https://packagist.org/packages/sg/datatablesbundle)

## Screenshots

### Table with Bootstrap3 integration: 

<div style="text-align:center"><img alt="Screenshot" src="https://github.com/stwe/DatatablesBundle/raw/master/Resources/images/bs3.jpg"></div>

### Table with default stylesheet (`display`): 

<div style="text-align:center"><img alt="Screenshot" src="https://github.com/stwe/DatatablesBundle/raw/master/Resources/images/display.jpg"></div>

## Documentation

[Installation](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/installation.md)

[Column types](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/columns.md)

[Setup Datatable Class](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/setup.md)

[Filtering](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/filter.md)

[To use a line formatter](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/lineFormatter.md)

[Query callbacks](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/query.md)

[Extensions like Buttons or Responsive](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/extensions.md)

[Options of the generator](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/generator.md)

[Reference configuration](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/configuration.md)

## Examples

[Examples](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/example.md)

[Demo Application - must be updated!](https://github.com/stwe/dtbundle-demo)

## Integrating 3rd party stuff 

[Integrate Bootstrap3](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/bootstrap3.md)

[Integrate the Translatable behavior extension for Doctrine 2](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/translatable.md)

[Integrate the LiipImagineBundle / ImageColumn, GalleryColumn and thumbnails](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/thumbs.md)

## Creating an Admin Section (new, unstable and experimental)

[Admin section](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/admin.md)

## Limitations and Known Issues

Much like every other piece of software `SgDatatablesBundle` is not perfect and far from feature complete.

### Use this Bundle in ServerSide mode

The ClientSide mode currently does not work with all features. There are some problems with the Buttons-Extension and MultiSelectColumn. 
At the moment I can not say whether the ClientSide mode is supported by me in the future. Priority has the ServerSide mode.

### Other limitations

- This bundle does not support multiple Ids
- 4th level associations are currently not supported
- Searching and filtering on a virtual column not yet implemented and disabled by default

## Reporting an issue or a feature request

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/stwe/DatatablesBundle/issues).

## Friendly License

This bundle is available under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

You are free to use, modify and distribute this software, as long as the copyright header is left intact (specifically the comment block which starts with /*)!
