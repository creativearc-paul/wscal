<?php
foreach($tags as $name=>$content){
    
    switch($name){
        case 'title_tag': 
            echo '<title>' . $content . '</title>' . "\n";
            break;
        case 'meta_description': 
            echo '<meta name="description" content="' . $content . '" />' . "\n";
            break;
        case 'meta_keywords': 
            echo '<meta name="keywords" content="' . $content . '" />' . "\n";
            break;
        case 'meta_robots_ovid': 
            echo '<meta name="robots" content="' . $content . '" />' . "\n";
            break;
            
        case 'open_graph_url': 
            echo '<meta property="og:url" content="' . $content . '" />' . "\n";
            break;
        case 'open_graph_type_ovid': 
            echo '<meta property="og:type" content="' . $content . '" />' . "\n";
            break;
        case 'open_graph_article_author': 
            if(isset($tags['open_graph_type_ovid']) && $tags['open_graph_type_ovid'] == 5){
                echo '<meta property="article:author" content="' . $content . '" />' . "\n";
            }
            break;
        case 'open_graph_site_name': 
            echo '<meta property="og:site_name" content="' . $content . '" />' . "\n";
            break;
        case 'open_graph_title': 
            echo '<meta property="og:title" content="' . $content . '" />' . "\n";
            break;
        case 'open_graph_image': 
            echo '<meta property="og:image:url" content="' . $content . '" />' . "\n";
            break;
        case 'open_graph_description': 
            echo '<meta property="og:description" content="' . $content . '" />' . "\n";
            break;
            
        case 'facebook_app_id': 
            echo '<meta property="fb:app_id" content="' . $content . '" />' . "\n";
            break;
            
        case 'twitter_card_type_ovid': 
            echo '<meta name="twitter:card" content="' . $content . '" />' . "\n";
            break;
        case 'twitter_card_url': 
            echo '<meta name="twitter:url" content="' . $content . '" />' . "\n";
            break;    
        case 'twitter_card_site': 
            echo '<meta name="twitter:site" content="' . $content . '" />' . "\n";
            break;
        case 'twitter_card_title': 
            echo '<meta name="twitter:title" content="' . $content . '" />' . "\n";
            break;
        case 'twitter_card_image': 
            echo '<meta name="twitter:image" content="' . $content . '" />' . "\n";
            break;
        case 'twitter_card_description': 
            echo '<meta name="twitter:description" content="' . $content . '" />' . "\n";
            break;
    }
    
}
