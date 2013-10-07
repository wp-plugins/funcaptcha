=== FunCaptcha - game CAPTCHA ===
Contributors: swipeads
Tags: captcha, antispam, comment, login, registration captcha, contact form 7 captcha, gravity forms captcha, buddypress CAPTCHA, spam blocking CAPTCHA, CAPTCHA plugin
Requires at least: 2.8.0
Tested up to: 3.6.1
Stable tag: 0.3.19

Stop spam with a fun, fast mini-game CAPTCHA! FunCaptcha is free, and works on every desktop and mobile device. For BuddyPress, Gravity Forms, CF7.

== Description ==

Spammers abuse your site, but users hate typing out twisty letters or ad phrases (aka CAPTCHA and reCAPTCHA). Automated spam filters make mistakes and require constant checking. FunCaptcha presents a mini-game CAPTCHA that blocks the bots while giving your users a few moments of fun. It's a real security solution hardened by experts and automatically updated to provide the best protection. Try [a demo](http://www.funcaptcha.co/) on our site!

Users complete these little games faster than other CAPTCHAs, with fewer frustrating failures and no typing. They work on all browsers and mobile devices, using HTML5 with a fallback to Flash. Visually impaired users can complete an audio challenge CAPTCHA provided by reCAPTCHA.

The FunCaptcha widget works easily on registration and comment forms, as well as Contact Form 7 and Gravity Forms. You can keep your anti-spam solutions such as Akismet, though you won't need to check those filters as often.

Learn more, give feedback, and ask questions at our website. Our epic battle against bots doesn't have to be a headache. Let's fight while having some fun!

To remain secure, this plugin connects to the FunCaptcha API servers to display and validate users answers. 

== Installation ==

