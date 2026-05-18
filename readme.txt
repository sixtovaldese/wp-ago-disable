=== aGo Disable ===
Contributors: sixtovaldese
Donate link: https://paypal.me/sixtovaldes
Tags: disable, comments, gutenberg, auto-updates, rss
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Toggle dashboard to disable WordPress features you do not use: comments, Gutenberg, auto-updates, pingbacks, search, RSS, admin bloat.

== Description ==

aGo Disable gives you a single screen with 14 toggles to turn off WordPress features that many sites do not need. Safe defaults are enabled on first activation. The rest are off by default; flip them only when you understand the impact.

Every toggle ships with a 1-line summary plus an expandable "More info" block that explains what it does, why you might want it on, and any caveat. No guesswork.

**Toggles included**

Content:

* Comments site-wide (posts, pages, custom post types, comment forms, admin menu).
* Gutenberg block editor (forces the classic editor).
* Pingbacks and trackbacks.
* Author archives (`/author/username/`).
* Attachment pages (the individual page WordPress creates for each upload).
* Internal site search.
* RSS feeds (main, comments, taxonomies).

Updates:

* Core auto-updates.
* Plugin auto-updates.
* Theme auto-updates.

Admin UX:

* The admin bar for non-admin users.
* "Try Gutenberg" and similar admin notices.
* The "WordPress Events and News" dashboard widget.

System:

* The Heartbeat API on the frontend (throttles to 60 seconds on the admin).

All toggles are reversible: untick the switch and changes save automatically. No external services, no remote calls, no telemetry.

== External services ==

This plugin does not call any third-party service or external API. It works entirely by registering and un-registering standard WordPress hooks and filters.

The "Quick links" card in the admin sidebar links to public third-party tools (Google PageSpeed, GTmetrix, Sucuri SiteCheck) for the convenience of the administrator. These are plain external links, opened in a new tab. The plugin does not send any data to those services.

== Installation ==

1. Upload the `ago-disable` folder to `/wp-content/plugins/` or install via the Plugins screen and upload the zip.
2. Activate the plugin through the Plugins menu in WordPress.
3. Go to aGo Tools, then Disable.
4. The safe defaults are already enabled. Use the toggles to fine-tune.

== Frequently Asked Questions ==

= Can I re-enable a feature after disabling it? =

Yes. Flip the switch back and changes save automatically.

= Will disabling search break my theme? =

Most themes degrade gracefully when search is disabled. If your theme has a visible search form and your visitors rely on it, leave that toggle off.

= I disabled Gutenberg but a plugin still requires it. What now? =

Flip the Gutenberg toggle back on. Some plugins (e.g. modern page builders, form plugins) ship Gutenberg blocks and need the editor active.

= Will disabling auto-updates leave me exposed? =

Auto-updates are convenient but can break sites on minor version changes. If you disable them, plan a recurring manual or staged update process.

= Does the plugin send any data to a third party? =

No. Zero outbound network traffic. No telemetry, no usage stats, no remote configuration.

= Is there a way to switch the plugin language? =

The plugin uses your WordPress site language. UI is translated to English, Spanish and Brazilian Portuguese out of the box.

== Privacy ==

aGo Disable does not collect or transmit any data. It stores only one option in the `wp_options` table under the key `ago_disable_settings`, containing the toggle states. No custom database tables are created. Deactivating the plugin does not delete the option. Uninstalling deletes it.

== Screenshots ==

1. Disable dashboard with 4 sections of toggles (Content, Updates, Admin UX, System) and the "Apply recommended" header.
2. A toggle row expanded showing "How it works", "Why" and "Watch out".
3. Sidebar with Quick links, About, Other aGo Lab plugins and donation cards.

== Changelog ==

= 1.0.0 =
* Initial release.
* 14 toggles across Content, Updates, Admin UX and System.
* Safe defaults seeded on first activation.
* Auto-save on toggle change.
* English, Spanish and Brazilian Portuguese translations bundled.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
