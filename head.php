<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

switch(substr($_SERVER['SCRIPT_FILENAME'], strlen(G5_PATH)))
{
	case '/bbs/register.php':
	case '/bbs/register_form.php':
	case '/bbs/register_result.php':
	case '/plugin/social/register_member.php':
		include_once(G5_THEME_PATH.'/head.def.php');
		return;
		break;
}

include_once(G5_THEME_PATH.'/head.def.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

include_once(G5_THEME_PATH.'/functions.php');

$menu_data = get_menu_db(0, true);
get_active_menu($menu_data);

$g5['sidebar']['right'] = !defined('_INDEX_')&&is_file(G5_PATH.'/sidebar.right.php') ? true : false;

if(defined('_INDEX_')) include G5_THEME_PATH.'/newwin.inc.php';
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
	<div class="container">
		<a class="navbar-brand" href="<?php echo G5_URL; ?>">
			<img height="48" src="<?php echo G5_IMG_URL; ?>/logo.png">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#TopNavbar" aria-controls="TopNavbar" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="TopNavbar">
			<ul class="navbar-nav mr-auto">
				<?php echo get_layout_menu($menu_data) ?>
				<?php echo outlogin('theme/basic') ?>
			</ul>
			<form class="form-inline my-2 my-lg-0 d-none d-lg-inline" action="<?php echo G5_BBS_URL ?>/search.php" method="get">
				<input type="hidden" name="sfl" value="wr_subject||wr_content">
				<input type="hidden" name="sop" value="and">

				<input class="form-control mr-sm-2" name="stx" type="search" placeholder="Search" aria-label="Search">
				<button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
	</div>
</nav>

<div class="container">

	<?php if($g5['sidebar']['right']) { ?>
		<div class="row">
			<div class="col-lg-9 mb-4">
	<?php } ?>