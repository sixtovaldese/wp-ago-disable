<?php

namespace AgoLab\Disable\Admin;

defined( 'ABSPATH' ) || exit;

class Page {

    /**
     * Toggles with plain-language metadata.
     * Each entry: section, label, summary (1-line, always visible), what, why, caveat, recommended.
     */
    private static function toggles(): array {
        return [
            'comments' => [
                'section'     => 'features',
                'label'       => __( 'Comments', 'ago-disable' ),
                'summary'     => __( 'Turns off WordPress comments site-wide.', 'ago-disable' ),
                'what'        => __( 'Removes comment forms, listings, the comments admin menu, and disables comments on existing posts and pages.', 'ago-disable' ),
                'why'         => __( 'Unmoderated comments are a spam magnet and a security risk.', 'ago-disable' ),
                'caveat'      => __( 'Existing comments stay in the database.', 'ago-disable' ),
                'recommended' => false,
            ],
            'gutenberg' => [
                'section'     => 'features',
                'label'       => __( 'Gutenberg block editor', 'ago-disable' ),
                'summary'     => __( 'Forces the classic editor instead of Gutenberg blocks.', 'ago-disable' ),
                'what'        => __( 'Replaces the Gutenberg block editor with the classic TinyMCE editor for posts, pages and custom post types.', 'ago-disable' ),
                'why'         => __( 'Useful if your team prefers classic editor or you use Elementor/Bricks/Divi for all content.', 'ago-disable' ),
                'caveat'      => __( 'Existing block content still renders; new posts default to classic.', 'ago-disable' ),
                'recommended' => false,
            ],
            'search' => [
                'section'     => 'features',
                'label'       => __( 'Internal search', 'ago-disable' ),
                'summary'     => __( 'Turns off the WordPress search (`?s=` returns 404).', 'ago-disable' ),
                'what'        => __( 'Returns 404 for `?s=` queries and removes the search template from the theme.', 'ago-disable' ),
                'why'         => __( 'Native search is poor and bots probe it. Skip it if you do not need search, or use a better one.', 'ago-disable' ),
                'caveat'      => __( 'Make sure no link in your theme points to a search bar.', 'ago-disable' ),
                'recommended' => false,
            ],
            'rss_feeds' => [
                'section'     => 'features',
                'label'       => __( 'RSS feeds', 'ago-disable' ),
                'summary'     => __( 'Disables all RSS feeds (main, comments, author, category).', 'ago-disable' ),
                'what'        => __( 'Returns a "feeds disabled" message instead of XML for any feed URL.', 'ago-disable' ),
                'why'         => __( 'Non-blog sites have no subscribers. Stops bots from scraping the full feed of your content.', 'ago-disable' ),
                'caveat'      => __( 'Only enable if you do NOT publish a blog or podcast.', 'ago-disable' ),
                'recommended' => false,
            ],

            'attachment_pages' => [
                'section'     => 'public',
                'label'       => __( 'Attachment pages', 'ago-disable' ),
                'summary'     => __( 'Removes the auto page for every uploaded image.', 'ago-disable' ),
                'what'        => __( 'Disables the dedicated page WordPress generates for each upload (e.g. `/my-image/`).', 'ago-disable' ),
                'why'         => __( 'These pages are thin (only the image) and hurt SEO. Most sites never link to them on purpose.', 'ago-disable' ),
                'caveat'      => '',
                'recommended' => true,
            ],
            'author_archives' => [
                'section'     => 'public',
                'label'       => __( 'Author archives', 'ago-disable' ),
                'summary'     => __( 'Hides the public author page that exposes usernames.', 'ago-disable' ),
                'what'        => __( 'Disables the `/author/admin/` style page that lists all posts by an author.', 'ago-disable' ),
                'why'         => __( 'Author archives leak usernames to attackers and are duplicate thin content.', 'ago-disable' ),
                'caveat'      => '',
                'recommended' => true,
            ],

            'pingbacks' => [
                'section'     => 'security',
                'label'       => __( 'Pingbacks & trackbacks', 'ago-disable' ),
                'summary'     => __( 'Closes a known DDoS amplification vector.', 'ago-disable' ),
                'what'        => __( 'Disables the pingback and trackback systems blogs used to notify each other about links.', 'ago-disable' ),
                'why'         => __( 'Pingbacks can be used as a reflection-attack vector and are mostly spam.', 'ago-disable' ),
                'caveat'      => '',
                'recommended' => true,
            ],
            'auto_updates_core' => [
                'section'     => 'security',
                'label'       => __( 'Automatic core updates', 'ago-disable' ),
                'summary'     => __( 'Stops WordPress from auto-updating itself.', 'ago-disable' ),
                'what'        => __( 'Disables background updates of WordPress core. Manual updates still work.', 'ago-disable' ),
                'why'         => __( 'Useful when you want to test updates in staging before production.', 'ago-disable' ),
                'caveat'      => __( 'Strongly NOT recommended on sites without a maintenance schedule. Auto-updates ship same-day security fixes.', 'ago-disable' ),
                'recommended' => false,
            ],
            'auto_updates_plugins' => [
                'section'     => 'security',
                'label'       => __( 'Automatic plugin updates', 'ago-disable' ),
                'summary'     => __( 'Stops plugins from updating themselves.', 'ago-disable' ),
                'what'        => __( 'Disables background updates of plugins.', 'ago-disable' ),
                'why'         => __( 'Full control of when updates land in production.', 'ago-disable' ),
                'caveat'      => __( 'Strongly NOT recommended without a maintenance routine.', 'ago-disable' ),
                'recommended' => false,
            ],
            'auto_updates_themes' => [
                'section'     => 'security',
                'label'       => __( 'Automatic theme updates', 'ago-disable' ),
                'summary'     => __( 'Stops themes from updating themselves.', 'ago-disable' ),
                'what'        => __( 'Disables background updates of themes.', 'ago-disable' ),
                'why'         => __( 'Same reason as core auto-updates.', 'ago-disable' ),
                'caveat'      => __( 'Strongly NOT recommended without a maintenance routine.', 'ago-disable' ),
                'recommended' => false,
            ],

            'admin_bar' => [
                'section'     => 'admin',
                'label'       => __( 'Admin bar on frontend', 'ago-disable' ),
                'summary'     => __( 'Hides the black bar when admins browse the public site.', 'ago-disable' ),
                'what'        => __( 'Hides the WordPress admin bar at the top of the frontend for logged-in admins.', 'ago-disable' ),
                'why'         => __( 'Cleaner frontend during QA and screenshots. Admin bar still shows in wp-admin.', 'ago-disable' ),
                'caveat'      => '',
                'recommended' => false,
            ],
            'admin_notices' => [
                'section'     => 'admin',
                'label'       => __( 'Plugin nag notices', 'ago-disable' ),
                'summary'     => __( 'Silences plugin upsells and "rate me" notices for non-admins.', 'ago-disable' ),
                'what'        => __( 'Hides the yellow/red notices plugins inject at the top of wp-admin for users below administrator.', 'ago-disable' ),
                'why'         => __( 'Reduces noise for editors and clients.', 'ago-disable' ),
                'caveat'      => __( 'Administrators still see all notices.', 'ago-disable' ),
                'recommended' => true,
            ],
            'wp_news_widget' => [
                'section'     => 'admin',
                'label'       => __( 'WordPress news widget', 'ago-disable' ),
                'summary'     => __( 'Removes the news widget from the dashboard.', 'ago-disable' ),
                'what'        => __( 'Removes the "WordPress Events and News" widget from the Dashboard screen.', 'ago-disable' ),
                'why'         => __( 'It clutters the dashboard and fetches from wordpress.org on every load.', 'ago-disable' ),
                'caveat'      => '',
                'recommended' => true,
            ],
            'heartbeat' => [
                'section'     => 'admin',
                'label'       => __( 'Heartbeat throttle', 'ago-disable' ),
                'summary'     => __( 'Reduces server CPU by polling once per minute (instead of 15 seconds).', 'ago-disable' ),
                'what'        => __( 'Throttles the WordPress Heartbeat AJAX polling (autosave, post-lock) to 60s.', 'ago-disable' ),
                'why'         => __( 'On shared hosting, heartbeat hammers admin-ajax.php and eats CPU.', 'ago-disable' ),
                'caveat'      => __( 'Autosave still works, just less frequently.', 'ago-disable' ),
                'recommended' => true,
            ],
        ];
    }

