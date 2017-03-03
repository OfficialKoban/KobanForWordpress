//var kmsv = "http://www.itsonlyleads.com";
var kmsv = "https://app-koban.com";
var api = "";
var usr = "";
var ssl = "";
var getJSON = function(url, successHandler, errorHandler) {
			  var xhr = typeof XMLHttpRequest != 'undefined'
				? new XMLHttpRequest()
				: new ActiveXObject('Microsoft.XMLHTTP');
			  xhr.open('get', url, true);
			  xhr.setRequestHeader("X-ncApi", api);
			  xhr.setRequestHeader("X-ncUser", usr);
			  xhr.onreadystatechange = function() {
				var status;
				var data;
				if (xhr.readyState == 4) { // `DONE`
				  status = xhr.status;
				  if (status == 200) {
					data = JSON.parse(xhr.responseText);
					successHandler && successHandler(data);
				  } else {
					errorHandler && errorHandler(status);
				  }
				}
			  };
			  xhr.send();
			};
		
(function() {
    tinymce.create("tinymce.plugins.koban_button_plugin", {

        //url argument holds the absolute url of our plugin directory
        init : function(ed, url) {

            //Bouton Koban   
            ed.addButton("koban", {
                title : "Koban",
                cmd : "koban_command",
                image : "http://track.itsonlyleads.com/Content/img/k3232.png"
            });

            //button functionality.
            ed.addCommand("koban_command", function() {
				api = tinyMCE.activeEditor.settings.km_key;
				usr = tinyMCE.activeEditor.settings.km_usr;
				ssl = tinyMCE.activeEditor.settings.km_ssl;
                ed.windowManager.open({
					width:600,
					height:200,
					title: 'Koban Marketing',
						body: [
							{type: 'listbox', name: 'eltype', label: 'Insérer un(e)', values:[
								{text:'...', value:'0'},
								{text: 'Formulaire', value:'F'},
								{text: 'Landing Page', value:'L'},
								{text: 'Call To Action', value:'C'}
							], onselect: function(v){
								kb_tmce_setValue(this.value(), this.rootControl);
							}},
							{
								type: 'listbox',
								name: 'elval',
								label: 'Sélectionnez',
								values:[{text:'...', value:'0'}]
							}
						],
						buttons: [{
							text: 'Insérer',
							subtype: 'primary',
							onclick: function(v) {
								var win = v.control.rootControl;
								var v = win.find("#elval")[0].state.data.value;
								var t = win.find("#eltype")[0].state.data.value;
								if (v != "0"){
									if (t == "F"){
										getJSON(kmsv + '/api/v1/ncForm/Get?id=' + v + '&s=' + ssl, function(data) {
											ed.execCommand("mceInsertContent", 0, data);
										});									
										(this).parent().parent().close();
									}
									if (t == "C"){
										getJSON(kmsv + '/api/v1/ncCallToAction/Get?id=' + v + '&s=' + ssl, function(data) {
											ed.execCommand("mceInsertContent", 0, data);
										});									
										(this).parent().parent().close();
									}
									if (t == "L"){
										getJSON(kmsv + '/api/v1/ncLandingPage/Get?id=' + v + '&s=' + ssl, function(data) {
											ed.execCommand("mceInsertContent", 0, data);
										});									
										(this).parent().parent().close();
									}
								}
							}
						},
						{
							text: 'Annuler',
							onclick: function() {
								(this).parent().parent().close();
							}
						}]
				});
            });

        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : "Koban Buttons",
                author : "Koban",
                version : "1"
            };
        }
    });

    tinymce.PluginManager.add("koban_button_plugin", tinymce.plugins.koban_button_plugin);
})();

function kb_tmce_setValue(v, win){
	if (v == "F" || v == "C" || v == "L"){
		var valbox = win.find("#elval")[0];
		valbox.value(null); 
		valbox.menu = null;
		var wait = [{text:"Veuillez patienter", value:"0"}];
		valbox.state.data.menu = valbox.settings.menu = wait;
		if (v == "F"){
			getJSON(kmsv + '/api/v1/ncForm/GetAll', function(data) {
			  var mn = [{text:"...", value:"0"}];
			  data.forEach(function(e, i){
				 var ne = {text: e.Label, value: e.Guid};
				 mn.push(ne);
			  });
			  valbox.state.data.menu = valbox.settings.menu = mn;
			}, function(status) {
			  alert('Something went wrong.');
			});
		}
		else{
			if (v == "C"){
			  getJSON(kmsv + '/api/v1/ncCallToAction/GetAll', function(data) {
			  var mn = [{text:"...", value:"0"}];
			  data.forEach(function(e, i){
				 var l = e.Label;
				 if (l == null)
					 l = "Non défini";
				 var ne = {text: l, value: e.Guid};
				 mn.push(ne);
			  });
			  valbox.state.data.menu = valbox.settings.menu = mn;
			}, function(status) {
			  alert('Something went wrong.');
			});
			}
			else{
				if (v == "L"){
					getJSON(kmsv + '/api/v1/ncLandingPage/GetAll', function(data) {
					  var mn = [{text:"...", value:"0"}];
					  data.forEach(function(e, i){
						 var ne = {text: e.Label, value: e.Guid};
						 mn.push(ne);
					  });
					  valbox.state.data.menu = valbox.settings.menu = mn;
					}, function(status) {
					  alert('Something went wrong.');
					});
				}
				else{
					valbox.state.data.menu = valbox.settings.menu = [{text:"un", value:"1"}, {text:"de", value:"2"}];					
				}
			}
		}
	}
	else{
		valbox.disabled = true;
		valbox.value(null);              
		valbox.menu = null;
		valbox.state.data.menu = valbox.settings.menu = [{text:"...", value:"0"}];
	}
}