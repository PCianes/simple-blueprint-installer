=== Simple blueprint installer ===
Contributors: sumapress, pablocianes
Donate link: https://pablocianes.com/
Tags: blueprint, installer, delete default content, installation, setup
Requires at least: 4.6
Tested up to: 5.3
Stable tag: trunk
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Install this as your first plugin and make easy and fast the first setup of your WordPress.

== Description ==

This single use plugin makes it quick and easy to perform some initial setup tasks in your WordPress install with the addition of two new tabs to help manage and quickly perform some important tasks you should do after a fresh WordPress installation.

The two new pages ( actually tabs ) are available through this plugin and their functions are:

== 1.- Blueprint: ==

After the plugin is activated you will be redirected to the management page, which is a **`new tab called "Blueprint"`** within the native plugins installation page of WordPress.
You can also find it if you go to “add new plugin”, next to the other native tabs like “Featured, Popular, Recommended and Favourites”.

Here you can change the default slug list of plugins ( with all the plugins already installed ) and add your own comma separated slug list of plugins and press the **`Set plugins`** button to be able to work with them.
After this step you can see the list of plugins below like you do in other native tabs of WordPress and operate one by one, or even activate the **`danger button`** and use the global actions that allow you to:
**`¡Delete all plugins!`**, **`Install all plugins set`**, **`Activate all plugins set`** and also **`¡Deactivate all plugins!`**.
Please be careful with these global options if you are in production mode and not in new installation.

Update the list of slugs with the **`Reset & Update`** button to set it to only the plugins already installed.

Note: Before installing new plugins you may want to delete defaults like **`Hello Dolly`** or **`Akismet`**.
For that please press the orange danger button and then **`¡Delete all plugins!`**.
After that you can work with your new blueprint = slugs list of your favourite plugins to install.

For more info please see below "Screenshots" and also read "Frequently Asked Questions".

== 2.- Setup ( gear icon ): ==

Here you can set and perform your own quick configuration options. This tab is beside the other one.

The **Quick global settings** available that you can choose what and how to perform at the push of the **`Do these actions`** button are:

**Cleaning Tasks:**
* Delete (or not) default post with id=1: **`Hello World`**
* Delete (or not) default post with id=2: **`Example page`**
* Delete (or not) all themes except current theme.
* Delete (or not) WordPress unnecessary core files: **`wp-config-sample.php`** and **`readme.html`**

**Other Tasks:**
* Set site language like in WordPress native page ( with auto translation installation ).
* Set timezone like in WordPress native page.
* Set date format selecting one of the most common.
* Set time format selecting one of the most common.
* Change the name ( and auto also the slug ) of the **`Uncategorized`** default category.
* Set a custom **`Category base`** for permalinks.
* Set a custom **`Tag base`** for permalinks.
* Set with default buttons or manual writing the permalink structure.
* Disable pings, trackbacks and comments on new articles.
* Organize my uploads into month- and year-based folders.
* Discourage search engines from indexing this site.

Note: This options page also informs you of the status of each option, such as if some options have been deleted or not, or the state of others based on what is really established in WordPress.

== Installation ==

1. Upload the plugin files to the **`/wp-content/plugins/`** directory, or into admin area of WordPress visit **`Plugins -> Add New`** and search **`Simple blueprint installer`**.
2. Install & Activate the plugin through the **`Plugins' page`** in WordPress.
3. After the plugin is activated you will be redirected to the management page, with all the plugins already installed as the first blueprint. You can also find it if you go to **`add new plugin`** and then go to new tab: **`Blueprint`**.
4. You can change this list of plugins with their slugs and press the **`Set plugins`** button to be able to operate with them.
5. **Important:** Before installing new plugins you may want to delete defaults like **`Hello Dolly`** or **`Akismet`**. For that please press the orange danger button and then **`¡Delete all plugins!`**. After that you can work with your new blueprint = slugs list of your favourite plugins to install.
6. Go to the other new tab with the icon of a gear and set all your own quickly configuration.

== Frequently Asked Questions ==

= Is this plugin for everyone? =

Chiefly it is for WordPress professionals who want to improve their workflow because of it is specially designed for them.

= Is it necessary for it to remain installed? =

It is not necessary, since it is only useful to help you with your first general configuration, saving you time, as well as avoiding forgetting important things.
Also within the options is to disable this plugin when you finish your work, although then you will have to delete it.

= What does "blueprint" mean for this plugin? =

Here a **`blueprint`** is slugs list of your favourite plugins to install.
Write comma separated all the slugs of those plugins you want to install quickly and press **`Set plugins`** button to be able to work with them.
Save where you want to future reference this collection of plugins as a simple text listing.

= How can I set my own "blueprint" in each WordPress installation? =

Write comma separated all the slugs of those plugins you want to install quickly as your own "blueprint".
To make this list you can search the slugs directly on WordPress.org.
Another way is just go to a WordPress installation, install this plugin, and then see in the "blueprint input" all the slugs of the plugins already installed as default first blueprint.
Note: update the list of slugs with the **`Reset & Update`** button to set it only the plugins already installed.

== Screenshots ==

1. Blueprint tab.
2. Setup tab ( icon of a gear )

== Changelog ==

= 1.0.2 =
* Remove Freemius.

= 1.0.1 =
* Freemius insights integration.

= 1.0.0 =
* First publicly available version

== Upgrade Notice ==

= 1.0.0 =
* First publicly available version

== Feedback and support ==

I would be happy to receive your feedback to improve this plugin.
Please let me know through [support forums](https://wordpress.org/support/plugin/simple-blueprint-installer) if you like it and please be sure to [leave a review.](https://wordpress.org/support/plugin/simple-blueprint-installer/reviews/#new-post).

Also you can contact me on my personal page [Pablo Cianes](https://pablocianes.com/) or even visit [Github of Simple Blueprint Installer](https://github.com/PCianes/simple-blueprint-installer) where you can find all the development code of this plugin.

I hope it is useful for you and look forward to reading your reviews! ;-) Thanks!