    public static function render(): void {
        $toggles      = self::toggles();
        $section_meta = [
            'features' => [ __( 'WordPress features', 'ago-disable' ), __( 'Turn off built-in features your site does not use.', 'ago-disable' ) ],
            'public'   => [ __( 'Public pages and archives', 'ago-disable' ), __( 'Disable thin auto-generated pages that hurt SEO and leak info.', 'ago-disable' ) ],
            'security' => [ __( 'Security and updates', 'ago-disable' ), __( 'Close attack surfaces and control updates.', 'ago-disable' ) ],
            'admin'    => [ __( 'Admin noise', 'ago-disable' ), __( 'Reduce dashboard clutter for editors and clients.', 'ago-disable' ) ],
        ];
        ?>
        <div class="wrap">
            <h1>
                <img src="<?php echo esc_url( AGODISABLE_URL . 'assets/img/agolab.webp' ); ?>" alt="aGo Lab" style="height:28px;width:auto;vertical-align:middle;margin-right:8px">
                <?php esc_html_e( 'aGo Disable', 'ago-disable' ); ?>
                <span style="font-size:12px;color:#999;margin-left:8px">v<?php echo esc_html( AGODISABLE_VERSION ); ?></span>
            </h1>

            <div class="ago-layout">
                <div class="ago-main">

                    <div class="card ago-card ago-intro">
                        <h2 style="margin-top:0"><?php esc_html_e( 'What this plugin does', 'ago-disable' ); ?></h2>
                        <p><?php esc_html_e( 'WordPress includes features that many sites never use: comments, RSS, attachment pages, the news widget, and so on. Each costs CPU, exposes attack surface, or adds clutter.', 'ago-disable' ); ?></p>
                        <p style="margin:0"><strong><?php esc_html_e( 'On install, the safe defaults are already enabled.', 'ago-disable' ); ?></strong> <?php esc_html_e( 'You can fine-tune below: each switch shows a 1-line summary, and "More info" expands the full explanation if you need it.', 'ago-disable' ); ?></p>
                    </div>

                    <div class="card ago-card">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap">
                            <h2 style="margin:0"><?php esc_html_e( 'Switches', 'ago-disable' ); ?></h2>
                            <div class="ago-actions" style="margin:0">
                                <button id="ago-apply-recommended" class="button button-primary" type="button">
                                    <?php esc_html_e( 'Apply recommended', 'ago-disable' ); ?>
                                </button>
                                <button id="ago-enable-all" class="button" type="button"><?php esc_html_e( 'Enable all', 'ago-disable' ); ?></button>
                                <button id="ago-disable-all" class="button" type="button"><?php esc_html_e( 'Reset', 'ago-disable' ); ?></button>
                            </div>
                        </div>
                        <p class="description"><?php esc_html_e( 'Changes save automatically. Each switch ON means "this WordPress feature is disabled".', 'ago-disable' ); ?></p>

                        <div id="ago-disable-status" style="display:none"></div>

                        <?php foreach ( $section_meta as $section => $meta ) : ?>
                            <div class="ago-section">
                                <h3><?php echo esc_html( $meta[0] ); ?></h3>
                                <?php if ( $meta[1] ) : ?><p class="description" style="margin-top:-4px"><?php echo esc_html( $meta[1] ); ?></p><?php endif; ?>

                                <?php foreach ( $toggles as $key => $t ) : if ( $t['section'] !== $section ) continue; ?>
                                <div class="ago-toggle-row">
                                    <div class="ago-toggle-main">
                                        <div class="ago-toggle-info">
                                            <div class="ago-toggle-title">
                                                <strong><?php echo esc_html( $t['label'] ); ?></strong>
                                                <?php if ( $t['recommended'] ) : ?>
                                                    <span class="ago-rec-pill"><?php esc_html_e( 'Recommended', 'ago-disable' ); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ago-toggle-summary"><?php echo esc_html( $t['summary'] ); ?></div>
                                        </div>
                                        <label class="ago-switch">
                                            <input type="checkbox" data-key="<?php echo esc_attr( $key ); ?>" <?php echo $t['recommended'] ? 'data-recommended="1"' : ''; ?>>
                                            <span class="ago-slider"></span>
                                        </label>
                                    </div>
                                    <?php if ( $t['what'] || $t['caveat'] ) : ?>
                                    <details class="ago-toggle-details">
                                        <summary><?php esc_html_e( 'More info', 'ago-disable' ); ?></summary>
                                        <div class="ago-toggle-details-inner">
                                            <?php if ( $t['what'] ) : ?>
                                                <p><strong><?php esc_html_e( 'How it works:', 'ago-disable' ); ?></strong> <?php echo esc_html( $t['what'] ); ?></p>
                                            <?php endif; ?>
                                            <?php if ( $t['why'] ) : ?>
                                                <p><strong><?php esc_html_e( 'Why:', 'ago-disable' ); ?></strong> <?php echo esc_html( $t['why'] ); ?></p>
                                            <?php endif; ?>
                                            <?php if ( $t['caveat'] ) : ?>
                                                <p class="ago-toggle-caveat"><strong><?php esc_html_e( 'Watch out:', 'ago-disable' ); ?></strong> <?php echo esc_html( $t['caveat'] ); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </details>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>

                <div class="ago-sidebar">

                    <div class="card ago-card">
                        <h3 style="margin-top:0"><?php esc_html_e( 'Quick links', 'ago-disable' ); ?></h3>
                        <ul class="ago-features" style="list-style:none;padding:0;margin:0">
                            <li><a href="https://pagespeed.web.dev/" target="_blank" rel="noopener"><?php esc_html_e( 'Measure your site with PageSpeed', 'ago-disable' ); ?></a></li>
                            <li><a href="https://gtmetrix.com/" target="_blank" rel="noopener"><?php esc_html_e( 'Audit performance with GTmetrix', 'ago-disable' ); ?></a></li>
                            <li><a href="https://sitecheck.sucuri.net/" target="_blank" rel="noopener"><?php esc_html_e( 'Free security scan (Sucuri)', 'ago-disable' ); ?></a></li>
                        </ul>
                    </div>

                    <div class="card ago-card">
                        <h3 style="margin-top:0"><?php esc_html_e( 'About', 'ago-disable' ); ?></h3>
                        <p style="font-size:13px;color:#666"><?php esc_html_e( 'Reversible switches to disable WordPress features you do not use. Safe defaults enabled out of the box.', 'ago-disable' ); ?></p>
                        <ul class="ago-features">
                            <li><?php esc_html_e( 'Auto-enables safe defaults on install', 'ago-disable' ); ?></li>
                            <li><?php esc_html_e( '14 reversible switches', 'ago-disable' ); ?></li>
                            <li><?php esc_html_e( 'No remote calls, no tracking', 'ago-disable' ); ?></li>
                            <li><?php esc_html_e( 'English, Spanish, Brazilian Portuguese', 'ago-disable' ); ?></li>
                            <li><?php esc_html_e( 'Completely free, no Pro tier.', 'ago-disable' ); ?></li>
                        </ul>
                    </div>

                    <div class="card ago-card">
                        <h3 style="margin-top:0"><?php esc_html_e( 'Other aGo Lab plugins', 'ago-disable' ); ?></h3>
                        <p style="font-size:13px;color:#666;margin-top:0">
                            <?php esc_html_e( 'Free WordPress plugins from the same team. No upsell pressure.', 'ago-disable' ); ?>
                        </p>
                        <ul class="ago-features">
                            <li><strong>aGo Cleanup</strong>, <?php esc_html_e( 'Remove WordPress bloat from the HTML head and assets.', 'ago-disable' ); ?></li>
                            <li><strong>aGo Mail Pilot</strong>, <?php esc_html_e( 'SMTP for WordPress with 8 provider presets and credentials wizard.', 'ago-disable' ); ?></li>
                            <li><strong>aGo AI Chatbot</strong>, <?php esc_html_e( 'AI customer support widget for your site.', 'ago-disable' ); ?></li>
                            <li><strong>aGo Legal</strong>, <?php esc_html_e( 'GDPR / LGPD / Chile Law 21.719 compliance toolkit.', 'ago-disable' ); ?></li>
                        </ul>
                        <p>
                            <a href="https://ago.cl/herramientas/" target="_blank" rel="noopener" class="button button-secondary" style="width:100%;text-align:center">
                                <?php esc_html_e( 'Browse aGo Lab plugins', 'ago-disable' ); ?>
                            </a>
                        </p>
                    </div>

                    <div class="card ago-card ago-donation">
                        <h3 style="margin-top:0"><?php esc_html_e( 'Support open source', 'ago-disable' ); ?></h3>
                        <p style="font-size:13px;color:#666"><?php esc_html_e( 'If this plugin saves you time, consider buying us a coffee.', 'ago-disable' ); ?></p>
                        <div class="ago-donation-amounts">
                            <a href="https://paypal.me/sixtovaldes/3" class="ago-amount" target="_blank" rel="noopener">$3</a>
                            <a href="https://paypal.me/sixtovaldes/5" class="ago-amount" target="_blank" rel="noopener">$5</a>
                            <a href="https://paypal.me/sixtovaldes/10" class="ago-amount" target="_blank" rel="noopener">$10</a>
                        </div>
                        <a href="https://paypal.me/sixtovaldes" class="ago-coffee-btn" target="_blank" rel="noopener">
                            <span class="dashicons dashicons-coffee" style="margin-right:6px"></span>
                            <?php esc_html_e( 'Buy us a coffee', 'ago-disable' ); ?>
                        </a>
                    </div>

                    <div class="ago-footer">
                        <a href="https://ago.cl" target="_blank" rel="noopener" class="ago-footer-logo">
                            <img src="<?php echo esc_url( AGODISABLE_URL . 'assets/img/agolab.webp' ); ?>" alt="aGo Lab" style="height:40px;width:auto">
                        </a>
                        <p>
                            <?php
                            echo wp_kses_post(
                                sprintf(
                                    /* translators: %1$s: heart icon HTML, %2$s: link to ago.cl */
                                    __( 'Developed with %1$s by %2$s', 'ago-disable' ),
                                    '<span style="color:#e25555">&#10084;</span>',
                                    '<a href="https://ago.cl" target="_blank" rel="noopener"><strong>aGo Lab</strong></a>'
                                )
                            );
                            ?>
                        </p>
                    </div>

                </div>
            </div>

        </div>
        <?php
    }
}
