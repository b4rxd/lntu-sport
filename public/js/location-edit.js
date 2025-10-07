let daysOfWeek = [
    {value: 1, text: 'Понеділок'},
    {value: 2, text: 'Вівторок'},
    {value: 3, text: 'Середа'},
    {value: 4, text: 'Четвер'},
    {value: 5, text: 'П’ятниця'},
    {value: 6, text: 'Субота'},
    {value: 7, text: 'Неділя'}
];

let indexes = { regular: 0, vacation: 0, special: 0 };

let fields = {
    regular: [
        {label: 'День', name: 'day_number', type: 'select', options: daysOfWeek},
        {label: 'Час з', name: 'time_from', type: 'time'},
        {label: 'Час до', name: 'time_till', type: 'time'},
        {label: 'Дата з', name: 'date_from', type: 'date'},
        {label: 'Дата по', name: 'date_till', type: 'date'}
    ],
    vacation: [
        {label: 'День', name: 'day_number', type: 'select', options: daysOfWeek},
        {label: 'Назва', name: 'title', type: 'text'},
        {label: 'Дата з', name: 'date_from', type: 'date'},
        {label: 'Дата по', name: 'date_till', type: 'date'}
    ],
    special: [
        {label: 'Час з', name: 'time_from', type: 'time'},
        {label: 'Час до', name: 'time_till', type: 'time'},
        {label: 'Дата з', name: 'date_from', type: 'date'},
        {label: 'Дата по', name: 'date_till', type: 'date'}
    ]
};

function addItem(type) {
    addItemWithValues(type, {});
}

function addItemWithValues(type, values) {
    let index = indexes[type]++;
    let htmlFields = fields[type].map(f => {
        let val = values[f.name] ?? '';
        let colClass = 'col-md-' + (type === 'vacation' && f.name === 'title' ? 4 : 2);
        if(f.type === 'select') {
            let options = f.options.map(d => `<option value="${d.value}" ${d.value == val ? 'selected' : ''}>${d.text}</option>`).join('');
            return `<div class="${colClass}"><label class="form-label">${f.label}</label><select name="${type}[${index}][${f.name}]" class="form-select" required>${options}</select></div>`;
        } else {
            return `<div class="${colClass}"><label class="form-label">${f.label}</label><input type="${f.type}" name="${type}[${index}][${f.name}]" class="form-control" value="${val}"></div>`;
        }
    }).join('');

    let html = `<div class="border p-3 mb-2 rounded ${type}-item">
                    <div class="row g-2 align-items-end">
                        ${htmlFields}
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="this.closest('.${type}-item').remove()">X</button>
                        </div>
                    </div>
                </div>`;
    document.getElementById(type + '-list').insertAdjacentHTML('beforeend', html);
}


let regularData = window.locationData.regular || [];
let vacationData = window.locationData.vacation || [];
let specialData = window.locationData.special || [];

regularData.forEach(r => addItemWithValues('regular', {
    day_number: r.day_number,
    time_from: r.time_from,
    time_till: r.time_till,
    date_from: r.date_from,
    date_till: r.date_till || ''
}));

vacationData.forEach(v => addItemWithValues('vacation', {
    day_number: v.day_number,
    title: v.title,
    date_from: v.date_from,
    date_till: v.date_till || ''
}));

specialData.forEach(s => addItemWithValues('special', {
    time_from: s.time_from,
    time_till: s.time_till,
    date_from: s.date_from,
    date_till: s.date_till || ''
}));
