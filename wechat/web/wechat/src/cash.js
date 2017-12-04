/*
 * 现金业务
 * @Author: Admin
 * @Date:   2017-11-17 13:36:31
 * @Last Modified by:   Admin
 * @Last Modified time: 2017-11-27 11:25:45
 */
! function(win) {
	var cash = window.Cash = function(options) {
		// 网络请求地址
		this.paymentUrl = options.paymentUrl || '';
		// 创建订单
		this.createUrl = options.createUrl || '';
		// 创建成功后跳转页面
		this.successUrl = options.successUrl || '/';

		// 分期配置
		this.installmentCycle = options.installmentCycle || {};
		// swiper实例
		this.swiper;
		// slides总数
		this.slides = 2;
		// 个人保障计划
		this.protectionFee = options.protectionFee || 0;
		// 贵宾服务包
		this.vipServiceFee = options.vipServiceFee || 0;
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

		// 绑定
		_this.bind();
		// 获取初始化数据
		this.initVal();
	}

	// 绑定
	cash.prototype.bind = function() {
		var _this = this;

		// 分期周期
		_this.periodsCycle();

		// 监听数据变动
		$('input').bind('blur', function() {
			var key = $(this).attr('name');
			var val = $(this).val();
			Cache.set(key, val);
			if (key == 'loanAmount') {
				_this.getPayment();
			}
		});

		// 监听checkbox变动
		$('input[type=checkbox]').bind('change', function() {
			var key = $(this).attr('name');
			var val = $(this).is(':checked') ? 1 : 0;
			Cache.set(key, val);
			if (key == 'isProtectionFee' || key == 'isVipServiceFee') {
				_this.getPayment();
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
			ele: "input[name=loanAmount]",
			datatype: "n",
			nullmsg: "请输入贷款金额",
			errormsg: "贷款金额不合法"
		}, {
			ele: "input[name=installmentCycle]",
			datatype: '*1-20',
			nullmsg: "请选择分期方式",
			errormsg: "请选择分期方式"
		}, {
			ele: "input[name=installmentPeriod]",
			datatype: '*1-20',
			nullmsg: "请选择分期时长",
			errormsg: "请选择分期时长"
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
								Cache.batchDel();
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
		}).addRule([{
			ele: "input[name=realName]",
			datatype: "s1-5",
			nullmsg: "请输入客户姓名",
			errormsg: "客户姓名长度为1~5之间"
		}, {
			ele: "input[name=certNo]",
			datatype: /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
			nullmsg: "请输入身份证号",
			errormsg: "身份证号不合法"
		}, {
			ele: "input[name=bankCardNo]",
			datatype: 's16-19',
			nullmsg: "请输入银行卡号",
			errormsg: "银行卡号不合法"
		}, {
			ele: "input[name=bankMobileNo]",
			datatype: /^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|17[0-9]|14[57])[0-9]{8}$/,
			nullmsg: "请输入银行预留手机号",
			errormsg: "预留手机号不合法"
		}, {
			ele: "input[name=mobileNo]",
			datatype: /^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|17[0-9]|14[57])[0-9]{8}$/,
			nullmsg: "请输入联系手机号",
			errormsg: "联系手机号不合法"
		}, {
			ele: "input[name=address]",
			datatype: "*",
			nullmsg: "请输入调查地址",
			errormsg: "调查地址不合法"
		}]);
	}

	/**
	 * 分期周期选择
	 * @return {[type]} 
	 */
	cash.prototype.periodsCycle = function() {
		var _this = this;
		// 初始分期方式值
		var installmentCycle = Cache.get('installmentCycle');
		installmentCycle = installmentCycle ? installmentCycle : '';
		// 初始分期方式标题
		var installmentCycleTitle = '请选择分期方式';

		// 初始分期方式值
		var installmentPeriod = Cache.get('installmentPeriod');
		installmentPeriod = installmentPeriod ? installmentPeriod : '';
		// 初始分期方式标题
		var installmentPeriodTitle = '请选择分期时长';

		// 选择分期方式
		var cycle = [];
		_this.installmentCycle.forEach(function(value) {
			cycle.push({
				'title': value.title,
				'value': value.value
			});

			if (installmentCycle === value.value) {
				installmentCycleTitle = value.title;

				if (value.periods.length > 0) {
					value.periods.forEach(function(val) {
						if (installmentPeriod === val.value) {
							installmentPeriodTitle = val.title;
						}
					});
				}
			}
		});

		// 分期方式初始值
		$('input[name=installmentCycle]').val(installmentCycle);
		$('#InstallmentType').val(installmentCycleTitle);
		// 分期时长初始化
		$('input[name=installmentPeriod]').val(installmentPeriod);
		$('#InstallmentPeriod').val(installmentPeriodTitle);

		if (installmentCycle) {
			// 获取所选父级
			var periods = [];
			_this.installmentCycle.forEach(function(value) {
				if (installmentCycle == value.value) {
					periods = value.periods;
				}
			});

			if (periods.length > 0) {
				var items = [];
				periods.forEach(function(val) {
					items.push({
						'title': val.title,
						'value': val.value
					});
				});

				// 选择分期时长
				$("#InstallmentPeriod").select('update', {
					title: "请选择分期时长",
					items: items,
					onChange: function(data) {
						$('input[name=installmentPeriod]').val(data.values);
						Cache.set('installmentPeriod', data.values);
						_this.getPayment();
					}
				});
			}
		}

		$("#InstallmentType").select({
			title: "请选择分期方式",
			items: cycle,
			onChange: function(data) {
				$('input[name=installmentCycle]').val(data.values);
				$('input[name=installmentPeriod]').val('');
				$('#InstallmentPeriod').val('请选择分期时长');
				$('#paymentContent').html('0元x0<br>(含可选服务费0元)');
				// 缓存选择的值
				Cache.set('installmentCycle', data.values);
				Cache.set('installmentPeriod', '');
				// 获取所选父级
				var periods = [];
				_this.installmentCycle.forEach(function(value) {
					if (data.values == value.value) {
						periods = value.periods;
					}
				});

				if (periods.length > 0) {
					var items = [];
					periods.forEach(function(val) {
						items.push({
							'title': val.title,
							'value': val.value
						});
					});

					// 选择分期时长
					$("#InstallmentPeriod").select('update', {
						title: "请选择分期时长",
						items: items,
						onChange: function(data) {
							$('input[name=installmentPeriod]').val(data.values);
							Cache.set('installmentPeriod', data.values);
							_this.getPayment();
						}
					});
				}
			}
		});
	}

	/**
	 * 网络请求获取贷款信息
	 * @return {[type]} 
	 */
	cash.prototype.getPayment = function() {
		// 获取表单对象数据
		var data = $('#formStep1').serializeObject();
		// 检测数据完整性
		if (!data.installmentCycle) return;
		if (!data.installmentPeriod) return;
		if (!data.loanAmount || !/^[0-9]*$/.test(data.loanAmount) || data.loanAmount < 0) return;

		// 获取之前的内容信息
		var paymentCnt = $('#paymentContent').html();

		$('#paymentContent').html('还款信息获取中···<br />');
		$.ajax({
			url: this.paymentUrl,
			data: $('#formStep1').serialize(),
			type: 'POST',
			timeout: 5000,
			success: function(res) {
				if (res.status) {
					var content = res.data.periodAmount + '元x' + data.installmentPeriod + '<br>(含可选服务费' + (parseFloat(res.data.vipServiceFee) + parseFloat(res.data.protectionFee)) + '元)';
					$('#paymentContent').html(content);
					$('#vipServiceFee').text('(' + res.data.vipServiceFee + '元/期)');
					$('#protectionFee').text('(' + res.data.protectionFee + '元/期)');
				} else {
					$('#paymentContent').html(paymentCnt);
				}
			},
			error: function() {
				$('#paymentContent').html(paymentCnt);
			}
		});
	}

	// 初始化本地存储数据
	cash.prototype.initVal = function() {
		var data = Cache.batchGet();
		for (var key in data) {
			if (-1 !== $.inArray(key, this.selects)) {
				if (key == 'g_goods_type') {
					$("input[name=" + key + "]").val(data[key]);
				}
				$("select[name=" + key + "]").find("option[value='" + data[key] + "']").attr("selected", true);
			} else if (-1 !== $.inArray(key, this.checkboxs)) {
				if (data[key] == 1) {
					$("input[name=" + key + "]").attr('checked', true);
				} else {
					$("input[name=" + key + "]").attr('checked', false);
				}
			} else if (-1 !== $.inArray(key, this.ids)) {
				$("#" + key).val(data[key]);
			} else {
				$("input[name=" + key + "]").val(data[key]);
			}
		}

		// 获取首次的分期还款信息
		this.getPayment();
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
}(window);