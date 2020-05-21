<?php
if (defined('NC_OPTIMIZEUP_DIR')):
        require_once NC_OPTIMIZEUP_DIR . '/lib/phpQuery/phpQuery.php';
        add_filter('the_posts', 'nc_nextpage_filter_the_posts');
        function nc_nextpage_filter_the_posts($posts) {
                if (!is_admin()) {
                        foreach( $posts as &$the_post ) {
                                $nextpage = get_post_meta($the_post->ID, 'nc_nextpage', true);
                                $imagenum = get_post_meta($the_post->ID, 'nextpage_imagenum', true);
                                if ( $nextpage && !preg_match( '#<!--nextpage-->#i', $the_post->post_content ) ) {
                                        $html = phpQuery::newDocument(wpautop($the_post->post_content));
                                        phpQuery::selectDocument($html);
                                        $num = 0;
                                        foreach (pq('img') as $img) {
                                                $num++;
                                                if ($num == $imagenum) {
                                                        if (count(pq($img)->parents('p'))) {
                                                                pq($img)->parents('p')->after('<!--nextpage-->');
                                                        } else {
                                                                pq($img)->parents('figure')->after('<!--nextpage-->');
                                                        }
                                                        
                                                        $num = 0;
                                                }
                                        }
                                        $the_post->post_content = $html->htmlOuter();
                                }
                        }
                }
                return $posts;
        }
endif;
