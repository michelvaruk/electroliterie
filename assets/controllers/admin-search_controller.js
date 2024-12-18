import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        let rows = document.querySelectorAll('tbody tr')
            
        function liveSearch() {
            let search_query = document.getElementById("searchbox").value;
            
            //Use innerText if all contents are visible
            //Use textContent for including hidden elements
            for (var i = 0; i < rows.length; i++) {
                if(rows[i].textContent.toLowerCase()
                        .includes(search_query.toLowerCase())) {
                    rows[i].classList.remove("hidden");
                } else {
                    rows[i].classList.add("hidden");
                }
            }
        }
        
        //A little delay
        let typingTimer;
        let typeInterval = 300;
        let searchInput = document.getElementById('searchbox');
        
        searchInput.addEventListener('keyup', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(liveSearch, typeInterval);
        });
    }

}
