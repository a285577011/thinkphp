
/*下面是购物车加减及总价计算的css*/
$(function () {
    $(".check-one").click(function () {
        total_count_money();
    });

    //模态框删除事件
    $('._btn_del_modal_id').click(function () {
        var id = $(this).attr('data-id');
        if (id == 'sel') {//删除选中的
            var ids = $('#cartTable input.check-one:checked');
            for (var i = 0; i < ids.length; i++) {
                var tr = $(ids[i]).parents('tr');
                sendDel(tr, function (data) { });
                $(tr).remove();
            }
            $('.delete-pop').modal("hide");
        } else {//单个删除
            sendDel(this, function (data) {
                if (data.status != 1) {
                    alert(data.message);
                } else {
                    $('#cartTable tr[data-id=' + id + ']').remove();
                    $('.delete-pop').modal("hide");
                }
            });
        }

    });

    //单个删除
    $('._delete').click(function () {
        var id = $(this).parents('tr').attr('data-id');
        $('._btn_del_modal_id').attr('data-id', id);
        $('.delete-pop').modal("show");
    });

    //删除选中的
    $('.delete').click(function () {
        $('._btn_del_modal_id').attr('data-id', 'sel');
        $('.delete-pop').modal("show");
    });

    $('._btn_del_modal_id_hide').click(function () {
        $('.delete-pop').modal("hide");
    });

    $(".allselect").click(function () {
        if (this.checked) {
            $("#cartTable input[type='checkbox']").each(function () {
                this.checked = true;
            });
        } else {
            $("#cartTable input[type='checkbox']").each(function () {
                this.checked = false;
            });
        }
        total_count_money();
        
    });


// 加
    $(".add").click(function () {
        var input = $(this).siblings(".count");
        var obj = $(this).parents(".d4");
        var per = parseFloat(obj.siblings(".d3").find(".per").text());//获取当前一行的单价
        var num = '';
        var price = '';
        input.attr("value", parseInt(input.attr("value")) + 1);//数量加1
        num = input.attr("value");
        price = num * per;//
        obj.siblings(".d5").find(".cart-price").text(price);
        sendChange(this);
    });

// 减
    $(".reduce").click(function () {
        var input = $(this).siblings(".count");
        var obj = $(this).parents(".d4");
        var per = parseFloat(obj.siblings(".d3").find(".per").text());//获取当前一行的单价
        var num = '';
        var price = '';
        var Val = parseInt(input.attr("value"));
        if (Val <= 1) {
            Val = 2;
        }
        input.attr("value", parseInt(Val) - 1);//数量减1
        num = input.attr("value");
        price = num * per;//
        obj.siblings(".d5").find(".cart-price").text(price);
        sendChange(this);
    });
});

var total_count_money = function () {
    var ids = $('#cartTable input.check-one:checked');
    var money = 0;
    for (var i = 0; i < ids.length; i++) {
        var tr = $(ids[i]).parents('tr');
        var num = $(tr).find('.count-input').val();
        money += parseInt(num);
    }

    $('#selectedTotal').html(ids.length);
    $('#priceTotal').html(money);
    gen_cart_list();
};





