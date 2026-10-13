{* Микроразметка организации. Данные задаются в php/config.php *}
<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "Organization",
        "url": "{$fact_url->full}",
        "name": "{$page->seo->meta_title}",
        "image": "{$page->seo->og_image}",
        "description": "{$page->seo->meta_desc}"{if !empty($phone->title)},
        "telephone": "{$phone->title}"{/if}{if !empty($address1) || !empty($address2)},
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "{constant('SITE_LOCALITY')}",
            "streetAddress": "{$address1} {$address2}",
            "postalCode": "{constant('SITE_POSTAL_CODE')}"
        }{/if}{if !empty($phone->title)},
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "sales",
            "name": "Менеджер",
            "telephone": "{$phone->title}"
        }{/if}
    }
</script>
