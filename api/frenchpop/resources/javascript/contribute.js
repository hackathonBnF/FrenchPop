class contribute {
    constructor() {
        
    }
    
    sendRequest(url) {
        const req = new XMLHttpRequest();
        req.open('GET', url, false); 
        req.send(null);

        if (req.status === 200) {
            console.log(JSON.parse(req.responseText));
            return JSON.parse(req.responseText);
        }
    }
    
    getPersons(person) {
        console.log("../ajax/searchPerson?person="+encodeURI(person));
        var p=this.sendRequest("../ajax/searchPerson?person="+encodeURI(person));
        return p;
    }
    
    addSugTag(tag,tagField,i) {
        var tags=tagField.value;
        tags=tags.split(" ");
        tags[tags.length-1]=tag;
        tagField.value=tags.join(" ")+" ";
        tagField.focus();
        document.getElementById("sugtags"+i).style.display='none';
        
    }
    
    submit(i) {
        var sub={
            ark:document.getElementById('ark'+i).value,
            tags:document.getElementById('tags'+i).value,
            comment:document.getElementById('comment'+i).value,
            id_thematique:document.getElementById('id_thematique').value,
            id_type:document.getElementById('id_type').value
        }
        var s=this.sendRequest("../ajax/addPerson?content="+encodeURIComponent(JSON.stringify(sub)));
        document.location='../frenchpop/accueil';
    }
}

window.addEventListener("load",function() {
   window['contrib']=new contribute();
   document.getElementById('searchButton').addEventListener("click",function() {
       var query=document.getElementById('searchQuery').value;
       var id_type=document.getElementById('id_type').value;
       console.log(query);
              console.log(id_type);
       switch(id_type) {
           case "1":
               var persons=contrib.getPersons(query);
               var pattern=`<span>
                            <a href="#" data-uk-dropdown="{mode:'click'}">accesPoint
                                <span class="uk-dropdown" style="width:100%; background:white; margin:0;">
                                <span class="#">Tags</span>  
                                    <input type='hidden' id='ark{{id}}' value='{{ark}}'>
                                    <input class="uk-search-input searchForm" type="search" placeholder="" id='tags{{id}}'>
                                    <div id='sugtags{{id}}'>
                                    </div>
                                    <br/><br/>
                                    <span class="#">Pourquoi ?</span>                                               
                                    <textarea class="uk-search-input searchForm" style="height:80px;" id='comment{{id}}'></textarea>
                                    <div class=""></div>
                                    <hr>
                                    <button class="contribute uk-button uk-button-primary" onclick='contrib.submit({{id}})'>Contribuer</button>
                                </span>
                            </a>
                        </span>`;
               if (persons.length) {
                   for (var i in persons) {
                       var p=persons[i];
                       var item=pattern.replace("accesPoint",p.accesPoint);
                       var item=item.replace("{{ark}}",p.ark);
                       item=item.replace("{{id}}",i);
                       item=item.replace("{{id}}",i);
                       item=item.replace("{{id}}",i);
                       item=item.replace("{{id}}",i);
                       item=item.replace("{{id}}",i);
                       document.getElementById('contribDialog').innerHTML=document.getElementById('contribDialog').innerHTML+item+'<hr/>';
                       document.getElementById('tags'+i).addEventListener('keyup',function(e) {
                           var tags=document.getElementById('tags'+i).value;
                           tags=tags.split(" ");
                           var lastTag=tags[tags.length-1];
                           if (lastTag) var sugtags=contrib.sendRequest("../ajax/searchTags?tag="+encodeURIComponent(lastTag));
                           var sugtagsHTML='';
                           for (var j in sugtags) {
                               sugtagsHTML+="<a href='#' onclick='contrib.addSugTag(\""+sugtags[j].label+"\",document.getElementById(\"tags"+i+"\"),"+i+")'>"+sugtags[j].label+"</a><br/>";
                           }
                           document.getElementById("sugtags"+i).innerHTML=sugtagsHTML;
                           document.getElementById("sugtags"+i).style.display='block';
                       });
                   }
               }
               break;
       }
   });
});