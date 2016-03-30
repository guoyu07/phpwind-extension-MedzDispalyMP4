<?php
defined('WEKIT_VERSION') or exit(403);
Wind::import('SRV:forum.srv.threadDisplay.do.PwThreadDisplayDoBase');
/**
 * 帖子内容展示
 *
 * @author Medz Seven <lovevipdsw@vip.qq.com>
 * @copyright http://medz.cn
 * @license http://medz.cn
 */
class App_MedzDispalyMP4PwThreadDisplayDo extends PwThreadDisplayDoBase {
	/*
	 * @see PwThreadDisplayDoBase
	*/

	/**
	 * 是否显示播放控件
	 *
	 * @var bool
	 **/
	private $show;

	/**
	 * 构造方法
	 *
	 * @return void
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function __construct()
	{
		$this->show = false;
	}

	/**
	 * 构造帖子数据
	 *
	 * @param array $read 帖子数据
	 * @return array
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function bulidRead($read)
	{
		$content = $read['content'];
		$content = preg_replace_callback(
			'/(\<a\\s*href=\"(.*?).mp4(.*?)\"(.*?)\>(.*?)\<\/a\>)/si',
			array($this, 'replace'),
			$content
		);
		$read['content'] = $content;
		return $read;
	}

	/**
	 * 解析mp4
	 *
	 * @return string
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	private function replace(array $url)
	{
		$url = preg_replace('/[\\r\\t\\s\\n]*?/si', '', $url['2']);
		$url = '<div class="medz-plyer-mp4" id="medz-plyer-' . mt_rand() . '" data-url="' . $url . '.mp4"></div>';
		$this->show = true;
		return $url;
	}

	/**
	 * 底部JS
	 *
	 * @return void
	 * @author Medz Seven <lovevipdsw@vip.qq.com>
	 **/
	public function runJs()
	{
		if ($this->show) {
		echo <<<HTML
<script>
Wind.use('jquery', GV.JS_EXTRES + '/MedzDispalyMp4/ckplayer/ckplayer.js', function() {
	var list  = $(document).find('div.medz-plyer-mp4').toArray();
	var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always',wmode:'transparent'};
	var swf  = GV.JS_EXTRES + '/MedzDispalyMp4/ckplayer/ckplayer.swf';
	for (i in list) {
		var \$this = $(list[i]);
		var id     = \$this.attr('id');
		var url    = \$this.attr('data-url');
		var flashvars={
	        f:url,
	        c:0,
	        v:60,
	        my_url:encodeURIComponent(window.location.href),
	        my_title:encodeURIComponent(document.title),
	        b:0
	    };
	    var video=[url + '->video/mp4'];
	    CKobject.embed(swf,id,'ckplayer-' + id,'600','400',false,flashvars,video,params);
	}
});
</script>
HTML;
		}
	}
	
}