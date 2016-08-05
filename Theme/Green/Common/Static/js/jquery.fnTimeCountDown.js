/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//倒计时效果
$.extend($.fn, {
    fnTimeCountDown: function (d, callback) {
        this.each(function () {
            var $this = $(this);
            var o = {
                hm: $this.find(".hm"),
                sec: $this.find(".sec"),
                mini: $this.find(".mini"),
                hour: $this.find(".hour"),
            };
            var f = {
                haomiao: function (n) {
                    if (n < 10)
                        return "00" + n.toString();
                    if (n < 100)
                        return "0" + n.toString();
                    return n.toString();
                },
                zero: function (n) {
                    var _n = parseInt(n, 10); //解析字符串,返回整数
                    if (_n > 0) {
                        if (_n <= 9) {
                            _n = "0" + _n
                        }
                        return String(_n);
                    } else {
                        return "00";
                    }
                },
                dv: function () {
                    var _d = $this.data("end") || d;
                    var now = new Date(), endDate = new Date(_d);
                    //现在将来秒差值

                    var dur = (endDate - now.getTime()) / 1000, mss = endDate - now.getTime(),
                            pms = {
                                hm: "000",
                                sec: "00",
                                mini: "00",
                                hour: "00",
                            };
                    if (mss > 0) {
                        pms.hm = f.haomiao(mss % 1000);
                        pms.sec = f.zero(dur % 60);
                        pms.mini = Math.floor((dur / 60)) > 0 ? f.zero(Math.floor((dur / 60)) % 60) : "00";
                        pms.hour = Math.floor((dur / 3600)) > 0 ? f.zero(Math.floor((dur / 3600)) % 24) : "00";
                    } else {
                        pms.hour = pms.mini = pms.sec = "00";
                        pms.hm = "000";
                    }
                    return pms;
                },
                ui: function () {
                    if (o.hm) {
                        o.hm.html(f.dv().hm);
                    }
                    if (o.sec) {
                        o.sec.html(f.dv().sec);
                    }
                    if (o.mini) {
                        o.mini.html(f.dv().mini);
                    }
                    if (o.hour) {
                        o.hour.html(f.dv().hour);
                    }
                    var sum = parseInt(f.dv().hm) + parseInt(f.dv().sec) + parseInt(f.dv().mini) + parseInt(f.dv().hour);
                    if (sum == 0) {
                        if (callback) {
                            callback();
                        }
                        return false;
                    }
                    setTimeout(f.ui, 1);
                }
            };
            f.ui();
        });
    }
});


