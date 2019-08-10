<?php
function get_active_menu($menu_datas)
{
	global $g5;

	foreach($menu_datas as $item)
	{
		$item['path'] = parse_url($item['me_link'])['path'].'/';
		$self['path'] = parse_url($_SERVER['REQUEST_URI'])['path'].'/';

		if($item['me_code'] == $g5['me_code'] || 
			(!$g5['me_code'] && !in_array($item['path'], array('', '/')) && strncmp($item['path'], $self['path'], strlen($item['path']))===0))
		{
			//echo $item['me_code'].' - '.$g5['me_code'].'<br />';
			//echo $item['path'].' - '.$self['path'].'<br />';
			//echo $item['path'] .' - '. strncmp($item['path'], $self['path'], strlen($item['path']));
			$g5['me_code'] = $item['me_code'];
		}

		if($item['sub']) get_active_menu($item['sub']);
	}
}

function get_layout_menu($menu_datas)
{
	global $g5;

	foreach($menu_datas as $item)
	{
		$item['act'] = $item['me_code'] == substr($g5['me_code'], 0, strlen($item['me_code'])) ? 'active' : '';

		if(!$item['sub'])
		{
			$output .= '<li class="nav-item"><a href="'.$item['me_link'].'" class="nav-link '.$item['act'].' '.$item['me_class'].'">'.$item['me_name'].'</a></li>';
		}
		else
		{
			$output .= '<li class="nav-item dropdown"><a href="'.$item['me_link'].'" class="nav-link dropdown-toggle '.$item['act'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$item['me_name'].'</a><div class="dropdown-menu">';

			foreach($item['sub'] as $item2)
			{
				$item2['act'] = $item2['me_code'] == substr($g5['me_code'], 0, strlen($item2['me_code'])) ? 'active' : '';

				if($item2['me_id']==-1)
					$output .= '<div class="dropdown-divider"></div>';
				else
					$output .= '<a href="'.$item2['me_link'].'" class="dropdown-item '.$item2['act'].' '.$item2['me_class'].'">'.$item2['me_name'].'</a>';
			}
			
			$output .= '</div></li>';
		}
	}

	return $output;
}

function get_layout_breadcrumb($menu_datas, $recursive=false)
{
	global $g5;

	foreach($menu_datas as $item)
	{
		if($item['me_code'] == substr($g5['me_code'], 0, strlen($item['me_code'])))
			if($item['me_code'] != $g5['me_code'])
				$output .= '<li><a href="'.$item['me_link'].'">'.$item['me_name'].'</a></li>';

		if($item['sub']) $output .= get_layout_breadcrumb($item['sub'], true);
	}

	if(!$recursive) $output = '<li><a href="'.G5_URL.'">Home</a></li>'.$output;

	return $output;
}

function get_member_info($mb_id, $name='', $email='', $homepage='')
{
    global $config;
    global $g5;
    global $bo_table, $sca, $is_admin, $member;

    $email_enc = new str_encrypt();
    $email = $email_enc->encrypt($email);
    $homepage = set_http(clean_xss_tags($homepage));

    $name     = get_text($name, 0, true);
    $email    = get_text($email);
    $homepage = get_text($homepage);

	$mb_ico_url = G5_IMG_URL.'/no_profile.gif';
	$mb_img_url = G5_IMG_URL.'/no_profile.gif';

    if ($mb_id)
	{
		$mb_icon_img = $mb_id.'.gif';

		if(file_exists(G5_DATA_PATH.'/member/'.substr($mb_id,0,2).'/'.$mb_icon_img))
			$mb_ico_url = G5_DATA_URL.'/member/'.substr($mb_id,0,2).'/'.$mb_icon_img;

		if(file_exists(G5_DATA_PATH.'/member_image/'.substr($mb_id,0,2).'/'.$mb_icon_img))
			$mb_img_url = G5_DATA_URL.'/member_image/'.substr($mb_id,0,2).'/'.$mb_icon_img;

		/*
        if ($config['cf_use_member_icon']) {
                if ($config['cf_use_member_icon'] == 2) // 회원아이콘+이름
		*/
	} else {
		if(!$bo_table)
		//  return $name;
		  return array('ico'=>$mb_ico_url, 'img'=>$mb_img_url, 'menu'=>'');

		$menu .= '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;sca='.$sca.'&amp;sfl=wr_name,1&amp;stx='.$name.'" title="'.$name.' 이름으로 검색" class="dropdown-item" rel="nofollow" onclick="return false;">'.$name.'</a>';
	}

	//$menu = '<div class="dropdown"><a href="#" data-toggle="dropdown"><a href="#" data-toggle="dropdown">test</a>';
	$menu = '<div class="dropdown-menu">';

    if($mb_id)
        $menu .= '<a href="'.G5_BBS_URL.'/memo_form.php?me_recv_mb_id='.$mb_id.'" class="dropdown-item" onclick="win_memo(this.href); return false;">쪽지보내기</a>';
    if($email)
        $menu .= '<a href="'.G5_BBS_URL.'/formmail.php?mb_id='.$mb_id.'&name='.urlencode($name).'&email='.$email.'" class="dropdown-item"  onclick="win_email(this.href); return false;">메일보내기</a>';
    if($homepage)
        $menu .= '<a href="'.$homepage.'" target="_blank">홈페이지</a>';
    if($mb_id)
        $menu .= '<a href="'.G5_BBS_URL.'/profile.php?mb_id='.$mb_id.'" onclick="win_profile(this.href); return false;" class="dropdown-item" >자기소개</a>';
    if($bo_table) {
        if($mb_id)
            $menu .= '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&sca='.$sca.'&sfl=mb_id,1&stx='.$mb_id.'" class="dropdown-item" >아이디로 검색</a>';
        else
            $menu .= '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table."&sca=".$sca.'&sfl=wr_name,1&stx='.$name.'" class="dropdown-item" >이름으로 검색</a>';
    }
    if($mb_id)
        $menu .= '<a href="'.G5_BBS_URL.'/new.php?mb_id='.$mb_id.'" class="dropdown-item" onclick="check_goto_new(this.href, event);">전체게시물</a>';
    if($is_admin == "super" && $mb_id) {
        $menu .= '<a href="'.G5_ADMIN_URL.'/member_form.php?w=u&mb_id='.$mb_id.'" class="dropdown-item" target="_blank">회원정보변경</a>';
        $menu .= '<a href="'.G5_ADMIN_URL.'/point_list.php?sfl=mb_id&stx='.$mb_id.'" class="dropdown-item" target="_blank">포인트내역</a>';
    }

	$menu .= '</div>';

    return array('ico'=>$mb_ico_url, 'img'=>$mb_img_url, 'menu'=>$menu);
}

function get_paging_k($write_pages, $cur_page, $total_page, $url, $add="")
{
    $url = preg_replace('#&amp;page=[0-9]*#', '', $url) . '&amp;page=';

    $str = '';
    //if ($cur_page > 1)
	//	$str .= '<li class="page-item"><a class="page-link" href="'.$url.'1'.$add.'"><i class="fas fa-fast-backward"></i></a></li>';

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1)
		$str .= '<li class="page-item"><a href="'.$url.($start_page-1).$add.'" class="page-link"><i class="fas fa-backward"></i></a></li>';

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<li class="page-item"><a href="'.$url.$k.$add.'" class="page-link">'.$k.'</a></li>';
            else
                $str .= '<li class="page-item active"><a href="#" class="page-link">'.$k.'</a></li>';
        }
    }

    if ($total_page > $end_page) $str .= '<li class="page-item"><a href="'.$url.($end_page+1).$add.'" class="page-link"><i class="fas fa-forward"></i></a></li>';

    //if ($cur_page < $total_page)
	//	$str .= '<li class="page-item"><a href="'.$url.$total_page.$add.'" class="page-link"><i class="fas fa-fast-forward"></i></a></li>';

    if ($str)
        return '<nav><ul class="pagination">'.$str.'</ul></nav>';
    else
        return '';
}
?>