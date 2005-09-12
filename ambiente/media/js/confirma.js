function confirma_redir(texto,url) {
    if (confirm(texto)) {
	window.location.href = url;
    }       
}
