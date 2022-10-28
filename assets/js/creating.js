const OPTION_TEMPLATE = `<input type="text" class="px-2 py-1 ring-2 ring-slate-300 mb-5 text-lg rounded-2xl focus:ring-pink-600">`

const createOption = () => {
    const list = document.getElementById('option-list')
    const newElement = document.createElement('ul')
    newElement.innerHTML = OPTION_TEMPLATE
    console.log(list)
    list.append(newElement)
    return false;
}

const submitForm = () => {
    const title = document.getElementById('title').value
    const list = document.getElementById('option-list')
    const options = [];

    for (let child of list.children) {
        const name = child.children[0].value;
        if (name) options.push(name)
    }

    document.getElementById('options').value = options.join(',')
    window.location.href = `/create_poll.php?title=${title}&options=${options.join(',')}`
    return true;
}
