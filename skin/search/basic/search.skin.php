<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$search_skin_url.'/style.css">', 0);

$write_pages = chg_paging($write_pages);
?>

<!-- 전체검색 시작 { -->
<form name="fsearch" onsubmit="return fsearch_submit(this);" method="get">
<input type="hidden" name="srows" value="<?php echo $srows ?>">

<div class="card">
	<div class="card-body px-md-5 bg-light">
		<div class="row">
			<div class="col-6 col-md-2 pr-1 px-md-1 mb-2 mb-md-0">
				<?php echo str_replace('class="select"', 'class="custom-select form-control"', $group_select) ?>
				<script>document.getElementById("gr_id").value = "<?php echo $gr_id ?>";</script>
			</div>
			<div class="col-6 col-md-2 pl-1 px-md-1 mb-2 mb-md-0">
				<select name="sfl" id="sfl" class="custom-select form-control">
					<option value="wr_subject||wr_content"<?php echo get_selected($_GET['sfl'], "wr_subject||wr_content") ?>>제목+내용</option>
					<option value="wr_subject"<?php echo get_selected($_GET['sfl'], "wr_subject") ?>>제목</option>
					<option value="wr_content"<?php echo get_selected($_GET['sfl'], "wr_content") ?>>내용</option>
					<option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id") ?>>회원아이디</option>
					<option value="wr_name"<?php echo get_selected($_GET['sfl'], "wr_name") ?>>이름</option>
				</select>
			</div>
			<div class="col-12 col-md-4 px-md-1 mb-2 mb-md-0">
				<input type="text" name="stx" value="<?php echo $text_stx ?>" id="stx" required class="form-control" size="40">
			</div>
			<div class="col-8 col-md-2 pr-1 px-md-1 mb-0">
				<button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search" aria-hidden="true"></i> 검색</button>
			</div>
			<div class="col-4 col-md-2 pl-1 px-md-1 mb-0">
				<div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
					<label class="btn btn-secondary <?php echo ($sop == "and") ? "active" : ""; ?>">
						<input type="radio" value="and" <?php echo ($sop == "and") ? "checked" : ""; ?> id="sop_and" name="sop">
						AND
					</label>
					<label class="btn btn-secondary <?php echo ($sop == "or") ? "active" : ""; ?>">
						<input type="radio" value="or" <?php echo ($sop == "or") ? "checked" : ""; ?> id="sop_or" name="sop" >
						OR
					</label>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function fsearch_submit(f)
{
	if (f.stx.value.length < 2) {
		alert("검색어는 두글자 이상 입력하십시오.");
		f.stx.select();
		f.stx.focus();
		return false;
	}

	// 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.
	var cnt = 0;
	for (var i=0; i<f.stx.value.length; i++) {
		if (f.stx.value.charAt(i) == ' ')
			cnt++;
	}

	if (cnt > 1) {
		alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
		f.stx.select();
		f.stx.focus();
		return false;
	}

	f.action = "";
	return true;
}
</script>
</form>

<div class="py-2">
	<?php if ($stx) { if ($board_count) { ?>
	<?php
		$str_board_list = chg_board_list($str_board_list);
	?>
	<ul class="list-inline">
		<li class="list-inline-item">
			<a href="?<?php echo $search_query ?>&amp;gr_id=<?php echo $gr_id ?>" class="btn btn-primary <?php if(strpos($str_board_list, ' active"')===false) echo 'active' ?>">전체게시판 <?php if(strpos($str_board_list, ' active"')===false) { ?><span class="badge badge-light"><?php echo number_format($total_count) ?></span><?php } ?></a>
		</li>
		<?php echo $str_board_list ?>
	</ul>
	<?php } else { ?>
	검색된 자료가 없습니다.
	<?php } }  ?>
</div>

<div id="sch_result">

    <hr>

    <?php if ($stx && $board_count) { ?><section class="sch_res_list"><?php }  ?>
    <?php
    $k=0;
    for ($idx=$table_index, $k=0; $idx<count($search_table) && $k<$rows; $idx++) {
     ?>
        <h2><a href="<?php echo get_pretty_url($search_table[$idx], '', $search_query); ?>"><?php echo $bo_subject[$idx] ?> 게시판 내 결과</a></h2>
        <ul>
        <?php
        for ($i=0; $i<count($list[$idx]) && $k<$rows; $i++, $k++) {
            if ($list[$idx][$i]['wr_is_comment'])
            {
                $comment_def = '<span class="cmt_def"><i class="fa fa-commenting-o" aria-hidden="true"></i><span class="sound_only">댓글</span></span> ';
                $comment_href = '#c_'.$list[$idx][$i]['wr_id'];
            }
            else
            {
                $comment_def = '';
                $comment_href = '';
            }
         ?>

            <li>
                <div class="sch_tit">
                    <a href="<?php echo $list[$idx][$i]['href'] ?><?php echo $comment_href ?>" class="sch_res_title"><?php echo $comment_def ?><?php echo $list[$idx][$i]['subject'] ?></a>
                    <a href="<?php echo $list[$idx][$i]['href'] ?><?php echo $comment_href ?>" target="_blank" class="pop_a"><i class="fa fa-window-restore" aria-hidden="true"></i><span class="sound_only">새창</span></a>
                </div>
                <p><?php echo $list[$idx][$i]['content'] ?></p>
                <div class="sch_info">
                    <?php echo $list[$idx][$i]['name'] ?>
                    <span class="sch_datetime"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $list[$idx][$i]['wr_datetime'] ?></span>
                </div>
            </li>
        <?php }  ?>
        </ul>
    <?php }  ?>
    <?php if ($stx && $board_count) {  ?></section><?php }  ?>

    <?php echo $write_pages ?>

</div>
<!-- } 전체검색 끝 -->