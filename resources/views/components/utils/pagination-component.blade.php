{{-- paginate horizontal right --}}
<div class="flex justify-end items-center mt-4">
    <div id="paginate" class="flex"></div>
</div>

<script>
    // set pagination with total pages
    function setPagination(response) {
        let html = ''

        for (let i = 1; i <= response.data.last_page; i++) {
            if (i === response.data.current_page) {
                html += `<button
                                class="rounded text-white bg-blue-500 px-4 py-1 text-sm ml-2 flex items-center flex-row"
                            >
                                <span>${i}</span>
                            </button>`
            } else {
                html += `<button
                                onclick="searchData(${i})"
                                class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm ml-2 cursor-pointer"
                            >
                                ${i}
                            </button>`
            }
        }

        $('#paginate').html(html)
    }
</script>
