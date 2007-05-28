/*

jsCrossmark - JavaScript Crossmark Markup Interpreter
=====================================================

:Author
   [[ Daniel Monteiro Basso | mailto:daniel@basso.inf.br ]]
   [[ Laboratório de Estudos Cognitivos | http://www.lec.ufrgs.br ]]
   [[ Universidade Federal do Rio Grande do Sul | http://www.ufrgs.br ]]


   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Library General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.

*/

function CrossmarkMacros()
{
   this.localization=new Array()
   this.macros=new Array()
   
   this.registerMacro=function(name, handler)
   {
      this.macros[name.toLowerCase()]=handler
   }
   
   this.call=function(name,params,block,semanticActions,semanticAnaliser)
   {
      var mname=name.toLowerCase()
      if (this.localization[mname])
         mname=this.localization[mname]
      var m=this.macros[mname]
      if (m) {
         var retv
         try {
            retv=m(params,block,semanticActions,semanticAnaliser)
         } catch(e) {
            retv='[the macro "'+name+'" triggered the error "'+
               e.name+' - '+e.message+'".]'
         }
         return retv
      } else
         return '[macro "'+name+'" undefined.]'
   }
}

cmMacros=new CrossmarkMacros()

cmMacros.registerMacro('author', function(params,block,sact,sanl)
{
   return sact.blockquote('<div align="center"><b>Author</b></div>'+
      sanl.commonParse(block),'author')
})

cmMacros.registerMacro('metadata', function(params,block,sact,sanl)
{
   return sact.blockquote('<div align="center"><b>Metadata</b></div>'+
      sanl.commonParse(block),'metadata')
})

cmMacros.registerMacro('summary', function(params,block,sact,sanl)
{
   return sact.blockquote('<div align="center"><b>Summary</b></div>'+
      sanl.commonParse(block),'summary')
})

cmMacros.registerMacro('quote', function(params,block,sact,sanl)
{
   return sact.blockquote(sanl.commonParse(block))
})

cmMacros.registerMacro('note', function(params,block,sact,sanl)
{
   if (!block)
      block=params.join(' ')
   return sact.blockquote('<div align="center"><b>Note</b></div>'+
      sanl.commonParse(block),'note')
})

cmMacros.registerMacro('image', function(params,block,sact,sanl)
{
   return sact.image(params[0],params[1])
})

cmMacros.registerMacro('date', function(params,block,sact,sanl)
{
   return params.join(' ')
})

cmMacros.registerMacro('code', function(params,block,sact,sanl)
{
   if (params[0]=='javascript')
   {
      var retv=eval(block)
      if (retv)
         return sact.blockquote(
            '<div align="center"><b>Code execution results</b></div>'+retv,'note')
      return ''
   }
})

