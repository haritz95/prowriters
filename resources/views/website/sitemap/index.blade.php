<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?php echo '<?xml-stylesheet type="text/xsl" href="'.asset("css/sitemap.xsl").'"?>'?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('top_menu_sitemap.xml') }}</loc>
        <lastmod></lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('footer_menu_sitemap.xml') }}</loc>
        <lastmod></lastmod>
    </sitemap>
	@if(!settings('hide_blog'))
    <sitemap>
        <loc>{{ route('blog.xml') }}</loc>
        <lastmod></lastmod>
    </sitemap>
	@endif
</sitemapindex>
