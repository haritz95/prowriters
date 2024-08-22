<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?php echo '<?xml-stylesheet type="text/xsl" href="'.asset("css/sitemap.xsl").'"?>'?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if(isset($menu))
        @foreach ($menu as $item)
            @if($item['url'] != '#' && !empty($item['url']))
            <url>
                <loc>{{ $item['url'] }}</loc>                 
            </url>
            @endif
        @endforeach
    @endif
</urlset> 