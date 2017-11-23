/*
 * 现金业务
 * @Author: Admin
 * @Date:   2017-11-17 13:36:31
 * @Last Modified by:   Admin
 * @Last Modified time: 2017-11-23 14:18:51
 */
! function(win) {
	var cash = window.Cash = function(options) {
		// 修改订单
		this.editUrl = options.editUrl || '';
		// 创建成功后跳转页面
		this.successUrl = options.successUrl || '/';

		// 婚姻状况
		this.maritalSituation = options.maritalSituation || {};
		// 联系人关系
		this.contactRelationship = options.contactRelationship || {};
		// swiper实例
		this.swiper;
		// slides总数
		this.slides = 2;
		// 默认的联系人数量
		this.initContacts = 2;
		// 最多联系人数量
		this.maxContacts = 10;
		// 当前联系人数量（决定下一个联系人的ID）
		this.currContactId = 0;

		// 计算
		this.selects = [];
		this.checkboxs = [
			'isProtectionFee',
			'isVipServiceFee'
		];
		this.ids = [];
	}

	// 初始化
	cash.prototype.init = function() {
		var _this = this;
		// 设置fastclick
		FastClick.attach(document.body);
		// 设置窗口高度
		$('.main-box').height(window.innerHeight + 'px');
		// 初始化高度
		$('.commit-order-container').height((window.innerHeight - 70) + 'px');
		// 实例化swiper
		_this.swiper = new Swiper('.swiper-container', {
			onSlideChangeEnd: function(swiper) {
				switch (swiper.activeIndex) {
					case 0: // 第1步
						_this.step1(swiper.activeIndex);
						break;

					case 1: // 第2步
						_this.step2(swiper.activeIndex);
						break;
				}
			},
			onInit: function(swiper) {
				// 获取总步骤数
				$('#totalStep').html(swiper.slides.length);
				$('#currStep').html(swiper.activeIndex + 1);
				_this.step1(swiper.activeIndex);
			}
		});

		// 初始化联系人数量
		for (var i = 1; i <= _this.initContacts; i++) {
			_this.buildContactHTML(i);
			_this.currContactId = i;
		}

		// 绑定
		_this.bind();
	}

	// 绑定
	cash.prototype.bind = function() {
		var _this = this;

		// 婚姻状况
		$("#maritalSituation").select({
			title: "请选择婚姻状况",
			items: _this.maritalSituation,
			onChange: function(data) {
				$('input[name=marital]').val(data.values);
			}
		});

		// 监听添加联系人操作
		$('#addContactBtn').on('click', function() {
			if (_this.currContactId < _this.maxContacts) {
				_this.currContactId += 1;
				_this.buildContactHTML(_this.currContactId);
			} else {
				$.toast('最多添加' + _this.maxContacts + '个联系人信息', "text");
			}
		});

		// 监听上一步
		$('#prevStep').bind('click', function() {
			_this.swiper.slidePrev();
		});
	}

	// 第一步验证
	cash.prototype.step1 = function(currStep) {
		var _this = this;
		// 初始化
		_this.initStep(currStep);
		// 验证数据并提交
		var validator = $('#formStep1').validator({
			btnSubmit: '.nextStep1',
			ajaxPost: true,
			callback: function(res) {
				if (res.status) {
					_this.swiper.slideNext();
				} else {
					$.toast(res.message, "text");
				}
			}
		}).addRule([{
			ele: "input[name=gender]",
			datatype: /(^(男|女)$)/,
			nullmsg: "请选择客户性别",
			errormsg: "请选择客户性别"
		}, {
			ele: "input[name=marital]",
			datatype: '*2-20',
			nullmsg: "请选择婚姻状况",
			errormsg: "请选择婚姻状况"
		}, {
			ele: "input[name=cardAddress]",
			datatype: '*0-200',
			nullmsg: "请输入户籍地址",
			errormsg: "请输入户籍地址"
		}, {
			ele: "input[name=currentAddress]",
			datatype: '*0-200',
			nullmsg: "请输入居住地址",
			errormsg: "请输入居住地址"
		}, {
			ele: "input[name=jobName]",
			datatype: '*0-200',
			nullmsg: "请输入工作单位",
			errormsg: "请输入工作单位"
		}, {
			ele: "input[name=jobAddress]",
			datatype: '*0-200',
			nullmsg: "请输入单位地址",
			errormsg: "单位地址长度在200个字符以内"
		}, {
			ele: "input[name=jobPhone]",
			datatype: /^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|17[0-9]|14[57])[0-9]{8}$/,
			ignore: "ignore",
			nullmsg: "请输入单位电话",
			errormsg: "请输入单位电话"
		}, {
			ele: "input[name=wechat]",
			datatype: '*0-40',
			nullmsg: "请输入微信账号",
			errormsg: "微信账号长度在40个字符以内"
		}, {
			ele: "input[name=qq]",
			datatype: '*0-20',
			nullmsg: "请输入QQ账号",
			errormsg: "QQ账号长度在20个字符以内"
		}, {
			ele: "input[name=alipay]",
			datatype: '*0-40',
			ignore: "ignore",
			nullmsg: "请输入支付宝账号",
			errormsg: "支付宝账号长度在20个字符以内"
		}]);
	}

	// 第二步验证
	cash.prototype.step2 = function(currStep) {
		var _this = this;
		// 初始化
		_this.initStep(currStep);
		// 验证数据并提交
		var validator = $('#formStep2').validator({
			btnSubmit: '.nextStep2',
			ajaxPost: true,
			callback: function(res) {
				if (res.status) {
					// 获取提交数据
					var data = $.extend({},
						$('#formStep1').serializeObject(),
						$('#formStep2').serializeObject()
					);

					$.ajaxPost(this.createUrl, data, function(res) {
						if (res.status) {
							$.toast(res.message, function() {
								// Cache.batchDel();
								window.location = _this.successUrl + '?orderId=' + res.data.orderId;
							});
						} else {
							$.toast(res.message, "text");
						}
					});
				} else {
					$.toast(res.message, "text");
				}
			}
		});
	}

	// 在每一步之前的操作
	cash.prototype.initStep = function(currStep) {
		$('#currStep').html(currStep + 1);
		var removeClass = new Array;
		for (var i = 1; i <= this.slides; i++) {
			$('.nextStep' + i).unbind('click');
			removeClass.push('nextStep' + i);
		}
		$('#nextStep').removeClass(removeClass.join(' ')).addClass('nextStep' + (currStep + 1));

		if ((currStep + 1) == this.slides) {
			$('#tipsText').html('提交订单');
		} else {
			$('#tipsText').html('下一步');
		}
	}

	/**
	 * 建立联系人HTML
	 * @param  {[type]} serial 序号
	 * @return {[type]}        void
	 */
	cash.prototype.buildContactHTML = function(serial) {
		var _this = this;

		var html = '';

		html += '<div class="weui-cell">';
		html += '<input type="hidden" name="serial'+serial+'" value="' + serial + '"/>';
		html += '<div class="weui-cell__hd"><label class="weui-label">联系人' + serial + '姓名</label></div>';
		html += '<div class="weui-cell__bd">';
		html += '<input class="weui-input" type="text" name="contactName'+serial+'" placeholder="请输入联系人姓名">';
		html += '</div></div>';
		html += '<div class="weui-cell weui-cell_select-picker">';
		html += '<div class="weui-cell__hd">';
		html += '<label class="weui-label">联系人' + serial + '关系</label>';
		html += '</div><div class="weui-cell__bd">';
		html += '<input class="weui-input contactRelationship" type="text" value="请选择联系人关系">';
		html += '<input type="hidden" class="contactRelationshipValue" name="contactRelation'+serial+'">';
		html += '</div></div>';
		html += '<div class="weui-cell">';
		html += '<div class="weui-cell__hd"><label class="weui-label">联系人' + serial + '手机</label></div>';
		html += '<div class="weui-cell__bd">';
		html += '<input class="weui-input" type="text" name="contactPhone'+serial+'" placeholder="请输入联系人手机">';
		html += '</div></div>';

		$('#addContactBtn').before(html);

		// 绑定婚姻状况
		$('.contactRelationship').select({
			title: "请选择联系人关系",
			items: _this.contactRelationship,
			onChange: function(data) {
				this.$input.next('.contactRelationshipValue').val(data.values);
			}
		});
	}


}(window);