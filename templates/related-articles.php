<div class="znaw-container">
<?php
if (isset($args)):
    foreach ($args as $article):
        $url = apply_filters('znaw_article_href', $article->url, $article);
        $title = apply_filters('znaw_article_title', $article->title, $article);
        $text = apply_filters('znaw_article_text', $article->text, $article);
        $host = !empty($article->host) ? $article->host : parse_url($article->url)['host'];
        $publishDate = strtotime($article->xml_date);
?>
    <div class="znaw-article">
        <div class="znaw-article-image">
            <div class="znaw-article-sentiment" style="background-color: <?php echo $article->sentiment_color; ?>"></div>
            <a href="<?php esc_attr_e($url)?>" target="_blank"><img src="<?php esc_attr_e($article->image_url)?>"></a>
        </div>
        <div class="znaw-article-inner">
            <div class="znaw-article-meta">
                <div class="znaw-article-category"><?php esc_html_e(!empty($article->category) ? $article->category : $article->pre_category) ?></div>
                <div><?php echo znaw_format_date($publishDate) ?></div>
                <div><a href="http://<?php esc_attr_e($host) ?>" target="_blank"><?php esc_html_e($host) ?></a></div>
            </div>
            <div class="znaw-article-title"><a href="<?php esc_attr_e($url)?>" target="_blank"><h5><?php esc_html_e($title)?></h5></a></div>
            <div class="znaw-article-info"><?php znaw_excerpt_e(esc_html($text)) ?></div>
            <div class="znaw-provided-by"><?php _e("Provided by ", "znaw") ?><a href="https://zip.news">zip.news</a></div>
        </div>
    </div>
<?php
    endforeach;
endif;
?>
</div>
