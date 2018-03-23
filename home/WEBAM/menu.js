function Menu(){
//thanks to :Project Seven Development

if(navigator.appVersion.indexOf("MSIE")==-1){return;}

var i,k,g,lg,r=/\s*hvr/,nn='',c,cs='hvr',bv='menubar';
for(i=0;i<6;i++){
	g=document.getElementById(bv+nn);
	if(g){
		lg=g.getElementsByTagName("LI");
		if(lg){
			for(k=0;k<lg.length;k++){
				lg[k].onmouseover=function(){
					c=this.className;
					cl=(c)?c+' '+cs:cs;
					this.className=cl;
				};
				lg[k].onmouseout=function(){
					c=this.className;
					this.className=(c)?c.replace(r,''):'';
				};
			}
		}
	}
	nn=i+1;
}
}