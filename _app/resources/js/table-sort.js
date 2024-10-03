document.addEventListener('DOMContentLoaded', function () {
    tableSort();
    flashMessage();
});
function tableSort() {
    const table = document.getElementById('sortable-table');
    if (table != null) {
        const headers = table.querySelectorAll('th[data-column]');

        headers.forEach(header => {
            header.addEventListener('click', () => {
                const column = header.getAttribute('data-column');
                const order = header.getAttribute('data-order');
                const rows = Array.from(table.querySelectorAll('tbody tr'));

                // Ordina le righe
                const sortedRows = rows.sort((a, b) => {
                    const aText = a.querySelector(`td:nth-child(${header.cellIndex + 1})`).textContent.trim().toLowerCase();
                    const bText = b.querySelector(`td:nth-child(${header.cellIndex + 1})`).textContent.trim().toLowerCase();

                    if (aText < bText) {
                        return order === 'asc' ? -1 : 1;
                    } else if (aText > bText) {
                        return order === 'asc' ? 1 : -1;
                    }
                    return 0;
                });

                // Aggiorna l'ordine
                header.setAttribute('data-order', order === 'asc' ? 'desc' : 'asc');

                // Rimuove le vecchie righe e aggiunge le nuove ordinate
                const tbody = table.querySelector('tbody');
                tbody.innerHTML = '';
                sortedRows.forEach(row => tbody.appendChild(row));

                // Rimuovi tutte le icone dagli altri header
                headers.forEach(h => {
                    const icon = h.querySelector('svg');
                    if (icon) {
                        icon.remove();
                    }
                });

                // Aggiungi icona di ordinamento con SVG diretti
                const iconHTML = order === 'asc'
                    ? `<svg class="w-4 h-4 inline-block ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>`
                    : `<svg class="w-4 h-4 inline-block ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>`;

                header.insertAdjacentHTML('beforeend', iconHTML);
            });
        });
    }
}
function flashMessage(){
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.transition = 'opacity 0.5s ease';
            flashMessage.style.opacity = '0';
            setTimeout(() => {
                flashMessage.remove();
            }, 500);
        }, 3000);
    }
}