[youtube http://www.youtube.com/watch?v=1XNEmuAwp7E]

You can view instructions with images at [our site](http://www.funcaptcha.co/setup/) or watch the above video.

= Install the Plugin =

There is two methods to install FunCaptcha.

Method one is by downloading the plugin from here and following these steps:

1. Upload funcaptcha folder to the /wp-content/plugins/ directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Plugin settings are located in 'Plugin', 'FunCaptcha'.

Method two is by downloading it directly from your Wordpress dashboard by following these steps:

1. Log in to your Wordpress blog as the administrator.
1. Navigate to Plugins -> Add New.
1. Enter "FunCaptcha" in the search box.
1. Click "Install Now".
1. Once installation has completed, click the "Activate Plugin" link.
1. Plugin settings are located in 'Plugin', 'FunCaptcha'.

In both cases it will warn you of needing to setup and activate FunCaptcha, which you can do directly from within the plugin.

= Configure the Plugin =

1. Navigate to Settings -> FunCaptcha
1. If you have not already registered for an account, follow the instructions.
1. After you've registered for an account you will be assigned a private and public Key. Copy and paste those keys into the FunCaptcha settings in your Wordpress admin.
1. Select which locations which you would like FunCaptcha to be active on and when you're done, click the Save Settings button.

== Frequently Asked Questions ==

= How can I try out your CAPTCHA? =
Try [a demo](http://www.funcaptcha.co/) of FunCaptcha on our site.

= Does FunCaptcha work on mobile and all other platforms? =
Yes. It is built on HTML5, with full support for mobiles. It works great with touch interfaces, and does not require any typing. All platforms used by a site’s customers are supported, including every kind of PC and Mac. A Flash version seamlessly appears for older browsers. In short, no user will be blocked.

= Is there audio accessibility? =
Yes. We use the well-known “reCAPTCHA” service to provide an audio CAPTCHA for those who need it.

= Are other CAPTCHAs more secure? =
Unfortunately, all typed-in CAPTCHAs are now being solved by bots to some degree. Some newer CAPTCHA alternatives are actually much easier for bots to solve than before! Once these new alternatives become popular enough to be worthwhile for hackers to target, they will crumble. FunCaptcha will stay ahead of the curve, and clearly explain how we do it-- not resort to a black box of promises.

= What is a CAPTCHA? =
A CAPTCHA (Completely Automated Public Turing test to tell Computers and Humans Apart) is a test to verify that a user is a human instead of a computer program, or “bot”. It stops bots from making user accounts and comments that spam other users, or “scraping” all of the content from a site in order to steal it for other uses.

= What is wrong with CAPTCHAs now? =
Most CAPTCHAs require the user to read and type in text. The text must be hard to read in order to be effective, so most CAPTCHAs are either annoying, or not difficult enough to stop bots. In any case, as more Internet traffic comes from mobile devices, typing turns off users. In short, everyone hates typed-out CAPTCHAs, and some users simply walk away.

= What is FunCaptcha? =
FunCaptcha is a CAPTCHA that presents a mini-game that blocks the bots while giving your users a few moments of fun. It’s a real security solution, hardened by experts and automatically updated to provide the best protection. Users complete these little games faster than other CAPTCHAs, with fewer frustrating failures and no typing. They work on all browsers and mobile devices. Visually impaired users can complete an audio challenge.

= FunCaptcha does not appear / I am using JetPack for comments =
We have noticed a few of our users are using the JetPack plugin, which currently does not support showing a CAPTCHA by third parties. You can disable the JetPack comment addon from your dashboard and it will work fine. There may be other plugins that cause our CAPTCHA to not appear. We recommend you note down any type of comment or registration plugins before you contact us to help us better assist you. If you have any other CAPTCHA plugins, please disable those as well. Our CAPTCHA will detect if you are using JetPack and warn you of the conflicting.

= FunCaptcha shows an error message =
This error message will only appear if you have not correctly added your private and public keys to the settings panel in your wordpress admin dashboard. Our CAPTCHA requires these to properly secure your website. If you contiune to see the error message even after entering correct keys, you may have a plugin that prevents external communication to our servers, such as "wp protection", please disable those as well.

= I'm using the Contact Form 7 plugin. Can I use your CAPTCHA to protect my form? =
Our CAPTCHA will appear as FunCaptcha as a short code option in Contact Form 7's Generate Tag dropdown.

= I'm using the Gravity Forms plugin. Can I use your CAPTCHA to protect my form or registration pages? =
FunCaptcha supports being displayed as a CAPTCHA for Gravity Forms using the advanced fields tab in Gravity Forms.

= I'm using the Buddypress plugin. Can I use your CAPTCHA on my registration page? =
Our CAPTCHA has full support for Buddypress and can be included on any Buddypress website. It will automatically work on any Buddypress registration forms and on any other location our CAPTCHA is enabled for.

= I'm using your CAPTCHA on a page with both a Form and a comment box, and it only appears once? =
Our CAPTCHA currently only supports being displayed once on the same page, so please only show it in a form if there is no comments on the same page.

= If I want to use your CAPTCHA on another site, do I need new keys? =
Yes, you can get those using the same account at our [website](https://swipeads.co/). Please login with the same account you're currently using for your wordpress site.

= How long does it take to install your CAPTCHA? =
Our CAPTCHA can be installed in less than two minutes, the entire signup process is kept within the plugin itself, unlike other CAPTCHAs such as ReCAPTCHA.

= Can I customize your CAPTCHA? =
We are rapidly updating and adding features to both our Wordpress plugin and our core technology platform, in future we will offer new ways for you to display the CAPTCHA to better match your website.

= Can I reposition the CAPTCHA? =
You can change how you'd like to align FunCaptcha in the settings to better match your websites layout. This works on your comment forms, lost password and registration forms, as well as Contact Form 7, BuddyPress and our Gravity Form addons. This setting will also align the submit button to match for the best user experience.

= What does 'security level' mean? =
By default, FunCaptcha's security level is Automatic, and you don't need to do a thing. But here are some details if you want to know what's going on under the hood.

= Can I disabled the CAPTCHA for logged in users? =
Yes, this is an option in the settings.

If the security level is Automatic, security starts at the lowest, Standard level. That means that users do fewer challenges, but spambots also have less work to do to guess their way past the challenge. For most sites, this is fine. Most people will only need to solve two challenges, and almost no spambots will get through. The security level rises and falls automatically, adjusted by FunCaptcha's monitoring system. If our system suspects that a user is a spambot, the security level for that user automatically rises to Enhanced level, described below.

The Enhanced security level makes users do a few more challenges. It's still easy and quick for humans, but becomes much harder for spambots to get through. No spambot that we see attacking our many sites is capable of getting through the Enhanced level.

As a site publisher, you start off at the Automatic security level, which is probably your best option, so you don't need to do a thing. However, if you wish, you can adjust your settings so security remains always at a particular elevated level, and won't adjust automatically. For example, you can assure that all of your users (and all spambots!) will always play at the Enhanced security level. You can change this setting for any domain listed on your account page at [SwipeAds.co](http://www.funcaptcha.co/). If you use our WordPress plugin, you can also use the plugin settings page. (If these two places don't have the same security setting, the more elevated setting of the two will prevail.)

= What do the stars do? =
You earn stars as you complete FunCaptchas anywhere on the web. The faster you complete the CAPTCHA challenge, the more stars you get. Gettng 5 stars means you play the games as well as we, the creators, do– and we have lots of practice! Unless you are using a “cookie blocker” or a “do not track” function on your browser, your star count will keep increasing each time you play. Once you reach 100 stars, you will get a special reward. The reward is not implemented yet but stay tuned, and keep honing your FunCaptcha skills!

= I'd like a certain feature or a way to make it fit the theme of my site better, is there anything I can do? =
Please contact us on the support forums if you have any ideas on how to improve our CAPTCHA. You can also contact us on our [site](http://www.funcaptcha.co/contact-us/).

= Other questions? =
For a full list of frequently asked questions, please see our [FAQ page](http://www.funcaptcha.co/faqs).

== Screenshots ==

1. Comment Form
2. Registration Form
3. Lost Password Form
4. Admin Page

== Changelog ==

= 0.3.19 =
* Made more clear the service connects to the funcaptcha API servers to verify solves.

= 0.3.18 =
* Fixed bug with missing files.

= 0.3.17 =
* Added FunCaptcha login form setting. You can now use FunCaptcha to protect your login forms.

= 0.3.16 =
* Updated error message.

= 0.3.15 =
* Updated to funcaptcha.co

= 0.3.14 =
* Added support for cache plugins.

= 0.3.13 =
* Added support for users with allow_url_include=0 set.

= 0.3.12 =
* Contact Form 7 support is now enabled by default.

= 0.3.11 =
* Added information about how to signup multiple Wordpress sites.

= 0.3.10 =
* Signup now automatically detects your site URL and prefills it for you.

= 0.3.9 =
* Can now hide from admins.

= 0.3.8 =
* Code cleanup and optimizations.

= 0.3.7 =
* User with Buddypress installed will now see FunCaptcha on Buddypress as well as the Wordpress registration forms.

= 0.3.6 =
* Added conflicting plugin warnings to activation tab.

= 0.3.5 =
* Updated nonce support, added additional settings security.

= 0.3.4 =
* Will now warn if FunCaptcha can detect any conflicting plugins.

= 0.3.3 =
* Patches CSRF vulnerability in the settings panel that was created in last update.

= 0.3.2 =
* FunCaptcha will no longer show the error message or prevent comments until you have inserted activation keys.
* Fixed url link in settings.

= 0.3.1 =
* Can now adjust the alignment of FunCaptcha, left, right or centered.

= 0.3.0 =
* Adjustable security setting. You can now choose between Automatic and Enhanced security. If you choose Automatic, security starts at the lowest level, and rises and falls automatically, adjusted by FunCaptcha's monitoring system. The Enhanced level has more challenges to solve, but is very hard for spammer programs to get past. Please read more at our [FAQ](https://swipeads.co/faqs)

= 0.2.2 =
* Gravity Forms support. You can now use FunCaptcha in any Gravity Forms, using the advanced field elements.
* Activate your account directly in the plugin to get your keys. No longer do you need to leave wordpress to register and activate an account to get your security keys. We have done our best to make this process as smooth and simple as possible.
* Activate tab added to support the new in-built registration process. Public and Private keys have also been moved to this new tab.
* Enhanced built in error checking. If your keys look incorrect we will now warn you.
* Fixed bug where if logged in users was disabled may still require verification.

= 0.2.1 =
* Removed php short codes to add support for older versions of PHP or those that have them turned off.

= 0.2.0 =
* Buddypress support.
* Now displays FunCaptcha before the comment submit button.
* Fixed a visual issue on mobile devices if a themes width was less than 300pixels, now FunCaptcha will resize to fit.
* New default settings.
* Left aligned FunCaptcha to match wordpress style better.

= 0.1.0 =
* Bug fix.

= 0.0.1 =
* Beta release.
