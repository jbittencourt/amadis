var chatSyncronizer = {
  chatTable:new Array(),
  send:function() {
    for(var i in this.chatTable) {
      this.chatTable[i].sendRequest();
    }
  },
  register:function(obj, name) {
    chatTable[name] = obj;
  }
};

/**
 *Esta eh uma funcao abstrata que deve ser implementada no seu cliente de chat.
 *Ela eh chamada pelo metodo chatSyncronizer::send(), isso serve para que nao tenhamos 
 *colisoes no tunel de comunicacao do JPSpan AJAX Framework.
 */

/*abstract*/ var sendRequest = function(){};
