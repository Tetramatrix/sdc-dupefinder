# SDC Dupefinder
A fully scalable WordPress product matcher plugin.

License: GPLv2 or later

License URI: https://www.gnu.org/licenses/gpl-2.0.html

Installation
1. Download and save the plugin into the WordPress plugin folder and enable the plugin in the backend.

2. Put \<?php echo do_shortcode('[sdc_dupefinder posts_per_page=6 title="Searched 24,144 products for a match. Possible dupes found..."]') \?> in your theme template single page.
For example put it in single-default.php in the rehub theme. That's it. It automatically shows a loading animation and loads matching products into the bottom of the page!

Some highlights:
The plugin does it work in the background and shows a loading animation while searching for matching products so that the main webpage still works like usual! This is even more important because it's a heavy algorithm and very compute intense. In order for this to work, it makes extensive use of, among other things the database, the WordPress REST-Api and the React Framework! For example if the database contains 20k products with 10-40 attributes each, the algorithm of matching a product with x attributes require to iterate over 20000x40*x data rows! That's a huge number and of course it takes some time to process, even on professional servers.

If you want to know more about the algorithm, please check this article from me: https://read.cash/@Gigamegs/wordpress-find-a-list-of-similar-dupe-products-5e13e3f5

Website: https://tetramatrix.github.io/sdc-dupefinder/
