let daysOfWeek = ['Понеділок','Вівторок','Середа','Четвер','П’ятниця','Субота','Неділя'];

let indexes = { regular: 0 };

let fields = {
    regular: [
            {label: 'День', name: 'day_number', type: 'day'},
            {label: 'Час з', name: 'time_from', type: 'time'},
            {label: 'Час до', name: 'time_till', type: 'time'}
        ]
};

function addItemWithValues(type, values = {}) {
let index = indexes[type]++;
if (index >= 7) return;

let htmlFields = fields[type].map(f => {
    let val = values[f.name] ?? '';
    let colClass = "col-md-3";

    if(f.type === 'day') {
        return `<div class="${colClass}">
                    <label class="form-label">${f.label}</label>
                    <input type="text" class="form-control" value="${daysOfWeek[index]}" readonly>
                    <input type="hidden" name="${type}[${index}][${f.name}]" value="${index+1}">
                </div>`;
    }

    return `<div class="${colClass}">
                <label class="form-label">${f.label}</label>
                <input type="${f.type}" name="${type}[${index}][${f.name}]" class="form-control" value="${val}" required step="60">
            </div>`;
}).join('');

let html =
    `<div class="border p-3 mb-2 rounded ${type}-item">
        <div class="row g-2 align-items-end">
            ${htmlFields}
            <div class="col-md-1">
                <button type="button" class="btn btn-danger"
                    onclick="this.closest('.${type}-item').remove(); updateIndexes();">X</button>
            </div>
        </div>
    </div>`;

document.getElementById(type + "-list").insertAdjacentHTML("beforeend", html);

}

function updateIndexes() {
    let items = document.querySelectorAll('#regular-list .regular-item');
    items.forEach((item, i) => {
        let dayInput = item.querySelector('input[type=hidden]');
        dayInput.value = i + 1;
        item.querySelector('input[readonly]').value = daysOfWeek[i];
    });
}

function addItem(type) {
    addItemWithValues(type, {});
}