cmMacros.registerMacro('table', function(params,block,sact,sanl)
{
   var tab=/((^|[^\\])(#|\|)\s*)(([^\n#\|]|(\\#)|(\\\|))+)([^\\](#|\|))/g
   var txt='<table><tr>'
   var s
   do {
      s=tab.exec(block)
      if (!s) break
      if (s[2]=='\n') txt+='<br/><tr/>'
      if (s[3]=='#' || s[9]=='#')
         txt+='<th/>'
      else
         txt+='<td/>'
      txt+=s[4]
      tab.lastIndex-=2
   } while (s)
   return txt
      .replace(/\\#/g,'#')
      .replace(/\\\|/g,'|')
      +'</table>'
})

cmMacros.registerMacro('math', function(params,block,sact,sanl)
{
   return '[sorry, no "math" in jsCrossmark. {'+
      sact.escapeSpecials(
         (params?params.join(' '):'')+(block?block:'')
      )+'}]'
})

cmMacros.registerMacro('raw', function(params,block,sact,sanl)
{
   return sact.escapeSpecials(block)
          .replace(/\n/g,'<br/>')
          .replace(/ /g,'&nbsp;')
})

function styleParser(trigger)
{
   // can't use \w because it has '_'
   var W='[^a-zA-Z0-9\\<\\[]'
   var w='[a-zA-Z0-9]'

   // remember, the backslashes are escaped
   // each '\\' means only '\' to the regexp
   return new RegExp( 
      '(.*' // anything
      +W    // followed by non word (a space for instance)
      +')\\'+trigger // then the trigger
      +'('+w         // then a word beggining
      +'([^\\'+trigger     // followed by whatever but the trigger
      +']|(\\\\\\'+trigger // or the trigger escaped
      +'))*'+w       // then a word ending
      +')\\'+trigger // close to the trigger
      +'(('+W+'|$).*)'   // then non word followed by whatever
   )
}

function splitSintaxBlocks(text)
{
   // sintaxBlocks=splitSintaxBlocks(text):
   // - trim right spaces
   // - split in chunks of same indentation
   // - strip the indentation
   // - count the blank lines before the chunk

   var lines=text.replace(/ *\n/g,'\n').split('\n')
   var i=0
   var sintaxBlocks=new Array()
   
   while (i<lines.length)
   {
      var blanksBefore=0
      var block=new Array()

      while (!String(lines[i]).length && i<lines.length)
      {
         blanksBefore++
         i++
      }
      
      var indentLevel=String(lines[i]).search(/\S/)
      
      while (indentLevel==String(lines[i]).search(/\S/) && i<lines.length)
      {
         block.push(String(lines[i]).substring(indentLevel))
         i++
      }
      
      sintaxBlocks.push({
         blanksBefore: blanksBefore,
         block: block,
         indentLevel: indentLevel
      })
   }
   return sintaxBlocks
}


// Crossmark Semantic Analiser States
cmsas={
   scan: 1,
   done: 2,
   doneDiscard: 3,
   grabBlock: 4,
   callMacro: 5,
   quoteBlock: 6,
   checkList: 7,
   mountList: 8,
   multiLineItem: 9,
}

function SemanticAnaliser(sintaxBlocks, semanticActions, position, state)
{
   this.sintaxBlocks=sintaxBlocks
   this.semanticActions=semanticActions
   this.pos=position?position:0
   this.state=state?state:cmsas.scan

   this.result=''
   this.headingLevel=1

   this.iteration=function()
   {
      this.cursb=this.sintaxBlocks[this.pos]
      this.nxtsb=this.sintaxBlocks[this.pos+1]
      
      if (!this.cursb)
      {
         this.state=cmsas.done
         return
      }

      if (this.state==cmsas.scan)
      {
         if (this.checkHeading()) return
         if (this.checkBlockMacro()) return
         if (this.checkBlockquote()) return
         if (this.checkItemList())
         {
            this.clIndent=-1
            this.clTopLevel=true
            this.state=cmsas.mountList
            this.mountList()
            return
         }
         
         this.result+=this.semanticActions.paragraph(
            this.commonParse(this.cursb.block.join(' ')))
         this.pos++
         return
      }

      if (this.state==cmsas.grabBlock)
      {
         if (!this.grabBlock())
            this.state=this.gbRetState
         return
      }
      
      if (this.state==cmsas.callMacro)
      {
         this.result+=cmMacros.call(
            this.macroName,
            this.macroArgs,
            this.gbBlock,
            this.semanticActions,
            this
         )
         this.state=cmsas.scan
         return
      }

      if (this.state==cmsas.quoteBlock)
      {
         this.result+=this.semanticActions.blockquote(
            this.commonParse(this.gbBlock)
         )
         this.state=cmsas.scan
         return
      }

      if (this.state==cmsas.checkList)
      {
         if (this.checkItemList())
         {
            this.result="It's an item list.\n"
            this.state=cmsas.done
         } else
            this.state=cmsas.doneDiscard
         return
      }
      
      if (this.state==cmsas.mountList)
      {
         this.mountList()
         return
      }
      
      this.pos++
   }
   
   this.analise=function()
   {
      while (this.state!=cmsas.done)
         this.iteration()
      if (this.state!=cmsas.doneDiscard)
         return this.result
      return false
   }

   ////////////////////////////////////////////////// BLOCK
   // sloppy code, but most won't notice (just like the fast styling)
   this.grabBlock=function()
   {
      if (!this.cursb.indentLevel
         || (!this.gbFirst && this.cursb.indentLevel<this.gbIndent)
         || (!this.gbFirst && this.cursb.blanksBefore>1))
         return false
      if (this.nxtsb && !this.nxtsb.indentLevel && this.nxtsb.blanksBefore<1)
         return false

      if (this.gbFirst)
         this.gbIndent=this.cursb.indentLevel

      var tind=''
      for (var i=this.gbIndent; i<this.cursb.indentLevel; i++)
         tind+=' '
      for (var i=0; i<this.cursb.blanksBefore; i++)
         this.gbBlock+='\n'
         
      this.gbBlock+=tind+this.cursb.block.join('\n'+tind)+'\n'

      this.pos++
      this.gbFirst=false
      return true
   }
   
   this.checkBlockquote=function()
   {
      if (!this.cursb.indentLevel || this.cursb.blanksBefore<1)
         return false

      if (this.nxtsb && !this.nxtsb.indentLevel && this.nxtsb.blanksBefore<1)
         return false

      this.state=cmsas.grabBlock
      this.gbBlock=''
      this.gbIndent=this.cursb.indentLevel
      this.gbFirst=true
      this.gbRetState=cmsas.quoteBlock
      return true
   }
   ////////////////////////////////////////////////// BLOCK

   ////////////////////////////////////////////////// MACRO CALL
   this.blockMacro_re=/^:(\w+)(\s|$)/

   this.checkBlockMacro=function()
   {
      if (this.cursb.indentLevel)
         return false
      
      var m=this.blockMacro_re.exec(this.cursb.block[0])
      if (!m)
         return false

      this.macroName=m[1]
      this.macroArgs=this.cursb.block[0].substring(m[1].length+2).split(/,? /)
      
      this.state=cmsas.grabBlock
      this.gbBlock=''
      this.gbFirst=true
      this.gbRetState=cmsas.callMacro
      
      this.pos++
      return true
   }
   
   this.inlineMacro=function(txt)
   {
      if (!txt)
         return ''
      var chunks=txt.split(/<(\w+[\s\S]*\S)>/gm) // '.' doesn't get \n
      var i=1
      var retv=this.style(
                  this.links(
                     this.semanticActions.escapeSpecials(
                        chunks[0]
                     )
                  )
               )

      while (i<chunks.length)
      {
         var macro=chunks[i].split(/(\w+)(\s.*)/g)
         if (macro[1].toLowerCase()=='raw' 
         || cmMacros.localization[macro[1].toLowerCase()]=='raw' )
            retv+=this.semanticActions.escapeSpecials(macro[2])
         else
            retv+=//" MACRO CALL "+macro[1]+
            this.style(
                  this.links(
                     cmMacros.call(
                        macro[1],
                        macro[2].split(/\s*,\s*/g),
                        false,
                        this.semanticActions,
                        this
                     )
                  )
               )
            //+" END "
         i++
         retv+=this.style(
                  this.links(
                     this.semanticActions.escapeSpecials(
                        chunks[i]
                     )
                  )
               )
         i++
      }
      return retv
   }

   ////////////////////////////////////////////////// MACRO CALL

   ////////////////////////////////////////////////// ITEM LIST
   // sloppiest code you'll find here, and the effects
   // most *will* notice
   
   this.explicitOrderedList_re=/^((\d+\.)+) (.*)$/ //$3
   this.implicitOrderedList_re=/^# (.*)$/ //$1
   this.unorderedList1_re=/^\* (.*)$/
   this.unorderedList2_re=/^\- (.*)$/

   this.checkItemList=function(nochild)
   {
      if (this.checkListType(this.explicitOrderedList_re,1,nochild))
         return true
      if (this.checkListType(this.implicitOrderedList_re,2,nochild))
         return true
      if (this.checkListType(this.unorderedList1_re,3,nochild))
         return true
      if (this.checkListType(this.unorderedList2_re,4,nochild))
         return true
      return false
   }

   this.checkListType=function(re,subtype,nochild)
   {
      for (var i=0; i<this.cursb.block.length; i++)
      {
         re.test.lastIndex=0
         if (!re.test(this.cursb.block[i]))
            return false
      }
      //if immediate, next block must have sub or
      //super items, or continued line.
      //notice we don't check a lot of thing, such
      //as continuity, type consistency, indents, etc...
      if (!nochild && this.nxtsb && !this.nxtsb.blanksBefore)
      {
         var innersa=new SemanticAnaliser(
            this.sintaxBlocks,this.semanticActions,this.pos+1, cmsas.checkList)
         if (!innersa.analise())
         {
            // anything with a good indent will do it :p
            if (this.nxtsb.indentLevel>this.cursb.indentLevel+2)
               return true
            return false
         }
      }
      this.clSubType=subtype
      this.clRegExp=re
      return true
   }

   this.mountList=function()
   {
      if (this.clIndent>-1 && // if not first block
         (this.clIndent>this.cursb.indentLevel || this.cursb.blanksBefore>0)
         )
      {
         //alert(this.cursb.block[0]+"toplevel"+this.clTopLevel)
         this.result+=this.semanticActions.endList(
            (this.clSubType==1||this.clSubType==2)?1:2
         )
         if (this.clTopLevel)
         {
            this.state=cmsas.scan
            return
         }
         this.state=cmsas.done
         return
      }

      if (this.clIndent==-1) // first block
      {
         if (!this.checkItemList(true))
         {
            this.result+=this.semanticActions.multiLineItem(
               '\n'+this.cursb.block.join('\n')
            )
            //this.clIndent=this.cursb.indentLevel
            if (this.clTopLevel)
            {
               this.state=cmsas.scan
               return
            }
            this.state=cmsas.done
            return
         }
         this.clIndent=this.cursb.indentLevel
         this.result+=this.semanticActions.beginList(
            (this.clSubType==1||this.clSubType==2)?1:2
         )
      }
      
      if (this.clIndent<this.cursb.indentLevel) // inner block
      {
         var innersa=new SemanticAnaliser(
            this.sintaxBlocks,this.semanticActions,this.pos, cmsas.mountList)
         innersa.clIndent=-1
         this.result+=innersa.analise()
         if (this.pos==innersa.pos)
            this.pos=innersa.pos+1 // patologic
            // but works, and as I'm tired I'll left it as it is
         else
            this.pos=innersa.pos
         //alert(innersa.cursb.block[0]+" i.pos "+innersa.pos)
         return
      } else { // same indent
         for (var i=0; i<this.cursb.block.length; i++)
            this.result+=this.semanticActions.listItem(
               this.commonParse(
                  this.cursb.block[i].replace(
                     this.clRegExp,
                     this.clSubType==1?'$3':'$1'
                  )
               )
            )
      }
      this.pos++
   }
   ////////////////////////////////////////////////// ITEM LIST

   ////////////////////////////////////////////////// HEADING LEVEL
   this.heading2=/== ([^\n]+) ==/ //backwards compatibility?
   this.heading3=/=== ([^\n]+) ===/
   this.heading4=/==== ([^\n]+) ====/

   this.checkHeading=function()
   {
      if (  this.cursb.indentLevel>0
         || (this.pos>1 && this.cursb.blanksBefore<1)
         || !this.nxtsb
         || this.nxtsb.blanksBefore<1  )
      {
         return false
      }

      if (this.cursb.block.length==1) // one liner
      {
         if (this.heading4.test(this.cursb.block[0]))
            return this.headingAction(4,this.cursb.block[0].replace(this.heading4,'$1'))
         if (this.heading3.test(this.cursb.block[0]))
            return this.headingAction(3,this.cursb.block[0].replace(this.heading3,'$1'))
         if (this.heading2.test(this.cursb.block[0]))
            return this.headingAction(2,this.cursb.block[0].replace(this.heading2,'$1'))
      } else
      if (this.cursb.block.length==2) // two liner
      {
         // from Ivan K.: "should just be defined as a fixed number (say, 3) or more"
         if (/^\={3,}$/.test(this.cursb.block[1]))
            return this.headingAction(1,this.cursb.block[0])
         if (/^\-{3,}$/.test(this.cursb.block[1]))
            return this.headingAction(2,this.cursb.block[0])
      }
      return false
   }

   this.headingAction=function(level,text)
   {
      if (level>=this.headingLevel)
      {
         if (level==this.headingLevel && this.pos)
            this.result+=this.semanticActions.leaveSubLevel()
         this.result+=this.semanticActions.enterSubLevel()
         this.result+=this.semanticActions.heading(level,this.style(text))
         var innersa=new SemanticAnaliser(this.sintaxBlocks,this.semanticActions,this.pos+1)
         innersa.headingLevel=level
         this.result+=innersa.analise()
         if (level!=this.headingLevel)
            this.result+=this.semanticActions.leaveSubLevel()
         this.pos=innersa.pos
         return true
      } else
      if (level<this.headingLevel)
      {
         this.state=cmsas.done
         return true
      }
      this.result+=this.semanticActions.heading(level,this.style(text))
      this.pos++
      return true
   }
   ////////////////////////////////////////////////// HEADING LEVEL

   ////
   this.commonParse=function(txt)
   {
      return this.inlineMacro(txt)
      /*return this.style(
               this.links(
                  this.semanticActions.escapeSpecials(
                     this.inlineMacro(txt)
                  )
               )
             )*/
   }
   ////

   ////////////////////////////////////////////////// STYLING
   if (!this.correctStyling)
   {
      // inaccurate, but fast and good enough
      this.bold_re=/\B\*\b(([^\*]|\\\*)*)\b\*\B/g
      this.italic_re=/\B\/\b(([^\/\<\>]|\/b|\\\/)+)\b\/\B/g
      this.underline_re=/([^a-zA-Z0-9])_([a-zA-Z0-9])([^_]*)([a-zA-Z0-9])_([^a-zA-Z0-9]?)/g
      this.inlineMono_re=/\B\`\b(([^\`]|\\\`)*)\b\`\B/g

      this.style=function(txt)
      {
         return txt
            .replace(this.italic_re,this.semanticActions.italic_repl)
            .replace(/\\\//g,"/")
            .replace(this.bold_re,this.semanticActions.bold_repl)
            .replace(/\\\*/g,"*")
            .replace(this.underline_re,this.semanticActions.underline_repl)
            .replace(this.inlineMono_re,this.semanticActions.inlineMono_repl)
            .replace(/\\\`/g,"`")
      }
   } else {
      // accurate parsing
      this.italic_re=styleParser('/')
      this.bold_re=styleParser('*')
      this.underline_re=styleParser('_')
      this.inlineMono_re=styleParser('`')


      this.style=function(txt)
      {
         while (true) { // ITALIC
            var m=this.italic_re.exec(txt)
            if (!m) break
            txt=m[1]+this.semanticActions.italic(m[2].replace(/\\\//g,'/'))+m[5]
         }
         while (true) { // BOLD
            var m=this.bold_re.exec(txt)
            if (!m) break
            txt=m[1]+this.semanticActions.bold(m[2].replace(/\\\*/g,'*'))+m[5]
         }
         while (true) { // UNDERLINE
            var m=this.underline_re.exec(txt)
            if (!m) break
            txt=m[1]+this.semanticActions.underline(m[2].replace(/\\_/g,'_'))+m[5]
         }
         while (true) { // INLINE MONOSPACE
            var m=this.inlineMono_re.exec(txt)
            if (!m) break
            txt=m[1]+this.semanticActions.monospace(m[2].replace(/\\`/g,'`'))+m[5]
         }
         return txt
      }
   }
   ////////////////////////////////////////////////// STYLING

   ////////////////////////////////////////////////// LINKS
   this.unnamedLinks_re=/\[\[\s*([^\|\[]*)\s*\]\]/g
   this.namedLinks_re=/\[\[\s*([^\|\[]*)\s*\|\s*([^\|\[]*)\]\]/g
   this.externalLinkMatch_re=/((http)|(mailto)|(ftp)\:).*/g

   this.links=function(txt)
   {
      var chunks=txt.split(this.unnamedLinks_re)
      txt=''
      for (var i=0; i<chunks.length;)
      {
         txt+=chunks[i]
         i++;
         if (i==chunks.length)
            break
         chunks[i]=chunks[i].replace(/\n/g,' ')

         this.externalLinkMatch_re.lastIndex=0

         if ( this.externalLinkMatch_re.test(chunks[i]) )
            txt+=this.semanticActions.link(chunks[i],chunks[i])
         else
            txt+=this.semanticActions.linkMangled(chunks[i],chunks[i])
         i++
      }
      chunks=txt.split(this.namedLinks_re)
      txt=''
      for (i=0; i<chunks.length;)
      {
         txt+=chunks[i]
         i++
         if (i==chunks.length)
            break
         chunks[i]=chunks[i].replace(/\n/g,' ')
         chunks[i+1]=chunks[i+1].replace(/\n/g,' ')
         
         this.externalLinkMatch_re.lastIndex=0

         if ( this.externalLinkMatch_re.test(chunks[i+1]) )
            txt+=this.semanticActions.link(chunks[i],chunks[i+1])
         else
            txt+=this.semanticActions.linkMangled(chunks[i],chunks[i+1])

         // I still don't know why, but the RegExp keeps
         // a state (or whatever is the reason) so I have
         // to call again to reset
         //this.externalLinkMatch_re.test(chunks[i+1])
         //found:
         // http://ecmanaut.blogspot.com/2006/09/regexp-peculiarities-and-pitfalls.html  

         i+=2
      }
      return txt
   }
   ////////////////////////////////////////////////// LINKS

}

function htmlSemanticActions()
{
   this.wikiManagerAddress="wikiManager.php?id="

   // HEADING LEVEL
   this.enterSubLevel=function()
   {
      return '<div class="section">'
   }
   this.leaveSubLevel=function()
   {
      return '</div>'
   }
   this.heading=function(level,text)
   {
      return '<h'+level+'>'+text+'</h'+level+'>'
   }
   // STYLING
   // - accurate:
   this.italic=function(text)
   {
      return '<i>'+text+'</i>'
   }
   this.bold=function(text)
   {
      return '<b>'+text+'</b>'
   }
   this.underline=function(text)
   {
      return '<u>'+text+'</u>'
   }
   this.monospace=function(text)
   {
      return '<code>'+text+'</code>'
   }
   // - fast:
   this.italic_repl="<i>$1</i>"
   this.bold_repl="<b>$1</b>"
   this.underline_repl="$1<u>$2$3$4</u>$5"
   this.inlineMono_repl="<code>$1</code>"
   
   // LINK
   this.link=function(text,addr)
   {
      return "<a href='"+addr+"'>"+text+"</a>";
   }
   this.linkMangled=function(text,addr)
   {
      addr=this.wikiManagerAddress+addr.replace(/[^\#\.a-zA-Z0-9]*/g,'')
      return "<a href='"+addr+"'>"+text+"</a>";
   }

   //PARAGRAPH   
   this.paragraph=function(text)
   {
      return '<p>'+text+'</p>'
   }
   //BLOCKQUOTE
   this.blockquote=function(text,style)
   {
      var lines=text.split('\n')
      for (var i=0; i<lines.length; i++)
      {
         var j
         var lead=''
         var l=String(lines[i])
         for (j=0; j<l.length; j++)
         {
            if (l.charAt(j)!=' ')
               break;
            lead+='&nbsp;'
         }
         lines[i]=lead+l.substring(j)
      }
      text=lines.join('<br/>')

      return '<div class="'+(style?style:'quote')+'">'+
         text.replace(/^<br\/>*/,'')
         +'</div>'
   }
   // LISTS
   this.beginList=function(type)
   {
      return type==1?'<ol>':'<ul>'
   }
   this.endList=function(type)
   {
      return type==1?'</ol>':'</ul>'
   }
   this.listItem=function(text)
   {
      return '<li/>'+text
   }
   this.multiLineItem=function(text)
   {
      return text.replace(/\n/g,'<br/>')
   }
   
   this.escapeSpecials=function(text)
   {
      return text.replace(/</g,"&lt;").replace(/>/g,"&gt;")
   }

   this.image=function(text, align)
   {
      return '<img src="'+text+'" '+(align?'align="'+align+'"':'')+'/>'
   }
}

function Crossmark()
{
   this.semanticActions=new htmlSemanticActions()

   this.parse=function(text)
   {
      var sintaxBlocks=splitSintaxBlocks(text)
      var rootSemanticAnaliser=new SemanticAnaliser(sintaxBlocks,this.semanticActions)
      var txt=rootSemanticAnaliser.analise()
      if (0)
         txt=txt.replace(/</g,'[').replace(/>/g,']')
      return txt
   }
}