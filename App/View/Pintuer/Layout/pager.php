<?php if ( $pages > 0 ): ?>
    <ul class="pagination pagination-big border-main">
        <?php
            $links = 5; //链接数量
            $start = max( 1, $page - intval( $links / 2 ) ); 
            $end = min( $start + $links - 1, $pages ); 
            $start = max( 1, $end - $links + 1 ); 
        ?>
        <li class="margin-small-right margin-small-bottom"><a href="{:U($pageBaseUri,array_merge($query,array('page'=>1)))}"><i class="icon-angle-double-left"></i></a></li>
        <?php for ( $i = $start; $i <= $end; $i++ ): ?>
            <li class="margin-small-right margin-small-bottom <?php echo $i == $page ? 'active' : ''; ?>">
                <a href="{:U($pageBaseUri,array_merge($query,array('page'=>$i)))}"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
        <li class="margin-small-right margin-small-bottom"><a href="{:U($pageBaseUri,array_merge($query,array('page'=>$pages)))}"><i class="icon-angle-double-right"></i></a></li>
    </ul>
<?php endif; ?>