/*
 * 离线存储
 * @Author: Admin
 * @Date:   2017-11-17 14:08:57
 * @Last Modified by:   Admin
 * @Last Modified time: 2017-11-17 14:23:25
 */
! function(win) {
	var cache = win.Cache = {
		// 获取存储对象
		storage: window.localStorage || window.sessionStorage,
		/**
		 * 设置缓存
		 * @param {[type]} key 键
		 * @param {[type]} val 值
		 */
		set: function(key, val) {
			return this.storage.setItem(key, val);
		},
		/**
		 * 获取缓存
		 * @param  {[type]} key 键
		 * @return {[type]}     值
		 */
		get: function(key) {
			return this.storage.getItem(key);
		},
		/**
		 * 删除缓存
		 * @param  {[type]} key 键
		 * @return {[type]}     删除结果
		 */
		del: function(key) {
			return this.storage.removeItem(key);
		},
		/**
		 * 删除所有缓存
		 * @return {[type]} 删除结果
		 */
		batchDel: function() {
			return this.storage.clear();
		},
		/**
		 * 获取所有缓存数据
		 * @return {[type]} 缓存数据对象
		 */
		batchGet: function() {
			var key;
			var container = {};
			for (var i = 0; i < this.storage.length; i++) {
				key = this.storage.key(i);
				container[key] = this.storage.getItem(key);
			}

			return container;
		},
		/**
		 * 批量设置
		 * @param  {[type]} object 批量设置对象
		 * @return {[type]}        最终结果
		 */
		batchSet: function(object) {
			var _this = this;
			object.forEach(function(val , key){
				_this.set(key , val);
			});
			return true;
		}
	};
}(window);