var api = require("../../../api.js"), utils = require("../../../utils/utils.js"), app = getApp();

Page({
    data: {
        now_date: new Date(),
    },
    onLoad: function(t) {
        app.pageOnLoad(this, t), this.getPreview(t);
    },
    onReady: function() {},
    onShow: function() {
        app.pageOnShow(this);
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    checkboxChange: function(t) {
        var a = t.target.dataset.pid, e = t.target.dataset.id, o = this.data.form_list, i = o[a].default[e].selected;
        o[a].default[e].selected = 1 != i, this.setData({
            form_list: o
        });
    },
    radioChange: function(t) {
        var a = t.target.dataset.pid, e = this.data.form_list;
        for (var o in e[a].default) t.target.dataset.id == o ? e[a].default[o].selected = !0 : e[a].default[o].selected = !1;
        this.setData({
            form_list: e
        });
    },
    inputChenge: function(t) {
        var a = t.target.dataset.id, e = this.data.form_list;
        e[a].default = t.detail.value, this.setData({
            form_list: e
        });
    },
    getPreview: function(t) {
        var o = this, a = JSON.parse(t.goods_info)[0];
        console.log(a);
        o.setData({
            attr: a.attr
        });
        var e = a.id;
        swan.showLoading({
            title: "正在加载",
            mask: !0
        });
        var i = JSON.stringify(a.attr);
        app.request({
            url: api.book.submit_preview,
            method: "post",
            data: {
                gid: e,
                attr: i
            },
            success: function(t) {
                console.log(t);
                if (0 == t.code) {
                    for (var a in t.data.form_list) "date" == t.data.form_list[a].type && (t.data.form_list[a].default = t.data.form_list[a].default ? t.data.form_list[a].default : utils.formatData(new Date())), 
                    "time" == t.data.form_list[a].type && (t.data.form_list[a].default = t.data.form_list[a].default ? t.data.form_list[a].default : "00:00");
                    var e = t.data.option;
                    e ? (1 == e.balance && (o.setData({
                        balance: !0,
                        pay_type: "BALANCE_PAY"
                    }), app.request({
                        url: api.user.index,
                        success: function(t) {
                            0 == t.code && swan.setStorageSync("user_info", t.data.user_info);
                        }
                    })), 1 == e.wechat && o.setData({
                        wechat: !0,
                        pay_type: "BAIDU_PAY"
                    })) : o.setData({
                        wechat: !0,
                        pay_type: "BAIDU_PAY"
                    }), o.setData({
                        goods: t.data.goods,
                        form_list: t.data.form_list,
                        pay_type: "BAIDU_PAY"
                    });

                } else swan.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {
                        t.confirm && swan.redirectTo({
                            url: "/pages/book/index/index"
                        });
                    }
                });
            },
            complete: function(t) {
                setTimeout(function() {
                    swan.hideLoading();
                }, 1e3);
            }
        });
    },
    booksubmit: function(a) {
        var e = this, t = e.data.pay_type;
        console.log(t);
        if (0 != e.data.goods.price) {
            if ("BALANCE_PAY" == t) {
                var o = swan.getStorageSync("user_info");
                swan.showModal({
                    title: "当前账户余额：" + o.money,
                    content: "是否使用余额",
                    success: function(t) {
                        t.confirm && e.submit(a);
                    }
                });
            }
            "BAIDU_PAY" == t && e.submit(a);
        } else e.submit(a);
    },
    submit: function(t) {
        var a = t.detail.formId, e = this, o = e.data.goods.id, i = JSON.stringify(e.data.attr), s = JSON.stringify(e.data.form_list), n = e.data.pay_type;
        console.log(e.data.form_list);
        swan.showLoading({
            title: "正在提交",
            mask: !0
        }), app.request({
            url: api.book.submit,
            method: "post",
            data: {
                gid: o,
                form_list: s,
                form_id: a,
                pay_type: n,
                attr: i
            },
            success: function(t) {
                if (0 == t.code) {
                    if (1 != t.type) return swan.showLoading({
                        title: "正在提交",
                        mask: !0
                    }), void swan.requestPolymerPayment({
                        orderInfo: {
                            "dealId": t.data.dealId,
                            "appKey": t.data.appKey,
                            "totalAmount": t.data.total_fee,
                            "tpOrderId": t.data.order_no,
                            "dealTitle": t.data.goods_name,
                            'signFieldsRange': '1',
                            "rsaSign": t.data.rsaSign,
                            "bizInfo": t.data.bizInfo
                        },
                        success: function(t) {
                            swan.showModal({
                                title: "提示",
                                content: "支付成功",
                                showCancel: !1,
                                confirmText: "确认",
                                success: function(e) {
                                    if (e.confirm) {
                                        swan.redirectTo({
                                            url: "/pages/book/order/order?status=1"
                                        });
                                    }
                                }
                            });
                        },
                        fail: function(err) {
                            swan.showModal({
                                title: "提示",
                                content: '支付失败：'+ err.errMsg,
                                showCancel: !1,
                                confirmText: "确认",
                                success: function(e) {
                                    e.confirm && swan.redirectTo({
                                        url: "/pages/book/order/order?status=0",
                                    });
                                }
                            });
                        },
                    });
                    swan.redirectTo({
                        url: "/pages/book/order/order?status=1"
                    });
                } else swan.showModal({
                    title: "提示",
                    content: t.msg,
                    showCancel: !1,
                    success: function(t) {}
                });
            },
            complete: function(t) {
                setTimeout(function() {
                    swan.hideLoading();
                }, 1e3);
            }
        });
    },
    switch: function(t) {
        this.setData({
            pay_type: t.currentTarget.dataset.type
        });
    },
    uploadImg: function(t) {
        var a = this, e = t.currentTarget.dataset.id, o = a.data.form_list;
        app.uploader.upload({
            start: function() {
                swan.showLoading({
                    title: "正在上传",
                    mask: !0
                });
            },
            success: function(t) {
                0 == t.code ? (o[e].default = t.data.url, a.setData({
                    form_list: o
                })) : a.showToast({
                    title: t.msg
                });
            },
            error: function(t) {
                a.showToast({
                    title: t
                });
            },
            complete: function() {
                swan.hideLoading();
            }
        });
    }
});