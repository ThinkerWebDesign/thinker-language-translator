=== Language Translator ===
Contributors: thinkerwebdesign
Tags: translator, shortcode, floating, footer, language translator, google translator, google language translator, translate
Donate link: http://www.thinkerwebdesign.com/thinker-language-translator-plugin/
Requires at least: 3.5+
Tested up to: 5.8
Stable tag: trunk
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Add a highly customizable language translator to your website.

== Description ==

= Features: =

* Customized floating Google language translator.
* Customized Google language translator below the footer.
* Customized Google language translator in specific locations via shortcode: `[thinker_translator]`
* Interactive or inline display.
* Display flags only, flags and text, or text only.
* Optional customized icon to display the language translator on mouse click or hover.

= How to Use: =

**Simple Setup**

* Inline Display: Use Inline Display or not. (Inline Display disables all Icon Method Settings)
* Use Icon Method: Use Hover/Click Icon Method or not.
* Icon Container: Use Icon Container or not.
* Locations: Most important is to select the desired Locations.
* Optionally display the translator in posts or pages using this shortcode: `[thinker_translator]`
* Optionally customize any features by changing the default settings on any other fields or not. (See the Customize Steps below)

**Customize Step 1 - Basic Settings**

* Main Color: Choose a custom main color or use the default. (Default: #3C3B6E)
* Languages: Choose the languages to offer for translation or use the default list. (Default: English, Spanish, French, Russian, Japanese, Chinese)
* Flags, Text or Both: Choose whether to display languages using Flags Only, Text Only, or Both Flags and Text. (Default: Flags Only)
* More Languages Button: Choose whether to display a More Languages option that displays all languages supported by Google. Choose to use only a Plus Sign, only the word `More`, a Plus Sign and the word `More`, or Disable More Languages. (Default: Plus Sign Only)
* Wide More Button: Optionally make the More Languages button span the entire width of the display. (Excludes Inline Display)
* Inline Display: Optionally use a simple Inline Display. (Disables all Hover/Click Icon settings)
* Cache Output: Optionally cache the HTML output for best performance. (Recommended)
* PNG Images Only: Optionally use PNG images instead of SVG images. (Not recommended)
* HTML Links Method: Optionally use outbound HTML links instead of JavaScript. This sends users to a translation of the website at: https://translate.google.com/ (Not recommended)

**Customize Step 2 - Hover/Click Icon**

*Icon Method*

* Use Icon Method: Optionally use a customized icon to display translator options on mouse click or hover.
* Icon Size: Choose the size of the icon in pixels. (Default: 30)
* Icon Type: Choose to use the Font Awesome Translator icon, A custom image, or none. (Default: Font Awesome)
* Icon Image URL: Choose a custom image to use as an icon or background behind the Font Awesome Icon. (Example: http://ex.com/ex.png)
* Icon BG Color: Choose a background color behind the icon. (Default: #fff)

*Icon Container*

* Icon Container: Optionally display the icon inside a customized container.
* Height: Choose the height of the icon container in pixels. (Default: 30)
* Width: Choose the width of the icon container in pixels. (Default: 50)
* Border Radius: Choose a border radius for the icon container using CSS Rules. (Default: 50%)
* Nation Flag BG: Choose a National Flag image for the icon container. (Default: United States)
* BG Image: Choose a custom image for the icon container. (Example: http://ex.com/ex.png)
* BG Color: Choose a background color for the icon container.

**Customize Step 3 - Locations**

*Shortcode*

* Shortcode Padding: Choose a padding for the shortcode container using CSS Rules. (Default: 5px)
* Display the translator in desired content using this shortcode: `[thinker_translator]`

*Floating*

* Floating Translator: Optionally display a customized Floating translator.
* Padding: Choose a padding for the Floating translator using CSS Rules. (Default: 0)
* Top Alignment: Choose a top alignment for the Floating translator using CSS Rules. (Default: 50px)
* Bottom Alignment: Choose a bottom alignment for the Floating translator using CSS Rules. (Default: auto)
* Left Alignment: Choose a left alignment for the Floating translator using CSS Rules. (Default: 50px)
* Right Alignment: Choose a right alignment for the Floating translator using CSS Rules. (Default: auto)

*Footer*

* Below Footer Translator: Optionally display a customized translator below the footer.
* Footer Padding: Choose a padding for the Footer translator using CSS Rules. (Default: 5px)
* Footer Align: Align the Footer translator: Center, Left, Right, Inherit. (Default: Center)
* Footer BG Color: Choose a background color for the Footer translator.
* Footer BG Style: Choose a background CSS style for the Footer translator. This is great to match the website. (Example: url(/ex.png) center top / cover) (Important: Do NOT use quotes for URL)

**General Notes**

* The translator Preview on the settings page excludes all Locations settings.
* The Remove Changes button will remove any unsaved changes and reload the page.
* The Clear All Fields button will reset all the settings to default values.
* For shortcode display, use: `[thinker_translator]`
* The Hover/Click Icon method and the Floating display both have nearly unlimited possibilities. Refer to below CSS Help section to learn more.
* Inline Display disables the Hover/Click Icon settings.
* If multiple translators are used on the same page, the More Languages Button is only used on the first translator. Order of priority: Floating, Footer, Shortcode.
* HTML Links Method is not recommended but does not use JavaScript. If HTML Links Method and Cache Output are both enabled, all translation links will point to your home page.

**CSS Help Notes**

* [Border Radius](https://www.w3schools.com/cssref/css3_pr_border-radius.asp)
* [Padding](https://www.w3schools.com/cssref/pr_padding.asp)
* [Background](https://www.w3schools.com/cssref/css3_pr_background.asp) *(Important: Do NOT use quotes for URL)*
* [Top](https://www.w3schools.com/cssref/pr_pos_top.asp)
* [Bottom](https://www.w3schools.com/cssref/pr_pos_bottom.asp)
* [Left](https://www.w3schools.com/cssref/pr_pos_left.asp)
* [Right](https://www.w3schools.com/cssref/pr_pos_right.asp)


= Demo: =

[Visit Plugin URI](http://www.thinkerwebdesign.com/thinker-language-translator-plugin/)

== Installation ==

1. Install the plugin using the `Plugins` menu in your WordPress Admin Area or upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in your WordPress Admin Area.
3. Follow the instructions in the above `How to Use` section.


== Frequently Asked Questions ==

= How are multiple languages selected? =

Hold CTRL or SHIFT keys to select multiple languages.

= What is Inline Display? =

Inline Display is a very simple display method that works well with a small number of languages and just about anywhere on a web page.

= Why does the HTML Links Method not work with WP installs on my computer? =

Google does not have access to local installations on your computer.

== Screenshots ==

1. Examples.
2. Settings Page.

== Changelog ==

= 1.0.1 =

* Improved Styles on "Powered by Google Translate"

= 1.0.0 =

* Initial release.

== Upgrade Notice ==

= 1.0.1 =

* Improved Styles on "Powered by Google Translate"

= 1.0.0 =

This is the initial release of the plugin.
