if (typeof wx === 'undefined') var wx = getApp().core;
var WxParse = require('../../../wxParse/wxParse.js');
var gSpecificationsModel = require('../../../components/goods/specifications_model.js');//商城多规格选择
var goodsBanner = require('../../../components/goods/goods_banner.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {
        pageType: "INTEGRAL",
        tab_detail: "active",
        tab_comment: "",
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        var self = this;
        if (options.integral) {
            self.setData({
                user_integral: options.integral,
            });
        }
        if (options.goods_id) {
            self.setData({
                id: options.goods_id,
            });
            self.getGoods();
        }
    },

    getGoods: function() {
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.integral.goods_info,
            data: {
                id: self.data.id
            },
            success: function(res) {
                if (res.code == 0) {
                    var detail = res.data.goods.detail;
                    WxParse.wxParse("detail", "html", detail, self);
                    getApp().core.setNavigationBarTitle({
                        title: res.data.goods.name,
                    })
                    var goods = res.data.goods;
                    goods.num = res.data.goods.goods_num;
                    goods.pic_list = res.data.goods.goods_pic_list;
                    self.setData({
                        goods: res.data.goods,
                        attr_group_list: res.data.attr_group_list,
                    });
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function(res) {
                            if (res.confirm) {
                                getApp().core.navigateTo({
                                    url: '/pages/integral-mall/index/index',
                                });
                            }
                        }
                    });
                }
            },
            complete: function(res) {
                setTimeout(function() {
                    getApp().core.hideLoading();
                }, 500);
            }
        });
    },


    // showAttrPicker: function() {
    //     var self = this;
    //     self.setData({
    //         show_attr_picker: true,
    //     });
    // },
    // hideAttrPicker: function() {
    //     var self = this;
    //     self.setData({
    //         show_attr_picker: false,
    //     });
    // },
    // attrClick: function(e) {
    //     var self = this;
    //     var attr_group_id = e.target.dataset.groupId;
    //     var attr_id = e.target.dataset.id;
    //     var attr_group_list = self.data.attr_group_list;
    //     for (var i in attr_group_list) {
    //         if (attr_group_list[i].attr_group_id != attr_group_id)
    //             continue;
    //         for (var j in attr_group_list[i].attr_list) {
    //             if (attr_group_list[i].attr_list[j].attr_id == attr_id) {
    //                 attr_group_list[i].attr_list[j].checked = true;
    //             } else {
    //                 attr_group_list[i].attr_list[j].checked = false;
    //             }
    //         }
    //     }
    //     self.setData({
    //         attr_group_list: attr_group_list,
    //     });
    //     var check_attr_list = [];
    //     var check_all = true;
    //     for (var i in attr_group_list) {
    //         var group_checked = false;
    //         for (var j in attr_group_list[i].attr_list) {
    //             if (attr_group_list[i].attr_list[j].checked) {
    //                 var attrs = {
    //                     'attr_id': attr_group_list[i].attr_list[j].attr_id,
    //                     'attr_name': attr_group_list[i].attr_list[j].attr_name
    //                 }
    //                 check_attr_list.push(attrs);
    //             }
    //         }
    //     }
    //     var goods = self.data.goods;
    //     var inattr = goods.attr;
    //     var inattr_id = [];
    //     var price = 0;
    //     var integral = 0;
    //     for (var x in inattr) {
    //         if (JSON.stringify(inattr[x].attr_list) == JSON.stringify(check_attr_list)) {
    //             if (parseFloat(inattr[x].price) > 0) {
    //                 price = inattr[x].price;
    //             } else {
    //                 price = goods.price;
    //             }
    //             if (parseInt(inattr[x].integral) > 0) {
    //                 integral = inattr[x].integral
    //             } else {
    //                 integral = goods.integral
    //             }
    //             self.setData({
    //                 attr_integral: integral,
    //                 attr_num: inattr[x].num,
    //                 attr_price: price,
    //                 status: 'attr',
    //             });
    //         }
    //     }
    // },

    showShareModal: function() {
        var self = this;
        self.setData({
            share_modal_active: "active",
            no_scroll: true,
        });
    },
    shareModalClose: function() {
        var self = this;
        self.setData({
            share_modal_active: "",
            no_scroll: false,
        });
    },

    exchangeGoods: function() {
        var self = this;
        if (!self.data.show_attr_picker) {
            self.setData({
                show_attr_picker: true,
            });
            return true;
        }
        var attr_group_list = self.data.attr_group_list;
        var checked_attr_list = [];
        for (var i in attr_group_list) {
            var attr = false;
            for (var j in attr_group_list[i].attr_list) {
                if (attr_group_list[i].attr_list[j].checked) {
                    attr = {
                        attr_id: attr_group_list[i].attr_list[j].attr_id,
                        attr_name: attr_group_list[i].attr_list[j].attr_name,
                    };
                    break;
                }
            }
            if (!attr) {
                getApp().core.showToast({
                    title: "请选择" + attr_group_list[i].attr_group_name,
                    image: "/images/icon-warning.png",
                });
                return true;
            } else {
                checked_attr_list.push({
                    attr_group_id: attr_group_list[i].attr_group_id,
                    attr_group_name: attr_group_list[i].attr_group_name,
                    attr_id: attr.attr_id,
                    attr_name: attr.attr_name,
                });
            }
        }
        var user_integral = self.data.user_integral;
        var attr_integral = self.data.attr_integral;
        var attr_num = self.data.attr_num
        if (parseInt(user_integral) < parseInt(attr_integral)) {
            getApp().core.showToast({
                title: "积分不足!",
                image: "/images/icon-warning.png",
            });
            return true;
        }
        if (attr_num <= 0) {
            getApp().core.showToast({
                title: "商品库存不足!",
                image: "/images/icon-warning.png",
            });
            return true;
        }
        var goods = self.data.goods;
        var attr_price = self.data.attr_price;
        var attr_integral = self.data.attr_integral;
        self.setData({
            show_attr_picker: false,
        });
        getApp().core.navigateTo({
            url: '/pages/integral-mall/order-submit/index?goods_info=' + JSON.stringify({
                goods_id: goods.id,
                attr: checked_attr_list,
                attr_price: attr_price,
                attr_integral: attr_integral
            }),
        });
    },
    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function(options) {
        getApp().page.onReady(this);

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function(options) {
        getApp().page.onShow(this);
        gSpecificationsModel.init(this);
        goodsBanner.init(this);

    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function(options) {
        getApp().page.onHide(this);

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function(options) {
        getApp().page.onUnload(this);

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function(options) {
        getApp().page.onPullDownRefresh(this);

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function(options) {
        getApp().page.onReachBottom(this);

    },
})