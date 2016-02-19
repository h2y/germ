        </div>
        <footer id="footer" class="yahei clearfix">
  		<p class="left"><?php echo dopt('d_notice_bottom');?></p>
			<p class="right">Powered by <a href="https://cn.wordpress.org/" target="_blank" rel="nofollow">WordPress</a>, and theme by <a href="http://hzy.pw/p/1933" target="_blank">Moshel</a>.</p>
		</footer>
	</div>

	<img id="qrimg" src="http://s.jiathis.com/qrcode.php?url=<?php echo home_url() ?>"/>
	<a id="qr" href="javascript:;"><i class="fa fa-qrcode"></i></a>
	<a id="gotop" title="点击返回页顶" href="javascript:;"><i class="fa fa-arrow-up"></i></a>

<?php
if( dopt('d_track_b') != '' ) '<div class="static-hide">'.dopt('d_track').'</div>';
if( dopt('d_footcode_b') != '' ) echo dopt('d_footcode');

if( is_single() && dopt('d_sideroll_single_b') ){
	$sr_1 = dopt('d_sideroll_single_1');
	$sr_2 = dopt('d_sideroll_single_2');
}elseif( is_home() && dopt('d_sideroll_index_b') ){
	$sr_1 = dopt('d_sideroll_index_1');
	$sr_2 = dopt('d_sideroll_index_2');
}elseif( dopt('d_sideroll_page_b') ){
	$sr_1 = dopt('d_sideroll_page_1');
	$sr_2 = dopt('d_sideroll_page_2');
}else{
	$sr_1 = -24;
	$sr_2 = -38;
}
echo '<script>var asr_1 = '.$sr_1.',asr_2 = '.$sr_2.';</script>';

wp_footer();

?>
</body>
</html>
