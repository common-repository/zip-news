=== Zip.News API ===
Contributors: atanasbalevsky
Tags: news api, zip.news, news, api, articles, zipnews, zip news
Requires at least: 5.5
Tested up to: 5.8
Stable tag: 1.5.0
Requires PHP: 7.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin will communicate with Zip.News' API, fetches articles according the search widget configuration and displaying them when the widget & widget area are enabled. The plugin will present the articles in a similar design to the website https://Zip.News, customizable by your theme. More info on https://zip.news.

== Description ==

[Demo widgets here](http://f1a7ee6fe401.less-is-more.dk)

# General plugin information about news API
- This plugin will communicate with Zip.News' API, fetches articles according the search widget configuration and displaying them when the widget & widget area are enabled with the help of a the news api.
- The plugin will present the articles in a similar design to the website https://Zip.News, customizable by your theme.
- The plugin will cache the results from the news api on your WordPress installation for a configured period
- This plugin can be customized or extended according to the WordPress codex
- This plugin uses a 3rd-party service (Zip.News) for the news api, so you must also comply to [Zip.News' Terms and Conditions](https://zip.news/us/agreement.html)


## Pre-requisites
### Create an account at Zip.News

Please visit https://zip.news and create an account that we'll use for the integration with your WordPress installation.

1. Open https://zip.news (* screenshot-2)
2. Login or create a new account (* screenshot-3)
3. Open your user menu and navigate to your Account Details (* screenshot-4)
4. On your Account Details page, please take your userID and apiToken (* screenshot-5) that we'll use to access the news api


## Plugin installation

1. Download the package from Zipnews's website https://zip.news/wordpress-zipnews.zip or find it at WordPress's plugin archive at https://wordpress.org/plugins/ (* screenshot-6)
2. Install the plugin and activate it (* screenshot-7)
3. Open the "Settings" page of the plugin and enter your UserID, ApiToken and configure your news API fetching period (* screenshot-8)


## Widget configuration

1. Navigate to Admin -> Appearance -> Widgets (* screenshot-9)
2. Add the Zip.News Widget to your desired widget area
3. Configure the widget with your desired search parameters. Please consult the how to search tutorial at https://zip.news/us/howToSearch.html or ping us at https://zip.news/us/about.html in case you need an assistance. (* screenshot-10)


## WordPress Developer information
- This plugin will communicate with Zip.News API, fetches articles according the search widget configuration and displaying them when the widget & widget area are enabled. More info on the news api please visit https://zip.news
- The plugin will cache the results on your WordPress installation for the configured period
- If a custom design is needed, please check the template at `./templates/related-articles.php`. You can override it by putting a template at `your_theme/related-articles.php`.
- There're numerous filters used to customize the presentation:
    - `widget_title` - this is an original WordPress filter that's used to hook up during widget title calculation
    - `znaw_article_href` - filter the URL of each article
    - `znaw_article_title` - filter the title of each article
    - `znaw_article_text` - filter the text of each article
    - `znaw_excerpt_length` - the length of the excerpt text
    - `znaw_excerpt_more` - the symbol for the "read more" if the excerpt has to be truncated
    - `znaw_the_content` - filter the excerpt text of the article

== Frequently Asked Questions ==

= Why do I you have 2 separate configuration pages =

The plugin settings page is designed to configure your news API access to zip.news, while the widget instance configuration is related to the search query and results the widgets will display.

== Screenshots ==
1. The result of the integration will be articles from zip.news presented inside of a widget on your WordPress installation. The design of the boxes will be similar to https://zip.news, however customization is possible.
2. Open https://zip.news
3. Login or create a new account
4. Open your user menu and navigate to your Account Details
5. On your Account Details page, please take your userID and apiToken
6. Download the package from https://zip.news/wordpress-zipnews.zip or find it at WordPress's plugin archive at https://wordpress.org/plugins/
7. Install the plugin and activate it
8. Open the "Settings" page of the plugin and enter your UserID, ApiToken and configure your news API fetching period
9. Widget configuration
10. Configure the widget with your desired search parameters. Please consult the how to search tutorial at https://zip.news/us/howToSearch.html or ping us at https://zip.news/us/about.html in case you need an assistance. 5.plugin.demo.local.jpg
11. A global news API access configuration has to be made in order for the WordPress installation to be able to communicate with the Zip.News API.
12. The articles shown on each widget might be different, so per widget configuration is needed in order to support multiple search terms/queries.

== Changelog ==

= 1.3.0 =
* Fixed Widgets Gutenberg screen

= 1.1.9 =
* Updated supported version

= 1.1.8 =
* Updated plugin description and howto

= 1.1.1 =
* Updated readme.txt with more screenshots

= 1.1.0 =
* First release to the global WordPress plugin repository

= 1.0.1 =
* Initial release
* Global API access configuration
* Configure search query per widget instance
