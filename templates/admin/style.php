<style type="text/css">

	.wrap *{
		box-sizing:border-box;
	}

	.wrap{
		padding:12px;
		box-sizing: border-box;
	}

	.wrap h1.wp-heading-inline {
	    background: #ce1025;
	    color: #fff;
	    width: 100%;
	    display: block;
	    margin: 10px 0 15px 0;
	    font-size: 15px;
	    background-image: url(<?php echo plugins_url('/assets/logo.png', __FILE__) ?>);
	    background-repeat: no-repeat;
	    background-position: 20px center;
	    background-size: auto 20px;
	    line-height: 60px;
	    position: relative;
	    padding: 0px 0px 0px 150px;
	}

	h1.wp-heading-inline:before {
	    content: '';
	    width: 1px;
	    height: 30px;
	    background: rgba(255,255,255,.4);
	    position: absolute;
	    left: 135px;
	    top: 15px;
	}

	.heartland-plugin-copy{
		max-width:750px;
	}

	.heartland-plugins{
		max-width:1200px;
		margin-top:45px;
	}

	section.plugin{
		width:300px;
		height:300px;
		background:#fff;
		float:left;
		box-sizing:border-box;
		margin:0 45px 45px 0;
		position: relative;
		cursor: pointer;
	}

	section.plugin:before{
		content:'';
		position: absolute;
		left:0;
		top:0;
		width:10px;
		height:100%;
		background:#000;
		transition:width .3s ease;
	}

	section.plugin.plugin-woo:before{
		background:#9b5c8f;
	}

	section.plugin.plugin-woo a.square{
		color:#9b5c8f;
	}

	section.plugin.plugin-ss:before{
		background:#000;
	}
	section.plugin.plugin-ss a.square{
		color:#000;
	}

	section.plugin.plugin-gf:before{
		background:#365666;
	}

	section.plugin.plugin-gf a.square{
		color:#365666;
	}

	section.plugin.plugin-em:before{
		background:#479217;
	}

	section.plugin.plugin-em a.square{
		color:#479217;
	}

	section.plugin.plugin-contact:before{
		background:#ce1025;
	}

	section.plugin.plugin-contact a.square{
		color:#ce1025;
	}

	section.plugin img {
	    height: 80px;
	    margin: 30px auto 30px;
	    display: block;
	    transform:scale(1,1);
	    transition:transform .3s ease;
	}

	section.plugin a.square {
	    position: absolute;
	    left: 0;
	    top: 0;
	    width: 100%;
	    height: 100%;
	    padding:0px 30px 0 40px;
	    cursor: pointer;
	    text-decoration: none;
	    outline: none;
	    box-shadow: 3px 3px 10px rgba(0,0,0,.1) !important;
	    transition:all .3s ease;
	}

	section.plugin a.square strong {
	    display: block;
	    font-size: 16px;
	    line-height: 22px;
	    margin-bottom: 10px;
	}

	section.plugin a.square span.description {
	    color: #777;
	}

	section.plugin a.install {
	    position: absolute;
	    display: inline-block;
	    padding: 9px 15px;
	    color: #fff;
	    background: #000;
	    text-decoration: none;
	    bottom: -10px;
	    right: -10px;
	    font-weight: 500;
	    font-size: 12px;
	    z-index: 2;
	    box-shadow: 2px 2px 6px rgba(0,0,0,.4);
	    transform:scale(1,1);
	    transition:transform .3s ease;
	}

	section.plugin a.install:hover{
		transform:scale(1.2,1.2);
	}

	#iframes iframe{
		float:left;
		width:100%;
	}
	.iframeholder:after,
	.iframeholder::after{
		content:'';
		display:block;
		width:100%;
		height:0px;
		clear:both;
		position:relative;
	}

	.input-icon {
		position: relative;
	}

	.input-icon > i {
		position: absolute;
		display: block;
		transform: translate(0, -50%);
		top: 50%;
		pointer-events: none;
		width: 25px;
		text-align: center;
		font-style: normal;
	}

	.input-icon > input {
		padding-left: 25px;
		padding-right: 0;
	}

	.input-icon-right > i {
		right: 0;
	}

	.input-icon-right > input {
		padding-left: 0;
		padding-right: 25px;
		text-align: right;
	}

</style>