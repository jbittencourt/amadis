var currentShowing='';
function lock()
{
	$('overlay').toggle();
	if(currentShowing != '') $(currentShowing).hide();
}

function switchMenu()
{
	lock();
	$('dashboard').toggle();
	currentShowing = 'dashboard';
}

function showLogin()
{
	lock();
	$('loginbox').toggle();
	currentShowing = 'loginbox';
}

function getScreen()
{
	new Ajax.Request('http://anakin.lec.ufrgs.br/~robson/olpc.test.html', {
		method:'get',
		onSuccess: function(transport){
   			//var json = transport.responseText.evalJSON();
			var el = document.createElement('DIV');
			el.innerHTML = transport.responseText;
			$('main_content').appendChild(el);
		}
	});
}
