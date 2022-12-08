function getAutoCompleteOptions(){
    let autocompleteOptionsClass = document.getElementsByClassName('autocompleteOptions');
    let autocompleteOptions = [];
    for (let i=0; i<autocompleteOptionsClass.length; i++){
        autocompleteOptions.push(autocompleteOptionsClass[i].textContent);
    }
    autocompleteOptions = [...new Set(autocompleteOptions.map(element => {
        return element.toLowerCase().trim();
    }).filter(element => element))];
    return autocompleteOptions;
}


function autocomplete(searchInput) {
    let currentFocus;
    let optionsArr = getAutoCompleteOptions();

    searchInput.addEventListener("input", function(e) {
        let outerDiv;
        let matchedSearch;
        let val = this.value;
        closeOptions();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        outerDiv = document.createElement("div");
        outerDiv.setAttribute("id", this.id + "autocomplete-list");
        outerDiv.setAttribute("class", "autocomplete-items");
        this.parentNode.appendChild(outerDiv);

        matchedSearch = document.createElement("div");
        matchedSearch.innerHTML = "<strong>" + val + "</strong>";
        matchedSearch.innerHTML += "<input type='hidden' value='" + val + "'>";
        matchedSearch.innerHTML += "<input type='hidden' value='" + val + "'>";
        outerDiv.appendChild(matchedSearch);
        for (let i = 0; i < Math.max(10, optionsArr.length); i++) {
            if (optionsArr[i].substr(0, val.length).toLowerCase() === val.toLowerCase()) {
                matchedSearch = document.createElement("div");
                matchedSearch.innerHTML = "<strong>" + optionsArr[i].substr(0, val.length) + "</strong>";
                matchedSearch.innerHTML += optionsArr[i].substr(val.length);
                matchedSearch.innerHTML += "<input type='hidden' value='" + optionsArr[i] + "'>";
                matchedSearch.addEventListener("click", function(e) {
                    searchInput.value = this.getElementsByTagName("input")[0].value;
                    closeOptions();
                });
                outerDiv.appendChild(matchedSearch);
            }
        }
        searchInput.addEventListener("keydown", function(e) {
            searchInput.value = document.querySelectorAll(".autocomplete-active")[0].textContent;
        });
    });

    searchInput.addEventListener("keydown", function(e) {
        let autocompleteList = document.getElementById(this.id + "autocomplete-list");
        if (autocompleteList) {
            autocompleteList = autocompleteList.getElementsByTagName("div")
        }
        if (e.keyCode === 40) { /*ArrowDown*/
            currentFocus++;
            addActive(autocompleteList);
        } else if (e.keyCode === 38) { /*ArrowUp*/
            currentFocus--;
            addActive(autocompleteList);
        } else if (e.keyCode === 13) { /*Enter*/
            e.preventDefault(); /*Prevent Form submission*/
            if (currentFocus > -1) {
                if (autocompleteList) autocompleteList[currentFocus].click();
            }
        }
    });

    function addActive(autocompleteList) {
        if (!autocompleteList) return false;
        removeActive(autocompleteList);
        if (currentFocus >= autocompleteList.length) {
            currentFocus = 0
        }
        if (currentFocus < 0) {
            currentFocus = (autocompleteList.length - 1)
        }
        autocompleteList[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(autocompleteList) {
        for (let i = 0; i < autocompleteList.length; i++) {
            autocompleteList[i].classList.remove("autocomplete-active");
        }
    }

    function closeOptions(element) {
        let currOptions = document.getElementsByClassName("autocomplete-items");
        for (let i = 0; i < currOptions.length; i++) {
            if (element !== currOptions[i] && element !== searchInput) {
                currOptions[i].parentNode.removeChild(currOptions[i]);
            }
        }
    }
    document.addEventListener("click", function (event) {
        closeOptions(event.target);
    });
}

autocomplete(document.getElementById("courseSearchBar"));
