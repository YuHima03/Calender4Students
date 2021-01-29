<head prefix="og: http://ogp.me/ns#">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?=$this->page_info["TITLE"]."｜Calender4Students"?></title>
    <!--desc-->
    <meta name="author" content="YuHima" />
    <meta name="description" content="<?=$this->page_info['DESC']?>" />
    <!--OGP-->
    <meta name="og:url" content="<?=$this->page_info['URL']?>" />
    <meta name="og:title" content="<?=$this->page_info['TITLE']?>" />
    <meta name="og:image" content="<?=$this->page_info['IMAGE']?>" />
    <meta name="og:description" content="<?=$this->page_info['DESC']?>" />
    <!--additional-->
    <?=$inner_html?>
</head>