function MainClass() {
	var Controller = [];
	var Model = [];
	var View = [];
	
	this.extend = function(name,Object) {
		Controller[name] = Object.Controller();
		Model[name] = Object.Model();
		View[name] = Object.View();
	};
	
	this.Controller = function(name) { return Controller[name]; };
	this.Model = function(name) { return Model[name]; };
	this.View = function(name) { return View[name]; };	
	
	this.expandCollapse = function(name,launcher,target) {
		var that = this;
		$(launcher).unbind('click');
		$(target).slideToggle('fast', function() {
			saveToCookie('contentsView', 'expandStatus', name, $(target).is(':hidden') ? 'none' : 'block');
			$(launcher).bind('click', function () { that.expandCollapse(name,launcher,target); }); 
			
			var expandButton = $(launcher).children(".icon");
			if ($(target).is(':hidden')){
				$(expandButton).attr("src", jsMeta.baseUrl+"/img/icon_plus_tiny.png");
			} else {
				$(expandButton).attr("src", jsMeta.baseUrl+"/img/icon_minus_tiny.png");
			}
		});
	};
	
	this.json = function(url,data) {
		return $.ajax({ 
			type: 'POST',
			dataType: 'json',
			data: data,
			url: jsMeta.baseUrl+url,
		});
	};
}

function FunctionsClass() {
	var Controller = [];
	var Model = [];
	var View = [];
	
	this.extend = function(type,name,Function) {
		if(type == 'Controller') { Controller[name] = Function; }
		if(type == 'Model') { Model[name] = Function; }
		if(type == 'View') { View[name] = Function; }
	};
	
	this.Controller = function() { return Controller; };
	this.Model = function() { return Model; };
	this.View = function() { return View; };
	
}
