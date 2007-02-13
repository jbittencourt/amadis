function Aggregator_deleteSource(id)
{
	var ids = id.split('_');

	AMAggregator.onDeleteSourceError = AM_callBack.onError;
	AMAggregator.deleteSource(ids[1], AMAggregatorCallBack.onDeleteSource);	
}

function Aggregator_toggleStatus(id)
{
	var ids = id.split('_');
	AMAggregator.onToggleStatusError = AM_callBack.onError;
  	AMAggregator.toggleStatus(ids[1], AMAggregatorCallBack.onToggleStatus);
}

function validateURL(url) 
{
	var er = /^http:\/\/[a-z]+.[a-z]+.[a-z]+/i;
	if (er.test(url)) return true;
	else return false;	
}

function Aggregator_addSource(projId)
{
	var title = AM_getElement('frm_name').value;
	var link = AM_getElement('frm_rssLink').value;

	if(title.length > 0 && link.length > 0) {
		if(validateURL(link)) {
			AMAggregator.onAddSourceError = AM_callBack.onError;
			AMAggregator.addSource(projId, title, link, AMAggregatorCallBack.onAddSource);
		} else alert('Endereco inadequado!');	
	} else {
		AM_getElement('frm_name').focus();
	}	
}

function Aggregator_addFilter(projId, count)
{
	var filter = AM_getElement('frm_filter').value;
	
	AMAggregator.onAddFilterError = AM_callBack.onError;
	AMAggregator.addFilter(projId, filter, count, AMAggregatorCallBack.onAddFilter);
	
}

function Aggregator_deleteFilter(projId, filter, count)
{
	AMAggregator.onDeleteFilterError = AM_callBack.onError;
	AMAggregator.deleteFilter(projId, filter, count, AMAggregatorCallBack.onDeleteFilter);
}

var AMAggregatorCallBack = {
	onToggleStatus: function(result) {
		if(result != 0) {
			var img = AM_getElement(result.id);
			img.src = result.src;
		}else alert('Ocorreu um erro');
	},
	onDeleteSource: function(result) {
		if(result != 0 ) {
			var item = AM_getElement('source_'+result.id);
			item.parentNode.removeChild(item);
		} else alert('Ocorreu um erro');
	},
	onAddFilter: function(result) {
		if(result != 0) {
			var filters = AM_getElement('filters');
			
			var filter = document.createElement('SPAN');
			filter.id = 'filter_'+result.count;
			filter.innerHTML = result.src;
			filters.appendChild(filter);
		}
	},
	onDeleteFilter: function(result) {
		if(result != 0) {
			var el = AM_getElement('filter_'+result.id);
			el.parentNode.removeChild(el);
		}
	},
	onAddSource: function(result) {
		if(result != 0) {
			var source_list = AM_getElement('sources_list');
			var span = document.createElement('SPAN');
			span.id = 'source_'+result.id;
			
			span.innerHTML = result.src;
			
			source_list.appendChild(span);
			AM_getElement('frm_name').value = '';
			AM_getElement('frm_rssLink').value = '';
			
		} else alert('Ocorreu um erro');
	}
}

