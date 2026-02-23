<div class="bg-white shadow rounded-lg p-4">
    <div class="flex gap-2 mb-3">
        <button type="button" id="addColumnBtn" class="bg-blue-600 text-white px-3 py-1 rounded">+ Add Column</button>
        <button type="button" id="removeColumnBtn" class="bg-red-600 text-white px-3 py-1 rounded">- Remove Column</button>

        <button type="button" id="addRowBtn" class="bg-green-600 text-white px-3 py-1 rounded">+ Add Row</button>
        <button type="button" id="removeRowBtn" class="bg-orange-600 text-white px-3 py-1 rounded">- Remove Row</button>
    </div>

    <table id="dynamicTable" class="min-w-full border border-gray-300 rounded overflow-hidden">
        <thead></thead>
        <tbody></tbody>
    </table>

    <input type="hidden" name="detail_columns" id="detail_columns">
    <input type="hidden" name="detail_rows" id="detail_rows">
</div>

<script>
let columns = @json($columns ?? $defaultColumns ?? ['Column 1']);
let rows    = @json($rows ?? $defaultRows ?? [['']]);

function renderTable() {
    const thead = document.querySelector('#dynamicTable thead');
    const tbody = document.querySelector('#dynamicTable tbody');
    
    thead.innerHTML = '';
    tbody.innerHTML = '';

    const headerRow = document.createElement('tr');

    columns.forEach((col, colIndex) => {
        const th = document.createElement('th');
        th.className = "border bg-gray-100 p-2 text-sm font-semibold cursor-pointer";
        th.contentEditable = true;
        th.innerText = col;
        th.addEventListener('input', () => {
            columns[colIndex] = th.innerText;
            saveJson();
        });
        headerRow.appendChild(th);
    });

    thead.appendChild(headerRow);

    rows.forEach((row,rowIndex)=>{
        const tr = document.createElement('tr');

        row.forEach((cell, colIndex)=>{
            const td = document.createElement('td');
            td.className = "border p-1";
            const input = document.createElement('input');
            input.className="w-full border-0 p-1";
            input.value = cell;
            input.addEventListener('input',()=>{
                rows[rowIndex][colIndex] = input.value;
                saveJson();
            });
            td.appendChild(input);
            tr.appendChild(td);
        });

        tbody.appendChild(tr);
    });

    saveJson();
}

function saveJson() {
    document.getElementById('detail_columns').value = JSON.stringify(columns);
    document.getElementById('detail_rows').value = JSON.stringify(rows);
}

document.getElementById('addColumnBtn')?.addEventListener('click', () => {
    columns.push("Column " + (columns.length + 1));
    rows.forEach(row => row.push(""));
    renderTable();
});

document.getElementById('removeColumnBtn')?.addEventListener('click', () => {
    if(columns.length > 1){
        columns.pop();
        rows.forEach(row => row.pop());
        renderTable();
    }
});

document.getElementById('addRowBtn')?.addEventListener('click', () => {
    rows.push(new Array(columns.length).fill(""));
    renderTable();
});

document.getElementById('removeRowBtn')?.addEventListener('click', () => {
    if(rows.length > 1){
        rows.pop();
        renderTable();
    }
});

renderTable();
</script>
