const canvas = document.getElementById('myChart');
const ctx = canvas.getContext('2d');
const labels = canvas.dataset.name.split(',');
const datas = canvas.dataset.stat.split(',');
const isAdmin = parseInt(canvas.dataset.admin);
let type = 'bar';
if (isAdmin) {
    type = 'doughnut';
}
const myChart = new Chart(ctx, {
    type: type,
    data: {
        labels: labels,
        datasets: [{
            label: 'Status de prospection',
            data: datas,
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(64,255,109,1)',
                'rgba(190,60,157,1)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(64,255,109,1)',
                'rgba(190,60,157,1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                display: false,
            }
        }
    }
});