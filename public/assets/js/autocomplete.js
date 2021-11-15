const ul = document.getElementById('autocompletion');
const input = document.getElementById('company');
let nameCompanies = input.dataset.name.split(',');
let autocompleteLis = document.getElementsByClassName('autocomplete');
document.body.addEventListener('click', () => {
    while (ul.lastElementChild) {
        ul.removeChild(ul.lastElementChild);
    }
})
input.addEventListener('keyup', () => {
    while (ul.lastElementChild) {
        ul.removeChild(ul.lastElementChild)
    }
    for (let i = 0; i < nameCompanies.length; i++) {
        if (nameCompanies[i].toLowerCase().includes(input.value)) {
            let li = document.createElement('li');
            li.classList.add('list-group-item');
            li.classList.add('autocomplete');
            li.classList.add('px-5');
            li.innerText = nameCompanies[i];
            ul.appendChild(li)
        }
    }
    if (autocompleteLis.length) {
        for (let i = 0; i < autocompleteLis.length; i++) {
            autocompleteLis[i].addEventListener('click', () => {
                input.value = autocompleteLis[i].innerHTML;
            })
        }
    }
})