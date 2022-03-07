<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['neoseo_validation_rules'] = array(
                'neoseo_channel' => array(
                                        array(
                                            'field'   => 'channel_id', 
                                            'label'   => 'Channel Id', 
                                            'rules'   => 'trim|xss_clean|required|integer|max_length[10]'
                                            ),
                                        array(
                                            'field'   => 'created_at', 
                                            'label'   => 'Created At', 
                                            'rules'   => 'trim|xss_clean|exact_length[19]'
                                            ),
                                        array(
                                            'field'   => 'updated_at', 
                                            'label'   => 'Updated At', 
                                            'rules'   => 'trim|xss_clean|exact_length[19]'
                                            ),
                                        array(
                                            'field'   => 'manage_channel', 
                                            'label'   => 'Manage Channel', 
                                            'rules'   => 'trim|xss_clean|required|max_length[1]'
                                            ),
                                        array(
                                            'field'   => 'title_tag', 
                                            'label'   => 'Title Tag', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'meta_description', 
                                            'label'   => 'Meta Description', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'meta_keywords', 
                                            'label'   => 'Meta Keywords', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'meta_robots_ovid', 
                                            'label'   => 'Robots', 
                                            'rules'   => 'trim|xss_clean|required|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'open_graph_url', 
                                            'label'   => 'Open Graph Url', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'open_graph_type_ovid', 
                                            'label'   => 'Open Graph Type', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'open_graph_article_author', 
                                            'label'   => 'Open Graph Article Author', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'open_graph_site_name', 
                                            'label'   => 'Open Graph Site Name', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'open_graph_title', 
                                            'label'   => 'Open Graph Title', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'open_graph_image', 
                                            'label'   => 'Open Graph Image', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'open_graph_description', 
                                            'label'   => 'Open Graph Description', 
                                            'rules'   => 'trim|xss_clean|max_length[600]'
                                            ),
                                        array(
                                            'field'   => 'facebook_app_id', 
                                            'label'   => 'Facebook App Id', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'twitter_card_type_ovid', 
                                            'label'   => 'Twitter Card Card', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'twitter_card_url', 
                                            'label'   => 'Twitter Card Url', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'twitter_card_site', 
                                            'label'   => 'Twitter Card Site', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'twitter_card_title', 
                                            'label'   => 'Twitter Card Title', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'twitter_card_image', 
                                            'label'   => 'Twitter Card Image', 
                                            'rules'   => 'trim|xss_clean|max_length[255]'
                                            ),
                                        array(
                                            'field'   => 'twitter_card_description', 
                                            'label'   => 'Twitter Card Description', 
                                            'rules'   => 'trim|xss_clean|max_length[600]'
                                            ),
                                        ),
                'neoseo_entry' => array(
                                    array(
                                        'field'   => 'entry_id', 
                                        'label'   => 'Entry Id', 
                                        'rules'   => 'trim|xss_clean|required|integer|max_length[10]'
                                        ),
                                    array(
                                        'field'   => 'created_at', 
                                        'label'   => 'Created At', 
                                        'rules'   => 'trim|xss_clean|exact_length[19]'
                                        ),
                                    array(
                                        'field'   => 'updated_at', 
                                        'label'   => 'Updated At', 
                                        'rules'   => 'trim|xss_clean|exact_length[19]'
                                        ),
                                    array(
                                        'field'   => 'channel_id', 
                                        'label'   => 'Channel Id', 
                                        'rules'   => 'trim|xss_clean|required|integer|max_length[10]'
                                        ),
                                    array(
                                        'field'   => 'title_tag', 
                                        'label'   => 'Title Tag', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'meta_description', 
                                        'label'   => 'Meta Description', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'meta_keywords', 
                                        'label'   => 'Meta Keywords', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'meta_robots_ovid', 
                                        'label'   => 'Robots', 
                                        'rules'   => 'trim|xss_clean|required|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'open_graph_url', 
                                        'label'   => 'Open Graph Url', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'open_graph_type_ovid', 
                                        'label'   => 'Open Graph Type', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'open_graph_article_author', 
                                        'label'   => 'Open Graph Article Author', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'open_graph_site_name', 
                                        'label'   => 'Open Graph Site Name', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'open_graph_title', 
                                        'label'   => 'Open Graph Title', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'open_graph_image', 
                                        'label'   => 'Open Graph Image', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'open_graph_description', 
                                        'label'   => 'Open Graph Description', 
                                        'rules'   => 'trim|xss_clean|max_length[600]'
                                        ),
                                    array(
                                        'field'   => 'facebook_app_id', 
                                        'label'   => 'Facebook App Id', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'twitter_card_type_ovid', 
                                        'label'   => 'Twitter Card Card', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'twitter_card_url', 
                                        'label'   => 'Twitter Card Url', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'twitter_card_site', 
                                        'label'   => 'Twitter Card Site', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'twitter_card_title', 
                                        'label'   => 'Twitter Card Title', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'twitter_card_image', 
                                        'label'   => 'Twitter Card Image', 
                                        'rules'   => 'trim|xss_clean|max_length[255]'
                                        ),
                                    array(
                                        'field'   => 'twitter_card_description', 
                                        'label'   => 'Twitter Card Description', 
                                        'rules'   => 'trim|xss_clean|max_length[600]'
                                        ),
                                    )
                
                );