//window.onload = function () {
//    if (!document.getElementsByClassName) {
//        document.getElementsByClassName = function (cls) {
//            var ret = [];
//            var els = document.getElementsByTagName('*');
//            for (var i = 0, len = els.length; i < len; i++) {
//
//                if (els[i].className.indexOf(cls + ' ') >= 0 || els[i].className.indexOf(' ' + cls + ' ') >= 0 || els[i].className.indexOf(' ' + cls) >= 0) {
//                    ret.push(els[i]);
//                }
//            }
//            return ret;
//        }
//    }
//
//    var table = document.getElementById('cartTable'); // 购物车表格
//    var selectInputs = document.getElementsByClassName('check'); // 所有勾选框
//    var checkAllInputs = document.getElementsByClassName('check-all') // 全选框
//    var tr = table.children[1].rows; //行
//    var selectedTotal = document.getElementById('selectedTotal'); //已选商品数目容器
//    var priceTotal = document.getElementById('priceTotal'); //总计
//    var deleteAll = document.getElementById('deleteAll'); // 删除全部按钮
//    /*    var selectedViewList = document.getElementById('selectedViewList'); //浮层已选商品列表容器*/
//    var selected = document.getElementById('selected'); //已选商品
//    var table_foot = document.getElementById('table_foot');
//
//    // 更新总数和总价格，已选浮层
//    function getTotal() {
//        var seleted = 0;
//        var price = 0;
//        var HTMLstr = '';
//        for (var i = 0, len = tr.length; i < len; i++) {
//            if (tr[i].getElementsByTagName('input')[0].checked) {
//                tr[i].className = 'on';
//                seleted += parseInt(tr[i].getElementsByTagName('input')[1].value);
//                price += parseFloat(tr[i].cells[5].innerHTML);
//                HTMLstr += '<div><img src="' + tr[i].getElementsByTagName('img')[0].src + '"><span class="del" index="' + i + '">取消选择</span></div>'
//            } else {
//                tr[i].className = '';
//            }
//        }
//        selectedTotal.innerHTML = seleted;
//        priceTotal.innerHTML = price.toFixed(2);
//        selectedViewList.innerHTML = HTMLstr;
//
//        if (seleted == 0) {
//            table_foot.className = 'table_foot';
//        }
//    }
//    // 计算单行价格
//    function getSubtotal(tr) {
//        var cells = tr.cells;
//        var price = cells[3]; //单价
//        var subtotal = cells[5]; //小计td
//        var countInput = tr.getElementsByTagName('input')[1]; //数目input
//        var span = tr.getElementsByTagName('span')[1]; //-号
//        //写入HTML
//        /* subtotal.innerHTML =("￥" +(parseInt(countInput.value) * parseFloat(price.innerHTML)).toFixed(2));*/
//        subtotal.innerHTML = (parseInt(countInput.value) * parseFloat(price.innerHTML)).toFixed(2);
//        //如果数目只有一个，把-号去掉
//        if (countInput.value == 1) {
//            span.innerHTML = '';
//        } else {
//            span.innerHTML = '-';
//        }
//    }
//
//    // 点击选择框
//    for (var i = 0; i < selectInputs.length; i++) {
//        selectInputs[i].onclick = function () {
//            if (this.className.indexOf('check-all') >= 0) { //如果是全选，则吧所有的选择框选中
//                for (var j = 0; j < selectInputs.length; j++) {
//                    selectInputs[j].checked = this.checked;
//                }
//            }
//            if (!this.checked) { //只要有一个未勾选，则取消全选框的选中状态
//                for (var i = 0; i < checkAllInputs.length; i++) {
//                    checkAllInputs[i].checked = false;
//                }
//            }
//            getTotal();//选完更新总计
//        }
//    }
//
//    // 显示已选商品弹层
//    selected.onclick = function () {
//        if (selectedTotal.innerHTML != 0) {
//            table_foot.className = (table_foot.className == 'table_foot' ? 'table_foot show' : 'table_foot');
//        }
//    }
//
//    //已选商品弹层中的取消选择按钮
//    /*    selectedViewList.onclick = function (e) {
//     var e = e || window.event;
//     var el = e.srcElement;
//     if (el.className=='del') {
//     var input =  tr[el.getAttribute('index')].getElementsByTagName('input')[0]
//     input.checked = false;
//     input.onclick();
//     }
//     }*/
//
//    //为每行元素添加事件
//    for (var i = 0; i < tr.length; i++) {
//        //将点击事件绑定到tr元素
//        tr[i].onclick = function (e) {
//            var e = e || window.event;
//            var el = e.target || e.srcElement; //通过事件对象的target属性获取触发元素
//            var cls = el.classList[0]; //触发元素的class
//            var countInout = this.getElementsByTagName('input')[1]; // 数目input
//            var value = parseInt(countInout.value); //数目
//            //通过判断触发元素的class确定用户点击了哪个元素
//            switch (cls) {
//                case 'add': //点击了加号
//                    countInout.value = value + 1;
//                    getSubtotal(this);
//                    sendChange(el);
//                    break;
//                case 'reduce': //点击了减号
//                    if (value > 1) {
//                        countInout.value = value - 1;
//                        getSubtotal(this);
//                         sendChange(el);
//                    }
//                    break;
//                case 'delete': //点击了删除
//                    var conf = confirm('确定删除此商品吗？');
//                    if (conf) {
//                        sendDel(el);
//                        this.parentNode.removeChild(this);
//                    }
//                    break;
//            }
//            getTotal();
//        }
//        // 给数目输入框绑定keyup事件
//        tr[i].getElementsByTagName('input')[1].onkeyup = function () {
//            var val = parseInt(this.value);
//            if (isNaN(val) || val <= 0) {
//                val = 1;
//            }
//            if (this.value != val) {
//                this.value = val;
//            }
//            getSubtotal(this.parentNode.parentNode); //更新小计
//            getTotal(); //更新总数
//            sendChange(this);
//        }
//    }
//    // 点击全部删除
//    deleteAll.onclick = function () {
//        if (selectedTotal.innerHTML != 0) {
//            var con = confirm('确定删除所选商品吗？'); //弹出确认框
//            if (con) {
//                for (var i = 0; i < tr.length; i++) {
//                    // 如果被选中，就删除相应的行
//                    if (tr[i].getElementsByTagName('input')[0].checked) {
//                        sendDel($(tr[i]).find('._id'));
//                        tr[i].parentNode.removeChild(tr[i]); // 删除相应节点
//                        i--; //回退下标位置
//                        
//                    }
//                }
//            }
//        } else {
//            alert('请选择商品！');
//        }
//        getTotal(); //更新总数
//    }
//    // 默认全选
//    checkAllInputs[0].checked = true;
//    checkAllInputs[0].onclick();
//}




