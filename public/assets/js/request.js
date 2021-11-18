const select = document.getElementsByClassName('company-level');
const progressBars = document.getElementsByClassName('progress-bar');
const checked = document.getElementsByClassName('checked');
for (let i = 0; i < select.length; i++) {
    select[i].addEventListener('change', () => {
        const companyId = select[i].dataset.id;
        const advancementId = select[i].value;
        const userId = select[i].dataset.user;
        fetch('/update/advance', {
            method: 'POST',
            body: JSON.stringify({'companyId': companyId, 'advancement': advancementId, 'userId': userId})
        })
            .then((res) => { return res.json(); })
            .then((data) => {
                console.log(JSON.stringify(data));
                progressBars[i].style.width = (select[i].value / (parseInt(select[i].options.length) - 1) * 100) + '%';
                if (parseInt(select[i].value) === (parseInt(select[i].options.length) - 1)) {
                    progressBars[i].classList.add('bg-success');
                    progressBars[i].classList.remove('bg-danger');
                } else if (parseInt(select[i].value) === select[i].options.length) {
                    progressBars[i].classList.add('bg-danger');
                    progressBars[i].classList.remove('bg-success');
                } else {
                    progressBars[i].classList.add('bg-primary');
                    progressBars[i].classList.remove('bg-danger');
                    progressBars[i].classList.remove('bg-success');
                }
                checked[i].innerHTML = '<i class="fas fa-check"></i>'
                //checked[i].classList.toggle('d-none');
                setTimeout(() => {
                    checked[i].innerHTML = ''
                    //checked[i].classList.toggle('d-none');
                }, 2000)
            })
    })
}
