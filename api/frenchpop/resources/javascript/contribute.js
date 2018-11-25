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
}

window.addEventListener("load",function() {
   var contrib=new contribute(); 
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
                                    <br/><br/>
                                    <span class="#">Pourquoi ?</span>                                               
                                    <textarea class="uk-search-input searchForm" style="height:80px;" id='comment{{id}}'></textarea>
                                    <div class=""></div>
                                    <hr>
                                    <button class="contribute uk-button uk-button-primary">Contribuer</button>
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
                       document.getElementById('contribDialog').innerHTML=document.getElementById('contribDialog').innerHTML+item+'<hr/>';
                   }
               }
               break;
       }
   });
});