///*号码弹出框*/
//$('#gsrecentmore').on('click', function () {
//    layer.open({
//        type: 2,
//        title: false,
//        shadeClose: true, //点击遮罩关闭层
//        area: ['484px', '380px'],
//        content: 'file:///E:/I.CN/web/pc/oneyuan/itsallnumber.html'
//    });
//});

/*协议弹窗框*/
$('#agreement').on('click', function () {
    layer.open({
        type: 1,
        title: false,
        shadeClose: true, //点击遮罩关闭层
        area: ['462px', '112px'],
        content: $('.agreement_pop'),
    });
});

//<!--支付完成弹出框-->
//$('#sure_button').on('click', function () {
//    layer.open({
//        type: 1,
//        title: false,
//        shadeClose: true, //点击遮罩关闭层
//        area: ['424px', '188px'],
//        content: $('.pay_pop'),
//    });
//});




///*支付方式的转换*/
//$(".bank_box").hide();
//$(function () {
//    $(".payfor").click(function () {
//        if ($(this).attr("value") == "1") {
//            $(".alipay_box").show();
//            $(".bank_box").hide();
//        } else if ($(this).attr("value") == "2") {
//            $(".alipay_box").hide();
//            $(".bank_box").show();
//        }
//
//    });
//});

///*选中有边线框*/
//$(".alipay_frame").addClass("frame_border")
//$(function () {
//    $(".alipay_box input").click(function () {
//        if ($(this).attr("value") == "1") {
//            $(".alipay_frame").addClass("frame_border")
//            $(".wechat_frame").removeClass("frame_border")
//        } else if ($(this).attr("value") == "2") {
//            $(".alipay_frame").removeClass("frame_border")
//            $(".wechat_frame").addClass("frame_border")
//        }
//    })
//
//})

//$(".bank_box .bank_frame").eq(0).addClass("bank_border");
//$(function () {
//    $(".bank_box input").click(function () {
//        if ($(this).attr("value") == "1") {
//            $(".bank_box .bank_frame").eq(0).addClass("bank_border").siblings().removeClass("bank_border")
//        } else if ($(this).attr("value") == "2") {
//            $(".bank_box .bank_frame").eq(1).addClass("bank_border").siblings().removeClass("bank_border")
//        } else if ($(this).attr("value") == "3") {
//            $(".bank_box .bank_frame").eq(2).addClass("bank_border").siblings().removeClass("bank_border")
//        } else if ($(this).attr("value") == "4") {
//            $(".bank_box .bank_frame").eq(3).addClass("bank_border").siblings().removeClass("bank_border")
//        } else if ($(this).attr("value") == "5") {
//            $(".bank_box .bank_frame").eq(4).addClass("bank_border").siblings().removeClass("bank_border")
//        } else if ($(this).attr("value") == "6") {
//            $(".bank_box .bank_frame").eq(5).addClass("bank_border").siblings().removeClass("bank_border")
//        } else if ($(this).attr("value") == "7") {
//            $(".bank_box .bank_frame").eq(6).addClass("bank_border").siblings().removeClass("bank_border")
//        } else if ($(this).attr("value") == "8") {
//            $(".bank_box .bank_frame").eq(7).addClass("bank_border").siblings().removeClass("bank_border")
//        } else if ($(this).attr("value") == "9") {
//            $(".bank_box .bank_frame").eq(8).addClass("bank_border").siblings().removeClass("bank_border")
//        } else if ($(this).attr("value") == "10") {
//            $(".bank_box .bank_frame").eq(9).addClass("bank_border").siblings().removeClass("bank_border")
//        }
//    })
//
//})


//
///*相关微商*/
//var m = 0
//$(".section").mouseenter(function () {
//
//    m = $(".section").index(this);
//    $(".section span").eq(m).addClass("xin");
//
//})
//$(".section").mouseleave(function () {
//
//    n = $(".section").index(this);
//    $(".section span").eq(n).removeClass("xin");
//
//})
//
//var j = 0
//$(".section a p").hide();
//$(".section").mouseenter(function () {
//    j = $(".section").index(this);
//    $(".section a p").eq(j).stop(true, false).show();
//})
//$(".section").mouseleave(function () {
//    $(".section a p").eq(j).stop(true, false).hide();
//})

// JavaScript Document