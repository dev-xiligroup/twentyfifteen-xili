# twentyfifteen-xili

Child theme of twenty fifteen theme with multilingual features.

This bundled theme twenty fifteen is introduced with WordPress 4.1.
The child theme '-xili' here is made to incorporate multilingual features et fixes for better live translation.

In `functions.php` file, some examples of commented source are shipped to be used in another themes...

Online demo website is here: http://2015.extend.xiligroup.org

## Prerequisites:

1. WordPress 4.1 + and updated twentyfifteen
1. **a child theme needs his parent theme**
1. `xili-language` version 2.17+ (with new theme-multilingual-classes and custom flags in media library)
1. `xili-language` plugin must be activated. The plugin is [available here](http://wordpress.org/plugins/xili-language/)
1. After installation or updating, it is recommanded to refresh permalinks (and empty the browser cache too)
1. After decompressing "master" .zip from Github, only upload the folder twentyfifteen-xili (near the readme.md) to themes folder , the child-theme files expect to be finally in `wp-content/themes/twentyfifteen-xili/`!
1. Before updating, don’t forget to backup your own `.mo` language files

## New in 1.1.0 (2015-04-24)
1. ready for WP 4.2 Powell

## New in 1.0.1 (2015-03-11)
1. new filter to translate description of categories in vertical nav menu (context menu_description used by a _x() function) - when you change value in category, nav menu must be rebuilt !

2015-04-24
