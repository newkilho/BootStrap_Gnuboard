<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(in_array(substr($_SERVER['SCRIPT_FILENAME'], strlen(G5_BBS_PATH)), array('/register.php', '/register_form.php')))
{
	include_once(G5_THEME_PATH.'/head.def.php');
	return;
}

include_once(G5_THEME_PATH.'/head.def.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

include_once(G5_THEME_PATH.'/functions.php');

// 메뉴 계산 (/head.php 응용)
$sql = " select *
			from {$g5['menu_table']}
			where me_use = '1'
			  and length(me_code) = '2'
			order by me_order, me_id ";
$result = sql_query($sql, false);
$gnb_zindex = 999; // gnb_1dli z-index 값 설정용
$menu_datas = array();

for ($i=0; $row=sql_fetch_array($result); $i++) {
	$menu_datas[$i] = $row;

	$sql2 = " select *
				from {$g5['menu_table']}
				where me_use = '1'
				  and length(me_code) = '4'
				  and substring(me_code, 1, 2) = '{$row['me_code']}'
				order by me_order, me_id ";
	$result2 = sql_query($sql2);
	for ($k=0; $row2=sql_fetch_array($result2); $k++) {
		$menu_datas[$i]['sub'][$k] = $row2;
	}

}

get_active_menu($menu_datas);

$g5['sidebar']['right'] = !defined('_INDEX_')&&is_file(G5_PATH.'/sidebar.right.php') ? true : false;
?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
	<a class="navbar-brand" href="<?=G5_URL?>">
		<img height="48" src="<?php echo G5_URL ?>/logo.png">
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#TopNavbar" aria-controls="TopNavbar" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="TopNavbar">
		<ul class="navbar-nav mr-auto">
			<?php echo get_layout_menu($menu_datas) ?>
			<?php echo outlogin('theme/basic') ?>
		</ul>
		<form class="form-inline my-2 my-lg-0 d-none d-lg-inline" action="<?php echo G5_BBS_URL ?>/search.php" method="get">
			<input type="hidden" name="sfl" value="wr_subject||wr_content">
			<input type="hidden" name="sop" value="and">

			<input class="form-control mr-sm-2" name="stx" type="search" placeholder="Search" aria-label="Search">
			<button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
		</form>
	</div>
</nav>

<main role="main" class="container mt-4">

	<?php if($g5['sidebar']['right']) { ?>
		<div class="row">
			<div class="col-lg-9">
	<?php } ?>