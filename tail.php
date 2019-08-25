<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/tail.php');
    return;
}

if(in_array(substr($_SERVER['SCRIPT_FILENAME'], strlen(G5_PATH)), array('/bbs/register.php', '/bbs/register_form.php')))
{
	include_once(G5_THEME_PATH."/tail.sub.php");
	return;
}

?>
		<?php if($g5['sidebar']['right']) { ?>
		</div>

		<div class="col-lg-3 mt-4 mt-lg-0">
			<?php @include G5_PATH.'/sidebar.right.php'; ?>
		</div>
		<? } ?>

</main>

<footer id="footer">
	<div class="footer-copyright">
		<div class="container py-2">
			<div class="row py-4">
				<div class="col-lg-2 d-flex align-items-center justify-content-center justify-content-lg-start mb-2 mb-lg-0">
					<a href="<?php echo G5_URL ?>" class="logo pr-0 pr-lg-3">
						<img src="<?php echo G5_URL ?>/logo.png" height="33">
					</a>
				</div>
				<div class="col-lg-5 d-flex align-items-center justify-content-center justify-content-lg-start mb-4 mb-lg-0">
					<p>© Copyright 2019. All Rights Reserved.</p>
				</div>
				<div class="col-lg-5 d-flex align-items-center justify-content-center justify-content-lg-end">
					<nav id="sub-menu">
						<ul class="list-unstyled list-inline">
							<li class="list-inline-item"><a href="<?php echo get_pretty_url('content', 'company'); ?>"> 회사소개</a></li>
							<li class="list-inline-item"><a href="<?php echo get_pretty_url('content', 'privacy'); ?>"> 개인정보</a></li>
							<li class="list-inline-item"><a href="<?php echo get_pretty_url('content', 'provision'); ?>"> 이용약관</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</footer>

<?php
if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<!-- } 하단 끝 -->

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>