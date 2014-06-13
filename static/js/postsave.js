(function() {
var StoragePrefix = "youBBS-acicfg-";
var saveDays = 7;

var s_Unsupported = "不支持的浏览器";
var s_LastSave = "上次保存于：";
var s_NoSave = "尚未保存过";
var s_Saved = "已保存";
var s_Spacer = "　　";
var s_Save = '保存';
var s_Restore = '恢复';
var s_Delete = '删除';
var s_Confirm = "恢复数据会覆盖现有内容，是否确认？";

var saveNode = document.getElementById('id-post-autosave');
var $ = function(a) { return document.getElementById(a); }; //Local variable. It won't clash with jQuery in the global scope.

if(saveNode) {
	if(window.localStorage) {
		var saveAnchor = document.createElement('a');
		var restoreAnchor = document.createElement('a');
		var delAnchor = document.createElement('a');
		var timeSpan = document.createElement('span');

		var gcLocalStorage = function() { //Use var xxx = function() to avoid polluting global scope. Some browsers consider function xxx() to be window.xxx = function().
			var i, k, p, t = new Date().getTime();
			for(i = 0; i < window.localStorage.length; i++) {
				k = window.localStorage.key(i);
				if(k.indexOf(StoragePrefix) !== 0) {
					continue;
				}
				try {
					p = JSON.parse(window.localStorage[k]);
					if(t > p["TTL"]) {
						window.localStorage.removeItem(k);
						i--;
					}
				} catch(e) {
					window.localStorage.removeItem(k);
				}
			}
		};

		var setLocalStorage = function(key, value, exdays) {
			key = StoragePrefix + key;
			gcLocalStorage();
			window.localStorage[key] = JSON.stringify({"TTL":new Date().getTime() + exdays * 86400 * 1000, "value":value});
		};

		var getLocalStorage = function(key) {
			key = StoragePrefix + key;
			gcLocalStorage();
			if(key in window.localStorage) {
				var p = JSON.parse(window.localStorage[key]);
				return p["value"];
			}
		};

		var delLocalStorage = function(key) {
			key = StoragePrefix + key;
			gcLocalStorage();
			if(key in window.localStorage) {
				window.localStorage.removeItem(key);
			}
		};

		var getSanitizedPathname = function() {
			if(/^\/t-[0-9]+/.test(location.pathname)) {
				return /^\/t-[0-9]+/.exec(location.pathname);
			}
			if(/^\/newpost\/[0-9]+$/.test(location.pathname)) {
				return location.pathname;
			}
			throw "This should not happen.";
		};

		var postKey = function() {
			return "postsave" + getSanitizedPathname();
		};

		var updateLastSave = function() {
			var p = getLocalStorage(postKey());
			if(p) {
				timeSpan.innerHTML = new Date(p["time"]).toLocaleString();
			} else {
				timeSpan.innerHTML = s_NoSave;
			}
		};

		var savePost = function(auto) {
			var p = {};

			if($('id-post-title')) {
				p["title"] = $('id-post-title').value;
			}
			p["message"] = $('id-content').value;
			p["path"] = getSanitizedPathname();
			p["time"] = new Date().getTime();

			setLocalStorage(postKey(), p, saveDays);
			updateLastSave();
			if(!auto) {
				alert(s_Saved);
			}
		};

		var restorePost = function() {
			if(!confirm(s_Confirm)) {
				return;
			}
			var p = getLocalStorage(postKey());
			if(p) {
				if($('id-post-title')) {
					$('id-post-title').value = p["title"];
				}
				$('id-content').value = p["message"];
			}
		};

		var deletePost = function() {
			delLocalStorage(postKey());
			updateLastSave();
		}

		updateLastSave();

		$('id-post-submit').addEventListener('click', function() { savePost(true); });
		saveAnchor.addEventListener('click', function() { savePost(false); });
		restoreAnchor.addEventListener('click', restorePost);
		delAnchor.addEventListener('click', deletePost);

		saveAnchor.appendChild(document.createTextNode(s_Save));
		restoreAnchor.appendChild(document.createTextNode(s_Restore));
		delAnchor.appendChild(document.createTextNode(s_Delete));

		saveNode.innerHTML = '';
		saveNode.appendChild(document.createTextNode(s_LastSave));
		saveNode.appendChild(timeSpan);
		saveNode.appendChild(document.createTextNode(s_Spacer));
		saveNode.appendChild(saveAnchor);
		saveNode.appendChild(document.createTextNode(s_Spacer));
		saveNode.appendChild(restoreAnchor);
		saveNode.appendChild(document.createTextNode(s_Spacer));
		saveNode.appendChild(delAnchor);
	} else {
		saveNode.innerHTML = s_CantSave;
	}
}

}());
