//点击登录弹出登录注册框
//弹出登录框

$('#login').on('click', function() {
	layer.open({
		type: 2,
		title: false,
		shadeClose: true, //点击遮罩关闭层
		area: ['482px', '350px'],
		content: 'http://127.0.0.1:8020/layui/login.html'
	});
});


//短信验证码倒计时
var countdown = 60;

function settime(obj) {
	if (countdown == 0) {
		obj.removeAttribute("disabled");
		obj.value = "免费获取验证码";
		countdown = 60;
		return;
	} else {
		obj.setAttribute("disabled", true);
		obj.value = "重新发送(" + countdown + ")";
		countdown--;
	}
	setTimeout(function() {
		settime(obj)
	}, 1000)
}

//短信验证码倒计时(兼容模式处理)
var countdown = 60;
$("#btn").click(function() {
	if (countdown == 0) {
		obj.removeAttribute("disabled");
		obj.value = "免费获取验证码";
		countdown = 60;
		return;
	} else {
		obj.setAttribute("disabled", true);
		obj.value = "重新发送(" + countdown + ")";
		countdown--;
	}
	setTimeout(function() {
		settime(obj);
	}, 1000);
})

//全国商品/本地服务切换激活样式
$('.nationorlocal a').click(function(){
	$(this).addClass('active').siblings().removeClass('active');
})
//一元爱拍选项卡切换添加边框效果

$('.nationgoods li').click(function(){
	$(this).addClass('active').siblings().removeClass('active');
})
//一元爱拍排序种类点击选中样式
$('.sort li').click(function(){
	$(this).addClass('active').siblings().removeClass('active');
})
