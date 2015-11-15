/*
---
description:     LazyLoad

authors:
  - David Walsh (http://davidwalsh.name)

license:
  - MIT-style license

requires:
  core/1.2.1:   '*'

provides:
  - LazyLoad
...
*/
var LazyLoad = new Class({

	Implements: [Options,Events],

	/* additional options */
	options: {
		range: 50,
		image: 'blank.gif',
		resetDimensions: false,
		elements: 'img',
		container: window,
		fireScroll: true, /* keeping for legacy */
		mode: 'vertical',
		startPosition: 0,
		mode: 'vertical'
	},

	/* initialize */
	initialize: function(options) {
		
		// get excluded selectors
		var excluded = document.id('gkLazyLoad').get('data-excluded');
		var excludedImgs = new Elements();
		this.excluded = excluded.split(","); 
		this.excluded = this.excluded.filter(function(item, index){
   			return item != "";
		});
		
		// collect all excluded img elements
		
		this.excluded.each(function(selector, index){
			if(selector.substring(0,1) == '^') {
				excludedImgs.append($$(selector.substring(1, selector.length)+' img'));
			} else {
				excludedImgs.append($$(selector));
			}
		});
		
		/* vars */
		this.setOptions(options);
		this.container = document.id(this.options.container);
		
		this.elements = $$(this.options.elements);
		
		// small validation	
		if(excludedImgs.length > 0) {
			this.elements = this.elements.differentiate(excludedImgs);
		}
		
		
		var axis = (this.options.mode == 'vertical' ? 'y': 'x');
		axis = 'y';
		this.containerDimension = this.container.getSize()[axis];
		this.startPosition = 0;

		var offset = (this.container != window && this.container != document.body ? this.container : "");

		/* find elements remember and hold on to */
		this.elements = this.elements.filter(function(el) {
			var elPos = el.getPosition(offset)[axis];
			
			/* reset image src IF the image is below the fold and range */
			if(elPos > this.containerDimension + this.options.range) {
				el.store('oSRC',el.get('src')).set('src',this.options.image);
				el.setStyle("opacity", 0);
				if(this.options.resetDimensions) {
					el.store('oWidth',el.get('width')).store('oHeight',el.get('height')).set({'width':'','height':''});
				}
				return true;
			}
		},this);
		
		
		
		/* create the action function */
		var action = function() {
			var cpos = this.container.getScroll()[axis];
			if(cpos > this.startPosition) {
				this.elements = this.elements.filter(function(el) {
					if((cpos + this.options.range + this.containerDimension) >= el.getPosition(offset)[axis]) {
						if(el.retrieve('oSRC')) { el.set('src',el.retrieve('oSRC')); el.fade(1);}
						if(this.options.resetDimensions) {
							el.set({ width: el.retrieve('oWidth'), height: el.retrieve('oHeight') });
						}
						
						this.fireEvent('load',[el]);
						return false;
					}
					return true;
				},this);
				this.startPosition = cpos;
			}
			this.fireEvent('scroll');
			/* remove this event IF no elements */
			if(!this.elements.length) {
				this.container.removeEvent('scroll',action);
				this.fireEvent('complete');
			}
		}.bind(this);

		/* listen for scroll */
		window.addEvent('scroll',action);
		if(this.options.fireScroll) { action();}
	}
});

window.addEvent('domready', function(){    
     var lazyloader = new LazyLoad({
       range: 20,
       image: $GK_TMPL_URL+'/images/blank.gif',
       resetDimensions: false,
       elements: 'img'
     });
});

function ArraySubtract(ara1,ara2) { 
  var aRes = new Array() ; 
  for(var i=0;i<ara1.length;i++) { 
    if( ! (ara2.contains(ara1[i]) )) { 
      aRes.push(ara1[i]) ; 
    } 
  } 
  return aRes ; 

}

/*
---
description: Additional methods for the Array class

license: MIT-style

authors:
- Alexander Herrmann

requires:
  core/1.4.3: [Array]

provides:
- Array.intersect
- Array.differentiate
- Array.getRange
- Array.reverse()
...
*/
Array.implement({
	
	/**
	 * Creates an intersection of the current array and the given one.
	 * Returns as new array.
	 * @param Array other	the array to use
	 * @return Array
	 */
	intersect: function(other) {
		var cpy = this.slice();
		this.each(function(el) {
			if (other.indexOf(el) < 0) {			
				cpy.splice(cpy.indexOf(el), 1);
			}
		}, this);
		return cpy;
	},
	
	/**
	 * Returns the symmetric difference between this array and the given one.
	 * Means the items both arrays include are removed from both and then both are combined.
	 * @param Array other	the array to use
	 * @return Array
	 */
	differentiate: function(other) {
		var src = this.slice();
		var cmp = other.slice();
		other.each(function(elem) {
			if (src.indexOf(elem) > -1) {
				// remove from both
				src.splice(src.indexOf(elem), 1);
				cmp.splice(cmp.indexOf(elem), 1);
			}
		}, this);
		// combine remaining items
		return src.combine(cmp);
	}
});
