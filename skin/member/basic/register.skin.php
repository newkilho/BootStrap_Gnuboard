<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);

//add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_URL.'/vendor/bootstrap/css/bootstrap.min.css">');
//add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_URL.'/vendor/fontawesome-free/css/all.min.css">');
//add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/custom.css">');
//add_javascript('<script src="'.G5_THEME_URL.'/vendor/bootstrap/js/bootstrap.min.js"></script>');

add_stylesheet('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">');
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/custom.css">');
?>

<div class="form-join">
<form name="fregister" action="<?php echo $register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

	<div class="text-center mb-5">
		<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>" class="logo"></a>
	</div>

	<?php
	// 소셜로그인 사용시 소셜로그인 버튼
	@include_once(get_social_skin_path().'/social_register.skin.php');
	?>

	<div class="mb-2">
		<div class="custom-control custom-checkbox">
			<input type="checkbox" class="custom-control-input" id="agree11" name="agree" value="1">
			<label class="custom-control-label" for="agree11">회원가입약관 동의</label>
		</div>
		<textarea class="form-control" rows="5" style="font-size: 12px;"><?php echo get_text($config['cf_stipulation']) ?></textarea>
	</div>

	<div class="mb-4">
		<div class="custom-control custom-checkbox">
			<input type="checkbox" class="custom-control-input" id="agree21" name="agree2" value="1">
			<label class="custom-control-label" for="agree21">개인정보처리방침안내 동의</label>
		</div>
		<textarea class="form-control" rows="5" style="font-size: 12px;"><?php echo get_text($config['cf_privacy']) ?></textarea>
	</div>

	<div>
		<input class="btn btn-primary btn-block" type="submit" value="다음">
	</div>
	
</form>
</div>

<script>
	function fregister_submit(f)
	{
		if (!f.agree.checked) {
			alert("회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
			f.agree.focus();
			return false;
		}

		if (!f.agree2.checked) {
			alert("개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
			f.agree2.focus();
			return false;
		}

		return true;
	}

	jQuery(function($){
		// 모두선택
		$("input[name=chk_all]").click(function() {
			if ($(this).prop('checked')) {
				$("input[name^=agree]").prop('checked', true);
			} else {
				$("input[name^=agree]").prop("checked", false);
			}
		});
	});
</script>