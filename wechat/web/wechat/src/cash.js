/*
 * 现金业务
 * @Author: Admin
 * @Date:   2017-11-17 13:36:31
 * @Last Modified by:   Admin
 * @Last Modified time: 2018-04-04 17:42:47
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
		this.installmentCycle = options.installmentCycle || [];
		// 产品类型
		this.cashProductType = options.cashProductType || [];
		// 借款用途
		this.casePurpose = options.casePurpose || [];
		// 婚姻状况
		this.maritalSituation = options.maritalSituation || [];
		// 联系人关系
		this.contactRelationship = options.contactRelationship || [];
		// 房屋权属
		this.houseProperty = options.houseProperty || [];
		// swiper实例
		this.swiper;
		// slides总数
		this.slides = 4;
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
		this.radios = [
			'gender'
		];
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

					case 2: // 第3步
						_this.step3(swiper.activeIndex);
						break;

					case 3: // 第4步
						_this.step4(swiper.activeIndex);
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

		// 借款用途
		var purpose = Cache.get('purpose');
		purpose = purpose ? purpose : '';
		// 初始化借款用途标题
		var purposeTitle = '';
		// 选择产品类型
		_this.casePurpose.forEach(function(value) {
			if (purpose == value.value) {
				purposeTitle = value.title;
			}
		});
		$('input[name=purpose]').val(purpose);
		$('#purposeType').val(purposeTitle);

		$("#purposeType").select({
			title: "请选择借款用途",
			items: _this.casePurpose,
			onChange: function(data) {
				$('input[name=purpose]').val(data.values);
				Cache.set('purpose', data.values);
			}
		});

		// 监听数据变动
		$('input[type=text]').bind('blur', function() {
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

		// 监听radio变动
		$('input[type=radio]').bind('change', function() {
			var key = $(this).attr('name');
			var val = $(this).val();
			if (key == 'gender') {
				if (val == '女') {
					$("input[value=女]").attr('checked', true);
					$("input[value=男]").attr('checked', false);
				} else {
					$("input[value=男]").attr('checked', true);
					$("input[value=女]").attr('checked', false);
				}
			}

			Cache.set(key, val);
		});

		// 初始婚姻状况
		var maritalSituation = Cache.get('maritalSituation');
		maritalSituation = maritalSituation ? maritalSituation : '';
		// 初始婚姻状况标题
		var maritalSituationTitle = '';
		// 选择产品类型
		_this.maritalSituation.forEach(function(value) {
			if (maritalSituation == value.value) {
				maritalSituationTitle = value.title;
			}
		});
		$('input[name=marital]').val(maritalSituation);
		$('#maritalSituation').val(maritalSituationTitle);
		$("#maritalSituation").select({
			title: "请选择婚姻状况",
			items: _this.maritalSituation,
			onChange: function(data) {
				$('input[name=marital]').val(data.values);
				Cache.set('maritalSituation', data.values);
			}
		});

		// 初始产品类型值
		var houseProperty = Cache.get('houseProperty');
		houseProperty = houseProperty ? houseProperty : '';
		// 初始房屋权属标题
		var housePropertyTitle = '';
		// 选择房屋权属类型
		_this.houseProperty.forEach(function(value) {
			if (houseProperty == value.value) {
				housePropertyTitle = value.title;
			}
		});
		$('input[name=houseProperty]').val(houseProperty);
		$('#houseProperty').val(housePropertyTitle);
		$("#houseProperty").select({
			title: "请选择房屋权属",
			items: _this.houseProperty,
			onChange: function(data) {
				$('input[name=houseProperty]').val(data.values);
				Cache.set('houseProperty', data.values);
			}
		});

		// 初始化联系人数量
		var initContacts = Cache.get('initContacts');
		initContacts = initContacts ? initContacts : _this.initContacts;
		for (var i = 1; i <= initContacts; i++) {
			_this.buildContactHTML(i);
			_this.currContactId = i;
		}

		// 监听添加联系人操作
		$('#addContactBtn').on('click', function() {
			if (_this.currContactId < _this.maxContacts) {
				_this.currContactId += 1;
				_this.buildContactHTML(_this.currContactId);
				Cache.set('initContacts', _this.currContactId);
			} else {
				$.toast('最多添加' + _this.maxContacts + '个联系人信息', "text");
			}
		});

		// 监听删除联系人操作
		$('#delContactBtn').on('click', function() {
			if (_this.currContactId > _this.initContacts) {
				// 当前序号
				var serial = _this.currContactId;

				// 清理掉本地存储中的数据
				Cache.del('contactName' + serial);
				Cache.del('contactPhone' + serial);
				Cache.del('contactRelation' + serial);

				// 删除掉DOM
				$('.weui-cell-contact' + serial).remove();

				// 减少一个DOM标签
				_this.currContactId -= 1;
				Cache.set('initContacts', _this.currContactId);
			} else {
				$.toast('最少保留' + _this.initContacts + '个联系人信息', "text");
			}
		});

		// 监听上一步
		$('#prevStep').bind('click', function() {
			_this.swiper.slidePrev();
		});

		// 监听文本框输入限制
		var couter = $('#remarkCounter');
		$('#remarkTextArea').on('input propertychange change', function() {
			var value = $(this).val();

			if (value.length > 200) {
				$(this).val(value.substr(0, 200));
			} else {
				couter.text(value.length);
			}
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
			ele: "input[name=purpose]",
			datatype: '*1-20',
			nullmsg: "请选择借款用途",
			errormsg: "请选择借款用途"
		}, {
			ele: "input[name=productType]",
			datatype: 'n',
			nullmsg: "请选择产品类型",
			errormsg: "请选择产品类型"
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
					_this.swiper.slideNext();
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
			datatype: /^(13[0-9]|14[579]|15[012356789]|16[6]|17[0-9]|18[0-9]|19[89])[0-9]{8}$/,
			nullmsg: "请输入银行预留手机号",
			errormsg: "预留手机号不合法"
		}, {
			ele: "input[name=mobileNo]",
			datatype: /^(13[0-9]|14[579]|15[012356789]|16[6]|17[0-9]|18[0-9]|19[89])[0-9]{8}$/,
			nullmsg: "请输入联系手机号",
			errormsg: "联系手机号不合法"
		}, {
			ele: "input[name=address]",
			datatype: "*",
			nullmsg: "请输入调查地址",
			errormsg: "调查地址不合法"
		}]);
	}

	// 第三步验证
	cash.prototype.step3 = function(currStep) {
		var _this = this;
		// 初始化
		_this.initStep(currStep);
		// 验证数据并提交
		var validator = $('#formStep3').validator({
			btnSubmit: '.nextStep3',
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
			ele: "input[name=monthlyIncome]",
			datatype: 'n',
			nullmsg: "请输入月收入",
			errormsg: "月收入不合法"
		}, {
			ele: "input[name=houseProperty]",
			datatype: '*2-20',
			nullmsg: "请选择房屋权属",
			errormsg: "请选择房屋权属"
		}, {
			ele: "input[name=cardAddress]",
			datatype: '*0-200',
			nullmsg: "请输入户籍地址",
			errormsg: "户籍地址长度在200以内"
		}, {
			ele: "input[name=currentAddress]",
			datatype: '*0-200',
			nullmsg: "请输入居住地址",
			errormsg: "居住地址长度在200以内"
		}, {
			ele: "input[name=jobName]",
			datatype: '*0-200',
			nullmsg: "请输入工作单位",
			errormsg: "工作单位长度在200以内"
		}, {
			ele: "input[name=jobAddress]",
			datatype: '*0-200',
			nullmsg: "请输入单位地址",
			errormsg: "单位地址长度在200以内"
		}, {
			ele: "input[name=jobPhone]",
			datatype: /(^0\d{2,3}-?\d{7,8}$)|(^(13[0-9]|14[579]|15[012356789]|16[6]|17[0-9]|18[0-9]|19[89])[0-9]{8}$)/,
			ignore: "ignore",
			nullmsg: "请输入单位电话",
			errormsg: "单位电话格式不正确"
		}, {
			ele: "input[name=wechat]",
			datatype: '*0-40',
			nullmsg: "请输入微信账号",
			errormsg: "微信账号长度在40以内"
		}, {
			ele: "input[name=qq]",
			datatype: '*0-20',
			nullmsg: "请输入QQ账号",
			errormsg: "QQ账号长度在20以内"
		}, {
			ele: "input[name=alipay]",
			datatype: '*0-40',
			ignore: "ignore",
			nullmsg: "请输入支付宝账号",
			errormsg: "支付宝账号长度在40以内"
		}]);
	}

	// 第二步验证
	cash.prototype.step4 = function(currStep) {
		var _this = this;
		// 初始化
		_this.initStep(currStep);
		// 验证数据并提交
		var validator = $('#formStep4').validator({
			btnSubmit: '.nextStep4',
			ajaxPost: true,
			callback: function(res) {
				if (res.status) {
					// 获取提交数据
					var data = $.extend({},
						$('#formStep1').serializeObject(),
						$('#formStep2').serializeObject(),
						$('#formStep3').serializeObject(),
						$('#formStep4').serializeObject()
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
		});
	}

	/**
	 * 建立联系人HTML
	 * @param  {[type]} serial 序号
	 * @return {[type]}        void
	 */
	cash.prototype.buildContactHTML = function(serial) {
		var _this = this;

		var html = '';

		html += '<div class="weui-cell weui-cell-contact' + serial + '">';
		html += '<input type="hidden" name="serial' + serial + '" value="' + serial + '"/>';
		html += '<div class="weui-cell__hd"><label class="weui-label">联系人' + serial + '姓名</label></div>';
		html += '<div class="weui-cell__bd">';
		html += '<input class="weui-input" type="text" name="contactName' + serial + '" placeholder="请输入联系人姓名">';
		html += '</div></div>';
		html += '<div class="weui-cell weui-cell_select-picker weui-cell-contact' + serial + '">';
		html += '<div class="weui-cell__hd">';
		html += '<label class="weui-label">联系人' + serial + '关系</label>';
		html += '</div><div class="weui-cell__bd">';
		html += '<input class="weui-input contactRelationship contactRelationship' + serial + '" type="text" value="请选择联系人关系">';
		html += '<input type="hidden" class="contactRelationshipValue" name="contactRelation' + serial + '">';
		html += '</div></div>';
		html += '<div class="weui-cell weui-cell-contact' + serial + '">';
		html += '<div class="weui-cell__hd"><label class="weui-label">联系人' + serial + '手机</label></div>';
		html += '<div class="weui-cell__bd">';
		html += '<input class="weui-input" type="text" name="contactPhone' + serial + '" placeholder="请输入联系人手机">';
		html += '</div></div>';

		$('#addContactBtn').before(html);

		$('input[type=text]').bind('blur', function() {
			var key = $(this).attr('name');
			var val = $(this).val();
			Cache.set(key, val);
		});

		// 初始化联系人名称
		var contactName = Cache.get('contactName' + serial);
		if (contactName) {
			$('input[name=contactName' + serial + ']').val(contactName);
		}

		// 初始化联系人电话
		var contactPhone = Cache.get('contactPhone' + serial);
		if (contactPhone) {
			$('input[name=contactPhone' + serial + ']').val(contactPhone);
		}

		// 初始化联系人关系
		var contactRelationshipTitle = '请选择联系人关系';
		var contactRelation = Cache.get('contactRelation' + serial);
		// 获取最终显示标题
		_this.contactRelationship.forEach(function(value) {
			if (contactRelation == value.value) {
				contactRelationshipTitle = value.title;
			}
		});

		$('.contactRelationship' + serial).val(contactRelationshipTitle);
		$('input[name=contactRelation' + serial + ']').val(contactRelation);

		// 绑定婚姻状况
		$('.contactRelationship' + serial).select({
			title: "请选择联系人关系",
			items: _this.contactRelationship,
			onChange: function(data) {
				Cache.set('contactRelation' + serial, data.values);
				this.$input.next('.contactRelationshipValue').val(data.values);
			}
		});
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
		var installmentCycleTitle = '';

		// 初始分期方式值
		var installmentPeriod = Cache.get('installmentPeriod');
		installmentPeriod = installmentPeriod ? installmentPeriod : '';
		// 初始分期方式标题
		var installmentPeriodTitle = '';

		// 初始产品类型值
		var productType = Cache.get('productType');
		productType = productType ? productType : '';
		// 初始产品类型标题
		var productTypeTitle = '';

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

		// 选择产品类型
		_this.cashProductType.forEach(function(value) {
			if (productType == value.value) {
				productTypeTitle = value.title;
			}
		});

		// 分期方式初始值
		$('input[name=installmentCycle]').val(installmentCycle);
		$('#InstallmentType').val(installmentCycleTitle);
		// 分期时长初始化
		$('input[name=installmentPeriod]').val(installmentPeriod);
		$('#InstallmentPeriod').val(installmentPeriodTitle);
		// 产品类型初始化
		$('input[name=productType]').val(productType);
		$('#productType').val(productTypeTitle);

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

		// 产品类型
		$("#productType").select({
			title: "请选择产品类型",
			items: _this.cashProductType,
			onChange: function(data) {
				$('input[name=productType]').val(data.values);
				Cache.set('productType', data.values);
				_this.getPayment();
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
		if (!data.productType) return;
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
			if (-1 !== $.inArray(key, this.checkboxs)) {
				if (data[key] == 1) {
					$("input[name=" + key + "]").attr('checked', true);
				} else {
					$("input[name=" + key + "]").attr('checked', false);
				}
			} else if (-1 !== $.inArray(key, this.radios)) {
				if (key == 'gender') {
					if (data[key] == '女') {
						$("input[value=女]").attr('checked', true);
						$("input[value=男]").attr('checked', false);
					} else {
						$("input[value=男]").attr('checked', true);
						$("input[value=女]").attr('checked', false);
					}
				}
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